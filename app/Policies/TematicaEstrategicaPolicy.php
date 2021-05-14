<?php

namespace App\Policies;

use App\Models\TematicaEstrategica;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TematicaEstrategicaPolicy
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
        if ( $user->hasPermissionTo('tematicas-estrategicas.index') || $user->hasPermissionTo('tematicas-estrategicas.show') || $user->hasPermissionTo('tematicas-estrategicas.create') || $user->hasPermissionTo('tematicas-estrategicas.edit') || $user->hasPermissionTo('tematicas-estrategicas.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TematicaEstrategica  $tematicaEstrategica
     * @return mixed
     */
    public function view(User $user, TematicaEstrategica $tematicaEstrategica)
    {
        if ( $user->hasPermissionTo('tematicas-estrategicas.show') ) {
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
        if ( $user->hasPermissionTo('tematicas-estrategicas.create') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TematicaEstrategica  $tematicaEstrategica
     * @return mixed
     */
    public function update(User $user, TematicaEstrategica $tematicaEstrategica)
    {
        if ($user->hasPermissionTo('tematicas-estrategicas.show') || $user->hasPermissionTo('tematicas-estrategicas.edit') || $user->hasPermissionTo('tematicas-estrategicas.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TematicaEstrategica  $tematicaEstrategica
     * @return mixed
     */
    public function delete(User $user, TematicaEstrategica $tematicaEstrategica)
    {
        if ( $user->hasPermissionTo('tematicas-estrategicas.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TematicaEstrategica  $tematicaEstrategica
     * @return mixed
     */
    public function restore(User $user, TematicaEstrategica $tematicaEstrategica)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TematicaEstrategica  $tematicaEstrategica
     * @return mixed
     */
    public function forceDelete(User $user, TematicaEstrategica $tematicaEstrategica)
    {
        //
    }
}
