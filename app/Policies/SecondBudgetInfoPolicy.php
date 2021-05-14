<?php

namespace App\Policies;

use App\Models\SecondBudgetInfo;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SecondBudgetInfoPolicy
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
        if ( $user->hasPermissionTo('second-budget-info.index') || $user->hasPermissionTo('second-budget-info.show') || $user->hasPermissionTo('second-budget-info.create') || $user->hasPermissionTo('second-budget-info.edit') || $user->hasPermissionTo('second-budget-info.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SecondBudgetInfo  $secondBudgetInfo
     * @return mixed
     */
    public function view(User $user, SecondBudgetInfo $secondBudgetInfo)
    {
        if ( $user->hasPermissionTo('second-budget-info.show') ) {
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
        if ( $user->hasPermissionTo('second-budget-info.create') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SecondBudgetInfo  $secondBudgetInfo
     * @return mixed
     */
    public function update(User $user, SecondBudgetInfo $secondBudgetInfo)
    {
        if ( $user->hasPermissionTo('second-budget-info.show') || $user->hasPermissionTo('second-budget-info.edit') || $user->hasPermissionTo('second-budget-info.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SecondBudgetInfo  $secondBudgetInfo
     * @return mixed
     */
    public function delete(User $user, SecondBudgetInfo $secondBudgetInfo)
    {
        if ( $user->hasPermissionTo('second-budget-info.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SecondBudgetInfo  $secondBudgetInfo
     * @return mixed
     */
    public function restore(User $user, SecondBudgetInfo $secondBudgetInfo)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SecondBudgetInfo  $secondBudgetInfo
     * @return mixed
     */
    public function forceDelete(User $user, SecondBudgetInfo $secondBudgetInfo)
    {
        //
    }
}
