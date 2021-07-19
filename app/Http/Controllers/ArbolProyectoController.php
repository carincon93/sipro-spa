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
use App\Http\Requests\ActividadRequest;
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

        $numeroCeldas = 4;
        switch ($proyecto) {
            case $proyecto->idi()->exists():
                $numeroCeldas = 4;
                break;
            case $proyecto->ta()->exists():
                $numeroCeldas = 6;
                break;
            case $proyecto->tp()->exists():
                $numeroCeldas = 4;
                break;
            case $proyecto->servicioTecnologico()->exists():
                if ($proyecto->servicioTecnologico->tipoProyectoSt->tipo_proyecto == 1) {
                    $numeroCeldas = 3;
                } else {
                    $numeroCeldas = 4;
                }
                break;
            case $proyecto->culturaInnovacion()->exists():
                $numeroCeldas = 4;
                break;
            default:
                break;
        }

        if ($proyecto->causasDirectas()->count() < $numeroCeldas) {
            for ($i = 0; $i < $numeroCeldas; $i++) {
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

        if ($proyecto->efectosDirectos()->count() < $numeroCeldas) {
            for ($i = 0; $i < $numeroCeldas; $i++) {
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
                } else {
                    $efectoDirecto->resultados()->create([
                        'descripcion'            => null,
                        'objetivo_especifico_id' => $objetivosEspecificos[$i]->id
                    ]);
                }
            }

            if ($proyecto->ta()->exists() && $proyecto->ta->modificable == false) {
                DB::select('SELECT public."crear_causas_indirectas"(' . $proyecto->id . ', 238, 237, 237, 0)');
                DB::select('SELECT public."crear_causas_indirectas"(' . $proyecto->id . ', 239, 238, 238, 1)');
                DB::select('SELECT public."crear_causas_indirectas"(' . $proyecto->id . ', 240, 239, 239, 2)');
                DB::select('SELECT public."crear_causas_indirectas"(' . $proyecto->id . ', 241, 240, 240, 3)');
                DB::select('SELECT public."crear_causas_indirectas"(' . $proyecto->id . ', 242, 241, 241, 4)');
                DB::select('SELECT public."crear_causas_indirectas"(' . $proyecto->id . ', 243, 242, 242, 5)');
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
            DB::select('SELECT public."actualizar_actividades_ta"(' . $proyecto->id . ', 238, 0, -1)');
            DB::select('SELECT public."actualizar_actividades_ta"(' . $proyecto->id . ', 239, 1, 9)');
            DB::select('SELECT public."actualizar_actividades_ta"(' . $proyecto->id . ', 240, 2, 19)');
            DB::select('SELECT public."actualizar_actividades_ta"(' . $proyecto->id . ', 241, 3, 25)');
            DB::select('SELECT public."actualizar_actividades_ta"(' . $proyecto->id . ', 242, 4, 32)');
            DB::select('SELECT public."actualizar_actividades_ta"(' . $proyecto->id . ', 243, 5, 38)');

            $proyecto->ta->update([
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
    public function showArbolProblemas(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);

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
            'convocatoria'      => $convocatoria->only('id'),
            'proyecto'          => $proyecto->only('id', 'precio_proyecto', 'identificacion_problema', 'problema_central', 'justificacion_problema', 'pregunta_formulacion_problema', 'codigo_linea_programatica', 'modificable'),
            'efectosDirectos'   => $efectosDirectos,
            'causasDirectas'    => $causasDirectas
        ]);
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
                    'problema_central'          => 'required|string|max:40000',
                ]);
                $ta->problema_central = $request->problema_central;

                $ta->save();
                break;
            case $proyecto->tp()->exists():
                $tp = $proyecto->tp;
                $request->validate([
                    'identificacion_problema'  => 'required|string|max:40000',
                    'problema_central'          => 'required|string|max:40000',
                    'justificacion_problema'    => 'required|string|max:40000',
                ]);
                $tp->identificacion_problema  = $request->identificacion_problema;
                $tp->justificacion_problema   = $request->justificacion_problema;
                $tp->problema_central = $request->problema_central;

                $tp->save();
                break;

            case $proyecto->culturaInnovacion()->exists():
                $request->validate([
                    'identificacion_problema'  => 'required|string|max:40000',
                    'problema_central'          => 'required|string|max:40000',
                    'justificacion_problema'    => 'required|string|max:40000',
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

        return redirect()->back()->with('success', 'El recurso se ha guardado correctamente.');
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

        return redirect()->back()->with('success', 'El recurso se ha guardado correctamente.');
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
                $numeroCeldas = 3;
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
            return redirect()->back()->with('error', 'No se pueden añadir más efectos indirectos.');
        }

        if (empty($efectoIndirecto->impacto)) {
            $efectoIndirecto->impacto()->create([
                ['descripcion' => null],
            ]);
        }

        return redirect()->back()->with('success', 'El recurso se ha guardado correctamente.');
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

        return redirect()->back()->with('success', 'El recurso se ha guardado correctamente.');
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
                $numeroCeldas = 4;
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
            return redirect()->back()->with('error', 'No se pueden añadir más causas indirectas.');
        }

        if (empty($causaIndirecta->actividad)) {
            $causaIndirecta->actividad()->create([
                ['descripcion' => null],
            ]);
        }

        return redirect()->back()->with('success', 'El recurso se ha guardado correctamente.');
    }

    /**
     * showArbolObjetivos
     *
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function showArbolObjetivos(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);

        $this->generateTree($proyecto);

        $efectosDirectos  = $proyecto->efectosDirectos()->with('efectosIndirectos.impacto', 'resultados')->get();
        $causasDirectas   = $proyecto->causasDirectas()->with('causasIndirectas.actividad', 'objetivoEspecifico')->get();
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
            'convocatoria'    => $convocatoria->only('id', 'min_fecha_inicio_proyectos', 'max_fecha_finalizacion_proyectos'),
            'proyecto'        => $proyecto->only('id', 'codigo_linea_programatica', 'precio_proyecto', 'identificacion_problema', 'problema_central', 'objetivo_general', 'fecha_inicio', 'fecha_finalizacion', 'modificable'),
            'efectosDirectos' => $efectosDirectos,
            'causasDirectas'  => $causasDirectas,
            'tiposImpacto'    => $tiposImpacto,
            'tipoProyectoA'   => $tipoProyectoA
        ]);
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
            'objetivo_general' => 'required|string|max:1200',
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

        return redirect()->back()->with('success', 'El recurso se ha guardado correctamente.');
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
            return redirect()->back()->with('success', 'El recurso se ha guardado correctamente.');
        }

        return redirect()->back()->with('error', 'Hubo un error mientras se actulizaba el impacto. Vuelva a intentar');
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
            return redirect()->back()->with('success', 'El recurso se ha guardado correctamente.');
        }

        return redirect()->back()->with('error', 'Hubo un error mientras se actualizaba el resultado. Vuelva a intentar');
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
            return redirect()->back()->with('success', 'El recurso se ha guardado correctamente.');
        }

        return redirect()->back()->with('error', 'Hubo un error mientras se actualizaba el objetivo específico. Vuelva a intentar.');
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

        $request->validate(
            ['descripcion' => 'required|string']
        );

        $actividad->fill($request->all());

        if ($actividad->save()) {
            return redirect()->back()->with('success', 'El recurso se ha guardado correctamente.');
        }

        return redirect()->back()->with('error', 'Hubo un error mientras se actulizaba la actividad. Vuelva a intentar');
    }
}
