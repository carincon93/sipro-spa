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

        if ($proyecto->causasDirectas()->count() < 4) {
            for ($i = 0; $i < 4; $i++) {
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

        if ($proyecto->efectosDirectos()->count() < 4) {
            for ($i = 0; $i < 4; $i++) {
                $efectoDirecto = $proyecto->efectosDirectos()->create([
                    ['descripcion' => null],
                ]);

                $resultado = $efectoDirecto->resultado()->create([
                    'descripcion'            => null,
                    'objetivo_especifico_id' => $objetivosEspecificos[$i]->id
                ]);
            }
        }

        foreach ($proyecto->efectosDirectos()->get() as $efectoDirecto) {
            foreach ($efectoDirecto->efectosIndirectos as $efectoIndirecto) {
                if (empty($efectoIndirecto->impacto)) {
                    $impacto = $efectoIndirecto->impacto()->create([
                        ['descripcion' => null],
                    ]);
                }
            }
        }

        foreach ($proyecto->causasDirectas()->get() as $causaDirecta) {
            foreach ($causaDirecta->causasIndirectas as $causaIndirecta) {
                if (empty($causaIndirecta->actividad)) {
                    $actividad = $causaIndirecta->actividad()->create([
                        ['descripcion' => null],
                    ]);
                }
            }
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
        $proyecto->codigo_linea_programatica = $proyecto->tipoProyecto->lineaProgramatica->codigo;
        switch ($proyecto) {
            case $proyecto->idi()->exists():
                $proyecto->planteamiento_problema = $proyecto->idi->planteamiento_problema;
                $proyecto->justificacion_problema = $proyecto->idi->justificacion_problema;
                break;
            case $proyecto->taTp()->exists():
                $proyecto->planteamiento_problema = $proyecto->tatp->planteamiento_problema;
                $proyecto->justificacion_problema = $proyecto->tatp->justificacion_problema;
                break;
            case $proyecto->servicioTecnologico()->exists():
                $proyecto->planteamiento_problema = $proyecto->servicioTecnologico->planteamiento_problema;
                $proyecto->justificacion_problema = $proyecto->servicioTecnologico->justificacion_problema;
                $proyecto->pregunta_formulacion_problema = $proyecto->servicioTecnologico->pregunta_formulacion_problema;
                break;
            case $proyecto->culturaInnovacion()->exists():
                $proyecto->planteamiento_problema = $proyecto->culturaInnovacion->planteamiento_problema;
                $proyecto->justificacion_problema = $proyecto->culturaInnovacion->justificacion_problema;
                break;
            default:
                break;
        }

        return Inertia::render('Convocatorias/Proyectos/ArbolesProyecto/ArbolProblemas', [
            'convocatoria'      => $convocatoria->only('id'),
            'proyecto'          => $proyecto->only('id', 'precio_proyecto', 'planteamiento_problema', 'justificacion_problema', 'pregunta_formulacion_problema', 'codigo_linea_programatica', 'modificable'),
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
    public function updatePlanteamientoProblema(Request $request, Proyecto $proyecto)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        $request->validate([
            'planteamiento_problema' => 'required|string|max:40000',
            'justificacion_problema' => 'required|string|max:40000',
        ]);

        switch ($proyecto) {
            case $proyecto->idi()->exists():
                $idi = $proyecto->idi;
                $idi->planteamiento_problema = $request->planteamiento_problema;
                $idi->justificacion_problema = $request->justificacion_problema;

                $idi->save();
                break;
            case $proyecto->servicioTecnologico()->exists():
                $servicioTecnologico = $proyecto->servicioTecnologico;
                $servicioTecnologico->planteamiento_problema = $request->planteamiento_problema;
                $servicioTecnologico->justificacion_problema = $request->justificacion_problema;
                $servicioTecnologico->pregunta_formulacion_problema = $request->pregunta_formulacion_problema;

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

        if (empty($request->id) && $efectoDirecto->efectosIndirectos()->count() < 3) {
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

        if (empty($request->id) && $causaDirecta->causasIndirectas()->count() < 3) {
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

        $efectosDirectos  = $proyecto->efectosDirectos()->with('efectosIndirectos.impacto', 'resultado')->get();
        $causasDirectas   = $proyecto->causasDirectas()->with('causasIndirectas.actividad', 'objetivoEspecifico')->get();
        $proyecto->codigo_linea_programatica = $proyecto->tipoProyecto->lineaProgramatica->codigo;
        switch ($proyecto) {
            case $proyecto->idi()->exists():
                $proyecto->objetivo_general         = $proyecto->idi->objetivo_general;
                $proyecto->planteamiento_problema   = $proyecto->idi->planteamiento_problema;
                break;
            case $proyecto->taTp()->exists():
                $proyecto->objetivo_general         = $proyecto->tatp->objetivo_general;
                $proyecto->planteamiento_problema   = $proyecto->tatp->planteamiento_problema;
                break;
            case $proyecto->servicioTecnologico()->exists():
                $proyecto->objetivo_general         = $proyecto->servicioTecnologico->objetivo_general;
                $proyecto->planteamiento_problema   = $proyecto->servicioTecnologico->planteamiento_problema;
                break;
            case $proyecto->culturaInnovacion()->exists():
                $proyecto->objetivo_general         = $proyecto->culturaInnovacion->objetivo_general;
                $proyecto->planteamiento_problema   = $proyecto->culturaInnovacion->planteamiento_problema;
                break;
            default:
                break;
        }

        return Inertia::render('Convocatorias/Proyectos/ArbolesProyecto/ArbolObjetivos', [
            'convocatoria'    => $convocatoria->only('id', 'min_fecha_inicio_proyectos', 'max_fecha_finalizacion_proyectos'),
            'proyecto'        => $proyecto->only('id', 'codigo_linea_programatica', 'precio_proyecto', 'planteamiento_problema', 'objetivo_general', 'fecha_inicio', 'fecha_finalizacion', 'modificable'),
            'efectosDirectos' => $efectosDirectos,
            'causasDirectas'  => $causasDirectas,
            'tiposResultado'  => json_decode(Storage::get('json/tipos-resultados.json'), true),
            'tiposImpacto'    => json_decode(Storage::get('json/tipos-impacto.json'), true),
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
            case $proyecto->Idi()->exists():
                $idi                   = $proyecto->Idi;
                $idi->objetivo_general = $request->objetivo_general;

                $idi->save();
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
    public function updateActividad(ActividadRequest $request, Convocatoria $convocatoria, Proyecto $proyecto, Actividad $actividad)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        $actividad->fill($request->all());

        if ($actividad->save()) {
            return redirect()->back()->with('success', 'El recurso se ha guardado correctamente.');
        }

        return redirect()->back()->with('error', 'Hubo un error mientras se actulizaba la actividad. Vuelva a intentar');
    }
}
