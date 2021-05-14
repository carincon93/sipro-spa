<?php

namespace App\Http\Controllers;

use App\Http\Requests\BudgetUsageRequest;
use App\Models\BudgetUsage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BudgetUsageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', [BudgetUsage::class]);

        return Inertia::render('Budgets/BudgetUsages/Index', [
            'filters'   => request()->all('search'),
            'budgetUsages' => BudgetUsage::orderBy('description', 'ASC')
                ->filterBudgetUsage(request()->only('search'))->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', [BudgetUsage::class]);

        return Inertia::render('Budgets/BudgetUsages/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BudgetUsageRequest $request)
    {
        $this->authorize('create', [BudgetUsage::class]);

        $budgetUsage = new BudgetUsage();
        $budgetUsage->description   = $request->description;
        $budgetUsage->code          = $request->code;

        $budgetUsage->save();

        return redirect()->route('resourceRoute.index')->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BudgetUsage  $budgetUsage
     * @return \Illuminate\Http\Response
     */
    public function show(BudgetUsage $budgetUsage)
    {
        $this->authorize('view', [BudgetUsage::class, $budgetUsage]);

        return Inertia::render('Budgets/BudgetUsages/Show', [
            'budgetUsage' => $budgetUsage
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BudgetUsage  $budgetUsage
     * @return \Illuminate\Http\Response
     */
    public function edit(BudgetUsage $budgetUsage)
    {
        $this->authorize('update', [BudgetUsage::class, $budgetUsage]);

        return Inertia::render('Budgets/BudgetUsages/Editar', [
            'budgetUsage' => $budgetUsage
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BudgetUsage  $budgetUsage
     * @return \Illuminate\Http\Response
     */
    public function update(BudgetUsageRequest $request, BudgetUsage $budgetUsage)
    {
        $this->authorize('update', [BudgetUsage::class, $budgetUsage]);

        $budgetUsage->description   = $request->description;
        $budgetUsage->code          = $request->code;

        $budgetUsage->save();

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BudgetUsage  $budgetUsage
     * @return \Illuminate\Http\Response
     */
    public function destroy(BudgetUsage $budgetUsage)
    {
        $this->authorize('delete', [BudgetUsage::class, $budgetUsage]);

        $budgetUsage->delete();

        return redirect()->route('resourceRoute.index')->with('success', 'The resource has been deleted successfully.');
    }
}
