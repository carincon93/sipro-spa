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

class ProjectTreeController extends Controller
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
            for ($i=0; $i < 4; $i++) {
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
            for ($i=0; $i < 4; $i++) {
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
     * showProblemTree
     *
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function showProblemTree(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('showProblemTree', [Proyecto::class, $proyecto]);

        switch ($proyecto) {
            case $proyecto->IDi()->exists():
                $this->generateTree($proyecto);
                $efectosDirectos = $proyecto->efectosDirectos()->with('efectosIndirectos:id,efecto_directo_id,descripcion')->get();
                $causasDirectas  = $proyecto->causasDirectas()->with('causasIndirectas')->get();
                $proyecto->planteamiento_problema = $proyecto->IDi->planteamiento_problema;
                $proyecto->justificacion_problema = $proyecto->IDi->justificacion_problema;
                break;
            default:
                break;
        }

        $proyecto->codigo_linea_programatica = $proyecto->tipoProyecto->lineaProgramatica->codigo;

        return Inertia::render('Convocatorias/Proyectos/ProjectTree/ProblemTree', [
            'convocatoria'      => $convocatoria->only('id'),
            'proyecto'          => $proyecto->only('id', 'planteamiento_problema', 'justificacion_problema', 'codigo_linea_programatica'),
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
        $this->authorize('createOrUpdateProblemTree', [Proyecto::class, $proyecto]);

        $request->validate([
            'planteamiento_problema' => 'required|string|max:1200',
        ]);

        switch ($proyecto) {
            case $proyecto->IDi()->exists():
                $IDi = $proyecto->IDi;
                $IDi->planteamiento_problema = $request->planteamiento_problema;
                $IDi->justificacion_problema = $request->justificacion_problema;

                $IDi->save();
                break;
            default:
                break;
        }

        return redirect()->back()->with('success', 'The resource has been saved successfully.');
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
        $this->authorize('createOrUpdateProblemTree', [Proyecto::class, $proyecto]);

        $efectoDirecto->descripcion = $request->descripcion;

        $efectoDirecto->save();

        return redirect()->back()->with('success', 'The resource has been saved successfully.');
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
        $this->authorize('createOrUpdateProblemTree', [Proyecto::class, $proyecto]);

        if (empty($request->id) && $efectoDirecto->efectosIndirectos()->count() < 3) {
            $efectoIndirecto = new EfectoIndirecto();
            $efectoIndirecto->fill($request->all());
            $efectoIndirecto->save();

        } elseif (!empty($request->id)) {
            $efectoIndirecto = EfectoIndirecto::find($request->id);
            $efectoIndirecto->descripcion = $request->descripcion;
            $efectoIndirecto->save();

        } else {
            return redirect()->back()->with('error', 'Cannot add more indirect effects.');
        }

        if (empty($efectoIndirecto->impacto)) {
            $efectoIndirecto->impacto()->create([
                ['descripcion' => null],
            ]);
        }

        return redirect()->back()->with('success', 'The resource has been saved successfully.');
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
        $this->authorize('createOrUpdateProblemTree', [Proyecto::class, $proyecto]);

        $causaDirecta->descripcion = $request->descripcion;

        $causaDirecta->save();

        return redirect()->back()->with('success', 'The resource has been saved successfully.');
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
        $this->authorize('createOrUpdateProblemTree', [Proyecto::class, $proyecto]);

        if (empty($request->id) && $causaDirecta->causasIndirectas()->count() < 3) {
            $causaIndirecta = new CausaIndirecta();
            $causaIndirecta->fill($request->all());
            $causaIndirecta->save();
        } elseif (!empty($request->id)) {
            $causaIndirecta = CausaIndirecta::find($request->id);
            $causaIndirecta->descripcion = $request->descripcion;
            $causaIndirecta->save();
        } else {
            return redirect()->back()->with('error', 'Cannot add more indirect causes.');
        }

        if (empty($causaIndirecta->actividad)) {
            $causaIndirecta->actividad()->create([
                ['descripcion' => null],
            ]);
        }

        return redirect()->back()->with('success', 'The resource has been saved successfully.');
    }

    /**
     * showObjectivesTree
     *
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function showObjectivesTree(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('showObjectivesTree', [Proyecto::class, $proyecto]);

        switch ($proyecto) {
            case $proyecto->IDi()->exists():
                $this->generateTree($proyecto);

                $efectosDirectos  = $proyecto->efectosDirectos()->with('efectosIndirectos.impacto', 'resultado')->get();
                $causasDirectas   = $proyecto->causasDirectas()->with('causasIndirectas.actividad', 'objetivoEspecifico')->get();
                break;
            default:
                break;
        }

        $proyecto->tipoProyecto->lineaProgramatica;
        $proyecto->makeHidden(
            'IDi', 
            'proyectoSennovaBudgets', 
            'updated_at',
        );

        return Inertia::render('Convocatorias/Proyectos/ProjectTree/ObjectivesTree', [
            'convocatoria'    => $convocatoria->only('id'),
            'proyecto'        => $proyecto,
            'efectosDirectos' => $efectosDirectos,
            'causasDirectas'  => $causasDirectas,
            'tiposResultado'  => json_decode(Storage::get('json/tipos-resultados.json'), true),
            'tiposImpacto'    => json_decode(Storage::get('json/tipos-impacto.json'), true),
        ]);
    }

    /**
     * updateObjective
     *
     * @param  mixed $request
     * @param  mixed $proyecto
     * @return void
     */
    public function updateObjective(Request $request, Proyecto $proyecto)
    {
        $this->authorize('createOrUpdateObjectivesTree', [Proyecto::class, $proyecto]);

        $request->validate([
            'objetivo_general' => 'required|string|max:1200',
        ]);

        switch ($proyecto) {
            case $proyecto->IDi()->exists():
                $IDi                    = $proyecto->IDi;
                $IDi->objetivo_general = $request->objetivo_general;

                $IDi->save();
                break;
            default:
                break;
        }

        return redirect()->back()->with('success', 'The resource has been saved successfully.');
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
        $this->authorize('createOrUpdateObjectivesTree', [Proyecto::class, $proyecto]);

        $impacto->descripcion    = $request->descripcion;
        $impacto->type           = $request->type;

        if ($impacto->save()) {
            return redirect()->back()->with('success', 'The resource has been saved successfully.');
        }

        return redirect()->back()->with('error', 'Error updating impact.');
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
        $this->authorize('createOrUpdateObjectivesTree', [Proyecto::class, $proyecto]);

        $resultado->fill($request->all());

        if ($resultado->save()) {
            return redirect()->back()->with('success', 'The resource has been saved successfully.');
        }

        return redirect()->back()->with('error', 'Error updating result.');
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
        $this->authorize('createOrUpdateObjectivesTree', [Proyecto::class, $proyecto]);

        $objetivoEspecifico->descripcion = $request->descripcion;
        $objetivoEspecifico->numero      = $request->numero;

        if ($objetivoEspecifico->save()) {
            return redirect()->back()->with('success', 'The resource has been saved successfully.');
        }

        return redirect()->back()->with('error', 'Error updating specific objective.');
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
        $this->authorize('createOrUpdateObjectivesTree', [Proyecto::class, $proyecto]);

        $actividad->fill($request->all());

        if ($actividad->save()) {
            return redirect()->back()->with('success', 'The resource has been saved successfully.');
        }

        return redirect()->back()->with('error', 'Error updating actividad.');
    }
}
