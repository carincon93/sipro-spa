<?php

namespace App\Http\Controllers;

use App\Http\Requests\SpecificObjectiveRequest;
use App\Models\Project;
use App\Models\SpecificObjective;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SpecificObjectiveController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Project $project)
    {
        $this->authorize('viewAny', [SpecificObjective::class]);

        return Inertia::render('SpecificObjectives/Index', [
            'filters'   => request()->all('search'),
            'specificObjectives' => SpecificObjective::orderBy('', 'ASC')
                ->filterSpecificObjective(request()->only('search'))->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Project $project)
    {
        $this->authorize('create', [SpecificObjective::class]);

        return Inertia::render('SpecificObjectives/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SpecificObjectiveRequest $request, Project $project)
    {
        $this->authorize('create', [SpecificObjective::class]);

        $specificObjective = new SpecificObjective();
        $specificObjective->fieldName = $request->fieldName;
        $specificObjective->fieldName = $request->fieldName;
        $specificObjective->fieldName = $request->fieldName;

        $specificObjective->save();

        return redirect()->route('resourceRoute.index')->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SpecificObjective  $specificObjective
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project, SpecificObjective $specificObjective)
    {
        $this->authorize('view', [SpecificObjective::class, $specificObjective]);

        return Inertia::render('SpecificObjectives/Show', [
            'specificObjective' => $specificObjective
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SpecificObjective  $specificObjective
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project, SpecificObjective $specificObjective)
    {
        $this->authorize('update', [SpecificObjective::class, $specificObjective]);

        return Inertia::render('SpecificObjectives/Editar', [
            'specificObjective' => $specificObjective
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SpecificObjective  $specificObjective
     * @return \Illuminate\Http\Response
     */
    public function update(SpecificObjectiveRequest $request, Project $project, SpecificObjective $specificObjective)
    {
        $this->authorize('update', [SpecificObjective::class, $specificObjective]);

        $specificObjective->fieldName = $request->fieldName;
        $specificObjective->fieldName = $request->fieldName;
        $specificObjective->fieldName = $request->fieldName;

        $specificObjective->save();

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SpecificObjective  $specificObjective
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project, SpecificObjective $specificObjective)
    {
        $this->authorize('delete', [SpecificObjective::class, $specificObjective]);

        $specificObjective->delete();

        return redirect()->route('resourceRoute.index')->with('success', 'The resource has been deleted successfully.');
    }
}
