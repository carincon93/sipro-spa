<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActividadRequest;
use App\Models\Convocatoria;
use App\Models\Proyecto;
use App\Models\Actividad;
use App\Models\Evaluacion\CulturaInnovacionEvaluacion;
use App\Models\Evaluacion\Evaluacion;
use App\Models\Evaluacion\IdiEvaluacion;
use App\Models\Evaluacion\ServicioTecnologicoEvaluacion;
use App\Models\Evaluacion\TaEvaluacion;
use App\Models\Evaluacion\TpEvaluacion;
use App\Models\ProyectoPresupuesto;
use App\Models\ProyectoRolSennova;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ActividadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Convocatoria $convocatoria, Proyecto $proyecto, Request $request)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);

        $proyecto->load('evaluaciones.idiEvaluacion');
        $proyecto->load('evaluaciones.taEvaluacion');

        $objetivoEspecifico = $proyecto->causasDirectas()->with('objetivoEspecifico')->get()->pluck('objetivoEspecifico')->flatten()->filter();
        $proyecto->codigo_linea_programatica = $proyecto->lineaProgramatica->codigo;

        switch ($proyecto) {
            case $proyecto->idi()->exists():
                $proyecto->metodologia = $proyecto->idi->metodologia;
                break;
            case $proyecto->ta()->exists():
                $proyecto->metodologia = $proyecto->ta->metodologia;
                $proyecto->metodologia_local = $proyecto->ta->metodologia_local;
                break;
            case $proyecto->tp()->exists():
                $proyecto->metodologia = $proyecto->tp->metodologia;
                $proyecto->metodologia_local = $proyecto->tp->metodologia_local;
                break;
            case $proyecto->culturaInnovacion()->exists():
                $proyecto->metodologia = $proyecto->culturaInnovacion->metodologia;
                break;
            case $proyecto->servicioTecnologico()->exists():
                $proyecto->metodologia = $proyecto->servicioTecnologico->metodologia;
                break;
            default:
                break;
        }

        return Inertia::render('Convocatorias/Proyectos/Actividades/Index', [
            'convocatoria'      => $convocatoria->only('id', 'fase_formateada', 'fase', 'mostrar_recomendaciones'),
            'proyecto'          => $proyecto->only('id', 'codigo_linea_programatica', 'precio_proyecto', 'modificable', 'metodologia', 'metodologia_local', 'en_subsanacion', 'evaluaciones'),
            'filters'           => request()->all('search'),
            'actividades'       => Actividad::whereIn(
                'objetivo_especifico_id',
                $objetivoEspecifico->map(function ($objetivoEspecifico) {
                    return $objetivoEspecifico->id;
                })
            )->with('objetivoEspecifico')->orderBy('objetivo_especifico_id', 'ASC')
                ->filterActividad(request()->only('search'))->paginate()->appends(['search' => request()->search]),
            'actividadesGantt'  => Actividad::whereIn(
                'objetivo_especifico_id',
                $objetivoEspecifico->map(function ($objetivoEspecifico) {
                    return $objetivoEspecifico->id;
                })
            )->where('fecha_inicio', '<>', null)->orderBy('fecha_inicio', 'ASC')->get(),
            'to_pdf'          => ($request->to_pdf == 1) ? true : false
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
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ActividadRequest $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        return redirect()->route('convocatorias.proyectos.actividades.index')->with('success', 'El recurso se ha creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Actividad  $actividad
     * @return \Illuminate\Http\Response
     */
    public function show(Convocatoria $convocatoria, Proyecto $proyecto, Actividad $actividad)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Actividad  $actividad
     * @return \Illuminate\Http\Response
     */
    public function edit(Convocatoria $convocatoria, Proyecto $proyecto, Actividad $actividad)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);

        $resultados = $proyecto->efectosDirectos()->whereHas('resultados', function ($query) {
            $query->where('descripcion', '!=', null);
        })->with('resultados')->get()->pluck('resultados')->flatten();

        $productos = $resultados->map(function ($resultado) {
            return $resultado->productos;
        })->flatten();

        return Inertia::render('Convocatorias/Proyectos/Actividades/Edit', [
            'convocatoria'                   => $convocatoria->only('id', 'fase_formateada', 'fase', 'min_fecha_inicio_proyectos', 'max_fecha_finalizacion_proyectos'),
            'proyecto'                       => $proyecto->only('id', 'fecha_inicio', 'fecha_finalizacion', 'modificable'),
            'actividad'                      => $actividad,
            'productos'                      => $productos,
            'productosRelacionados'          => $actividad->productos()->pluck('id'),
            'proyectoPresupuesto'            => ProyectoPresupuesto::where('proyecto_id', $proyecto->id)->with('convocatoriaPresupuesto.presupuestoSennova.segundoGrupoPresupuestal:id,nombre', 'convocatoriaPresupuesto.presupuestoSennova.tercerGrupoPresupuestal:id,nombre', 'convocatoriaPresupuesto.presupuestoSennova.usoPresupuestal:id,descripcion')->get(),
            'proyectoPresupuestoRelacionado' => $actividad->proyectoPresupuesto()->pluck('id'),
            'proyectoRoles'                  => ProyectoRolSennova::where('proyecto_id', $proyecto->id)->with('convocatoriaRolSennova.rolSennova:id,nombre')->get(),
            'proyectoRolRelacionado'         => $actividad->proyectoRolesSennova()->pluck('id'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Actividad  $actividad
     * @return \Illuminate\Http\Response
     */
    public function update(ActividadRequest $request,  Convocatoria $convocatoria, Proyecto $proyecto, Actividad $actividad)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        $actividad->descripcion         = $request->descripcion;
        $actividad->fecha_inicio        = $request->fecha_inicio;
        $actividad->fecha_finalizacion  = $request->fecha_finalizacion;

        $actividad->save();

        $actividad->proyectoPresupuesto()->sync($request->proyecto_presupuesto_id);
        $actividad->proyectoRolesSennova()->sync($request->proyecto_rol_sennova_id);

        return back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Actividad  $actividad
     * @return \Illuminate\Http\Response
     */
    public function destroy(Convocatoria $convocatoria, Proyecto $proyecto, Actividad $actividad)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        $actividad->delete();

        return redirect()->route('convocatorias.proyectos.actividades.index', [$convocatoria, $proyecto])->with('success', 'El recurso se ha eliminado correctamente.');
    }

    /**
     * updateMetodologia
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Proyecto  $proyecto
     * @return \Illuminate\Http\Response
     */
    public function updateMetodologia(Request $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        $request->validate([
            'metodologia' => 'required|string|max:20000',
        ]);

        switch ($proyecto) {
            case $proyecto->idi()->exists():
                $idi              = $proyecto->idi;
                $idi->metodologia = $request->metodologia;

                $idi->save();
                break;
            case $proyecto->ta()->exists():
                $ta                     = $proyecto->ta;
                $ta->metodologia        = $request->metodologia;
                $ta->metodologia_local  = $request->metodologia_local;

                $ta->save();
                break;
            case $proyecto->tp()->exists():
                $tp                     = $proyecto->tp;
                $tp->metodologia        = $request->metodologia;
                $tp->metodologia_local  = $request->metodologia_local;

                $tp->save();
                break;
            case $proyecto->culturaInnovacion()->exists():
                $culturaInnovacion              = $proyecto->culturaInnovacion;
                $culturaInnovacion->metodologia = $request->metodologia;

                $culturaInnovacion->save();
                break;
            case $proyecto->servicioTecnologico()->exists():
                $servicioTecnologico              = $proyecto->servicioTecnologico;
                $servicioTecnologico->metodologia = $request->metodologia;

                $servicioTecnologico->save();
                break;
            default:
                break;
        }

        return back()->with('success', 'El recurso se ha guardado correctamente.');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showMetodologiaEvaluacion(Convocatoria $convocatoria, Evaluacion $evaluacion)
    {
        $this->authorize('visualizar-evaluacion-autor', $evaluacion);

        $objetivoEspecifico = $evaluacion->proyecto->causasDirectas()->with('objetivoEspecifico')->get()->pluck('objetivoEspecifico')->flatten()->filter();
        $evaluacion->proyecto->codigo_linea_programatica = $evaluacion->proyecto->lineaProgramatica->codigo;

        switch ($evaluacion->proyecto) {
            case $evaluacion->proyecto->idi()->exists():
                $evaluacion->proyecto->metodologia = $evaluacion->proyecto->idi->metodologia;
                $evaluacion->idiEvaluacion;
                $idi = $evaluacion->proyecto->idi;

                $segundaEvaluacion = IdiEvaluacion::whereHas('evaluacion', function ($query) use ($idi) {
                    $query->where('evaluaciones.proyecto_id', $idi->id)->where('evaluaciones.habilitado', true);
                })->where('idi_evaluaciones.id', '!=', $evaluacion->idiEvaluacion->id)->first();
                break;
            case $evaluacion->proyecto->ta()->exists():
                $evaluacion->proyecto->metodologia = $evaluacion->proyecto->ta->metodologia;
                $evaluacion->proyecto->metodologia_local = $evaluacion->proyecto->ta->metodologia_local;
                $evaluacion->taEvaluacion;
                $ta = $evaluacion->proyecto->ta;

                $segundaEvaluacion = TaEvaluacion::whereHas('evaluacion', function ($query) use ($ta) {
                    $query->where('evaluaciones.proyecto_id', $ta->id)->where('evaluaciones.habilitado', true);
                })->where('ta_evaluaciones.id', '!=', $evaluacion->taEvaluacion->id)->first();
                break;
            case $evaluacion->proyecto->tp()->exists():
                $evaluacion->proyecto->metodologia = $evaluacion->proyecto->tp->metodologia;
                $evaluacion->proyecto->metodologia_local = $evaluacion->proyecto->tp->metodologia_local;

                $evaluacion->tpEvaluacion;
                $tp = $evaluacion->proyecto->tp;

                $segundaEvaluacion = TpEvaluacion::whereHas('evaluacion', function ($query) use ($tp) {
                    $query->where('evaluaciones.proyecto_id', $tp->id)->where('evaluaciones.habilitado', true);
                })->where('tp_evaluaciones.id', '!=', $evaluacion->tpEvaluacion->id)->first();
                break;
            case $evaluacion->proyecto->culturaInnovacion()->exists():
                $evaluacion->proyecto->metodologia = $evaluacion->proyecto->culturaInnovacion->metodologia;

                $evaluacion->culturaInnovacionEvaluacion;
                $culturaInnovacion = $evaluacion->proyecto->culturaInnovacion;

                $segundaEvaluacion = CulturaInnovacionEvaluacion::whereHas('evaluacion', function ($query) use ($culturaInnovacion) {
                    $query->where('evaluaciones.proyecto_id', $culturaInnovacion->id)->where('evaluaciones.habilitado', true);
                })->where('cultura_innovacion_evaluaciones.id', '!=', $evaluacion->culturaInnovacionEvaluacion->id)->first();
                break;
            case $evaluacion->proyecto->servicioTecnologico()->exists():
                $evaluacion->proyecto->metodologia = $evaluacion->proyecto->servicioTecnologico->metodologia;

                $servicioTecnologico = $evaluacion->proyecto->servicioTecnologico;

                $segundaEvaluacion = ServicioTecnologicoEvaluacion::whereHas('evaluacion', function ($query) use ($servicioTecnologico) {
                    $query->where('evaluaciones.proyecto_id', $servicioTecnologico->id)->where('evaluaciones.habilitado', true);
                })->where('servicios_tecnologicos_evaluaciones.id', '!=', $evaluacion->servicioTecnologicoEvaluacion->id)->first();
                break;
            default:
                break;
        }

        return Inertia::render('Convocatorias/Evaluaciones/Actividades/Index', [
            'convocatoria'      => $convocatoria->only('id', 'fase_formateada', 'fase', 'mostrar_recomendaciones'),
            'evaluacion'        => $evaluacion,
            'segundaEvaluacion' => $segundaEvaluacion,
            'proyecto'          => $evaluacion->proyecto->only('id', 'codigo_linea_programatica', 'precio_proyecto', 'finalizado', 'metodologia', 'metodologia_local', 'cantidad_objetivos'),
            'year'              => date('Y') + 1,
            'filters'           => request()->all('search'),
            'actividades'       => Actividad::whereIn(
                'objetivo_especifico_id',
                $objetivoEspecifico->map(function ($objetivoEspecifico) {
                    return $objetivoEspecifico->id;
                })
            )->with('objetivoEspecifico')->orderBy('objetivo_especifico_id', 'ASC')
                ->filterActividad(request()->only('search'))->paginate()->appends(['search' => request()->search]),
            'actividadesGantt'  => Actividad::whereIn(
                'objetivo_especifico_id',
                $objetivoEspecifico->map(function ($objetivoEspecifico) {
                    return $objetivoEspecifico->id;
                })
            )->where('fecha_inicio', '<>', null)->orderBy('fecha_inicio', 'ASC')->get(),
        ]);
    }

    /**
     * updateMetodologiaEvaluacion
     *
     * @param  mixed $request
     * @param  mixed $convocatoria
     * @param  mixed $evaluacion
     * @return void
     */
    public function updateMetodologiaEvaluacion(Request $request, Convocatoria $convocatoria, Evaluacion $evaluacion)
    {
        $this->authorize('modificar-evaluacion-autor', $evaluacion);

        switch ($evaluacion) {
            case $evaluacion->idiEvaluacion()->exists():
                $evaluacion->idiEvaluacion()->update([
                    'metodologia_puntaje'      => $request->metodologia_puntaje,
                    'metodologia_comentario'   => $request->metodologia_requiere_comentario == false ? $request->metodologia_comentario : null
                ]);
                break;
            case $evaluacion->culturaInnovacionEvaluacion()->exists():
                $evaluacion->culturaInnovacionEvaluacion()->update([
                    'metodologia_puntaje'      => $request->metodologia_puntaje,
                    'metodologia_comentario'   => $request->metodologia_requiere_comentario == false ? $request->metodologia_comentario : null
                ]);
                break;
            case $evaluacion->taEvaluacion()->exists():
                $evaluacion->taEvaluacion()->update([
                    'metodologia_comentario'   => $request->metodologia_requiere_comentario == false ? $request->metodologia_comentario : null
                ]);
                break;
            case $evaluacion->tpEvaluacion()->exists():
                $evaluacion->tpEvaluacion()->update([
                    'metodologia_comentario'   => $request->metodologia_requiere_comentario == false ? $request->metodologia_comentario : null
                ]);
                break;

            case $evaluacion->servicioTecnologicoEvaluacion()->exists():
                $evaluacion->servicioTecnologicoEvaluacion()->update([
                    'metodologia_puntaje'      => $request->metodologia_puntaje,
                    'metodologia_comentario'   => $request->metodologia_requiere_comentario == false ? $request->metodologia_comentario : null,

                    'actividades_primer_obj_puntaje'      => $request->actividades_primer_obj_puntaje,
                    'actividades_primer_obj_comentario'   => $request->actividades_primer_obj_requiere_comentario == false ? $request->actividades_primer_obj_comentario : null,

                    'actividades_segundo_obj_puntaje'      => $request->actividades_segundo_obj_puntaje,
                    'actividades_segundo_obj_comentario'   => $request->actividades_segundo_obj_requiere_comentario == false ? $request->actividades_segundo_obj_comentario : null,

                    'actividades_tercer_obj_puntaje'      => $request->actividades_tercer_obj_puntaje,
                    'actividades_tercer_obj_comentario'   => $request->actividades_tercer_obj_requiere_comentario == false ? $request->actividades_tercer_obj_comentario : null,

                    'actividades_cuarto_obj_puntaje'      => $request->actividades_cuarto_obj_puntaje,
                    'actividades_cuarto_obj_comentario'   => $request->actividades_cuarto_obj_requiere_comentario == false ? $request->actividades_cuarto_obj_comentario : null,
                ]);
                break;
            default:
                break;
        }

        $evaluacion->save();

        return back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Actividad  $actividad
     * @return \Illuminate\Http\Response
     */
    public function actividadEvaluacion(Convocatoria $convocatoria, Evaluacion $evaluacion, Actividad $actividad)
    {
        $this->authorize('visualizar-evaluacion-autor', $evaluacion);

        $resultados = $evaluacion->proyecto->efectosDirectos()->whereHas('resultados', function ($query) {
            $query->where('descripcion', '!=', null);
        })->with('resultados')->get()->pluck('resultados')->flatten();

        $productos = $resultados->map(function ($resultado) {
            return $resultado->productos;
        })->flatten();

        return Inertia::render('Convocatorias/Evaluaciones/Actividades/Edit', [
            'convocatoria'                   => $convocatoria->only('id', 'fase_formateada', 'fase', 'min_fecha_inicio_proyectos', 'max_fecha_finalizacion_proyectos'),
            'evaluacion'                     => $evaluacion->only('id'),
            'proyecto'                       => $evaluacion->proyecto->only('id', 'fecha_inicio', 'fecha_finalizacion', 'finalizado'),
            'productos'                      => $productos,
            'proyectoPresupuesto'            => ProyectoPresupuesto::where('proyecto_id', $evaluacion->proyecto->id)->with('convocatoriaPresupuesto.presupuestoSennova.segundoGrupoPresupuestal:id,nombre', 'convocatoriaPresupuesto.presupuestoSennova.tercerGrupoPresupuestal:id,nombre', 'convocatoriaPresupuesto.presupuestoSennova.usoPresupuestal:id,descripcion')->get(),
            'actividad'                      => $actividad,
            'productosRelacionados'          => $actividad->productos()->pluck('id'),
            'proyectoPresupuestoRelacionado' => $actividad->proyectoPresupuesto()->pluck('id')
        ]);
    }
}
