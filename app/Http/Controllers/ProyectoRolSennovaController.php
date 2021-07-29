<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProyectoRolSennovaRequest;
use App\Models\Convocatoria;
use App\Models\Proyecto;
use App\Models\ProyectoRolSennova;
use Illuminate\Http\Request;
use App\Http\Traits\ProyectoRolSennovaValidationTrait;
use App\Http\Traits\ProyectoRolStValidationTrait;
use App\Http\Traits\ProyectoRolTaValidationTrait;
use App\Http\Traits\ProyectoRolTpValidationTrait;
use App\Models\Evaluacion\Evaluacion;
use App\Models\Evaluacion\ProyectoRolEvaluacion;
use Inertia\Inertia;

class ProyectoRolSennovaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);

        $proyecto->codigo_linea_programatica = $proyecto->lineaProgramatica->codigo;

        if ($proyecto->codigo_linea_programatica == 70) {
            $proyecto->cantidad_instructores_planta = $proyecto->ta->cantidad_instructores_planta;
            $proyecto->cantidad_dinamizadores_planta = $proyecto->ta->cantidad_dinamizadores_planta;
            $proyecto->cantidad_psicopedagogos_planta = $proyecto->ta->cantidad_psicopedagogos_planta;
        }

        /**
         * Si el proyecto es de la línea programática 23 se prohibe el acceso. No requiere de roles SENNOVA
         */
        if ($proyecto->codigo_linea_programatica == 23) {
            return redirect()->route('convocatorias.proyectos.arbol-objetivos', [$convocatoria, $proyecto])->with('error', 'Esta línea programática no requiere de roles SENNOVA');
        }

        return Inertia::render('Convocatorias/Proyectos/RolesSennova/Index', [
            'convocatoria'           => $convocatoria->only('id'),
            'proyecto'               => $proyecto->only('id', 'codigo_linea_programatica', 'precio_proyecto', 'modificable', 'total_roles_sennova', 'cantidad_instructores_planta', 'cantidad_dinamizadores_planta', 'cantidad_psicopedagogos_planta'),
            'filters'                => request()->all('search'),
            'proyectoRolesSennova'   => ProyectoRolSennova::where('proyecto_id', $proyecto->id)->filterProyectoRolSennova(request()->only('search'))->with('convocatoriaRolSennova.rolSennova')->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);

        $proyecto->codigo_linea_programatica = $proyecto->lineaProgramatica->codigo;

        if ($proyecto->codigo_linea_programatica == 68) {
            $proyecto->max_meses_ejecucion = $proyecto->servicioTecnologico->max_meses_ejecucion;
        }

        return Inertia::render('Convocatorias/Proyectos/RolesSennova/Create', [
            'convocatoria'       => $convocatoria->only('id'),
            'proyecto'           => $proyecto->only('id', 'diff_meses', 'modificable', 'max_meses_ejecucion', 'codigo_linea_programatica'),
            'lineaProgramatica'  => $proyecto->lineaProgramatica->only('id')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProyectoRolSennovaRequest $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        /**
         * Línea programática 65
         */
        if ($proyecto->lineaProgramatica->codigo == 65) {
            if (ProyectoRolSennovaValidationTrait::culturaInnovacionRoles($proyecto, $request->convocatoria_rol_sennova_id, $request->numero_roles)) {
                return redirect()->back()->with('error', 'No se ha podido agregar este rol SENNOVA. Razones: Ha superado el límite permitido o el rol no está disponible para este centro de formación. Revise los lineamientos de la convocatoria.');
            }
        }

        /**
         * Línea programática 68
         */
        if ($proyecto->lineaProgramatica->codigo == 68) {
            if (ProyectoRolStValidationTrait::rolStValidation($proyecto, $proyecto->servicioTecnologico->tipoProyectoSt->id, $request->convocatoria_rol_sennova_id, null, $request->numero_roles)) {
                return redirect()->back()->with('error', 'No se ha podido agregar este rol SENNOVA. Razones: Ha superado el límite permitido o el rol no está disponible para este tipo de proyecto. Revise los lineamientos de la convocatoria.');
            }
        }

        /**
         * Línea programática 69
         */
        if ($proyecto->lineaProgramatica->codigo == 69) {
            if (ProyectoRolTpValidationTrait::rolTpValidation($proyecto, $proyecto->tp->nodoTecnoparque->id, $request->convocatoria_rol_sennova_id, null, $request->numero_roles)) {
                return redirect()->back()->with('error', 'No se ha podido agregar este rol SENNOVA. Razones: Ha superado el límite permitido o el rol no está disponible para este nodo. Revise los lineamientos de la convocatoria.');
            }
        }

        /**
         * Línea programática 70
         */
        if ($proyecto->lineaProgramatica->codigo == 70) {
            if (ProyectoRolTaValidationTrait::rolTaValidation($proyecto, $proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->id, $request->convocatoria_rol_sennova_id, null, $request->numero_roles)) {
                return redirect()->back()->with('error', 'No se ha podido agregar este rol SENNOVA. Razones: Ha superado el límite permitido o el rol no está disponible para esta tecnoacademia. Revise los lineamientos de la convocatoria.');
            }
        }

        /**
         * Todas las líneas
         */
        if (ProyectoRolSennovaValidationTrait::monitoriaValidation($request->convocatoria_rol_sennova_id, $proyecto, null, $request->numero_meses, $request->numero_roles)) {
            return redirect()->back()->with('error', 'Máximo 2 monitorías de 3 a 6 meses cada una');
        }

        if ($proyecto->lineaProgramatica->codigo != 70) {
            if (ProyectoRolSennovaValidationTrait::contratoAprendizajeValidation($request->convocatoria_rol_sennova_id, $proyecto, null, $request->numero_meses, $request->numero_roles)) {
                return redirect()->back()->with('error', 'Máximo 1 contrato de aprendizaje por 6 meses');
            }
        } else {
            if (ProyectoRolTaValidationTrait::contratoAprendizajeValidation($request->convocatoria_rol_sennova_id, $proyecto, null, $request->numero_meses, $request->numero_roles)) {
                return redirect()->back()->with('error', 'Máximo 4 contratos de aprendizaje por 6 meses');
            }
        }

        $proyectoRolSennova = new ProyectoRolSennova();
        $proyectoRolSennova->numero_meses = $request->numero_meses;
        $proyectoRolSennova->numero_roles = $request->numero_roles;
        $proyectoRolSennova->descripcion  = $request->descripcion;
        $proyectoRolSennova->proyecto()->associate($proyecto->id);
        $proyectoRolSennova->convocatoriaRolSennova()->associate($request->convocatoria_rol_sennova_id);

        $proyectoRolSennova->save();

        return redirect()->route('convocatorias.proyectos.proyecto-rol-sennova.index', [$convocatoria, $proyecto])->with('success', 'El recurso se ha creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProyectoRolSennova  $proyectoRolSennova
     * @return \Illuminate\Http\Response
     */
    public function show(Convocatoria $convocatoria, Proyecto $proyecto, ProyectoRolSennova $proyectoRolSennova)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProyectoRolSennova  $proyectoRolSennova
     * @return \Illuminate\Http\Response
     */
    public function edit(Convocatoria $convocatoria, Proyecto $proyecto, ProyectoRolSennova $proyectoRolSennova)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);

        $proyecto->codigo_linea_programatica = $proyecto->lineaProgramatica->codigo;

        if ($proyecto->codigo_linea_programatica == 68) {
            $proyecto->max_meses_ejecucion = $proyecto->servicioTecnologico->max_meses_ejecucion;
        }

        return Inertia::render('Convocatorias/Proyectos/RolesSennova/Edit', [
            'convocatoria'          => $convocatoria->only('id'),
            'proyecto'              => $proyecto->only('id', 'diff_meses', 'modificable', 'max_meses_ejecucion', 'codigo_linea_programatica'),
            'proyectoRolSennova'    => $proyectoRolSennova,
            'rolSennova'            => $proyectoRolSennova->convocatoriaRolSennova->rolSennova->only('nombre'),
            'lineaProgramatica'     => $proyecto->lineaProgramatica->only('id')
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProyectoRolSennova  $proyectoRolSennova
     * @return \Illuminate\Http\Response
     */
    public function update(ProyectoRolSennovaRequest $request, Convocatoria $convocatoria, Proyecto $proyecto, ProyectoRolSennova $proyectoRolSennova)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        /**
         * Línea programática 65
         */
        if ($proyecto->lineaProgramatica->codigo == 65) {
            if (ProyectoRolSennovaValidationTrait::culturaInnovacionRoles($proyecto, $request->convocatoria_rol_sennova_id, $request->numero_roles)) {
                return redirect()->back()->with('error', 'No se ha podido agregar este rol SENNOVA. Razones: Ha superado el límite permitido o el rol no está disponible para este centro de formación. Revise los lineamientos de la convocatoria.');
            }
        }

        /**
         * Línea programática 68
         */
        if ($proyecto->lineaProgramatica->codigo == 68) {
            if (ProyectoRolStValidationTrait::rolStValidation($proyecto, $proyecto->servicioTecnologico->tipoProyectoSt->id, $request->convocatoria_rol_sennova_id, $proyectoRolSennova->id, $request->numero_roles)) {
                return redirect()->back()->with('error', 'No se ha podido agregar este rol SENNOVA. Razones: Ha superado el límite permitido o el rol no está disponible para este tipo de proyecto. Revise los lineamientos de la convocatoria.');
            }
        }

        /**
         * Línea programática 69
         */
        if ($proyecto->lineaProgramatica->codigo == 69) {
            if (ProyectoRolTpValidationTrait::rolTpValidation($proyecto, $proyecto->tp->nodoTecnoparque->id, $request->convocatoria_rol_sennova_id, $proyectoRolSennova->id, $request->numero_roles)) {
                return redirect()->back()->with('error', 'No se ha podido agregar este rol SENNOVA. Razones: Ha superado el límite permitido o el rol no está disponible para este nodo. Revise los lineamientos de la convocatoria.');
            }
        }

        /**
         * Línea programática 70
         */
        if ($proyecto->lineaProgramatica->codigo == 70) {
            if (ProyectoRolTaValidationTrait::rolTaValidation($proyecto, $proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->id, $request->convocatoria_rol_sennova_id, $proyectoRolSennova->id, $request->numero_roles)) {
                return redirect()->back()->with('error', 'No se ha podido agregar este rol SENNOVA. Razones: Ha superado el límite permitido o el rol no está disponible para esta tecnoacademia. Revise los lineamientos de la convocatoria.');
            }
        }

        /**
         * Todas las líneas
         */
        if (ProyectoRolSennovaValidationTrait::monitoriaValidation($request->convocatoria_rol_sennova_id, $proyecto, $proyectoRolSennova, $request->numero_meses, $request->numero_roles)) {
            return redirect()->back()->with('error', 'Máximo 2 monitorias de 3 a 6 meses cada una');
        }

        if ($proyecto->lineaProgramatica->codigo != 70) {
            if (ProyectoRolSennovaValidationTrait::contratoAprendizajeValidation($request->convocatoria_rol_sennova_id, $proyecto, $proyectoRolSennova, $request->numero_meses, $request->numero_roles)) {
                return redirect()->back()->with('error', 'Máximo 1 contrato de aprendizaje por 6 meses');
            }
        } else {
            if (ProyectoRolTaValidationTrait::contratoAprendizajeValidation($request->convocatoria_rol_sennova_id, $proyecto, $proyectoRolSennova, $request->numero_meses, $request->numero_roles)) {
                return redirect()->back()->with('error', 'Máximo 4 contratos de aprendizaje por 6 meses');
            }
        }

        $proyectoRolSennova->numero_meses = $request->numero_meses;
        $proyectoRolSennova->numero_roles = $request->numero_roles;
        $proyectoRolSennova->descripcion  = $request->descripcion;
        $proyectoRolSennova->proyecto()->associate($proyecto->id);
        $proyectoRolSennova->convocatoriaRolSennova()->associate($request->convocatoria_rol_sennova_id);

        $proyectoRolSennova->save();

        return redirect()->back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProyectoRolSennova  $proyectoRolSennova
     * @return \Illuminate\Http\Response
     */
    public function destroy(Convocatoria $convocatoria, Proyecto $proyecto, ProyectoRolSennova $proyectoRolSennova)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        $proyectoRolSennova->delete();

        return redirect()->route('convocatorias.proyectos.proyecto-rol-sennova.index', [$convocatoria, $proyecto])->with('success', 'El recurso se ha eliminado correctamente.');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function proyectoRolEvaluacion(Convocatoria $convocatoria, Evaluacion $evaluacion)
    {
        $evaluacion->proyecto->codigo_linea_programatica = $evaluacion->proyecto->lineaProgramatica->codigo;

        if ($evaluacion->proyecto->codigo_linea_programatica == 70) {
            $evaluacion->proyecto->cantidad_instructores_planta = $evaluacion->proyecto->ta->cantidad_instructores_planta;
            $evaluacion->proyecto->cantidad_dinamizadores_planta = $evaluacion->proyecto->ta->cantidad_dinamizadores_planta;
            $evaluacion->proyecto->cantidad_psicopedagogos_planta = $evaluacion->proyecto->ta->cantidad_psicopedagogos_planta;
        }

        /**
         * Si el proyecto es de la línea programática 23 se prohibe el acceso. No requiere de roles SENNOVA
         */
        if ($evaluacion->proyecto->codigo_linea_programatica == 23) {
            return redirect()->route('convocatorias.proyectos.arbol-objetivos', [$convocatoria, $evaluacion->proyecto])->with('error', 'Esta línea programática no requiere de roles SENNOVA');
        }

        return Inertia::render('Convocatorias/Evaluaciones/RolesSennova/Index', [
            'convocatoria'           => $convocatoria->only('id'),
            'evaluacion'             => $evaluacion->only('id'),
            'proyecto'               => $evaluacion->proyecto->only('id', 'codigo_linea_programatica', 'precio_proyecto', 'finalizado', 'total_roles_sennova', 'cantidad_instructores_planta', 'cantidad_dinamizadores_planta', 'cantidad_psicopedagogos_planta'),
            'filters'                => request()->all('search'),
            'proyectoRolesSennova'   => ProyectoRolSennova::where('proyecto_id', $evaluacion->proyecto->id)->filterProyectoRolSennova(request()->only('search'))->with('convocatoriaRolSennova.rolSennova')->paginate(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProyectoRolSennova  $proyectoRolSennova
     * @return \Illuminate\Http\Response
     */
    public function evaluacionForm(Convocatoria $convocatoria, Evaluacion $evaluacion, ProyectoRolSennova $proyectoRolSennova)
    {
        $evaluacion->proyecto->codigo_linea_programatica = $evaluacion->proyecto->lineaProgramatica->codigo;

        if ($evaluacion->proyecto->codigo_linea_programatica == 68) {
            $evaluacion->proyecto->max_meses_ejecucion = $evaluacion->proyecto->servicioTecnologico->max_meses_ejecucion;
        }

        return Inertia::render('Convocatorias/Evaluaciones/RolesSennova/Edit', [
            'convocatoria'          => $convocatoria->only('id'),
            'evaluacion'            => $evaluacion->only('id'),
            'proyecto'              => $evaluacion->proyecto->only('id', 'diff_meses', 'finalizado', 'max_meses_ejecucion', 'codigo_linea_programatica'),
            'proyectoRolSennova'    => $proyectoRolSennova,
            'rolSennova'            => $proyectoRolSennova->convocatoriaRolSennova->rolSennova->only('nombre'),
            'lineaProgramatica'     => $evaluacion->proyecto->lineaProgramatica->only('id'),
            'proyectoRolEvaluacion' => ProyectoRolEvaluacion::where('evaluacion_id', $evaluacion->id)->where('proyecto_rol_sennova_id', $proyectoRolSennova->id)->first()
        ]);
    }

    public function updateEvaluacion(Request $request, Convocatoria $convocatoria, Evaluacion $evaluacion, ProyectoRolSennova $proyectoRolSennova)
    {
        ProyectoRolEvaluacion::updateOrCreate(
            ['evaluacion_id' => $evaluacion->id, 'proyecto_rol_sennova_id' => $proyectoRolSennova->id],
            ['correcto' => $request->correcto, 'comentario' => $request->correcto ? $request->comentario : null]
        );

        return redirect()->back()->with('success', 'El recurso se ha actualizado correctamente.');
    }
}
