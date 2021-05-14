<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any users.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        if ( $user->hasPermissionTo('users.index') || $user->hasPermissionTo('users.show') || $user->hasPermissionTo('users.create') || $user->hasPermissionTo('users.edit') || $user->hasPermissionTo('users.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the users.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function view(User $user)
    {
        if ( $user->hasPermissionTo('users.show') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create users.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ( $user->hasPermissionTo('users.create') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the users.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function update(User $user)
    {
        if ( $user->hasPermissionTo('users.show') || $user->hasPermissionTo('users.edit') || $user->hasPermissionTo('users.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the users.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function delete(User $user)
    {
        if ( $user->hasPermissionTo('users.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the users.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function restore(User $user)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the users.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function forceDelete(User $user)
    {
        //
    }
}
