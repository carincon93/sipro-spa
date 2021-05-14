<?php

namespace App\Policies;

use App\Models\SectorProductivo;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SectorProductivoPolicy
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
        if ( $user->hasPermissionTo('sectores-productivos.index') || $user->hasPermissionTo('sectores-productivos.show') || $user->hasPermissionTo('sectores-productivos.create') || $user->hasPermissionTo('sectores-productivos.edit') || $user->hasPermissionTo('sectores-productivos.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SectorProductivo  $sectorProductivo
     * @return mixed
     */
    public function view(User $user, SectorProductivo $sectorProductivo)
    {
        if ( $user->hasPermissionTo('sectores-productivos.show') ) {
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
        if ( $user->hasPermissionTo('sectores-productivos.create') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SectorProductivo  $sectorProductivo
     * @return mixed
     */
    public function update(User $user, SectorProductivo $sectorProductivo)
    {
        if ( $user->hasPermissionTo('sectores-productivos.show') || $user->hasPermissionTo('sectores-productivos.edit') || $user->hasPermissionTo('sectores-productivos.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SectorProductivo  $sectorProductivo
     * @return mixed
     */
    public function delete(User $user, SectorProductivo $sectorProductivo)
    {
        if ( $user->hasPermissionTo('sectores-productivos.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SectorProductivo  $sectorProductivo
     * @return mixed
     */
    public function restore(User $user, SectorProductivo $sectorProductivo)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SectorProductivo  $sectorProductivo
     * @return mixed
     */
    public function forceDelete(User $user, SectorProductivo $sectorProductivo)
    {
        //
    }
}
