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
            return $user->getAllPermissions()->whereIn('id', [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13])->count() > 0;
        });

        Gate::define('formular-proyecto', function (User $user) {
            return $user->getAllPermissions()->whereIn('id', [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13])->count() > 0;
        });

        Gate::define('validar-autor', function (User $user, Proyecto $proyecto) {
            return $proyecto->participantes()->where('user_id', $user->id)->exists() ? true : false || $user->hasRole(4) && $proyecto->centroFormacion->id == $user->dinamizadorCentroFormacion->id;
        });
    }

    public function registerSuperAdminPolicy()
    {
        Gate::before(function ($user, $ability) {
            return $user->hasRole(1) ? true : null;
        });
    }
}
