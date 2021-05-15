<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProyectoRolSennovaRequest;
use App\Models\Convocatoria;
use App\Models\Proyecto;
use App\Models\ProyectoRolSennova;
use Illuminate\Http\Request;
use App\Http\Traits\ProjectRoleValidationTrait;
use Inertia\Inertia;

class ProyectoRolSennovaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('viewAny', [ProyectoRolSennova::class]);

        return Inertia::render('Convocatorias/Proyectos/ProyectoRolSennova/Index', [
            'convocatoria'           => $convocatoria->only('id'),
            'proyecto'               => $proyecto,
            'filters'                => request()->all('search'),
            'proyectoRolesSennova'   => ProyectoRolSennova::where('proyecto_id', $proyecto->id)->filterProyectoRolSennova(request()->only('search'))->with('convocatoriaSennovaRole.sennovaRole')->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('create', [ProyectoRolSennova::class]);

        return Inertia::render('Convocatorias/Proyectos/ProyectoRolSennova/Create', [
            'convocatoria'       => $convocatoria->only('id'),
            'proyecto'           => $proyecto->only('id', 'diff_months'),
            'lineaProgramatica'  => $proyecto->tipoProyecto->lineaProgramatica->only('id')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProyectoRolSennovaRequest $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('create', [ProyectoRolSennova::class]);

        if (ProjectRoleValidationTrait::studentRoleValidation($request->convocatoria_sennova_role_id, $proyecto, null, $request->cant_meses, $request->cant_roles)) {
            return redirect()->back()->with('error', "Máximo 2 monitorias de 3 a 6 meses cada una");
        }

        $proyectoSennovaRole = new ProyectoRolSennova();
        $proyectoSennovaRole->cant_meses     = $request->cant_meses;
        $proyectoSennovaRole->cant_roles     = $request->cant_roles;
        $proyectoSennovaRole->description    = $request->description;
        $proyectoSennovaRole->proyecto()->associate($proyecto->id);
        $proyectoSennovaRole->convocatoriaSennovaRole()->associate($request->convocatoria_sennova_role_id);

        $proyectoSennovaRole->save();

        return redirect()->route('convocatorias.proyectos.proyecto-sennova-roles.index', [$convocatoria, $proyecto])->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProyectoRolSennova  $proyectoSennovaRole
     * @return \Illuminate\Http\Response
     */
    public function show(Convocatoria $convocatoria, Proyecto $proyecto, ProyectoRolSennova $proyectoSennovaRole)
    {
        $this->authorize('view', [ProyectoRolSennova::class, $proyectoSennovaRole]);

        return Inertia::render('Convocatorias/Proyectos/ProyectoRolSennova/Show', [
            'proyectoSennovaRole' => $proyectoSennovaRole
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProyectoRolSennova  $proyectoSennovaRole
     * @return \Illuminate\Http\Response
     */
    public function edit(Convocatoria $convocatoria, Proyecto $proyecto, ProyectoRolSennova $proyectoSennovaRole)
    {
        $this->authorize('update', [ProyectoRolSennova::class, $proyectoSennovaRole]);

        return Inertia::render('Convocatorias/Proyectos/ProyectoRolSennova/Editar', [
            'proyectoSennovaRole'    => $proyectoSennovaRole,
            'convocatoria'                  => $convocatoria->only('id'),
            'proyecto'               => $proyecto->only('id', 'diff_months'),
            'roleName'              => $proyectoSennovaRole->convocatoriaSennovaRole->sennovaRole->only('name'),
            'lineaProgramatica'      => $proyecto->tipoProyecto->lineaProgramatica->only('id')
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProyectoRolSennova  $proyectoSennovaRole
     * @return \Illuminate\Http\Response
     */
    public function update(ProyectoRolSennovaRequest $request, Convocatoria $convocatoria, Proyecto $proyecto, ProyectoRolSennova $proyectoSennovaRole)
    {
        $this->authorize('update', [ProyectoRolSennova::class, $proyectoSennovaRole]);

        if (ProjectRoleValidationTrait::studentRoleValidation($request->convocatoria_sennova_role_id, $proyecto, $proyectoSennovaRole, $request->cant_meses, $request->cant_roles)) {
            return redirect()->back()->with('error', "Máximo 2 monitorias de 3 a 6 meses cada una");
        }

        $proyectoSennovaRole->cant_meses     = $request->cant_meses;
        $proyectoSennovaRole->cant_roles      = $request->cant_roles;
        $proyectoSennovaRole->description    = $request->description;
        $proyectoSennovaRole->proyecto()->associate($proyecto->id);
        $proyectoSennovaRole->convocatoriaSennovaRole()->associate($request->convocatoria_sennova_role_id);

        $proyectoSennovaRole->save();

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProyectoRolSennova  $proyectoSennovaRole
     * @return \Illuminate\Http\Response
     */
    public function destroy(Convocatoria $convocatoria, Proyecto $proyecto, ProyectoRolSennova $proyectoSennovaRole)
    {
        $this->authorize('delete', [ProyectoRolSennova::class, $proyectoSennovaRole]);

        $proyectoSennovaRole->delete();

        return redirect()->route('convocatorias.proyectos.proyecto-sennova-roles.index', [$convocatoria, $proyecto])->with('success', 'The resource has been deleted successfully.');
    }
}
