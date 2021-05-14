<?php

namespace App\Policies;

use App\Models\TemaPriorizado;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TemaPriorizadoPolicy
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
        if ( $user->hasPermissionTo('temas-priorizados.index') || $user->hasPermissionTo('temas-priorizados.show') || $user->hasPermissionTo('temas-priorizados.create') || $user->hasPermissionTo('temas-priorizados.edit') || $user->hasPermissionTo('temas-priorizados.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TemaPriorizado  $temaPriorizado
     * @return mixed
     */
    public function view(User $user, TemaPriorizado $temaPriorizado)
    {
        if ( $user->hasPermissionTo('temas-priorizados.show') ) {
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
        if ( $user->hasPermissionTo('temas-priorizados.create') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TemaPriorizado  $temaPriorizado
     * @return mixed
     */
    public function update(User $user, TemaPriorizado $temaPriorizado)
    {
        if ( $user->hasPermissionTo('temas-priorizados.show') || $user->hasPermissionTo('temas-priorizados.edit') || $user->hasPermissionTo('temas-priorizados.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TemaPriorizado  $temaPriorizado
     * @return mixed
     */
    public function delete(User $user, TemaPriorizado $temaPriorizado)
    {
        if ( $user->hasPermissionTo('temas-priorizados.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TemaPriorizado  $temaPriorizado
     * @return mixed
     */
    public function restore(User $user, TemaPriorizado $temaPriorizado)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TemaPriorizado  $temaPriorizado
     * @return mixed
     */
    public function forceDelete(User $user, TemaPriorizado $temaPriorizado)
    {
        //
    }
}
