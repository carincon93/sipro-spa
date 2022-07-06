<?php

namespace App\Http\Controllers;

use App\Models\AmbienteModernizacion;
use App\Http\Controllers\Controller;
use App\Http\Requests\AmbienteModernizacionRequest;
use App\Http\Requests\EquipoAmbienteModernizacionRequest;
use App\Models\CodigoProyectoSgps;
use App\Models\EquipoAmbienteModernizacion;
use App\Models\MesaSectorial;
use App\Models\SeguimientoAmbienteModernizacion;
use App\Models\SemilleroInvestigacion;
use App\Models\TipologiaAmbiente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PDF;

class AmbienteModernizacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', [AmbienteModernizacion::class]);

        $authUser = auth()->user();
        $codigosSgps = CodigoProyectoSgps::select('seguimientos_ambiente_modernizacion.codigo_proyecto_sgps_id', 'codigos_proyectos_sgps.codigo_sgps', 'codigos_proyectos_sgps.titulo', 'codigos_proyectos_sgps.year_ejecucion')->leftJoin('seguimientos_ambiente_modernizacion', 'codigos_proyectos_sgps.id', 'seguimientos_ambiente_modernizacion.codigo_proyecto_sgps_id')
            ->join('lineas_programaticas', 'codigos_proyectos_sgps.linea_programatica_id', 'lineas_programaticas.id')
            ->where('lineas_programaticas.codigo', 23)
            ->where('codigos_proyectos_sgps.centro_formacion_id', $authUser->centro_formacion_id)
            ->where('seguimientos_ambiente_modernizacion.codigo_proyecto_sgps_id', NULL)
            ->get();

        return Inertia::render('AmbientesModernizacion/Index', [
            'filters'                   => request()->all('search'),
            'ambientesModernizacion'    => AmbienteModernizacion::distinct('seguimiento_ambiente_modernizacion_id')->with('seguimientoAmbienteModernizacion.ambientesModernizacion', 'seguimientoAmbienteModernizacion.centroFormacion.regional')->orderBy('seguimiento_ambiente_modernizacion_id', 'ASC')
                ->filterAmbienteModernizacion(request()->only('search'))->paginate(),
            'codigosSgpsFaltantes'      => $codigosSgps
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('create', [AmbienteModernizacion::class]);

        $seguimientoId = str_replace('=', '', $request->seguimiento_id);
        $ambienteModernizacion = $seguimientoId ? AmbienteModernizacion::where('seguimiento_ambiente_modernizacion_id', $seguimientoId)->orderBy('created_at', 'DESC')->first() : null;
        if ($ambienteModernizacion) {
            // Se hace el clonado y se redirige al edit
            DB::table('ambientes_modernizacion')->where('seguimiento_ambiente_modernizacion_id', $seguimientoId)->update(['finalizado' => true]);
            $nuevoSeguimientoAmbiente = $this->replicateRow($ambienteModernizacion);
            $nuevoSeguimientoAmbiente->update(['finalizado' => false]);
            $this->generatePdfAmbienteModernizacion($ambienteModernizacion);

            return redirect()->route('ambientes-modernizacion.edit', $nuevoSeguimientoAmbiente)->with('success', 'Se ha generado un nuevo seguimiento.');
        }

        $authUser = auth()->user();
        $codigosSgps = CodigoProyectoSgps::selectRaw('codigos_proyectos_sgps.id as value, concat(codigos_proyectos_sgps.titulo, chr(10), \'∙ Código: SGPS-\', codigos_proyectos_sgps.codigo_sgps, chr(10), \'∙ Año: \', codigos_proyectos_sgps.year_ejecucion) as label')->leftJoin('seguimientos_ambiente_modernizacion', 'codigos_proyectos_sgps.id', 'seguimientos_ambiente_modernizacion.codigo_proyecto_sgps_id')
            ->join('lineas_programaticas', 'codigos_proyectos_sgps.linea_programatica_id', 'lineas_programaticas.id')
            ->where('lineas_programaticas.codigo', 23)
            ->where('codigos_proyectos_sgps.centro_formacion_id', $authUser->centro_formacion_id)
            ->where('seguimientos_ambiente_modernizacion.codigo_proyecto_sgps_id', NULL)
            ->get();

        return Inertia::render('AmbientesModernizacion/Create', [
            'centroFormacionId'         => $authUser->centro_formacion_id,
            'seguimientoId'             => $seguimientoId,
            'codigosSgps'               => $codigosSgps,
            'tipologiasAmbientes'       => TipologiaAmbiente::select('tipologias_ambientes.id as value', 'tipologias_ambientes.tipo as label')->orderBy('tipologias_ambientes.tipo', 'ASC')->get(),
            'mesasSectoriales'          => MesaSectorial::select('id', 'nombre')->get('id'),
            'semillerosInvestigacion'   => SemilleroInvestigacion::select('semilleros_investigacion.nombre as label', 'semilleros_investigacion.id as value')->join('lineas_investigacion', 'semilleros_investigacion.linea_investigacion_id', 'lineas_investigacion.id')->join('grupos_investigacion', 'lineas_investigacion.grupo_investigacion_id', 'grupos_investigacion.id')->where('grupos_investigacion.centro_formacion_id', '=', auth()->user()->centroFormacion->id)->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AmbienteModernizacionRequest $request)
    {
        $this->authorize('create', [AmbienteModernizacion::class]);

        $codigoProyectoSgps = CodigoProyectoSgps::find($request->codigo_proyecto_sgps_id);

        $seguimientoAmbienteModernizacion = new SeguimientoAmbienteModernizacion();

        $seguimientoAmbienteModernizacion->centroFormacion()->associate($codigoProyectoSgps->centro_formacion_id);
        $seguimientoAmbienteModernizacion->codigoProyectoSgps()->associate($request->codigo_proyecto_sgps_id);

        $seguimientoAmbienteModernizacion->save();

        $ambienteModernizacion = new AmbienteModernizacion();
        $ambienteModernizacion->nombre_ambiente                     = $request->nombre_ambiente;
        $ambienteModernizacion->alineado_mesas_sectoriales          = $request->alineado_mesas_sectoriales;
        $ambienteModernizacion->financiado_anteriormente            = $request->financiado_anteriormente;
        $ambienteModernizacion->estado_general_maquinaria           = $request->estado_general_maquinaria;
        $ambienteModernizacion->razon_estado_general                = $request->razon_estado_general;
        $ambienteModernizacion->ambiente_activo                     = $request->ambiente_activo;
        $ambienteModernizacion->justificacion_ambiente_inactivo     = $request->justificacion_ambiente_inactivo;
        $ambienteModernizacion->ambiente_activo_procesos_idi        = $request->ambiente_activo_procesos_idi;
        $ambienteModernizacion->numero_proyectos_beneficiados       = $request->numero_proyectos_beneficiados;
        $ambienteModernizacion->ambiente_formacion_complementaria   = $request->ambiente_formacion_complementaria;
        $ambienteModernizacion->numero_total_cursos_comp            = $request->numero_total_cursos_comp;
        $ambienteModernizacion->numero_cursos_empresas              = $request->numero_cursos_empresas;
        $ambienteModernizacion->datos_empresa                       = $request->datos_empresa;
        $ambienteModernizacion->cursos_complementarios              = $request->cursos_complementarios;
        $ambienteModernizacion->coordenada_latitud_ambiente         = $request->coordenada_latitud_ambiente;
        $ambienteModernizacion->coordenada_longitud_ambiente        = $request->coordenada_longitud_ambiente;
        $ambienteModernizacion->impacto_procesos_formacion          = $request->impacto_procesos_formacion;
        $ambienteModernizacion->pertinencia_sector_productivo       = $request->pertinencia_sector_productivo;
        $ambienteModernizacion->palabras_clave_ambiente             = $request->palabras_clave_ambiente;
        $ambienteModernizacion->observaciones_generales_ambiente    = $request->observaciones_generales_ambiente;
        $ambienteModernizacion->soporte_fotos_ambiente              = $request->soporte_fotos_ambiente;

        $ambienteModernizacion->redConocimiento()->associate($request->red_conocimiento_id);
        $ambienteModernizacion->lineaInvestigacion()->associate($request->linea_investigacion_id);
        $ambienteModernizacion->disciplinaSubareaConocimiento()->associate($request->disciplina_subarea_conocimiento_id);
        $ambienteModernizacion->tematicaEstrategica()->associate($request->tematica_estrategica_id);
        $ambienteModernizacion->tipologiaAmbiente()->associate($request->tipologia_ambiente_id);
        $ambienteModernizacion->actividadEconomica()->associate($request->actividad_economica_id);
        $ambienteModernizacion->dinamizadorSennova()->associate(auth()->user()->id);
        $ambienteModernizacion->seguimientoAmbienteModernizacion()->associate($seguimientoAmbienteModernizacion->id);

        $ambienteModernizacion->save();

        $ambienteModernizacion->mesasSectoriales()->sync($request->mesa_sectorial_id);
        $ambienteModernizacion->codigosProyectosSgps()->sync($request->codigos_proyectos_id);
        $ambienteModernizacion->programasFormacionCalificados()->sync($request->programas_formacion_calificados);
        $ambienteModernizacion->programasFormacionNoCalificados()->sync($request->programas_formacion);
        $ambienteModernizacion->semillerosInvestigacion()->sync($request->semilleros_investigacion_id);

        return redirect()->route('ambientes-modernizacion.edit', $ambienteModernizacion)->with('success', 'El recurso se ha creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AmbienteModernizacion  $ambienteModernizacion
     * @return \Illuminate\Http\Response
     */
    public function show(AmbienteModernizacion $ambienteModernizacion)
    {
        $this->authorize('view', [AmbienteModernizacion::class, $ambienteModernizacion]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AmbienteModernizacion  $ambienteModernizacion
     * @return \Illuminate\Http\Response
     */
    public function edit(AmbienteModernizacion $ambienteModernizacion)
    {
        $this->authorize('update', [AmbienteModernizacion::class, $ambienteModernizacion]);

        $ambienteModernizacion->seguimientoAmbienteModernizacion;
        $ambienteModernizacion->disciplinaSubareaConocimiento->subareaConocimiento->areaConocimiento;
        $ambienteModernizacion->seguimientoAmbienteModernizacion->centroFormacion->regional;

        $codigosSgps = [];

        $authUser = auth()->user();
        if ($authUser->hasRole([4])) {
            $codigosSgps = CodigoProyectoSgps::selectRaw('codigos_proyectos_sgps.id as value, concat(codigos_proyectos_sgps.titulo, chr(10), \'∙ Código: SGPS-\', codigos_proyectos_sgps.codigo_sgps, chr(10), \'∙ Año: \', codigos_proyectos_sgps.year_ejecucion) as label')->join('lineas_programaticas', 'codigos_proyectos_sgps.linea_programatica_id', 'lineas_programaticas.id')->where('lineas_programaticas.codigo', 23)->where('codigos_proyectos_sgps.centro_formacion_id', $authUser->centro_formacion_id)->orderBy('codigos_proyectos_sgps.codigo_sgps', 'ASC')->get();
        } else {
            $codigosSgps = CodigoProyectoSgps::selectRaw('codigos_proyectos_sgps.id as value, concat(codigos_proyectos_sgps.titulo, chr(10), \'∙ Código: SGPS-\', codigos_proyectos_sgps.codigo_sgps, chr(10), \'∙ Año: \', codigos_proyectos_sgps.year_ejecucion) as label')->join('lineas_programaticas', 'codigos_proyectos_sgps.linea_programatica_id', 'lineas_programaticas.id')->where('lineas_programaticas.codigo', 23)->orderBy('codigos_proyectos_sgps.codigo_sgps', 'ASC')->get();
        }

        return Inertia::render('AmbientesModernizacion/Edit', [
            'centroFormacionId'                             => $authUser->centro_formacion_id,
            'ambienteModernizacion'                         => $ambienteModernizacion,
            'codigosSgps'                                   => $codigosSgps,
            'tipologiasAmbientes'                           => TipologiaAmbiente::select('tipologias_ambientes.id as value', 'tipologias_ambientes.tipo as label')->orderBy('tipologias_ambientes.tipo', 'ASC')->get(),
            'mesasSectoriales'                              => MesaSectorial::select('id', 'nombre')->get('id'),
            'semillerosInvestigacion'                       => SemilleroInvestigacion::select('semilleros_investigacion.nombre as label', 'semilleros_investigacion.id as value')->join('lineas_investigacion', 'semilleros_investigacion.linea_investigacion_id', 'lineas_investigacion.id')->join('grupos_investigacion', 'lineas_investigacion.grupo_investigacion_id', 'grupos_investigacion.id')->where('grupos_investigacion.centro_formacion_id', '=', auth()->user()->centroFormacion->id)->get(),
            'codigosProyectosRelacionados'                  => $ambienteModernizacion->codigosProyectosSgps()->selectRaw('codigos_proyectos_sgps.id as value, concat(codigos_proyectos_sgps.titulo, chr(10), \'∙ Código: \', codigos_proyectos_sgps.codigo_sgps) as label')->get(),
            'programasFormacionCalificadosRelacionados'     => $ambienteModernizacion->programasFormacionCalificados()->selectRaw('programas_formacion.id as value, concat(programas_formacion.nombre, chr(10), \'∙ Código: \', programas_formacion.codigo) as label')->get(),
            'programasFormacionNoCalificadosRelacionados'   => $ambienteModernizacion->programasFormacionNoCalificados()->selectRaw('programas_formacion_articulados.id as value, concat(programas_formacion_articulados.nombre, chr(10), \'∙ Código: \', programas_formacion_articulados.codigo) as label')->get(),
            'semillerosRelacionados'                        => $ambienteModernizacion->semillerosInvestigacion()->select('semilleros_investigacion.nombre as label', 'semilleros_investigacion.id as value')->get(),
            'mesasSectorialesRelacionadas'                  => $ambienteModernizacion->mesasSectoriales()->pluck('id'),
            'equiposAmbienteModernizacion'                  => EquipoAmbienteModernizacion::where('ambiente_modernizacion_id', $ambienteModernizacion->id)->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AmbienteModernizacion  $ambienteModernizacion
     * @return \Illuminate\Http\Response
     */
    public function update(AmbienteModernizacionRequest $request, AmbienteModernizacion $ambienteModernizacion)
    {
        $this->authorize('update', [AmbienteModernizacion::class, $ambienteModernizacion]);

        $ambienteModernizacion->alineado_mesas_sectoriales          = $request->alineado_mesas_sectoriales;
        $ambienteModernizacion->financiado_anteriormente            = $request->financiado_anteriormente;
        $ambienteModernizacion->estado_general_maquinaria           = $request->estado_general_maquinaria;
        $ambienteModernizacion->razon_estado_general                = $request->estado_general_maquinaria == '1' ? null : $request->razon_estado_general;
        $ambienteModernizacion->ambiente_activo                     = $request->ambiente_activo;
        $ambienteModernizacion->justificacion_ambiente_inactivo     = $request->ambiente_activo ? null : $request->justificacion_ambiente_inactivo;
        $ambienteModernizacion->ambiente_activo_procesos_idi        = $request->ambiente_activo_procesos_idi;
        $ambienteModernizacion->numero_proyectos_beneficiados       = $request->ambiente_activo_procesos_idi == 1 ? $request->numero_proyectos_beneficiados : 0;
        $ambienteModernizacion->ambiente_formacion_complementaria   = $request->ambiente_formacion_complementaria;
        $ambienteModernizacion->numero_total_cursos_comp            = $request->ambiente_formacion_complementaria == 1 ? $request->numero_total_cursos_comp : 0;
        $ambienteModernizacion->numero_cursos_empresas              = $request->ambiente_formacion_complementaria == 1 ? $request->numero_cursos_empresas : 0;
        $ambienteModernizacion->datos_empresa                       = $request->ambiente_formacion_complementaria == 1 ? $request->datos_empresa : null;
        $ambienteModernizacion->cursos_complementarios              = $request->ambiente_formacion_complementaria == 1 ? $request->cursos_complementarios : null;
        $ambienteModernizacion->coordenada_latitud_ambiente         = $request->coordenada_latitud_ambiente;
        $ambienteModernizacion->coordenada_longitud_ambiente        = $request->coordenada_longitud_ambiente;
        $ambienteModernizacion->palabras_clave_ambiente             = $request->palabras_clave_ambiente;
        $ambienteModernizacion->soporte_fotos_ambiente              = $request->soporte_fotos_ambiente;

        $ambienteModernizacion->numero_personas_certificadas        = $request->numero_personas_certificadas;
        $ambienteModernizacion->numero_tecnicas_tecnologias         = $request->numero_tecnicas_tecnologias;
        $ambienteModernizacion->numero_publicaciones                = $request->numero_publicaciones;
        $ambienteModernizacion->numero_aprendices_beneficiados      = $request->numero_aprendices_beneficiados;

        $ambienteModernizacion->cod_proyectos_beneficiados          = $request->cod_proyectos_beneficiados;

        $ambienteModernizacion->redConocimiento()->associate($request->red_conocimiento_id);
        $ambienteModernizacion->lineaInvestigacion()->associate($request->linea_investigacion_id);
        $ambienteModernizacion->disciplinaSubareaConocimiento()->associate($request->disciplina_subarea_conocimiento_id);
        $ambienteModernizacion->tematicaEstrategica()->associate($request->tematica_estrategica_id);
        $ambienteModernizacion->tipologiaAmbiente()->associate($request->tipologia_ambiente_id);
        $ambienteModernizacion->actividadEconomica()->associate($request->actividad_economica_id);
        $ambienteModernizacion->dinamizadorSennova()->associate(auth()->user()->id);

        $ambienteModernizacion->save();

        $ambienteModernizacion->seguimientoAmbienteModernizacion()->update(['codigo_proyecto_sgps_id' => $request->codigo_proyecto_sgps_id]);

        $request->alineado_mesas_sectoriales == 1 ? $ambienteModernizacion->mesasSectoriales()->sync($request->mesa_sectorial_id) : $ambienteModernizacion->mesasSectoriales()->detach();
        $request->financiado_anteriormente == 1 ? $ambienteModernizacion->codigosProyectosSgps()->sync($request->codigos_proyectos_id) : $ambienteModernizacion->codigosProyectosSgps()->detach();
        $request->ambiente_activo == 1 ? $ambienteModernizacion->programasFormacionCalificados()->sync($request->programas_formacion_calificados) : $ambienteModernizacion->programasFormacionCalificados()->detach();
        $request->ambiente_activo == 1 ? $ambienteModernizacion->programasFormacionNoCalificados()->sync($request->programas_formacion) : $ambienteModernizacion->programasFormacionNoCalificados()->detach();
        $request->ambiente_activo_procesos_idi == 1 ? $ambienteModernizacion->semillerosInvestigacion()->sync($request->semilleros_investigacion_id) : $ambienteModernizacion->semillerosInvestigacion()->detach();

        return redirect()->back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AmbienteModernizacion  $ambienteModernizacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(AmbienteModernizacion $ambienteModernizacion)
    {
        $this->authorize('delete', [AmbienteModernizacion::class, $ambienteModernizacion]);

        $ambienteModernizacion->delete();

        return redirect()->route('ambientes-modernizacion.index')->with('success', 'El recurso se ha eliminado correctamente.');
    }

    /**
     * download
     *
     * @param  mixed $ambienteModernizacion
     * @return void
     */
    public function download(AmbienteModernizacion $ambienteModernizacion)
    {
        $this->authorize('update', $ambienteModernizacion);

        $path = $ambienteModernizacion->soporte_fotos_ambiente;

        return response()->download(storage_path("app/$path"));
    }

    public function equiposStore(EquipoAmbienteModernizacionRequest $request, AmbienteModernizacion $ambienteModernizacion)
    {
        $message = '';

        if ($request->id) {
            $equipoAmbienteModernizacion = EquipoAmbienteModernizacion::find($request->id);
            $equipoAmbienteModernizacion->numero_inventario_equipo      = $request->numero_inventario_equipo;
            $equipoAmbienteModernizacion->nombre_equipo                 = $request->nombre_equipo;
            $equipoAmbienteModernizacion->descripcion_tecnica_equipo    = $request->descripcion_tecnica_equipo;
            $equipoAmbienteModernizacion->estado_equipo                 = $request->estado_equipo;
            $equipoAmbienteModernizacion->equipo_en_funcionamiento      = $request->equipo_en_funcionamiento;
            $equipoAmbienteModernizacion->observaciones_generales       = $request->observaciones_generales;
            $equipoAmbienteModernizacion->marca                         = $request->marca;
            $equipoAmbienteModernizacion->horas_promedio_uso            = $request->horas_promedio_uso;
            $equipoAmbienteModernizacion->frecuencia_mantenimiento      = $request->frecuencia_mantenimiento;
            $equipoAmbienteModernizacion->year_adquisicion              = $request->year_adquisicion;
            $equipoAmbienteModernizacion->nombre_cuentadante            = $request->nombre_cuentadante;
            $equipoAmbienteModernizacion->cedula_cuentadante            = $request->cedula_cuentadante;
            $equipoAmbienteModernizacion->rol_cuentadante               = $request->rol_cuentadante;

            $equipoAmbienteModernizacion->save();

            $message = 'El recurso se ha modificado correctamente.';
        } else {
            $equipoAmbienteModernizacion = new EquipoAmbienteModernizacion();
            $equipoAmbienteModernizacion->numero_inventario_equipo      = $request->numero_inventario_equipo;
            $equipoAmbienteModernizacion->nombre_equipo                 = $request->nombre_equipo;
            $equipoAmbienteModernizacion->descripcion_tecnica_equipo    = $request->descripcion_tecnica_equipo;
            $equipoAmbienteModernizacion->estado_equipo                 = $request->estado_equipo;
            $equipoAmbienteModernizacion->equipo_en_funcionamiento      = $request->equipo_en_funcionamiento;
            $equipoAmbienteModernizacion->observaciones_generales       = $request->observaciones_generales;
            $equipoAmbienteModernizacion->marca                         = $request->marca;
            $equipoAmbienteModernizacion->horas_promedio_uso            = $request->horas_promedio_uso;
            $equipoAmbienteModernizacion->frecuencia_mantenimiento      = $request->frecuencia_mantenimiento;
            $equipoAmbienteModernizacion->year_adquisicion              = $request->year_adquisicion;
            $equipoAmbienteModernizacion->nombre_cuentadante            = $request->nombre_cuentadante;
            $equipoAmbienteModernizacion->cedula_cuentadante            = $request->cedula_cuentadante;
            $equipoAmbienteModernizacion->rol_cuentadante               = $request->rol_cuentadante;
            $equipoAmbienteModernizacion->ambienteModernizacion()->associate($ambienteModernizacion);

            $equipoAmbienteModernizacion->save();

            $message = 'El recurso se ha creado correctamente.';
        }

        return back()->with('success', $message);
    }

    public function destroyEquipo(EquipoAmbienteModernizacion $equipoAmbienteModernizacion)
    {
        $this->authorize('delete', [EquipoAmbienteModernizacion::class, $equipoAmbienteModernizacion]);

        $equipoAmbienteModernizacion->delete();

        return back()->with('success', 'El recurso se ha eliminado correctamente.');
    }

    /**
     * 
     */
    public function replicateRow($ambienteModernizacion)
    {
        $clone = $ambienteModernizacion->replicate();
        $clone->push();

        //load relations on EXISTING MODEL
        $ambienteModernizacion->load(
            'mesasSectoriales',
            'codigosProyectosSgps',
            'programasFormacionCalificados',
            'programasFormacionNoCalificados',
            'semillerosInvestigacion'
        );

        //re-sync everything
        foreach ($ambienteModernizacion->getRelations() as $relationName => $values) {
            if ($relationName != 'equiposAmbienteModernizacion') {
                $clone->{$relationName}()->sync($values);
            }
        }

        foreach ($ambienteModernizacion->equiposAmbienteModernizacion as $equipo) {
            $clone->equiposAmbienteModernizacion()->create($equipo->toArray());
        }

        $clone->save();

        return $clone;
    }

    /**
     * generatePdfAmbienteModernizacion
     *
     * @param  mixed $proyecto
     * @return void
     */
    function generatePdfAmbienteModernizacion(AmbienteModernizacion $ambienteModernizacion)
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', -1);

        $pdf = PDF::loadView('AmbienteModernizacionPdf', [
            'ambienteModernizacion' => $ambienteModernizacion,
        ]);

        // return $pdf->stream("dompdf_out.pdf", array("Attachment" => false));

        $output = $pdf->setWarnings(false)->output();
        $random    = Str::random(10);
        $fileName = $ambienteModernizacion->seguimientoAmbienteModernizacion->codigo . $random;
        $path = 'ambientes-modernizacion/' . $fileName . '.pdf';
        Storage::put($path, $output);

        $ambienteModernizacion->update(['pdf_path' => $path]);
    }

    /**
     * descargarPdfAmbienteModernizacion
     *
     * @param  mixed $ambienteModernizacion
     * @return void
     */
    function descargarPdfAmbienteModernizacion(AmbienteModernizacion $ambienteModernizacion)
    {
        return Storage::download($ambienteModernizacion->pdf_path);
    }

    public function updateLongColumn(Request $request, AmbienteModernizacion $ambienteModernizacion, $column)
    {
        $ambienteModernizacion->update($request->only($column));

        return back();
    }
}
