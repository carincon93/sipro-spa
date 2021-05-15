<?php

namespace App\Policies;

use App\Models\EntidadAliada;
use App\Models\User;
use App\Models\IDi;
use Illuminate\Auth\Access\HandlesAuthorization;

class EntidadAliadaPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user, Rdi $rdi)
    {
        if ($rdi->project->projectType->programmaticLine->code == 23) {
            return false;
        }

        if ( $user->hasPermissionTo('partner-organizations.index') || $user->hasPermissionTo('partner-organizations.show') || $user->hasPermissionTo('partner-organizations.create') || $user->hasPermissionTo('partner-organizations.edit') || $user->hasPermissionTo('partner-organizations.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EntidadAliada  $EntidadAliada
     * @return mixed
     */
    public function view(User $user, EntidadAliada $EntidadAliada)
    {
        if ( $user->hasPermissionTo('partner-organizations.show') ) {
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
        if ( $user->hasPermissionTo('partner-organizations.create') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EntidadAliada  $EntidadAliada
     * @return mixed
     */
    public function update(User $user, EntidadAliada $EntidadAliada)
    {
        if ( $user->hasPermissionTo('partner-organizations.show') || $user->hasPermissionTo('partner-organizations.edit') || $user->hasPermissionTo('partner-organizations.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EntidadAliada  $EntidadAliada
     * @return mixed
     */
    public function delete(User $user, EntidadAliada $EntidadAliada)
    {
        if ( $user->hasPermissionTo('partner-organizations.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EntidadAliada  $EntidadAliada
     * @return mixed
     */
    public function restore(User $user, EntidadAliada $EntidadAliada)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EntidadAliada  $EntidadAliada
     * @return mixed
     */
    public function forceDelete(User $user, EntidadAliada $EntidadAliada)
    {
        //
    }
}
