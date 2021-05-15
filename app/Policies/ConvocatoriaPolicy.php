<?php

namespace App\Policies;

use App\Models\Convocatoria;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConvocatoriaPolicy
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
        if ( $user->hasPermissionTo('convocatorias.index') || $user->hasPermissionTo('convocatorias.show') || $user->hasPermissionTo('convocatorias.create') || $user->hasPermissionTo('convocatorias.edit') || $user->hasPermissionTo('convocatorias.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Convocatoria  $convocatoria
     * @return mixed
     */
    public function view(User $user, Convocatoria $convocatoria)
    {
        if ( $user->hasPermissionTo('convocatorias.show') ) {
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
        if ( $user->hasPermissionTo('convocatorias.create') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Convocatoria  $convocatoria
     * @return mixed
     */
    public function update(User $user, Convocatoria $convocatoria)
    {
        if ( $user->hasPermissionTo('convocatorias.show') || $user->hasPermissionTo('convocatorias.edit') || $user->hasPermissionTo('convocatorias.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Convocatoria  $convocatoria
     * @return mixed
     */
    public function delete(User $user, Convocatoria $convocatoria)
    {
        if ( $user->hasPermissionTo('convocatorias.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Convocatoria  $convocatoria
     * @return mixed
     */
    public function restore(User $user, Convocatoria $convocatoria)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Convocatoria  $convocatoria
     * @return mixed
     */
    public function forceDelete(User $user, Convocatoria $convocatoria)
    {
        //
    }
}
