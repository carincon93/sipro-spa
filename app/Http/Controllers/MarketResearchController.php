<?php

namespace App\Http\Controllers;

use App\Http\Requests\MarketResearchRequest;
use App\Models\CallBudget;
use App\Models\Call;
use App\Models\Project;
use App\Models\ProjectSennovaBudget;
use App\Models\ProjectBudgetBatch;
use App\Models\MarketResearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class MarketResearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( Call $call, Project $project, ProjectSennovaBudget $projectSennovaBudget)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create( Call $call, Project $project, ProjectSennovaBudget $projectSennovaBudget)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MarketResearchRequest $request, Call $call, Project $project, ProjectSennovaBudget $projectSennovaBudget)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MarketResearch  $marketResearch
     * @return \Illuminate\Http\Response
     */
    public function show(Call $call, Project $project, ProjectSennovaBudget $projectSennovaBudget, MarketResearch $marketResearch)
    {
        $this->authorize('view', [MarketResearch::class, $marketResearch]);

        return Inertia::render('Calls/Projects/ProjectSennovaBudgets/MarketResearch/Show', [
            'marketResearch' => $marketResearch
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MarketResearch  $marketResearch
     * @return \Illuminate\Http\Response
     */
    public function edit(Call $call, Project $project, ProjectSennovaBudget $projectSennovaBudget, ProjectBudgetBatch $projectBudgetBatch)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MarketResearch  $marketResearch
     * @return \Illuminate\Http\Response
     */
    public function update(MarketResearchRequest $request, Call $call, Project $project, ProjectSennovaBudget $projectSennovaBudget, ProjectBudgetBatch $projectBudgetBatch)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MarketResearch  $marketResearch
     * @return \Illuminate\Http\Response
     */
    public function destroy(Call $call, Project $project, ProjectSennovaBudget $projectSennovaBudget, MarketResearch $marketResearch)
    {
        //
    }
}
