<?php

namespace App\Policies;

use App\Models\ProjectSennovaRole;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectSennovaRolePolicy
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
        if ( $user->hasPermissionTo('project-sennova-roles.index') || $user->hasPermissionTo('project-sennova-roles.show') || $user->hasPermissionTo('project-sennova-roles.create') || $user->hasPermissionTo('project-sennova-roles.edit') || $user->hasPermissionTo('project-sennova-roles.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProjectSennovaRole  $projectSennovaRole
     * @return mixed
     */
    public function view(User $user, ProjectSennovaRole $projectSennovaRole)
    {
        if ( $user->hasPermissionTo('project-sennova-roles.show') ) {
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
        if ( $user->hasPermissionTo('project-sennova-roles.create') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProjectSennovaRole  $projectSennovaRole
     * @return mixed
     */
    public function update(User $user, ProjectSennovaRole $projectSennovaRole)
    {
        if ( $user->hasPermissionTo('project-sennova-roles.show') || $user->hasPermissionTo('project-sennova-roles.edit') || $user->hasPermissionTo('project-sennova-roles.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProjectSennovaRole  $projectSennovaRole
     * @return mixed
     */
    public function delete(User $user, ProjectSennovaRole $projectSennovaRole)
    {
        if ( $user->hasPermissionTo('project-sennova-roles.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProjectSennovaRole  $projectSennovaRole
     * @return mixed
     */
    public function restore(User $user, ProjectSennovaRole $projectSennovaRole)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProjectSennovaRole  $projectSennovaRole
     * @return mixed
     */
    public function forceDelete(User $user, ProjectSennovaRole $projectSennovaRole)
    {
        //
    }
}
