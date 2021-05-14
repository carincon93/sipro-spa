<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectTypeRequest;
use App\Models\ProgrammaticLine;
use App\Models\ProjectType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', [ProjectType::class]);

        return Inertia::render('ProjectTypes/Index', [
            'filters'   => request()->all('search'),
            'projectTypes' => ProjectType::with(['programmaticLine' => function ($query) {
                $query->orderBy('nombre', 'ASC');
            }])
                ->filterProjectType(request()->only('search'))->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', [ProjectType::class]);

        return Inertia::render('ProjectTypes/Create', [
            'programmaticLines' => ProgrammaticLine::orderBy('nombre', 'ASC')->select(['id as value', 'name as label'])->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectTypeRequest $request)
    {
        $this->authorize('create', [ProjectType::class]);

        $projectType = new ProjectType();
        $projectType->name          = $request->name;
        $projectType->maximum_value = $request->maximum_value;
        $projectType->programmaticLine()->associate($request->programmatic_line_id);

        $projectType->save();

        return redirect()->route('project-types.index')->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProjectType  $projectType
     * @return \Illuminate\Http\Response
     */
    public function show(ProjectType $projectType)
    {
        $this->authorize('view', [ProjectType::class, $projectType]);

        return Inertia::render('ProjectTypes/Show', [
            'projectType' => $projectType,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProjectType  $projectType
     * @return \Illuminate\Http\Response
     */
    public function edit(ProjectType $projectType)
    {
        $this->authorize('update', [ProjectType::class, $projectType]);

        return Inertia::render('ProjectTypes/Editar', [
            'projectType'        => $projectType,
            'programmaticLines'  => ProgrammaticLine::orderBy('nombre', 'ASC')->select(['id as value', 'name as label'])->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProjectType  $projectType
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectTypeRequest $request, ProjectType $projectType)
    {
        $this->authorize('update', [ProjectType::class, $projectType]);

        $projectType->name          = $request->name;
        $projectType->maximum_value = $request->maximum_value;
        $projectType->programmaticLine()->associate($request->programmatic_line_id);

        $projectType->save();

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProjectType  $projectType
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProjectType $projectType)
    {
        $this->authorize('delete', [ProjectType::class, $projectType]);

        $projectType->delete();

        return redirect()->route('project-types.index')->with('success', 'The resource has been deleted successfully.');
    }
}
