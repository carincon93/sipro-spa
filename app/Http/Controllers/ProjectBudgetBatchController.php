<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectBudgetBatchRequest;
use App\Models\Convocatoria;
use App\Models\Proyecto;
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
    public function index(Convocatoria $convocatoria, Proyecto $proyecto, ProjectSennovaBudget $proyectoSennovaBudget)
    {
        $this->authorize('viewAny', [ProjectBudgetBatch::class]);

        // Denega si el rubro no requiere lotes y ya hay un estudio de mercado guardado o si el rubro no requiere de estudio de mercado.
        if (!$proyectoSennovaBudget->convocatoriaBudget->sennovaBudget->requires_market_research) {
            return redirect()->route('convocatorias.projects.project-sennova-budgets.index', [$convocatoria, $proyecto])->with('success', 'The resource has been created successfully.');
        }

        return Inertia::render('Convocatorias/Proyectos/ProjectSennovaBudgets/MarketResearch/Index', [
            'filters'               => request()->all('search'),
            'projectBudgetBatches'  => $proyectoSennovaBudget->projectBudgetBatches()
                ->with('marketResearch')
                ->filterProjectBudgetBatch(request()->only('search'))->paginate(),
            'convocatoria'                          => $convocatoria->only('id'),
            'project'                       => $proyecto->only('id', 'percentage_industrial_machinery'),
            'projectSennovaBudget'          => $proyectoSennovaBudget->only('id', 'average'),
            'sennovaBudget'                 => $proyectoSennovaBudget->convocatoriaBudget->sennovaBudget->only('id', 'message'),
            'budgetUsage'                   => $proyectoSennovaBudget->convocatoriaBudget->sennovaBudget->budgetUsage->only('id', 'description'),
            'convocatoriaBudget'                    => $proyectoSennovaBudget->convocatoriaBudget->only('id'),
            'requiresMarketResearch'        => $proyectoSennovaBudget->convocatoriaBudget->sennovaBudget->requires_market_research,
            'requiresMarketResearchBatch'   => $proyectoSennovaBudget->convocatoriaBudget->sennovaBudget->requires_market_research_batch,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Convocatoria $convocatoria, Proyecto $proyecto, ProjectSennovaBudget $proyectoSennovaBudget)
    {
        $this->authorize('create', [ProjectBudgetBatch::class]);

        return redirect()->route('convocatorias.projects.project-sennova-budgets.project-budget-batches.index', [$convocatoria, $proyecto, $proyectoSennovaBudget]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectBudgetBatchRequest $request, Convocatoria $convocatoria, Proyecto $proyecto, ProjectSennovaBudget $proyectoSennovaBudget)
    {
        $this->authorize('create', [ProjectBudgetBatch::class]);

        // Denega si el rubro no requiere lotes y ya hay un estudio de mercado guardado o si el rubro no requiere de estudio de mercado.
        if (!$proyectoSennovaBudget->convocatoriaBudget->sennovaBudget->requires_market_research_batch && $proyectoSennovaBudget->projectBudgetBatches->count() > 0 || !$proyectoSennovaBudget->convocatoriaBudget->sennovaBudget->requires_market_research) {
            return redirect()->route('convocatorias.projects.project-sennova-budgets.index', [$convocatoria, $proyecto]);
        }

        // Validaciones
        // Línea 66
        if ($proyecto->projectType->programmaticLine->code == 66) {
            // Trae el porcentaje calculado del rubro de "MAQUINARIA INDUSTRIAL"
            $percentageIndustrialMachinery = $proyecto->percentage_industrial_machinery;
            if (BudgetValidationTrait::specialConstructionServicesValidation($request->first_price_quote, $request->second_price_quote, $request->third_price_quote, $proyectoSennovaBudget->convocatoriaBudget->sennovaBudget->budgetUsage->code, $proyecto->total_special_construction_services, 0, $percentageIndustrialMachinery)) {
                return redirect()->back()->with('error', "Este estudio de mercado supera el 5% ($ {$percentageIndustrialMachinery} COP) del total del rubro 'Maquinaria industrial'. Vuelva a diligenciar.");
            }

            $proyectoPercentage = $proyecto->total_project_budget * 0.05;
            if (BudgetValidationTrait::totalProjectBudgetValidation($proyecto->total_project_budget, 0.05, $proyecto->total_machinery_maintenance, $request->first_price_quote, $request->second_price_quote, $request->third_price_quote, $proyectoSennovaBudget->convocatoriaBudget->sennovaBudget->budgetUsage->code, 0)) {
                return redirect()->back()->with('error', "Este estudio de mercado supera el 5% del ($ {$proyectoPercentage}) COP total del proyecto. Vuelva a diligenciar.");
            }
        }

        $proyectoBudgetBatch = new ProjectBudgetBatch();
        $proyectoBudgetBatch->qty_items = $request->qty_items;

        $secondBudgetInfoName   = Str::slug(substr($proyectoSennovaBudget->convocatoriaBudget->sennovaBudget->secondBudgetInfo->name, 0, 30), '-');
        $random = Str::random(5);

        $factSheet = $request->fact_sheet;

        $factSheetFileName  = "$proyecto->code-ficha-tecnica-$secondBudgetInfoName-cod$random.".$factSheet->extension();
        $factSheetFile      = $factSheet->storeAs(
            'fact-sheets', $factSheetFileName
        );
        $proyectoBudgetBatch->fact_sheet = $factSheetFile;

        $proyectoBudgetBatch->projectSennovaBudget()->associate($proyectoSennovaBudget);
        $proyectoBudgetBatch->save();

        // Primer estudio de mercado

        $marketResearch = new MarketResearch();

        $marketResearch->price_quote    = $request->first_price_quote;
        $marketResearch->company_name   = $request->first_company_name;

        $firstCompanyName   = Str::slug(substr($request->first_company_name, 0, 30), '-');

        $requestFirstPriceQuoteFile = $request->first_price_quote_file;

        $firstPriceQuoteFileName = "$proyecto->code-estudio-de-mercado-$firstCompanyName-cod".Str::random(5).".".$requestFirstPriceQuoteFile->extension();
        $firstPriceQuoteFile = $requestFirstPriceQuoteFile->storeAs(
            'market-research', $firstPriceQuoteFileName
        );
        $marketResearch->price_quote_file = $firstPriceQuoteFile;

        $marketResearch->projectBudgetBatch()->associate($proyectoBudgetBatch);
        $marketResearch->save();

        // Segundo estudio de mercado

        $marketResearch = new MarketResearch();

        $marketResearch->price_quote    = $request->second_price_quote;
        $marketResearch->company_name   = $request->second_company_name;

        $secondCompanyName   = Str::slug(substr($request->second_company_name, 0, 30), '-');

        $requestFirstPriceQuoteFile = $request->second_price_quote_file;

        $secondPriceQuoteFileName   = "$proyecto->code-estudio-de-mercado-$secondCompanyName-cod".Str::random(5).".".$requestFirstPriceQuoteFile->extension();
        $secondPriceQuoteFile       = $requestFirstPriceQuoteFile->storeAs(
            'market-research', $secondPriceQuoteFileName
        );
        $marketResearch->price_quote_file = $secondPriceQuoteFile;

        $marketResearch->projectBudgetBatch()->associate($proyectoBudgetBatch);
        $marketResearch->save();

        if ($request->third_price_quote && $request->third_company_name && $request->hasFile('third_price_quote_file')) {
            // Tercer estudio de mercado

            $marketResearch = new MarketResearch();

            $marketResearch->price_quote    = $request->third_price_quote;
            $marketResearch->company_name   = $request->third_company_name;

            $thirdCompanyName   = Str::slug(substr($request->third_company_name, 0, 30), '-');

            $requestFirstPriceQuoteFile = $request->third_price_quote_file;

            $thirdPriceQuoteFileName = "$proyecto->code-estudio-de-mercado-$thirdCompanyName-cod".Str::random(5).".".$requestFirstPriceQuoteFile->extension();
            $thirdPriceQuoteFile = $requestFirstPriceQuoteFile->storeAs(
                'market-research', $thirdPriceQuoteFileName
            );
            $marketResearch->price_quote_file = $thirdPriceQuoteFile;

            $marketResearch->projectBudgetBatch()->associate($proyectoBudgetBatch);
            $marketResearch->save();
        }

        if ($proyectoSennovaBudget->convocatoriaBudget->sennovaBudget->requires_market_research_batch) {
            return redirect()->back()->with('success', 'The resource has been created successfully.');
        }

        return redirect()->route('convocatorias.projects.project-sennova-budgets.index', [$convocatoria, $proyecto])->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProjectBudgetBatch  $proyectoBudgetBatch
     * @return \Illuminate\Http\Response
     */
    public function show(Convocatoria $convocatoria, Proyecto $proyecto, ProjectSennovaBudget $proyectoSennovaBudget, ProjectBudgetBatch $proyectoBudgetBatch)
    {
        $this->authorize('view', [ProjectBudgetBatch::class, $proyectoBudgetBatch]);

        return Inertia::render('Convocatorias/Proyectos/ProjectSennovaBudgets/ProjectBudgetBatches/Show', [
            'projectBudgetBatch' => $proyectoBudgetBatch
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProjectBudgetBatch  $proyectoBudgetBatch
     * @return \Illuminate\Http\Response
     */
    public function edit(Convocatoria $convocatoria, Proyecto $proyecto, ProjectSennovaBudget $proyectoSennovaBudget, ProjectBudgetBatch $proyectoBudgetBatch)
    {
        $this->authorize('update', [ProjectBudgetBatch::class, $proyectoBudgetBatch]);

        // Denega si el rubro no requiere lotes y ya hay un estudio de mercado guardado o si el rubro no requiere de estudio de mercado.
        if (!$proyectoSennovaBudget->convocatoriaBudget->sennovaBudget->requires_market_research) {
            return redirect()->route('convocatorias.projects.project-sennova-budgets.index', [$convocatoria, $proyecto])->with('success', 'The resource has been created successfully.');
        }

        $proyectoSennovaBudget->convocatoriaBudget->sennovaBudget->budgetUsage;
        $proyectoBudgetBatch->marketResearch;

        return Inertia::render('Convocatorias/Proyectos/ProjectSennovaBudgets/MarketResearch/Editar', [
            'convocatoria'                  => $convocatoria,
            'project'               => $proyecto,
            'projectSennovaBudget'  => $proyectoSennovaBudget,
            'projectBudgetBatch'    => $proyectoBudgetBatch
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProjectBudgetBatch  $proyectoBudgetBatch
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectBudgetBatchRequest $request, Convocatoria $convocatoria, Proyecto $proyecto, ProjectSennovaBudget $proyectoSennovaBudget, ProjectBudgetBatch $proyectoBudgetBatch)
    {
        $this->authorize('update', [ProjectBudgetBatch::class, $proyectoBudgetBatch]);

        // Denega si el rubro no requiere lotes y ya hay un estudio de mercado guardado o si el rubro no requiere de estudio de mercado.
        if (!$proyectoSennovaBudget->convocatoriaBudget->sennovaBudget->requires_market_research) {
            return redirect()->route('convocatorias.projects.project-sennova-budgets.index', [$convocatoria, $proyecto]);
        }

        // Validaciones
        // Línea 66
        if ($proyecto->projectType->programmaticLine->code == 66) {
            // Trae el porcentaje calculado del rubro de "MAQUINARIA INDUSTRIAL"
            $percentageIndustrialMachinery = $proyecto->percentage_industrial_machinery;
            if (BudgetValidationTrait::specialConstructionServicesValidation($request->first_price_quote, $request->second_price_quote, $request->third_price_quote, $proyectoSennovaBudget->convocatoriaBudget->sennovaBudget->budgetUsage->code, $proyecto->total_special_construction_services, $proyectoSennovaBudget->average, $percentageIndustrialMachinery)) {
                return redirect()->back()->with('error', "Este estudio de mercado supera el 5% ($ {$percentageIndustrialMachinery} COP) del total del rubro 'Maquinaria industrial'. Vuelva a diligenciar.");
            }

            $proyectoPercentage = $proyecto->total_project_budget * 0.05;
            if (BudgetValidationTrait::totalProjectBudgetValidation($proyecto->total_project_budget, 0.05, $proyecto->total_machinery_maintenance, $request->first_price_quote, $request->second_price_quote, $request->third_price_quote, $proyectoSennovaBudget->convocatoriaBudget->sennovaBudget->budgetUsage->code, $proyectoSennovaBudget->average)) {
                return redirect()->back()->with('error', "Este estudio de mercado supera el 5% del ($ {$proyectoPercentage}) COP total del proyecto. Vuelva a diligenciar.");
            }
        }

        $proyectoBudgetBatch->qty_items = $request->qty_items;
        if ($request->hasFile('fact_sheet')) {
            Storage::delete($proyectoBudgetBatch->fact_sheet);
            $secondBudgetInfoName   = Str::slug(substr($proyectoSennovaBudget->convocatoriaBudget->sennovaBudget->secondBudgetInfo->name, 0, 30), '-');
            $random                 = Str::random(5);
            $factSheet              = $request->fact_sheet;
            $factSheetFileName      = "$proyecto->code-ficha-tecnica-$secondBudgetInfoName-cod$random.".$factSheet->extension();
            $factSheetFile          = $factSheet->storeAs(
                'fact-sheets', $factSheetFileName
            );
            $proyectoBudgetBatch->fact_sheet = $factSheetFile;
        }
        $proyectoBudgetBatch->projectSennovaBudget()->associate($proyectoSennovaBudget);
        $proyectoBudgetBatch->save();


        // ========================================================

        // Primer estudio de mercado
        $firstMarketResearch = $proyectoBudgetBatch->marketResearch()->where('id', $request->first_market_research_id)->first();
        if ($request->hasFile('first_price_quote_file')) {
            Storage::delete($firstMarketResearch->price_quote_file);
            $firstCompanyName           = Str::slug(substr($request->first_company_name, 0, 30), '-');
            $requestFirstPriceQuoteFile = $request->first_price_quote_file;
            $firstPriceQuoteFileName    = "$proyecto->code-estudio-de-mercado-$firstCompanyName-cod".Str::random(5).".".$requestFirstPriceQuoteFile->extension();
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
        $secondMarketResearch = $proyectoBudgetBatch->marketResearch()->where('id', $request->second_market_research_id)->first();
        if ($request->hasFile('second_price_quote_file')) {
            Storage::delete($firstMarketResearch->second_price_quote_file);
            $secondCompanyName          = Str::slug(substr($request->second_company_name, 0, 30), '-');
            $requestFirstPriceQuoteFile = $request->second_price_quote_file;
            $secondPriceQuoteFileName   = "$proyecto->code-estudio-de-mercado-$secondCompanyName-cod".Str::random(5).".".$requestFirstPriceQuoteFile->extension();
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
        $thirdMarketResearch = $proyectoBudgetBatch->marketResearch()->where('id', $request->third_market_research_id)->first();
        if ($request->requires_third_market_research == '0' && $thirdMarketResearch) {
            Storage::delete($thirdMarketResearch->third_price_quote_file);
            $thirdMarketResearch->delete();
        } else {
            if ($request->hasFile('third_price_quote_file')) {
                $thirdCompanyName           = Str::slug(substr($request->third_company_name, 0, 30), '-');
                $requestFirstPriceQuoteFile = $request->third_price_quote_file;
                $thirdPriceQuoteFileName    = "$proyecto->code-estudio-de-mercado-$thirdCompanyName-cod".Str::random(5).".".$requestFirstPriceQuoteFile->extension();
                $thirdPriceQuoteFile        = $requestFirstPriceQuoteFile->storeAs(
                    'market-research', $thirdPriceQuoteFileName
                );
            }
            if ($request->third_price_quote || $request->third_company_name) {


                $proyectoBudgetBatch->marketResearch()->where('id', $request->third_market_research_id)->updateOrCreate(
                    [
                        'id'                => $request->third_market_research_id,
                        'price_quote_file'  => $thirdPriceQuoteFile ?? $thirdMarketResearch->price_quote_file,
                        'price_quote'       => $request->third_price_quote,
                        'company_name'      => $request->third_company_name,
                    ]
                );
            }
        }

        return redirect()->route('convocatorias.projects.project-sennova-budgets.project-budget-batches.index', [$convocatoria, $proyecto, $proyectoSennovaBudget])->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProjectBudgetBatch  $proyectoBudgetBatch
     * @return \Illuminate\Http\Response
     */
    public function destroy(Convocatoria $convocatoria, Proyecto $proyecto, ProjectSennovaBudget $proyectoSennovaBudget, ProjectBudgetBatch $proyectoBudgetBatch)
    {
        $this->authorize('delete', [ProjectBudgetBatch::class, $proyectoBudgetBatch]);

        foreach ($proyectoBudgetBatch->marketResearch as $marketResearch) {
            Storage::delete($marketResearch->price_quote_file);
        }

        $proyectoBudgetBatch->delete();

        return redirect()->route('convocatorias.projects.project-sennova-budgets.project-budget-batches.index', [$convocatoria, $proyecto, $proyectoSennovaBudget])->with('success', 'The resource has been deleted successfully.');
    }

    /**
     * download
     *
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @param  mixed $proyectoSennovaBudget
     * @param  mixed $proyectoBudgetBatch
     * @return void
     */
    public function download(Convocatoria $convocatoria, Proyecto $proyecto, ProjectSennovaBudget $proyectoSennovaBudget, ProjectBudgetBatch $proyectoBudgetBatch)
    {
        return response()->download(storage_path("app/$proyectoBudgetBatch->fact_sheet"));
    }

    /**
     * download
     *
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @param  mixed $proyectoSennovaBudget
     * @param  mixed $marketResearch
     * @return void
     */
    public function downloadPriceQuoteFile(Convocatoria $convocatoria, Proyecto $proyecto, ProjectSennovaBudget $proyectoSennovaBudget, MarketResearch $marketResearch)
    {
        return response()->download(storage_path("app/$marketResearch->price_quote_file"));
    }
}
