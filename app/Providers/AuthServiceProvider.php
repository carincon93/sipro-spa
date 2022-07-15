<?php

namespace App\Providers;

use App\Models\Evaluacion\Evaluacion;
use App\Models\LineaProgramatica;
use App\Models\Proyecto;
use App\Models\ProyectoIdiTecnoacademia;
use App\Models\ProyectoIdiTecnoacademiaProducto;
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
        'App\Models\User'                                   => 'App\Policies\UserPolicy',
        'App\Models\Tecnoacademia'                          => 'App\Policies\TecnoacademiaPolicy',
        'App\Models\SemilleroInvestigacion'                 => 'App\Policies\SemilleroInvestigacionPolicy',
        'App\Models\LineaInvestigacion'                     => 'App\Policies\LineaInvestigacionPolicy',
        'App\Models\AmbienteModernizacion'                  => 'App\Policies\AmbienteModernizacionPolicy',
        'App\Models\ProgramaFormacion'                      => 'App\Policies\ProgramaFormacionPolicy',
        'App\Models\Evaluacion\Evaluacion'                  => 'App\Policies\EvaluacionPolicy',
        'App\Models\Evaluacion\ProyectoCapacidadInstalada'  => 'App\Policies\ProyectoCapacidadInstaladaPolicy',
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

        Gate::define('formular-proyecto', function (User $user, $lineaProgramaticaId, $convocatoria) {
            $lineaProgramatica = LineaProgramatica::find($lineaProgramaticaId);

            // Usuarios específicos con permisos de creación de proyectos
            if ($user->can_by_user->search(11) !== false && $lineaProgramatica->codigo == 65 || $user->can_by_user->search(1) !== false && $lineaProgramatica->codigo == 23 || $user->can_by_user->search(1) !== false && $lineaProgramatica->codigo == 66 || $user->can_by_user->search(1) !== false && $lineaProgramatica->codigo == 82 || $user->can_by_user->search(5) !== false && $lineaProgramatica->codigo == 68 || $user->can_by_user->search(8) !== false && $lineaProgramatica->codigo == 70 || $user->can_by_user->search(17) !== false && $lineaProgramatica->codigo == 69) {
                return true;
            }

            // Cuando la convocatoria es de proyectos DEMO y está activa
            if ($convocatoria && $convocatoria->tipo_convocatoria == 2 && $convocatoria->esta_activa == true) {
                return true;
            }

            // NO se puede formular cuando es una convocatoria NORMAL, está activa y está en un fase diferente a la de formulación
            if ($convocatoria->tipo_convocatoria == 1 && $convocatoria->esta_activa && $convocatoria->fase != 1) {
                return false;
            }

            // Si el usuario tiene alguno de los siguiente permisos de cualquier línea programática: CREAR, EDITAR, ELIMINAR o VISUALIZACIÓN
            if ($lineaProgramatica) {
                return $user->getAllPermissions()->whereIn('id', [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21])->count() > 0;
            }

            return false;
        });

        Gate::define('visualizar-proyecto-autor', function (User $user, Proyecto $proyecto) {
            // Puede ser visualizado por Activadores de línea / pariticipantes / evaluadores
            if ($user->hasRole([5, 17, 18, 19, 20]) || $proyecto->participantes()->where('user_id', $user->id)->exists() || $user->evaluaciones()->where('proyecto_id', $proyecto->id)->first()) {
                return true;
            }

            // Puede ser visualizado por el Director regional / Subdirector de centro / Dinamizador SENNOVA / Líder de grupo de investigación
            if ($user->hasRole(2) && $user->directorRegional && $proyecto->centroFormacion->id == $user->directorRegional->id || $user->hasRole(3) && $proyecto->centroFormacion->id == $user->subdirectorCentroFormacion->id || $user->hasRole(4) && $user->dinamizadorCentroFormacion && $proyecto->centroFormacion->id == $user->dinamizadorCentroFormacion->id || $user->hasRole(21) && $proyecto->centroFormacion->id == $user->centroFormacion->id) {
                return true;
            } else {
                // Puede ser visualizado por roles con permisos específicos de visualización 
                return $proyecto->lineaProgramatica->codigo == 69 && ($user->getAllPermissions()->where('id', 20)->first() ? $user->getAllPermissions()->where('id', 20)->first()->exists() : null) ||
                    $proyecto->lineaProgramatica->codigo == 70 && ($user->getAllPermissions()->where('id', 15)->first() ?  $user->getAllPermissions()->where('id', 15)->first()->exists() : null) ||
                    $proyecto->lineaProgramatica->codigo == 68 && ($user->getAllPermissions()->where('id', 16)->first() ?  $user->getAllPermissions()->where('id', 16)->first()->exists() : null) ||
                    $proyecto->lineaProgramatica->codigo == 65 && ($user->getAllPermissions()->where('id', 21)->first() ?  $user->getAllPermissions()->where('id', 21)->first()->exists() : null) ||
                    $proyecto->lineaProgramatica->codigo == 66 && ($user->getAllPermissions()->where('id', 14)->first() ?  $user->getAllPermissions()->where('id', 14)->first()->exists() : null) ||
                    $proyecto->lineaProgramatica->codigo == 82 && ($user->getAllPermissions()->where('id', 14)->first() ?  $user->getAllPermissions()->where('id', 14)->first()->exists() : null) ||
                    $proyecto->lineaProgramatica->codigo == 23 && ($user->getAllPermissions()->where('id', 14)->first() ?  $user->getAllPermissions()->where('id', 14)->first()->exists() : null);
            }

            return false;
        });

        Gate::define('modificar-proyecto-autor', function (User $user, Proyecto $proyecto) {
            // $user->hasRole([5, 17, 18, 19, 20]) -> Activadores de línea
            if ($user->hasRole([5, 17, 18, 19, 20])) {
                return true;
            }

            if ($proyecto->modificable == false) {
                return false;
            }

            if ($proyecto->modificable == true) {
                // Puede ser modificado por el autor / Dinamizador SENNOVA / Líder de grupo de investigación
                if ($proyecto->participantes()->where('user_id', $user->id)->exists() || $user->hasRole(4) && $user->dinamizadorCentroFormacion && $proyecto->centroFormacion->id == $user->dinamizadorCentroFormacion->id && $proyecto->habilitado_para_evaluar == false || $user->hasRole(21) && $proyecto->centroFormacion->id == $user->centroFormacion->id && $proyecto->habilitado_para_evaluar == false) {
                    return true;
                }
            }

            return false;
        });

        Gate::define('eliminar-proyecto-autor', function (User $user, Proyecto $proyecto) {
            if (!$proyecto->radicado) {
                // Si el proyecto no ha sido radicado este puede ser eliminado por el autor / Dinamizador SENNOVA / Líder de grupo de investigación
                if ($proyecto->participantes()->where('user_id', $user->id)->exists() || $user->hasRole(4) && $user->dinamizadorCentroFormacion && $proyecto->centroFormacion->id == $user->dinamizadorCentroFormacion->id || $user->hasRole(21) && $proyecto->centroFormacion->id == $user->centroFormacion->id) {
                    return true;
                }
            }

            return false;
        });

        Gate::define('crear-proyecto-idi-tecnoacademia', function (User $user) {
            if ($user->hasRole([5, 10, 12, 22])) {
                return true;
            }

            return false;
        });

        Gate::define('modificar-proyecto-idi-tecnoacademia', function (User $user, ProyectoIdiTecnoacademia $proyectoIdiTecnoacademia) {
            if ($proyectoIdiTecnoacademia->participantes()->where('user_id', $user->id)->exists()) {
                return true;
            }

            return false;
        });

        Gate::define('modificar-producto-idi-tecnoacademia', function (User $user, ProyectoIdiTecnoacademia $proyectoIdiTecnoacademia, ProyectoIdiTecnoacademiaProducto $proyectoIdiTecnoacademiaProducto) {
            if ($proyectoIdiTecnoacademia->participantes()->where('user_id', $user->id)->exists() && $proyectoIdiTecnoacademiaProducto->proyecto_idi_tecnoacademia_id == $proyectoIdiTecnoacademia->id) {
                return true;
            }

            return false;
        });

        Gate::define('validar-dinamizador', function (User $user, Proyecto $proyecto) {
            return $user->hasRole(4) && $proyecto->centroFormacion->id == $user->dinamizadorCentroFormacion->id;
        });

        Gate::define('visualizar-evaluacion-autor', function (User $user, Evaluacion $evaluacion) {
            if ($user->hasRole([20, 18, 19, 5, 17, 11]) && $evaluacion->user_id == $user->id) {
                return true;
            }

            return false;
        });

        Gate::define('modificar-evaluacion-autor', function (User $user, Evaluacion $evaluacion) {
            if ($user->hasRole([20, 18, 19, 5, 17, 11]) && $evaluacion->modificable == true && $evaluacion->user_id == $user->id) {
                return true;
            }

            return false;
        });

        Gate::define('listar-convocatorias', function (User $user) {
            return $user->hasRole(11) || $user->getAllPermissions()->whereIn('id', [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21])->count() > 0;
        });

        Gate::define('descargar-reportes', function (User $user) {
            return $user->hasRole([20, 18, 19, 5, 17]);
        });
    }

    public function registerSuperAdminPolicy()
    {
        Gate::before(function ($user, $ability) {
            return $user->hasRole(1) ? true : null;
        });
    }
}
