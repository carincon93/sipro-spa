<?php

namespace App\Policies;

use App\Models\BudgetUsage;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BudgetUsagePolicy
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
        if ( $user->hasPermissionTo('budget-usages.index') || $user->hasPermissionTo('budget-usages.show') || $user->hasPermissionTo('budget-usages.create') || $user->hasPermissionTo('budget-usages.edit') || $user->hasPermissionTo('budget-usages.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BudgetUsage  $budgetUsage
     * @return mixed
     */
    public function view(User $user, BudgetUsage $budgetUsage)
    {
        if ( $user->hasPermissionTo('budget-usages.show') ) {
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
        if ( $user->hasPermissionTo('budget-usages.create') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BudgetUsage  $budgetUsage
     * @return mixed
     */
    public function update(User $user, BudgetUsage $budgetUsage)
    {
        if (  $user->hasPermissionTo('budget-usages.show') || $user->hasPermissionTo('budget-usages.edit') || $user->hasPermissionTo('budget-usages.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BudgetUsage  $budgetUsage
     * @return mixed
     */
    public function delete(User $user, BudgetUsage $budgetUsage)
    {
        if ( $user->hasPermissionTo('budget-usages.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BudgetUsage  $budgetUsage
     * @return mixed
     */
    public function restore(User $user, BudgetUsage $budgetUsage)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\BudgetUsage  $budgetUsage
     * @return mixed
     */
    public function forceDelete(User $user, BudgetUsage $budgetUsage)
    {
        //
    }
}
