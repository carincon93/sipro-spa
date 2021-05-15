<?php

namespace App\Policies;

use App\Models\MesaSectorial;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MesaSectorialPolicy
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
        if ( $user->hasPermissionTo('mesas-sectoriales.index') || $user->hasPermissionTo('mesas-sectoriales.show') || $user->hasPermissionTo('mesas-sectoriales.create') || $user->hasPermissionTo('mesas-sectoriales.edit') || $user->hasPermissionTo('mesas-sectoriales.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MesaSectorial  $mesaSectorial
     * @return mixed
     */
    public function view(User $user, MesaSectorial $mesaSectorial)
    {
        if ( $user->hasPermissionTo('mesas-sectoriales.show') ) {
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
        if ( $user->hasPermissionTo('mesas-sectoriales.create') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MesaSectorial  $mesaSectorial
     * @return mixed
     */
    public function update(User $user, MesaSectorial $mesaSectorial)
    {
        if ( $user->hasPermissionTo('mesas-sectoriales.show') || $user->hasPermissionTo('mesas-sectoriales.edit') || $user->hasPermissionTo('mesas-sectoriales.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MesaSectorial  $mesaSectorial
     * @return mixed
     */
    public function delete(User $user, MesaSectorial $mesaSectorial)
    {
        if ( $user->hasPermissionTo('mesas-sectoriales.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MesaSectorial  $mesaSectorial
     * @return mixed
     */
    public function restore(User $user, MesaSectorial $mesaSectorial)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\MesaSectorial  $mesaSectorial
     * @return mixed
     */
    public function forceDelete(User $user, MesaSectorial $mesaSectorial)
    {
        //
    }
}
