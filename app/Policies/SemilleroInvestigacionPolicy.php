<?php

namespace App\Policies;

use App\Models\SemilleroInvestigacion;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SemilleroInvestigacionPolicy
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
        if ( $user->hasPermissionTo('semilleros-investigacion.index') || $user->hasPermissionTo('semilleros-investigacion.show') || $user->hasPermissionTo('semilleros-investigacion.create') || $user->hasPermissionTo('semilleros-investigacion.edit') || $user->hasPermissionTo('semilleros-investigacion.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SemilleroInvestigacion  $semilleroInvestigacion
     * @return mixed
     */
    public function view(User $user, SemilleroInvestigacion $semilleroInvestigacion)
    {
        if ( $user->hasPermissionTo('semilleros-investigacion.show') ) {
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
        if ( $user->hasPermissionTo('semilleros-investigacion.create') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SemilleroInvestigacion  $semilleroInvestigacion
     * @return mixed
     */
    public function update(User $user, SemilleroInvestigacion $semilleroInvestigacion)
    {
        if ( $user->hasPermissionTo('semilleros-investigacion.show') || $user->hasPermissionTo('semilleros-investigacion.edit') || $user->hasPermissionTo('semilleros-investigacion.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SemilleroInvestigacion  $semilleroInvestigacion
     * @return mixed
     */
    public function delete(User $user, SemilleroInvestigacion $semilleroInvestigacion)
    {
        if ( $user->hasPermissionTo('semilleros-investigacion.destroy') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SemilleroInvestigacion  $semilleroInvestigacion
     * @return mixed
     */
    public function restore(User $user, SemilleroInvestigacion $semilleroInvestigacion)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\SemilleroInvestigacion  $semilleroInvestigacion
     * @return mixed
     */
    public function forceDelete(User $user, SemilleroInvestigacion $semilleroInvestigacion)
    {
        //
    }
}
