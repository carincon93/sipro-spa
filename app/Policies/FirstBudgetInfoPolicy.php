<?php

namespace App\Policies;

use App\Models\FirstBudgetInfo;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FirstBudgetInfoPolicy
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
        if ( $user->hasPermissionTo('first-budget-info.index') || $user->hasPermissionTo('first-budget-info.show') || $user->hasPermissionTo('first-budget-info.create') || $user->hasPermissionTo('first-budget-info.edit') || $user->hasPermissionTo('first-budget-info.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FirstBudgetInfo  $firstBudgetInfo
     * @return mixed
     */
    public function view(User $user, FirstBudgetInfo $firstBudgetInfo)
    {
        if ( $user->hasPermissionTo('first-budget-info.show') ) {
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
        if ( $user->hasPermissionTo('first-budget-info.create') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FirstBudgetInfo  $firstBudgetInfo
     * @return mixed
     */
    public function update(User $user, FirstBudgetInfo $firstBudgetInfo)
    {
        if ( $user->hasPermissionTo('first-budget-info.show') || $user->hasPermissionTo('first-budget-info.edit') || $user->hasPermissionTo('first-budget-info.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FirstBudgetInfo  $firstBudgetInfo
     * @return mixed
     */
    public function delete(User $user, FirstBudgetInfo $firstBudgetInfo)
    {
        if ( $user->hasPermissionTo('first-budget-info.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FirstBudgetInfo  $firstBudgetInfo
     * @return mixed
     */
    public function restore(User $user, FirstBudgetInfo $firstBudgetInfo)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FirstBudgetInfo  $firstBudgetInfo
     * @return mixed
     */
    public function forceDelete(User $user, FirstBudgetInfo $firstBudgetInfo)
    {
        //
    }
}
