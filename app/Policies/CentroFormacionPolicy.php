<?php

namespace App\Policies;

use App\Models\CentroFormacion;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CentroFormacionPolicy
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
        if ( $user->hasPermissionTo('centros-formacion.index') || $user->hasPermissionTo('centros-formacion.show') || $user->hasPermissionTo('centros-formacion.create') || $user->hasPermissionTo('centros-formacion.edit') || $user->hasPermissionTo('centros-formacion.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CentroFormacion  $centroFormacion
     * @return mixed
     */
    public function view(User $user, CentroFormacion $centroFormacion)
    {
        if ( $user->hasPermissionTo('centros-formacion.show') ) {
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
        if ( $user->hasPermissionTo('centros-formacion.create') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CentroFormacion  $centroFormacion
     * @return mixed
     */
    public function update(User $user, CentroFormacion $centroFormacion)
    {
        if ( $user->hasPermissionTo('centros-formacion.show') || $user->hasPermissionTo('centros-formacion.edit') || $user->hasPermissionTo('centros-formacion.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CentroFormacion  $centroFormacion
     * @return mixed
     */
    public function delete(User $user, CentroFormacion $centroFormacion)
    {
        if ( $user->hasPermissionTo('centros-formacion.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CentroFormacion  $centroFormacion
     * @return mixed
     */
    public function restore(User $user, CentroFormacion $centroFormacion)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CentroFormacion  $centroFormacion
     * @return mixed
     */
    public function forceDelete(User $user, CentroFormacion $centroFormacion)
    {
        //
    }
}
