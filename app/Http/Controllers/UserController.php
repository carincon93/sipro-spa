<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', [User::class]);

        return Inertia::render('Users/Index', [
            'filters'   => request()->all('search'),
            'usuarios'  => User::orderBy('nombre', 'ASC')
                ->filterUser(request()->only('search'))->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', [User::class]);

        return Inertia::render('Users/Create', [
            'tiposDocumento'        => json_decode(Storage::get('json/tipos-documento.json'), true),
            'tiposParticipacion'    => json_decode(Storage::get('json/tipos-participacion.json'), true),
            'roles'                 => Role::select('id', 'name')->get('id')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $this->authorize('create', [User::class]);

        $user = new User();

        $user->nombre               = $request->nombre;
        $user->email                = $request->email;
        $user->password             = $user::makePassword($request->numero_documento);
        $user->tipo_documento       = $request->tipo_documento;
        $user->numero_documento     = $request->numero_documento;
        $user->numero_celular       = $request->numero_celular;
        $user->habilitado           = $request->habilitado;
        $user->tipo_participacion   = $request->tipo_participacion;
        $user->autorizacion_datos   = $request->autorizacion_datos;
        $user->centroFormacion()->associate($request->centro_formacion_id);

        $user->save();

        $user->assignRole($request->role_id);

        return redirect()->route('users.index')->with('success', 'El recurso se ha creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $this->authorize('view', [User::class, $user]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $this->authorize('update', [User::class, $user]);

        return Inertia::render('Users/Edit', [
            'usuario'               => $user,
            'tiposDocumento'        => json_decode(Storage::get('json/tipos-documento.json'), true),
            'tiposParticipacion'    => json_decode(Storage::get('json/tipos-participacion.json'), true),
            'rolesRelacionados'     => $user->roles()->pluck('id'),
            'roles'                 => Role::select('id', 'name')->get('id')
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        $this->authorize('update', [User::class, $user]);

        $user->nombre               = $request->nombre;
        $user->email                = $request->email;
        $user->tipo_documento       = $request->tipo_documento;
        $user->numero_documento     = $request->numero_documento;
        $user->numero_celular       = $request->numero_celular;
        $user->habilitado           = $request->habilitado;
        $user->tipo_participacion   = $request->tipo_participacion;
        $user->autorizacion_datos   = $request->autorizacion_datos;
        $user->centroFormacion()->associate($request->centro_formacion_id);

        if ($request->default_password) {
            $user->password = $user::makePassword($request->numero_documento);
        }

        $user->save();

        $user->syncRoles($request->role_id);

        return redirect()->back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $this->authorize('delete', [User::class, $user]);

        try {
            $user->delete();
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'No se puede elimiar el recurso debido a que está asociado a uno o varios proyectos.');
        }

        return redirect()->route('users.index')->with('success', 'El recurso se ha eliminado correctamente.');
    }
}
