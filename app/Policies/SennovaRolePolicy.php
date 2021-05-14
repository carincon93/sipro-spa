<?php

namespace App\Policies;

use App\Models\SennovaRole;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SennovaRolePolicy
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
        if ( $user->hasPermissionTo('sennova-roles.index') || $user->hasPermissionTo('sennova-roles.show') || $user->hasPermissionTo('sennova-roles.create') || $user->hasPermissionTo('sennova-roles.edit') || $user->hasPermissionTo('sennova-roles.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SennovaRole  $sennovaRole
     * @return mixed
     */
    public function view(User $user, SennovaRole $sennovaRole)
    {
        if ( $user->hasPermissionTo('sennova-roles.show') ) {
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
        if ( $user->hasPermissionTo('sennova-roles.create') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SennovaRole  $sennovaRole
     * @return mixed
     */
    public function update(User $user, SennovaRole $sennovaRole)
    {
        if ( $user->hasPermissionTo('sennova-roles.show') || $user->hasPermissionTo('sennova-roles.edit') || $user->hasPermissionTo('sennova-roles.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SennovaRole  $sennovaRole
     * @return mixed
     */
    public function delete(User $user, SennovaRole $sennovaRole)
    {
        if ( $user->hasPermissionTo('sennova-roles.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SennovaRole  $sennovaRole
     * @return mixed
     */
    public function restore(User $user, SennovaRole $sennovaRole)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SennovaRole  $sennovaRole
     * @return mixed
     */
    public function forceDelete(User $user, SennovaRole $sennovaRole)
    {
        //
    }
}
