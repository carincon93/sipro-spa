<?php

namespace App\Http\Controllers;

use App\Http\Requests\LineaInvestigacionRequest;
use App\Models\LineaInvestigacion;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LineaInvestigacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', [LineaInvestigacion::class]);

        return Inertia::render('LineasInvestigacion/Index', [
            'filters'               => request()->all('search'),
            'lineasInvestigacion'   => LineaInvestigacion::select('lineas_investigacion.id', 'lineas_investigacion.nombre', 'lineas_investigacion.grupo_investigacion_id')->with('grupoInvestigacion', 'grupoInvestigacion.centroFormacion')
                ->filterLineaInvestigacion(request()->only('search'))->paginate()->appends(['search' => request()->search]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', [LineaInvestigacion::class]);

        return Inertia::render('LineasInvestigacion/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LineaInvestigacionRequest $request)
    {
        $this->authorize('create', [LineaInvestigacion::class]);

        $lineaInvestigacion = new LineaInvestigacion();
        $lineaInvestigacion->nombre         = $request->nombre;
        $lineaInvestigacion->grupoInvestigacion()->associate($request->grupo_investigacion_id);

        $lineaInvestigacion->save();

        return redirect()->route('lineas-investigacion.index')->with('success', 'El recurso se ha creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LineaInvestigacion  $lineaInvestigacion
     * @return \Illuminate\Http\Response
     */
    public function show(LineaInvestigacion $lineaInvestigacion)
    {
        $this->authorize('view', [LineaInvestigacion::class, $lineaInvestigacion]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LineaInvestigacion  $lineaInvestigacion
     * @return \Illuminate\Http\Response
     */
    public function edit(LineaInvestigacion $lineaInvestigacion)
    {
        $this->authorize('update', [LineaInvestigacion::class, $lineaInvestigacion]);

        return Inertia::render('LineasInvestigacion/Edit', [
            'lineaInvestigacion' => $lineaInvestigacion,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LineaInvestigacion  $lineaInvestigacion
     * @return \Illuminate\Http\Response
     */
    public function update(LineaInvestigacionRequest $request, LineaInvestigacion $lineaInvestigacion)
    {
        $this->authorize('update', [LineaInvestigacion::class, $lineaInvestigacion]);

        $lineaInvestigacion->nombre = $request->nombre;
        $lineaInvestigacion->grupoInvestigacion()->associate($request->grupo_investigacion_id);

        $lineaInvestigacion->save();

        return back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LineaInvestigacion  $lineaInvestigacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(LineaInvestigacion $lineaInvestigacion)
    {
        $this->authorize('delete', [LineaInvestigacion::class, $lineaInvestigacion]);

        try {
            $lineaInvestigacion->delete();
        } catch (QueryException $e) {
            return back()->with('error', 'No se puede eliminar el recurso debido a que está asociado a uno o varios proyectos.');
        }

        return redirect()->route('lineas-investigacion.index')->with('success', 'El recurso se ha eliminado correctamente.');
    }
}
