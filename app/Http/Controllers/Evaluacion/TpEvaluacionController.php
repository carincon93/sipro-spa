<?php

namespace App\Http\Controllers\Evaluacion;

use App\Models\Evaluacion\TpEvaluacion;
use App\Http\Controllers\Controller;
use App\Http\Requests\Evaluacion\TpEvaluacionRequest;
use App\Models\Convocatoria;
use App\Models\NodoTecnoparque;
use App\Models\Regional;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TpEvaluacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Convocatoria $convocatoria)
    {
        return Inertia::render('Convocatorias/Evaluaciones/Tp/Index', [
            'convocatoria'  => $convocatoria->only('id', 'fase_formateada'),
            'filters'       => request()->all('search'),
            'tp'            => TpEvaluacion::getProyectosPorEvaluador($convocatoria)->appends(['search' => request()->search]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Convocatoria $convocatoria)
    {
        // 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TpEvaluacionRequest $request)
    {
        //    
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Evaluacion\TpEvaluacion  $tpEvaluacion
     * @return \Illuminate\Http\Response
     */
    public function show(Convocatoria $convocatoria, TpEvaluacion $tpEvaluacion)
    {
        // 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Evaluacion\TpEvaluacion  $tpEvaluacion
     * @return \Illuminate\Http\Response
     */
    public function edit(Convocatoria $convocatoria, TpEvaluacion $tpEvaluacion)
    {
        $tpEvaluacion->evaluacion->proyecto;
        $tp = $tpEvaluacion->evaluacion->proyecto->tp;
        $tp->codigo_linea_programatica = $tp->proyecto->lineaProgramatica->codigo;
        $tp->precio_proyecto           = $tp->proyecto->precioProyecto;
        $tp->proyecto->centroFormacion;

        if (auth()->user()->hasRole(16)) {
            $nodosTecnoParque = NodoTecnoparque::select('nodos_tecnoparque.id as value', 'nodos_tecnoparque.nombre as label')->join('centros_formacion', 'nodos_tecnoparque.centro_formacion_id', 'centros_formacion.id')->where('centros_formacion.regional_id', auth()->user()->centroFormacion->regional_id)->get();
        } else {
            $nodosTecnoParque = NodoTecnoparque::select('nodos_tecnoparque.id as value', 'nodos_tecnoparque.nombre as label')->join('centros_formacion', 'nodos_tecnoparque.centro_formacion_id', 'centros_formacion.id')->get();
        }

        return Inertia::render('Convocatorias/Evaluaciones/Tp/Edit', [
            'convocatoria'          => $convocatoria->only('id', 'fase_formateada', 'min_fecha_inicio_proyectos_tp', 'max_fecha_finalizacion_proyectos_tp', 'fecha_maxima_tp', 'mostrar_recomendaciones'),
            'tp'                    => $tp,
            'tpEvaluacion'          => $tpEvaluacion,
            'idiSegundaEvaluacion'  => TpEvaluacion::whereHas('evaluacion', function ($query) use ($tp) {
                $query->where('evaluaciones.proyecto_id', $tp->id)->where('evaluaciones.habilitado', true);
            })->where('tp_evaluaciones.id', '!=', $tpEvaluacion->id)->first(),
            'regionales'            => Regional::select('id as value', 'nombre as label', 'codigo')->orderBy('nombre')->get(),
            'proyectoMunicipios'    => $tp->proyecto->municipios()->select('municipios.id as value', 'municipios.nombre as label', 'regionales.nombre as group', 'regionales.codigo')->join('regionales', 'regionales.id', 'municipios.regional_id')->get(),
            'nodosTecnoParque'      => $nodosTecnoParque
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Evaluacion\TpEvaluacion  $tpEvaluacion
     * @return \Illuminate\Http\Response
     */
    public function update(TpEvaluacionRequest $request, Convocatoria $convocatoria, TpEvaluacion $tpEvaluacion)
    {
        $tpEvaluacion->evaluacion()->update([
            'iniciado' => true,
            'clausula_confidencialidad' => $request->clausula_confidencialidad
        ]);

        $tpEvaluacion->resumen_regional_comentario = $request->resumen_regional_requiere_comentario == false ? $request->resumen_regional_comentario : null;
        $tpEvaluacion->antecedentes_regional_comentario = $request->antecedentes_regional_requiere_comentario == false ? $request->antecedentes_regional_comentario : null;
        $tpEvaluacion->municipios_comentario = $request->municipios_requiere_comentario == false ? $request->municipios_comentario : null;
        $tpEvaluacion->fecha_ejecucion_comentario = $request->fecha_ejecucion_requiere_comentario == false ? $request->fecha_ejecucion_comentario : null;
        $tpEvaluacion->cadena_valor_comentario = $request->cadena_valor_requiere_comentario == false ? $request->cadena_valor_comentario : null;
        $tpEvaluacion->bibliografia_comentario = $request->bibliografia_requiere_comentario == false ? $request->bibliografia_comentario : null;
        $tpEvaluacion->retos_oportunidades_comentario = $request->retos_oportunidades_requiere_comentario == false ? $request->retos_oportunidades_comentario : null;
        $tpEvaluacion->pertinencia_territorio_comentario = $request->pertinencia_territorio_requiere_comentario == false ? $request->pertinencia_territorio_comentario : null;
        $tpEvaluacion->ortografia_comentario = $request->ortografia_requiere_comentario == false ? $request->ortografia_comentario : null;
        $tpEvaluacion->redaccion_comentario = $request->redaccion_requiere_comentario == false ? $request->redaccion_comentario : null;
        $tpEvaluacion->normas_apa_comentario = $request->normas_apa_requiere_comentario == false ? $request->normas_apa_comentario : null;

        $tpEvaluacion->save();

        return redirect()->back()->with('success', 'El recurso ha sido actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Evaluacion\TpEvaluacion  $tpEvaluacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Convocatoria $convocatoria, TpEvaluacion $tpEvaluacion)
    {
        $this->authorize('delete', [TpEvaluacion::class, $tpEvaluacion]);

        $tpEvaluacion->delete();

        return redirect()->route('convocatorias.tp-evaluaciones.index', [$convocatoria])->with('success', 'The resource has been deleted successfully.');
    }
}
