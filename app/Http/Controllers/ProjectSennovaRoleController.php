<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectSennovaRoleRequest;
use App\Models\Convocatoria;
use App\Models\Proyecto;
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
    public function index(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('viewAny', [ProjectSennovaRole::class]);

        $proyecto->projectType->programmaticLine;
        $proyecto->makeHidden(
            'rdi', 
            'projectSennovaBudgets', 
            'updated_at',
        );

        return Inertia::render('Convocatorias/Proyectos/ProjectSennovaRoles/Index', [
            'convocatoria'                  => $convocatoria->only('id'),
            'project'               => $proyecto,
            'filters'               => request()->all('search'),
            'projectSennovaRoles'   => ProjectSennovaRole::where('proyecto_id', $proyecto->id)->filterProjectSennovaRole(request()->only('search'))->with('convocatoriaSennovaRole.sennovaRole')->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('create', [ProjectSennovaRole::class]);

        return Inertia::render('Convocatorias/Proyectos/ProjectSennovaRoles/Create', [
            'convocatoria'              => $convocatoria->only('id'),
            'project'           => $proyecto->only('id', 'diff_months'),
            'programmaticLine'  => $proyecto->projectType->programmaticLine->only('id')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectSennovaRoleRequest $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('create', [ProjectSennovaRole::class]);

        if (ProjectRoleValidationTrait::studentRoleValidation($request->convocatoria_sennova_role_id, $proyecto, null, $request->qty_months, $request->qty_roles)) {
            return redirect()->back()->with('error', "Máximo 2 monitorias de 3 a 6 meses cada una");
        }

        $proyectoSennovaRole = new ProjectSennovaRole();
        $proyectoSennovaRole->qty_months     = $request->qty_months;
        $proyectoSennovaRole->qty_roles      = $request->qty_roles;
        $proyectoSennovaRole->description    = $request->description;
        $proyectoSennovaRole->project()->associate($proyecto->id);
        $proyectoSennovaRole->convocatoriaSennovaRole()->associate($request->convocatoria_sennova_role_id);

        $proyectoSennovaRole->save();

        return redirect()->route('convocatorias.projects.project-sennova-roles.index', [$convocatoria, $proyecto])->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProjectSennovaRole  $proyectoSennovaRole
     * @return \Illuminate\Http\Response
     */
    public function show(Convocatoria $convocatoria, Proyecto $proyecto, ProjectSennovaRole $proyectoSennovaRole)
    {
        $this->authorize('view', [ProjectSennovaRole::class, $proyectoSennovaRole]);

        return Inertia::render('Convocatorias/Proyectos/ProjectSennovaRoles/Show', [
            'projectSennovaRole' => $proyectoSennovaRole
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProjectSennovaRole  $proyectoSennovaRole
     * @return \Illuminate\Http\Response
     */
    public function edit(Convocatoria $convocatoria, Proyecto $proyecto, ProjectSennovaRole $proyectoSennovaRole)
    {
        $this->authorize('update', [ProjectSennovaRole::class, $proyectoSennovaRole]);

        return Inertia::render('Convocatorias/Proyectos/ProjectSennovaRoles/Editar', [
            'projectSennovaRole'    => $proyectoSennovaRole,
            'convocatoria'                  => $convocatoria->only('id'),
            'project'               => $proyecto->only('id', 'diff_months'),
            'roleName'              => $proyectoSennovaRole->convocatoriaSennovaRole->sennovaRole->only('name'),
            'programmaticLine'      => $proyecto->projectType->programmaticLine->only('id')
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProjectSennovaRole  $proyectoSennovaRole
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectSennovaRoleRequest $request, Convocatoria $convocatoria, Proyecto $proyecto, ProjectSennovaRole $proyectoSennovaRole)
    {
        $this->authorize('update', [ProjectSennovaRole::class, $proyectoSennovaRole]);

        if (ProjectRoleValidationTrait::studentRoleValidation($request->convocatoria_sennova_role_id, $proyecto, $proyectoSennovaRole, $request->qty_months, $request->qty_roles)) {
            return redirect()->back()->with('error', "Máximo 2 monitorias de 3 a 6 meses cada una");
        }

        $proyectoSennovaRole->qty_months     = $request->qty_months;
        $proyectoSennovaRole->qty_roles      = $request->qty_roles;
        $proyectoSennovaRole->description    = $request->description;
        $proyectoSennovaRole->project()->associate($proyecto->id);
        $proyectoSennovaRole->convocatoriaSennovaRole()->associate($request->convocatoria_sennova_role_id);

        $proyectoSennovaRole->save();

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProjectSennovaRole  $proyectoSennovaRole
     * @return \Illuminate\Http\Response
     */
    public function destroy(Convocatoria $convocatoria, Proyecto $proyecto, ProjectSennovaRole $proyectoSennovaRole)
    {
        $this->authorize('delete', [ProjectSennovaRole::class, $proyectoSennovaRole]);

        $proyectoSennovaRole->delete();

        return redirect()->route('convocatorias.projects.project-sennova-roles.index', [$convocatoria, $proyecto])->with('success', 'The resource has been deleted successfully.');
    }
}
