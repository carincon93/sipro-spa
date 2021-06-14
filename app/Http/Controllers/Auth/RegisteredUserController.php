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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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
            'tiposParticipacion'    => json_decode(Storage::get('json/tipos-participacion.json'), true),
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
            'tipo_participacion'    => $request->tipo_participacion,
            'autorizacion_datos'    => $request->autorizacion_datos,
            'centro_formacion_id'   => $request->centro_formacion_id
        ]);

        $user->syncRoles($request->role_id);

        event(new Registered($user));

        $centroFormacion = CentroFormacion::find($request->centro_formacion_id);
        $centroFormacion->dinamizadorSennova->notify(new NuevoUsuario($user));

        return redirect()->back()->with('success', '¡El registro ha sido exitoso!. Solicite al dinamizador de su centro de formación que lo habilite en la plataforma.');
    }
}
