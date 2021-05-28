<?php

namespace App\Http\Controllers;

use App\Http\Requests\TemaPriorizadoRequest;
use App\Models\SectorProductivo;
use App\Models\MesaTecnica;
use App\Models\TemaPriorizado;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TemaPriorizadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', [TemaPriorizado::class]);

        return Inertia::render('TemasPriorizados/Index', [
            'filters'   => request()->all('search'),
            'temasPriorizados' => TemaPriorizado::with(['sectorProductivo', 'mesaTecnica'])
                ->filterTemaPriorizado(request()->only('search'))->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', [TemaPriorizado::class]);

        return Inertia::render('TemasPriorizados/Create', [
            'sectoresProductivos'   => SectorProductivo::orderBy('nombre', 'ASC')->select(['id as value', 'nombre as label'])->get(),
            'mesasTecnicas'         =>  MesaTecnica::orderBy('nombre', 'ASC')->select(['id as value', 'nombre as label'])->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TemaPriorizadoRequest $request)
    {
        $this->authorize('create', [TemaPriorizado::class]);

        $temaPriorizado = new TemaPriorizado();
        $temaPriorizado->nombre = $request->nombre;
        $temaPriorizado->sectorProductivo()->associate($request->sector_productivo_id);
        $temaPriorizado->mesaTecnica()->associate($request->mesa_tecnica_id);

        $temaPriorizado->save();

        return redirect()->route('temas-priorizados.index')->with('success', 'El recurso se ha creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TemaPriorizado  $temaPriorizado
     * @return \Illuminate\Http\Response
     */
    public function show(TemaPriorizado $temaPriorizado)
    {
        $this->authorize('view', [TemaPriorizado::class, $temaPriorizado]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TemaPriorizado  $temaPriorizado
     * @return \Illuminate\Http\Response
     */
    public function edit(TemaPriorizado $temaPriorizado)
    {
        $this->authorize('update', [TemaPriorizado::class, $temaPriorizado]);

        return Inertia::render('TemasPriorizados/Edit', [
            'temaPriorizado'        => $temaPriorizado,
            'sectoresProductivos'   => SectorProductivo::orderBy('nombre', 'ASC')->select(['id as value', 'nombre as label'])->get(),
            'mesasTecnicas'         => MesaTecnica::orderBy('nombre', 'ASC')->select(['id as value', 'nombre as label'])->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TemaPriorizado  $temaPriorizado
     * @return \Illuminate\Http\Response
     */
    public function update(TemaPriorizadoRequest $request, TemaPriorizado $temaPriorizado)
    {
        $this->authorize('update', [TemaPriorizado::class, $temaPriorizado]);

        $temaPriorizado->nombre = $request->nombre;

        $temaPriorizado->sectorProductivo()->associate($request->sector_productivo_id);
        $temaPriorizado->mesaTecnica()->associate($request->mesa_tecnica_id);

        $temaPriorizado->save();

        return redirect()->back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TemaPriorizado  $temaPriorizado
     * @return \Illuminate\Http\Response
     */
    public function destroy(TemaPriorizado $temaPriorizado)
    {
        $this->authorize('delete', [TemaPriorizado::class, $temaPriorizado]);

        $temaPriorizado->delete();

        return redirect()->route('temas-priorizados.index')->with('success', 'El recurso se ha eliminado correctamente.');
    }
}
