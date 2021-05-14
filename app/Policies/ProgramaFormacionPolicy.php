<?php

namespace App\Policies;

use App\Models\ProgramaFormacion;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProgramaFormacionPolicy
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
        if ( $user->hasPermissionTo('programas-formacion.index') || $user->hasPermissionTo('programas-formacion.show') || $user->hasPermissionTo('programas-formacion.create') || $user->hasPermissionTo('programas-formacion.edit') || $user->hasPermissionTo('programas-formacion.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProgramaFormacion  $programaFormacion
     * @return mixed
     */
    public function view(User $user, ProgramaFormacion $programaFormacion)
    {
        if ( $user->hasPermissionTo('programas-formacion.show') ) {
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
        if ( $user->hasPermissionTo('programas-formacion.create') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProgramaFormacion  $programaFormacion
     * @return mixed
     */
    public function update(User $user, ProgramaFormacion $programaFormacion)
    {
        if ( $user->hasPermissionTo('programas-formacion.show') || $user->hasPermissionTo('programas-formacion.edit') || $user->hasPermissionTo('programas-formacion.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProgramaFormacion  $programaFormacion
     * @return mixed
     */
    public function delete(User $user, ProgramaFormacion $programaFormacion)
    {
        if ( $user->hasPermissionTo('programas-formacion.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProgramaFormacion  $programaFormacion
     * @return mixed
     */
    public function restore(User $user, ProgramaFormacion $programaFormacion)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProgramaFormacion  $programaFormacion
     * @return mixed
     */
    public function forceDelete(User $user, ProgramaFormacion $programaFormacion)
    {
        //
    }
}
