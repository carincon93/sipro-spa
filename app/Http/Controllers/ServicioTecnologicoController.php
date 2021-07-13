<?php

namespace App\Http\Controllers;

use App\Models\ServicioTecnologico;
use App\Http\Controllers\Controller;
use App\Http\Requests\ServicioTecnologicoRequest;
use App\Models\Convocatoria;
use App\Models\Proyecto;
use App\Models\TipoProyectoSt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
            'convocatoria'      => $convocatoria->only('id', 'min_fecha_inicio_proyectos_st', 'max_fecha_finalizacion_proyectos_st'),
            'roles'             => collect(json_decode(Storage::get('json/roles-sennova-st.json'), true)),
            'tiposProyectoSt'   => TipoProyectoSt::selectRaw("tipos_proyecto_st.id as value, CASE tipos_proyecto_st.tipo_proyecto
                WHEN '1' THEN	concat(centros_formacion.nombre, chr(10), '∙ Tipo de proyecto: A', chr(10), '∙ Mesa técnica: ', mesas_tecnicas.nombre)
                WHEN '2' THEN	concat(centros_formacion.nombre, chr(10), '∙ Tipo de proyecto: B', chr(10), '∙ Mesa técnica: ', mesas_tecnicas.nombre)
            END as label")->join('centros_formacion', 'tipos_proyecto_st.centro_formacion_id', 'centros_formacion.id')->join('mesas_tecnicas', 'tipos_proyecto_st.mesa_tecnica_id', 'mesas_tecnicas.id')->where('centros_formacion.regional_id', auth()->user()->centroFormacion->regional_id)->get()
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
        $proyecto->lineaProgramatica()->associate(10);
        $proyecto->convocatoria()->associate($convocatoria);
        $proyecto->save();

        $servicioTecnologico = new ServicioTecnologico();
        $servicioTecnologico->titulo                        = $request->titulo;
        $servicioTecnologico->fecha_inicio                  = $request->fecha_inicio;
        $servicioTecnologico->fecha_finalizacion            = $request->fecha_finalizacion;
        $servicioTecnologico->max_meses_ejecucion           = $request->max_meses_ejecucion;

        $servicioTecnologico->resumen                               = '';
        $servicioTecnologico->antecedentes                          = '';
        $servicioTecnologico->objetivo_general                      = null;
        $servicioTecnologico->problema_central                      = null;
        $servicioTecnologico->justificacion_problema                = null;
        $servicioTecnologico->identificacion_problema               = null;
        $servicioTecnologico->pregunta_formulacion_problema         = '';
        $servicioTecnologico->metodologia                           = '';
        $servicioTecnologico->propuesta_sostenibilidad              = '';
        $servicioTecnologico->bibliografia                          = '';

        $servicioTecnologico->tipoProyectoSt()->associate($request->tipo_proyecto_st_id);

        $proyecto->servicioTecnologico()->save($servicioTecnologico);


        $proyecto->participantes()->attach(
            Auth::user()->id,
            [
                'es_formulador'     => true,
                'cantidad_meses'    => $request->max_meses_ejecucion,
                'cantidad_horas'    => 48,
                'rol_sennova'       => $request->rol_sennova,
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

        $servicioTecnologico->codigo_linea_programatica = $servicioTecnologico->proyecto->lineaProgramatica->codigo;
        $servicioTecnologico->precio_proyecto           = $servicioTecnologico->proyecto->precioProyecto;
        $servicioTecnologico->proyecto->centroFormacion;

        return Inertia::render('Convocatorias/Proyectos/ServiciosTecnologicos/Edit', [
            'convocatoria'          => $convocatoria->only('id', 'min_fecha_inicio_proyectos_st', 'max_fecha_finalizacion_proyectos_st'),
            'servicioTecnologico'   => $servicioTecnologico,
            'tiposProyectoSt'       => TipoProyectoSt::selectRaw("tipos_proyecto_st.id as value, CASE tipos_proyecto_st.tipo_proyecto
                WHEN '1' THEN	concat(centros_formacion.nombre, chr(10), '∙ Tipo de proyecto: A', chr(10), '∙ Mesa técnica: ', mesas_tecnicas.nombre)
                WHEN '2' THEN	concat(centros_formacion.nombre, chr(10), '∙ Tipo de proyecto: B', chr(10), '∙ Mesa técnica: ', mesas_tecnicas.nombre)
            END as label")->join('centros_formacion', 'tipos_proyecto_st.centro_formacion_id', 'centros_formacion.id')->join('mesas_tecnicas', 'tipos_proyecto_st.mesa_tecnica_id', 'mesas_tecnicas.id')->where('centros_formacion.regional_id', auth()->user()->centroFormacion->regional_id)->get()
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

        $servicioTecnologico->titulo                        = $request->titulo;
        $servicioTecnologico->resumen                       = $request->resumen;
        $servicioTecnologico->antecedentes                  = $request->antecedentes;
        $servicioTecnologico->metodologia                   = $request->metodologia;
        $servicioTecnologico->fecha_inicio                  = $request->fecha_inicio;
        $servicioTecnologico->fecha_finalizacion            = $request->fecha_finalizacion;
        $servicioTecnologico->max_meses_ejecucion           = $request->max_meses_ejecucion;
        $servicioTecnologico->identificacion_problema       = $request->identificacion_problema;
        $servicioTecnologico->pregunta_formulacion_problema = $request->pregunta_formulacion_problema;
        $servicioTecnologico->justificacion_problema        = $request->justificacion_problema;
        $servicioTecnologico->bibliografia                  = $request->bibliografia;

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

    /**
     * updateEspecificacionesInfraestructura
     *
     * @param  mixed $request
     * @param  mixed $convocatoria
     * @param  mixed $servicioTecnologico
     * @return void
     */
    public function updateEspecificacionesInfraestructura(Request $request, Convocatoria $convocatoria, ServicioTecnologico $servicioTecnologico)
    {
        $this->authorize('modificar-proyecto-autor', [$servicioTecnologico->proyecto]);

        $request->validate([
            'especificaciones_area'     => 'required|string|max:40000',
            'infraestructura_adecuada'  => 'required|boolean',
            'video'                     => 'nullable|max:255|url',
        ]);

        $servicioTecnologico->update([
            'especificaciones_area'     => $request->especificaciones_area,
            'infraestructura_adecuada'  => $request->infraestructura_adecuada,
            'video'                     => $request->video
        ]);

        return redirect()->back()->with('success', 'El recurso se ha guardado correctamente.');
    }
}
