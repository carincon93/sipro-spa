<?php

namespace App\Http\Controllers;

use App\Http\Requests\LineaTecnologicaRequest;
use App\Models\LineaTecnologica;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LineaTecnologicaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', [LineaTecnologica::class]);

        return Inertia::render('LineasTecnologicas/Index', [
            'filters'            => request()->all('search'),
            'lineasTecnologicas' => LineaTecnologica::orderBy('nombre', 'ASC')
                ->filterLineaTecnologica(request()->only('search'))->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', [LineaTecnologica::class]);

        return Inertia::render('LineasTecnologicas/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LineaTecnologicaRequest $request)
    {
        $this->authorize('create', [LineaTecnologica::class]);

        $lineaTecnologica = new LineaTecnologica();
        $lineaTecnologica->nombre = $request->nombre;

        $lineaTecnologica->save();

        return redirect()->route('lineas-tecnologicas.index')->with('success', 'El recurso se ha creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LineaTecnologica  $lineaTecnologica
     * @return \Illuminate\Http\Response
     */
    public function show(LineaTecnologica $lineaTecnologica)
    {
        $this->authorize('view', [LineaTecnologica::class, $lineaTecnologica]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LineaTecnologica  $lineaTecnologica
     * @return \Illuminate\Http\Response
     */
    public function edit(LineaTecnologica $lineaTecnologica)
    {
        $this->authorize('update', [LineaTecnologica::class, $lineaTecnologica]);

        return Inertia::render('LineasTecnologicas/Edit', [
            'lineaTecnologica' => $lineaTecnologica
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LineaTecnologica  $lineaTecnologica
     * @return \Illuminate\Http\Response
     */
    public function update(LineaTecnologicaRequest $request, LineaTecnologica $lineaTecnologica)
    {
        $this->authorize('update', [LineaTecnologica::class, $lineaTecnologica]);

        $lineaTecnologica->nombre = $request->nombre;

        $lineaTecnologica->save();

        return redirect()->back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LineaTecnologica  $lineaTecnologica
     * @return \Illuminate\Http\Response
     */
    public function destroy(LineaTecnologica $lineaTecnologica)
    {
        $this->authorize('delete', [LineaTecnologica::class, $lineaTecnologica]);

        $lineaTecnologica->delete();

        return redirect()->route('lineas-tecnologicas.index')->with('success', 'El recurso se ha eliminado correctamente.');
    }
}
