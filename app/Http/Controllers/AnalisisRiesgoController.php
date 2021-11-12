<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnalisisRiesgoRequest;
use App\Models\Convocatoria;
use App\Models\Proyecto;
use App\Models\AnalisisRiesgo;
use App\Models\Evaluacion\CulturaInnovacionEvaluacion;
use App\Models\Evaluacion\Evaluacion;
use App\Models\Evaluacion\IdiEvaluacion;
use App\Models\Evaluacion\ServicioTecnologicoEvaluacion;
use App\Models\Evaluacion\TaEvaluacion;
use App\Models\Evaluacion\TpEvaluacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AnalisisRiesgoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);

        $proyecto->load('evaluaciones.idiEvaluacion');
        $proyecto->load('evaluaciones.taEvaluacion');

        $proyecto->codigo_linea_programatica = $proyecto->lineaProgramatica->codigo;

        return Inertia::render('Convocatorias/Proyectos/AnalisisRiesgo/Index', [
            'convocatoria'    => $convocatoria->only('id', 'fase_formateada', 'fase', 'mostrar_recomendaciones'),
            'proyecto'        => $proyecto->only('id', 'codigo_linea_programatica', 'precio_proyecto', 'modificable', 'en_subsanacion', 'evaluaciones', 'mostrar_recomendaciones'),
            'filters'         => request()->all('search'),
            'analisisRiesgos' => AnalisisRiesgo::where('proyecto_id', $proyecto->id)->orderBy('descripcion', 'ASC')
                ->filterAnalisisRiesgo(request()->only('search'))->paginate()->appends(['search' => request()->search]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);

        return Inertia::render('Convocatorias/Proyectos/AnalisisRiesgo/Create', [
            'convocatoria'          => $convocatoria,
            'proyecto'              => $proyecto,
            'nivelesRiesgo'         => json_decode(Storage::get('json/niveles-riesgo.json'), true),
            'tiposRiesgo'           => json_decode(Storage::get('json/tipos-riesgo.json'), true),
            'probabilidadesRiesgo'  => json_decode(Storage::get('json/probabilidades-riesgo.json'), true),
            'impactosRiesgo'        => json_decode(Storage::get('json/impactos-riesgo.json'), true)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AnalisisRiesgoRequest $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        $analisisRiesgo = new AnalisisRiesgo();
        $analisisRiesgo->nivel               = $request->nivel;
        $analisisRiesgo->tipo                = $request->tipo;
        $analisisRiesgo->descripcion         = $request->descripcion;
        $analisisRiesgo->probabilidad        = $request->probabilidad;
        $analisisRiesgo->impacto             = $request->impacto;
        $analisisRiesgo->efectos             = $request->efectos;
        $analisisRiesgo->medidas_mitigacion  = $request->medidas_mitigacion;
        $analisisRiesgo->proyecto()->associate($proyecto);

        $analisisRiesgo->save();

        return redirect()->route('convocatorias.proyectos.analisis-riesgos.index', [$convocatoria, $proyecto])->with('success', 'El recurso se ha creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AnalisisRiesgo  $analisisRiesgo
     * @return \Illuminate\Http\Response
     */
    public function show(Convocatoria $convocatoria, Proyecto $proyecto, AnalisisRiesgo $analisisRiesgo)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);

        return Inertia::render('Convocatorias/Proyectos/AnalisisRiesgo/Show', [
            'analisisRiesgo' => $analisisRiesgo
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AnalisisRiesgo  $analisisRiesgo
     * @return \Illuminate\Http\Response
     */
    public function edit(Convocatoria $convocatoria, Proyecto $proyecto, AnalisisRiesgo $analisisRiesgo)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);

        return Inertia::render('Convocatorias/Proyectos/AnalisisRiesgo/Edit', [
            'convocatoria'         => $convocatoria->only('id', 'fase_formateada', 'fase'),
            'proyecto'             => $proyecto,
            'analisisRiesgo'       => $analisisRiesgo,
            'nivelesRiesgo'        => json_decode(Storage::get('json/niveles-riesgo.json'), true),
            'tiposRiesgo'          => json_decode(Storage::get('json/tipos-riesgo.json'), true),
            'probabilidadesRiesgo' => json_decode(Storage::get('json/probabilidades-riesgo.json'), true),
            'impactosRiesgo'       => json_decode(Storage::get('json/impactos-riesgo.json'), true)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AnalisisRiesgo  $analisisRiesgo
     * @return \Illuminate\Http\Response
     */
    public function update(AnalisisRiesgoRequest $request, Convocatoria $convocatoria, Proyecto $proyecto, AnalisisRiesgo $analisisRiesgo)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        $analisisRiesgo->nivel              = $request->nivel;
        $analisisRiesgo->tipo               = $request->tipo;
        $analisisRiesgo->descripcion        = $request->descripcion;
        $analisisRiesgo->probabilidad       = $request->probabilidad;
        $analisisRiesgo->impacto            = $request->impacto;
        $analisisRiesgo->efectos            = $request->efectos;
        $analisisRiesgo->medidas_mitigacion = $request->medidas_mitigacion;
        $analisisRiesgo->proyecto()->associate($proyecto);

        $analisisRiesgo->save();

        return back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AnalisisRiesgo  $analisisRiesgo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Convocatoria $convocatoria, Proyecto $proyecto, AnalisisRiesgo $analisisRiesgo)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        $analisisRiesgo->delete();

        return redirect()->route('convocatorias.proyectos.analisis-riesgos.index', [$convocatoria, $proyecto])->with('success', 'El recurso se ha eliminado correctamente.');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showAnalisisRiesgosEvaluacion(Convocatoria $convocatoria, Evaluacion $evaluacion)
    {
        $this->authorize('visualizar-evaluacion-autor', $evaluacion);

        $evaluacion->proyecto->codigo_linea_programatica = $evaluacion->proyecto->lineaProgramatica->codigo;

        switch ($evaluacion->proyecto) {
            case $evaluacion->proyecto->idi()->exists():
                $evaluacion->idiEvaluacion;
                $idi = $evaluacion->proyecto->idi;

                $otrasEvaluaciones = IdiEvaluacion::whereHas('evaluacion', function ($query) use ($idi) {
                    $query->where('evaluaciones.proyecto_id', $idi->id)->where('evaluaciones.habilitado', true);
                })->where('idi_evaluaciones.id', '!=', $evaluacion->idiEvaluacion->id)->first();
                break;
            case $evaluacion->proyecto->ta()->exists():
                $evaluacion->taEvaluacion;
                $ta = $evaluacion->proyecto->ta;

                $otrasEvaluaciones = TaEvaluacion::with('evaluacion.evaluador')->whereHas('evaluacion', function ($query) use ($ta) {
                    $query->where('evaluaciones.proyecto_id', $ta->id)->where('evaluaciones.habilitado', true);
                })->where('ta_evaluaciones.id', '!=', $evaluacion->taEvaluacion->id)->get();
                break;
            case $evaluacion->proyecto->tp()->exists():
                $evaluacion->tpEvaluacion;
                $tp = $evaluacion->proyecto->tp;

                $otrasEvaluaciones = TpEvaluacion::whereHas('evaluacion', function ($query) use ($tp) {
                    $query->where('evaluaciones.proyecto_id', $tp->id)->where('evaluaciones.habilitado', true);
                })->where('tp_evaluaciones.id', '!=', $evaluacion->tpEvaluacion->id)->first();
                break;
            case $evaluacion->proyecto->culturaInnovacion()->exists():
                $evaluacion->culturaInnovacionEvaluacion;
                $culturaInnovacion = $evaluacion->proyecto->culturaInnovacion;

                $otrasEvaluaciones = CulturaInnovacionEvaluacion::whereHas('evaluacion', function ($query) use ($culturaInnovacion) {
                    $query->where('evaluaciones.proyecto_id', $culturaInnovacion->id)->where('evaluaciones.habilitado', true);
                })->where('cultura_innovacion_evaluaciones.id', '!=', $evaluacion->culturaInnovacionEvaluacion->id)->first();
                break;
            case $evaluacion->proyecto->servicioTecnologico()->exists():
                $servicioTecnologico = $evaluacion->proyecto->servicioTecnologico;

                $otrasEvaluaciones = ServicioTecnologicoEvaluacion::whereHas('evaluacion', function ($query) use ($servicioTecnologico) {
                    $query->where('evaluaciones.proyecto_id', $servicioTecnologico->id)->where('evaluaciones.habilitado', true);
                })->where('servicios_tecnologicos_evaluaciones.id', '!=', $evaluacion->servicioTecnologicoEvaluacion->id)->first();
                break;
            default:
                break;
        }

        return Inertia::render('Convocatorias/Evaluaciones/AnalisisRiesgo/Index', [
            'convocatoria'      => $convocatoria->only('id', 'fase_formateada', 'fase'),
            'evaluacion'        => $evaluacion,
            'otrasEvaluaciones' => $otrasEvaluaciones,
            'proyecto'          => $evaluacion->proyecto->only('id', 'codigo_linea_programatica', 'precio_proyecto', 'modificable', 'finalizado'),
            'filters'           => request()->all('search'),
            'analisisRiesgos'   => AnalisisRiesgo::where('proyecto_id', $evaluacion->proyecto->id)->orderBy('descripcion', 'ASC')
                ->filterAnalisisRiesgo(request()->only('search'))->paginate()->appends(['search' => request()->search]),
        ]);
    }

    /**
     * updateAnalisisRiesgosEvaluacion
     *
     * @param  mixed $request
     * @param  mixed $convocatoria
     * @param  mixed $evaluacion
     * @return void
     */
    public function updateAnalisisRiesgosEvaluacion(Request $request, Convocatoria $convocatoria, Evaluacion $evaluacion)
    {
        $this->authorize('modificar-evaluacion-autor', $evaluacion);

        switch ($evaluacion) {
            case $evaluacion->idiEvaluacion()->exists():
                $evaluacion->idiEvaluacion()->update([
                    'analisis_riesgos_puntaje'      => $request->analisis_riesgos_puntaje,
                    'analisis_riesgos_comentario'   => $request->analisis_riesgos_requiere_comentario == false ? $request->analisis_riesgos_comentario : null
                ]);
                break;
            case $evaluacion->culturaInnovacionEvaluacion()->exists():
                $evaluacion->culturaInnovacionEvaluacion()->update([
                    'analisis_riesgos_puntaje'      => $request->analisis_riesgos_puntaje,
                    'analisis_riesgos_comentario'   => $request->analisis_riesgos_requiere_comentario == false ? $request->analisis_riesgos_comentario : null
                ]);
                break;

            case $evaluacion->taEvaluacion()->exists():
                $evaluacion->taEvaluacion()->update([
                    'analisis_riesgos_comentario'   => $request->analisis_riesgos_requiere_comentario == false ? $request->analisis_riesgos_comentario : null
                ]);
                break;
            case $evaluacion->tpEvaluacion()->exists():
                $evaluacion->tpEvaluacion()->update([
                    'analisis_riesgos_comentario'   => $request->analisis_riesgos_requiere_comentario == false ? $request->analisis_riesgos_comentario : null
                ]);
                break;

            case $evaluacion->servicioTecnologicoEvaluacion()->exists():
                $evaluacion->servicioTecnologicoEvaluacion()->update([
                    'riesgos_objetivo_general_puntaje'      => $request->riesgos_objetivo_general_puntaje,
                    'riesgos_objetivo_general_comentario'   => $request->riesgos_objetivo_general_requiere_comentario == false ? $request->riesgos_objetivo_general_comentario : null,

                    'riesgos_productos_puntaje'             => $request->riesgos_productos_puntaje,
                    'riesgos_productos_comentario'          => $request->riesgos_productos_requiere_comentario == false ? $request->riesgos_productos_comentario : null,

                    'riesgos_actividades_puntaje'           => $request->riesgos_actividades_puntaje,
                    'riesgos_actividades_comentario'        => $request->riesgos_actividades_requiere_comentario == false ? $request->riesgos_actividades_comentario : null,
                ]);
                break;
            default:
                break;
        }

        $evaluacion->save();

        return back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AnalisisRiesgo  $analisisRiesgo
     * @return \Illuminate\Http\Response
     */
    public function analisisRiesgoEvaluacion(Convocatoria $convocatoria, Evaluacion $evaluacion, AnalisisRiesgo $analisisRiesgo)
    {
        $this->authorize('visualizar-evaluacion-autor', $evaluacion);

        return Inertia::render('Convocatorias/Evaluaciones/AnalisisRiesgo/Edit', [
            'convocatoria'          => $convocatoria->only('id', 'fase_formateada', 'fase'),
            'evaluacion'            => $evaluacion->only('id'),
            'proyecto'              => $evaluacion->proyecto,
            'analisisRiesgo'        => $analisisRiesgo,
            'nivelesRiesgo'         => json_decode(Storage::get('json/niveles-riesgo.json'), true),
            'tiposRiesgo'           => json_decode(Storage::get('json/tipos-riesgo.json'), true),
            'probabilidadesRiesgo'  => json_decode(Storage::get('json/probabilidades-riesgo.json'), true),
            'impactosRiesgo'        => json_decode(Storage::get('json/impactos-riesgo.json'), true)
        ]);
    }
}
