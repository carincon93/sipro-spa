<?php

namespace App\Http\Controllers;

use App\Models\Convocatoria;
use App\Models\Proyecto;
use App\Models\EfectoDirecto;
use App\Models\EfectoIndirecto;
use App\Models\CausaDirecta;
use App\Models\CausaIndirecta;
use App\Models\Resultado;
use App\Models\Impacto;
use App\Models\ObjetivoEspecifico;
use App\Models\Actividad;
use App\Http\Requests\CausaDirectaRequest;
use App\Http\Requests\EfectoDirectoRequest;
use App\Http\Requests\EfectoIndirectoRequest;
use App\Http\Requests\CausaIndirectaRequest;

use App\Http\Requests\ImpactoRequest;
use App\Http\Requests\ObjetivoEspecificoRequest;
use App\Http\Requests\ResultadoRequest;
use App\Models\Evaluacion\Evaluacion;
use App\Models\Evaluacion\IdiEvaluacion;
use App\Models\Evaluacion\TaEvaluacion;
use App\Models\Evaluacion\TpEvaluacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ArbolProyectoController extends Controller
{
    /**
     * generateTree
     *
     * @param  mixed $proyecto
     * @return void
     */
    private function generateTree(Proyecto $proyecto)
    {
        $objetivosEspecificos = $proyecto->causasDirectas()->with('objetivoEspecifico')->count() > 0 ? $proyecto->causasDirectas()->with('objetivoEspecifico')->get()->pluck('objetivoEspecifico')->flatten() : [];

        $numeroCeldasCausasDirectas = 4;
        $numeroCeldasEfectosDirectos = 4;
        switch ($proyecto) {

            case $proyecto->ta()->exists():
                $numeroCeldasEfectosDirectos = 6;
                $numeroCeldasCausasDirectas = 6;
                break;
            case $proyecto->tp()->exists():
                $numeroCeldasEfectosDirectos = 12;
                break;
            case $proyecto->servicioTecnologico()->exists():
                if ($proyecto->servicioTecnologico->tipoProyectoSt->tipo_proyecto == 1) {
                    $numeroCeldasEfectosDirectos = 3;
                    $numeroCeldasCausasDirectas = 3;
                }
                break;
            default:
                break;
        }

        if ($proyecto->causasDirectas()->count() < $numeroCeldasCausasDirectas) {
            for ($i = 0; $i < $numeroCeldasCausasDirectas; $i++) {
                $causaDirecta = $proyecto->causasDirectas()->create([
                    ['descripcion' => null],
                ]);

                $objetivoEspecifico = $causaDirecta->objetivoEspecifico()->create([
                    'descripcion'   => null,
                    'numero'        => $i + 1,
                ]);

                array_push($objetivosEspecificos, $objetivoEspecifico);
            }
        }

        if ($proyecto->efectosDirectos()->count() < $numeroCeldasEfectosDirectos) {
            for ($i = 0; $i < $numeroCeldasEfectosDirectos; $i++) {
                $efectoDirecto = $proyecto->efectosDirectos()->create([
                    ['descripcion' => null],
                ]);

                if ($proyecto->servicioTecnologico()->exists() && $i == 0) {
                    for ($j = 0; $j < 9; $j++) {
                        $efectoDirecto->resultados()->create([
                            'descripcion'            => null,
                            'objetivo_especifico_id' => $objetivosEspecificos[$i]->id
                        ]);
                    }
                } else if ($proyecto->servicioTecnologico()->exists() && $i == 1 || $proyecto->servicioTecnologico()->exists() && $i == 2) {
                    for ($j = 0; $j < 3; $j++) {
                        $efectoDirecto->resultados()->create([
                            'descripcion'            => null,
                            'objetivo_especifico_id' => $objetivosEspecificos[$i]->id
                        ]);
                    }
                } else if ($proyecto->servicioTecnologico()->exists() && $proyecto->servicioTecnologico->tipoProyectoSt->tipo_proyecto == 2 && $i == 3) {
                    for ($j = 0; $j < 2; $j++) {
                        $efectoDirecto->resultados()->create([
                            'descripcion'            => null,
                            'objetivo_especifico_id' => $objetivosEspecificos[$i]->id
                        ]);
                    }
                } else if ($proyecto->tp()->exists()) {
                    $numObj = 0;
                    if ($i < 3) {
                        $numObj = 0;
                    } else if ($i < 6) {
                        $numObj = 1;
                    } else if ($i < 9) {
                        $numObj = 2;
                    } else if ($i < 12) {
                        $numObj = 3;
                    }

                    $efectoDirecto->resultados()->create([
                        'descripcion'            => null,
                        'objetivo_especifico_id' => $objetivosEspecificos[$numObj]->id
                    ]);
                } else {
                    $efectoDirecto->resultados()->create([
                        'descripcion'            => null,
                        'objetivo_especifico_id' => $objetivosEspecificos[$i]->id
                    ]);
                }
            }

            if ($proyecto->ta()->exists() && $proyecto->ta->modificable == false) {
                DB::select('SELECT public."crear_causas_indirectas"(' . $proyecto->id . ', 384, 377, 385, 0)');
                DB::select('SELECT public."crear_causas_indirectas"(' . $proyecto->id . ', 385, 378, 386, 1)');
                DB::select('SELECT public."crear_causas_indirectas"(' . $proyecto->id . ', 386, 379, 387, 2)');
                DB::select('SELECT public."crear_causas_indirectas"(' . $proyecto->id . ', 387, 380, 388, 3)');
                DB::select('SELECT public."crear_causas_indirectas"(' . $proyecto->id . ', 388, 381, 389, 4)');
                DB::select('SELECT public."crear_causas_indirectas"(' . $proyecto->id . ', 389, 382, 390, 5)');
            } else if ($proyecto->tp()->exists() && $proyecto->tp->modificable == false) {
                DB::select('SELECT public."crear_causas_indirectas_tp"(' . $proyecto->id . ', 489, 0)');
                DB::select('SELECT public."crear_causas_indirectas_tp"(' . $proyecto->id . ', 490, 1)');
                DB::select('SELECT public."crear_causas_indirectas_tp"(' . $proyecto->id . ', 491, 2)');
                DB::select('SELECT public."crear_causas_indirectas_tp"(' . $proyecto->id . ', 492, 3)');

                DB::select('SELECT public."crear_efectos_indirectos_tp"(' . $proyecto->id . ', 482, 0)');
                DB::select('SELECT public."crear_efectos_indirectos_tp"(' . $proyecto->id . ', 483, 1)');
                DB::select('SELECT public."crear_efectos_indirectos_tp"(' . $proyecto->id . ', 484, 2)');
                DB::select('SELECT public."crear_efectos_indirectos_tp"(' . $proyecto->id . ', 485, 3)');
                DB::select('SELECT public."crear_efectos_indirectos_tp"(' . $proyecto->id . ', 486, 4)');
                DB::select('SELECT public."crear_efectos_indirectos_tp"(' . $proyecto->id . ', 487, 5)');
                DB::select('SELECT public."crear_efectos_indirectos_tp"(' . $proyecto->id . ', 488, 6)');
                DB::select('SELECT public."crear_efectos_indirectos_tp"(' . $proyecto->id . ', 489, 7)');
                DB::select('SELECT public."crear_efectos_indirectos_tp"(' . $proyecto->id . ', 490, 8)');
                DB::select('SELECT public."crear_efectos_indirectos_tp"(' . $proyecto->id . ', 491, 9)');
                DB::select('SELECT public."crear_efectos_indirectos_tp"(' . $proyecto->id . ', 492, 10)');
                DB::select('SELECT public."crear_efectos_indirectos_tp"(' . $proyecto->id . ', 493, 11)');

                DB::select('SELECT public."crear_productos_tp"(' . $proyecto->id . ', 519, 0)');
                DB::select('SELECT public."crear_productos_tp"(' . $proyecto->id . ', 520, 1)');
                DB::select('SELECT public."crear_productos_tp"(' . $proyecto->id . ', 521, 2)');
                DB::select('SELECT public."crear_productos_tp"(' . $proyecto->id . ', 522, 3)');
                DB::select('SELECT public."crear_productos_tp"(' . $proyecto->id . ', 523, 4)');
                DB::select('SELECT public."crear_productos_tp"(' . $proyecto->id . ', 524, 5)');
                DB::select('SELECT public."crear_productos_tp"(' . $proyecto->id . ', 525, 6)');
                DB::select('SELECT public."crear_productos_tp"(' . $proyecto->id . ', 526, 7)');
                DB::select('SELECT public."crear_productos_tp"(' . $proyecto->id . ', 527, 8)');
            }
        }

        foreach ($proyecto->efectosDirectos()->get() as $efectoDirecto) {
            foreach ($efectoDirecto->efectosIndirectos as $efectoIndirecto) {
                if (empty($efectoIndirecto->impacto)) {
                    $efectoIndirecto->impacto()->create([
                        ['descripcion' => null],
                    ]);
                }
            }
        }

        foreach ($proyecto->causasDirectas()->get() as $causaDirecta) {
            foreach ($causaDirecta->causasIndirectas as $causaIndirecta) {
                if (empty($causaIndirecta->actividad)) {
                    $causaIndirecta->actividad()->create([
                        ['descripcion' => null],
                    ]);
                }
            }
        }

        if ($proyecto->ta()->exists() && $proyecto->ta->modificable == false) {
            DB::select('SELECT public."objetivos_especificos_ta"(' . $proyecto->id . ')');
            DB::select('SELECT public."actualizar_actividades_ta"(' . $proyecto->id . ', 384, 0, -1)');
            DB::select('SELECT public."actualizar_actividades_ta"(' . $proyecto->id . ', 385, 1, 9)');
            DB::select('SELECT public."actualizar_actividades_ta"(' . $proyecto->id . ', 386, 2, 19)');
            DB::select('SELECT public."actualizar_actividades_ta"(' . $proyecto->id . ', 387, 3, 25)');
            DB::select('SELECT public."actualizar_actividades_ta"(' . $proyecto->id . ', 388, 4, 32)');
            DB::select('SELECT public."actualizar_actividades_ta"(' . $proyecto->id . ', 389, 5, 38)');

            DB::select('SELECT public."actividad_producto_tatp"(' . $proyecto->id . ', 385)');
            DB::select('SELECT public."actividad_producto_tatp"(' . $proyecto->id . ', 386)');
            DB::select('SELECT public."actividad_producto_tatp"(' . $proyecto->id . ', 387)');
            DB::select('SELECT public."actividad_producto_tatp"(' . $proyecto->id . ', 388)');
            DB::select('SELECT public."actividad_producto_tatp"(' . $proyecto->id . ', 389)');
            DB::select('SELECT public."actividad_producto_tatp"(' . $proyecto->id . ', 390)');

            DB::select('SELECT public."actualizar_impactos_ta"(' . $proyecto->id . ', 508, 0, 0)');
            DB::select('SELECT public."actualizar_impactos_ta"(' . $proyecto->id . ', 507, 0, 1)');
            DB::select('SELECT public."actualizar_impactos_ta"(' . $proyecto->id . ', 509, 0, 2)');
            DB::select('SELECT public."actualizar_impactos_ta"(' . $proyecto->id . ', 510, 0, 3)');
            DB::select('SELECT public."actualizar_impactos_ta"(' . $proyecto->id . ', 511, 0, 4)');
            DB::select('SELECT public."actualizar_impactos_ta"(' . $proyecto->id . ', 512, 0, 5)');

            $proyecto->ta->update([
                'modificable' => true
            ]);
        } else if ($proyecto->tp()->exists() && $proyecto->tp->modificable == false) {
            DB::select('SELECT public."objetivos_especificos_tp"(' . $proyecto->id . ')');
            DB::select('SELECT public."actualizar_actividades_tp"(' . $proyecto->id . ', 489, 0, -1)');
            DB::select('SELECT public."actualizar_actividades_tp"(' . $proyecto->id . ', 490, 1, 6)');
            DB::select('SELECT public."actualizar_actividades_tp"(' . $proyecto->id . ', 491, 2, 15)');

            DB::select('SELECT public."actividad_producto_tatp"(' . $proyecto->id . ', 519)');
            DB::select('SELECT public."actividad_producto_tatp"(' . $proyecto->id . ', 520)');
            DB::select('SELECT public."actividad_producto_tatp"(' . $proyecto->id . ', 521)');
            DB::select('SELECT public."actividad_producto_tatp"(' . $proyecto->id . ', 522)');
            DB::select('SELECT public."actividad_producto_tatp"(' . $proyecto->id . ', 523)');
            DB::select('SELECT public."actividad_producto_tatp"(' . $proyecto->id . ', 524)');
            DB::select('SELECT public."actividad_producto_tatp"(' . $proyecto->id . ', 525)');
            DB::select('SELECT public."actividad_producto_tatp"(' . $proyecto->id . ', 526)');
            DB::select('SELECT public."actividad_producto_tatp"(' . $proyecto->id . ', 527)');

            DB::select('SELECT public."actualizar_impactos_tp"(' . $proyecto->id . ', 590, 0, 0)');
            DB::select('SELECT public."actualizar_impactos_tp"(' . $proyecto->id . ', 591, 0, 1)');
            DB::select('SELECT public."actualizar_impactos_tp"(' . $proyecto->id . ', 592, 0, 2)');
            DB::select('SELECT public."actualizar_impactos_tp"(' . $proyecto->id . ', 593, 0, 3)');
            DB::select('SELECT public."actualizar_impactos_tp"(' . $proyecto->id . ', 594, 0, 4)');
            DB::select('SELECT public."actualizar_impactos_tp"(' . $proyecto->id . ', 595, 0, 5)');

            $proyecto->tp->update([
                'modificable' => true
            ]);
        }
    }

    /**
     * showArbolProblemas
     *
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function showArbolProblemas(Request $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);

        $proyecto->load('evaluaciones.idiEvaluacion');

        $this->generateTree($proyecto);
        $efectosDirectos = $proyecto->efectosDirectos()->with('efectosIndirectos:id,efecto_directo_id,descripcion')->get();
        $causasDirectas  = $proyecto->causasDirectas()->with('causasIndirectas')->get();
        $proyecto->codigo_linea_programatica = $proyecto->lineaProgramatica->codigo;

        switch ($proyecto) {
            case $proyecto->idi()->exists():
                $proyecto->problema_central = $proyecto->idi->problema_central;
                $proyecto->justificacion_problema   = $proyecto->idi->justificacion_problema;
                $proyecto->identificacion_problema  = $proyecto->idi->identificacion_problema;
                break;
            case $proyecto->ta()->exists():
                $proyecto->problema_central = $proyecto->ta->problema_central;
                break;
            case $proyecto->tp()->exists():
                $proyecto->justificacion_problema   = $proyecto->tp->justificacion_problema;
                $proyecto->identificacion_problema  = $proyecto->tp->identificacion_problema;
                $proyecto->problema_central         = $proyecto->tp->problema_central;
                break;
            case $proyecto->servicioTecnologico()->exists():
                $proyecto->problema_central = $proyecto->servicioTecnologico->problema_central;
                break;
            case $proyecto->culturaInnovacion()->exists():
                $proyecto->problema_central         = $proyecto->culturaInnovacion->problema_central;
                $proyecto->justificacion_problema   = $proyecto->culturaInnovacion->justificacion_problema;
                $proyecto->identificacion_problema  = $proyecto->culturaInnovacion->identificacion_problema;
                break;
            default:
                break;
        }

        return Inertia::render('Convocatorias/Proyectos/ArbolesProyecto/ArbolProblemas', [
            'convocatoria'      => $convocatoria->only('id', 'fase_formateada', 'mostrar_recomendaciones'),
            'proyecto'          => $proyecto->only('id', 'precio_proyecto', 'identificacion_problema', 'problema_central', 'justificacion_problema', 'pregunta_formulacion_problema', 'codigo_linea_programatica', 'modificable', 'en_subsanacion', 'evaluaciones'),
            'efectosDirectos'   => $efectosDirectos,
            'causasDirectas'    => $causasDirectas,
            'to_pdf'            => ($request->to_pdf == 1) ? true : false
        ]);
    }

    /**
     * showArbolProblemasEvaluacion
     *
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function showArbolProblemasEvaluacion(Convocatoria $convocatoria, Evaluacion $evaluacion)
    {
        $efectosDirectos = $evaluacion->proyecto->efectosDirectos()->with('efectosIndirectos:id,efecto_directo_id,descripcion')->get();
        $causasDirectas  = $evaluacion->proyecto->causasDirectas()->with('causasIndirectas')->get();
        $evaluacion->proyecto->codigo_linea_programatica = $evaluacion->proyecto->lineaProgramatica->codigo;

        switch ($evaluacion->proyecto) {
            case $evaluacion->proyecto->idi()->exists():
                $evaluacion->proyecto->antecedentes             = $evaluacion->proyecto->idi->antecedentes;
                $evaluacion->proyecto->marco_conceptual         = $evaluacion->proyecto->idi->marco_conceptual;
                $evaluacion->proyecto->problema_central         = $evaluacion->proyecto->idi->problema_central;
                $evaluacion->proyecto->justificacion_problema   = $evaluacion->proyecto->idi->justificacion_problema;
                $evaluacion->proyecto->identificacion_problema  = $evaluacion->proyecto->idi->identificacion_problema;
                $evaluacion->idiEvaluacion;
                $idi = $evaluacion->proyecto->idi;

                $segundaEvaluacion = IdiEvaluacion::whereHas('evaluacion', function ($query) use ($idi) {
                    $query->where('evaluaciones.proyecto_id', $idi->id)->where('evaluaciones.habilitado', true);
                })->where('idi_evaluaciones.id', '!=', $evaluacion->idiEvaluacion->id)->first();
                break;
            case $evaluacion->proyecto->ta()->exists():
                $evaluacion->proyecto->problema_central = $evaluacion->proyecto->ta->problema_central;
                $evaluacion->taEvaluacion;
                $ta = $evaluacion->proyecto->ta;

                $segundaEvaluacion = TaEvaluacion::whereHas('evaluacion', function ($query) use ($ta) {
                    $query->where('evaluaciones.proyecto_id', $ta->id)->where('evaluaciones.habilitado', true);
                })->where('ta_evaluaciones.id', '!=', $evaluacion->taEvaluacion->id)->first();
                break;
            case $evaluacion->proyecto->tp()->exists():
                $evaluacion->proyecto->justificacion_problema   = $evaluacion->proyecto->tp->justificacion_problema;
                $evaluacion->proyecto->identificacion_problema  = $evaluacion->proyecto->tp->identificacion_problema;
                $evaluacion->proyecto->problema_central         = $evaluacion->proyecto->tp->problema_central;
                $evaluacion->tpEvaluacion;
                $tp = $evaluacion->proyecto->tp;

                $segundaEvaluacion = TpEvaluacion::whereHas('evaluacion', function ($query) use ($tp) {
                    $query->where('evaluaciones.proyecto_id', $tp->id)->where('evaluaciones.habilitado', true);
                })->where('tp_evaluaciones.id', '!=', $evaluacion->tpEvaluacion->id)->first();
                break;
            case $evaluacion->proyecto->servicioTecnologico()->exists():
                $evaluacion->proyecto->problema_central = $evaluacion->proyecto->servicioTecnologico->problema_central;
                break;
            case $evaluacion->proyecto->culturaInnovacion()->exists():
                $evaluacion->proyecto->problema_central         = $evaluacion->proyecto->culturaInnovacion->problema_central;
                $evaluacion->proyecto->justificacion_problema   = $evaluacion->proyecto->culturaInnovacion->justificacion_problema;
                $evaluacion->proyecto->identificacion_problema  = $evaluacion->proyecto->culturaInnovacion->identificacion_problema;
                $evaluacion->culturaInnovacionEvaluacion;
                break;
            default:
                break;
        }

        return Inertia::render('Convocatorias/Evaluaciones/ArbolesProyecto/ArbolProblemas', [
            'convocatoria'      => $convocatoria->only('id', 'fase_formateada', 'mostrar_recomendaciones'),
            'evaluacion'        => $evaluacion,
            'segundaEvaluacion' => $segundaEvaluacion,
            'proyecto'          => $evaluacion->proyecto->only('id', 'precio_proyecto', 'identificacion_problema', 'problema_central', 'justificacion_problema', 'pregunta_formulacion_problema', 'antecedentes', 'marco_conceptual', 'codigo_linea_programatica', 'finalizado'),
            'efectosDirectos'   => $efectosDirectos,
            'causasDirectas'    => $causasDirectas,
        ]);
    }


    /**
     * updateArbolProblemasEvaluacion
     *
     * @param  mixed $request
     * @param  mixed $convocatoria
     * @param  mixed $evaluacion
     * @return void
     */
    public function updateArbolProblemasEvaluacion(Request $request, Convocatoria $convocatoria, Evaluacion $evaluacion)
    {
        switch ($evaluacion) {
            case $evaluacion->idiEvaluacion()->exists():
                $evaluacion->idiEvaluacion()->update([
                    'problema_central_puntaje'      => $request->problema_central_puntaje,
                    'problema_central_comentario'   => $request->problema_central_requiere_comentario == false ? $request->problema_central_comentario : null
                ]);
                break;
            case $evaluacion->culturaInnovacionEvaluacion()->exists():
                $evaluacion->culturaInnovacionEvaluacion()->update([
                    'problema_central_puntaje'      => $request->problema_central_puntaje,
                    'problema_central_comentario'   => $request->problema_central_requiere_comentario == false ? $request->problema_central_comentario : null
                ]);
                break;
            default:
                break;
        }

        return back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * updateProblem
     *
     * @param  mixed $request
     * @param  mixed $proyecto
     * @return void
     */
    public function updateProblemaCentral(Request $request, Proyecto $proyecto)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        switch ($proyecto) {
            case $proyecto->idi()->exists():
                $request->validate([
                    'identificacion_problema'  => 'required|string|max:40000',
                    'problema_central'         => 'required|string|max:40000',
                    'justificacion_problema'   => 'required|string|max:40000',
                ]);

                $idi = $proyecto->idi;
                $idi->identificacion_problema   = $request->identificacion_problema;
                $idi->problema_central          = $request->problema_central;
                $idi->justificacion_problema    = $request->justificacion_problema;

                $idi->save();
                break;
            case $proyecto->ta()->exists():
                $ta = $proyecto->ta;

                $request->validate([
                    'problema_central' => 'required|string|max:40000',
                ]);
                $ta->problema_central = $request->problema_central;

                $ta->save();
                break;
            case $proyecto->tp()->exists():
                $tp = $proyecto->tp;
                $request->validate([
                    'identificacion_problema'  => 'required|string|max:40000',
                    'problema_central'         => 'required|string|max:40000',
                    'justificacion_problema'   => 'required|string|max:40000',
                ]);
                $tp->identificacion_problema  = $request->identificacion_problema;
                $tp->justificacion_problema   = $request->justificacion_problema;
                $tp->problema_central = $request->problema_central;

                $tp->save();
                break;

            case $proyecto->culturaInnovacion()->exists():
                $request->validate([
                    'identificacion_problema'  => 'required|string|max:40000',
                    'problema_central'         => 'required|string|max:40000',
                    'justificacion_problema'   => 'required|string|max:40000',
                ]);

                $culturaInnovacion = $proyecto->culturaInnovacion;
                $culturaInnovacion->identificacion_problema  = $request->identificacion_problema;
                $culturaInnovacion->problema_central         = $request->problema_central;
                $culturaInnovacion->justificacion_problema   = $request->justificacion_problema;

                $culturaInnovacion->save();
                break;
            case $proyecto->servicioTecnologico()->exists():
                $request->validate([
                    'problema_central' => 'required|string|max:40000',
                ]);
                $servicioTecnologico                   = $proyecto->servicioTecnologico;
                $servicioTecnologico->problema_central = $request->problema_central;

                $servicioTecnologico->save();
                break;
            default:
                break;
        }

        return back()->with('success', 'El recurso se ha guardado correctamente.');
    }

    /**
     * updateEfectoDirecto
     *
     * @param  mixed $request
     * @param  mixed $proyecto
     * @param  mixed $efectoDirecto
     * @return void
     */
    public function updateEfectoDirecto(EfectoDirectoRequest $request, Proyecto $proyecto, EfectoDirecto $efectoDirecto)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        $efectoDirecto->descripcion = $request->descripcion;

        $efectoDirecto->save();

        return back()->with('success', 'El recurso se ha guardado correctamente.');
    }

    /**
     * createOrUpdateEfectoIndirecto
     *
     * @param  mixed $request
     * @param  mixed $proyecto
     * @param  mixed $efectoDirecto
     * @return void
     */
    public function createOrUpdateEfectoIndirecto(EfectoIndirectoRequest $request, Proyecto $proyecto, EfectoDirecto $efectoDirecto)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        $numeroCeldas = 3;
        switch ($proyecto) {
            case $proyecto->idi()->exists():
                $numeroCeldas = 3;
                break;
            case $proyecto->ta()->exists():
                $numeroCeldas = 3;
                break;
            case $proyecto->tp()->exists():
                $numeroCeldas = 1;
                break;
            case $proyecto->servicioTecnologico()->exists():
                $numeroCeldas = 3;
                break;
            case $proyecto->culturaInnovacion()->exists():
                $numeroCeldas = 3;
                break;
            default:
                break;
        }

        if (empty($request->id) && $efectoDirecto->efectosIndirectos()->count() < $numeroCeldas) {
            $efectoIndirecto = new EfectoIndirecto();
            $efectoIndirecto->fill($request->all());
            $efectoIndirecto->save();
        } elseif (!empty($request->id)) {
            $efectoIndirecto = EfectoIndirecto::find($request->id);
            $efectoIndirecto->descripcion = $request->descripcion;
            $efectoIndirecto->save();
        } else {
            return back()->with('error', 'No se pueden añadir más efectos indirectos.');
        }

        if (empty($efectoIndirecto->impacto)) {
            $efectoIndirecto->impacto()->create([
                ['descripcion' => null],
            ]);
        }

        return back()->with('success', 'El recurso se ha guardado correctamente.');
    }

    /**
     * updateCausaDirecta
     *
     * @param  mixed $request
     * @param  mixed $proyecto
     * @param  mixed $causaDirecta
     * @return void
     */
    public function updateCausaDirecta(CausaDirectaRequest $request, Proyecto $proyecto, CausaDirecta $causaDirecta)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        $causaDirecta->descripcion = $request->descripcion;

        $causaDirecta->save();

        return back()->with('success', 'El recurso se ha guardado correctamente.');
    }

    /**
     * createOrUpdateCausaIndirecta
     *
     * @param  mixed $request
     * @param  mixed $proyecto
     * @param  mixed $causaDirecta
     * @return void
     */
    public function createOrUpdateCausaIndirecta(CausaIndirectaRequest $request, Proyecto $proyecto, CausaDirecta $causaDirecta)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        $numeroCeldas = 4;
        switch ($proyecto) {
            case $proyecto->idi()->exists():
                $numeroCeldas = 4;
                break;
            case $proyecto->ta()->exists():
                $numeroCeldas = 10;
                break;
            case $proyecto->tp()->exists():
                $numeroCeldas = 9;
                break;
            case $proyecto->servicioTecnologico()->exists():
                $numeroCeldas = 14;
                break;
            case $proyecto->culturaInnovacion()->exists():
                $numeroCeldas = 4;
                break;
            default:
                break;
        }

        if (empty($request->id) && $causaDirecta->causasIndirectas()->count() < $numeroCeldas) {
            $causaIndirecta = new CausaIndirecta();
            $causaIndirecta->fill($request->all());
            $causaIndirecta->save();
        } elseif (!empty($request->id)) {
            $causaIndirecta = CausaIndirecta::find($request->id);
            $causaIndirecta->descripcion = $request->descripcion;
            $causaIndirecta->save();
        } else {
            return back()->with('error', 'No se pueden añadir más causas indirectas.');
        }

        if (empty($causaIndirecta->actividad)) {
            $causaIndirecta->actividad()->create([
                ['descripcion' => null],
            ]);
        }

        return back()->with('success', 'El recurso se ha guardado correctamente.');
    }

    /**
     * showArbolObjetivos
     *
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function showArbolObjetivos(Request $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);

        $proyecto->load('evaluaciones.idiEvaluacion');

        $this->generateTree($proyecto);

        $efectosDirectos    = $proyecto->efectosDirectos()->with('efectosIndirectos.impacto', 'resultados')->get();
        $causasDirectas     = $proyecto->causasDirectas()->with('causasIndirectas.actividad', 'objetivoEspecifico')->get();
        $objetivoEspecifico = $proyecto->causasDirectas()->with('objetivoEspecifico')->get()->pluck('objetivoEspecifico')->flatten()->filter();

        $proyecto->codigo_linea_programatica = $proyecto->lineaProgramatica->codigo;
        $tipoProyectoA = false;
        switch ($proyecto) {
            case $proyecto->idi()->exists():
                $proyecto->problema_central         = $proyecto->idi->problema_central;
                $proyecto->identificacion_problema  = $proyecto->idi->identificacion_problema;
                $proyecto->objetivo_general         = $proyecto->idi->objetivo_general;
                $tiposImpacto = json_decode(Storage::get('json/tipos-impacto.json'), true);
                break;
            case $proyecto->ta()->exists():
                $proyecto->problema_central         = $proyecto->ta->problema_central;
                $proyecto->identificacion_problema  = $proyecto->ta->identificacion_problema;
                $proyecto->objetivo_general         = $proyecto->ta->objetivo_general;
                $tiposImpacto = json_decode(Storage::get('json/tipos-impacto.json'), true);
                break;
            case $proyecto->tp()->exists():
                $proyecto->problema_central         = $proyecto->tp->problema_central;
                $proyecto->identificacion_problema  = $proyecto->tp->identificacion_problema;
                $proyecto->objetivo_general         = $proyecto->tp->objetivo_general;
                $tiposImpacto = json_decode(Storage::get('json/tipos-impacto.json'), true);
                break;
            case $proyecto->culturaInnovacion()->exists():
                $proyecto->problema_central         = $proyecto->culturaInnovacion->problema_central;
                $proyecto->identificacion_problema  = $proyecto->culturaInnovacion->identificacion_problema;
                $proyecto->objetivo_general         = $proyecto->culturaInnovacion->objetivo_general;
                $tiposImpacto = json_decode(Storage::get('json/tipos-impacto.json'), true);
                break;
            case $proyecto->servicioTecnologico()->exists():
                $proyecto->objetivo_general   = $proyecto->servicioTecnologico->objetivo_general;
                $proyecto->problema_central   = $proyecto->servicioTecnologico->problema_central;
                $tiposImpacto = json_decode(Storage::get('json/tipos-impacto-st.json'), true);
                $tipoProyectoA = $proyecto->servicioTecnologico->tipoProyectoSt->tipo_proyecto == 1;
                break;
            default:
                break;
        }

        return Inertia::render('Convocatorias/Proyectos/ArbolesProyecto/ArbolObjetivos', [
            'convocatoria'    => $convocatoria->only('id', 'fase_formateada', 'min_fecha_inicio_proyectos', 'max_fecha_finalizacion_proyectos'),
            'proyecto'        => $proyecto->only('id', 'codigo_linea_programatica', 'precio_proyecto', 'identificacion_problema', 'problema_central', 'objetivo_general', 'fecha_inicio', 'fecha_finalizacion', 'modificable', 'en_subsanacion', 'evaluaciones'),
            'efectosDirectos' => $efectosDirectos,
            'causasDirectas'  => $causasDirectas,
            'tiposImpacto'    => $tiposImpacto,
            'tipoProyectoA'   => $tipoProyectoA,
            'resultados'      => Resultado::select('id as value', 'descripcion as label', 'objetivo_especifico_id')->whereIn(
                'objetivo_especifico_id',
                $objetivoEspecifico->map(function ($objetivoEspecifico) {
                    return $objetivoEspecifico->id;
                })
            )->get(),
            'to_pdf'          => ($request->to_pdf == 1) ? true : false
        ]);
    }

    /**
     * showArbolObjetivosEvaluacion
     *
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function showArbolObjetivosEvaluacion(Convocatoria $convocatoria, Evaluacion $evaluacion)
    {
        $efectosDirectos  = $evaluacion->proyecto->efectosDirectos()->with('efectosIndirectos.impacto', 'resultados')->get();
        $causasDirectas   = $evaluacion->proyecto->causasDirectas()->with('causasIndirectas.actividad', 'objetivoEspecifico')->get();
        $evaluacion->proyecto->codigo_linea_programatica = $evaluacion->proyecto->lineaProgramatica->codigo;
        $tipoProyectoA = false;
        switch ($evaluacion->proyecto) {
            case $evaluacion->proyecto->idi()->exists():
                $evaluacion->proyecto->problema_central         = $evaluacion->proyecto->idi->problema_central;
                $evaluacion->proyecto->identificacion_problema  = $evaluacion->proyecto->idi->identificacion_problema;
                $evaluacion->proyecto->objetivo_general         = $evaluacion->proyecto->idi->objetivo_general;
                $tiposImpacto = json_decode(Storage::get('json/tipos-impacto.json'), true);
                $evaluacion->idiEvaluacion;
                $idi = $evaluacion->proyecto->idi;

                $segundaEvaluacion = IdiEvaluacion::whereHas('evaluacion', function ($query) use ($idi) {
                    $query->where('evaluaciones.proyecto_id', $idi->id)->where('evaluaciones.habilitado', true);
                })->where('idi_evaluaciones.id', '!=', $evaluacion->idiEvaluacion->id)->first();
                break;
            case $evaluacion->proyecto->ta()->exists():
                $evaluacion->proyecto->problema_central         = $evaluacion->proyecto->ta->problema_central;
                $evaluacion->proyecto->identificacion_problema  = $evaluacion->proyecto->ta->identificacion_problema;
                $evaluacion->proyecto->objetivo_general         = $evaluacion->proyecto->ta->objetivo_general;
                $evaluacion->taEvaluacion;
                $ta = $evaluacion->proyecto->ta;

                $segundaEvaluacion = TaEvaluacion::whereHas('evaluacion', function ($query) use ($ta) {
                    $query->where('evaluaciones.proyecto_id', $ta->id)->where('evaluaciones.habilitado', true);
                })->where('ta_evaluaciones.id', '!=', $evaluacion->taEvaluacion->id)->first();

                $tiposImpacto = json_decode(Storage::get('json/tipos-impacto.json'), true);
                break;
            case $evaluacion->proyecto->tp()->exists():
                $evaluacion->proyecto->problema_central         = $evaluacion->proyecto->tp->problema_central;
                $evaluacion->proyecto->identificacion_problema  = $evaluacion->proyecto->tp->identificacion_problema;
                $evaluacion->proyecto->objetivo_general         = $evaluacion->proyecto->tp->objetivo_general;

                $evaluacion->tpEvaluacion;
                $tp = $evaluacion->proyecto->tp;

                $segundaEvaluacion = TpEvaluacion::whereHas('evaluacion', function ($query) use ($tp) {
                    $query->where('evaluaciones.proyecto_id', $tp->id)->where('evaluaciones.habilitado', true);
                })->where('tp_evaluaciones.id', '!=', $evaluacion->tpEvaluacion->id)->first();


                $tiposImpacto = json_decode(Storage::get('json/tipos-impacto.json'), true);
                break;
            case $evaluacion->proyecto->culturaInnovacion()->exists():
                $evaluacion->proyecto->problema_central         = $evaluacion->proyecto->culturaInnovacion->problema_central;
                $evaluacion->proyecto->identificacion_problema  = $evaluacion->proyecto->culturaInnovacion->identificacion_problema;
                $evaluacion->proyecto->objetivo_general         = $evaluacion->proyecto->culturaInnovacion->objetivo_general;
                $tiposImpacto = json_decode(Storage::get('json/tipos-impacto.json'), true);
                $evaluacion->culturaInnovacionEvaluacion;
                break;
            case $evaluacion->proyecto->servicioTecnologico()->exists():
                $evaluacion->proyecto->objetivo_general   = $evaluacion->proyecto->servicioTecnologico->objetivo_general;
                $evaluacion->proyecto->problema_central   = $evaluacion->proyecto->servicioTecnologico->problema_central;
                $tiposImpacto = json_decode(Storage::get('json/tipos-impacto-st.json'), true);
                $tipoProyectoA = $evaluacion->proyecto->servicioTecnologico->tipoProyectoSt->tipo_proyecto == 1;
                break;
            default:
                break;
        }

        return Inertia::render('Convocatorias/Evaluaciones/ArbolesProyecto/ArbolObjetivos', [
            'convocatoria'      => $convocatoria->only('id', 'fase_formateada', 'min_fecha_inicio_proyectos', 'max_fecha_finalizacion_proyectos'),
            'evaluacion'        => $evaluacion,
            'segundaEvaluacion' => $segundaEvaluacion,
            'proyecto'          => $evaluacion->proyecto->only('id', 'codigo_linea_programatica', 'precio_proyecto', 'identificacion_problema', 'problema_central', 'objetivo_general', 'fecha_inicio', 'fecha_finalizacion', 'finalizado'),
            'efectosDirectos'   => $efectosDirectos,
            'causasDirectas'    => $causasDirectas,
            'tiposImpacto'      => $tiposImpacto,
            'tipoProyectoA'     => $tipoProyectoA
        ]);
    }

    /**
     * updateArbolObjetivosEvaluacion
     *
     * @param  mixed $request
     * @param  mixed $convocatoria
     * @param  mixed $evaluacion
     * @return void
     */
    public function updateArbolObjetivosEvaluacion(Request $request, Convocatoria $convocatoria, Evaluacion $evaluacion)
    {
        switch ($evaluacion) {
            case $evaluacion->idiEvaluacion()->exists():
                $evaluacion->idiEvaluacion()->update([
                    'objetivos_puntaje'      => $request->objetivos_puntaje,
                    'objetivos_comentario'   => $request->objetivos_requiere_comentario == false ? $request->objetivos_comentario : null,
                    'resultados_puntaje'     => $request->resultados_puntaje,
                    'resultados_comentario'  => $request->resultados_requiere_comentario == false ? $request->resultados_comentario : null
                ]);
                break;
            case $evaluacion->culturaInnovacionEvaluacion()->exists():
                $evaluacion->culturaInnovacionEvaluacion()->update([
                    'objetivos_puntaje'      => $request->objetivos_puntaje,
                    'objetivos_comentario'   => $request->objetivos_requiere_comentario == false ? $request->objetivos_comentario : null,
                    'resultados_puntaje'     => $request->resultados_puntaje,
                    'resultados_comentario'  => $request->resultados_requiere_comentario == false ? $request->resultados_comentario : null
                ]);
                break;
            default:
                break;
        }

        $evaluacion->save();

        return back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * updateObjetivoGeneral
     *
     * @param  mixed $request
     * @param  mixed $proyecto
     * @return void
     */
    public function updateObjetivoGeneral(Request $request, Proyecto $proyecto)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        $request->validate([
            'objetivo_general' => 'required|string',
        ]);

        switch ($proyecto) {
            case $proyecto->idi()->exists():
                $idi                   = $proyecto->idi;
                $idi->objetivo_general = $request->objetivo_general;

                $idi->save();
                break;
            case $proyecto->ta()->exists():
                $ta                   = $proyecto->ta;
                $ta->objetivo_general = $request->objetivo_general;

                $ta->save();
                break;
            case $proyecto->tp()->exists():
                $tp                   = $proyecto->tp;
                $tp->objetivo_general = $request->objetivo_general;

                $tp->save();
                break;
            case $proyecto->culturaInnovacion()->exists():
                $culturaInnovacion                   = $proyecto->culturaInnovacion;
                $culturaInnovacion->objetivo_general = $request->objetivo_general;

                $culturaInnovacion->save();
                break;
            case $proyecto->servicioTecnologico()->exists():
                $servicioTecnologico                   = $proyecto->servicioTecnologico;
                $servicioTecnologico->objetivo_general = $request->objetivo_general;

                $servicioTecnologico->save();
                break;
            default:
                break;
        }

        return back()->with('success', 'El recurso se ha guardado correctamente.');
    }

    /**
     * updateImpacto
     *
     * @param  mixed $request
     * @param  mixed $proyecto
     * @param  mixed $impacto
     * @return void
     */
    public function updateImpacto(ImpactoRequest $request, Proyecto $proyecto, Impacto $impacto)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        $impacto->descripcion    = $request->descripcion;
        $impacto->tipo           = $request->tipo;

        if ($impacto->save()) {
            return back()->with('success', 'El recurso se ha guardado correctamente.');
        }

        return back()->with('error', 'Hubo un error mientras se actulizaba el impacto. Vuelva a intentar');
    }

    /**
     * updateResultado
     *
     * @param  mixed $request
     * @param  mixed $proyecto
     * @param  mixed $resultado
     * @return void
     */
    public function updateResultado(ResultadoRequest $request, Proyecto $proyecto, Resultado $resultado)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        $resultado->fill($request->all());

        if ($proyecto->idi()->exists() || $proyecto->culturaInnovacion()->exists()) {
            $request->validate([
                'trl' => ['required', 'integer', 'between:1,9'],
            ]);
        }

        if ($resultado->save()) {
            return back()->with('success', 'El recurso se ha guardado correctamente.');
        }

        return back()->with('error', 'Hubo un error mientras se actualizaba el resultado. Vuelva a intentar');
    }

    /**
     * updateObjetivoEspecifico
     *
     * @param  mixed $request
     * @param  mixed $proyecto
     * @param  mixed $objetivoEspecifico
     * @return void
     */
    public function updateObjetivoEspecifico(ObjetivoEspecificoRequest $request, Proyecto $proyecto, ObjetivoEspecifico $objetivoEspecifico)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        $objetivoEspecifico->descripcion = $request->descripcion;
        $objetivoEspecifico->numero      = $request->numero;

        if ($objetivoEspecifico->save()) {
            return back()->with('success', 'El recurso se ha guardado correctamente.');
        }

        return back()->with('error', 'Hubo un error mientras se actualizaba el objetivo específico. Vuelva a intentar.');
    }

    /**
     * updateActividad
     *
     * @param  mixed $request
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @param  mixed $actividad
     * @return void
     */
    public function updateActividad(Request $request, Convocatoria $convocatoria, Proyecto $proyecto, Actividad $actividad)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        $request->resultado_id = $request->resultado_id['value'];
        $request->validate(
            ['descripcion'  => 'required|string'],
            ['resultado_id' => 'required|integer|exists:resultados']
        );

        $resultado = Resultado::find($request->resultado_id);
        $actividad->descripcion = $request->descripcion;
        $actividad->resultado()->associate($request->resultado_id);
        $actividad->objetivoEspecifico()->associate($resultado->objetivo_especifico_id);

        if ($actividad->save()) {
            return back()->with('success', 'El recurso se ha guardado correctamente.');
        }

        return back()->with('error', 'Hubo un error mientras se actulizaba la actividad. Vuelva a intentar');
    }
}
