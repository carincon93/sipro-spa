<?php

namespace App\Policies;

use App\Models\ConvocatoriaRolSennova;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConvocatoriaRolSennovaPolicy
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
        if ( $user->hasPermissionTo('convocatoria-rol-sennova.index') || $user->hasPermissionTo('convocatoria-rol-sennova.show') || $user->hasPermissionTo('convocatoria-rol-sennova.create') || $user->hasPermissionTo('convocatoria-rol-sennova.edit') || $user->hasPermissionTo('convocatoria-rol-sennova.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ConvocatoriaRolSennova  $convocatoriaRolSennova
     * @return mixed
     */
    public function view(User $user, convocatoriaRolSennova $convocatoriaRolSennova)
    {
        if ( $user->hasPermissionTo('convocatoria-rol-sennova.show') ) {
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
        if ( $user->hasPermissionTo('convocatoria-rol-sennova.create') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ConvocatoriaRolSennova  $convocatoriaRolSennova
     * @return mixed
     */
    public function update(User $user, convocatoriaRolSennova $convocatoriaRolSennova)
    {
        if ( $user->hasPermissionTo('convocatoria-rol-sennova.show') || $user->hasPermissionTo('convocatoria-rol-sennova.edit') || $user->hasPermissionTo('convocatoria-rol-sennova.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ConvocatoriaRolSennova  $convocatoriaRolSennova
     * @return mixed
     */
    public function delete(User $user, convocatoriaRolSennova $convocatoriaRolSennova)
    {
        if ( $user->hasPermissionTo('convocatoria-rol-sennova.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ConvocatoriaRolSennova  $convocatoriaRolSennova
     * @return mixed
     */
    public function restore(User $user, convocatoriaRolSennova $convocatoriaRolSennova)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ConvocatoriaRolSennova  $convocatoriaRolSennova
     * @return mixed
     */
    public function forceDelete(User $user, convocatoriaRolSennova $convocatoriaRolSennova)
    {
        //
    }
}
