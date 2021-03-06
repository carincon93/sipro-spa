<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\CulturaInnovacion;
use App\Models\Convocatoria;
use App\Models\MesaSectorial;
use App\Models\Tecnoacademia;
use App\Http\Requests\CulturaInnovacionLongColumnRequest;
use App\Http\Requests\CulturaInnovacionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Traits\ProyectoRolSennovaValidationTrait;
use App\Models\CentroFormacion;
use Inertia\Inertia;

class CulturaInnovacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Convocatoria $convocatoria)
    {
        return Inertia::render('Convocatorias/Proyectos/CulturaInnovacion/Index', [
            'convocatoria'      => $convocatoria->only('id', 'esta_activa', 'fase_formateada', 'fase', 'tipo_convocatoria', 'fase'),
            'filters'           => request()->all('search', 'estructuracion_proyectos'),
            'culturaInnovacion' => CulturaInnovacion::getProyectosPorRol($convocatoria)->appends(['search' => request()->search, 'estructuracion_proyectos' => request()->estructuracion_proyectos]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Convocatoria $convocatoria)
    {
        $this->authorize('formular-proyecto', [9, $convocatoria]);

        if (auth()->user()->hasRole(15)) {
            $centrosFormacion = CentroFormacion::selectRaw('centros_formacion.id as value, concat(centros_formacion.nombre, chr(10), \'∙ Código: \', centros_formacion.codigo) as label')->where('centros_formacion.regional_id', auth()->user()->centroFormacion->regional->id)->whereIn('centros_formacion.codigo', [9309, 9503, 9230, 9124, 9525, 9222, 9212, 9116, 9517, 9401, 9403, 9303, 9310, 9529, 9308, 9101])->orderBy('centros_formacion.nombre', 'ASC')->get();
        } else {
            $centrosFormacion = CentroFormacion::selectRaw('centros_formacion.id as value, concat(centros_formacion.nombre, chr(10), \'∙ Código: \', centros_formacion.codigo) as label')->orderBy('centros_formacion.nombre', 'ASC')->get();
        }

        return Inertia::render('Convocatorias/Proyectos/CulturaInnovacion/Create', [
            'convocatoria'      => $convocatoria->only('id', 'esta_activa', 'fase_formateada', 'fase', 'tipo_convocatoria', 'min_fecha_inicio_proyectos_cultura', 'max_fecha_finalizacion_proyectos_cultura', 'fecha_maxima_cultura'),
            'roles'             => collect(json_decode(Storage::get('json/roles-sennova-idi.json'), true)),
            'centrosFormacion'  => $centrosFormacion
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CulturaInnovacionRequest $request, Convocatoria $convocatoria)
    {
        $this->authorize('formular-proyecto', [$request->linea_programatica_id, $convocatoria]);

        if (ProyectoRolSennovaValidationTrait::culturaInnovacionNumeroProyectos($request->centro_formacion_id, $request->linea_programatica_id)) {
            return back()->with('error', 'El centro de formación ya tiene registrado un proyecto de la línea programática 65.');
        };

        $proyecto = new Proyecto();
        $proyecto->centroFormacion()->associate($request->centro_formacion_id);
        $proyecto->lineaProgramatica()->associate($request->linea_programatica_id);
        $proyecto->convocatoria()->associate($convocatoria);
        $proyecto->save();

        $culturaInnovacion = new CulturaInnovacion();
        $culturaInnovacion->titulo                                = $request->titulo;
        $culturaInnovacion->fecha_inicio                          = $request->fecha_inicio;
        $culturaInnovacion->fecha_finalizacion                    = $request->fecha_finalizacion;
        $culturaInnovacion->max_meses_ejecucion                   = $request->max_meses_ejecucion;
        $culturaInnovacion->video                                 = null;
        $culturaInnovacion->justificacion_industria_4             = null;
        $culturaInnovacion->justificacion_economia_naranja        = null;
        $culturaInnovacion->justificacion_politica_discapacidad   = null;
        $culturaInnovacion->resumen                               = '';
        $culturaInnovacion->antecedentes                          = '';
        $culturaInnovacion->marco_conceptual                      = '';
        $culturaInnovacion->metodologia                           = '';
        $culturaInnovacion->propuesta_sostenibilidad              = '';
        $culturaInnovacion->bibliografia                          = '';
        $culturaInnovacion->numero_aprendices                     = 0;
        $culturaInnovacion->impacto_municipios                    = '';
        $culturaInnovacion->impacto_centro_formacion              = '';

        $culturaInnovacion->muestreo                              = 6;
        $culturaInnovacion->actividades_muestreo                  = null;
        $culturaInnovacion->objetivo_muestreo                     = null;
        $culturaInnovacion->recoleccion_especimenes               = 2;

        $culturaInnovacion->relacionado_plan_tecnologico          = 2;
        $culturaInnovacion->relacionado_agendas_competitividad    = 2;
        $culturaInnovacion->relacionado_mesas_sectoriales         = 2;
        $culturaInnovacion->relacionado_tecnoacademia             = 2;

        $culturaInnovacion->lineaInvestigacion()->associate($request->linea_investigacion_id);
        $culturaInnovacion->areaConocimiento()->associate($request->area_conocimiento_id);
        $culturaInnovacion->tematicaEstrategica()->associate($request->tematica_estrategica_id);
        $culturaInnovacion->actividadEconomica()->associate($request->actividad_economica_id);

        $proyecto->culturaInnovacion()->save($culturaInnovacion);

        $proyecto->participantes()->attach(
            Auth::user()->id,
            [
                'es_formulador'     => true,
                'cantidad_meses'    => $request->cantidad_meses,
                'cantidad_horas'    => $request->cantidad_horas,
                'rol_sennova'       => $request->rol_sennova,
            ]
        );

        return redirect()->route('convocatorias.cultura-innovacion.edit', [$convocatoria, $culturaInnovacion])->with('success', 'El recurso se ha creado correctamente. Por favor continue diligenciando la información.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CulturaInnovacion  $culturaInnovacion
     * @return \Illuminate\Http\Response
     */
    public function show(Convocatoria $convocatoria, CulturaInnovacion $culturaInnovacion)
    {
        $this->authorize('visualizar-proyecto-autor', [$culturaInnovacion->proyecto]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CulturaInnovacion  $culturaInnovacion
     * @return \Illuminate\Http\Response
     */
    public function edit(Convocatoria $convocatoria, CulturaInnovacion $culturaInnovacion)
    {
        $this->authorize('visualizar-proyecto-autor', [$culturaInnovacion->proyecto]);

        $culturaInnovacion->load('proyecto.evaluaciones.culturaInnovacionEvaluacion');

        $culturaInnovacion->codigo_linea_programatica = $culturaInnovacion->proyecto->lineaProgramatica->codigo;
        $culturaInnovacion->precio_proyecto           = $culturaInnovacion->proyecto->precioProyecto;
        $culturaInnovacion->proyecto->centroFormacion;

        $culturaInnovacion->mostrar_recomendaciones = $culturaInnovacion->proyecto->mostrar_recomendaciones;
        $culturaInnovacion->mostrar_requiere_subsanacion = $culturaInnovacion->proyecto->mostrar_requiere_subsanacion;

        return Inertia::render('Convocatorias/Proyectos/CulturaInnovacion/Edit', [
            'convocatoria'                              => $convocatoria->only('id', 'esta_activa', 'fase_formateada', 'fase', 'tipo_convocatoria', 'min_fecha_inicio_proyectos_cultura', 'max_fecha_finalizacion_proyectos_cultura', 'fecha_maxima_cultura', 'mostrar_recomendaciones'),
            'culturaInnovacion'                         => $culturaInnovacion,
            'mesasSectorialesRelacionadas'              => $culturaInnovacion->mesasSectoriales()->pluck('id'),
            'lineasTecnoacademiaRelacionadas'           => $culturaInnovacion->tecnoacademiaLineasTecnoacademia()->pluck('id'),
            'tecnoacademia'                             => $culturaInnovacion->tecnoacademiaLineasTecnoacademia()->first() ? $culturaInnovacion->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->only('id', 'nombre') : null,
            'mesasSectoriales'                          => MesaSectorial::select('id', 'nombre')->get('id'),
            'tecnoacademias'                            => TecnoAcademia::select('id as value', 'nombre as label')->get(),
            'opcionesAplicaNoAplica'                    => json_decode(Storage::get('json/opciones-aplica-no-aplica.json'), true),
            'proyectoMunicipios'                        => $culturaInnovacion->proyecto->municipios()->select('municipios.id as value', 'municipios.nombre as label', 'regionales.nombre as group')->join('regionales', 'regionales.id', 'municipios.regional_id')->get(),
            'proyectoProgramasFormacion'                => $culturaInnovacion->proyecto->programasFormacionImpactados()->selectRaw('id as value, concat(programas_formacion.nombre, chr(10), \'∙ Código: \', programas_formacion.codigo) as label')->get(),
            'proyectoProgramasFormacionArticulados'     => $culturaInnovacion->proyecto->programasFormacionArticulados()->selectRaw('id as value, concat(programas_formacion_articulados.nombre, chr(10), \'∙ Código: \', programas_formacion_articulados.codigo) as label')->get(),
            'versiones'                                 => $culturaInnovacion->proyecto->PdfVersiones,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CulturaInnovacion  $culturaInnovacion
     * @return \Illuminate\Http\Response
     */
    public function update(CulturaInnovacionRequest $request, Convocatoria $convocatoria, CulturaInnovacion $culturaInnovacion)
    {
        $this->authorize('modificar-proyecto-autor', [$culturaInnovacion->proyecto]);

        $culturaInnovacion->titulo                                = $request->titulo;
        $culturaInnovacion->fecha_inicio                          = $request->fecha_inicio;
        $culturaInnovacion->fecha_finalizacion                    = $request->fecha_finalizacion;
        $culturaInnovacion->max_meses_ejecucion                   = $request->max_meses_ejecucion;
        $culturaInnovacion->video                                 = $request->video;

        $culturaInnovacion->numero_aprendices                     = $request->numero_aprendices;

        $culturaInnovacion->muestreo                              = $request->muestreo;
        $culturaInnovacion->actividades_muestreo                  = $request->muestreo == 1 ? $request->actividades_muestreo : null;
        $culturaInnovacion->objetivo_muestreo                     = $request->muestreo == 1 ? $request->objetivo_muestreo  : null;
        $culturaInnovacion->recoleccion_especimenes               = $request->recoleccion_especimenes;

        $culturaInnovacion->lineaInvestigacion()->associate($request->linea_investigacion_id);
        $culturaInnovacion->areaConocimiento()->associate($request->area_conocimiento_id);
        $culturaInnovacion->tematicaEstrategica()->associate($request->tematica_estrategica_id);
        $culturaInnovacion->actividadEconomica()->associate($request->actividad_economica_id);

        $culturaInnovacion->relacionado_plan_tecnologico          = $request->relacionado_plan_tecnologico;
        $culturaInnovacion->relacionado_agendas_competitividad    = $request->relacionado_agendas_competitividad;
        $culturaInnovacion->relacionado_mesas_sectoriales         = $request->relacionado_mesas_sectoriales;
        $culturaInnovacion->relacionado_tecnoacademia             = $request->relacionado_tecnoacademia;

        $culturaInnovacion->proyecto->municipios()->sync($request->municipios);
        $culturaInnovacion->proyecto->programasFormacionImpactados()->sync($request->programas_formacion);
        $culturaInnovacion->proyecto->programasFormacionArticulados()->sync($request->programas_formacion_articulados);

        $culturaInnovacion->save();

        $request->relacionado_mesas_sectoriales == 1 ? $culturaInnovacion->mesasSectoriales()->sync($request->mesa_sectorial_id) : $culturaInnovacion->mesasSectoriales()->detach();
        $request->relacionado_tecnoacademia == 1 ? $culturaInnovacion->tecnoacademiaLineasTecnoacademia()->sync($request->linea_tecnologica_id) : $culturaInnovacion->tecnoacademiaLineasTecnoacademia()->detach();


        return back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    public function updateLongColumn(CulturaInnovacionLongColumnRequest $request, Convocatoria $convocatoria, CulturaInnovacion $culturaInnovacion, $column)
    {
        $this->authorize('modificar-proyecto-autor', [$culturaInnovacion->proyecto]);

        $culturaInnovacion->update($request->only($column));

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CulturaInnovacion  $culturaInnovacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Convocatoria $convocatoria, CulturaInnovacion $culturaInnovacion)
    {
        $this->authorize('modificar-proyecto-autor', [$culturaInnovacion->proyecto]);

        if ($convocatoria->fase != 1) {
            return back()->with('error', 'Un proyecto finalizado no se puede eliminar.');
        }

        if (!Hash::check($request->password, Auth::user()->password)) {
            return back()
                ->withErrors(['password' => __('The password is incorrect.')]);
        }

        $culturaInnovacion->proyecto()->delete();

        return redirect()->route('convocatorias.cultura-innovacion.index', [$convocatoria])->with('success', 'El recurso se ha eliminado correctamente.');
    }
}
