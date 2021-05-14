<?php

namespace App\Policies;

use App\Models\Output;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class OutputPolicy
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
        if ( $user->hasPermissionTo('outputs.index') || $user->hasPermissionTo('outputs.show') || $user->hasPermissionTo('outputs.create') || $user->hasPermissionTo('outputs.edit') || $user->hasPermissionTo('outputs.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Output  $output
     * @return mixed
     */
    public function view(User $user, Output $output)
    {
        if ( $user->hasPermissionTo('outputs.show') ) {
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
        if ( $user->hasPermissionTo('outputs.create') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Output  $output
     * @return mixed
     */
    public function update(User $user, Output $output)
    {
        if ( $user->hasPermissionTo('outputs.show') || $user->hasPermissionTo('outputs.edit') || $user->hasPermissionTo('outputs.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Output  $output
     * @return mixed
     */
    public function delete(User $user, Output $output)
    {
        if ( $user->hasPermissionTo('outputs.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Output  $output
     * @return mixed
     */
    public function restore(User $user, Output $output)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Output  $output
     * @return mixed
     */
    public function forceDelete(User $user, Output $output)
    {
        //
    }
}
