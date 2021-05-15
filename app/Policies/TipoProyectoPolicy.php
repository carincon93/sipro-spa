<?php

namespace App\Policies;

use App\Models\TipoProyecto;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TipoProyectoPolicy
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
        if ( $user->hasPermissionTo('tipos-proyecto.index') || $user->hasPermissionTo('tipos-proyecto.show') || $user->hasPermissionTo('tipos-proyecto.create') || $user->hasPermissionTo('tipos-proyecto.edit') || $user->hasPermissionTo('tipos-proyecto.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TipoProyecto  $tipoProyecto
     * @return mixed
     */
    public function view(User $user, TipoProyecto $tipoProyecto)
    {
        if ( $user->hasPermissionTo('tipos-proyecto.show') ) {
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
        if ( $user->hasPermissionTo('tipos-proyecto.create') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TipoProyecto  $tipoProyecto
     * @return mixed
     */
    public function update(User $user, TipoProyecto $tipoProyecto)
    {
        if ( $user->hasPermissionTo('tipos-proyecto.show') || $user->hasPermissionTo('tipos-proyecto.edit') || $user->hasPermissionTo('tipos-proyecto.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TipoProyecto  $tipoProyecto
     * @return mixed
     */
    public function delete(User $user, TipoProyecto $tipoProyecto)
    {
        if ( $user->hasPermissionTo('tipos-proyecto.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TipoProyecto  $tipoProyecto
     * @return mixed
     */
    public function restore(User $user, TipoProyecto $tipoProyecto)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\TipoProyecto  $tipoProyecto
     * @return mixed
     */
    public function forceDelete(User $user, TipoProyecto $tipoProyecto)
    {
        //
    }
}
