<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectSennovaRoleRequest;
use App\Models\Call;
use App\Models\Project;
use App\Models\ProjectSennovaRole;
use Illuminate\Http\Request;
use App\Http\Traits\ProjectRoleValidationTrait;
use Inertia\Inertia;

class ProjectSennovaRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Call $call, Project $project)
    {
        $this->authorize('viewAny', [ProjectSennovaRole::class]);

        $project->projectType->programmaticLine;
        $project->makeHidden(
            'rdi', 
            'projectSennovaBudgets', 
            'updated_at',
        );

        return Inertia::render('Calls/Projects/ProjectSennovaRoles/Index', [
            'call'                  => $call->only('id'),
            'project'               => $project,
            'filters'               => request()->all('search'),
            'projectSennovaRoles'   => ProjectSennovaRole::where('project_id', $project->id)->filterProjectSennovaRole(request()->only('search'))->with('callSennovaRole.sennovaRole')->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Call $call, Project $project)
    {
        $this->authorize('create', [ProjectSennovaRole::class]);

        return Inertia::render('Calls/Projects/ProjectSennovaRoles/Create', [
            'call'              => $call->only('id'),
            'project'           => $project->only('id', 'diff_months'),
            'programmaticLine'  => $project->projectType->programmaticLine->only('id')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectSennovaRoleRequest $request, Call $call, Project $project)
    {
        $this->authorize('create', [ProjectSennovaRole::class]);

        if (ProjectRoleValidationTrait::studentRoleValidation($request->call_sennova_role_id, $project, null, $request->qty_months, $request->qty_roles)) {
            return redirect()->back()->with('error', "Máximo 2 monitorias de 3 a 6 meses cada una");
        }

        $projectSennovaRole = new ProjectSennovaRole();
        $projectSennovaRole->qty_months     = $request->qty_months;
        $projectSennovaRole->qty_roles      = $request->qty_roles;
        $projectSennovaRole->description    = $request->description;
        $projectSennovaRole->project()->associate($project->id);
        $projectSennovaRole->callSennovaRole()->associate($request->call_sennova_role_id);

        $projectSennovaRole->save();

        return redirect()->route('calls.projects.project-sennova-roles.index', [$call, $project])->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProjectSennovaRole  $projectSennovaRole
     * @return \Illuminate\Http\Response
     */
    public function show(Call $call, Project $project, ProjectSennovaRole $projectSennovaRole)
    {
        $this->authorize('view', [ProjectSennovaRole::class, $projectSennovaRole]);

        return Inertia::render('Calls/Projects/ProjectSennovaRoles/Show', [
            'projectSennovaRole' => $projectSennovaRole
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProjectSennovaRole  $projectSennovaRole
     * @return \Illuminate\Http\Response
     */
    public function edit(Call $call, Project $project, ProjectSennovaRole $projectSennovaRole)
    {
        $this->authorize('update', [ProjectSennovaRole::class, $projectSennovaRole]);

        return Inertia::render('Calls/Projects/ProjectSennovaRoles/Editar', [
            'projectSennovaRole'    => $projectSennovaRole,
            'call'                  => $call->only('id'),
            'project'               => $project->only('id', 'diff_months'),
            'roleName'              => $projectSennovaRole->callSennovaRole->sennovaRole->only('name'),
            'programmaticLine'      => $project->projectType->programmaticLine->only('id')
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProjectSennovaRole  $projectSennovaRole
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectSennovaRoleRequest $request, Call $call, Project $project, ProjectSennovaRole $projectSennovaRole)
    {
        $this->authorize('update', [ProjectSennovaRole::class, $projectSennovaRole]);

        if (ProjectRoleValidationTrait::studentRoleValidation($request->call_sennova_role_id, $project, $projectSennovaRole, $request->qty_months, $request->qty_roles)) {
            return redirect()->back()->with('error', "Máximo 2 monitorias de 3 a 6 meses cada una");
        }

        $projectSennovaRole->qty_months     = $request->qty_months;
        $projectSennovaRole->qty_roles      = $request->qty_roles;
        $projectSennovaRole->description    = $request->description;
        $projectSennovaRole->project()->associate($project->id);
        $projectSennovaRole->callSennovaRole()->associate($request->call_sennova_role_id);

        $projectSennovaRole->save();

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProjectSennovaRole  $projectSennovaRole
     * @return \Illuminate\Http\Response
     */
    public function destroy(Call $call, Project $project, ProjectSennovaRole $projectSennovaRole)
    {
        $this->authorize('delete', [ProjectSennovaRole::class, $projectSennovaRole]);

        $projectSennovaRole->delete();

        return redirect()->route('calls.projects.project-sennova-roles.index', [$call, $project])->with('success', 'The resource has been deleted successfully.');
    }
}
