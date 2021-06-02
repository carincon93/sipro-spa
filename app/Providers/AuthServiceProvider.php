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
        // 'App\Models\User' => 'App\Policies\UserPolicy',
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
            return $user->hasRole(74);
        });

        Gate::define('formular-proyecto-idi', function (User $user) {
            return $user->hasRole(74);
        });

        Gate::define('validar-autor', function (User $user, Proyecto $proyecto) {
            return
                $proyecto->participantes()->where('user_id', $user->id)->exists() ? true : false;
        });
    }

    public function registerSuperAdminPolicy()
    {
        Gate::before(function ($user, $ability) {
            return $user->hasRole(1) ? true : null;
        });
    }
}
