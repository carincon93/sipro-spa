<?php

namespace App\Policies;

use App\Models\ThirdBudgetInfo;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ThirdBudgetInfoPolicy
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
        if ( $user->hasPermissionTo('third-budget-info.index') || $user->hasPermissionTo('third-budget-info.show')|| $user->hasPermissionTo('third-budget-info.create') || $user->hasPermissionTo('third-budget-info.edit') || $user->hasPermissionTo('third-budget-info.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ThirdBudgetInfo  $thirdBudgetInfo
     * @return mixed
     */
    public function view(User $user, ThirdBudgetInfo $thirdBudgetInfo)
    {
        if ( $user->hasPermissionTo('third-budget-info.show') ) {
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
        if ( $user->hasPermissionTo('third-budget-info.create') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ThirdBudgetInfo  $thirdBudgetInfo
     * @return mixed
     */
    public function update(User $user, ThirdBudgetInfo $thirdBudgetInfo)
    {
        if ( $user->hasPermissionTo('third-budget-info.show') || $user->hasPermissionTo('third-budget-info.edit') || $user->hasPermissionTo('third-budget-info.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ThirdBudgetInfo  $thirdBudgetInfo
     * @return mixed
     */
    public function delete(User $user, ThirdBudgetInfo $thirdBudgetInfo)
    {
        if ( $user->hasPermissionTo('third-budget-info.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ThirdBudgetInfo  $thirdBudgetInfo
     * @return mixed
     */
    public function restore(User $user, ThirdBudgetInfo $thirdBudgetInfo)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ThirdBudgetInfo  $thirdBudgetInfo
     * @return mixed
     */
    public function forceDelete(User $user, ThirdBudgetInfo $thirdBudgetInfo)
    {
        //
    }
}
