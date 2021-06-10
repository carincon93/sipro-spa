<?php

namespace App\Http\Traits;

use App\Models\CentroFormacion;
use App\Models\ConvocatoriaRolSennova;
use App\Models\TipoProyecto;

trait ProyectoRolSennovaValidationTrait
{
    public static function monitoriaValidation($convocatoriaRolSennova, $proyecto, $proyectoRolSennova, $numeroMeses, $numeroRoles)
    {
        $convocatoriaRolSennova = ConvocatoriaRolSennova::find($convocatoriaRolSennova);
        if (stripos($convocatoriaRolSennova->rolSennova->nombre, 'monitor (aprendiz)') !== false) {
            // Trae la suma del número de monitorías requeridos
            $numeroRolesSaved = $proyecto->proyectoRolesSennova()->selectRaw('sum(proyecto_rol_sennova.numero_roles) as qty')
                ->join('convocatoria_rol_sennova', 'proyecto_rol_sennova.convocatoria_rol_sennova_id', 'convocatoria_rol_sennova.id')
                ->join('roles_sennova', 'convocatoria_rol_sennova.rol_sennova_id', 'roles_sennova.id')
                ->where('roles_sennova.nombre', 'like', '%monitor (aprendiz)%')->first()->qty;

            // Si se va a actualizar, se trae la suma del número de monitorías requeridos del recurso a actualizar y se resta al número total de todos los monitorías para no afectar la suma de lo que viene en el form
            if ($proyectoRolSennova) {
                $numeroRolesSaved -= $proyecto->proyectoRolesSennova()->selectRaw('sum(proyecto_rol_sennova.numero_roles) as qty')
                    ->join('convocatoria_rol_sennova', 'proyecto_rol_sennova.convocatoria_rol_sennova_id', 'convocatoria_rol_sennova.id')
                    ->join('roles_sennova', 'convocatoria_rol_sennova.rol_sennova_id', 'roles_sennova.id')
                    ->where('roles_sennova.nombre', 'like', '%monitor (aprendiz)%')->where('proyecto_rol_sennova.id', $proyectoRolSennova->id)->first()->qty;
            }

            if (
                $proyecto->proyectoRolesSennova()->where('roles_sennova.nombre', 'like', '%monitor (aprendiz)%')->join('convocatoria_rol_sennova', 'proyecto_rol_sennova.convocatoria_rol_sennova_id', 'convocatoria_rol_sennova.id')
                ->join('roles_sennova', 'convocatoria_rol_sennova.rol_sennova_id', 'roles_sennova.id')->count() > 2 ||  ($numeroRolesSaved + $numeroRoles) > 2
            ) {
                return true;
            }

            if ($numeroMeses == 3 && $numeroRoles <= 2 || $numeroMeses == 6 && $numeroRoles <= 2) {
                return false;
            } else {
                return true;
            }
        }

        return false;
    }

    public static function contratoAprendizajeValidation($convocatoriaRolSennova, $proyecto, $proyectoRolSennova, $numeroMeses, $numeroRoles)
    {
        $convocatoriaRolSennova = ConvocatoriaRolSennova::find($convocatoriaRolSennova);
        if (stripos($convocatoriaRolSennova->rolSennova->nombre, 'Aprendiz sennova (contrato aprendizaje)') !== false) {
            // Trae la suma del número de monitorías requeridos
            $numeroRolesSaved = $proyecto->proyectoRolesSennova()->selectRaw('sum(proyecto_rol_sennova.numero_roles) as qty')
                ->join('convocatoria_rol_sennova', 'proyecto_rol_sennova.convocatoria_rol_sennova_id', 'convocatoria_rol_sennova.id')
                ->join('roles_sennova', 'convocatoria_rol_sennova.rol_sennova_id', 'roles_sennova.id')
                ->where('roles_sennova.nombre', 'like', '%Aprendiz sennova (contrato aprendizaje)%')->first()->qty;

            // Si se va a actualizar, se trae la suma del número de monitorías requeridos del recurso a actualizar y se resta al número total de todos los monitorías para no afectar la suma de lo que viene en el form
            if ($proyectoRolSennova) {
                $numeroRolesSaved -= $proyecto->proyectoRolesSennova()->selectRaw('sum(proyecto_rol_sennova.numero_roles) as qty')
                    ->join('convocatoria_rol_sennova', 'proyecto_rol_sennova.convocatoria_rol_sennova_id', 'convocatoria_rol_sennova.id')
                    ->join('roles_sennova', 'convocatoria_rol_sennova.rol_sennova_id', 'roles_sennova.id')
                    ->where('roles_sennova.nombre', 'like', '%Aprendiz sennova (contrato aprendizaje)%')->where('proyecto_rol_sennova.id', $proyectoRolSennova->id)->first()->qty;
            }

            if (
                $proyecto->proyectoRolesSennova()->where('roles_sennova.nombre', 'like', '%Aprendiz sennova (contrato aprendizaje)%')->join('convocatoria_rol_sennova', 'proyecto_rol_sennova.convocatoria_rol_sennova_id', 'convocatoria_rol_sennova.id')
                ->join('roles_sennova', 'convocatoria_rol_sennova.rol_sennova_id', 'roles_sennova.id')->count() > 1 ||  ($numeroRolesSaved + $numeroRoles) > 1
            ) {
                return true;
            }


            if ($numeroMeses == 6 && $numeroRoles <= 1) {
                return false;
            } else {
                return true;
            }
        }

        return false;
    }

    /**
     * culturaInnovacion
     *
     * @param  mixed $centroFormacionId
     * @param  mixed $tipoProyectoId
     * @return void
     */
    public static function culturaInnovacionNumeroProyectos($centroFormacionId, $tipoProyectoId)
    {
        $centroFormacion = CentroFormacion::find($centroFormacionId);
        $tipoProyecto = TipoProyecto::find($tipoProyectoId);

        if (in_array($centroFormacion->codigo, [9309, 9503, 9124, 9120, 9222, 9116, 9548, 9401, 9403, 9303, 9310, 9529, 9121]) && $tipoProyecto->lineaProgramatica->codigo == 65) {
            foreach ($centroFormacion->proyectos as $proyecto) {
                if ($proyecto->tipoProyecto->lineaProgramatica->codigo == 65) {
                    return true;
                }
            }
        }

        return false;
    }

    public static function culturaInnovacionRoles($centroFormacionId)
    {
        // 25 experto temático en producción científica
        // 26 gestor editorial
        // 27 auxiliar editorial
    }
}
