<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectAnnexeRequest;
use App\Models\Call;
use App\Models\Project;
use App\Models\Annexe;
use App\Models\ProjectAnnexe;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectAnnexeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Call $call, Project $project)
    {
        $this->authorize('viewAny', [ProjectAnnexe::class]);

        return Inertia::render('Calls/Projects/ProjectAnnexes/Index', [
            'filters'           => request()->all('search'),
            'projectAnnexes'    => $project->projectAnnexes()->select('project_annexes.id', 'project_annexes.annexe_id', 'project_annexes.file', 'annexes.name')
                ->join('annexes', 'project_annexes.annexe_id', 'annexes.id')
                ->filterProjectAnnexe(request()->only('search'))->paginate(),
            'call'      => $call,
            'project'   => $project,
            'annexes'   => Annexe::select('id', 'name')->join('annexe_programmatic_line', 'annexes.id', 'annexe_programmatic_line.annexe_id')->where('annexe_programmatic_line.programmatic_line_id', $project->projectType->programmaticLine->id)->paginate()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Call $call, Project $project)
    {
        $this->authorize('create', [ProjectAnnexe::class]);

        return Inertia::render('Calls/Projects/ProjectAnnexes/Create', [
            'call'      => $call,
            'project'   => $project,
            'annexes'   => Annexe::select('id as value', 'name as label')->join('annexe_programmatic_line', 'annexes.id', 'annexe_programmatic_line.annexe_id')->where('annexe_programmatic_line.programmatic_line_id', $project->projectType->programmaticLine->id)->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectAnnexeRequest $request, Call $call, Project $project)
    {
        $this->authorize('create', [ProjectAnnexe::class]);

        $annexe = Annexe::select('id', 'name')->where('id', $request->annexe_id)->first();

        $annexeName     = Str::slug(substr($annexe->name, 0, 30), '-');
        $random         = Str::random(5);
        $requestFile    = $request->file;
        $fileName       = "$project->code-$annexeName-cod$random.".$requestFile->extension();
        $file = $requestFile->storeAs(
            'annexes', $fileName
        );

        ProjectAnnexe::updateOrCreate(
            ['project_id' => $project->id, 'annexe_id' => $annexe->id],
            ['file' => $file]
        );

        return redirect()->route('calls.projects.project-annexes.index', [$call, $project])->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProjectAnnexe  $projectAnnexe
     * @return \Illuminate\Http\Response
     */
    public function show (Call $call, Project $project, ProjectAnnexe $projectAnnexe)
    {
        $this->authorize('view', [ProjectAnnexe::class, $projectAnnexe]);

        return Inertia::render('Calls/Projects/ProjectAnnexes/Show', [
            'projectAnnexe' => $projectAnnexe
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProjectAnnexe  $projectAnnexe
     * @return \Illuminate\Http\Response
     */
    public function edit (Call $call, Project $project, ProjectAnnexe $projectAnnexe)
    {
        $this->authorize('update', [ProjectAnnexe::class, $projectAnnexe]);

        return Inertia::render('Calls/Projects/ProjectAnnexes/Editar', [
            'call'          => $call,
            'project'       => $project,
            'projectAnnexe' => $projectAnnexe,
            'annexes'       => Annexe::select('id as value', 'name as label')->join('annexe_programmatic_line', 'annexes.id', 'annexe_programmatic_line.annexe_id')->where('annexe_programmatic_line.programmatic_line_id', $project->projectType->programmaticLine->id)->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProjectAnnexe  $projectAnnexe
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectAnnexeRequest $request, Call $call, Project $project, ProjectAnnexe $projectAnnexe)
    {
        $this->authorize('update', [ProjectAnnexe::class, $projectAnnexe]);

        $projectAnnexe->fieldName = $request->fieldName;
        $projectAnnexe->fieldName = $request->fieldName;
        $projectAnnexe->fieldName = $request->fieldName;

        $projectAnnexe->save();

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProjectAnnexe  $projectAnnexe
     * @return \Illuminate\Http\Response
     */
    public function destroy (Call $call, Project $project, ProjectAnnexe $projectAnnexe)
    {
        $this->authorize('delete', [ProjectAnnexe::class, $projectAnnexe]);

        $projectAnnexe->delete();

        return redirect()->route('calls.projects.project-annexes.index', [$call, $project])->with('success', 'The resource has been deleted successfully.');
    }

    /**
     * download
     *
     * @param  mixed $call
     * @param  mixed $project
     * @param  mixed $projectAnnexe
     * @return void
     */
    public function download (Call $call, Project $project, ProjectAnnexe $projectAnnexe)
    {
        return response()->download(storage_path("app/$projectAnnexe->file"));
    }
}
