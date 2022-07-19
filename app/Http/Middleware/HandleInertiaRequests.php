<?php

namespace App\Http\Middleware;

use App\Models\Convocatoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Middleware;
use Illuminate\Support\Facades\Session;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request)
    {
        return array_merge(parent::share($request), [
            'csrf_token'    => csrf_token(),
            'convocatoria'  => Convocatoria::where('esta_activa', true)->first(),
            'allowed'       => collect([
                'ambientes_modernizacion' => Gate::inspect('viewAny', [AmbienteModernizacion::class])->allowed()
            ]),
            'auth' => [
                'user'                  => $request->user() ? $request->user()->only('id', 'nombre', 'nombre_usuario', 'email', 'roles', 'can', 'can_by_user', 'centro_formacion_id') : null,
                'notificaciones'        => $request->user() ? $request->user()->unreadNotifications()->orderBy('created_at', 'DESC')->take(3)->get() : null,
                'numeroNotificaciones'  => $request->user() ? $request->user()->unreadNotifications()->count() : 0
            ],
            'flash' => function () {
                return [
                    'success'   => Session::get('success'),
                    'error'     => Session::get('error'),
                ];
            },
        ]);
    }
}
