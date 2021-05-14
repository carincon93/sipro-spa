<?php

namespace App\Http\Traits;

use App\Models\CallSennovaRole;

trait ProjectRoleValidationTrait {

    public static function studentRoleValidation($callSennovaRole, $project, $projectSennovaRole, $qtyMonths, $qtyRoles) {
        if ($project->projectType->programmaticLine->code == 66) {
            
            // Trae la suma de la cantidad de aprendices requeridos
            $qtySaved = $project->sennovaRoles()->selectRaw('sum(project_sennova_roles.qty_roles) as qty')
                            ->join('call_sennova_roles', 'project_sennova_roles.call_sennova_role_id', 'call_sennova_roles.id')
                            ->join('sennova_roles', 'call_sennova_roles.sennova_role_id', 'sennova_roles.id')
                            ->where('sennova_roles.name', 'like', '%monitor (aprendiz)%')->first()->qty;

            // Si se va a actualziar, se trae la suma de la cantidad de aprendices requeridos en el rol de proyecto a editar y se resta a la cantidad de todos los roles para no afectar la suma de lo que viene en el form
            if($projectSennovaRole) {
                $qtySaved -= $project->sennovaRoles()->selectRaw('sum(project_sennova_roles.qty_roles) as qty')
                                ->join('call_sennova_roles', 'project_sennova_roles.call_sennova_role_id', 'call_sennova_roles.id')
                                ->join('sennova_roles', 'call_sennova_roles.sennova_role_id', 'sennova_roles.id')
                                ->where('sennova_roles.name', 'like', '%monitor (aprendiz)%')->where('project_sennova_roles.id', $projectSennovaRole->id)->first()->qty;
            }

            if(
                $project->sennovaRoles()->where('sennova_roles.name', 'like', '%monitor (aprendiz)%')->join('call_sennova_roles', 'project_sennova_roles.call_sennova_role_id', 'call_sennova_roles.id')
                ->join('sennova_roles', 'call_sennova_roles.sennova_role_id', 'sennova_roles.id')->count() > 2 ||  ($qtySaved + $qtyRoles) > 2
            ) {
                return true;
            }
            
            $callSennovaRole = CallSennovaRole::find($callSennovaRole);
            if(stripos($callSennovaRole->sennovaRole->name, 'monitor (aprendiz)') !== FALSE) {
    
                
                if($qtyMonths == 3 && $qtyRoles <= 2 || $qtyMonths == 6 && $qtyRoles <= 2) {
                    return false;
                }
            }
        }

        return true;
    }
}