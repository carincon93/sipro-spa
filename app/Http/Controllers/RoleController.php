<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', [Role::class]);

        return Inertia::render('Roles/Index', [
            'filters'   => request()->all('search'),
            'roles'     => Role::orderBy('name', 'ASC')
                ->filterRole(request()->only('search'))->paginate()->appends(['search' => request()->search]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', [Role::class]);

        return Inertia::render('Roles/Create', [
            'allPermissions'  => Permission::orderBy('id')->get(['id', 'name']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        $this->authorize('create', [Role::class]);

        $role = new Role();
        $role->name        = $request->name;
        $role->description = $request->description;
        $role->guard_name  = 'web';

        $role->save();

        $role->syncPermissions($request->permission_id);

        return redirect()->route('roles.index')->with('success', 'El recurso se ha creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        $this->authorize('view', [Role::class, $role]);

        return Inertia::render('Roles/Show', [
            'role' => $role
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $this->authorize('update', [Role::class, $role]);

        return Inertia::render('Roles/Edit', [
            'role'             => $role,
            'allPermissions'   => Permission::orderBy('id')->get(['id', 'name']),
            'rolePermissions'  => $role->permissions->pluck('id')
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(RoleRequest $request, Role $role)
    {
        $this->authorize('update', [Role::class, $role]);

        $role->name        = $request->name;
        $role->description = $request->description;
        $role->guard_name  = 'web';
        $role->save();

        $role->syncPermissions($request->permission_id);

        return back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $this->authorize('delete', [Role::class, $role]);

        if (in_array($role->id, [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21])) {
            return back()->with('error', 'El recurso no se puede eliminar.');
        }

        $role->delete();

        return redirect()->route('roles.index')->with('success', 'El recurso se ha eliminado correctamente.');
    }
}
