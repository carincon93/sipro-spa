<?php

namespace App\Http\Controllers;

use App\Http\Requests\MincienciasTypologyRequest;
use App\Models\MincienciasTypology;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MincienciasTypologyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', [MincienciasTypology::class]);

        return Inertia::render('MincienciasTypologies/Index', [
            'filters'   => request()->all('search'),
            'mincienciasTypologies' => MincienciasTypology::orderBy('nombre', 'ASC')
                ->filterMincienciasTypology(request()->only('search'))->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', [MincienciasTypology::class]);

        return Inertia::render('MincienciasTypologies/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MincienciasTypologyRequest $request)
    {
        $this->authorize('create', [MincienciasTypology::class]);

        $mincienciasTypology = new MincienciasTypology();
        $mincienciasTypology->name = $request->name;

        $mincienciasTypology->save();

        return redirect()->route('minciencias-typologies.index')->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MincienciasTypology  $mincienciasTypology
     * @return \Illuminate\Http\Response
     */
    public function show(MincienciasTypology $mincienciasTypology)
    {
        $this->authorize('view', [MincienciasTypology::class, $mincienciasTypology]);

        return Inertia::render('MincienciasTypologies/Show', [
            'mincienciasTypology' => $mincienciasTypology
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MincienciasTypology  $mincienciasTypology
     * @return \Illuminate\Http\Response
     */
    public function edit(MincienciasTypology $mincienciasTypology)
    {
        $this->authorize('update', [MincienciasTypology::class, $mincienciasTypology]);

        return Inertia::render('MincienciasTypologies/Editar', [
            'mincienciasTypology' => $mincienciasTypology
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MincienciasTypology  $mincienciasTypology
     * @return \Illuminate\Http\Response
     */
    public function update(MincienciasTypologyRequest $request, MincienciasTypology $mincienciasTypology)
    {
        $this->authorize('update', [MincienciasTypology::class, $mincienciasTypology]);

        $mincienciasTypology->name = $request->name;

        $mincienciasTypology->save();

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MincienciasTypology  $mincienciasTypology
     * @return \Illuminate\Http\Response
     */
    public function destroy(MincienciasTypology $mincienciasTypology)
    {
        $this->authorize('delete', [MincienciasTypology::class, $mincienciasTypology]);

        $mincienciasTypology->delete();

        return redirect()->route('minciencias-typologies.index')->with('success', 'The resource has been deleted successfully.');
    }
}
