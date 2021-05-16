<?php

namespace App\Http\Traits;

use App\Models\ConvocatoriaRolSennova;

trait ProyectoRolSennovaValidationTrait {

    public static function monitoriaValidation($convocatoriaRolSennova, $proyecto, $proyectoRolSennova, $numeroMeses, $numeroRoles) 
    {
        if ($proyecto->tipoProyecto->lineaProgramatica->codigo != 66) {
            return false;
        }

        // Trae la suma del número de monitorías requeridos
        $numeroMesesSaved = $proyecto->proyectoRolesSennova()->selectRaw('sum(proyecto_rol_sennova.numero_roles) as qty')
                        ->join('convocatoria_rol_sennova', 'proyecto_rol_sennova.convocatoria_rol_sennova_id', 'convocatoria_rol_sennova.id')
                        ->join('roles_sennova', 'convocatoria_rol_sennova.rol_sennova_id', 'roles_sennova.id')
                        ->where('roles_sennova.nombre', 'like', '%monitor (aprendiz)%')->first()->qty;

        // Si se va a actualizar, se trae la suma del número de monitorías requeridos del recurso a actualizar y se resta al número total de todos los monitorías para no afectar la suma de lo que viene en el form
        if($proyectoRolSennova) {
            $numeroMesesSaved -= $proyecto->proyectoRolesSennova()->selectRaw('sum(proyecto_rol_sennova.numero_roles) as qty')
                            ->join('convocatoria_rol_sennova', 'proyecto_rol_sennova.convocatoria_rol_sennova_id', 'convocatoria_rol_sennova.id')
                            ->join('roles_sennova', 'convocatoria_rol_sennova.rol_sennova_id', 'roles_sennova.id')
                            ->where('roles_sennova.nombre', 'like', '%monitor (aprendiz)%')->where('proyecto_rol_sennova.id', $proyectoRolSennova->id)->first()->qty;
        }

        if(
            $proyecto->proyectoRolesSennova()->where('roles_sennova.nombre', 'like', '%monitor (aprendiz)%')->join('convocatoria_rol_sennova', 'proyecto_rol_sennova.convocatoria_rol_sennova_id', 'convocatoria_rol_sennova.id')
            ->join('roles_sennova', 'convocatoria_rol_sennova.rol_sennova_id', 'roles_sennova.id')->count() > 2 ||  ($numeroMesesSaved + $numeroRoles) > 2
        ) {
            return true;
        }
        
        $convocatoriaRolSennova = ConvocatoriaRolSennova::find($convocatoriaRolSennova);
        if(stripos($convocatoriaRolSennova->rolSennova->nombre, 'monitor (aprendiz)') !== false) {
            if($numeroMeses == 3 && $numeroRoles <= 2 || $numeroMeses == 6 && $numeroRoles <= 2) {
                return false;
            }
        }

        return true;
    }
}