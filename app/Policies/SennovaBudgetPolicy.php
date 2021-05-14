<?php

namespace App\Policies;

use App\Models\SennovaBudget;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SennovaBudgetPolicy
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
        if ( $user->hasPermissionTo('sennova-budgets.index') || $user->hasPermissionTo('sennova-budgets.show') || $user->hasPermissionTo('sennova-budgets.create') || $user->hasPermissionTo('sennova-budgets.edit') || $user->hasPermissionTo('sennova-budgets.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SennovaBudget  $sennovaBudget
     * @return mixed
     */
    public function view(User $user, SennovaBudget $sennovaBudget)
    {
        if ( $user->hasPermissionTo('sennova-budgets.show') ) {
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
        if ( $user->hasPermissionTo('sennova-budgets.create') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SennovaBudget  $sennovaBudget
     * @return mixed
     */
    public function update(User $user, SennovaBudget $sennovaBudget)
    {
        if ( $user->hasPermissionTo('sennova-budgets.show') || $user->hasPermissionTo('sennova-budgets.edit') || $user->hasPermissionTo('sennova-budgets.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SennovaBudget  $sennovaBudget
     * @return mixed
     */
    public function delete(User $user, SennovaBudget $sennovaBudget)
    {
        if ( $user->hasPermissionTo('sennova-budgets.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SennovaBudget  $sennovaBudget
     * @return mixed
     */
    public function restore(User $user, SennovaBudget $sennovaBudget)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SennovaBudget  $sennovaBudget
     * @return mixed
     */
    public function forceDelete(User $user, SennovaBudget $sennovaBudget)
    {
        //
    }
}
