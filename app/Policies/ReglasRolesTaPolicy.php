<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\ReglasRolesTa;
use App\Models\User;

class ReglasRolesTaPolicy
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
        if ( $user->hasPermissionTo('reglasRolesTa.index') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ReglasRolesTa  $reglasRolesTa
     * @return mixed
     */
    public function view(User $user, ReglasRolesTa $reglasRolesTa)
    {
        if ( $user->hasPermissionTo('reglasRolesTa.show') ) {
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
        if ( $user->hasPermissionTo('reglasRolesTa.create') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ReglasRolesTa  $reglasRolesTa
     * @return mixed
     */
    public function update(User $user, ReglasRolesTa $reglasRolesTa)
    {
        if ( $user->hasPermissionTo('reglasRolesTa.edit') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ReglasRolesTa  $reglasRolesTa
     * @return mixed
     */
    public function delete(User $user, ReglasRolesTa $reglasRolesTa)
    {
        if ( $user->hasPermissionTo('reglasRolesTa.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ReglasRolesTa  $reglasRolesTa
     * @return mixed
     */
    public function restore(User $user, ReglasRolesTa $reglasRolesTa)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ReglasRolesTa  $reglasRolesTa
     * @return mixed
     */
    public function forceDelete(User $user, ReglasRolesTa $reglasRolesTa)
    {
        //
    }
}
