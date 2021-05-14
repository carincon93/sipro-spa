<?php

namespace App\Policies;

use App\Models\SpecificObjective;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SpecificObjectivePolicy
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
        if ( $user->hasPermissionTo('specific-objectives.index') || $user->hasPermissionTo('specific-objectives.show') || $user->hasPermissionTo('specific-objectives.create') || $user->hasPermissionTo('specific-objectives.edit') ||$user->hasPermissionTo('specific-objectives.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SpecificObjective  $specificObjective
     * @return mixed
     */
    public function view(User $user, SpecificObjective $specificObjective)
    {
        if ( $user->hasPermissionTo('specific-objectives.show') ) {
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
        if ( $user->hasPermissionTo('specific-objectives.create') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SpecificObjective  $specificObjective
     * @return mixed
     */
    public function update(User $user, SpecificObjective $specificObjective)
    {
        if ( $user->hasPermissionTo('specific-objectives.show') || $user->hasPermissionTo('specific-objectives.edit') ||$user->hasPermissionTo('specific-objectives.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SpecificObjective  $specificObjective
     * @return mixed
     */
    public function delete(User $user, SpecificObjective $specificObjective)
    {
        if ( $user->hasPermissionTo('specific-objectives.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SpecificObjective  $specificObjective
     * @return mixed
     */
    public function restore(User $user, SpecificObjective $specificObjective)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SpecificObjective  $specificObjective
     * @return mixed
     */
    public function forceDelete(User $user, SpecificObjective $specificObjective)
    {
        //
    }
}
