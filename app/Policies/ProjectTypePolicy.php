<?php

namespace App\Policies;

use App\Models\ProjectType;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectTypePolicy
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
        if ( $user->hasPermissionTo('project-types.index') || $user->hasPermissionTo('project-types.show') || $user->hasPermissionTo('project-types.create') || $user->hasPermissionTo('project-types.edit') || $user->hasPermissionTo('project-types.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProjectType  $projectType
     * @return mixed
     */
    public function view(User $user, ProjectType $projectType)
    {
        if ( $user->hasPermissionTo('project-types.show') ) {
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
        if ( $user->hasPermissionTo('project-types.create') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProjectType  $projectType
     * @return mixed
     */
    public function update(User $user, ProjectType $projectType)
    {
        if ( $user->hasPermissionTo('project-types.show') || $user->hasPermissionTo('project-types.edit') || $user->hasPermissionTo('project-types.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProjectType  $projectType
     * @return mixed
     */
    public function delete(User $user, ProjectType $projectType)
    {
        if ( $user->hasPermissionTo('project-types.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProjectType  $projectType
     * @return mixed
     */
    public function restore(User $user, ProjectType $projectType)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProjectType  $projectType
     * @return mixed
     */
    public function forceDelete(User $user, ProjectType $projectType)
    {
        //
    }
}
