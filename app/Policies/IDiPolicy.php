<?php

namespace App\Policies;

use App\Models\IDi;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class IDiPolicy
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
        if ( $user->hasPermissionTo('idi.index') || $user->hasPermissionTo('idi.show') || $user->hasPermissionTo('idi.create') || $user->hasPermissionTo('idi.edit') || $user->hasPermissionTo('idi.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\IDi  $idi
     * @return mixed
     */
    public function view(User $user, IDi $idi)
    {
        if ( $user->hasPermissionTo('idi.show') ) {
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
        if ( $user->hasPermissionTo('idi.create') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\IDi  $idi
     * @return mixed
     */
    public function update(User $user, IDi $idi)
    {
        if ( $user->hasPermissionTo('idi.show') || $user->hasPermissionTo('idi.edit') || $user->hasPermissionTo('idi.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\IDi  $idi
     * @return mixed
     */
    public function delete(User $user, IDi $idi)
    {
        if ( $user->hasPermissionTo('idi.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\IDi  $idi
     * @return mixed
     */
    public function restore(User $user, IDi $idi)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\IDi  $idi
     * @return mixed
     */
    public function forceDelete(User $user, IDi $idi)
    {
        //
    }
}
