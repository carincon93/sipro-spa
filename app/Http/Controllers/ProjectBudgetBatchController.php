<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectBudgetBatchRequest;
use App\Models\Call;
use App\Models\Project;
use App\Models\ProjectSennovaBudget;
use App\Models\ProjectBudgetBatch;
use App\Models\MarketResearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\BudgetValidationTrait;
use Inertia\Inertia;

class ProjectBudgetBatchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Call $call, Project $project, ProjectSennovaBudget $projectSennovaBudget)
    {
        $this->authorize('viewAny', [ProjectBudgetBatch::class]);

        // Denega si el rubro no requiere lotes y ya hay un estudio de mercado guardado o si el rubro no requiere de estudio de mercado.
        if (!$projectSennovaBudget->callBudget->sennovaBudget->requires_market_research) {
            return redirect()->route('calls.projects.project-sennova-budgets.index', [$call, $project])->with('success', 'The resource has been created successfully.');
        }

        return Inertia::render('Calls/Projects/ProjectSennovaBudgets/MarketResearch/Index', [
            'filters'               => request()->all('search'),
            'projectBudgetBatches'  => $projectSennovaBudget->projectBudgetBatches()
                ->with('marketResearch')
                ->filterProjectBudgetBatch(request()->only('search'))->paginate(),
            'call'                          => $call->only('id'),
            'project'                       => $project->only('id', 'percentage_industrial_machinery'),
            'projectSennovaBudget'          => $projectSennovaBudget->only('id', 'average'),
            'sennovaBudget'                 => $projectSennovaBudget->callBudget->sennovaBudget->only('id', 'message'),
            'budgetUsage'                   => $projectSennovaBudget->callBudget->sennovaBudget->budgetUsage->only('id', 'description'),
            'callBudget'                    => $projectSennovaBudget->callBudget->only('id'),
            'requiresMarketResearch'        => $projectSennovaBudget->callBudget->sennovaBudget->requires_market_research,
            'requiresMarketResearchBatch'   => $projectSennovaBudget->callBudget->sennovaBudget->requires_market_research_batch,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Call $call, Project $project, ProjectSennovaBudget $projectSennovaBudget)
    {
        $this->authorize('create', [ProjectBudgetBatch::class]);

        return redirect()->route('calls.projects.project-sennova-budgets.project-budget-batches.index', [$call, $project, $projectSennovaBudget]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectBudgetBatchRequest $request, Call $call, Project $project, ProjectSennovaBudget $projectSennovaBudget)
    {
        $this->authorize('create', [ProjectBudgetBatch::class]);

        // Denega si el rubro no requiere lotes y ya hay un estudio de mercado guardado o si el rubro no requiere de estudio de mercado.
        if (!$projectSennovaBudget->callBudget->sennovaBudget->requires_market_research_batch && $projectSennovaBudget->projectBudgetBatches->count() > 0 || !$projectSennovaBudget->callBudget->sennovaBudget->requires_market_research) {
            return redirect()->route('calls.projects.project-sennova-budgets.index', [$call, $project]);
        }

        // Validaciones
        // Línea 66
        if ($project->projectType->programmaticLine->code == 66) {
            // Trae el porcentaje calculado del rubro de "MAQUINARIA INDUSTRIAL"
            $percentageIndustrialMachinery = $project->percentage_industrial_machinery;
            if (BudgetValidationTrait::specialConstructionServicesValidation($request->first_price_quote, $request->second_price_quote, $request->third_price_quote, $projectSennovaBudget->callBudget->sennovaBudget->budgetUsage->code, $project->total_special_construction_services, 0, $percentageIndustrialMachinery)) {
                return redirect()->back()->with('error', "Este estudio de mercado supera el 5% ($ {$percentageIndustrialMachinery} COP) del total del rubro 'Maquinaria industrial'. Vuelva a diligenciar.");
            }

            $projectPercentage = $project->total_project_budget * 0.05;
            if (BudgetValidationTrait::totalProjectBudgetValidation($project->total_project_budget, 0.05, $project->total_machinery_maintenance, $request->first_price_quote, $request->second_price_quote, $request->third_price_quote, $projectSennovaBudget->callBudget->sennovaBudget->budgetUsage->code, 0)) {
                return redirect()->back()->with('error', "Este estudio de mercado supera el 5% del ($ {$projectPercentage}) COP total del proyecto. Vuelva a diligenciar.");
            }
        }

        $projectBudgetBatch = new ProjectBudgetBatch();
        $projectBudgetBatch->qty_items = $request->qty_items;

        $secondBudgetInfoName   = Str::slug(substr($projectSennovaBudget->callBudget->sennovaBudget->secondBudgetInfo->name, 0, 30), '-');
        $random = Str::random(5);

        $factSheet = $request->fact_sheet;

        $factSheetFileName  = "$project->code-ficha-tecnica-$secondBudgetInfoName-cod$random.".$factSheet->extension();
        $factSheetFile      = $factSheet->storeAs(
            'fact-sheets', $factSheetFileName
        );
        $projectBudgetBatch->fact_sheet = $factSheetFile;

        $projectBudgetBatch->projectSennovaBudget()->associate($projectSennovaBudget);
        $projectBudgetBatch->save();

        // Primer estudio de mercado

        $marketResearch = new MarketResearch();

        $marketResearch->price_quote    = $request->first_price_quote;
        $marketResearch->company_name   = $request->first_company_name;

        $firstCompanyName   = Str::slug(substr($request->first_company_name, 0, 30), '-');

        $requestFirstPriceQuoteFile = $request->first_price_quote_file;

        $firstPriceQuoteFileName = "$project->code-estudio-de-mercado-$firstCompanyName-cod".Str::random(5).".".$requestFirstPriceQuoteFile->extension();
        $firstPriceQuoteFile = $requestFirstPriceQuoteFile->storeAs(
            'market-research', $firstPriceQuoteFileName
        );
        $marketResearch->price_quote_file = $firstPriceQuoteFile;

        $marketResearch->projectBudgetBatch()->associate($projectBudgetBatch);
        $marketResearch->save();

        // Segundo estudio de mercado

        $marketResearch = new MarketResearch();

        $marketResearch->price_quote    = $request->second_price_quote;
        $marketResearch->company_name   = $request->second_company_name;

        $secondCompanyName   = Str::slug(substr($request->second_company_name, 0, 30), '-');

        $requestFirstPriceQuoteFile = $request->second_price_quote_file;

        $secondPriceQuoteFileName   = "$project->code-estudio-de-mercado-$secondCompanyName-cod".Str::random(5).".".$requestFirstPriceQuoteFile->extension();
        $secondPriceQuoteFile       = $requestFirstPriceQuoteFile->storeAs(
            'market-research', $secondPriceQuoteFileName
        );
        $marketResearch->price_quote_file = $secondPriceQuoteFile;

        $marketResearch->projectBudgetBatch()->associate($projectBudgetBatch);
        $marketResearch->save();

        if ($request->third_price_quote && $request->third_company_name && $request->hasFile('third_price_quote_file')) {
            // Tercer estudio de mercado

            $marketResearch = new MarketResearch();

            $marketResearch->price_quote    = $request->third_price_quote;
            $marketResearch->company_name   = $request->third_company_name;

            $thirdCompanyName   = Str::slug(substr($request->third_company_name, 0, 30), '-');

            $requestFirstPriceQuoteFile = $request->third_price_quote_file;

            $thirdPriceQuoteFileName = "$project->code-estudio-de-mercado-$thirdCompanyName-cod".Str::random(5).".".$requestFirstPriceQuoteFile->extension();
            $thirdPriceQuoteFile = $requestFirstPriceQuoteFile->storeAs(
                'market-research', $thirdPriceQuoteFileName
            );
            $marketResearch->price_quote_file = $thirdPriceQuoteFile;

            $marketResearch->projectBudgetBatch()->associate($projectBudgetBatch);
            $marketResearch->save();
        }

        if ($projectSennovaBudget->callBudget->sennovaBudget->requires_market_research_batch) {
            return redirect()->back()->with('success', 'The resource has been created successfully.');
        }

        return redirect()->route('calls.projects.project-sennova-budgets.index', [$call, $project])->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProjectBudgetBatch  $projectBudgetBatch
     * @return \Illuminate\Http\Response
     */
    public function show(Call $call, Project $project, ProjectSennovaBudget $projectSennovaBudget, ProjectBudgetBatch $projectBudgetBatch)
    {
        $this->authorize('view', [ProjectBudgetBatch::class, $projectBudgetBatch]);

        return Inertia::render('Calls/Projects/ProjectSennovaBudgets/ProjectBudgetBatches/Show', [
            'projectBudgetBatch' => $projectBudgetBatch
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProjectBudgetBatch  $projectBudgetBatch
     * @return \Illuminate\Http\Response
     */
    public function edit(Call $call, Project $project, ProjectSennovaBudget $projectSennovaBudget, ProjectBudgetBatch $projectBudgetBatch)
    {
        $this->authorize('update', [ProjectBudgetBatch::class, $projectBudgetBatch]);

        // Denega si el rubro no requiere lotes y ya hay un estudio de mercado guardado o si el rubro no requiere de estudio de mercado.
        if (!$projectSennovaBudget->callBudget->sennovaBudget->requires_market_research) {
            return redirect()->route('calls.projects.project-sennova-budgets.index', [$call, $project])->with('success', 'The resource has been created successfully.');
        }

        $projectSennovaBudget->callBudget->sennovaBudget->budgetUsage;
        $projectBudgetBatch->marketResearch;

        return Inertia::render('Calls/Projects/ProjectSennovaBudgets/MarketResearch/Editar', [
            'call'                  => $call,
            'project'               => $project,
            'projectSennovaBudget'  => $projectSennovaBudget,
            'projectBudgetBatch'    => $projectBudgetBatch
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProjectBudgetBatch  $projectBudgetBatch
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectBudgetBatchRequest $request, Call $call, Project $project, ProjectSennovaBudget $projectSennovaBudget, ProjectBudgetBatch $projectBudgetBatch)
    {
        $this->authorize('update', [ProjectBudgetBatch::class, $projectBudgetBatch]);

        // Denega si el rubro no requiere lotes y ya hay un estudio de mercado guardado o si el rubro no requiere de estudio de mercado.
        if (!$projectSennovaBudget->callBudget->sennovaBudget->requires_market_research) {
            return redirect()->route('calls.projects.project-sennova-budgets.index', [$call, $project]);
        }

        // Validaciones
        // Línea 66
        if ($project->projectType->programmaticLine->code == 66) {
            // Trae el porcentaje calculado del rubro de "MAQUINARIA INDUSTRIAL"
            $percentageIndustrialMachinery = $project->percentage_industrial_machinery;
            if (BudgetValidationTrait::specialConstructionServicesValidation($request->first_price_quote, $request->second_price_quote, $request->third_price_quote, $projectSennovaBudget->callBudget->sennovaBudget->budgetUsage->code, $project->total_special_construction_services, $projectSennovaBudget->average, $percentageIndustrialMachinery)) {
                return redirect()->back()->with('error', "Este estudio de mercado supera el 5% ($ {$percentageIndustrialMachinery} COP) del total del rubro 'Maquinaria industrial'. Vuelva a diligenciar.");
            }

            $projectPercentage = $project->total_project_budget * 0.05;
            if (BudgetValidationTrait::totalProjectBudgetValidation($project->total_project_budget, 0.05, $project->total_machinery_maintenance, $request->first_price_quote, $request->second_price_quote, $request->third_price_quote, $projectSennovaBudget->callBudget->sennovaBudget->budgetUsage->code, $projectSennovaBudget->average)) {
                return redirect()->back()->with('error', "Este estudio de mercado supera el 5% del ($ {$projectPercentage}) COP total del proyecto. Vuelva a diligenciar.");
            }
        }

        $projectBudgetBatch->qty_items = $request->qty_items;
        if ($request->hasFile('fact_sheet')) {
            Storage::delete($projectBudgetBatch->fact_sheet);
            $secondBudgetInfoName   = Str::slug(substr($projectSennovaBudget->callBudget->sennovaBudget->secondBudgetInfo->name, 0, 30), '-');
            $random                 = Str::random(5);
            $factSheet              = $request->fact_sheet;
            $factSheetFileName      = "$project->code-ficha-tecnica-$secondBudgetInfoName-cod$random.".$factSheet->extension();
            $factSheetFile          = $factSheet->storeAs(
                'fact-sheets', $factSheetFileName
            );
            $projectBudgetBatch->fact_sheet = $factSheetFile;
        }
        $projectBudgetBatch->projectSennovaBudget()->associate($projectSennovaBudget);
        $projectBudgetBatch->save();


        // ========================================================

        // Primer estudio de mercado
        $firstMarketResearch = $projectBudgetBatch->marketResearch()->where('id', $request->first_market_research_id)->first();
        if ($request->hasFile('first_price_quote_file')) {
            Storage::delete($firstMarketResearch->price_quote_file);
            $firstCompanyName           = Str::slug(substr($request->first_company_name, 0, 30), '-');
            $requestFirstPriceQuoteFile = $request->first_price_quote_file;
            $firstPriceQuoteFileName    = "$project->code-estudio-de-mercado-$firstCompanyName-cod".Str::random(5).".".$requestFirstPriceQuoteFile->extension();
            $firstPriceQuoteFile        = $requestFirstPriceQuoteFile->storeAs(
                'market-research', $firstPriceQuoteFileName
            );
        }

        $firstMarketResearch->update(
            [
                'price_quote_file'  => $firstPriceQuoteFile ?? $firstMarketResearch->price_quote_file,
                'price_quote'       => $request->first_price_quote,
                'company_name'      => $request->first_company_name,
            ]
        );

        // ========================================================

        // Segundo estudio de mercado
        $secondMarketResearch = $projectBudgetBatch->marketResearch()->where('id', $request->second_market_research_id)->first();
        if ($request->hasFile('second_price_quote_file')) {
            Storage::delete($firstMarketResearch->second_price_quote_file);
            $secondCompanyName          = Str::slug(substr($request->second_company_name, 0, 30), '-');
            $requestFirstPriceQuoteFile = $request->second_price_quote_file;
            $secondPriceQuoteFileName   = "$project->code-estudio-de-mercado-$secondCompanyName-cod".Str::random(5).".".$requestFirstPriceQuoteFile->extension();
            $secondPriceQuoteFile       = $requestFirstPriceQuoteFile->storeAs(
                'market-research', $secondPriceQuoteFileName
            );
        }

        $secondMarketResearch->update(
            [
                'price_quote_file'  => $secondPriceQuoteFile ?? $secondMarketResearch->price_quote_file,
                'price_quote'       => $request->second_price_quote,
                'company_name'      => $request->second_company_name,
            ]
        );

        // ========================================================

        // Tercer estudio de mercado
        $thirdMarketResearch = $projectBudgetBatch->marketResearch()->where('id', $request->third_market_research_id)->first();
        if ($request->requires_third_market_research == '0' && $thirdMarketResearch) {
            Storage::delete($thirdMarketResearch->third_price_quote_file);
            $thirdMarketResearch->delete();
        } else {
            if ($request->hasFile('third_price_quote_file')) {
                $thirdCompanyName           = Str::slug(substr($request->third_company_name, 0, 30), '-');
                $requestFirstPriceQuoteFile = $request->third_price_quote_file;
                $thirdPriceQuoteFileName    = "$project->code-estudio-de-mercado-$thirdCompanyName-cod".Str::random(5).".".$requestFirstPriceQuoteFile->extension();
                $thirdPriceQuoteFile        = $requestFirstPriceQuoteFile->storeAs(
                    'market-research', $thirdPriceQuoteFileName
                );
            }
            if ($request->third_price_quote || $request->third_company_name) {


                $projectBudgetBatch->marketResearch()->where('id', $request->third_market_research_id)->updateOrCreate(
                    [
                        'id'                => $request->third_market_research_id,
                        'price_quote_file'  => $thirdPriceQuoteFile ?? $thirdMarketResearch->price_quote_file,
                        'price_quote'       => $request->third_price_quote,
                        'company_name'      => $request->third_company_name,
                    ]
                );
            }
        }

        return redirect()->route('calls.projects.project-sennova-budgets.project-budget-batches.index', [$call, $project, $projectSennovaBudget])->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProjectBudgetBatch  $projectBudgetBatch
     * @return \Illuminate\Http\Response
     */
    public function destroy(Call $call, Project $project, ProjectSennovaBudget $projectSennovaBudget, ProjectBudgetBatch $projectBudgetBatch)
    {
        $this->authorize('delete', [ProjectBudgetBatch::class, $projectBudgetBatch]);

        foreach ($projectBudgetBatch->marketResearch as $marketResearch) {
            Storage::delete($marketResearch->price_quote_file);
        }

        $projectBudgetBatch->delete();

        return redirect()->route('calls.projects.project-sennova-budgets.project-budget-batches.index', [$call, $project, $projectSennovaBudget])->with('success', 'The resource has been deleted successfully.');
    }

    /**
     * download
     *
     * @param  mixed $call
     * @param  mixed $project
     * @param  mixed $projectSennovaBudget
     * @param  mixed $projectBudgetBatch
     * @return void
     */
    public function download(Call $call, Project $project, ProjectSennovaBudget $projectSennovaBudget, ProjectBudgetBatch $projectBudgetBatch)
    {
        return response()->download(storage_path("app/$projectBudgetBatch->fact_sheet"));
    }

    /**
     * download
     *
     * @param  mixed $call
     * @param  mixed $project
     * @param  mixed $projectSennovaBudget
     * @param  mixed $marketResearch
     * @return void
     */
    public function downloadPriceQuoteFile(Call $call, Project $project, ProjectSennovaBudget $projectSennovaBudget, MarketResearch $marketResearch)
    {
        return response()->download(storage_path("app/$marketResearch->price_quote_file"));
    }
}
