<?php

namespace App\Policies;

use App\Models\MincienciasSubtypology;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MincienciasSubtypologyPolicy
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
        if ( $user->hasPermissionTo('minciencias-subtypologies.index') || $user->hasPermissionTo('minciencias-subtypologies.show') || $user->hasPermissionTo('minciencias-subtypologies.create') || $user->hasPermissionTo('minciencias-subtypologies.edit') || $user->hasPermissionTo('minciencias-subtypologies.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MincienciasSubtypology  $mincienciasSubtypology
     * @return mixed
     */
    public function view(User $user, MincienciasSubtypology $mincienciasSubtypology)
    {
        if ( $user->hasPermissionTo('minciencias-subtypologies.show') ) {
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
        if ( $user->hasPermissionTo('minciencias-subtypologies.create') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MincienciasSubtypology  $mincienciasSubtypology
     * @return mixed
     */
    public function update(User $user, MincienciasSubtypology $mincienciasSubtypology)
    {
        if ( $user->hasPermissionTo('minciencias-subtypologies.show') || $user->hasPermissionTo('minciencias-subtypologies.edit') || $user->hasPermissionTo('minciencias-subtypologies.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MincienciasSubtypology  $mincienciasSubtypology
     * @return mixed
     */
    public function delete(User $user, MincienciasSubtypology $mincienciasSubtypology)
    {
        if ( $user->hasPermissionTo('minciencias-subtypologies.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MincienciasSubtypology  $mincienciasSubtypology
     * @return mixed
     */
    public function restore(User $user, MincienciasSubtypology $mincienciasSubtypology)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MincienciasSubtypology  $mincienciasSubtypology
     * @return mixed
     */
    public function forceDelete(User $user, MincienciasSubtypology $mincienciasSubtypology)
    {
        //
    }
}
