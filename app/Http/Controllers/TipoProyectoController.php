<?php

namespace App\Http\Controllers;

use App\Http\Requests\TipoProyectoRequest;
use App\Models\LineaProgramatica;
use App\Models\TipoProyecto;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TipoProyectoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', [TipoProyecto::class]);

        return Inertia::render('TiposProyecto/Index', [
            'filters'       => request()->all('search'),
            'tiposProyecto' => TipoProyecto::with(['lineaProgramatica' => function ($query) {
                $query->orderBy('nombre', 'ASC');
            }])
                ->filterTipoProyecto(request()->only('search'))->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', [TipoProyecto::class]);

        return Inertia::render('TiposProyecto/Create', [
            'lineasProgramaticas' => LineaProgramatica::orderBy('nombre', 'ASC')->select(['id as value', 'nombre as label'])->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TipoProyectoRequest $request)
    {
        $this->authorize('create', [TipoProyecto::class]);

        $tipoProyecto = new TipoProyecto();
        $tipoProyecto->nombre          = $request->nombre;
        $tipoProyecto->lineaProgramatica()->associate($request->linea_programatica_id);

        $tipoProyecto->save();

        return redirect()->route('tipos-proyecto.index')->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TipoProyecto  $tipoProyecto
     * @return \Illuminate\Http\Response
     */
    public function show(TipoProyecto $tipoProyecto)
    {
        $this->authorize('view', [TipoProyecto::class, $tipoProyecto]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TipoProyecto  $tipoProyecto
     * @return \Illuminate\Http\Response
     */
    public function edit(TipoProyecto $tipoProyecto)
    {
        $this->authorize('update', [TipoProyecto::class, $tipoProyecto]);

        return Inertia::render('TiposProyecto/Edit', [
            'tipoProyecto'        => $tipoProyecto,
            'lineasProgramaticas' => LineaProgramatica::orderBy('nombre', 'ASC')->select(['id as value', 'nombre as label'])->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TipoProyecto  $tipoProyecto
     * @return \Illuminate\Http\Response
     */
    public function update(TipoProyectoRequest $request, TipoProyecto $tipoProyecto)
    {
        $this->authorize('update', [TipoProyecto::class, $tipoProyecto]);

        $tipoProyecto->nombre          = $request->nombre;
        $tipoProyecto->lineaProgramatica()->associate($request->linea_programatica_id);

        $tipoProyecto->save();

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TipoProyecto  $tipoProyecto
     * @return \Illuminate\Http\Response
     */
    public function destroy(TipoProyecto $tipoProyecto)
    {
        $this->authorize('delete', [TipoProyecto::class, $tipoProyecto]);

        $tipoProyecto->delete();

        return redirect()->route('tipos-proyecto.index')->with('success', 'The resource has been deleted successfully.');
    }
}
