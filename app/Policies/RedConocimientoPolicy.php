<?php

namespace App\Policies;

use App\Models\RedConocimiento;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RedConocimientoPolicy
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
        if ( $user->hasPermissionTo('redes-conocimiento.index') || $user->hasPermissionTo('redes-conocimiento.show') || $user->hasPermissionTo('redes-conocimiento.create') || $user->hasPermissionTo('redes-conocimiento.edit') || $user->hasPermissionTo('redes-conocimiento.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RedConocimiento  $redConocimiento
     * @return mixed
     */
    public function view(User $user, RedConocimiento $redConocimiento)
    {
        if ( $user->hasPermissionTo('redes-conocimiento.show') ) {
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
        if ( $user->hasPermissionTo('redes-conocimiento.create') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RedConocimiento  $redConocimiento
     * @return mixed
     */
    public function update(User $user, RedConocimiento $redConocimiento)
    {
        if ( $user->hasPermissionTo('redes-conocimiento.show') || $user->hasPermissionTo('redes-conocimiento.edit') || $user->hasPermissionTo('redes-conocimiento.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RedConocimiento  $redConocimiento
     * @return mixed
     */
    public function delete(User $user, RedConocimiento $redConocimiento)
    {
        if ( $user->hasPermissionTo('redes-conocimiento.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RedConocimiento  $redConocimiento
     * @return mixed
     */
    public function restore(User $user, RedConocimiento $redConocimiento)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RedConocimiento  $redConocimiento
     * @return mixed
     */
    public function forceDelete(User $user, RedConocimiento $redConocimiento)
    {
        //
    }
}
