<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConvocatoriaPresupuestoRequest;
use App\Models\ConvocatoriaPresupuesto;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ConvocatoriaPresupuestoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', [ConvocatoriaPresupuesto::class]);

        return Inertia::render('ConvocatoriaPresupuesto/Index', [
            'filters'                   => request()->all('search'),
            'convocatoriaPresupuesto'   => ConvocatoriaPresupuesto::orderBy('', 'ASC')
                ->filterConvocatoriaPresupuesto(request()->only('search'))->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', [ConvocatoriaPresupuesto::class]);

        return Inertia::render('ConvocatoriaPresupuesto/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ConvocatoriaPresupuestoRequest $request)
    {
        $this->authorize('create', [ConvocatoriaPresupuesto::class]);

        $callBudget = new ConvocatoriaPresupuesto();
        $callBudget->fieldName = $request->fieldName;
        $callBudget->fieldName = $request->fieldName;
        $callBudget->fieldName = $request->fieldName;

        $callBudget->save();

        return redirect()->route('convocatoria-presupuesto.index')->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ConvocatoriaPresupuesto  $callBudget
     * @return \Illuminate\Http\Response
     */
    public function show(ConvocatoriaPresupuesto $callBudget)
    {
        $this->authorize('view', [ConvocatoriaPresupuesto::class, $callBudget]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ConvocatoriaPresupuesto  $callBudget
     * @return \Illuminate\Http\Response
     */
    public function edit(ConvocatoriaPresupuesto $callBudget)
    {
        $this->authorize('update', [ConvocatoriaPresupuesto::class, $callBudget]);

        return Inertia::render('ConvocatoriaPresupuesto/Edit', [
            'callBudget' => $callBudget
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ConvocatoriaPresupuesto  $callBudget
     * @return \Illuminate\Http\Response
     */
    public function update(ConvocatoriaPresupuestoRequest $request, ConvocatoriaPresupuesto $callBudget)
    {
        $this->authorize('update', [ConvocatoriaPresupuesto::class, $callBudget]);

        $callBudget->fieldName = $request->fieldName;
        $callBudget->fieldName = $request->fieldName;
        $callBudget->fieldName = $request->fieldName;

        $callBudget->save();

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ConvocatoriaPresupuesto  $callBudget
     * @return \Illuminate\Http\Response
     */
    public function destroy(ConvocatoriaPresupuesto $callBudget)
    {
        $this->authorize('delete', [ConvocatoriaPresupuesto::class, $callBudget]);

        $callBudget->delete();

        return redirect()->route('convocatoria-presupuesto.index')->with('success', 'The resource has been deleted successfully.');
    }
}
