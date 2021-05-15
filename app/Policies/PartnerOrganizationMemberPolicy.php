<?php

namespace App\Policies;

use App\Models\EntidadAliadaMember;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EntidadAliadaMemberPolicy
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
        if ( $user->hasPermissionTo('partner-organization-members.index') || $user->hasPermissionTo('partner-organization-members.show') || $user->hasPermissionTo('partner-organization-members.create') || $user->hasPermissionTo('partner-organization-members.edit') || $user->hasPermissionTo('partner-organization-members.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EntidadAliadaMember  $EntidadAliadaMember
     * @return mixed
     */
    public function view(User $user, EntidadAliadaMember $EntidadAliadaMember)
    {
        if ( $user->hasPermissionTo('partner-organization-members.show') ) {
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
        if ( $user->hasPermissionTo('partner-organization-members.create') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EntidadAliadaMember  $EntidadAliadaMember
     * @return mixed
     */
    public function update(User $user, EntidadAliadaMember $EntidadAliadaMember)
    {
        if ( $user->hasPermissionTo('partner-organization-members.show') || $user->hasPermissionTo('partner-organization-members.edit') || $user->hasPermissionTo('partner-organization-members.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EntidadAliadaMember  $EntidadAliadaMember
     * @return mixed
     */
    public function delete(User $user, EntidadAliadaMember $EntidadAliadaMember)
    {
        if ( $user->hasPermissionTo('partner-organization-members.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EntidadAliadaMember  $EntidadAliadaMember
     * @return mixed
     */
    public function restore(User $user, EntidadAliadaMember $EntidadAliadaMember)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\EntidadAliadaMember  $EntidadAliadaMember
     * @return mixed
     */
    public function forceDelete(User $user, EntidadAliadaMember $EntidadAliadaMember)
    {
        //
    }
}
