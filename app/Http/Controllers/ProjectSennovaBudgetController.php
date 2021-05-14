<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectSennovaBudgetRequest;
use App\Models\Call;
use App\Models\CallBudget;
use App\Models\SecondBudgetInfo;
use App\Models\Project;
use App\Models\ProjectSennovaBudget;
use App\Models\SoftwareInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Traits\BudgetValidationTrait;
use Inertia\Inertia;

class ProjectSennovaBudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Call $call, Project $project)
    {
        $this->authorize('viewAny', ProjectSennovaBudget::class);

        $project->projectType->programmaticLine;
        $project->makeHidden(
            'rdi', 
            'projectSennovaBudgets', 
            'updated_at',
        );

        return Inertia::render('Calls/Projects/ProjectSennovaBudgets/Index', [
            'call'                  => $call->only('id'),
            'project'               => $project,
            'filters'               => request()->all('search'),
            'projectSennovaBudgets' => ProjectSennovaBudget::where('project_id', $project->id)->filterProjectSennovaBudget(request()->only('search'))->with('callBudget.sennovaBudget.thirdBudgetInfo:id,name', 'callBudget.sennovaBudget.secondBudgetInfo:id,name', 'callBudget.sennovaBudget.budgetUsage:id,description')->paginate(),
            'secondBudgetInfo'      => SecondBudgetInfo::orderBy('nombre', 'ASC')->get('name'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Call $call, Project $project)
    {
        $this->authorize('create', [ProjectSennovaBudget::class]);

        $project->projectType->programmaticLine->only('id');

        return Inertia::render('Calls/Projects/ProjectSennovaBudgets/Create', [
            'call'          => $call,
            'project'       => $project,
            'licenceTypes'  => json_decode(Storage::get('json/license-types.json'), true),
            'softwareTypes' => json_decode(Storage::get('json/software-types.json'), true)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectSennovaBudgetRequest $request, Call $call, Project $project)
    {
        $this->authorize('create', [ProjectSennovaBudget::class]);

        // Validaciones
        // Línea 66
        if ($project->projectType->programmaticLine->code == 66) {
            if (BudgetValidationTrait::viaticsValidation($project->total_viatics, $request->value, $request->qty_items, 0, 0)) {
                return redirect()->back()->with('error', "La sumatoria de todos los rubros de viáticos no debe superar el valor de $4.000.000");
            }
        }

        $callBudget = CallBudget::find($request->call_budget_id);

        $projectSennovaBudget = new ProjectSennovaBudget();
        $projectSennovaBudget->description      = $request->description;
        $projectSennovaBudget->justification    = $request->justification;
        $projectSennovaBudget->value            = $request->value;
        $projectSennovaBudget->qty_items        = $request->qty_items;

        $projectSennovaBudget->project()->associate($project);
        $projectSennovaBudget->callBudget()->associate($callBudget);
        $projectSennovaBudget->save();

        if($request->get('budget_usage_code') == '2010100600203101') {
            $softwareInfo = new SoftwareInfo();
            $softwareInfo->license_type     = $request->get('license_type');
            $softwareInfo->software_type    = $request->get('software_type');
            $softwareInfo->fecha_incio       = $request->get('fecha_incio');
            $softwareInfo->fecha_finalizacion         = $request->get('fecha_finalizacion');
            
            $projectSennovaBudget->softwareInfo()->save($softwareInfo);
        }

        return redirect()->route('calls.projects.project-sennova-budgets.project-budget-batches.index', [$call, $project, $projectSennovaBudget])->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProjectSennovaBudget  $projectSennovaBudget
     * @return \Illuminate\Http\Response
     */
    public function show(Call $call, Project $project, ProjectSennovaBudget $projectSennovaBudget)
    {
        $this->authorize('view', [ProjectSennovaBudget::class, $projectSennovaBudget]);

        return Inertia::render('Calls/Projects/ProjectSennovaBudgets/Show', [
            'projectSennovaBudget' => $projectSennovaBudget
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProjectSennovaBudget  $projectSennovaBudget
     * @return \Illuminate\Http\Response
     */
    public function edit(Call $call, Project $project, ProjectSennovaBudget $projectSennovaBudget)
    {
        $this->authorize('update', [ProjectSennovaBudget::class, $projectSennovaBudget]);

        $projectSennovaBudget->softwareInfo;
        $projectSennovaBudget->callBudget->sennovaBudget->budgetUsage;
        $project->projectType->programmaticLine;

        return Inertia::render('Calls/Projects/ProjectSennovaBudgets/Editar', [
            'call'                  => $call->only('id'),
            'project'               => $project,
            'projectSennovaBudget'  => $projectSennovaBudget,
            'licenceTypes'          => json_decode(Storage::get('json/license-types.json'), true),
            'softwareTypes'         => json_decode(Storage::get('json/software-types.json'), true)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProjectSennovaBudget  $projectSennovaBudget
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Call $call, Project $project, ProjectSennovaBudget $projectSennovaBudget)
    {
        $this->authorize('update', [ProjectSennovaBudget::class, $projectSennovaBudget]);

        // Validaciones
        // Línea 66
        if ($project->projectType->programmaticLine->code == 66) {
            if (BudgetValidationTrait::viaticsValidation($project->total_viatics, $request->value, $request->qty_items, $projectSennovaBudget->value, $projectSennovaBudget->qty_items)) {
                return redirect()->back()->with('error', "La sumatoria de todos los rubros de viáticos no debe superar el valor de $4.000.000");
            }
        }

        $callBudget = CallBudget::find($request->call_budget_id);

        $projectSennovaBudget->description      = $request->description;
        $projectSennovaBudget->justification    = $request->justification;
        $projectSennovaBudget->value            = null;
        $projectSennovaBudget->qty_items        = null;

        if (!$callBudget->sennovaBudget->requires_market_research) {
            foreach ($projectSennovaBudget->projectBudgetBatches as $projectBudgetBatch) {
                Storage::delete($projectBudgetBatch->fact_sheet);

                foreach ($projectBudgetBatch->marketResearch as $marketResearch) {
                    Storage::delete($marketResearch->price_quote_file);
                }

                $projectBudgetBatch->delete();
            }

            $projectSennovaBudget->value      = $request->value;
            $projectSennovaBudget->qty_items  = $request->qty_items;
        }

        $projectSennovaBudget->project()->associate($project);
        $projectSennovaBudget->callBudget()->associate($callBudget);
        $projectSennovaBudget->save();

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProjectSennovaBudget  $projectSennovaBudget
     * @return \Illuminate\Http\Response
     */
    public function destroy(Call $call, Project $project, ProjectSennovaBudget $projectSennovaBudget)
    {
        $this->authorize('delete', [ProjectSennovaBudget::class, $projectSennovaBudget]);

        $projectSennovaBudget->delete();

        return redirect()->route('calls.projects.project-sennova-budgets.index', [$call, $project])->with('success', 'The resource has been deleted successfully.');
    }
}
