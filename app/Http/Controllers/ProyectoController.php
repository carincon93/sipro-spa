<?php

namespace App\Http\Controllers;

use App\Http\Requests\NuevoProponenteRequest;
use App\Http\Requests\ProgramaFormacionRequest;
use App\Http\Requests\ProponenteRequest;
use App\Http\Traits\ProyectoValidationTrait;
use App\Models\Convocatoria;
use App\Models\Evaluacion\CulturaInnovacionEvaluacion;
use App\Models\Evaluacion\Evaluacion;
use App\Models\Evaluacion\IdiEvaluacion;
use App\Models\Evaluacion\ServicioTecnologicoEvaluacion;
use App\Models\Evaluacion\TaEvaluacion;
use App\Models\Evaluacion\TpEvaluacion;
use App\Models\Impacto;
use App\Models\User;
use App\Models\ProgramaFormacion;
use App\Models\Proyecto;
use App\Models\SemilleroInvestigacion;
use App\Models\ProyectoPdfVersion;
use App\Notifications\ComentarioProyecto;
use App\Notifications\EvaluacionFinalizada;
use App\Notifications\ProyectoFinalizado;
use App\Notifications\ProyectoConfirmado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProyectoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Proyectos/Index', [
            'filters'       => request()->all('search'),
            'proyectos'     => Proyecto::with('PdfVersiones', 'convocatoria')->orderBy('id', 'ASC')->filterProyecto(request()->only('search'))->paginate()->appends(['search' => request()->search]),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Evaluacion\Proyecto $proyecto
     * @return \Illuminate\Http\Response
     */
    public function editProyecto(Proyecto $proyecto)
    {
        return Inertia::render('Proyectos/Edit', [
            'proyecto'    => $proyecto,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Evaluacion\Proyecto  $proyecto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Proyecto $proyecto)
    {
        $proyecto->a_evaluar                = $request->a_evaluar;
        $proyecto->modificable              = $request->modificable;
        $proyecto->finalizado               = $request->finalizado;
        $proyecto->radicado                 = $request->radicado;
        $proyecto->mostrar_recomendaciones  = $request->mostrar_recomendaciones;

        $proyecto->save();

        switch ($proyecto) {
            case $proyecto->estado_evaluacion_idi != null:
                $proyecto->update(['estado' => $proyecto->estado_evaluacion_idi]);
                break;
            case $proyecto->estado_evaluacion_cultura_innovacion != null:
                $proyecto->update(['estado' => $proyecto->estado_evaluacion_cultura_innovacion]);
                break;

            case $proyecto->estado_evaluacion_ta != null:
                $proyecto->update(['estado' => $proyecto->estado_evaluacion_ta]);
                break;

            case $proyecto->estado_evaluacion_tp != null:
                $proyecto->update(['estado' => $proyecto->estado_evaluacion_tp]);
                break;

            case $proyecto->estado_evaluacion_servicios_tecnologicos != null:
                $proyecto->update(['estado' => $proyecto->estado_evaluacion_servicios_tecnologicos]);
                break;
            default:
                break;
        }

        return back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * showCadenaValor
     *
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function showCadenaValor(Request $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);

        $proyecto->load('evaluaciones.idiEvaluacion');
        $proyecto->load('evaluaciones.taEvaluacion');

        $proyecto->codigo_linea_programatica = $proyecto->lineaProgramatica->codigo;

        if ($proyecto->idi()->exists()) {
            $objetivoGeneral = $proyecto->idi->objetivo_general;
            $proyecto->propuesta_sostenibilidad = $proyecto->idi->propuesta_sostenibilidad;
        }

        if ($proyecto->ta()->exists()) {
            $objetivoGeneral = $proyecto->ta->objetivo_general;
            $proyecto->propuesta_sostenibilidad_social      = $proyecto->ta->propuesta_sostenibilidad_social;
            $proyecto->propuesta_sostenibilidad_ambiental   = $proyecto->ta->propuesta_sostenibilidad_ambiental;
            $proyecto->propuesta_sostenibilidad_financiera  = $proyecto->ta->propuesta_sostenibilidad_financiera;
        }

        if ($proyecto->tp()->exists()) {
            $objetivoGeneral = $proyecto->tp->objetivo_general;
            $proyecto->propuesta_sostenibilidad = $proyecto->tp->propuesta_sostenibilidad;
        }

        if ($proyecto->servicioTecnologico()->exists()) {
            $objetivoGeneral = $proyecto->servicioTecnologico->objetivo_general;
            $proyecto->propuesta_sostenibilidad = $proyecto->servicioTecnologico->propuesta_sostenibilidad;
            $proyecto->propuesta_sostenibilidad = $proyecto->servicioTecnologico->propuesta_sostenibilidad;
        }

        if ($proyecto->culturaInnovacion()->exists()) {
            $objetivoGeneral = $proyecto->culturaInnovacion->objetivo_general;
            $proyecto->propuesta_sostenibilidad = $proyecto->culturaInnovacion->propuesta_sostenibilidad;
        }

        $objetivos = collect(['Objetivo general' => $objetivoGeneral]);
        $productos = collect([]);

        foreach ($proyecto->causasDirectas as $causaDirecta) {
            $objetivos->prepend($causaDirecta->objetivoEspecifico->descripcion, $causaDirecta->objetivoEspecifico->numero);
        }

        foreach ($proyecto->efectosDirectos as $efectoDirecto) {
            foreach ($efectoDirecto->resultados as $resultado) {
                foreach ($resultado->productos as $producto) {
                    $productos->prepend(['v' => 'prod' . $producto->id,  'f' => $producto->nombre, 'fkey' =>  $resultado->objetivoEspecifico->numero, 'tootlip' => 'prod' . $producto->id, 'actividades' => $producto->actividades->load('proyectoRolesSennova.convocatoriaRolSennova.rolSennova')]);
                }
            }
        }

        return Inertia::render('Convocatorias/Proyectos/CadenaValor/Index', [
            'convocatoria'  => $convocatoria->only('id', 'fase_formateada', 'fase', 'mostrar_recomendaciones'),
            'proyecto'      => $proyecto->only('id', 'codigo_linea_programatica', 'precio_proyecto', 'propuesta_sostenibilidad', 'propuesta_sostenibilidad_social', 'propuesta_sostenibilidad_ambiental', 'propuesta_sostenibilidad_financiera', 'modificable', 'en_subsanacion', 'evaluaciones', 'mostrar_recomendaciones'),
            'productos'     => $productos,
            'objetivos'     => $objetivos,
            'to_pdf'            => ($request->to_pdf == 1) ? true : false
        ]);
    }

    /**
     * showCadenaValorEvaluacion
     *
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function showCadenaValorEvaluacion(Convocatoria $convocatoria, Evaluacion $evaluacion)
    {
        $this->authorize('visualizar-evaluacion-autor', $evaluacion);

        $evaluacion->proyecto->codigo_linea_programatica = $evaluacion->proyecto->lineaProgramatica->codigo;

        $efectosIndirectos = $evaluacion->proyecto->efectosDirectos()->with('efectosIndirectos')->get()->pluck('efectosIndirectos')->flatten()->filter();

        switch ($evaluacion->proyecto) {
            case $evaluacion->proyecto->idi()->exists():
                $objetivoGeneral = $evaluacion->proyecto->idi->objetivo_general;
                $evaluacion->proyecto->propuesta_sostenibilidad = $evaluacion->proyecto->idi->propuesta_sostenibilidad;

                $evaluacion->idiEvaluacion;
                $idi = $evaluacion->proyecto->idi;

                $segundaEvaluacion = IdiEvaluacion::whereHas('evaluacion', function ($query) use ($idi) {
                    $query->where('evaluaciones.proyecto_id', $idi->id)->where('evaluaciones.habilitado', true);
                })->where('idi_evaluaciones.id', '!=', $evaluacion->idiEvaluacion->id)->first();
                break;
            case $evaluacion->proyecto->ta()->exists():
                $objetivoGeneral = $evaluacion->proyecto->ta->objetivo_general;
                $evaluacion->proyecto->propuesta_sostenibilidad_social      = $evaluacion->proyecto->ta->propuesta_sostenibilidad_social;
                $evaluacion->proyecto->propuesta_sostenibilidad_ambiental   = $evaluacion->proyecto->ta->propuesta_sostenibilidad_ambiental;
                $evaluacion->proyecto->propuesta_sostenibilidad_financiera  = $evaluacion->proyecto->ta->propuesta_sostenibilidad_financiera;

                $evaluacion->taEvaluacion;
                $ta = $evaluacion->proyecto->ta;

                $segundaEvaluacion = TaEvaluacion::whereHas('evaluacion', function ($query) use ($ta) {
                    $query->where('evaluaciones.proyecto_id', $ta->id)->where('evaluaciones.habilitado', true);
                })->where('ta_evaluaciones.id', '!=', $evaluacion->taEvaluacion->id)->first();
                break;
            case $evaluacion->proyecto->tp()->exists():
                $objetivoGeneral = $evaluacion->proyecto->tp->objetivo_general;
                $evaluacion->proyecto->propuesta_sostenibilidad = $evaluacion->proyecto->tp->propuesta_sostenibilidad;

                $evaluacion->tpEvaluacion;
                $tp = $evaluacion->proyecto->tp;

                $segundaEvaluacion = TpEvaluacion::whereHas('evaluacion', function ($query) use ($tp) {
                    $query->where('evaluaciones.proyecto_id', $tp->id)->where('evaluaciones.habilitado', true);
                })->where('tp_evaluaciones.id', '!=', $evaluacion->tpEvaluacion->id)->first();
                break;

            case $evaluacion->proyecto->servicioTecnologico()->exists():
                $objetivoGeneral = $evaluacion->proyecto->servicioTecnologico->objetivo_general;
                $evaluacion->proyecto->propuesta_sostenibilidad = $evaluacion->proyecto->servicioTecnologico->propuesta_sostenibilidad;

                $servicioTecnologico = $evaluacion->proyecto->servicioTecnologico;

                $segundaEvaluacion = ServicioTecnologicoEvaluacion::whereHas('evaluacion', function ($query) use ($servicioTecnologico) {
                    $query->where('evaluaciones.proyecto_id', $servicioTecnologico->id)->where('evaluaciones.habilitado', true);
                })->where('servicios_tecnologicos_evaluaciones.id', '!=', $evaluacion->servicioTecnologicoEvaluacion->id)->first();
                break;

            case $evaluacion->proyecto->culturaInnovacion()->exists():
                $objetivoGeneral = $evaluacion->proyecto->culturaInnovacion->objetivo_general;
                $evaluacion->proyecto->propuesta_sostenibilidad = $evaluacion->proyecto->culturaInnovacion->propuesta_sostenibilidad;

                $evaluacion->culturaInnovacionEvaluacion;
                $culturaInnovacion = $evaluacion->proyecto->culturaInnovacion;

                $segundaEvaluacion = CulturaInnovacionEvaluacion::whereHas('evaluacion', function ($query) use ($culturaInnovacion) {
                    $query->where('evaluaciones.proyecto_id', $culturaInnovacion->id)->where('evaluaciones.habilitado', true);
                })->where('cultura_innovacion_evaluaciones.id', '!=', $evaluacion->culturaInnovacionEvaluacion->id)->first();

                break;
            default:
                break;
        }

        $objetivos = collect(['Objetivo general' => $objetivoGeneral]);
        $productos = collect([]);

        foreach ($evaluacion->proyecto->causasDirectas as $causaDirecta) {
            $objetivos->prepend($causaDirecta->objetivoEspecifico->descripcion, $causaDirecta->objetivoEspecifico->numero);
        }

        foreach ($evaluacion->proyecto->efectosDirectos as $efectoDirecto) {
            foreach ($efectoDirecto->resultados as $resultado) {
                foreach ($resultado->productos as $producto) {
                    $productos->prepend(['v' => 'prod' . $producto->id, 'meta' => $producto->productoTaTp ? $producto->productoTaTp->valor_proyectado : 'Sin información',  'f' => $producto->nombre, 'fkey' =>  $resultado->objetivoEspecifico->numero, 'tootlip' => 'prod' . $producto->id, 'actividades' => $producto->actividades]);
                }
            }
        }

        return Inertia::render('Convocatorias/Evaluaciones/CadenaValor/Index', [
            'convocatoria'      => $convocatoria->only('id', 'fase_formateada', 'fase'),
            'evaluacion'        => $evaluacion,
            'segundaEvaluacion' => $segundaEvaluacion,
            'proyecto'          => $evaluacion->proyecto,
            'productos'         => $productos,
            'objetivos'         => $objetivos,
            'impactos'          => Impacto::whereIn(
                'efecto_indirecto_id',
                $efectosIndirectos->map(function ($efectosIndirecto) {
                    return $efectosIndirecto->id;
                })
            )->orderBy('tipo', 'ASC')->get()
        ]);
    }

    /**
     * updateCadenaValorEvaluacion
     *
     * @param  mixed $request
     * @param  mixed $convocatoria
     * @param  mixed $evaluacion
     * @return void
     */
    public function updateCadenaValorEvaluacion(Request $request, Convocatoria $convocatoria, Evaluacion $evaluacion)
    {
        $this->authorize('modificar-evaluacion-autor', $evaluacion);

        switch ($evaluacion) {
            case $evaluacion->idiEvaluacion()->exists():
                $evaluacion->idiEvaluacion()->update([
                    'cadena_valor_puntaje'      => $request->cadena_valor_puntaje,
                    'cadena_valor_comentario'   => $request->cadena_valor_requiere_comentario == false ? $request->cadena_valor_comentario : null
                ]);
                break;
            case $evaluacion->culturaInnovacionEvaluacion()->exists():
                $evaluacion->culturaInnovacionEvaluacion()->update([
                    'cadena_valor_puntaje'      => $request->cadena_valor_puntaje,
                    'cadena_valor_comentario'   => $request->cadena_valor_requiere_comentario == false ? $request->cadena_valor_comentario : null
                ]);
                break;
            case $evaluacion->taEvaluacion()->exists():
                $evaluacion->taEvaluacion()->update([
                    'cadena_valor_comentario'   => $request->cadena_valor_requiere_comentario == false ? $request->cadena_valor_comentario : null
                ]);
                break;
            case $evaluacion->tpEvaluacion()->exists():
                $evaluacion->tpEvaluacion()->update([
                    'cadena_valor_comentario'   => $request->cadena_valor_requiere_comentario == false ? $request->cadena_valor_comentario : null
                ]);
                break;

            case $evaluacion->servicioTecnologicoEvaluacion()->exists():
                $evaluacion->servicioTecnologicoEvaluacion()->update([
                    'propuesta_sostenibilidad_puntaje'      => $request->propuesta_sostenibilidad_puntaje,
                    'propuesta_sostenibilidad_comentario'   => $request->propuesta_sostenibilidad_requiere_comentario == false ? $request->propuesta_sostenibilidad_comentario : null,

                    'impacto_ambiental_puntaje'      => $request->impacto_ambiental_puntaje,
                    'impacto_ambiental_comentario'   => $request->impacto_ambiental_requiere_comentario == false ? $request->impacto_ambiental_comentario : null,

                    'impacto_social_centro_puntaje'      => $request->impacto_social_centro_puntaje,
                    'impacto_social_centro_comentario'   => $request->impacto_social_centro_requiere_comentario == false ? $request->impacto_social_centro_comentario : null,

                    'impacto_social_productivo_puntaje'      => $request->impacto_social_productivo_puntaje,
                    'impacto_social_productivo_comentario'   => $request->impacto_social_productivo_requiere_comentario == false ? $request->impacto_social_productivo_comentario : null,

                    'impacto_tecnologico_puntaje'      => $request->impacto_tecnologico_puntaje,
                    'impacto_tecnologico_comentario'   => $request->impacto_social_productivo_requiere_comentario == false ? $request->impacto_tecnologico_comentario : null,
                ]);
                break;
            default:
                break;
        }

        $evaluacion->save();

        return back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    public function updatePropuestaSostenibilidad(Request $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        switch ($proyecto) {
            case $proyecto->idi()->exists():
                $request->validate([
                    'propuesta_sostenibilidad' => 'required|string|max:10000',
                ]);
                $idi                            = $proyecto->idi;
                $idi->propuesta_sostenibilidad  = $request->propuesta_sostenibilidad;

                $idi->save();
                break;
            case $proyecto->ta()->exists():
                $request->validate([
                    'propuesta_sostenibilidad_social'       => 'required|string|max:10000',
                    'propuesta_sostenibilidad_ambiental'    => 'required|string|max:10000',
                    'propuesta_sostenibilidad_financiera'   => 'required|string|max:10000',
                ]);
                $ta = $proyecto->ta;
                $ta->propuesta_sostenibilidad_social        = $request->propuesta_sostenibilidad_social;
                $ta->propuesta_sostenibilidad_ambiental     = $request->propuesta_sostenibilidad_ambiental;
                $ta->propuesta_sostenibilidad_financiera    = $request->propuesta_sostenibilidad_financiera;

                $ta->save();
                break;
            case $proyecto->tp()->exists():
                $request->validate([
                    'propuesta_sostenibilidad' => 'required|string|max:10000',
                ]);
                $tp                           = $proyecto->tp;
                $tp->propuesta_sostenibilidad = $request->propuesta_sostenibilidad;

                $tp->save();
                break;
            case $proyecto->culturaInnovacion()->exists():
                $request->validate([
                    'propuesta_sostenibilidad' => 'required|string|max:10000',
                ]);
                $culturaInnovacion                              = $proyecto->culturaInnovacion;
                $culturaInnovacion->propuesta_sostenibilidad    = $request->propuesta_sostenibilidad;

                $culturaInnovacion->save();
                break;
            case $proyecto->servicioTecnologico()->exists():
                $request->validate([
                    'propuesta_sostenibilidad' => 'required|string|max:10000',
                ]);
                $servicioTecnologico                            = $proyecto->servicioTecnologico;
                $servicioTecnologico->propuesta_sostenibilidad  = $request->propuesta_sostenibilidad;

                $servicioTecnologico->save();
                break;
            default:
                break;
        }

        return back()->with('success', 'El recurso se ha guardado correctamente.');
    }

    /**
     * Proyecto
     *
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function edit(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        switch ($proyecto) {
            case $proyecto->idi()->exists():
                return redirect()->route('convocatorias.idi.edit', [$convocatoria, $proyecto]);
                break;
            case $proyecto->ta()->exists():
                return redirect()->route('convocatorias.ta.edit', [$convocatoria, $proyecto]);
                break;
            case $proyecto->tp()->exists():
                return redirect()->route('convocatorias.tp.edit', [$convocatoria, $proyecto]);
                break;
            case $proyecto->servicioTecnologico()->exists():
                return redirect()->route('convocatorias.servicios-tecnologicos.edit', [$convocatoria, $proyecto]);
                break;
            case $proyecto->culturaInnovacion()->exists():
                return redirect()->route('convocatorias.cultura-innovacion.edit', [$convocatoria, $proyecto]);
                break;
            default:
                break;
        }
    }

    /**
     * Show summary.
     *
     * @param  \App\Models\Proyecto  $proyecto
     * @return \Illuminate\Http\Response
     */
    public function summary(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('visualizar-proyecto-autor', [$proyecto]);

        $proyecto->codigo_linea_programatica = $proyecto->lineaProgramatica->codigo;
        $proyecto->precio_proyecto           = $proyecto->precioProyecto;

        $proyecto->logs = $proyecto::getLog($proyecto->id);

        if ($proyecto->ta()->exists()) {
            $proyecto->max_valor_roles = $proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->max_valor_roles;
            $proyecto->max_valor_presupuesto = $proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->max_valor_presupuesto;
        }

        return Inertia::render('Convocatorias/Proyectos/Summary', [
            'convocatoria'              => $convocatoria->only('id', 'fase_formateada', 'fase', 'min_fecha_inicio_proyectos', 'max_fecha_finalizacion_proyectos'),
            'proyecto'                  => $proyecto->only('id', 'precio_proyecto', 'codigo_linea_programatica', 'logs', 'finalizado', 'modificable', 'a_evaluar', 'max_valor_roles', 'max_valor_presupuesto', 'mostrar_recomendaciones'),
            'problemaCentral'           => ProyectoValidationTrait::problemaCentral($proyecto),
            'efectosDirectos'           => ProyectoValidationTrait::efectosDirectos($proyecto),
            'causasIndirectas'          => ProyectoValidationTrait::causasIndirectas($proyecto),
            'causasDirectas'            => ProyectoValidationTrait::causasDirectas($proyecto),
            'efectosIndirectos'         => ProyectoValidationTrait::efectosIndirectos($proyecto),
            'objetivoGeneral'           => ProyectoValidationTrait::objetivoGeneral($proyecto),
            'resultados'                => ProyectoValidationTrait::resultados($proyecto),
            'objetivosEspecificos'      => ProyectoValidationTrait::objetivosEspecificos($proyecto),
            'actividades'               => ProyectoValidationTrait::actividades($proyecto),
            'impactos'                  => ProyectoValidationTrait::impactos($proyecto),
            // 'actividadesPresupuesto'    => ProyectoValidationTrait::actividadesPresupuesto($proyecto),
            'resultadoProducto'         => ProyectoValidationTrait::resultadoProducto($proyecto),
            'analisisRiesgo'            => ProyectoValidationTrait::analisisRiesgo($proyecto),
            'anexos'                    => ProyectoValidationTrait::anexos($proyecto),
            'generalidades'             => ProyectoValidationTrait::generalidades($proyecto),
            'metodologia'               => ProyectoValidationTrait::metodologia($proyecto),
            'propuestaSostenibilidad'   => ProyectoValidationTrait::propuestaSostenibilidad($proyecto),
            'productosActividades'      => ProyectoValidationTrait::productosActividades($proyecto),
            'articulacionSennova'       => ProyectoValidationTrait::articulacionSennova($proyecto),
            'soportesEstudioMercado'    => ProyectoValidationTrait::soportesEstudioMercado($proyecto),
            'estudiosMercadoArchivo'    => ProyectoValidationTrait::estudiosMercadoArchivo($proyecto),
            'edt'                       => ProyectoValidationTrait::edt($proyecto),
            'maxValorRoles'             => ProyectoValidationTrait::maxValorRoles($proyecto),
            'maxValorTAPresupuesto'     => ProyectoValidationTrait::maxValorTAPresupuesto($proyecto),
            'versiones'                 => $proyecto->PdfVersiones,
        ]);
    }

    /**
     * Show summaryEvaluacion.
     *
     * @param  \App\Models\Evaluacion  $evaluacion
     * @return \Illuminate\Http\Response
     */
    public function summaryEvaluacion(Convocatoria $convocatoria, Evaluacion $evaluacion)
    {
        $this->authorize('visualizar-evaluacion-autor', $evaluacion);

        $evaluacion->proyecto->codigo_linea_programatica = $evaluacion->proyecto->lineaProgramatica->codigo;
        $evaluacion->proyecto->precio_proyecto           = $evaluacion->proyecto->precioProyecto;

        $evaluacion->proyecto->logs = $evaluacion->proyecto::getLog($evaluacion->proyecto->id);

        switch ($evaluacion) {
            case $evaluacion->idiEvaluacion()->exists():
                $evaluacion->titulo_puntaje             = $evaluacion->idiEvaluacion->titulo_puntaje;
                $evaluacion->video_puntaje              = $evaluacion->idiEvaluacion->video_puntaje;
                $evaluacion->resumen_puntaje            = $evaluacion->idiEvaluacion->resumen_puntaje;
                $evaluacion->problema_central_puntaje   = $evaluacion->idiEvaluacion->problema_central_puntaje;
                $evaluacion->objetivos_puntaje          = $evaluacion->idiEvaluacion->objetivos_puntaje;
                $evaluacion->metodologia_puntaje        = $evaluacion->idiEvaluacion->metodologia_puntaje;
                $evaluacion->entidad_aliada_puntaje     = $evaluacion->idiEvaluacion->entidad_aliada_puntaje;
                $evaluacion->resultados_puntaje         = $evaluacion->idiEvaluacion->resultados_puntaje;
                $evaluacion->productos_puntaje          = $evaluacion->idiEvaluacion->productos_puntaje;
                $evaluacion->cadena_valor_puntaje       = $evaluacion->idiEvaluacion->cadena_valor_puntaje;
                $evaluacion->analisis_riesgos_puntaje   = $evaluacion->idiEvaluacion->analisis_riesgos_puntaje;
                $evaluacion->ortografia_puntaje         = $evaluacion->idiEvaluacion->ortografia_puntaje;
                $evaluacion->redaccion_puntaje          = $evaluacion->idiEvaluacion->redaccion_puntaje;
                $evaluacion->normas_apa_puntaje         = $evaluacion->idiEvaluacion->normas_apa_puntaje;
                break;
            case $evaluacion->culturaInnovacionEvaluacion()->exists():
                $evaluacion->titulo_puntaje             = $evaluacion->culturaInnovacionEvaluacion->titulo_puntaje;
                $evaluacion->video_puntaje              = $evaluacion->culturaInnovacionEvaluacion->video_puntaje;
                $evaluacion->resumen_puntaje            = $evaluacion->culturaInnovacionEvaluacion->resumen_puntaje;
                $evaluacion->problema_central_puntaje   = $evaluacion->culturaInnovacionEvaluacion->problema_central_puntaje;
                $evaluacion->objetivos_puntaje          = $evaluacion->culturaInnovacionEvaluacion->objetivos_puntaje;
                $evaluacion->metodologia_puntaje        = $evaluacion->culturaInnovacionEvaluacion->metodologia_puntaje;
                $evaluacion->entidad_aliada_puntaje     = $evaluacion->culturaInnovacionEvaluacion->entidad_aliada_puntaje;
                $evaluacion->resultados_puntaje         = $evaluacion->culturaInnovacionEvaluacion->resultados_puntaje;
                $evaluacion->productos_puntaje          = $evaluacion->culturaInnovacionEvaluacion->productos_puntaje;
                $evaluacion->cadena_valor_puntaje       = $evaluacion->culturaInnovacionEvaluacion->cadena_valor_puntaje;
                $evaluacion->analisis_riesgos_puntaje   = $evaluacion->culturaInnovacionEvaluacion->analisis_riesgos_puntaje;
                $evaluacion->ortografia_puntaje         = $evaluacion->culturaInnovacionEvaluacion->ortografia_puntaje;
                $evaluacion->redaccion_puntaje          = $evaluacion->culturaInnovacionEvaluacion->redaccion_puntaje;
                $evaluacion->normas_apa_puntaje         = $evaluacion->culturaInnovacionEvaluacion->normas_apa_puntaje;
                break;
            case $evaluacion->servicioTecnologicoEvaluacion()->exists():
                $evaluacion->titulo_puntaje = $evaluacion->servicioTecnologicoEvaluacion->titulo_puntaje;
                $evaluacion->resumen_puntaje = $evaluacion->servicioTecnologicoEvaluacion->resumen_puntaje;
                $evaluacion->antecedentes_puntaje = $evaluacion->servicioTecnologicoEvaluacion->antecedentes_puntaje;
                $evaluacion->justificacion_problema_puntaje = $evaluacion->servicioTecnologicoEvaluacion->justificacion_problema_puntaje;
                $evaluacion->pregunta_formulacion_problema_puntaje = $evaluacion->servicioTecnologicoEvaluacion->pregunta_formulacion_problema_puntaje;
                $evaluacion->propuesta_sostenibilidad_puntaje = $evaluacion->servicioTecnologicoEvaluacion->propuesta_sostenibilidad_puntaje;
                $evaluacion->identificacion_problema_puntaje = $evaluacion->servicioTecnologicoEvaluacion->identificacion_problema_puntaje;
                $evaluacion->arbol_problemas_puntaje = $evaluacion->servicioTecnologicoEvaluacion->arbol_problemas_puntaje;
                $evaluacion->impacto_ambiental_puntaje = $evaluacion->servicioTecnologicoEvaluacion->impacto_ambiental_puntaje;
                $evaluacion->impacto_social_centro_puntaje = $evaluacion->servicioTecnologicoEvaluacion->impacto_social_centro_puntaje;
                $evaluacion->impacto_social_productivo_puntaje = $evaluacion->servicioTecnologicoEvaluacion->impacto_social_productivo_puntaje;
                $evaluacion->impacto_tecnologico_puntaje = $evaluacion->servicioTecnologicoEvaluacion->impacto_tecnologico_puntaje;
                $evaluacion->objetivo_general_puntaje = $evaluacion->servicioTecnologicoEvaluacion->objetivo_general_puntaje;
                $evaluacion->primer_objetivo_puntaje = $evaluacion->servicioTecnologicoEvaluacion->primer_objetivo_puntaje;
                $evaluacion->segundo_objetivo_puntaje = $evaluacion->servicioTecnologicoEvaluacion->segundo_objetivo_puntaje;
                $evaluacion->tercer_objetivo_puntaje = $evaluacion->servicioTecnologicoEvaluacion->tercer_objetivo_puntaje;
                $evaluacion->cuarto_objetivo_puntaje = $evaluacion->servicioTecnologicoEvaluacion->cuarto_objetivo_puntaje;
                $evaluacion->resultados_primer_obj_puntaje = $evaluacion->servicioTecnologicoEvaluacion->resultados_primer_obj_puntaje;
                $evaluacion->resultados_segundo_obj_puntaje = $evaluacion->servicioTecnologicoEvaluacion->resultados_segundo_obj_puntaje;
                $evaluacion->resultados_tercer_obj_puntaje = $evaluacion->servicioTecnologicoEvaluacion->resultados_tercer_obj_puntaje;
                $evaluacion->resultados_cuarto_obj_puntaje = $evaluacion->servicioTecnologicoEvaluacion->resultados_cuarto_obj_puntaje;
                $evaluacion->metodologia_puntaje = $evaluacion->servicioTecnologicoEvaluacion->metodologia_puntaje;
                $evaluacion->actividades_primer_obj_puntaje = $evaluacion->servicioTecnologicoEvaluacion->actividades_primer_obj_puntaje;
                $evaluacion->actividades_segundo_obj_puntaje = $evaluacion->servicioTecnologicoEvaluacion->actividades_segundo_obj_puntaje;
                $evaluacion->actividades_tercer_obj_puntaje = $evaluacion->servicioTecnologicoEvaluacion->actividades_tercer_obj_puntaje;
                $evaluacion->actividades_cuarto_obj_puntaje = $evaluacion->servicioTecnologicoEvaluacion->actividades_cuarto_obj_puntaje;
                $evaluacion->productos_primer_obj_puntaje = $evaluacion->servicioTecnologicoEvaluacion->productos_primer_obj_puntaje;
                $evaluacion->productos_segundo_obj_puntaje = $evaluacion->servicioTecnologicoEvaluacion->productos_segundo_obj_puntaje;
                $evaluacion->productos_tercer_obj_puntaje = $evaluacion->servicioTecnologicoEvaluacion->productos_tercer_obj_puntaje;
                $evaluacion->productos_cuarto_obj_puntaje = $evaluacion->servicioTecnologicoEvaluacion->productos_cuarto_obj_puntaje;

                $evaluacion->riesgos_objetivo_general_puntaje = $evaluacion->servicioTecnologicoEvaluacion->riesgos_objetivo_general_puntaje;
                $evaluacion->riesgos_productos_puntaje = $evaluacion->servicioTecnologicoEvaluacion->riesgos_productos_puntaje;
                $evaluacion->riesgos_actividades_puntaje = $evaluacion->servicioTecnologicoEvaluacion->riesgos_actividades_puntaje;

                break;
            default:
                break;
        }

        return Inertia::render('Convocatorias/Evaluaciones/Summary', [
            'convocatoria' => $convocatoria->only('id', 'fase_formateada', 'fase', 'min_fecha_inicio_proyectos', 'max_fecha_finalizacion_proyectos', 'finalizado'),
            'evaluacion'   => $evaluacion,
            'proyecto'     => $evaluacion->proyecto->only('id', 'precio_proyecto', 'codigo_linea_programatica', 'logs', 'finalizado', 'modificable', 'a_evaluar'),
            'versiones'    => $evaluacion->proyecto->PdfVersiones,
        ]);
    }

    /**
     * Finalizar evaluación.
     *
     * @param  \App\Models\Proyecto  $proyecto
     * @return \Illuminate\Http\Response
     */
    public function finalizarEvaluacion(Request $request, Convocatoria $convocatoria, Evaluacion $evaluacion)
    {
        if (!Hash::check($request->password, Auth::user()->password)) {
            return back()
                ->withErrors(['password' => __('The password is incorrect.')]);
        }

        $evaluacion->iniciado = false;
        $evaluacion->finalizado = true;
        $evaluacion->save();

        Auth::user()->notify(new EvaluacionFinalizada($convocatoria, $evaluacion->proyecto));

        return back()->with('success', 'La evaluación ha sido finalizada correctamente.');
    }

    /**
     * Enviar el proyecto al dinamizador a cargo.
     *
     * @param  \App\Models\Proyecto  $proyecto
     * @return \Illuminate\Http\Response
     */
    public function finalizarProyecto(Request $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('visualizar-proyecto-autor', [$proyecto]);

        if (!Hash::check($request->password, Auth::user()->password)) {
            return back()
                ->withErrors(['password' => __('The password is incorrect.')]);
        }

        $proyecto->modificable = false;
        $proyecto->finalizado = true;
        $proyecto->save();

        $proyecto->centroFormacion->dinamizadorSennova->notify(new ProyectoFinalizado($convocatoria, $proyecto));

        $version = $proyecto->codigo . '-PDF-' . \Carbon\Carbon::now()->format('YmdHis');
        $proyecto->PdfVersiones()->save(new ProyectoPdfVersion(['version' => $version]));

        return back()->with('success', 'Se ha finalizado el proyecto correctamente.');
    }

    /**
     * Enviar proyecto a evaluación.
     *
     * @param  \App\Models\Proyecto  $proyecto
     * @return \Illuminate\Http\Response
     */
    public function enviarAEvaluacion(Request $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('validar-dinamizador', [$proyecto]);

        if (!Hash::check($request->password, Auth::user()->password)) {
            return back()
                ->withErrors(['password' => __('The password is incorrect.')]);
        }


        $proyecto->a_evaluar = true;
        $proyecto->finalizado = true;
        $proyecto->modificable = false;
        $proyecto->save();

        $user = $proyecto->participantes()->where('es_formulador', true)->first();
        $user->notify(new ProyectoConfirmado($proyecto, Auth::user()));

        return back()->with('success', 'Se ha confirmado el proyecto correctamente.');
    }

    /**
     * El dinamizador devuelve el proyecto al proponente con algún comentario.
     *
     * @param  \App\Models\Proyecto  $proyecto
     * @return \Illuminate\Http\Response
     */
    public function devolverProyecto(Request $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('validar-dinamizador', [$proyecto]);

        $proyecto->a_evaluar = false;
        $proyecto->finalizado = false;
        $proyecto->modificable = true;
        $proyecto->save();

        $user = $proyecto->participantes()->where('es_formulador', true)->first();
        $user->notify(new ComentarioProyecto($convocatoria, $proyecto, $request->comentario));

        return back()->with('success', 'Se ha notificado al proponente.');
    }

    /**
     * participantes
     *
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function participantes(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);

        $proyecto->codigo_linea_programatica = $proyecto->lineaProgramatica->codigo;
        $proyecto->participantes;
        $proyecto->programasFormacion;
        $proyecto->semillerosInvestigacion;

        if ($proyecto->codigo_linea_programatica == 70) {
            return redirect()->route('convocatorias.ta.edit', [$convocatoria, $proyecto])->with('error', 'Esta línea programática no requiere de participantes');
        }

        if ($proyecto->codigo_linea_programatica == 23 || $proyecto->codigo_linea_programatica == 65 || $proyecto->codigo_linea_programatica == 66 || $proyecto->codigo_linea_programatica == 82) {
            $rolesSennova = collect(json_decode(Storage::get('json/roles-sennova-idi.json'), true));
        } elseif ($proyecto->codigo_linea_programatica == 70) {
            $rolesSennova = collect(json_decode(Storage::get('json/roles-sennova-ta.json'), true));
        } elseif ($proyecto->codigo_linea_programatica == 69) {
            $rolesSennova = collect(json_decode(Storage::get('json/roles-sennova-tp.json'), true));
        } elseif ($proyecto->codigo_linea_programatica == 68) {
            $rolesSennova = collect(json_decode(Storage::get('json/roles-sennova-st.json'), true));
        }

        $proyecto->load('participantes.centroFormacion.regional');
        $proyecto->load('semillerosInvestigacion.lineaInvestigacion.grupoInvestigacion');

        return Inertia::render('Convocatorias/Proyectos/Participantes/Index', [
            'convocatoria'          => $convocatoria,
            'proyecto'              => $proyecto->only('id', 'codigo_linea_programatica', 'precio_proyecto', 'modificable', 'diff_meses', 'participantes', 'semillerosInvestigacion', 'mostrar_recomendaciones'),
            'tiposDocumento'        => json_decode(Storage::get('json/tipos-documento.json'), true),
            'tiposVinculacion'      => json_decode(Storage::get('json/tipos-vinculacion.json'), true),
            'roles'                 => $rolesSennova,
        ]);
    }

    /**
     * participantesEvaluacion
     *
     * @param  mixed $convocatoria
     * @param  mixed $evaluacion
     * @return void
     */
    public function participantesEvaluacion(Convocatoria $convocatoria, Evaluacion $evaluacion)
    {
        $this->authorize('visualizar-evaluacion-autor', $evaluacion);

        $evaluacion->proyecto->codigo_linea_programatica = $evaluacion->proyecto->lineaProgramatica->codigo;
        $evaluacion->proyecto->participantes;
        $evaluacion->proyecto->programasFormacion;
        $evaluacion->proyecto->semillerosInvestigacion;

        if ($evaluacion->proyecto->codigo_linea_programatica == 70) {
            return back()->with('error', 'Esta línea programática no requiere de participantes');
        }

        if ($evaluacion->proyecto->codigo_linea_programatica == 23 || $evaluacion->proyecto->codigo_linea_programatica == 65 || $evaluacion->proyecto->codigo_linea_programatica == 66 || $evaluacion->proyecto->codigo_linea_programatica == 82) {
            $rolesSennova = collect(json_decode(Storage::get('json/roles-sennova-idi.json'), true));
        } elseif ($evaluacion->proyecto->codigo_linea_programatica == 70) {
            $rolesSennova = collect(json_decode(Storage::get('json/roles-sennova-ta.json'), true));
        } elseif ($evaluacion->proyecto->codigo_linea_programatica == 69) {
            $rolesSennova = collect(json_decode(Storage::get('json/roles-sennova-tp.json'), true));
        } elseif ($evaluacion->proyecto->codigo_linea_programatica == 68) {
            $rolesSennova = collect(json_decode(Storage::get('json/roles-sennova-st.json'), true));
        }

        $evaluacion->proyecto->load('participantes.centroFormacion.regional');
        $evaluacion->proyecto->load('semillerosInvestigacion.lineaInvestigacion.grupoInvestigacion');

        return Inertia::render('Convocatorias/Evaluaciones/Participantes/Index', [
            'convocatoria'          => $convocatoria,
            'evaluacion'            => $evaluacion->only('id'),
            'proyecto'              => $evaluacion->proyecto->only('id', 'codigo_linea_programatica', 'precio_proyecto', 'finalizado', 'diff_meses', 'participantes', 'semillerosInvestigacion'),
            'tiposDocumento'        => json_decode(Storage::get('json/tipos-documento.json'), true),
            'tiposVinculacion'      => json_decode(Storage::get('json/tipos-vinculacion.json'), true),
            'roles'                 => $rolesSennova,
        ]);
    }

    /**
     * filterParticipantes
     *
     * @param  mixed $request
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function filterParticipantes(Request $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        if (!empty($request->search_participante)) {
            $query = User::orderBy('users.nombre', 'ASC')
                ->filterUser(['search' => $request->search_participante])
                ->with('centroFormacion.regional')->take(6);

            if ($proyecto->participantes->count() > 0) {
                $query->whereNotIn('users.id', explode(',', $proyecto->participantes->implode('id', ',')));
            }

            $users = $query->get()->take(5);

            return $users->makeHidden('can', 'roles', 'user_name', 'permissions')->toJson();
        }

        return null;
    }

    /**
     * linkParticipante
     *
     * @param  mixed $request
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function linkParticipante(ProponenteRequest $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $data = $request->only('cantidad_horas', 'cantidad_meses', 'rol_sennova');

        if (is_array($data['rol_sennova'])) {
            $data['rol_sennova'] = $data['rol_sennova']['value'];
        }

        try {
            if ($proyecto->participantes()->where('id', $request->user_id)->exists()) {
                return back()->with('error', 'El recurso ya está vinculado.');
            }

            $proyecto->participantes()->attach($request->user_id, $data);
            return back()->with('success', 'El recurso se ha vinculado correctamente.');
        } catch (\Throwable $th) {
            return back()->with('error', 'Oops! Algo salió mal.');
        }

        return back()->with('error', 'Oops! Algo salió mal.');
    }

    /**
     * unlinkParticipante
     *
     * @param  mixed $request
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function unlinkParticipante(Request $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $request->validate(['user_id' => 'required']);

        try {
            if ($proyecto->participantes()->where('id', $request->user_id)->exists()) {
                $proyecto->participantes()->detach($request->user_id);
                return back()->with('success', 'El recurso se ha desvinculado correctamente.');
            }
            return back()->with('success', 'El recurso ya está desvinculado.');
        } catch (\Throwable $th) {
            return back()->with('error', 'Oops! Algo salió mal.');
        }
        return back()->with('error', 'Oops! Algo salió mal.');
    }

    /**
     * updateParticipante
     *
     * @param  mixed $request
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function updateParticipante(ProponenteRequest $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $data = $request->only('cantidad_horas', 'cantidad_meses', 'rol_sennova');

        try {
            if ($proyecto->participantes()->where('id', $request->user_id)->exists()) {
                $proyecto->participantes()->updateExistingPivot($request->user_id, $data);
                return back()->with('success', 'El recurso se ha vinculado correctamente.');
            }
            return back()->with('error', 'El recurso ya está desvinculado.');
        } catch (\Throwable $th) {
            return back()->with('error', 'Oops! Algo salió mal.');
        }

        return back()->with('error', 'Oops! Algo salió mal.');
    }

    /**
     * registerParticipante
     *
     * @param  mixed $request
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function registerParticipante(NuevoProponenteRequest $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);

        $user = new User();

        $user->nombre               = $request->nombre;
        $user->email                = $request->email;
        $user->password             = $user::makePassword($request->numero_documento);
        $user->tipo_documento       = $request->tipo_documento;
        $user->numero_documento     = $request->numero_documento;
        $user->numero_celular       = $request->numero_celular;
        $user->habilitado           = 0;
        $user->tipo_vinculacion   = $request->tipo_vinculacion;
        $user->autorizacion_datos   = $request->autorizacion_datos;
        $user->centroFormacion()->associate($request->centro_formacion_id);

        $user->save();

        $user->assignRole(14);

        $data = $request->only('cantidad_horas', 'cantidad_meses', 'rol_sennova');
        $data['user_id'] = $user->id;

        return $this->linkParticipante(new ProponenteRequest($data), $convocatoria, $proyecto);
    }

    /**
     * filterSemillerosInvestigacion
     *
     * @param  mixed $request
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function filterSemillerosInvestigacion(Request $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        if (!empty($request->search_semillero_investigacion)) {
            $query = SemilleroInvestigacion::select('semilleros_investigacion.id', 'semilleros_investigacion.nombre', 'semilleros_investigacion.linea_investigacion_id')->orderBy('semilleros_investigacion.nombre', 'ASC')
                ->filterSemilleroInvestigacion(['search' => $request->search_semillero_investigacion])
                ->with('lineaInvestigacion.grupoInvestigacion');

            if ($proyecto->semillerosInvestigacion->count() > 0) {
                $query->whereNotIn('semilleros_investigacion.id', explode(',', $proyecto->semillerosInvestigacion->implode('id', ',')));
            }

            $semillerosInvestigacion = $query->get()->take(5);

            return $semillerosInvestigacion->makeHidden('created_at', 'updated_at')->toJson();
        }

        return null;
    }

    /**
     * linkSemilleroInvestigacion
     *
     * @param  mixed $request
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function linkSemilleroInvestigacion(Request $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $request->validate(['semillero_investigacion_id' => 'required']);

        try {
            if ($proyecto->semillerosInvestigacion()->where('id', $request->semillero_investigacion_id)->exists()) {
                return back()->with('error', 'El recurso ya está vinculado.');
            }
            $proyecto->semillerosInvestigacion()->attach($request->semillero_investigacion_id);
            return back()->with('success', 'El recurso se ha vinculado correctamente.');
        } catch (\Throwable $th) {
            return back()->with('error', 'Oops! Algo salió mal.');
        }

        return back()->with('error', 'Oops! Algo salió mal.');
    }

    /**
     * unlinkSemilleroInvestigacion
     *
     * @param  mixed $request
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function unlinkSemilleroInvestigacion(Request $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $request->validate(['semillero_investigacion_id' => 'required']);

        try {
            if ($proyecto->semillerosInvestigacion()->where('id', $request->semillero_investigacion_id)->exists()) {
                $proyecto->semillerosInvestigacion()->detach($request->semillero_investigacion_id);
                return back()->with('success', 'El recurso se ha desvinculado correctamente.');
            }
            return back()->with('success', 'El recurso ya está desvinculado.');
        } catch (\Throwable $th) {
            return back()->with('error', 'Oops! Algo salió mal.');
        }
        return back()->with('error', 'Oops! Algo salió mal.');
    }

    /**
     * filterProgramasFormacion
     *
     * @param  mixed $request
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function filterProgramasFormacion(Request $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        if (!empty($request->search_programa_formacion)) {
            $query = ProgramaFormacion::select('programas_formacion.id', 'programas_formacion.nombre', 'programas_formacion.codigo', 'programas_formacion.modalidad', 'programas_formacion.centro_formacion_id')->orderBy('programas_formacion.nombre', 'ASC')
                ->filterProgramaFormacion(['search' => $request->search_programa_formacion])
                ->with('centroFormacion.regional');

            if ($proyecto->programasFormacion->count() > 0) {
                $query->whereNotIn('programas_formacion.id', explode(',', $proyecto->programasFormacion->implode('id', ',')));
            }

            $programasFormacion = $query->get()->take(5);

            return $programasFormacion->makeHidden('created_at', 'updated_at')->toJson();
        }

        return null;
    }

    /**
     * linkProgramaFormacion
     *
     * @param  mixed $request
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function linkProgramaFormacion(Request $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $request->validate(['programa_formacion_id' => 'required']);

        try {
            if ($proyecto->programasFormacion()->where('id', $request->programa_formacion_id)->exists()) {
                return back()->with('error', 'El recurso ya está vinculado.');
            }
            $proyecto->programasFormacion()->attach($request->programa_formacion_id);
            return back()->with('success', 'El recurso se ha vinculado correctamente.');
        } catch (\Throwable $th) {
            return back()->with('error', 'Oops! Algo salió mal.');
        }

        return back()->with('error', 'Oops! Algo salió mal.');
    }

    /**
     * unlinkProgramaFormacion
     *
     * @param  mixed $request
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function unlinkProgramaFormacion(Request $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $request->validate(['programa_formacion_id' => 'required']);

        try {
            if ($proyecto->programasFormacion()->where('id', $request->programa_formacion_id)->exists()) {
                $proyecto->programasFormacion()->detach($request->programa_formacion_id);
                return back()->with('success', 'El recurso se ha desvinculado correctamente.');
            }
            return back()->with('success', 'El recurso ya está desvinculado.');
        } catch (\Throwable $th) {
            return back()->with('error', 'Oops! Algo salió mal.');
        }
        return back()->with('error', 'Oops! Algo salió mal.');
    }

    /**
     * downloadManualUsuario
     *
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @param  mixed $proyectoAnexo
     * @return void
     */
    public function downloadManualUsuario()
    {
        return response()->download(storage_path("app/manual-usuario/Manual_de_usuario.pdf"));
    }

    /**
     * storeProgramaFormacion
     *
     * @param  mixed $request
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function storeProgramaFormacion(ProgramaFormacionRequest $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $programaFormacion = new ProgramaFormacion();
        $programaFormacion->nombre              = $request->nombre;
        $programaFormacion->codigo              = $request->codigo;
        $programaFormacion->modalidad           = $request->modalidad;
        $programaFormacion->nivel_formacion     = $request->nivel_formacion;
        $programaFormacion->centroFormacion()->associate($request->centro_formacion_id);

        $programaFormacion->save();

        if ($proyecto->ta()->exists()) {
            $proyecto->taProgramasFormacion()->attach($programaFormacion);
        }

        return back()->with('success', 'El recurso se ha creado correctamente.');
    }

    /**  
     * descargarPdf
     *
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @param  mixed $version
     * @return void
     */
    public function descargarPdf(Convocatoria $convocatoria, Proyecto $proyecto, $version)
    {
        return response()->download(storage_path("app/convocatorias/" . $convocatoria->id . "/" . $proyecto->id . "/" . $version . ".pdf"));
    }

    /**
     * showComentariosGeneralesForm
     *
     * @param  mixed $convocatoria
     * @param  mixed $evaluacion
     * @return void
     */
    public function showComentariosGeneralesForm(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);

        $proyecto->codigo_linea_programatica = $proyecto->lineaProgramatica->codigo;

        $proyecto->evaluaciones->load('evaluacionCausalesRechazo');

        return Inertia::render('Convocatorias/Proyectos/ComentariosGenerales', [
            'convocatoria'                  => $convocatoria->only('id', 'fase_formateada', 'fase'),
            'evaluaciones'                  => $proyecto->evaluaciones,
            'proyecto'                      => $proyecto,
        ]);
    }

    /**
     * udpdateComentariosGenerales
     *
     * @param  mixed $convocatoria
     * @param  mixed $evaluacion
     * @return void
     */
    public function udpdateComentariosGenerales(Request $request, Convocatoria $convocatoria, $evaluacion)
    {
        $evaluacion = Evaluacion::find($evaluacion);

        $this->authorize('modificar-proyecto-autor', $evaluacion->proyecto);

        $evaluacion->update(
            ['evaluacion_id' => $evaluacion->id, 'replicas' => $request->replicas],
        );

        return back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    public function udpdatePrecioProyecto()
    {
        $proyectos = Proyecto::all();

        foreach ($proyectos as $proyecto) {
            $proyecto->update(['precio_proyecto' => $proyecto->precio_proyecto]);
        }

        return back()->with('success', 'El recurso se ha actualizado correctamente.');
    }
}
