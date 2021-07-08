<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegisterRequest;
use App\Models\CentroFormacion;
use App\Models\Role;
use App\Models\User;
use App\Notifications\NuevoUsuario;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;

use Inertia\Inertia;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return Inertia::render('Auth/Register', [
            'roles'                 => Role::select('id', 'name')->where('name', 'ilike', "%Proponente%")->get(),
            'tiposDocumento'        => json_decode(Storage::get('json/tipos-documento.json'), true),
            'tiposVinculacion'    => json_decode(Storage::get('json/tipos-vinculacion.json'), true),
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(UserRegisterRequest $request)
    {
        $user = User::create([
            'nombre'                => $request->nombre,
            'email'                 => $request->email,
            'password'              => Hash::make($request->password),
            'tipo_documento'        => $request->tipo_documento,
            'numero_documento'      => $request->numero_documento,
            'numero_celular'        => $request->numero_celular,
            'habilitado'            => false,
            'tipo_vinculacion'      => $request->tipo_vinculacion,
            'autorizacion_datos'    => $request->autorizacion_datos,
            'centro_formacion_id'   => $request->centro_formacion_id
        ]);

        $user->syncRoles($request->role_id);

        event(new Registered($user));

        $users = null;
        if ($user->whereHas('roles', function (Builder $query) use ($user) {
            return $query->where('name', 'ilike', '%proponente i+d+i%')->where('users.id', $user->id);
        })->first()) {
            $users = User::whereHas('roles', function (Builder $query) {
                return $query->where('name', 'ilike', '%activador i+d+i%');
            })->get();
        }

        if ($user->whereHas('roles', function (Builder $query) use ($user) {
            return $query->where('name', 'ilike', '%proponente cultura de la innovación%')->where('users.id', $user->id);
        })->first()) {
            $users = User::whereHas('roles', function (Builder $query) {
                return $query->where('name', 'ilike', '%activador cultura de la innovación%');
            })->get();
        }

        if ($user->whereHas('roles', function (Builder $query) use ($user) {
            return $query->where('name', 'ilike', '%proponente tecnoacademia%')->where('users.id', $user->id);
        })->first()) {
            $users = User::whereHas('roles', function (Builder $query) {
                return $query->where('name', 'ilike', '%activador tecnoacademia%');
            })->get();
        }

        if ($user->whereHas('roles', function (Builder $query) use ($user) {
            return $query->where('name', 'ilike', '%proponente tecnoparque%')->where('users.id', $user->id);
        })->first()) {
            $users = User::whereHas('roles', function (Builder $query) {
                return $query->where('name', 'ilike', '%activador tecnoparque%');
            })->get();
        }

        if ($user->whereHas('roles', function (Builder $query) use ($user) {
            return $query->where('name', 'ilike', '%proponente servicios tecnológicos%')->where('users.id', $user->id);
        })->first()) {
            $users = User::whereHas('roles', function (Builder $query) {
                return $query->where('name', 'ilike', '%activador servicios tecnológicos%');
            })->get();
        }

        if ($users) {
            Notification::send($users, new NuevoUsuario($user));
        }

        $centroFormacion = CentroFormacion::find($request->centro_formacion_id);
        $centroFormacion->dinamizadorSennova->notify(new NuevoUsuario($user));

        return redirect()->back()->with('success', '¡El registro ha sido exitoso!. Solicite al dinamizador de su centro de formación que lo habilite en la plataforma.');
    }
}
