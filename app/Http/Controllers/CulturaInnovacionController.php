<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\CulturaInnovacion;
use App\Models\Convocatoria;
use App\Models\MesaSectorial;
use App\Models\Tecnoacademia;
use App\Http\Requests\CulturaInnovacionRequest;
use App\Models\RolSennova;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Traits\ProyectoRolSennovaValidationTrait;
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
        $this->authorize('formular-proyecto-cultura-innovacion');

        return Inertia::render('Convocatorias/Proyectos/CulturaInnovacion/Index', [
            'convocatoria'      => $convocatoria->only('id'),
            'filters'           => request()->all('search'),
            'culturaInnovacion' => CulturaInnovacion::getProyectosPorRol($convocatoria),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Convocatoria $convocatoria)
    {
        $this->authorize('formular-proyecto-cultura-innovacion');

        return Inertia::render('Convocatorias/Proyectos/CulturaInnovacion/Create', [
            'convocatoria' => $convocatoria->only('id', 'min_fecha_inicio_proyectos', 'max_fecha_finalizacion_proyectos'),
            'roles'        => RolSennova::select('id as value', 'nombre as label')->orderBy('nombre', 'ASC')->get(),
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
        $this->authorize('formular-proyecto-cultura-innovacion');

        if (ProyectoRolSennovaValidationTrait::culturaInnovacionNumeroProyectos($request->centro_formacion_id, $request->tipo_proyecto_id)) {
            return redirect()->back()->with('error', 'El centro de formación ya tiene registrado un proyecto de la línea programática 65.');
        };

        $proyecto = new Proyecto();
        $proyecto->centroFormacion()->associate($request->centro_formacion_id);
        $proyecto->tipoProyecto()->associate($request->tipo_proyecto_id);
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
        $culturaInnovacion->resumen                               = 'Por favor diligencie el resumen del proyecto';
        $culturaInnovacion->antecedentes                          = 'Por favor diligencie los antecedentes del proyecto';
        $culturaInnovacion->marco_conceptual                      = 'Por favor diligencie el marco conceptual del proyecto';
        $culturaInnovacion->metodologia                           = 'Por favor diligencie la metodología del proyecto';
        $culturaInnovacion->propuesta_sostenibilidad              = 'Por favor diligencie la propuesta de sotenibilidad del proyecto';
        $culturaInnovacion->bibliografia                          = 'Por favor diligencie la bibliografía';
        $culturaInnovacion->numero_aprendices                     = 0;
        $culturaInnovacion->impacto_municipios                    = 'Describa el beneficio en los municipios';
        $culturaInnovacion->impacto_centro_formacion              = 'Describa el impacto en el centro de formación';

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
                'es_autor'          => true,
                'es_formulador'     => true,
                'cantidad_meses'    => $request->cantidad_meses,
                'cantidad_horas'    => $request->cantidad_horas,
                'rol_sennova_id'    => $request->rol_sennova_id,
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
        $this->authorize('view', [CulturaInnovacion::class, $culturaInnovacion]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CulturaInnovacion  $culturaInnovacion
     * @return \Illuminate\Http\Response
     */
    public function edit(Convocatoria $convocatoria, CulturaInnovacion $culturaInnovacion)
    {
        $this->authorize('validar-autor', [$culturaInnovacion->proyecto]);

        $culturaInnovacion->codigo_linea_programatica = $culturaInnovacion->proyecto->tipoProyecto->lineaProgramatica->codigo;
        $culturaInnovacion->precio_proyecto           = $culturaInnovacion->proyecto->precioProyecto;

        return Inertia::render('Convocatorias/Proyectos/CulturaInnovacion/Edit', [
            'convocatoria'                      => $convocatoria->only('id', 'min_fecha_inicio_proyectos', 'max_fecha_finalizacion_proyectos'),
            'culturaInnovacion'                 => $culturaInnovacion,
            'mesasSectorialesRelacionadas'      => $culturaInnovacion->mesasSectoriales()->pluck('id'),
            'lineasTecnologicasRelacionadas'    => $culturaInnovacion->tecnoacademiaLineasTecnologicas()->pluck('id'),
            'tecnoacademia'                     => $culturaInnovacion->tecnoacademiaLineasTecnologicas()->first() ? $culturaInnovacion->tecnoacademiaLineasTecnologicas()->first()->tecnoacademia->only('id', 'nombre') : null,
            'mesasSectoriales'                  => MesaSectorial::select('id', 'nombre')->get('id'),
            'tecnoacademias'                    => TecnoAcademia::select('id as value', 'nombre as label')->get(),
            'opcionesAplicaNoAplica'            => json_decode(Storage::get('json/opciones-aplica-no-aplica.json'), true),
            'proyectoMunicipios'                => $culturaInnovacion->proyecto->municipios()->select('municipios.id as value', 'municipios.nombre as label', 'regionales.nombre as group')->join('regionales', 'regionales.id', 'municipios.regional_id')->get(),
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
        $this->authorize('validar-autor', [$culturaInnovacion->proyecto]);

        $culturaInnovacion->titulo                                = $request->titulo;
        $culturaInnovacion->fecha_inicio                          = $request->fecha_inicio;
        $culturaInnovacion->fecha_finalizacion                    = $request->fecha_finalizacion;
        $culturaInnovacion->video                                 = $request->video;
        $culturaInnovacion->justificacion_industria_4             = $request->justificacion_industria_4;
        $culturaInnovacion->justificacion_economia_naranja        = $request->justificacion_economia_naranja;
        $culturaInnovacion->justificacion_politica_discapacidad   = $request->justificacion_politica_discapacidad;
        $culturaInnovacion->resumen                               = $request->resumen;
        $culturaInnovacion->antecedentes                          = $request->antecedentes;
        $culturaInnovacion->marco_conceptual                      = $request->marco_conceptual;
        $culturaInnovacion->metodologia                           = $request->metodologia;
        $culturaInnovacion->propuesta_sostenibilidad              = $request->propuesta_sostenibilidad;
        $culturaInnovacion->bibliografia                          = $request->bibliografia;
        $culturaInnovacion->numero_aprendices                     = $request->numero_aprendices;
        $culturaInnovacion->impacto_municipios                    = $request->impacto_municipios;
        $culturaInnovacion->impacto_centro_formacion              = $request->impacto_centro_formacion;

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

        $culturaInnovacion->save();

        $request->relacionado_mesas_sectoriales == 1 ? $culturaInnovacion->mesasSectoriales()->sync($request->mesa_sectorial_id) : $culturaInnovacion->mesasSectoriales()->detach();
        $request->relacionado_tecnoacademia == 1 ? $culturaInnovacion->tecnoacademiaLineasTecnologicas()->sync($request->linea_tecnologica_id) : $culturaInnovacion->tecnoacademiaLineasTecnologicas()->detach();


        return redirect()->back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CulturaInnovacion  $culturaInnovacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Convocatoria $convocatoria, CulturaInnovacion $culturaInnovacion)
    {
        $this->authorize('validar-autor', [$culturaInnovacion->proyecto]);

        if (!Hash::check($request->password, Auth::user()->password)) {
            return redirect()->back()
                ->withErrors(['password' => __('The password is incorrect.')]);
        }

        $culturaInnovacion->proyecto()->delete();

        return redirect()->route('convocatorias.cultura-innovacion.index', [$convocatoria])->with('success', 'El recurso se ha eliminado correctamente.');
    }
}
