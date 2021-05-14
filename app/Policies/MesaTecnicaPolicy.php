<?php

namespace App\Policies;

use App\Models\TechnicalCommittee;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TechnicalCommitteePolicy
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
        if ( $user->hasPermissionTo('technical-committees.index') || $user->hasPermissionTo('technical-committees.show') || $user->hasPermissionTo('technical-committees.create') || $user->hasPermissionTo('technical-committees.edit') || $user->hasPermissionTo('technical-committees.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TechnicalCommittee  $mesaTecnica
     * @return mixed
     */
    public function view(User $user, TechnicalCommittee $mesaTecnica)
    {
        if ( $user->hasPermissionTo('technical-committees.show') ) {
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
        if ( $user->hasPermissionTo('technical-committees.create') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TechnicalCommittee  $mesaTecnica
     * @return mixed
     */
    public function update(User $user, TechnicalCommittee $mesaTecnica)
    {
        if ( $user->hasPermissionTo('technical-committees.show') || $user->hasPermissionTo('technical-committees.edit') || $user->hasPermissionTo('technical-committees.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TechnicalCommittee  $mesaTecnica
     * @return mixed
     */
    public function delete(User $user, TechnicalCommittee $mesaTecnica)
    {
        if ( $user->hasPermissionTo('technical-committees.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TechnicalCommittee  $mesaTecnica
     * @return mixed
     */
    public function restore(User $user, TechnicalCommittee $mesaTecnica)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TechnicalCommittee  $mesaTecnica
     * @return mixed
     */
    public function forceDelete(User $user, TechnicalCommittee $mesaTecnica)
    {
        //
    }
}
