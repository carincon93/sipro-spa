<?php

namespace App\Http\Traits;

use App\Models\ConvocatoriaRolSennova;
use App\Models\ProyectoRolSennova;

trait ProyectoRolTaValidationTrait
{
    /**
     * totalRolesSennova
     *
     * Obtiene la cantidad total de un rol sennova
     * 
     * @param  mixed $proyecto
     * @param  mixed $codigo
     * @return int
     */
    public static function totalRolesSennova($proyecto, $rolSennovaId)
    {
        $total = 0;

        foreach ($proyecto->proyectoRolesSennova as $rolSennova) {
            if ($rolSennova->convocatoriaRolSennova->rolSennova->id == $rolSennovaId) {
                $total++;
            }
        }

        return $total;
    }

    public static function rolTaValidation($proyecto, $tecnoacademiaId, $convocatoriaRolSennovaId, $proyectoRolSennovaId, $cantidadRoles)
    {
        $reglas = '[
            {
                "tecnoacademiaId": 4, 
                "modalidad": 2,
                "roles": [
                    {
                        "rolId": 11,
                        "max": 1
                    },
                    {
                        "rolId": 9,
                        "max": 7
                    },
                    {
                        "rolId": 13,
                        "max": 1
                    }
                ]
            },
            {
                "tecnoacademiaId": 7, 
                "modalidad": 2,
                "roles": [
                    {
                        "rolId": 11,
                        "max": 1
                    },
                    {
                        "rolId": 9,
                        "max": 7
                    },
                    {
                        "rolId": 13,
                        "max": 1
                    }
                ]
            },
            {
                "tecnoacademiaId": 11, 
                "modalidad": 2,
                "roles": [
                    {
                        "rolId": 11,
                        "max": 1
                    },
                    {
                        "rolId": 9,
                        "max": 6
                    },
                    {
                        "rolId": 13,
                        "max": 1
                    }
                ]
            },
            {
                "tecnoacademiaId": 14, 
                "modalidad": 2,
                "roles": [
                    {
                        "rolId": 11,
                        "max": 1
                    },
                    {
                        "rolId": 9,
                        "max": 7
                    },
                    {
                        "rolId": 13,
                        "max": 1
                    }
                ]
            },
            {
                "tecnoacademiaId": 17, 
                "modalidad": 2,
                "roles": [
                    {
                        "rolId": 11,
                        "max": 1
                    },
                    {
                        "rolId": 9,
                        "max": 7
                    },
                    {
                        "rolId": 13,
                        "max": 1
                    }
                ]
            },
            {
                "tecnoacademiaId": 16, 
                "modalidad": 1,
                "roles": [
                    {
                        "rolId": 11,
                        "max": 1
                    },
                    {
                        "rolId": 9,
                        "max": 6
                    },
                    {
                        "rolId": 13,
                        "max": 1
                    }
                ]
            },
            {
                "tecnoacademiaId": 21, 
                "modalidad": 3,
                "roles": [
                    {
                        "rolId": 12,
                        "max": 1
                    },
                    {
                        "rolId": 9,
                        "max": 5
                    },
                    {
                        "rolId": 10,
                        "max": 4
                    },
                    {
                        "rolId": 13,
                        "max": 1
                    }
                ]
            },
            {
                "tecnoacademiaId": 2, 
                "modalidad": 3,
                "roles": [
                    {
                        "rolId": 9,
                        "max": 6
                    },
                    {
                        "rolId": 10,
                        "max": 5
                    },
                    {
                        "rolId": 13,
                        "max": 1
                    }
                ]
            },
            {
                "tecnoacademiaId": 6, 
                "modalidad": 3,
                "roles": [
                    {
                        "rolId": 9,
                        "max": 5
                    },
                    {
                        "rolId": 10,
                        "max": 5
                    },
                    {
                        "rolId": 13,
                        "max": 1
                    }
                ]
            },
            {
                "tecnoacademiaId": 5, 
                "modalidad": 2,
                "roles": [
                    {
                        "rolId": 11,
                        "max": 1
                    },
                    {
                        "rolId": 9,
                        "max": 8
                    },
                    {
                        "rolId": 13,
                        "max": 1
                    }
                ]
            },
            {
                "tecnoacademiaId": 24, 
                "modalidad": 3,
                "roles": [
                    {
                        "rolId": 9,
                        "max": 11
                    },
                    {
                        "rolId": 10,
                        "max": 3
                    },
                    {
                        "rolId": 13,
                        "max": 1
                    }
                ]
            },
            {
                "tecnoacademiaId": 26, 
                "modalidad": 3,
                "roles": [
                    {
                        "rolId": 9,
                        "max": 7
                    },
                    {
                        "rolId": 10,
                        "max": 2
                    },
                    {
                        "rolId": 14,
                        "max": 1
                    }
                ]
            },
            {
                "tecnoacademiaId": 27, 
                "modalidad": 3,
                "roles": [
                    {
                        "rolId": 9,
                        "max": 4
                    },
                    {
                        "rolId": 10,
                        "max": 7
                    },
                    {
                        "rolId": 13,
                        "max": 1
                    }
                ]
            },
            {
                "tecnoacademiaId": 13, 
                "modalidad": 3,
                "roles": [
                    {
                        "rolId": 9,
                        "max": 6
                    },
                    {
                        "rolId": 10,
                        "max": 6
                    },
                    {
                        "rolId": 13,
                        "max": 1
                    }
                ]
            },
            {
                "tecnoacademiaId": 3, 
                "modalidad": 2,
                "roles": [
                    {
                        "rolId": 11,
                        "max": 1
                    },
                    {
                        "rolId": 9,
                        "max": 6
                    },
                    {
                        "rolId": 13,
                        "max": 1
                    }
                ]
            },
            {
                "tecnoacademiaId": 8, 
                "modalidad": 3,
                "roles": [
                    {
                        "rolId": 12,
                        "max": 1
                    },
                    {
                        "rolId": 9,
                        "max": 5
                    },
                    {
                        "rolId": 10,
                        "max": 3
                    },
                    {
                        "rolId": 14,
                        "max": 1
                    }
                ]
            },
            {
                "tecnoacademiaId": 22, 
                "modalidad": 3,
                "roles": [
                    {
                        "rolId": 9,
                        "max": 2
                    },
                    {
                        "rolId": 10,
                        "max": 7
                    },
                    {
                        "rolId": 14,
                        "max": 1
                    }
                ]
            },
            {
                "tecnoacademiaId": 1, 
                "modalidad": 3,
                "roles": [
                    {
                        "rolId": 12,
                        "max": 1
                    },
                    {
                        "rolId": 9,
                        "max": 4
                    },
                    {
                        "rolId": 10,
                        "max": 4
                    },
                    {
                        "rolId": 13,
                        "max": 1
                    }
                ]
            },
            {
                "tecnoacademiaId": 12, 
                "modalidad": 2,
                "roles": [
                    {
                        "rolId": 11,
                        "max": 1
                    },
                    {
                        "rolId": 9,
                        "max": 7
                    },
                    {
                        "rolId": 10,
                        "max": 1
                    }
                ]
            },
            {
                "tecnoacademiaId": 9, 
                "modalidad": 3,
                "roles": [
                    {
                        "rolId": 11,
                        "max": 1
                    },
                    {
                        "rolId": 9,
                        "max": 7
                    },
                    {
                        "rolId": 10,
                        "max": 1
                    }
                ]
            },
            {
                "tecnoacademiaId": 10, 
                "modalidad": 2,
                "roles": [
                    {
                        "rolId": 11,
                        "max": 1
                    },
                    {
                        "rolId": 9,
                        "max": 6
                    },
                    {
                        "rolId": 10,
                        "max": 1
                    }
                ]
            },
            {
                "tecnoacademiaId": 15, 
                "modalidad": 3,
                "roles": [
                    {
                        "rolId": 9,
                        "max": 2
                    },
                    {
                        "rolId": 10,
                        "max": 9
                    },
                    {
                        "rolId": 13,
                        "max": 1
                    }
                ]
            },
            {
                "tecnoacademiaId": 19, 
                "modalidad": 1,
                "roles": [
                    {
                        "rolId": 11,
                        "max": 1
                    },
                    {
                        "rolId": 9,
                        "max": 9
                    },
                    {
                        "rolId": 13,
                        "max": 1
                    }
                ]
            },
            {
                "tecnoacademiaId": 18, 
                "modalidad": 3,
                "roles": [
                    {
                        "rolId": 9,
                        "max": 1
                    },
                    {
                        "rolId": 10,
                        "max": 5
                    },
                    {
                        "rolId": 13,
                        "max": 1
                    }
                ]
            },
            {
                "tecnoacademiaId": 25, 
                "modalidad": 2,
                "roles": [
                    {
                        "rolId": 11,
                        "max": 1
                    },
                    {
                        "rolId": 9,
                        "max": 6
                    },
                    {
                        "rolId": 13,
                        "max": 1
                    }
                ]
            },
            {
                "tecnoacademiaId": 23, 
                "modalidad": 3,
                "roles": [
                    {
                        "rolId": 12,
                        "max": 1
                    },
                    {
                        "rolId": 9,
                        "max": 7
                    },
                    {
                        "rolId": 13,
                        "max": 1
                    }
                ]
            }
        ]';

        $tecnoacademia = collect(json_decode($reglas, true))->where('tecnoacademiaId', $tecnoacademiaId)->first();

        $convocatoriaRolSennova = ConvocatoriaRolSennova::find($convocatoriaRolSennovaId);

        $proyectoRolSennovaBd = ProyectoRolSennova::find($proyectoRolSennovaId);

        $proyectoRolesSennova = ProyectoRolSennova::where('convocatoria_rol_sennova_id', $convocatoriaRolSennovaId)->where('proyecto_id', $proyecto->id)->get();
        if (count($proyectoRolesSennova) > 0) {
            foreach ($proyectoRolesSennova as $proyectoRolSennova) {
                if ($proyectoRolSennovaBd && $proyectoRolSennova->id == $proyectoRolSennovaBd->id) {
                    $proyectoRolSennova->numero_roles = 0;
                }
                $cantidadRoles += $proyectoRolSennova->numero_roles;
            }
        }

        if ($tecnoacademia) {
            $count = 0;
            // Valida si el rol ta está en el array
            foreach ($tecnoacademia['roles'] as $rol) {
                if ($convocatoriaRolSennova->rolSennova->id == $rol['rolId']) {
                    $count--;
                } else {
                    $count++;
                }
            }

            foreach ($tecnoacademia['roles'] as $rol) {
                if ($convocatoriaRolSennova->rolSennova->id == $rol['rolId'] && $cantidadRoles > $rol['max']) {
                    return true;
                }
            }
        }

        // Valida si el rol ta está en el array
        if ($count == count($tecnoacademia['roles'])) {
            return true;
        }

        return false;
    }
}
