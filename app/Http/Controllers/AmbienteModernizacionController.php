<?php

namespace App\Http\Controllers;

use App\Models\AmbienteModernizacion;
use App\Http\Controllers\Controller;
use App\Http\Requests\AmbienteModernizacionRequest;
use App\Models\CentroFormacion;
use App\Models\CodigoProyectoSgps;
use App\Models\MesaSectorial;
use App\Models\SemilleroInvestigacion;
use App\Models\TipologiaAmbiente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

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

        return Inertia::render('AmbientesModernizacion/Index', [
            'filters'   => request()->all('search'),
            'ambientesModernizacion' => AmbienteModernizacion::orderBy('nombre_ambiente', 'ASC')
                ->filterAmbienteModernizacion(request()->only('search'))->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', [AmbienteModernizacion::class]);

        return Inertia::render('AmbientesModernizacion/Create', [
            'centrosFormacion' => CentroFormacion::selectRaw('centros_formacion.id as value, concat(centros_formacion.nombre, chr(10), \'∙ Código: \', centros_formacion.codigo) as label')->orderBy('centros_formacion.nombre', 'ASC')->get(),
            'codigosSgps' => CodigoProyectoSgps::selectRaw('codigos_proyectos_sgps.id as value, concat(codigos_proyectos_sgps.titulo, chr(10), \'∙ Código: \', codigos_proyectos_sgps.codigo_sgps) as label')->orderBy('codigos_proyectos_sgps.codigo_sgps', 'ASC')->get(),
            'tipologiasAmbientes' => TipologiaAmbiente::select('tipologias_ambientes.id as value', 'tipologias_ambientes.tipo as label')->orderBy('tipologias_ambientes.tipo', 'ASC')->get(),
            'mesasSectoriales' => MesaSectorial::select('id', 'nombre')->get('id'),
            'semillerosInvestigacion' => SemilleroInvestigacion::select('semilleros_investigacion.nombre as label', 'semilleros_investigacion.id as value')->join('lineas_investigacion', 'semilleros_investigacion.linea_investigacion_id', 'lineas_investigacion.id')->join('grupos_investigacion', 'lineas_investigacion.grupo_investigacion_id', 'grupos_investigacion.id')->where('grupos_investigacion.centro_formacion_id', '=', auth()->user()->centroFormacion->id)->get()
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

        $ambienteModernizacion = new AmbienteModernizacion();
        $ambienteModernizacion->nombre_ambiente = $request->nombre_ambiente;
        $ambienteModernizacion->alineado_mesas_sectoriales = $request->alineado_mesas_sectoriales;
        $ambienteModernizacion->financiado_anteriormente = $request->financiado_anteriormente;
        $ambienteModernizacion->estado_general_maquinaria = $request->estado_general_maquinaria;
        $ambienteModernizacion->razon_estado_general = $request->razon_estado_general;
        $ambienteModernizacion->ambiente_activo = $request->ambiente_activo;
        $ambienteModernizacion->justificacion_ambiente_inactivo = $request->justificacion_ambiente_inactivo;
        $ambienteModernizacion->ambiente_activo_procesos_idi = $request->ambiente_activo_procesos_idi;
        $ambienteModernizacion->numero_proyectos_beneficiados = $request->numero_proyectos_beneficiados;
        $ambienteModernizacion->ambiente_formacion_complementaria = $request->ambiente_formacion_complementaria;
        $ambienteModernizacion->numero_total_cursos_comp = $request->numero_total_cursos_comp;
        $ambienteModernizacion->numero_cursos_empresas = $request->numero_cursos_empresas;
        $ambienteModernizacion->datos_empresa = $request->datos_empresa;
        $ambienteModernizacion->cursos_complementarios = $request->cursos_complementarios;
        $ambienteModernizacion->coordenada_latitud_ambiente = $request->coordenada_latitud_ambiente;
        $ambienteModernizacion->coordenada_longitud_ambiente = $request->coordenada_longitud_ambiente;
        $ambienteModernizacion->impacto_procesos_formacion = $request->impacto_procesos_formacion;
        $ambienteModernizacion->pertinencia_sector_productivo = $request->pertinencia_sector_productivo;
        $ambienteModernizacion->palabras_clave_ambiente = $request->palabras_clave_ambiente;
        $ambienteModernizacion->observaciones_generales_ambiente = $request->observaciones_generales_ambiente;

        if ($request->hasFile('soporte_fotos_ambiente')) {
            $path = $request->file('soporte_fotos_ambiente')->store(
                'fotos-ambientes'
            );
            $ambienteModernizacion->soporte_fotos_ambiente = $path;
        }

        $ambienteModernizacion->redConocimiento()->associate($request->red_conocimiento_id);
        $ambienteModernizacion->lineaInvestigacion()->associate($request->linea_investigacion_id);
        $ambienteModernizacion->centroFormacion()->associate($request->centro_formacion_id);
        $ambienteModernizacion->disciplinaSubareaConocimiento()->associate($request->disciplina_subarea_conocimiento_id);
        $ambienteModernizacion->tematicaEstrategica()->associate($request->tematica_estrategica_id);
        $ambienteModernizacion->codigoProyectoSgps()->associate($request->codigo_proyecto_sgps_id);
        $ambienteModernizacion->tipologiaAmbiente()->associate($request->tipologia_ambiente_id);
        $ambienteModernizacion->actividadEconomica()->associate($request->actividad_economica_id);
        $ambienteModernizacion->dinamizadorSennova()->associate(auth()->user()->id);

        $ambienteModernizacion->save();

        $ambienteModernizacion->mesasSectoriales()->sync($request->mesa_sectorial_id);
        $ambienteModernizacion->codigosProyectosSgps()->sync($request->codigos_proyectos_id);
        $ambienteModernizacion->codigosProyectosSgpsBeneficiados()->sync($request->cod_proyectos_beneficiados_id);
        $ambienteModernizacion->programasFormacionCalificados()->sync($request->programas_formacion_calificados);
        $ambienteModernizacion->programasFormacionNoCalificados()->sync($request->programas_formacion);
        $ambienteModernizacion->semilerosInvestigacion()->sync($request->semilleros_investigacion_id);

        return redirect()->route('ambientes-modernizacion.index')->with('success', 'El recurso se ha creado correctamente.');
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

        $ambienteModernizacion->disciplinaSubareaConocimiento->subareaConocimiento->areaConocimiento;

        return Inertia::render('AmbientesModernizacion/Edit', [
            'ambienteModernizacion' => $ambienteModernizacion,
            'centrosFormacion' => CentroFormacion::selectRaw('centros_formacion.id as value, concat(centros_formacion.nombre, chr(10), \'∙ Código: \', centros_formacion.codigo) as label')->orderBy('centros_formacion.nombre', 'ASC')->get(),
            'codigosSgps' => CodigoProyectoSgps::selectRaw('codigos_proyectos_sgps.id as value, concat(codigos_proyectos_sgps.titulo, chr(10), \'∙ Código: \', codigos_proyectos_sgps.codigo_sgps) as label')->orderBy('codigos_proyectos_sgps.codigo_sgps', 'ASC')->get(),
            'tipologiasAmbientes' => TipologiaAmbiente::select('tipologias_ambientes.id as value', 'tipologias_ambientes.tipo as label')->orderBy('tipologias_ambientes.tipo', 'ASC')->get(),
            'mesasSectoriales' => MesaSectorial::select('id', 'nombre')->get('id'),
            'semillerosInvestigacion' => SemilleroInvestigacion::select('semilleros_investigacion.nombre as label', 'semilleros_investigacion.id as value')->join('lineas_investigacion', 'semilleros_investigacion.linea_investigacion_id', 'lineas_investigacion.id')->join('grupos_investigacion', 'lineas_investigacion.grupo_investigacion_id', 'grupos_investigacion.id')->where('grupos_investigacion.centro_formacion_id', '=', auth()->user()->centroFormacion->id)->get(),

            'codigosProyectosRelacionados' => $ambienteModernizacion->codigosProyectosSgps()->selectRaw('codigos_proyectos_sgps.id as value, concat(codigos_proyectos_sgps.titulo, chr(10), \'∙ Código: \', codigos_proyectos_sgps.codigo_sgps) as label')->get(),
            'programasFormacionCalificadosRelacionados' => $ambienteModernizacion->programasFormacionCalificados()->selectRaw('programas_formacion.id as value, concat(programas_formacion.nombre, chr(10), \'∙ Código: \', programas_formacion.codigo) as label')->get(),
            'programasFormacionNoCalificadosRelacionados' => $ambienteModernizacion->programasFormacionNoCalificados()->selectRaw('programas_formacion_articulados.id as value, concat(programas_formacion_articulados.nombre, chr(10), \'∙ Código: \', programas_formacion_articulados.codigo) as label')->get(),
            'codProyectosBeneficiadosRelacionados' => $ambienteModernizacion->codigosProyectosSgpsBeneficiados()->selectRaw('codigos_proyectos_sgps.id as value, concat(codigos_proyectos_sgps.titulo, chr(10), \'∙ Código: \', codigos_proyectos_sgps.codigo_sgps) as label')->get(),
            'semillerosRelacionados' => $ambienteModernizacion->semilerosInvestigacion()->select('semilleros_investigacion.nombre as label', 'semilleros_investigacion.id as value')->get(),
            'mesasSectorialesRelacionadas' => $ambienteModernizacion->mesasSectoriales()->pluck('id'),
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

        $ambienteModernizacion->nombre_ambiente = $request->nombre_ambiente;
        $ambienteModernizacion->alineado_mesas_sectoriales = $request->alineado_mesas_sectoriales;
        $ambienteModernizacion->financiado_anteriormente = $request->financiado_anteriormente;
        $ambienteModernizacion->estado_general_maquinaria = $request->estado_general_maquinaria;
        $ambienteModernizacion->razon_estado_general = $request->razon_estado_general;
        $ambienteModernizacion->ambiente_activo = $request->ambiente_activo;
        $ambienteModernizacion->justificacion_ambiente_inactivo = $request->justificacion_ambiente_inactivo;
        $ambienteModernizacion->ambiente_activo_procesos_idi = $request->ambiente_activo_procesos_idi;
        $ambienteModernizacion->numero_proyectos_beneficiados = $request->numero_proyectos_beneficiados;
        $ambienteModernizacion->ambiente_formacion_complementaria = $request->ambiente_formacion_complementaria;
        $ambienteModernizacion->numero_total_cursos_comp = $request->numero_total_cursos_comp;
        $ambienteModernizacion->numero_cursos_empresas = $request->numero_cursos_empresas;
        $ambienteModernizacion->datos_empresa = $request->datos_empresa;
        $ambienteModernizacion->cursos_complementarios = $request->cursos_complementarios;
        $ambienteModernizacion->coordenada_latitud_ambiente = $request->coordenada_latitud_ambiente;
        $ambienteModernizacion->coordenada_longitud_ambiente = $request->coordenada_longitud_ambiente;
        $ambienteModernizacion->impacto_procesos_formacion = $request->impacto_procesos_formacion;
        $ambienteModernizacion->pertinencia_sector_productivo = $request->pertinencia_sector_productivo;
        $ambienteModernizacion->palabras_clave_ambiente = $request->palabras_clave_ambiente;
        $ambienteModernizacion->observaciones_generales_ambiente = $request->observaciones_generales_ambiente;

        if ($request->hasFile('soporte_fotos_ambiente')) {
            Storage::delete($ambienteModernizacion->soporte_fotos_ambiente);
            $path = $request->file('soporte_fotos_ambiente')->store(
                'fotos-ambientes'
            );
            $ambienteModernizacion->soporte_fotos_ambiente = $path;
        }

        $ambienteModernizacion->redConocimiento()->associate($request->red_conocimiento_id);
        $ambienteModernizacion->lineaInvestigacion()->associate($request->linea_investigacion_id);
        $ambienteModernizacion->centroFormacion()->associate($request->centro_formacion_id);
        $ambienteModernizacion->disciplinaSubareaConocimiento()->associate($request->disciplina_subarea_conocimiento_id);
        $ambienteModernizacion->tematicaEstrategica()->associate($request->tematica_estrategica_id);
        $ambienteModernizacion->codigoProyectoSgps()->associate($request->codigo_proyecto_sgps_id);
        $ambienteModernizacion->tipologiaAmbiente()->associate($request->tipologia_ambiente_id);
        $ambienteModernizacion->actividadEconomica()->associate($request->actividad_economica_id);
        $ambienteModernizacion->dinamizadorSennova()->associate(auth()->user()->id);

        $ambienteModernizacion->save();

        $ambienteModernizacion->mesasSectoriales()->sync($request->mesa_sectorial_id);
        $ambienteModernizacion->codigosProyectosSgps()->sync($request->codigos_proyectos_id);
        $ambienteModernizacion->codigosProyectosSgpsBeneficiados()->sync($request->cod_proyectos_beneficiados_id);
        $ambienteModernizacion->programasFormacionCalificados()->sync($request->programas_formacion_calificados);
        $ambienteModernizacion->programasFormacionNoCalificados()->sync($request->programas_formacion);
        $ambienteModernizacion->semilerosInvestigacion()->sync($request->semilleros_investigacion_id);

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

        Storage::delete($ambienteModernizacion->soporte_fotos_ambiente);
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
}
