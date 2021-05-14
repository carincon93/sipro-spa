<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Project;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function showProblemTree(User $user, Project $project)
    {
        if ( $user->hasPermissionTo('rdi.show') || $user->hasPermissionTo('rdi.edit') || $user->hasPermissionTo('rdi.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return mixed
     */
    public function showObjectivesTree(User $user, Project $project)
    {
        if ( $user->hasPermissionTo('rdi.show') || $user->hasPermissionTo('rdi.edit') || $user->hasPermissionTo('rdi.delete') ) {
            return true;
        }

        return false;
    }

    /**
     *
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return mixed
     */
    public function createOrUpdateProblemTree(User $user, Project $project)
    {
        if ( $user->hasPermissionTo('rdi.create') || $user->hasPermissionTo('rdi.edit') ) {
            return true;
        }

        return false;
    }

    /**
     *
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Project  $project
     * @return mixed
     */
    public function createOrUpdateObjectivesTree(User $user, Project $project)
    {
        if ( $user->hasPermissionTo('rdi.create') || $user->hasPermissionTo('rdi.edit') ) {
            return true;
        }

        return false;
    }

}
