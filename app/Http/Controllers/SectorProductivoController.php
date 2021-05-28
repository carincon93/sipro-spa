<?php

namespace App\Http\Controllers;

use App\Http\Requests\SectorProductivoRequest;
use App\Models\SectorProductivo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SectorProductivoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', [SectorProductivo::class]);

        return Inertia::render('SectoresProductivos/Index', [
            'filters'   => request()->all('search'),
            'sectoresProductivos' => SectorProductivo::orderBy('nombre', 'ASC')
                ->filterSectorProductivo(request()->only('search'))->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', [SectorProductivo::class]);

        return Inertia::render('SectoresProductivos/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SectorProductivoRequest $request)
    {
        $this->authorize('create', [SectorProductivo::class]);

        $sectorProductivo = new SectorProductivo();
        $sectorProductivo->nombre = $request->nombre;

        $sectorProductivo->save();

        return redirect()->route('sectores-productivos.index')->with('success', 'El recurso se ha creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SectorProductivo  $sectorProductivo
     * @return \Illuminate\Http\Response
     */
    public function show(SectorProductivo $sectorProductivo)
    {
        $this->authorize('view', [SectorProductivo::class, $sectorProductivo]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SectorProductivo  $sectorProductivo
     * @return \Illuminate\Http\Response
     */
    public function edit(SectorProductivo $sectorProductivo)
    {
        $this->authorize('update', [SectorProductivo::class, $sectorProductivo]);

        return Inertia::render('SectoresProductivos/Edit', [
            'sectorProductivo' => $sectorProductivo
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SectorProductivo  $sectorProductivo
     * @return \Illuminate\Http\Response
     */
    public function update(SectorProductivoRequest $request, SectorProductivo $sectorProductivo)
    {
        $this->authorize('update', [SectorProductivo::class, $sectorProductivo]);

        $sectorProductivo->nombre = $request->nombre;

        $sectorProductivo->save();

        return redirect()->back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SectorProductivo  $sectorProductivo
     * @return \Illuminate\Http\Response
     */
    public function destroy(SectorProductivo $sectorProductivo)
    {
        $this->authorize('delete', [SectorProductivo::class, $sectorProductivo]);

        $sectorProductivo->delete();

        return redirect()->route('sectores-productivos.index')->with('success', 'El recurso se ha eliminado correctamente.');
    }
}
