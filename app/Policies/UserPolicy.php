<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UserPolicy
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
        if ($user->hasRole(4)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function view(User $user)
    {
        if ($user->hasRole(4)) {
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
        if ($user->hasRole(4)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function update(User $user, $proponente)
    {
        if ($user->whereHas('roles', function (Builder $query) use ($user) {
            return $query->where('name', 'ilike', '%líder i+d+i%')->where('users.id', $user->id);
        })->first() && $proponente) {
        }

        if ($user->whereHas('roles', function (Builder $query) use ($user) {
            return $query->where('name', 'ilike', '%líder cultura de la innovación%')->where('users.id', $user->id);
        })->first() && $proponente) {
        }

        if ($user->whereHas('roles', function (Builder $query) use ($user) {
            return $query->where('name', 'ilike', '%líder tecnoacademia%')->where('users.id', $user->id);
        })->first() && $proponente) {
        }

        if ($user->whereHas('roles', function (Builder $query) use ($user) {
            return $query->where('name', 'ilike', '%líder tecnoparque%')->where('users.id', $user->id);
        })->first() && $proponente) {
        }

        if ($user->whereHas('roles', function (Builder $query) use ($user) {
            return $query->where('name', 'ilike', '%líder servicios tecnológicos%')->where('users.id', $user->id);
        })->first() && $proponente) {
        }
        if ($user->dinamizadorCentroFormacion && $user->dinamizadorCentroFormacion->id == $proponente->centroFormacion->id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function delete(User $user)
    {
        if ($user->hasPermissionTo('model.delete')) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function restore(User $user)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return mixed
     */
    public function forceDelete(User $user)
    {
        //
    }
}
