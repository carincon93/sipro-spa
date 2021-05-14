<?php

namespace App\Policies;

use App\Models\ProjectBudgetBatch;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectBudgetBatchPolicy
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
        if ( $user->hasPermissionTo('project-budget-batches.index') || $user->hasPermissionTo('project-budget-batches.show') || $user->hasPermissionTo('project-budget-batches.create') || $user->hasPermissionTo('project-budget-batches.edit') || $user->hasPermissionTo('project-budget-batches.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProjectBudgetBatch  $projectBudgetBatch
     * @return mixed
     */
    public function view(User $user, ProjectBudgetBatch $projectBudgetBatch)
    {
        if ( $user->hasPermissionTo('project-budget-batches.show') ) {
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
        if ( $user->hasPermissionTo('project-budget-batches.create') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProjectBudgetBatch  $projectBudgetBatch
     * @return mixed
     */
    public function update(User $user, ProjectBudgetBatch $projectBudgetBatch)
    {
        if ( $user->hasPermissionTo('project-budget-batches.show') || $user->hasPermissionTo('project-budget-batches.edit') || $user->hasPermissionTo('project-budget-batches.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProjectBudgetBatch  $projectBudgetBatch
     * @return mixed
     */
    public function delete(User $user, ProjectBudgetBatch $projectBudgetBatch)
    {
        if ( $user->hasPermissionTo('project-budget-batches.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProjectBudgetBatch  $projectBudgetBatch
     * @return mixed
     */
    public function restore(User $user, ProjectBudgetBatch $projectBudgetBatch)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProjectBudgetBatch  $projectBudgetBatch
     * @return mixed
     */
    public function forceDelete(User $user, ProjectBudgetBatch $projectBudgetBatch)
    {
        //
    }
}
