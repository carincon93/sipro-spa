<?php

namespace App\Policies;

use App\Models\ProjectAnnexe;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProjectAnnexePolicy
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
        if ( $user->hasPermissionTo('project-annexes.index') || $user->hasPermissionTo('project-annexes.show') || $user->hasPermissionTo('project-annexes.create') || $user->hasPermissionTo('project-annexes.edit') || $user->hasPermissionTo('project-annexes.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProjectAnnexe  $projectAnnexe
     * @return mixed
     */
    public function view(User $user, ProjectAnnexe $projectAnnexe)
    {
        if ( $user->hasPermissionTo('project-annexes.show') ) {
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
        if ( $user->hasPermissionTo('project-annexes.create') || $user->hasPermissionTo('project-annexes.edit') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProjectAnnexe  $projectAnnexe
     * @return mixed
     */
    public function update(User $user, ProjectAnnexe $projectAnnexe)
    {
        if (  $user->hasPermissionTo('project-annexes.show') || $user->hasPermissionTo('project-annexes.edit') || $user->hasPermissionTo('project-annexes.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProjectAnnexe  $projectAnnexe
     * @return mixed
     */
    public function delete(User $user, ProjectAnnexe $projectAnnexe)
    {
        if ( $user->hasPermissionTo('project-annexes.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProjectAnnexe  $projectAnnexe
     * @return mixed
     */
    public function restore(User $user, ProjectAnnexe $projectAnnexe)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ProjectAnnexe  $projectAnnexe
     * @return mixed
     */
    public function forceDelete(User $user, ProjectAnnexe $projectAnnexe)
    {
        //
    }
}
