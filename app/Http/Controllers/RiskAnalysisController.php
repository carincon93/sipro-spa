<?php

namespace App\Http\Controllers;

use App\Http\Requests\RiskAnalysisRequest;
use App\Models\Call;
use App\Models\Project;
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
    public function index(Call $call, Project $project)
    {
        $this->authorize('viewAny', [RiskAnalysis::class]);

        $project->projectType->programmaticLine;
        $project->makeHidden(
            'rdi', 
            'projectSennovaBudgets', 
            'updated_at',
        );

        return Inertia::render('Calls/Projects/RiskAnalysis/Index', [
            'call'      => $call,
            'project'   => $project,
            'filters'   => request()->all('search'),
            'riskAnalysis' => RiskAnalysis::where('project_id', $project->id)->orderBy('description', 'ASC')
                ->filterRiskAnalysis(request()->only('search'))->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Call $call, Project $project)
    {
        $this->authorize('create', [RiskAnalysis::class]);

        return Inertia::render('Calls/Projects/RiskAnalysis/Create', [
            'call'              => $call,
            'project'           => $project,
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
    public function store(RiskAnalysisRequest $request, Call $call, Project $project)
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
        $riskAnalysis->project()->associate($project);

        $riskAnalysis->save();

        return redirect()->route('calls.projects.risk-analysis.index', [$call, $project])->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RiskAnalysis  $riskAnalysis
     * @return \Illuminate\Http\Response
     */
    public function show(Call $call, Project $project, RiskAnalysis $riskAnalysis)
    {
        $this->authorize('view', [RiskAnalysis::class, $riskAnalysis]);

        return Inertia::render('Calls/Projects/RiskAnalysis/Show', [
            'riskAnalysis' => $riskAnalysis
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RiskAnalysis  $riskAnalysis
     * @return \Illuminate\Http\Response
     */
    public function edit(Call $call, Project $project, RiskAnalysis $riskAnalysis)
    {
        $this->authorize('update', [RiskAnalysis::class, $riskAnalysis]);

        return Inertia::render('Calls/Projects/RiskAnalysis/Editar', [
            'call'              => $call,
            'project'           => $project,
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
    public function update(RiskAnalysisRequest $request, Call $call, Project $project, RiskAnalysis $riskAnalysis)
    {
        $this->authorize('update', [RiskAnalysis::class, $riskAnalysis]);

        $riskAnalysis->level                = $request->level;
        $riskAnalysis->type                 = $request->type;
        $riskAnalysis->description          = $request->description;
        $riskAnalysis->probability          = $request->probability;
        $riskAnalysis->impact               = $request->impact;
        $riskAnalysis->effects              = $request->effects;
        $riskAnalysis->mitigation_measures  = $request->mitigation_measures;
        $riskAnalysis->project()->associate($project);

        $riskAnalysis->save();

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RiskAnalysis  $riskAnalysis
     * @return \Illuminate\Http\Response
     */
    public function destroy(Call $call, Project $project, RiskAnalysis $riskAnalysis)
    {
        $this->authorize('delete', [RiskAnalysis::class, $riskAnalysis]);

        $riskAnalysis->delete();

        return redirect()->route('calls.projects.risk-analysis.index', [$call, $project])->with('success', 'The resource has been deleted successfully.');
    }
}
