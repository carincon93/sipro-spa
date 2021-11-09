<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\CentroFormacion;
use App\Models\Proyecto;
use App\Models\User;

class ApiController extends Controller
{
    public function CreateToken(Request $request)
    {
        $token = $request->user()->createToken($request->token_name);

        return ['token' => $token->plainTextToken, 'type'=>'Bearer'];
    }

    public function isUserSennova(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => [Rule::requiredIf(function () use ($request) {
                            return empty($request->document_number);
                        }),
                        'email',
                        'exists:users,email'],
            'document_number' => [Rule::requiredIf(function () use ($request) {
                            return empty($request->email);
                        }),
                        'integer',
                        'exists:users,numero_documento'],

        ]);

        if ($validator->fails()) {
            return ['errors'=>$validator->errors()];
        }

        $user = User::where(function ($query) use ($request){
            if (!empty($request->email)) {
                $query->where('email', $request->email);
            }
            if (!empty($request->document_number)) {
                $query->where('numero_documento', $request->document_number);
            }
        })->role('dinamizador sennova')->first();

        if (!empty($user)) {
            return response()->json(['data' =>[
                    "type"=> "users",
                    "id"=> $user->id,
                    "attributes"=> [
                        'name' => $user->nombre,
                        'document_type' => $user->tipo_documento_text,
                        'document_number' => $user->numero_documento,
                        'email' => $user->email,
                        'cellphone' => $user->numero_celular,
                        "links"=> [
                          "proyects_api"=> route('v1.projects_by_user', $user->id)
                        ]
                    ],
                    "training_center"=>[
                        "type"=> "training_centers",
                        "id"=> $user->centroFormacion->id,
                        "attributes"=> [
                          "center_code"=> $user->centroFormacion->codigo,
                          "name"=> $user->centroFormacion->nombre,
                          "regional"=> $user->centroFormacion->regional->nombre,
                        ],
                        "links"=> [
                          "proyects_api"=> route('v1.projects_by_center', $user->centroFormacion->id)
                        ]
                    ],
                ]
            ]);
        }

        return response()->json(['erros'=>['User not found']], 404);
    }

    public function projectsByUser($id)
    {
        $user = User::findOrFail($id);

        $projects = [];
        foreach ($user->proyectos as $proyecto) {
            $tipo = '';
            if (!empty($proyecto->idi)) {
                $datos =  $proyecto->idi;
                $tipo = 'I+D+I';
            } else if (!empty($proyecto->ta)) {
                $datos =  $proyecto->ta;
                $tipo = 'Tecnoacademia';
            } else if (!empty($proyecto->tp)) {
                $datos =  $proyecto->tp;
                $tipo = 'Tecnoparque';
            } else if (!empty($proyecto->culturaInnovacion)) {
                $datos =  $proyecto->culturaInnovacion;
                $tipo = 'Apropiación de la cultura de la innovación';
            } else if (!empty($proyecto->servicioTecnologico)) {
                $datos =  $proyecto->servicioTecnologico;
                $tipo = 'Servicios tecnológicos';
            }
            $userFormuler = $proyecto->participantes()->wherePivot('es_formulador', true)->first();
            array_push($projects, [
                "type"=> "projects",
                "id"=> $proyecto->id,
                "attributes"=> [
                    "announcement"=> $proyecto->convocatoria->descripcion,
                    "type"=> $tipo,
                    'code' => $proyecto->codigo,
                    'title' => $datos->titulo,
                    'general_objective' => $datos->objetivo_general,
                    'start_date' => $proyecto->fecha_inicio,
                    'end_date' => $proyecto->fecha_finalizacion,
                    "links"=> [
                      "proyect_api"=> route('v1.project', $proyecto->id),
                      "public_url"=> route('convocatorias.proyectos.edit', ['convocatoria'=>$proyecto->convocatoria, 'proyecto'=>$proyecto]),
                    ]
                ],
                "formuler"=>[
                    "type"=> "users",
                    "id"=> $userFormuler->id,
                    "attributes"=> [
                        'name' => $userFormuler->nombre,
                        'document_type' => $userFormuler->tipo_documento_text,
                        'document_number' => $userFormuler->numero_documento,
                        'email' => $userFormuler->email,
                        'cellphone' => $userFormuler->numero_celular
                    ]
                ],
                "training_center"=>[
                    "type"=> "training_centers",
                    "id"=> $proyecto->centroFormacion->id,
                    "attributes"=> [
                      "center_code"=> $proyecto->centroFormacion->codigo,
                      "name"=> $proyecto->centroFormacion->nombre,
                      "regional"=> $proyecto->centroFormacion->regional->nombre,
                    ],
                    "links"=> [
                      "proyects_api"=> route('v1.projects_by_center', $proyecto->centroFormacion->id)
                    ]
                ],
            ]);
        }

        return response()->json(['data'=>$projects]);
    }

    public function projectsByCenter($id)
    {
        $center = CentroFormacion::findOrFail($id);

        $projects = [];
        foreach ($center->proyectos as $proyecto) {
            $tipo = '';
            if (!empty($proyecto->idi)) {
                $datos =  $proyecto->idi;
                $tipo = 'I+D+I';
            } else if (!empty($proyecto->ta)) {
                $datos =  $proyecto->ta;
                $tipo = 'Tecnoacademia';
            } else if (!empty($proyecto->tp)) {
                $datos =  $proyecto->tp;
                $tipo = 'Tecnoparque';
            } else if (!empty($proyecto->culturaInnovacion)) {
                $datos =  $proyecto->culturaInnovacion;
                $tipo = 'Apropiación de la cultura de la innovación';
            } else if (!empty($proyecto->servicioTecnologico)) {
                $datos =  $proyecto->servicioTecnologico;
                $tipo = 'Servicios tecnológicos';
            }
            $userFormuler = $proyecto->participantes()->wherePivot('es_formulador', true)->first();
            array_push($projects, [
                "type"=> "projects",
                "id"=> $proyecto->id,
                "attributes"=> [
                    "announcement"=> $proyecto->convocatoria->descripcion,
                    "type"=> $tipo,
                    'code' => $proyecto->codigo,
                    'title' => $datos->titulo,
                    'general_objective' => $datos->objetivo_general,
                    'start_date' => $proyecto->fecha_inicio,
                    'end_date' => $proyecto->fecha_finalizacion,
                    "links"=> [
                      "proyect_api"=> route('v1.project', $proyecto->id),
                      "public_url"=> route('convocatorias.proyectos.edit', ['convocatoria'=>$proyecto->convocatoria, 'proyecto'=>$proyecto]),
                    ]
                ],
                "formuler"=>[
                    "type"=> "users",
                    "id"=> $userFormuler->id,
                    "attributes"=> [
                        'name' => $userFormuler->nombre,
                        'document_type' => $userFormuler->tipo_documento_text,
                        'document_number' => $userFormuler->numero_documento,
                        'email' => $userFormuler->email,
                        'cellphone' => $userFormuler->numero_celular
                    ]
                ],
            ]);
        }

        $response = array_merge(['data'=> $projects], [
            "training_center"=>[
                "type"=> "training_centers",
                "id"=> $center->id,
                "attributes"=> [
                  "center_code"=> $center->codigo,
                  "name"=> $center->nombre,
                  "regional"=> $center->regional->nombre,
                ],
                "links"=> [
                  "proyects_api"=> route('v1.projects_by_center', $center->id)
                ]
            ]
        ]);

        return response()->json($response);
    }

    public function summaryProject($id)
    {
        $proyecto = Proyecto::findOrFail($id);

        $tipo = '';
        $datos = '';
        if (!empty($proyecto->idi)) {
            $datos =  $proyecto->idi;
            $tipo = 'I+D+I';
        } else if (!empty($proyecto->ta)) {
            $datos =  $proyecto->ta;
            $tipo = 'Tecnoacademia';
        } else if (!empty($proyecto->tp)) {
            $datos =  $proyecto->tp;
            $tipo = 'Tecnoparque';
        } else if (!empty($proyecto->culturaInnovacion)) {
            $datos =  $proyecto->culturaInnovacion;
            $tipo = 'Apropiación de la cultura de la innovación';
        } else if (!empty($proyecto->servicioTecnologico)) {
            $datos =  $proyecto->servicioTecnologico;
            $tipo = 'Servicios tecnológicos';
        }

        $response = [
            "type"=> "projects",
            "id"=> $proyecto->id,
            "attributes"=> [
                'announcement'=>$proyecto->convocatoria->descripcion,
                'code'=>$proyecto->codigo,
                'type'=>$tipo,
                'title'=>$datos->titulo,
                'programmatic_line' => [
                    'code'=>$proyecto->lineaProgramatica->codigo,
                    'name'=>$proyecto->lineaProgramatica->nombre,
                ],
                'knowledge_network' => ($datos->redConocimiento) ? $datos->redConocimiento->nombre : 'N/A',
                'knowledge_area' => ($datos->disciplinaSubareaConocimiento) ? $datos->disciplinaSubareaConocimiento->subareaConocimiento->areaConocimiento->nombre : ($datos->areaConocimiento ? $datos->areaConocimiento->nombre : ($datos->disciplinasSubareaConocimiento ? $datos->disciplinasSubareaConocimiento->map(function ($disciplinaSubareaConocimiento) {
                    return ['nombre' => $disciplinaSubareaConocimiento->subareaConocimiento->areaConocimiento->nombre];
                })->implode('nombre', ', ') : 'N/A')),
                'knowledge_subarea' => ($datos->disciplinaSubareaConocimiento) ? $datos->disciplinaSubareaConocimiento->subareaConocimiento->nombre : ($datos->disciplinasSubareaConocimiento ? $datos->disciplinasSubareaConocimiento->map(function ($disciplinaSubareaConocimiento) {
                    return ['nombre' => $disciplinaSubareaConocimiento->subareaConocimiento->nombre];
                })->implode('nombre', ', ') : 'N/A'),
                'disciplines_subarea_knowledge' => ($datos->disciplinaSubareaConocimiento) ? $datos->disciplinaSubareaConocimiento->nombre : ($datos->disciplinasSubareaConocimiento ? $datos->disciplinasSubareaConocimiento->implode('nombre', ', ') : 'N/A'),
                'general_objective' => $datos->objetivo_general,
                'total_project_budget' => $proyecto->total_proyecto_presupuesto,
                'total_roles_sennova' => $proyecto->total_roles_sennova,
                'price_project' => $proyecto->precio_proyecto > 0 ? $proyecto->precio_proyecto : '0',
                'finalized' => ($proyecto->finalizado) ? 'SI' : 'NO',
                'to_evaluate' => ($proyecto->a_evaluar) ? 'SI' : 'NO',
                'evaluation_state' => $proyecto->idi()->exists() ? $proyecto->estado_evaluacion_idi['estado'] : ($proyecto->culturaInnovacion()->exists() ? $proyecto->estado_evaluacion_cultura_innovacion['estado'] : ($proyecto->ta()->exists() ? $proyecto->estado_evaluacion_ta['estado'] : ($proyecto->tp()->exists() ? $proyecto->estado_evaluacion_tp['estado'] : ($proyecto->servicioTecnologico()->exists() ? $proyecto->estado_evaluacion_servicios_tecnologicos['estado'] : 'Sin información registrada')))),
                'state_cord_sennova' => $proyecto->estado_cord_sennova ? json_decode($proyecto->estado_cord_sennova)->estado : 'N/A',
                'score' => $proyecto->idi()->exists() ? $proyecto->estado_evaluacion_idi['puntaje'] : ($proyecto->culturaInnovacion()->exists() ? $proyecto->estado_evaluacion_cultura_innovacion['puntaje'] : ($proyecto->servicioTecnologico()->exists() ? $proyecto->estado_evaluacion_servicios_tecnologicos['puntaje'] : 'N/A')),
                'alert' => $proyecto->idi()->exists() ? $proyecto->estado_evaluacion_idi['alerta'] : 'N/A'
            ],
            'participants' => $this->mapParticipantes($proyecto->participantes),
            "training_center"=>[
                "type"=> "training_centers",
                "id"=> $proyecto->centroFormacion->id,
                "attributes"=> [
                  "center_code"=> $proyecto->centroFormacion->codigo,
                  "name"=> $proyecto->centroFormacion->nombre,
                  "regional"=> $proyecto->centroFormacion->regional->nombre,
                ],
                "links"=> [
                  "proyects_api"=> route('v1.projects_by_center', $proyecto->centroFormacion->id)
                ]
            ],
            "links"=> [
              "proyect_api"=> route('v1.project', $proyecto->id),
              "public_url"=> route('convocatorias.proyectos.edit', ['convocatoria'=>$proyecto->convocatoria, 'proyecto'=>$proyecto]),
            ]
        ];

        return response()->json(['data' => $response]);
    }

    private function mapParticipantes($participantes)
    {
        $tipos_vinculacion = collect(json_decode(Storage::get('json/tipos-vinculacion.json'), true));
        $mapParticipantes = [];

        foreach ($participantes as $participante) {
            array_push($mapParticipantes, [
                "type"=> "users",
                "id"=> $participante->id,
                "attributes"=> [
                    'name' => strtr(utf8_decode($participante->nombre), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY'),
                    'document_type' => $participante->tipo_documento_text,
                    'document_number' => $participante->numero_documento,
                    'contract' => $participante->tipo_vinculacion_text,
                    'months' => $participante->pivot->cantidad_meses,
                    'hours' => $participante->pivot->cantidad_horas,
                    'formuler' => $participante->pivot->es_formulador,
                ]
            ]);
        }
        return $mapParticipantes;
    }
}
