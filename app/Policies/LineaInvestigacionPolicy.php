<?php

namespace App\Policies;

use App\Models\LineaInvestigacion;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

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
        if ( $user->hasPermissionTo('lineas-investigacion.index') || $user->hasPermissionTo('lineas-investigacion.show') || $user->hasPermissionTo('lineas-investigacion.create') || $user->hasPermissionTo('lineas-investigacion.edit') || $user->hasPermissionTo('lineas-investigacion.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LineaInvestigacion  $lineaInvestigacion
     * @return mixed
     */
    public function view(User $user, LineaInvestigacion $lineaInvestigacion)
    {
        if ( $user->hasPermissionTo('lineas-investigacion.show') ) {
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
        if ( $user->hasPermissionTo('lineas-investigacion.create') ) {
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
        if ( $user->hasPermissionTo('lineas-investigacion.show') || $user->hasPermissionTo('lineas-investigacion.edit') || $user->hasPermissionTo('lineas-investigacion.destroy') ) {
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
        if ( $user->hasPermissionTo('lineas-investigacion.destroy') ) {
            return true;
        }

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
