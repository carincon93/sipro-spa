<?php

namespace App\Policies;

use App\Models\RiskAnalysis;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RiskAnalysisPolicy
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
        if ( $user->hasPermissionTo('risk-analysis.index') || $user->hasPermissionTo('risk-analysis.show') || $user->hasPermissionTo('risk-analysis.create') || $user->hasPermissionTo('risk-analysis.edit') || $user->hasPermissionTo('risk-analysis.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RiskAnalysis  $riskAnalysis
     * @return mixed
     */
    public function view(User $user, RiskAnalysis $riskAnalysis)
    {
        if ( $user->hasPermissionTo('risk-analysis.show') ) {
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
        if ( $user->hasPermissionTo('risk-analysis.create') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RiskAnalysis  $riskAnalysis
     * @return mixed
     */
    public function update(User $user, RiskAnalysis $riskAnalysis)
    {
        if ( $user->hasPermissionTo('risk-analysis.show') || $user->hasPermissionTo('risk-analysis.edit') || $user->hasPermissionTo('risk-analysis.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RiskAnalysis  $riskAnalysis
     * @return mixed
     */
    public function delete(User $user, RiskAnalysis $riskAnalysis)
    {
        if ( $user->hasPermissionTo('risk-analysis.delete') ) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RiskAnalysis  $riskAnalysis
     * @return mixed
     */
    public function restore(User $user, RiskAnalysis $riskAnalysis)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\RiskAnalysis  $riskAnalysis
     * @return mixed
     */
    public function forceDelete(User $user, RiskAnalysis $riskAnalysis)
    {
        //
    }
}
