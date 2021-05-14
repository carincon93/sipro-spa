<?php

namespace App\Http\Controllers;

use App\Http\Requests\RedConocimientoRequest;
use App\Models\RedConocimiento;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RedConocimientoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', [RedConocimiento::class]);

        return Inertia::render('RedesConocimiento/Index', [
            'filters'           => request()->all('search'),
            'redesConocimiento' => RedConocimiento::orderBy('nombre', 'ASC')
                ->filterRedConocimiento(request()->only('search'))->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', [RedConocimiento::class]);

        return Inertia::render('RedesConocimiento/Crear');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RedConocimientoRequest $request)
    {
        $this->authorize('create', [RedConocimiento::class]);

        $redConocimiento = new RedConocimiento();
        $redConocimiento->nombre = $request->nombre;

        $redConocimiento->save();

        return redirect()->route('redes-conocimiento.index')->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RedConocimiento  $redConocimiento
     * @return \Illuminate\Http\Response
     */
    public function show(RedConocimiento $redConocimiento)
    {
        $this->authorize('view', [RedConocimiento::class, $redConocimiento]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RedConocimiento  $redConocimiento
     * @return \Illuminate\Http\Response
     */
    public function edit(RedConocimiento $redConocimiento)
    {
        $this->authorize('update', [RedConocimiento::class, $redConocimiento]);

        return Inertia::render('RedesConocimiento/Editar', [
            'redConocimiento' => $redConocimiento
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RedConocimiento  $redConocimiento
     * @return \Illuminate\Http\Response
     */
    public function update(RedConocimientoRequest $request, RedConocimiento $redConocimiento)
    {
        $this->authorize('update', [RedConocimiento::class, $redConocimiento]);

        $redConocimiento->nombre = $request->nombre;

        $redConocimiento->save();

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RedConocimiento  $redConocimiento
     * @return \Illuminate\Http\Response
     */
    public function destroy(RedConocimiento $redConocimiento)
    {
        $this->authorize('delete', [RedConocimiento::class, $redConocimiento]);

        $redConocimiento->delete();

        return redirect()->route('redes-conocimiento.index')->with('success', 'The resource has been deleted successfully.');
    }
}
