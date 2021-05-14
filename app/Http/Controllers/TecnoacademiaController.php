<?php

namespace App\Http\Controllers;

use App\Http\Requests\TecnoacademiaRequest;
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
                ->filterTecnoacademia(request()->only('search'))->paginate(),
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

        return Inertia::render('Tecnoacademias/Crear');
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

        $tecnoAcademia = new Tecnoacademia();
        $tecnoAcademia->nombre = $request->nombre;

        $tecnoAcademia->save();

        return redirect()->route('tecnoacademias.index')->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tecnoacademia  $tecnoAcademia
     * @return \Illuminate\Http\Response
     */
    public function show(Tecnoacademia $tecnoAcademia)
    {
        $this->authorize('view', [Tecnoacademia::class, $tecnoAcademia]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tecnoacademia  $tecnoAcademia
     * @return \Illuminate\Http\Response
     */
    public function edit(Tecnoacademia $tecnoAcademia)
    {
        $this->authorize('update', [Tecnoacademia::class, $tecnoAcademia]);

        return Inertia::render('Tecnoacademias/Editar', [
            'tecnoAcademia' => $tecnoAcademia
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tecnoacademia  $tecnoAcademia
     * @return \Illuminate\Http\Response
     */
    public function update(TecnoacademiaRequest $request, Tecnoacademia $tecnoAcademia)
    {
        $this->authorize('update', [Tecnoacademia::class, $tecnoAcademia]);

        $tecnoAcademia->nombre = $request->nombre;

        $tecnoAcademia->save();

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tecnoacademia  $tecnoAcademia
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tecnoacademia $tecnoAcademia)
    {
        $this->authorize('delete', [Tecnoacademia::class, $tecnoAcademia]);

        $tecnoAcademia->delete();

        return redirect()->route('tecnoacademias.index')->with('success', 'The resource has been deleted successfully.');
    }
}
