<?php

namespace App\Http\Controllers;

use App\Http\Requests\CallBudgetRequest;
use App\Models\CallBudget;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CallBudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', [CallBudget::class]);

        return Inertia::render('rubrosPresupuestalesConvocatoria/Index', [
            'filters'   => request()->all('search'),
            'rubrosPresupuestalesConvocatoria' => CallBudget::orderBy('', 'ASC')
                ->filterCallBudget(request()->only('search'))->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', [CallBudget::class]);

        return Inertia::render('rubrosPresupuestalesConvocatoria/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CallBudgetRequest $request)
    {
        $this->authorize('create', [CallBudget::class]);

        $callBudget = new CallBudget();
        $callBudget->fieldName = $request->fieldName;
        $callBudget->fieldName = $request->fieldName;
        $callBudget->fieldName = $request->fieldName;

        $callBudget->save();

        return redirect()->route('resourceRoute.index')->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CallBudget  $callBudget
     * @return \Illuminate\Http\Response
     */
    public function show(CallBudget $callBudget)
    {
        $this->authorize('view', [CallBudget::class, $callBudget]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CallBudget  $callBudget
     * @return \Illuminate\Http\Response
     */
    public function edit(CallBudget $callBudget)
    {
        $this->authorize('update', [CallBudget::class, $callBudget]);

        return Inertia::render('rubrosPresupuestalesConvocatoria/Edit', [
            'callBudget' => $callBudget
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CallBudget  $callBudget
     * @return \Illuminate\Http\Response
     */
    public function update(CallBudgetRequest $request, CallBudget $callBudget)
    {
        $this->authorize('update', [CallBudget::class, $callBudget]);

        $callBudget->fieldName = $request->fieldName;
        $callBudget->fieldName = $request->fieldName;
        $callBudget->fieldName = $request->fieldName;

        $callBudget->save();

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CallBudget  $callBudget
     * @return \Illuminate\Http\Response
     */
    public function destroy(CallBudget $callBudget)
    {
        $this->authorize('delete', [CallBudget::class, $callBudget]);

        $callBudget->delete();

        return redirect()->route('resourceRoute.index')->with('success', 'The resource has been deleted successfully.');
    }
}
