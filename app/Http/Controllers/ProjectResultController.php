<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectResultRequest;
use App\Models\ProjectResult;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectResultController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', [ProjectResult::class]);

        return Inertia::render('ProjectResults/Index', [
            'filters'   => request()->all('search'),
            'projectResults' => ProjectResult::orderBy('', 'ASC')
                ->filterProjectResult(request()->only('search'))->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', [ProjectResult::class]);

        return Inertia::render('ProjectResults/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectResultRequest $request)
    {
        $this->authorize('create', [ProjectResult::class]);

        $projectResult = new ProjectResult();
        $projectResult->fieldName = $request->fieldName;
        $projectResult->fieldName = $request->fieldName;
        $projectResult->fieldName = $request->fieldName;

        $projectResult->save();

        return redirect()->route('resourceRoute.index')->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProjectResult  $projectResult
     * @return \Illuminate\Http\Response
     */
    public function show(ProjectResult $projectResult)
    {
        $this->authorize('view', [ProjectResult::class, $projectResult]);

        return Inertia::render('ProjectResults/Show', [
            'projectResult' => $projectResult
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProjectResult  $projectResult
     * @return \Illuminate\Http\Response
     */
    public function edit(ProjectResult $projectResult)
    {
        $this->authorize('update', [ProjectResult::class, $projectResult]);

        return Inertia::render('ProjectResults/Editar', [
            'projectResult' => $projectResult
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProjectResult  $projectResult
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectResultRequest $request, ProjectResult $projectResult)
    {
        $this->authorize('update', [ProjectResult::class, $projectResult]);

        $projectResult->fieldName = $request->fieldName;
        $projectResult->fieldName = $request->fieldName;
        $projectResult->fieldName = $request->fieldName;

        $projectResult->save();

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProjectResult  $projectResult
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProjectResult $projectResult)
    {
        $this->authorize('delete', [ProjectResult::class, $projectResult]);

        $projectResult->delete();

        return redirect()->route('resourceRoute.index')->with('success', 'The resource has been deleted successfully.');
    }
}
