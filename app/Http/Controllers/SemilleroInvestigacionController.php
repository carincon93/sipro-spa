<?php

namespace App\Http\Controllers;

use App\Http\Requests\SemilleroInvestigacionRequest;
use App\Models\LineaInvestigacion;
use App\Models\SemilleroInvestigacion;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SemilleroInvestigacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', [SemilleroInvestigacion::class]);

        return Inertia::render('SemillerosInvestigacion/Index', [
            'filters'   => request()->all('search'),
            'semillerosInvestigacion' => SemilleroInvestigacion::getSemillerosInvestigacionByRol()->appends(['search' => request()->search]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', [SemilleroInvestigacion::class]);

        return Inertia::render('SemillerosInvestigacion/Create', [
            'lineasInvestigacion' => LineaInvestigacion::orderBy('nombre', 'ASC')->select(['id as value', 'nombre as label'])->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SemilleroInvestigacionRequest $request)
    {
        $this->authorize('create', [SemilleroInvestigacion::class]);

        $semilleroInvestigacion = new SemilleroInvestigacion();
        $semilleroInvestigacion->nombre = $request->nombre;
        $semilleroInvestigacion->lineaInvestigacion()->associate($request->linea_investigacion_id);

        $semilleroInvestigacion->save();

        return redirect()->route('semilleros-investigacion.index')->with('success', 'El recurso se ha creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SemilleroInvestigacion  $semilleroInvestigacion
     * @return \Illuminate\Http\Response
     */
    public function show(SemilleroInvestigacion $semilleroInvestigacion)
    {
        $this->authorize('view', [SemilleroInvestigacion::class, $semilleroInvestigacion]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SemilleroInvestigacion  $semilleroInvestigacion
     * @return \Illuminate\Http\Response
     */
    public function edit(SemilleroInvestigacion $semilleroInvestigacion)
    {
        $this->authorize('update', [SemilleroInvestigacion::class, $semilleroInvestigacion]);

        $semilleroInvestigacion->lineaInvestigacion->grupoInvestigacion;

        return Inertia::render('SemillerosInvestigacion/Edit', [
            'semilleroInvestigacion'  => $semilleroInvestigacion,
            'lineasInvestigacion'     => LineaInvestigacion::orderBy('nombre', 'ASC')->select(['id as value', 'nombre as label'])->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SemilleroInvestigacion  $semilleroInvestigacion
     * @return \Illuminate\Http\Response
     */
    public function update(SemilleroInvestigacionRequest $request, SemilleroInvestigacion $semilleroInvestigacion)
    {
        $this->authorize('update', [SemilleroInvestigacion::class, $semilleroInvestigacion]);

        $semilleroInvestigacion->nombre = $request->nombre;
        $semilleroInvestigacion->lineaInvestigacion()->associate($request->linea_investigacion_id);

        $semilleroInvestigacion->save();

        return back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SemilleroInvestigacion  $semilleroInvestigacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(SemilleroInvestigacion $semilleroInvestigacion)
    {
        $this->authorize('delete', [SemilleroInvestigacion::class, $semilleroInvestigacion]);

        $semilleroInvestigacion->delete();

        return redirect()->route('semilleros-investigacion.index')->with('success', 'El recurso se ha eliminado correctamente.');
    }
}
