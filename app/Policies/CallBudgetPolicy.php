<?php

namespace App\Policies;

use App\Models\CallBudget;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CallBudgetPolicy
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
        if ( $user->hasPermissionTo('call-budgets.index') || $user->hasPermissionTo('call-budgets.show') || $user->hasPermissionTo('call-budgets.create') || $user->hasPermissionTo('call-budgets.edit') || $user->hasPermissionTo('call-budgets.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CallBudget  $callBudget
     * @return mixed
     */
    public function view(User $user, CallBudget $callBudget)
    {
        if ( $user->hasPermissionTo('call-budgets.show') ) {
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
        if ( $user->hasPermissionTo('call-budgets.create') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CallBudget  $callBudget
     * @return mixed
     */
    public function update(User $user, CallBudget $callBudget)
    {
        if ( $user->hasPermissionTo('call-budgets.show') || $user->hasPermissionTo('call-budgets.edit') || $user->hasPermissionTo('call-budgets.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CallBudget  $callBudget
     * @return mixed
     */
    public function delete(User $user, CallBudget $callBudget)
    {
        if ( $user->hasPermissionTo('call-budgets.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CallBudget  $callBudget
     * @return mixed
     */
    public function restore(User $user, CallBudget $callBudget)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\CallBudget  $callBudget
     * @return mixed
     */
    public function forceDelete(User $user, CallBudget $callBudget)
    {
        //
    }
}
