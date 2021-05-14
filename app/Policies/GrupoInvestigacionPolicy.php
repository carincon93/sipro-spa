<?php

namespace App\Policies;

use App\Models\GrupoInvestigacion;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GrupoInvestigacionPolicy
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
        if ( $user->hasPermissionTo('grupos-investigacion.index') || $user->hasPermissionTo('grupos-investigacion.show') || $user->hasPermissionTo('grupos-investigacion.create') || $user->hasPermissionTo('grupos-investigacion.edit') || $user->hasPermissionTo('grupos-investigacion.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\GrupoInvestigacion  $grupoInvestigacion
     * @return mixed
     */
    public function view(User $user, GrupoInvestigacion $grupoInvestigacion)
    {
        if ( $user->hasPermissionTo('grupos-investigacion.show') ) {
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
        if ( $user->hasPermissionTo('grupos-investigacion.create') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\GrupoInvestigacion  $grupoInvestigacion
     * @return mixed
     */
    public function update(User $user, GrupoInvestigacion $grupoInvestigacion)
    {
        if ( $user->hasPermissionTo('grupos-investigacion.show') || $user->hasPermissionTo('grupos-investigacion.edit') || $user->hasPermissionTo('grupos-investigacion.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\GrupoInvestigacion  $grupoInvestigacion
     * @return mixed
     */
    public function delete(User $user, GrupoInvestigacion $grupoInvestigacion)
    {
        if ( $user->hasPermissionTo('grupos-investigacion.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\GrupoInvestigacion  $grupoInvestigacion
     * @return mixed
     */
    public function restore(User $user, GrupoInvestigacion $grupoInvestigacion)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\GrupoInvestigacion  $grupoInvestigacion
     * @return mixed
     */
    public function forceDelete(User $user, GrupoInvestigacion $grupoInvestigacion)
    {
        //
    }
}
