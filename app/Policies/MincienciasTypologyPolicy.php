<?php

namespace App\Policies;

use App\Models\MincienciasTypology;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MincienciasTypologyPolicy
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
        if ( $user->hasPermissionTo('minciencias-typologies.index') || $user->hasPermissionTo('minciencias-typologies.show') || $user->hasPermissionTo('minciencias-typologies.create') || $user->hasPermissionTo('minciencias-typologies.edit') || $user->hasPermissionTo('minciencias-typologies.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MincienciasTypology  $mincienciasTypology
     * @return mixed
     */
    public function view(User $user, MincienciasTypology $mincienciasTypology)
    {
        if ( $user->hasPermissionTo('minciencias-typologies.show') ) {
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
        if ( $user->hasPermissionTo('minciencias-typologies.create') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MincienciasTypology  $mincienciasTypology
     * @return mixed
     */
    public function update(User $user, MincienciasTypology $mincienciasTypology)
    {
        if ( $user->hasPermissionTo('minciencias-typologies.show') || $user->hasPermissionTo('minciencias-typologies.edit') || $user->hasPermissionTo('minciencias-typologies.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MincienciasTypology  $mincienciasTypology
     * @return mixed
     */
    public function delete(User $user, MincienciasTypology $mincienciasTypology)
    {
        if ( $user->hasPermissionTo('minciencias-typologies.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MincienciasTypology  $mincienciasTypology
     * @return mixed
     */
    public function restore(User $user, MincienciasTypology $mincienciasTypology)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MincienciasTypology  $mincienciasTypology
     * @return mixed
     */
    public function forceDelete(User $user, MincienciasTypology $mincienciasTypology)
    {
        //
    }
}
