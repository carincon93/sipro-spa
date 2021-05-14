<?php

namespace App\Policies;

use App\Models\Tecnoacademia;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TecnoacademiaPolicy
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
        if ( $user->hasPermissionTo('tecnoacademias.index') || $user->hasPermissionTo('tecnoacademias.show') || $user->hasPermissionTo('tecnoacademias.create') || $user->hasPermissionTo('tecnoacademias.edit') || $user->hasPermissionTo('tecnoacademias.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tecnoacademia  $tecnoacademia
     * @return mixed
     */
    public function view(User $user, Tecnoacademia $tecnoacademia)
    {
        if ( $user->hasPermissionTo('tecnoacademias.show') ) {
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
        if ( $user->hasPermissionTo('tecnoacademias.create') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tecnoacademia  $tecnoacademia
     * @return mixed
     */
    public function update(User $user, Tecnoacademia $tecnoacademia)
    {
        if ( $user->hasPermissionTo('tecnoacademias.show') || $user->hasPermissionTo('tecnoacademias.edit') || $user->hasPermissionTo('tecnoacademias.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tecnoacademia  $tecnoacademia
     * @return mixed
     */
    public function delete(User $user, Tecnoacademia $tecnoacademia)
    {
        if ( $user->hasPermissionTo('tecnoacademias.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tecnoacademia  $tecnoacademia
     * @return mixed
     */
    public function restore(User $user, Tecnoacademia $tecnoacademia)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Tecnoacademia  $tecnoacademia
     * @return mixed
     */
    public function forceDelete(User $user, Tecnoacademia $tecnoacademia)
    {
        //
    }
}
