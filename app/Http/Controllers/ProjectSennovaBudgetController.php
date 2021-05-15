<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectSennovaBudgetRequest;
use App\Models\Convocatoria;
use App\Models\ConvocatoriaBudget;
use App\Models\SecondBudgetInfo;
use App\Models\Proyecto;
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
    public function index(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('viewAny', ProjectSennovaBudget::class);

        $proyecto->projectType->programmaticLine;
        $proyecto->makeHidden(
            'rdi', 
            'projectSennovaBudgets', 
            'updated_at',
        );

        return Inertia::render('Convocatorias/Proyectos/ProjectSennovaBudgets/Index', [
            'convocatoria'                  => $convocatoria->only('id'),
            'proyecto'              => $proyecto,
            'filters'               => request()->all('search'),
            'projectSennovaBudgets' => ProjectSennovaBudget::where('proyecto_id', $proyecto->id)->filterProjectSennovaBudget(request()->only('search'))->with('convocatoriaBudget.sennovaBudget.thirdBudgetInfo:id,nombre', 'convocatoriaBudget.sennovaBudget.secondBudgetInfo:id,nombre', 'convocatoriaBudget.sennovaBudget.budgetUsage:id,description')->paginate(),
            'secondBudgetInfo'      => SecondBudgetInfo::orderBy('nombre', 'ASC')->get('nombre'),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('create', [ProjectSennovaBudget::class]);

        $proyecto->projectType->programmaticLine->only('id');

        return Inertia::render('Convocatorias/Proyectos/ProjectSennovaBudgets/Create', [
            'convocatoria'          => $convocatoria,
            'proyecto'      => $proyecto,
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
    public function store(ProjectSennovaBudgetRequest $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('create', [ProjectSennovaBudget::class]);

        // Validaciones
        // Línea 66
        if ($proyecto->projectType->programmaticLine->code == 66) {
            if (BudgetValidationTrait::viaticsValidation($proyecto->total_viatics, $request->value, $request->qty_items, 0, 0)) {
                return redirect()->back()->with('error', "La sumatoria de todos los rubros de viáticos no debe superar el valor de $4.000.000");
            }
        }

        $convocatoriaBudget = ConvocatoriaBudget::find($request->convocatoria_budget_id);

        $proyectoSennovaBudget = new ProjectSennovaBudget();
        $proyectoSennovaBudget->description      = $request->description;
        $proyectoSennovaBudget->justification    = $request->justification;
        $proyectoSennovaBudget->value            = $request->value;
        $proyectoSennovaBudget->qty_items        = $request->qty_items;

        $proyectoSennovaBudget->project()->associate($proyecto);
        $proyectoSennovaBudget->convocatoriaBudget()->associate($convocatoriaBudget);
        $proyectoSennovaBudget->save();

        if($request->get('budget_usage_code') == '2010100600203101') {
            $softwareInfo = new SoftwareInfo();
            $softwareInfo->license_type     = $request->get('license_type');
            $softwareInfo->software_type    = $request->get('software_type');
            $softwareInfo->fecha_inicio       = $request->get('fecha_inicio');
            $softwareInfo->fecha_finalizacion         = $request->get('fecha_finalizacion');
            
            $proyectoSennovaBudget->softwareInfo()->save($softwareInfo);
        }

        return redirect()->route('convocatorias.projects.project-sennova-budgets.project-budget-batches.index', [$convocatoria, $proyecto, $proyectoSennovaBudget])->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProjectSennovaBudget  $proyectoSennovaBudget
     * @return \Illuminate\Http\Response
     */
    public function show(Convocatoria $convocatoria, Proyecto $proyecto, ProjectSennovaBudget $proyectoSennovaBudget)
    {
        $this->authorize('view', [ProjectSennovaBudget::class, $proyectoSennovaBudget]);

        return Inertia::render('Convocatorias/Proyectos/ProjectSennovaBudgets/Show', [
            'projectSennovaBudget' => $proyectoSennovaBudget
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProjectSennovaBudget  $proyectoSennovaBudget
     * @return \Illuminate\Http\Response
     */
    public function edit(Convocatoria $convocatoria, Proyecto $proyecto, ProjectSennovaBudget $proyectoSennovaBudget)
    {
        $this->authorize('update', [ProjectSennovaBudget::class, $proyectoSennovaBudget]);

        $proyectoSennovaBudget->softwareInfo;
        $proyectoSennovaBudget->convocatoriaBudget->sennovaBudget->budgetUsage;
        $proyecto->projectType->programmaticLine;

        return Inertia::render('Convocatorias/Proyectos/ProjectSennovaBudgets/Editar', [
            'convocatoria'                  => $convocatoria->only('id'),
            'proyecto'              => $proyecto,
            'projectSennovaBudget'  => $proyectoSennovaBudget,
            'licenceTypes'          => json_decode(Storage::get('json/license-types.json'), true),
            'softwareTypes'         => json_decode(Storage::get('json/software-types.json'), true)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProjectSennovaBudget  $proyectoSennovaBudget
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Convocatoria $convocatoria, Proyecto $proyecto, ProjectSennovaBudget $proyectoSennovaBudget)
    {
        $this->authorize('update', [ProjectSennovaBudget::class, $proyectoSennovaBudget]);

        // Validaciones
        // Línea 66
        if ($proyecto->projectType->programmaticLine->code == 66) {
            if (BudgetValidationTrait::viaticsValidation($proyecto->total_viatics, $request->value, $request->qty_items, $proyectoSennovaBudget->value, $proyectoSennovaBudget->qty_items)) {
                return redirect()->back()->with('error', "La sumatoria de todos los rubros de viáticos no debe superar el valor de $4.000.000");
            }
        }

        $convocatoriaBudget = ConvocatoriaBudget::find($request->convocatoria_budget_id);

        $proyectoSennovaBudget->description      = $request->description;
        $proyectoSennovaBudget->justification    = $request->justification;
        $proyectoSennovaBudget->value            = null;
        $proyectoSennovaBudget->qty_items        = null;

        if (!$convocatoriaBudget->sennovaBudget->requires_market_research) {
            foreach ($proyectoSennovaBudget->projectBudgetBatches as $proyectoBudgetBatch) {
                Storage::delete($proyectoBudgetBatch->fact_sheet);

                foreach ($proyectoBudgetBatch->marketResearch as $marketResearch) {
                    Storage::delete($marketResearch->price_quote_file);
                }

                $proyectoBudgetBatch->delete();
            }

            $proyectoSennovaBudget->value      = $request->value;
            $proyectoSennovaBudget->qty_items  = $request->qty_items;
        }

        $proyectoSennovaBudget->project()->associate($proyecto);
        $proyectoSennovaBudget->convocatoriaBudget()->associate($convocatoriaBudget);
        $proyectoSennovaBudget->save();

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProjectSennovaBudget  $proyectoSennovaBudget
     * @return \Illuminate\Http\Response
     */
    public function destroy(Convocatoria $convocatoria, Proyecto $proyecto, ProjectSennovaBudget $proyectoSennovaBudget)
    {
        $this->authorize('delete', [ProjectSennovaBudget::class, $proyectoSennovaBudget]);

        $proyectoSennovaBudget->delete();

        return redirect()->route('convocatorias.projects.project-sennova-budgets.index', [$convocatoria, $proyecto])->with('success', 'The resource has been deleted successfully.');
    }
}
