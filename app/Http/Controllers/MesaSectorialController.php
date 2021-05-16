<?php

namespace App\Http\Controllers;

use App\Http\Requests\MesaSectorialRequest;
use App\Models\MesaSectorial;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MesaSectorialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', [MesaSectorial::class]);

        return Inertia::render('MesasSectoriales/Index', [
            'filters'           => request()->all('search'),
            'mesasSectoriales'  => MesaSectorial::orderBy('nombre', 'ASC')
                ->filterMesaSectorial(request()->only('search'))->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', [MesaSectorial::class]);

        return Inertia::render('MesasSectoriales/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MesaSectorialRequest $request)
    {
        $this->authorize('create', [MesaSectorial::class]);

        $mesaSectorial = new MesaSectorial();
        $mesaSectorial->nombre = $request->nombre;

        $mesaSectorial->save();

        return redirect()->route('mesas-sectoriales.index')->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MesaSectorial  $mesaSectorial
     * @return \Illuminate\Http\Response
     */
    public function show(MesaSectorial $mesaSectorial)
    {
        $this->authorize('view', [MesaSectorial::class, $mesaSectorial]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MesaSectorial  $mesaSectorial
     * @return \Illuminate\Http\Response
     */
    public function edit(MesaSectorial $mesaSectorial)
    {
        $this->authorize('update', [MesaSectorial::class, $mesaSectorial]);

        return Inertia::render('MesasSectoriales/Edit', [
            'mesaSectorial' => $mesaSectorial
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MesaSectorial  $mesaSectorial
     * @return \Illuminate\Http\Response
     */
    public function update(MesaSectorialRequest $request, MesaSectorial $mesaSectorial)
    {
        $this->authorize('update', [MesaSectorial::class, $mesaSectorial]);

        $mesaSectorial->nombre = $request->nombre;

        $mesaSectorial->save();

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MesaSectorial  $mesaSectorial
     * @return \Illuminate\Http\Response
     */
    public function destroy(MesaSectorial $mesaSectorial)
    {
        $this->authorize('delete', [MesaSectorial::class, $mesaSectorial]);

        $mesaSectorial->delete();

        return redirect()->route('mesas-sectoriales.index')->with('success', 'The resource has been deleted successfully.');
    }
}
