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
        $this->authorize('formular-proyecto');

        return Inertia::render('Convocatorias/Proyectos/ServiciosTecnologicos/Index', [
            'convocatoria'          => $convocatoria,
            'filters'               => request()->all('search'),
            'serviciosTecnologicos' => ServicioTecnologico::getProyectosPorRol($convocatoria)->appends(['search' => request()->search]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Convocatoria $convocatoria)
    {
        $this->authorize('formular-proyecto');

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
        $this->authorize('formular-proyecto');

        $proyecto = new Proyecto();
        $proyecto->centroFormacion()->associate($request->centro_formacion_id);
        $proyecto->tipoProyecto()->associate($request->tipo_proyecto_id);
        $proyecto->convocatoria()->associate($convocatoria);
        $proyecto->save();

        $servicioTecnologico = new ServicioTecnologico();
        $servicioTecnologico->titulo                        = $request->titulo;
        $servicioTecnologico->fecha_inicio                  = $request->fecha_inicio;
        $servicioTecnologico->fecha_finalizacion            = $request->fecha_finalizacion;
        $servicioTecnologico->max_meses_ejecucion           = $request->max_meses_ejecucion;

        $servicioTecnologico->resumen                               = 'Por favor diligencie el resumen del proyecto';
        $servicioTecnologico->antecedentes                          = 'Por favor diligencie los antecedentes del proyecto';
        $servicioTecnologico->objetivo_general                      = null;
        $servicioTecnologico->problema_central                = null;
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

        $servicioTecnologico->mesaTecnicaSectorProductivo()->associate($request->mesa_tecnica_sector_productivo_id);

        $proyecto->servicioTecnologico()->save($servicioTecnologico);

        $proyecto->participantes()->attach(
            Auth::user()->id,
            [
                'es_formulador'     => true,
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
        $this->authorize('visualizar-proyecto-autor', [$servicioTecnologico->proyecto]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ServicioTecnologico  $servicioTecnologico
     * @return \Illuminate\Http\Response
     */
    public function edit(Convocatoria $convocatoria, ServicioTecnologico $servicioTecnologico)
    {
        $this->authorize('visualizar-proyecto-autor', [$servicioTecnologico->proyecto]);

        $servicioTecnologico->codigo_linea_programatica = $servicioTecnologico->proyecto->tipoProyecto->lineaProgramatica->codigo;
        $servicioTecnologico->precio_proyecto           = $servicioTecnologico->proyecto->precioProyecto;
        $servicioTecnologico->load('mesaTecnicaSectorProductivo.mesaTecnica', 'mesaTecnicaSectorProductivo.sectorProductivo');

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
        $this->authorize('modificar-proyecto-autor', [$servicioTecnologico->proyecto]);

        $servicioTecnologico->titulo                                = $request->titulo;
        $servicioTecnologico->resumen                               = $request->resumen;
        $servicioTecnologico->antecedentes                          = $request->antecedentes;
        $servicioTecnologico->metodologia                           = $request->metodologia;
        $servicioTecnologico->fecha_inicio                          = $request->fecha_inicio;
        $servicioTecnologico->fecha_finalizacion                    = $request->fecha_finalizacion;
        $servicioTecnologico->max_meses_ejecucion                   = $request->max_meses_ejecucion;
        $servicioTecnologico->especificaciones_area                 = $request->especificaciones_area;
        $servicioTecnologico->bibliografia                          = $request->bibliografia;

        $servicioTecnologico->mesaTecnicaSectorProductivo()->associate($request->mesa_tecnica_sector_productivo_id);

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
        $this->authorize('modificar-proyecto-autor', [$servicioTecnologico->proyecto]);

        $servicioTecnologico->proyecto()->delete();

        return redirect()->route('convocatorias.servicios-tecnologicos.index', [$convocatoria])->with('success', 'El recurso se ha eliminado correctamente.');
    }
}
