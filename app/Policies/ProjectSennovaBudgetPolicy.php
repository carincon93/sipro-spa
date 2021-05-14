<?php

namespace App\Policies;

use App\Models\ProjectSennovaBudget;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectSennovaBudgetPolicy
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
        if ( $user->hasPermissionTo('project-sennova-budgets.index') || $user->hasPermissionTo('project-sennova-budgets.show') || $user->hasPermissionTo('project-sennova-budgets.create') || $user->hasPermissionTo('project-sennova-budgets.edit') || $user->hasPermissionTo('project-sennova-budgets.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProjectSennovaBudget  $projectSennovaBudget
     * @return mixed
     */
    public function view(User $user, ProjectSennovaBudget $projectSennovaBudget)
    {
        if ( $user->hasPermissionTo('project-sennova-budgets.show') ) {
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
        if ( $user->hasPermissionTo('project-sennova-budgets.create') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProjectSennovaBudget  $projectSennovaBudget
     * @return mixed
     */
    public function update(User $user, ProjectSennovaBudget $projectSennovaBudget)
    {
        if ( $user->hasPermissionTo('project-sennova-budgets.show') || $user->hasPermissionTo('project-sennova-budgets.edit') || $user->hasPermissionTo('project-sennova-budgets.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProjectSennovaBudget  $projectSennovaBudget
     * @return mixed
     */
    public function delete(User $user, ProjectSennovaBudget $projectSennovaBudget)
    {
        if ( $user->hasPermissionTo('project-sennova-budgets.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProjectSennovaBudget  $projectSennovaBudget
     * @return mixed
     */
    public function restore(User $user, ProjectSennovaBudget $projectSennovaBudget)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProjectSennovaBudget  $projectSennovaBudget
     * @return mixed
     */
    public function forceDelete(User $user, ProjectSennovaBudget $projectSennovaBudget)
    {
        //
    }
}
