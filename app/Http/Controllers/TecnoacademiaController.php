<?php

namespace App\Http\Controllers;

use App\Http\Requests\TecnoacademiaRequest;
use App\Models\LineaTecnologica;
use App\Models\Tecnoacademia;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TecnoacademiaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', [Tecnoacademia::class]);

        return Inertia::render('Tecnoacademias/Index', [
            'filters'           => request()->all('search'),
            'tecnoacademias'    => Tecnoacademia::orderBy('nombre', 'ASC')
                ->filterTecnoacademia(request()->only('search'))->paginate()->appends(['search' => request()->search]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', [Tecnoacademia::class]);

        return Inertia::render('Tecnoacademias/Create', [
            'lineasTecnologicas' => LineaTecnologica::orderBy('nombre', 'ASC')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TecnoacademiaRequest $request)
    {
        $this->authorize('create', [Tecnoacademia::class]);

        $tecnoacademia = new Tecnoacademia();
        $tecnoacademia->nombre = $request->nombre;

        $tecnoacademia->save();

        $tecnoacademia->lineasTecnologicas()->attach($request->linea_tecnologica_id);

        return redirect()->route('tecnoacademias.index')->with('success', 'El recurso se ha creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tecnoacademia  $tecnoacademia
     * @return \Illuminate\Http\Response
     */
    public function show(Tecnoacademia $tecnoacademia)
    {
        $this->authorize('view', [Tecnoacademia::class, $tecnoacademia]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tecnoacademia  $tecnoacademia
     * @return \Illuminate\Http\Response
     */
    public function edit(Tecnoacademia $tecnoacademia)
    {
        $this->authorize('update', [Tecnoacademia::class, $tecnoacademia]);

        return Inertia::render('Tecnoacademias/Edit', [
            'tecnoacademia'                     => $tecnoacademia,
            'lineasTecnologicas'                => LineaTecnologica::orderBy('nombre', 'ASC')->get(),
            'lineasTecnologicasRelacionadas'    => $tecnoacademia->lineasTecnologicas()->pluck('lineas_tecnologicas.id')
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tecnoacademia  $tecnoacademia
     * @return \Illuminate\Http\Response
     */
    public function update(TecnoacademiaRequest $request, Tecnoacademia $tecnoacademia)
    {
        $this->authorize('update', [Tecnoacademia::class, $tecnoacademia]);

        $tecnoacademia->nombre = $request->nombre;
        $tecnoacademia->lineasTecnologicas()->sync($request->linea_tecnologica_id);
        $tecnoacademia->save();

        return redirect()->back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tecnoacademia  $tecnoacademia
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tecnoacademia $tecnoacademia)
    {
        $this->authorize('delete', [Tecnoacademia::class, $tecnoacademia]);

        $tecnoacademia->delete();

        return redirect()->route('tecnoacademias.index')->with('success', 'El recurso se ha eliminado correctamente.');
    }
}
