<?php

namespace App\Http\Controllers;

use App\Http\Requests\NuevoProponenteRequest;
use App\Http\Requests\ProponenteRequest;
use App\Http\Traits\ProyectoValidationTrait;
use App\Models\Convocatoria;
use App\Models\Evaluacion\Evaluacion;
use App\Models\Impacto;
use App\Models\User;
use App\Models\ProgramaFormacion;
use App\Models\Proyecto;
use App\Models\SemilleroInvestigacion;
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
     * showCadenaValor
     *
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function showCadenaValor(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);

        $proyecto->load('evaluaciones.idiEvaluacion');

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
                    $productos->prepend(['v' => 'prod' . $producto->id,  'f' => $producto->nombre, 'fkey' =>  $resultado->objetivoEspecifico->numero, 'tootlip' => 'prod' . $producto->id, 'actividades' => $producto->actividades]);
                }
            }
        }

        return Inertia::render('Convocatorias/Proyectos/CadenaValor/Index', [
            'convocatoria'  => $convocatoria->only('id'),
            'proyecto'      => $proyecto->only('id', 'codigo_linea_programatica', 'precio_proyecto', 'propuesta_sostenibilidad', 'propuesta_sostenibilidad_social', 'propuesta_sostenibilidad_ambiental', 'propuesta_sostenibilidad_financiera', 'modificable', 'en_subsanacion', 'evaluaciones'),
            'productos'     => $productos,
            'objetivos'     => $objetivos
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
        $evaluacion->proyecto->codigo_linea_programatica = $evaluacion->proyecto->lineaProgramatica->codigo;

        $efectosIndirectos = $evaluacion->proyecto->efectosDirectos()->with('efectosIndirectos')->get()->pluck('efectosIndirectos')->flatten()->filter();

        if ($evaluacion->proyecto->idi()->exists()) {
            $objetivoGeneral = $evaluacion->proyecto->idi->objetivo_general;
            $evaluacion->proyecto->propuesta_sostenibilidad = $evaluacion->proyecto->idi->propuesta_sostenibilidad;
            $evaluacion->idiEvaluacion;
        }

        if ($evaluacion->proyecto->ta()->exists()) {
            $objetivoGeneral = $evaluacion->proyecto->ta->objetivo_general;
            $evaluacion->proyecto->propuesta_sostenibilidad_social      = $evaluacion->proyecto->ta->propuesta_sostenibilidad_social;
            $evaluacion->proyecto->propuesta_sostenibilidad_ambiental   = $evaluacion->proyecto->ta->propuesta_sostenibilidad_ambiental;
            $evaluacion->proyecto->propuesta_sostenibilidad_financiera  = $evaluacion->proyecto->ta->propuesta_sostenibilidad_financiera;
        }

        if ($evaluacion->proyecto->tp()->exists()) {
            $objetivoGeneral = $evaluacion->proyecto->tp->objetivo_general;
            $evaluacion->proyecto->propuesta_sostenibilidad = $evaluacion->proyecto->tp->propuesta_sostenibilidad;
        }

        if ($evaluacion->proyecto->servicioTecnologico()->exists()) {
            $objetivoGeneral = $evaluacion->proyecto->servicioTecnologico->objetivo_general;
            $evaluacion->proyecto->propuesta_sostenibilidad = $evaluacion->proyecto->servicioTecnologico->propuesta_sostenibilidad;
        }

        if ($evaluacion->proyecto->culturaInnovacion()->exists()) {
            $objetivoGeneral = $evaluacion->proyecto->culturaInnovacion->objetivo_general;
            $evaluacion->proyecto->propuesta_sostenibilidad = $evaluacion->proyecto->culturaInnovacion->propuesta_sostenibilidad;
            $evaluacion->culturaInnovacionEvaluacion;
        }

        $objetivos = collect(['Objetivo general' => $objetivoGeneral]);
        $productos = collect([]);

        foreach ($evaluacion->proyecto->causasDirectas as $causaDirecta) {
            $objetivos->prepend($causaDirecta->objetivoEspecifico->descripcion, $causaDirecta->objetivoEspecifico->numero);
        }

        foreach ($evaluacion->proyecto->efectosDirectos as $efectoDirecto) {
            foreach ($efectoDirecto->resultados as $resultado) {
                foreach ($resultado->productos as $producto) {
                    $productos->prepend(['v' => 'prod' . $producto->id,  'f' => $producto->nombre, 'fkey' =>  $resultado->objetivoEspecifico->numero, 'tootlip' => 'prod' . $producto->id, 'actividades' => $producto->actividades]);
                }
            }
        }

        return Inertia::render('Convocatorias/Evaluaciones/CadenaValor/Index', [
            'convocatoria'  => $convocatoria->only('id'),
            'evaluacion'    => $evaluacion,
            'proyecto'      => $evaluacion->proyecto->only('id', 'codigo_linea_programatica', 'precio_proyecto', 'propuesta_sostenibilidad', 'propuesta_sostenibilidad_social', 'propuesta_sostenibilidad_ambiental', 'propuesta_sostenibilidad_financiera', 'finalizado'),
            'productos'     => $productos,
            'objetivos'     => $objetivos,
            'impactos'      => Impacto::whereIn(
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
        switch ($evaluacion) {
            case $evaluacion->idiEvaluacion()->exists():
                $evaluacion->idiEvaluacion()->update([
                    'cadena_valor_puntaje'      => $request->cadena_valor_puntaje,
                    'cadena_valor_comentario'   => $request->cadena_valor_requiere_comentario == true ? $request->cadena_valor_comentario : null
                ]);
                break;
            case $evaluacion->culturaInnovacionEvaluacion()->exists():
                $evaluacion->culturaInnovacionEvaluacion()->update([
                    'cadena_valor_puntaje'      => $request->cadena_valor_puntaje,
                    'cadena_valor_comentario'   => $request->cadena_valor_requiere_comentario == true ? $request->cadena_valor_comentario : null
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
     * edit
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
            'convocatoria'              => $convocatoria->only('id', 'min_fecha_inicio_proyectos', 'max_fecha_finalizacion_proyectos'),
            'proyecto'                  => $proyecto->only('id', 'precio_proyecto', 'codigo_linea_programatica', 'logs', 'finalizado', 'modificable', 'a_evaluar', 'max_valor_roles', 'max_valor_presupuesto'),
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
            'edt'                       => ProyectoValidationTrait::edt($proyecto),
            'maxValorRoles'             => ProyectoValidationTrait::maxValorRoles($proyecto),
            'maxValorTAPresupuesto'     => ProyectoValidationTrait::maxValorTAPresupuesto($proyecto),
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
                break;
            default:
                break;
        }

        return Inertia::render('Convocatorias/Evaluaciones/Summary', [
            'convocatoria' => $convocatoria->only('id', 'min_fecha_inicio_proyectos', 'max_fecha_finalizacion_proyectos', 'finalizado'),
            'evaluacion'   => $evaluacion,
            'proyecto'     => $evaluacion->proyecto->only('id', 'precio_proyecto', 'codigo_linea_programatica', 'logs', 'finalizado', 'modificable', 'a_evaluar'),
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
            'proyecto'              => $proyecto->only('id', 'codigo_linea_programatica', 'precio_proyecto', 'modificable', 'diff_meses', 'participantes', 'semillerosInvestigacion'),
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
}
