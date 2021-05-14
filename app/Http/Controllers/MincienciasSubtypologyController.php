<?php

namespace App\Http\Controllers;

use App\Http\Requests\MincienciasSubtypologyRequest;
use App\Models\MincienciasTypology;
use App\Models\MincienciasSubtypology;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MincienciasSubtypologyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', [MincienciasSubtypology::class]);

        return Inertia::render('MincienciasSubtypologies/Index', [
            'filters'   => request()->all('search'),
            'mincienciasSubtypologies' => MincienciasSubtypology::with(['mincienciasTypology' => function ($query) {
                $query->orderBy('nombre', 'ASC');
            }])
                ->filterMincienciasSubtypology(request()->only('search'))->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', [MincienciasSubtypology::class]);

        return Inertia::render('MincienciasSubtypologies/Create', [
            'mincienciasTypologies' =>  MincienciasTypology::orderBy('nombre', 'ASC')->select(['id as value', 'name as label'])->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MincienciasSubtypologyRequest $request)
    {
        $this->authorize('create', [MincienciasSubtypology::class]);

        $mincienciasSubtypology = new MincienciasSubtypology();
        $mincienciasSubtypology->name = $request->name;
        $mincienciasSubtypology->mincienciasTypology()->associate($request->minciencias_typology_id);
        $mincienciasSubtypology->save();

        return redirect()->route('minciencias-subtypologies.index')->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MincienciasSubtypology  $mincienciasSubtypology
     * @return \Illuminate\Http\Response
     */
    public function show(MincienciasSubtypology $mincienciasSubtypology)
    {
        $this->authorize('view', [MincienciasSubtypology::class, $mincienciasSubtypology]);

        return Inertia::render('MincienciasSubtypologies/Show', [
            'mincienciasSubtypology' => $mincienciasSubtypology
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MincienciasSubtypology  $mincienciasSubtypology
     * @return \Illuminate\Http\Response
     */
    public function edit(MincienciasSubtypology $mincienciasSubtypology)
    {
        $this->authorize('update', [MincienciasSubtypology::class, $mincienciasSubtypology]);

        return Inertia::render('MincienciasSubtypologies/Editar', [
            'mincienciasSubtypology'    => $mincienciasSubtypology,
            'mincienciasTypologies'     =>  MincienciasTypology::orderBy('nombre', 'ASC')->select(['id as value', 'name as label'])->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MincienciasSubtypology  $mincienciasSubtypology
     * @return \Illuminate\Http\Response
     */
    public function update(MincienciasSubtypologyRequest $request, MincienciasSubtypology $mincienciasSubtypology)
    {
        $this->authorize('update', [MincienciasSubtypology::class, $mincienciasSubtypology]);

        $mincienciasSubtypology->name = $request->name;
        $mincienciasSubtypology->mincienciasTypology()->associate($request->minciencias_typology_id);
        $mincienciasSubtypology->save();

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MincienciasSubtypology  $mincienciasSubtypology
     * @return \Illuminate\Http\Response
     */
    public function destroy(MincienciasSubtypology $mincienciasSubtypology)
    {
        $this->authorize('delete', [MincienciasSubtypology::class, $mincienciasSubtypology]);

        $mincienciasSubtypology->delete();

        return redirect()->route('minciencias-subtypologies.index')->with('success', 'The resource has been deleted successfully.');
    }
}
