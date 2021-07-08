<?php

namespace App\Providers;

use App\Models\Proyecto;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\User' => 'App\Policies\UserPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $this->registerSuperAdminPolicy();

        Gate::define('listar-convocatorias', function (User $user) {
            return $user->getAllPermissions()->whereIn('id', [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 16, 15, 20, 21])->count() > 0;
        });

        Gate::define('formular-proyecto', function (User $user) {
            return $user->getAllPermissions()->whereIn('id', [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 16, 15, 20, 21])->count() > 0;
        });

        Gate::define('visualizar-proyecto-autor', function (User $user, Proyecto $proyecto) {
            return $proyecto->lineaProgramatica->codigo == 69 && ($user->getAllPermissions()->where('id', 20)->first() ? $user->getAllPermissions()->where('id', 20)->first()->exists() : null) || $proyecto->lineaProgramatica->codigo == 70 && ($user->getAllPermissions()->where('id', 15)->first() ? $user->getAllPermissions()->where('id', 15)->first()->exists() : null) || $proyecto->lineaProgramatica->codigo == 68 && ($user->getAllPermissions()->where('id', 16)->first() ? $user->getAllPermissions()->where('id', 16)->first()->exists() : null) || $proyecto->lineaProgramatica->codigo == 65 && ($user->getAllPermissions()->where('id', 21)->first() ? $user->getAllPermissions()->where('id', 21)->first()->exists() : null) || $proyecto->lineaProgramatica->codigo == 66 && ($user->getAllPermissions()->where('id', 14)->first() ? $user->getAllPermissions()->where('id', 14)->first()->exists() : null) || $proyecto->lineaProgramatica->codigo == 82 && ($user->getAllPermissions()->where('id', 14)->first() ? $user->getAllPermissions()->where('id', 14)->first()->exists() : null) || $proyecto->lineaProgramatica->codigo == 23 && ($user->getAllPermissions()->where('id', 14)->first() ? $user->getAllPermissions()->where('id', 14)->first()->exists() : null) || $proyecto->participantes()->where('user_id', $user->id)->exists() ? true : false || $user->hasRole(4) && $proyecto->centroFormacion->id == $user->dinamizadorCentroFormacion->id;
        });

        Gate::define('modificar-proyecto-autor', function (User $user, Proyecto $proyecto) {
            return $proyecto->participantes()->where('user_id', $user->id)->exists() ? true : false && $proyecto->finalizado == false && $proyecto->radicado == false || $user->hasRole(4) && $proyecto->centroFormacion->id == $user->dinamizadorCentroFormacion->id && $proyecto->radicado == false;
        });

        Gate::define('validar-dinamizador', function (User $user, Proyecto $proyecto) {
            return $user->hasRole(4) && $proyecto->centroFormacion->id == $user->dinamizadorCentroFormacion->id;
        });
    }

    public function registerSuperAdminPolicy()
    {
        Gate::before(function ($user, $ability) {
            return $user->hasRole(1) ? true : null;
        });
    }
}
