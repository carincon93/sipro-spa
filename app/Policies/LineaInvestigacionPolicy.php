<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\LineaInvestigacion;
use App\Models\User;

class LineaInvestigacionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        if ($user->hasRole([4, 21])) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function view(User $user)
    {
        if ($user->hasRole([4, 21])) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->hasRole([4, 21])) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LineaInvestigacion  $lineaInvestigacion
     * @return mixed
     */
    public function update(User $user, LineaInvestigacion $lineaInvestigacion)
    {
        if ($user->hasRole(4) && $user->dinamizadorCentroFormacion->id == $lineaInvestigacion->grupoInvestigacion->centroFormacion->id || $user->hasRole(21) && $user->centroFormacion->id == $lineaInvestigacion->grupoInvestigacion->centroFormacion->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LineaInvestigacion  $lineaInvestigacion
     * @return mixed
     */
    public function delete(User $user, LineaInvestigacion $lineaInvestigacion)
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LineaInvestigacion  $lineaInvestigacion
     * @return mixed
     */
    public function restore(User $user, LineaInvestigacion $lineaInvestigacion)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LineaInvestigacion  $lineaInvestigacion
     * @return mixed
     */
    public function forceDelete(User $user, LineaInvestigacion $lineaInvestigacion)
    {
        //
    }
}
