<?php

namespace App\Policies;

use App\Models\Call;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CallPolicy
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
        if ( $user->hasPermissionTo('calls.index') || $user->hasPermissionTo('calls.show') || $user->hasPermissionTo('calls.create') || $user->hasPermissionTo('calls.edit') || $user->hasPermissionTo('calls.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Call  $call
     * @return mixed
     */
    public function view(User $user, Call $call)
    {
        if ( $user->hasPermissionTo('calls.show') ) {
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
        if ( $user->hasPermissionTo('calls.create') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Call  $call
     * @return mixed
     */
    public function update(User $user, Call $call)
    {
        if ( $user->hasPermissionTo('calls.show') || $user->hasPermissionTo('calls.edit') || $user->hasPermissionTo('calls.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Call  $call
     * @return mixed
     */
    public function delete(User $user, Call $call)
    {
        if ( $user->hasPermissionTo('calls.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Call  $call
     * @return mixed
     */
    public function restore(User $user, Call $call)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Call  $call
     * @return mixed
     */
    public function forceDelete(User $user, Call $call)
    {
        //
    }
}
