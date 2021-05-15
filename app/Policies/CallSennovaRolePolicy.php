<?php

namespace App\Policies;

use App\Models\ConvocatoriaRolSennova;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CallRolSennovaPolicy
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
        if ( $user->hasPermissionTo('call-sennova-roles.index') || $user->hasPermissionTo('call-sennova-roles.show') || $user->hasPermissionTo('call-sennova-roles.create') || $user->hasPermissionTo('call-sennova-roles.edit') || $user->hasPermissionTo('call-sennova-roles.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ConvocatoriaRolSennova  $callRolSennova
     * @return mixed
     */
    public function view(User $user, CallRolSennova $callRolSennova)
    {
        if ( $user->hasPermissionTo('call-sennova-roles.show') ) {
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
        if ( $user->hasPermissionTo('call-sennova-roles.create') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ConvocatoriaRolSennova  $callRolSennova
     * @return mixed
     */
    public function update(User $user, CallRolSennova $callRolSennova)
    {
        if ( $user->hasPermissionTo('call-sennova-roles.show') || $user->hasPermissionTo('call-sennova-roles.edit') || $user->hasPermissionTo('call-sennova-roles.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ConvocatoriaRolSennova  $callRolSennova
     * @return mixed
     */
    public function delete(User $user, CallRolSennova $callRolSennova)
    {
        if ( $user->hasPermissionTo('call-sennova-roles.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ConvocatoriaRolSennova  $callRolSennova
     * @return mixed
     */
    public function restore(User $user, CallRolSennova $callRolSennova)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ConvocatoriaRolSennova  $callRolSennova
     * @return mixed
     */
    public function forceDelete(User $user, CallRolSennova $callRolSennova)
    {
        //
    }
}
