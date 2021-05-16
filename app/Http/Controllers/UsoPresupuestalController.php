<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsoPresupuestalRequest;
use App\Models\UsoPresupuestal;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UsoPresupuestalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', [UsoPresupuestal::class]);

        return Inertia::render('Presupuesto/UsosPresupuestales/Index', [
            'filters'               => request()->all('search'),
            'UsosPresupuestales'    => UsoPresupuestal::orderBy('descripcion', 'ASC')
                ->filterUsoPresupuestal(request()->only('search'))->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', [UsoPresupuestal::class]);

        return Inertia::render('Presupuesto/UsosPresupuestales/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UsoPresupuestalRequest $request)
    {
        $this->authorize('create', [UsoPresupuestal::class]);

        $UsoPresupuestal = new UsoPresupuestal();
        $UsoPresupuestal->descripcion   = $request->descripcion;
        $UsoPresupuestal->codigo        = $request->codigo;

        $UsoPresupuestal->save();

        return redirect()->route('usos-presupuestales.index')->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\UsoPresupuestal  $UsoPresupuestal
     * @return \Illuminate\Http\Response
     */
    public function show(UsoPresupuestal $UsoPresupuestal)
    {
        $this->authorize('view', [UsoPresupuestal::class, $UsoPresupuestal]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\UsoPresupuestal  $UsoPresupuestal
     * @return \Illuminate\Http\Response
     */
    public function edit(UsoPresupuestal $UsoPresupuestal)
    {
        $this->authorize('update', [UsoPresupuestal::class, $UsoPresupuestal]);

        return Inertia::render('Presupuesto/UsosPresupuestales/Edit', [
            'UsoPresupuestal' => $UsoPresupuestal
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UsoPresupuestal  $UsoPresupuestal
     * @return \Illuminate\Http\Response
     */
    public function update(UsoPresupuestalRequest $request, UsoPresupuestal $UsoPresupuestal)
    {
        $this->authorize('update', [UsoPresupuestal::class, $UsoPresupuestal]);

        $UsoPresupuestal->descripcion   = $request->descripcion;
        $UsoPresupuestal->codigo        = $request->codigo;

        $UsoPresupuestal->save();

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\UsoPresupuestal  $UsoPresupuestal
     * @return \Illuminate\Http\Response
     */
    public function destroy(UsoPresupuestal $UsoPresupuestal)
    {
        $this->authorize('delete', [UsoPresupuestal::class, $UsoPresupuestal]);

        $UsoPresupuestal->delete();

        return redirect()->route('usos-presupuestales.index')->with('success', 'The resource has been deleted successfully.');
    }
}
