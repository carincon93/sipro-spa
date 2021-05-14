<?php

namespace App\Policies;

use App\Models\LineaProgramatica;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LineaProgramaticaPolicy
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
        if ( $user->hasPermissionTo('lineas-programaticas.index') || $user->hasPermissionTo('lineas-programaticas.show') || $user->hasPermissionTo('lineas-programaticas.create') || $user->hasPermissionTo('lineas-programaticas.edit') || $user->hasPermissionTo('lineas-programaticas.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LineaProgramatica  $lineaProgramatica
     * @return mixed
     */
    public function view(User $user, LineaProgramatica $lineaProgramatica)
    {
        if ( $user->hasPermissionTo('lineas-programaticas.show') ) {
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
        if ( $user->hasPermissionTo('lineas-programaticas.create') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LineaProgramatica  $lineaProgramatica
     * @return mixed
     */
    public function update(User $user, LineaProgramatica $lineaProgramatica)
    {
        if ( $user->hasPermissionTo('lineas-programaticas.show') || $user->hasPermissionTo('lineas-programaticas.edit') || $user->hasPermissionTo('lineas-programaticas.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LineaProgramatica  $lineaProgramatica
     * @return mixed
     */
    public function delete(User $user, LineaProgramatica $lineaProgramatica)
    {
        if ( $user->hasPermissionTo('lineas-programaticas.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LineaProgramatica  $lineaProgramatica
     * @return mixed
     */
    public function restore(User $user, LineaProgramatica $lineaProgramatica)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LineaProgramatica  $lineaProgramatica
     * @return mixed
     */
    public function forceDelete(User $user, LineaProgramatica $lineaProgramatica)
    {
        //
    }
}
