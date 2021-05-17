<?php

namespace App\Http\Controllers;

use App\Http\Requests\PresupuestoSennovaRequest;
use App\Models\PresupuestoSennova;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PresupuestoSennovaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', [PresupuestoSennova::class]);

        return Inertia::render('PresupuestoSennova/Index', [
            'filters'               => request()->all('search'),
            'presupuestoSennova'    => PresupuestoSennova::orderBy('mensaje', 'ASC')
                ->filterPresupuestoSennova(request()->only('search'))->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', [PresupuestoSennova::class]);

        return Inertia::render('PresupuestoSennova/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PresupuestoSennovaRequest $request)
    {
        $this->authorize('create', [PresupuestoSennova::class]);

        $presupuestoSennova = new PresupuestoSennova();
        $presupuestoSennova->fieldName = $request->fieldName;
        $presupuestoSennova->fieldName = $request->fieldName;
        $presupuestoSennova->fieldName = $request->fieldName;

        $presupuestoSennova->save();

        return redirect()->route('resourceRoute.index')->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PresupuestoSennova  $presupuestoSennova
     * @return \Illuminate\Http\Response
     */
    public function show(PresupuestoSennova $presupuestoSennova)
    {
        $this->authorize('view', [PresupuestoSennova::class, $presupuestoSennova]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PresupuestoSennova  $presupuestoSennova
     * @return \Illuminate\Http\Response
     */
    public function edit(PresupuestoSennova $presupuestoSennova)
    {
        $this->authorize('update', [PresupuestoSennova::class, $presupuestoSennova]);

        return Inertia::render('PresupuestoSennova/Edit', [
            'sennovaBudget' => $presupuestoSennova
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PresupuestoSennova  $presupuestoSennova
     * @return \Illuminate\Http\Response
     */
    public function update(PresupuestoSennovaRequest $request, PresupuestoSennova $presupuestoSennova)
    {
        $this->authorize('update', [PresupuestoSennova::class, $presupuestoSennova]);

        $presupuestoSennova->fieldName = $request->fieldName;
        $presupuestoSennova->fieldName = $request->fieldName;
        $presupuestoSennova->fieldName = $request->fieldName;

        $presupuestoSennova->save();

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PresupuestoSennova  $presupuestoSennova
     * @return \Illuminate\Http\Response
     */
    public function destroy(PresupuestoSennova $presupuestoSennova)
    {
        $this->authorize('delete', [PresupuestoSennova::class, $presupuestoSennova]);

        $presupuestoSennova->delete();

        return redirect()->route('resourceRoute.index')->with('success', 'The resource has been deleted successfully.');
    }
}
