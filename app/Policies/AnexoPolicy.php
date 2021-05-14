<?php

namespace App\Policies;

use App\Models\Anexo;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AnexoPolicy
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
        if ( $user->hasPermissionTo('anexos.index') || $user->hasPermissionTo('anexos.show') || $user->hasPermissionTo('anexos.create') || $user->hasPermissionTo('anexos.edit') || $user->hasPermissionTo('anexos.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Anexo  $anexo
     * @return mixed
     */
    public function view(User $user, Anexo $anexo)
    {
        if ( $user->hasPermissionTo('anexos.show') ) {
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
        if ( $user->hasPermissionTo('anexos.create') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Anexo  $anexo
     * @return mixed
     */
    public function update(User $user, Anexo $anexo)
    {
        if (  $user->hasPermissionTo('anexos.show') || $user->hasPermissionTo('anexos.edit') || $user->hasPermissionTo('anexos.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Anexo  $anexo
     * @return mixed
     */
    public function delete(User $user, Anexo $anexo)
    {
        if ( $user->hasPermissionTo('anexos.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Anexo  $anexo
     * @return mixed
     */
    public function restore(User $user, Anexo $anexo)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Anexo  $anexo
     * @return mixed
     */
    public function forceDelete(User $user, Anexo $anexo)
    {
        //
    }
}
