<?php

namespace App\Policies;

use App\Models\LineaTecnologica;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LineaTecnologicaPolicy
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
        if ( $user->hasPermissionTo('lineas-tecnologicas.index') || $user->hasPermissionTo('lineas-tecnologicas.show') || $user->hasPermissionTo('lineas-tecnologicas.create') || $user->hasPermissionTo('lineas-tecnologicas.edit') || $user->hasPermissionTo('lineas-tecnologicas.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LineaTecnologica  $lineaTecnologica
     * @return mixed
     */
    public function view(User $user, LineaTecnologica $lineaTecnologica)
    {
        if ( $user->hasPermissionTo('lineas-tecnologicas.show') ) {
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
        if ( $user->hasPermissionTo('lineas-tecnologicas.create') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LineaTecnologica  $lineaTecnologica
     * @return mixed
     */
    public function update(User $user, LineaTecnologica $lineaTecnologica)
    {
        if ( $user->hasPermissionTo('lineas-tecnologicas.show') || $user->hasPermissionTo('lineas-tecnologicas.edit') || $user->hasPermissionTo('lineas-tecnologicas.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LineaTecnologica  $lineaTecnologica
     * @return mixed
     */
    public function delete(User $user, LineaTecnologica $lineaTecnologica)
    {
        if ( $user->hasPermissionTo('lineas-tecnologicas.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LineaTecnologica  $lineaTecnologica
     * @return mixed
     */
    public function restore(User $user, LineaTecnologica $lineaTecnologica)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\LineaTecnologica  $lineaTecnologica
     * @return mixed
     */
    public function forceDelete(User $user, LineaTecnologica $lineaTecnologica)
    {
        //
    }
}
