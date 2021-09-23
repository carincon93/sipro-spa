<?php

namespace App\Providers;

use App\Models\Convocatoria;
use App\Models\Evaluacion\Evaluacion;
use App\Models\LineaProgramatica;
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
        'App\Models\Tecnoacademia' => 'App\Policies\TecnoacademiaPolicy',
        'App\Models\SemilleroInvestigacion' => 'App\Policies\SemilleroInvestigacionPolicy',
        'App\Models\LineaInvestigacion' => 'App\Policies\LineaInvestigacionPolicy',
        'App\Models\ProgramaFormacion' => 'App\Policies\ProgramaFormacionPolicy',
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
            return $user->hasRole(11) || $user->getAllPermissions()->whereIn('id', [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21])->count() > 0;
        });

        Gate::define('formular-proyecto', function (User $user, $lineaProgramaticaId) {

            $lineaProgramatica = LineaProgramatica::find($lineaProgramaticaId);

            if ($lineaProgramatica && $user->getAllPermissions()->whereIn('id', [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21])->count() > 0) {
                return true;
            } else {
                return $user->getAllPermissions()->whereIn('id', [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21])->count() > 0;
            }

            return false;
        });

        Gate::define('visualizar-proyecto-autor', function (User $user, Proyecto $proyecto) {
            if ($user->hasRole([5, 17, 18, 19, 20]) || $proyecto->participantes()->where('user_id', $user->id)->exists() || $user->evaluaciones()->where('proyecto_id', $proyecto->id)->first()) {
                return true;
            }

            if ($user->hasRole(2) && $user->directorRegional && $proyecto->centroFormacion->id == $user->directorRegional->id || $user->hasRole(3) && $proyecto->centroFormacion->id == $user->subdirectorCentroFormacion->id || $user->hasRole(4) && $user->dinamizadorCentroFormacion && $proyecto->centroFormacion->id == $user->dinamizadorCentroFormacion->id || $user->hasRole(21) && $proyecto->centroFormacion->id == $user->centroFormacion->id) {
                return true;
            } else {
                return $proyecto->lineaProgramatica->codigo == 69 && ($user->getAllPermissions()->where('id', 20)->first() ? $user->getAllPermissions()->where('id', 20)->first()->exists() : null) || $proyecto->lineaProgramatica->codigo == 70 && ($user->getAllPermissions()->where('id', 15)->first() ? $user->getAllPermissions()->where('id', 15)->first()->exists() : null) || $proyecto->lineaProgramatica->codigo == 68 && ($user->getAllPermissions()->where('id', 16)->first() ? $user->getAllPermissions()->where('id', 16)->first()->exists() : null) || $proyecto->lineaProgramatica->codigo == 65 && ($user->getAllPermissions()->where('id', 21)->first() ? $user->getAllPermissions()->where('id', 21)->first()->exists() : null) || $proyecto->lineaProgramatica->codigo == 66 && ($user->getAllPermissions()->where('id', 14)->first() ? $user->getAllPermissions()->where('id', 14)->first()->exists() : null) || $proyecto->lineaProgramatica->codigo == 82 && ($user->getAllPermissions()->where('id', 14)->first() ? $user->getAllPermissions()->where('id', 14)->first()->exists() : null) || $proyecto->lineaProgramatica->codigo == 23 && ($user->getAllPermissions()->where('id', 14)->first() ? $user->getAllPermissions()->where('id', 14)->first()->exists() : null);
            }

            return false;
        });

        Gate::define('modificar-proyecto-autor', function (User $user, Proyecto $proyecto) {
            if ($proyecto->modificable == false) {
                return false;
            }

            if ($user->hasRole([5, 17, 18, 19, 20]) || $proyecto->participantes()->where('user_id', $user->id)->exists()) {
                return true;
            }

            if ($proyecto->modificable == true) {
                if ($proyecto->participantes()->where('user_id', $user->id)->exists() || $user->hasRole(4) && $user->dinamizadorCentroFormacion && $proyecto->centroFormacion->id == $user->dinamizadorCentroFormacion->id && $proyecto->a_evaluar == false || $user->hasRole(21) && $proyecto->centroFormacion->id == $user->centroFormacion->id && $proyecto->a_evaluar == false) {
                    return true;
                }
            }

            return false;
        });

        Gate::define('validar-dinamizador', function (User $user, Proyecto $proyecto) {
            return $user->hasRole(4) && $proyecto->centroFormacion->id == $user->dinamizadorCentroFormacion->id;
        });

        Gate::define('visualizar-evaluacion-autor', function (User $user, Evaluacion $evaluacion) {
            if ($evaluacion->user_id == $user->id) {
                return true;
            }

            return false;
        });

        Gate::define('modificar-evaluacion-autor', function (User $user, Evaluacion $evaluacion) {
            if ($evaluacion->modificable == true && $evaluacion->user_id == $user->id) {
                return true;
            }

            return false;
        });
    }

    public function registerSuperAdminPolicy()
    {
        Gate::before(function ($user, $ability) {
            return $user->hasRole(1) ? true : null;
        });
    }
}
