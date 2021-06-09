<?php

namespace App\Http\Controllers;

use App\Models\ServicioTecnologico;
use App\Http\Controllers\Controller;
use App\Http\Requests\ServicioTecnologicoRequest;
use App\Models\Convocatoria;
use App\Models\MesaTecnica;
use App\Models\Proyecto;
use App\Models\RolSennova;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ServicioTecnologicoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Convocatoria $convocatoria)
    {
        $this->authorize('viewAny', [ServicioTecnologico::class]);

        return Inertia::render('Convocatorias/Proyectos/ServiciosTecnologicos/Index', [
            'convocatoria'          => $convocatoria,
            'filters'               => request()->all('search'),
            'serviciosTecnologicos' => ServicioTecnologico::orderBy('titulo', 'ASC')
                ->filterServicioTecnologico(request()->only('search'))->paginate()->appends(['search' => request()->search]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Convocatoria $convocatoria)
    {
        $this->authorize('create', [ServicioTecnologico::class]);

        return Inertia::render('Convocatorias/Proyectos/ServiciosTecnologicos/Create', [
            'convocatoria'  => $convocatoria->only('id', 'min_fecha_inicio_proyectos', 'max_fecha_finalizacion_proyectos'),
            'mesasTecnicas' => MesaTecnica::select('id as value', 'nombre as label')->orderBy('nombre', 'ASC')->get(),
            'roles'         => RolSennova::select('id as value', 'nombre as label')->orderBy('nombre', 'ASC')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ServicioTecnologicoRequest $request, Convocatoria $convocatoria)
    {
        $this->authorize('create', [ServicioTecnologico::class]);

        $proyecto = new Proyecto();
        $proyecto->centroFormacion()->associate($request->centro_formacion_id);
        $proyecto->tipoProyecto()->associate($request->tipo_proyecto_id);
        $proyecto->convocatoria()->associate($convocatoria);
        $proyecto->save();

        $servicioTecnologico = new ServicioTecnologico();
        $servicioTecnologico->titulo                        = $request->titulo;
        $servicioTecnologico->titulo_proyecto_articulado    = $request->titulo_proyecto_articulado;
        $servicioTecnologico->fecha_inicio                  = $request->fecha_inicio;
        $servicioTecnologico->fecha_finalizacion            = $request->fecha_finalizacion;

        $servicioTecnologico->video                                 = null;
        $servicioTecnologico->justificacion_industria_4             = null;
        $servicioTecnologico->justificacion_economia_naranja        = null;
        $servicioTecnologico->justificacion_politica_discapacidad   = null;
        $servicioTecnologico->resumen                               = 'Por favor diligencie el resumen del proyecto';
        $servicioTecnologico->antecedentes                          = 'Por favor diligencie los antecedentes del proyecto';
        $servicioTecnologico->objetivo_general                      = null;
        $servicioTecnologico->planteamiento_problema                = null;
        $servicioTecnologico->justificacion_problema                = null;
        $servicioTecnologico->metodologia                           = 'Por favor diligencie la metodología del proyecto';
        $servicioTecnologico->propuesta_sostenibilidad              = 'Por favor diligencie la propuesta de sotenibilidad del proyecto';
        $servicioTecnologico->bibliografia                          = 'Por favor diligencie la bibliografía';
        $servicioTecnologico->numero_aprendices                     = 0;
        $servicioTecnologico->impacto_centro_formacion              = 'Describa el beneficio en los municipios';
        $servicioTecnologico->pregunta_formulacion_problema         = 'Describa la pregunta de la formulación del problema';
        $servicioTecnologico->impacto_centro_formacion              = 'Describa el impacto en el centro de formación';
        $servicioTecnologico->infraestructura_adecuada              = false;
        $servicioTecnologico->especificaciones_area                 = 'Describa las especificaciones del área';
        $servicioTecnologico->bibliografia                          = 'Por favor diligencie la bibliografía';
        $servicioTecnologico->video                                 = 'https://www.youtube.com/';

        $servicioTecnologico->actividadEconomica()->associate($request->actividad_economica_id);
        $servicioTecnologico->disciplinaSubareaConocimiento()->associate($request->disciplina_subarea_conocimiento_id);
        $servicioTecnologico->tematicaEstrategica()->associate($request->tematica_estrategica_id);
        $servicioTecnologico->lineaProgramatica()->associate($request->linea_programatica_id);
        $servicioTecnologico->redConocimiento()->associate($request->red_conocimiento_id);
        $servicioTecnologico->temaPriorizado()->associate($request->tema_priorizado_id);

        $proyecto->servicioTecnologico()->save($servicioTecnologico);

        $proyecto->participantes()->attach(
            Auth::user()->id,
            [
                'es_autor'          => true,
                'cantidad_meses'    => $request->cantidad_meses,
                'cantidad_horas'    => $request->cantidad_horas,
                'rol_sennova_id'    => $request->rol_sennova_id,
            ]
        );

        return redirect()->route('convocatorias.servicios-tecnologicos.edit', [$convocatoria, $servicioTecnologico])->with('success', 'El recurso se ha creado correctamente. Por favor continue diligenciando la información.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ServicioTecnologico  $servicioTecnologico
     * @return \Illuminate\Http\Response
     */
    public function show(Convocatoria $convocatoria, ServicioTecnologico $servicioTecnologico)
    {
        $this->authorize('view', [ServicioTecnologico::class, $servicioTecnologico]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ServicioTecnologico  $servicioTecnologico
     * @return \Illuminate\Http\Response
     */
    public function edit(Convocatoria $convocatoria, ServicioTecnologico $servicioTecnologico)
    {
        $this->authorize('update', [ServicioTecnologico::class, $servicioTecnologico]);

        $servicioTecnologico->codigo_linea_programatica = $servicioTecnologico->proyecto->tipoProyecto->lineaProgramatica->codigo;
        $servicioTecnologico->precio_proyecto           = $servicioTecnologico->proyecto->precioProyecto;
        $servicioTecnologico->load('temaPriorizado.sectorProductivo', 'temaPriorizado.mesaTecnica');

        return Inertia::render('Convocatorias/Proyectos/ServiciosTecnologicos/Edit', [
            'convocatoria'          => $convocatoria->only('id', 'min_fecha_inicio_proyectos', 'max_fecha_finalizacion_proyectos'),
            'servicioTecnologico'   => $servicioTecnologico,
            'mesasTecnicas'         => MesaTecnica::select('id as value', 'nombre as label')->orderBy('nombre', 'ASC')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ServicioTecnologico  $servicioTecnologico
     * @return \Illuminate\Http\Response
     */
    public function update(ServicioTecnologicoRequest $request, Convocatoria $convocatoria, ServicioTecnologico $servicioTecnologico)
    {
        $this->authorize('update', [ServicioTecnologico::class, $servicioTecnologico]);

        $servicioTecnologico->titulo_proyecto_articulado            = $request->titulo_proyecto_articulado;
        $servicioTecnologico->titulo                                = $request->titulo;
        $servicioTecnologico->justificacion_industria_4             = $request->justificacion_industria_4;
        $servicioTecnologico->justificacion_economia_naranja        = $request->justificacion_economia_naranja;
        $servicioTecnologico->justificacion_politica_discapacidad   = $request->justificacion_politica_discapacidad;
        $servicioTecnologico->resumen                               = $request->resumen;
        $servicioTecnologico->antecedentes                          = $request->antecedentes;
        $servicioTecnologico->planteamiento_problema                = $request->planteamiento_problema;
        $servicioTecnologico->justificacion_problema                = $request->justificacion_problema;
        $servicioTecnologico->pregunta_formulacion_problema         = $request->pregunta_formulacion_problema;
        $servicioTecnologico->objetivo_general                      = $request->objetivo_general;
        $servicioTecnologico->metodologia                           = $request->metodologia;
        $servicioTecnologico->numero_aprendices                     = $request->numero_aprendices;
        $servicioTecnologico->fecha_inicio                          = $request->fecha_inicio;
        $servicioTecnologico->fecha_finalizacion                    = $request->fecha_finalizacion;
        $servicioTecnologico->propuesta_sostenibilidad              = $request->propuesta_sostenibilidad;
        $servicioTecnologico->impacto_centro_formacion              = $request->impacto_centro_formacion;
        $servicioTecnologico->infraestructura_adecuada              = $request->infraestructura_adecuada;
        $servicioTecnologico->especificaciones_area                 = $request->especificaciones_area;
        $servicioTecnologico->bibliografia                          = $request->bibliografia;
        $servicioTecnologico->video                                 = $request->video;

        $servicioTecnologico->actividadEconomica()->associate($request->actividad_economica_id);
        $servicioTecnologico->disciplinaSubareaConocimiento()->associate($request->disciplina_subarea_conocimiento_id);
        $servicioTecnologico->tematicaEstrategica()->associate($request->tematica_estrategica_id);
        $servicioTecnologico->lineaProgramatica()->associate($request->linea_programatica_id);
        $servicioTecnologico->redConocimiento()->associate($request->red_conocimiento_id);
        $servicioTecnologico->temaPriorizado()->associate($request->tema_priorizado_id);

        $servicioTecnologico->proyecto()->update(['tipo_proyecto_id' => $request->tipo_proyecto_id]);
        $servicioTecnologico->proyecto()->update(['centro_formacion_id' => $request->centro_formacion_id]);

        $servicioTecnologico->save();

        return redirect()->back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ServicioTecnologico  $servicioTecnologico
     * @return \Illuminate\Http\Response
     */
    public function destroy(Convocatoria $convocatoria, ServicioTecnologico $servicioTecnologico)
    {
        $this->authorize('delete', [ServicioTecnologico::class, $servicioTecnologico]);

        $servicioTecnologico->delete();

        return redirect()->route('convocatorias.servicios-tecnologicos.index', [$convocatoria])->with('success', 'El recurso se ha eliminado correctamente.');
    }
}
