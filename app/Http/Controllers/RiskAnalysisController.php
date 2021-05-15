<?php

namespace App\Http\Controllers;

use App\Http\Requests\RiskAnalysisRequest;
use App\Models\Convocatoria;
use App\Models\Proyecto;
use App\Models\RiskAnalysis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class RiskAnalysisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('viewAny', [RiskAnalysis::class]);

        $proyecto->projectType->programmaticLine;
        $proyecto->makeHidden(
            'rdi', 
            'projectSennovaBudgets', 
            'updated_at',
        );

        return Inertia::render('Convocatorias/Proyectos/RiskAnalysis/Index', [
            'convocatoria'      => $convocatoria,
            'project'   => $proyecto,
            'filters'   => request()->all('search'),
            'riskAnalysis' => RiskAnalysis::where('project_id', $proyecto->id)->orderBy('description', 'ASC')
                ->filterRiskAnalysis(request()->only('search'))->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('create', [RiskAnalysis::class]);

        return Inertia::render('Convocatorias/Proyectos/RiskAnalysis/Create', [
            'convocatoria'              => $convocatoria,
            'project'           => $proyecto,
            'riskLevels'        => json_decode(Storage::get('json/risk-levels.json'), true),
            'riskTypes'         => json_decode(Storage::get('json/risk-types.json'), true),
            'riskProbabilities' => json_decode(Storage::get('json/risk-probabilities.json'), true),
            'riskImpacts'       => json_decode(Storage::get('json/risk-impacts.json'), true)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RiskAnalysisRequest $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('create', [RiskAnalysis::class]);

        $riskAnalysis = new RiskAnalysis();
        $riskAnalysis->level                = $request->level;
        $riskAnalysis->type                 = $request->type;
        $riskAnalysis->description          = $request->description;
        $riskAnalysis->probability          = $request->probability;
        $riskAnalysis->impact               = $request->impact;
        $riskAnalysis->effects              = $request->effects;
        $riskAnalysis->mitigation_measures  = $request->mitigation_measures;
        $riskAnalysis->project()->associate($proyecto);

        $riskAnalysis->save();

        return redirect()->route('convocatorias.projects.risk-analysis.index', [$convocatoria, $proyecto])->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RiskAnalysis  $riskAnalysis
     * @return \Illuminate\Http\Response
     */
    public function show(Convocatoria $convocatoria, Proyecto $proyecto, RiskAnalysis $riskAnalysis)
    {
        $this->authorize('view', [RiskAnalysis::class, $riskAnalysis]);

        return Inertia::render('Convocatorias/Proyectos/RiskAnalysis/Show', [
            'riskAnalysis' => $riskAnalysis
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RiskAnalysis  $riskAnalysis
     * @return \Illuminate\Http\Response
     */
    public function edit(Convocatoria $convocatoria, Proyecto $proyecto, RiskAnalysis $riskAnalysis)
    {
        $this->authorize('update', [RiskAnalysis::class, $riskAnalysis]);

        return Inertia::render('Convocatorias/Proyectos/RiskAnalysis/Editar', [
            'convocatoria'              => $convocatoria,
            'project'           => $proyecto,
            'riskAnalysis'      => $riskAnalysis,
            'riskLevels'        => json_decode(Storage::get('json/risk-levels.json'), true),
            'riskTypes'         => json_decode(Storage::get('json/risk-types.json'), true),
            'riskProbabilities' => json_decode(Storage::get('json/risk-probabilities.json'), true),
            'riskImpacts'       => json_decode(Storage::get('json/risk-impacts.json'), true)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RiskAnalysis  $riskAnalysis
     * @return \Illuminate\Http\Response
     */
    public function update(RiskAnalysisRequest $request, Convocatoria $convocatoria, Proyecto $proyecto, RiskAnalysis $riskAnalysis)
    {
        $this->authorize('update', [RiskAnalysis::class, $riskAnalysis]);

        $riskAnalysis->level                = $request->level;
        $riskAnalysis->type                 = $request->type;
        $riskAnalysis->description          = $request->description;
        $riskAnalysis->probability          = $request->probability;
        $riskAnalysis->impact               = $request->impact;
        $riskAnalysis->effects              = $request->effects;
        $riskAnalysis->mitigation_measures  = $request->mitigation_measures;
        $riskAnalysis->project()->associate($proyecto);

        $riskAnalysis->save();

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RiskAnalysis  $riskAnalysis
     * @return \Illuminate\Http\Response
     */
    public function destroy(Convocatoria $convocatoria, Proyecto $proyecto, RiskAnalysis $riskAnalysis)
    {
        $this->authorize('delete', [RiskAnalysis::class, $riskAnalysis]);

        $riskAnalysis->delete();

        return redirect()->route('convocatorias.projects.risk-analysis.index', [$convocatoria, $proyecto])->with('success', 'The resource has been deleted successfully.');
    }
}
