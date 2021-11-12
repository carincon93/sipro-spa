<?php

namespace App\Http\Controllers;

use App\Models\Convocatoria;
use App\Models\Proyecto;
use App\Models\Ta;
use App\Models\TecnoAcademia;
use App\Http\Controllers\Controller;
use App\Http\Requests\ArticulacionSennovaRequest;
use App\Http\Requests\TaLongColumnRequest;
use App\Http\Requests\TaRequest;
use App\Models\ActividadEconomica;
use App\Models\DisCurricular;
use App\Models\Evaluacion\Evaluacion;
use App\Models\Evaluacion\TaEvaluacion;
use App\Models\GrupoInvestigacion;
use App\Models\LineaInvestigacion;
use App\Models\ProgramaFormacion;
use App\Models\RedConocimiento;
use App\Models\Regional;
use App\Models\SemilleroInvestigacion;
use App\Models\TematicaEstrategica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class TaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Convocatoria $convocatoria)
    {
        return Inertia::render('Convocatorias/Proyectos/Ta/Index', [
            'convocatoria'  => $convocatoria->only('id', 'fase_formateada', 'fase'),
            'filters'       => request()->all('search', 'estructuracion_proyectos'),
            'ta'            => Ta::getProyectosPorRol($convocatoria)->appends(['search' => request()->search, 'estructuracion_proyectos' => request()->estructuracion_proyectos]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Convocatoria $convocatoria)
    {
        $this->authorize('formular-proyecto', [5]);

        if (auth()->user()->hasRole(12)) {
            $tecnoAcademias = Tecnoacademia::selectRaw("tecnoacademias.id as value, CASE modalidad
                WHEN '1' THEN	concat(tecnoacademias.nombre, chr(10), '∙ Modalidad: itinerante', chr(10), '∙ Centro de formación: ', centros_formacion.nombre)
                WHEN '2' THEN	concat(tecnoacademias.nombre, chr(10), '∙ Modalidad: itinerante - vehículo', chr(10), '∙ Centro de formación: ', centros_formacion.nombre)
                WHEN '3' THEN	concat(tecnoacademias.nombre, chr(10), '∙ Modalidad: fija con extensión', chr(10), '∙ Centro de formación: ', centros_formacion.nombre)
            END as label, centros_formacion.id as centro_formacion_id")
                ->join('centros_formacion', 'tecnoacademias.centro_formacion_id', 'centros_formacion.id')
                ->where('tecnoacademias.centro_formacion_id', auth()->user()->centroFormacion->id)->get();
        } else {
            $tecnoAcademias = $tecnoAcademias = Tecnoacademia::selectRaw("tecnoacademias.id as value, CASE modalidad
                WHEN '1' THEN	concat(tecnoacademias.nombre, chr(10), '∙ Modalidad: itinerante', chr(10), '∙ Centro de formación: ', centros_formacion.nombre)
                WHEN '2' THEN	concat(tecnoacademias.nombre, chr(10), '∙ Modalidad: itinerante - vehículo', chr(10), '∙ Centro de formación: ', centros_formacion.nombre)
                WHEN '3' THEN	concat(tecnoacademias.nombre, chr(10), '∙ Modalidad: fija con extensión', chr(10), '∙ Centro de formación: ', centros_formacion.nombre)
            END as label, centros_formacion.id as centro_formacion_id")
                ->join('centros_formacion', 'tecnoacademias.centro_formacion_id', 'centros_formacion.id')
                ->get();
        }

        return Inertia::render('Convocatorias/Proyectos/Ta/Create', [
            'convocatoria'   => $convocatoria->only('id', 'fase_formateada', 'fase', 'min_fecha_inicio_proyectos_ta', 'max_fecha_finalizacion_proyectos_ta', 'fecha_maxima_ta'),
            'tecnoAcademias' => $tecnoAcademias
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaRequest $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('formular-proyecto', [5]);

        $tecnoAcademia = TecnoAcademia::find($request->tecnoacademia_id['value']);

        $proyecto = new Proyecto();
        $proyecto->centroFormacion()->associate($tecnoAcademia->centro_formacion_id);
        $proyecto->lineaProgramatica()->associate(5);
        $proyecto->convocatoria()->associate($convocatoria);
        $proyecto->save();

        $proyecto->tecnoacademiaLineasTecnoacademia()->sync($request->tecnoacademia_linea_tecnoacademia_id);

        $ta = new Ta();
        $ta->fecha_inicio                       = $request->fecha_inicio;
        $ta->fecha_finalizacion                 = $request->fecha_finalizacion;
        $ta->max_meses_ejecucion                = $request->max_meses_ejecucion;
        $ta->resumen                            = '';
        $ta->antecedentes                       = '';
        $ta->marco_conceptual                   = '';
        $ta->metodologia                        = '';
        $ta->bibliografia                       = '';
        $ta->impacto_municipios                 = '';
        $ta->articulacion_centro_formacion      = '';
        $ta->identificacion_problema            = '';
        $ta->resumen_regional                   = '';
        $ta->antecedentes_tecnoacademia         = '';
        $ta->retos_oportunidades                = '';
        $ta->pertinencia_territorio             = '';
        $ta->metodologia_local                  = '';
        $ta->numero_instituciones               = 0;
        $ta->nombre_instituciones               = null;
        $ta->nombre_instituciones_programas     = null;
        $ta->proyeccion_nuevas_instituciones    = 1;
        $ta->proyeccion_articulacion_media      = 1;
        $ta->articulacion_semillero             = 1;

        $proyecto->ta()->save($ta);

        $proyecto->participantes()->attach(
            Auth::user()->id,
            [
                'es_formulador'     => true,
                'cantidad_meses'    => $request->max_meses_ejecucion,
                'cantidad_horas'    => 48,
                'rol_sennova'       => 3,
            ]
        );

        if ($proyecto->lineaProgramatica->codigo == 70) {
            DB::select('SELECT public."generalidades_ta"(' . $proyecto->id . ')');
        }

        return redirect()->route('convocatorias.ta.edit', [$convocatoria, $ta])->with('success', 'El recurso se ha creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ta  $ta
     * @return \Illuminate\Http\Response
     */
    public function show(Convocatoria $convocatoria, Ta $ta)
    {
        $this->authorize('visualizar-proyecto-autor', [$ta->proyecto]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ta  $ta
     * @return \Illuminate\Http\Response
     */
    public function edit(Convocatoria $convocatoria, Ta $ta)
    {
        $this->authorize('visualizar-proyecto-autor', [$ta->proyecto]);

        $ta->load('proyecto.evaluaciones.taEvaluacion');

        $ta->codigo_linea_programatica = $ta->proyecto->lineaProgramatica->codigo;
        $ta->precio_proyecto           = $ta->proyecto->precioProyecto;
        $ta->proyecto->centroFormacion;

        $ta->mostrar_recomendaciones = $ta->proyecto->mostrar_recomendaciones;

        if (auth()->user()->hasRole(12) && !auth()->user()->hasRole(5)) {
            $tecnoAcademias = Tecnoacademia::selectRaw("tecnoacademias.id as value, CASE modalidad
                WHEN '1' THEN	concat(tecnoacademias.nombre, chr(10), '∙ Modalidad: itinerante', chr(10), '∙ Centro de formación: ', centros_formacion.nombre)
                WHEN '2' THEN	concat(tecnoacademias.nombre, chr(10), '∙ Modalidad: itinerante - vehículo', chr(10), '∙ Centro de formación: ', centros_formacion.nombre)
                WHEN '3' THEN	concat(tecnoacademias.nombre, chr(10), '∙ Modalidad: fija con extensión', chr(10), '∙ Centro de formación: ', centros_formacion.nombre)
            END as label, centros_formacion.id as centro_formacion_id")
                ->join('centros_formacion', 'tecnoacademias.centro_formacion_id', 'centros_formacion.id')
                ->where('tecnoacademias.centro_formacion_id', auth()->user()->centroFormacion->id)->get();
        } else {
            $tecnoAcademias = $tecnoAcademias = Tecnoacademia::selectRaw("tecnoacademias.id as value, CASE modalidad
                WHEN '1' THEN	concat(tecnoacademias.nombre, chr(10), '∙ Modalidad: itinerante', chr(10), '∙ Centro de formación: ', centros_formacion.nombre)
                WHEN '2' THEN	concat(tecnoacademias.nombre, chr(10), '∙ Modalidad: itinerante - vehículo', chr(10), '∙ Centro de formación: ', centros_formacion.nombre)
                WHEN '3' THEN	concat(tecnoacademias.nombre, chr(10), '∙ Modalidad: fija con extensión', chr(10), '∙ Centro de formación: ', centros_formacion.nombre)
            END as label, centros_formacion.id as centro_formacion_id")
                ->join('centros_formacion', 'tecnoacademias.centro_formacion_id', 'centros_formacion.id')
                ->get();
        }

        return Inertia::render('Convocatorias/Proyectos/Ta/Edit', [
            'convocatoria'                          => $convocatoria->only('id', 'fase_formateada', 'fase', 'min_fecha_inicio_proyectos_ta', 'max_fecha_finalizacion_proyectos_ta', 'fecha_maxima_ta', 'mostrar_recomendaciones'),
            'ta'                                    => $ta,
            'tecnoacademiaRelacionada'              => $ta->proyecto->tecnoacademiaLineasTecnoacademia()->first() ? $ta->proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->id : null,
            'lineasTecnoacademiaRelacionadas'       => $ta->proyecto->tecnoacademiaLineasTecnoacademia()->select('tecnoacademia_linea_tecnoacademia.id as value', 'lineas_tecnoacademia.nombre')->join('lineas_tecnoacademia', 'tecnoacademia_linea_tecnoacademia.linea_tecnoacademia_id', 'lineas_tecnoacademia.id')->get(),
            'regionales'                            => Regional::select('id as value', 'nombre as label', 'codigo')->orderBy('nombre')->get(),
            'tecnoacademias'                        => TecnoAcademia::select('id as value', 'nombre as label')->get(),
            'proyectoMunicipios'                    => $ta->proyecto->municipios()->select('municipios.id as value', 'municipios.nombre as label', 'regionales.nombre as group', 'regionales.codigo')->join('regionales', 'regionales.id', 'municipios.regional_id')->get(),
            'proyectoMunicipiosImpactar'            => $ta->proyecto->municipiosAImpactar()->select('municipios.id as value', 'municipios.nombre as label', 'regionales.nombre as group', 'regionales.codigo')->join('regionales', 'regionales.id', 'municipios.regional_id')->get(),
            'proyectoProgramasFormacionArticulados' => $ta->proyecto->taProgramasFormacion()->selectRaw('id as value, concat(programas_formacion.nombre, chr(10), \'∙ Código: \', programas_formacion.codigo) as label')->get(),
            'proyectoDisCurriculares'               => $ta->proyecto->disCurriculares()->selectRaw('id as value, concat(nombre, \' ∙ Código: \', codigo) as label')->get(),
            'disCurriculares'                       => DisCurricular::selectRaw('id as value, concat(nombre, \' ∙ Código: \', codigo) as label')->get(),
            'programasFormacion'                    => ProgramaFormacion::selectRaw('id as value, concat(programas_formacion.nombre, chr(10), \'∙ Código: \', programas_formacion.codigo) as label')->where('centro_formacion_id', $ta->proyecto->centroFormacion->id)->orderBy('nombre', 'ASC')->get(),
            'tecnoAcademias'                        => $tecnoAcademias,
            'modalidades'                           => json_decode(Storage::get('json/modalidades-estudio.json'), true),
            'nivelesFormacion'                      => json_decode(Storage::get('json/nivel-formacion.json'), true)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ta  $ta
     * @return \Illuminate\Http\Response
     */
    public function update(TaRequest $request, Convocatoria $convocatoria, Ta $ta)
    {
        $this->authorize('modificar-proyecto-autor', [$ta->proyecto]);

        $ta->fecha_inicio                       = $request->fecha_inicio;
        $ta->fecha_finalizacion                 = $request->fecha_finalizacion;
        $ta->max_meses_ejecucion                = $request->max_meses_ejecucion;

        $ta->numero_instituciones               = is_array(json_decode($request->nombre_instituciones)) ? count(json_decode($request->nombre_instituciones)) : 0;
        $ta->nombre_instituciones               = $request->nombre_instituciones;
        $ta->nombre_instituciones_programas     = $request->nombre_instituciones_programas;
        $ta->nuevas_instituciones               = $request->nuevas_instituciones;

        $ta->proyeccion_nuevas_instituciones    = $request->proyeccion_nuevas_instituciones;
        $ta->proyeccion_articulacion_media      = $request->proyeccion_articulacion_media;

        $ta->otras_nuevas_instituciones = $request->otras_nuevas_instituciones;
        $ta->otras_nombre_instituciones_programas = $request->otras_nombre_instituciones_programas;
        $ta->otras_nombre_instituciones = $request->otras_nombre_instituciones;

        $ta->proyecto->municipios()->sync($request->municipios);
        $ta->proyecto->municipiosAImpactar()->sync($request->municipios_impactar);
        $ta->proyecto->taProgramasFormacion()->sync($request->programas_formacion_articulados);
        $ta->proyecto->tecnoacademiaLineasTecnoacademia()->sync($request->tecnoacademia_linea_tecnoacademia_id);
        $ta->proyecto->disCurriculares()->sync($request->dis_curricular_id);

        $ta->save();

        return back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    public function updateLongColumn(TaLongColumnRequest $request, Convocatoria $convocatoria, Ta $ta, $column)
    {
        $this->authorize('modificar-proyecto-autor', [$ta->proyecto]);

        $ta->update($request->only($column));

        return back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ta  $ta
     * @return \Illuminate\Http\Response
     */
    public function updateInfraestructura(Request $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        // $this->authorize('modificar-proyecto-autor', [$proyecto]);

        $proyecto->ta()->update([
            'infraestructura_tecnoacademia' => $request->infraestructura_tecnoacademia['value']
        ]);

        return back()->with('success', 'El recurso se ha actualizado correctamente.');
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ta  $ta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Convocatoria $convocatoria, Ta $ta)
    {
        if ($ta->id == 1052) {
            return back()->with('error', 'Este proyecto no se puede eliminar.');
        }

        $this->authorize('modificar-proyecto-autor', [$ta->proyecto]);

        if ($convocatoria->fase != 1) {
            return back()->with('error', 'Un proyecto finalizado no se puede eliminar.');
        }

        if (!Hash::check($request->password, Auth::user()->password)) {
            return back()
                ->withErrors(['password' => __('The password is incorrect.')]);
        }

        $ta->proyecto()->delete();

        return redirect()->route('convocatorias.ta.index', [$convocatoria])->with('success', 'El recurso se ha eliminado correctamente.');
    }

    /**
     * updateCantidadRolesTa
     *
     * @param  mixed $request
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function updateCantidadRolesTa(Request $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('modificar-proyecto-autor', [$proyecto]);

        $request->validate([
            'cantidad_instructores_planta'    => 'required|integer|min:0|max:32767',
            'cantidad_dinamizadores_planta'   => 'required|integer|min:0|max:32767',
            'cantidad_psicopedagogos_planta'  => 'required|integer|min:0|max:32767'
        ]);

        $proyecto->ta()->update([
            'cantidad_instructores_planta'   => $request->cantidad_instructores_planta,
            'cantidad_dinamizadores_planta'  => $request->cantidad_dinamizadores_planta,
            'cantidad_psicopedagogos_planta' => $request->cantidad_psicopedagogos_planta
        ]);

        return back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * showArticulacionSennova
     *
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function showArticulacionSennova(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('visualizar-proyecto-autor', [$proyecto]);

        $proyecto->codigo_linea_programatica            = $proyecto->lineaProgramatica->codigo;
        $proyecto->precio_proyecto                      = $proyecto->precioProyecto;
        $proyecto->proyectos_ejecucion                  = $proyecto->ta->proyectos_ejecucion;
        $proyecto->articulacion_semillero               = $proyecto->ta->articulacion_semillero;
        $proyecto->semilleros_en_formalizacion          = $proyecto->ta->semilleros_en_formalizacion;

        return Inertia::render('Convocatorias/Proyectos/ArticulacionSennova/Index', [
            'convocatoria'              => $convocatoria->only('id', 'fase_formateada', 'fase', 'year', 'min_fecha_inicio_proyectos_ta', 'max_fecha_finalizacion_proyectos_ta'),
            'proyecto'                  => $proyecto->only('id', 'precio_proyecto', 'codigo_linea_programatica', 'proyectos_ejecucion', 'modificable', 'articulacion_semillero', 'semilleros_en_formalizacion', 'mostrar_recomendaciones'),
            'lineasInvestigacion'       => LineaInvestigacion::selectRaw('lineas_investigacion.id as value, concat(lineas_investigacion.nombre, chr(10), \'∙ Grupo de investigación: \', grupos_investigacion.nombre, chr(10)) as label')->join('grupos_investigacion', 'lineas_investigacion.grupo_investigacion_id', 'grupos_investigacion.id')->where('grupos_investigacion.centro_formacion_id', $proyecto->centroFormacion->id)->get(),
            'gruposInvestigacion'       => GrupoInvestigacion::selectRaw('grupos_investigacion.id as value, concat(grupos_investigacion.nombre, chr(10), \'∙ \', centros_formacion.nombre, chr(10)) as label')->join('centros_formacion', 'grupos_investigacion.centro_formacion_id', 'centros_formacion.id')->where('centros_formacion.regional_id', $proyecto->centroFormacion->regional->id)->get(),
            'semillerosInvestigacion'   => SemilleroInvestigacion::selectRaw('semilleros_investigacion.id as value, concat(semilleros_investigacion.nombre, chr(10), \'∙ Grupo de investigación: \', grupos_investigacion.nombre, chr(10)) as label')->join('lineas_investigacion', 'semilleros_investigacion.linea_investigacion_id', 'lineas_investigacion.id')->join('grupos_investigacion', 'lineas_investigacion.grupo_investigacion_id', 'grupos_investigacion.id')->where('grupos_investigacion.centro_formacion_id', $proyecto->centroFormacion->id)->get(),
            'redesConocimiento'         => RedConocimiento::select('id as value', 'nombre as label')->get(),
            'tematicasEstrategicas'     => TematicaEstrategica::select('id as value', 'nombre as label')->get(),
            'actividadesEconomicas'     => ActividadEconomica::select('id as value', 'nombre as label')->get(),

            'gruposInvestigacionRelacionados'               => $proyecto->gruposInvestigacion()->select('grupos_investigacion.id as value', 'grupos_investigacion.nombre as label')->get(),
            'lineasInvestigacionRelacionadas'               => $proyecto->lineasInvestigacion()->select('lineas_investigacion.id as value', 'lineas_investigacion.nombre as label')->get(),
            'semillerosInvestigacionRelacionados'           => $proyecto->semillerosInvestigacion()->select('semilleros_investigacion.id as value', 'semilleros_investigacion.nombre as label')->get(),
            'disciplinasSubareaConocimientoRelacionadas'    => $proyecto->ta->disciplinasSubareaConocimiento()->select('disciplinas_subarea_conocimiento.id as value', 'disciplinas_subarea_conocimiento.nombre as label')->get(),
            'redesConocimientoRelacionadas'                 => $proyecto->ta->redesConocimiento()->select('redes_conocimiento.id as value', 'redes_conocimiento.nombre as label')->get(),
            'tematicasEstrategicasRelacionadas'             => $proyecto->ta->tematicasEstrategicas()->select('tematicas_estrategicas.id as value', 'tematicas_estrategicas.nombre as label')->get(),
            'actividadesEconomicasRelacionadas'             => $proyecto->ta->actividadesEconomicas()->select('actividades_economicas.id as value', 'actividades_economicas.nombre as label')->get(),
        ]);
    }

    /**
     * showArticulacionSennovaEvaluacion
     *
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function showArticulacionSennovaEvaluacion(Convocatoria $convocatoria, Evaluacion $evaluacion)
    {
        $this->authorize('visualizar-evaluacion-autor', $evaluacion);

        $evaluacion->proyecto->codigo_linea_programatica            = $evaluacion->proyecto->lineaProgramatica->codigo;
        $evaluacion->proyecto->precio_proyecto                      = $evaluacion->proyecto->precioProyecto;
        $evaluacion->proyecto->proyectos_ejecucion                  = $evaluacion->proyecto->ta->proyectos_ejecucion;
        $evaluacion->proyecto->articulacion_semillero               = $evaluacion->proyecto->ta->articulacion_semillero;
        $evaluacion->proyecto->semilleros_en_formalizacion          = $evaluacion->proyecto->ta->semilleros_en_formalizacion;
        $ta = $evaluacion->proyecto->ta;

        return Inertia::render('Convocatorias/Evaluaciones/ArticulacionSennova/Index', [
            'convocatoria'              => $convocatoria->only('id', 'fase_formateada', 'fase', 'year', 'min_fecha_inicio_proyectos_ta', 'max_fecha_finalizacion_proyectos_ta', 'mostrar_recomendaciones'),
            'evaluacion'                => $evaluacion,
            'otrasEvaluaciones'         => TaEvaluacion::with('evaluacion.evaluador')->whereHas('evaluacion', function ($query) use ($ta) {
                $query->where('evaluaciones.proyecto_id', $ta->id)->where('evaluaciones.habilitado', true);
            })->where('ta_evaluaciones.id', '!=', $evaluacion->taEvaluacion->id)->get(),
            'proyecto'                  => $evaluacion->proyecto->only('id', 'precio_proyecto', 'codigo_linea_programatica', 'proyectos_ejecucion', 'modificable', 'articulacion_semillero', 'semilleros_en_formalizacion'),
            'lineasInvestigacion'       => LineaInvestigacion::selectRaw('lineas_investigacion.id as value, concat(lineas_investigacion.nombre, chr(10), \'∙ Grupo de investigación: \', grupos_investigacion.nombre, chr(10)) as label')->join('grupos_investigacion', 'lineas_investigacion.grupo_investigacion_id', 'grupos_investigacion.id')->where('grupos_investigacion.centro_formacion_id', $evaluacion->proyecto->centroFormacion->id)->get(),
            'gruposInvestigacion'       => GrupoInvestigacion::selectRaw('grupos_investigacion.id as value, concat(grupos_investigacion.nombre, chr(10), \'∙ \', centros_formacion.nombre, chr(10)) as label')->join('centros_formacion', 'grupos_investigacion.centro_formacion_id', 'centros_formacion.id')->where('centros_formacion.regional_id', $evaluacion->proyecto->centroFormacion->regional->id)->get(),
            'semillerosInvestigacion'   => SemilleroInvestigacion::selectRaw('semilleros_investigacion.id as value, concat(semilleros_investigacion.nombre, chr(10), \'∙ Grupo de investigación: \', grupos_investigacion.nombre, chr(10)) as label')->join('lineas_investigacion', 'semilleros_investigacion.linea_investigacion_id', 'lineas_investigacion.id')->join('grupos_investigacion', 'lineas_investigacion.grupo_investigacion_id', 'grupos_investigacion.id')->where('grupos_investigacion.centro_formacion_id', $evaluacion->proyecto->centroFormacion->id)->get(),
            'redesConocimiento'         => RedConocimiento::select('id as value', 'nombre as label')->get(),
            'tematicasEstrategicas'     => TematicaEstrategica::select('id as value', 'nombre as label')->get(),
            'actividadesEconomicas'     => ActividadEconomica::select('id as value', 'nombre as label')->get(),

            'gruposInvestigacionRelacionados'               => $evaluacion->proyecto->gruposInvestigacion()->select('grupos_investigacion.id as value', 'grupos_investigacion.nombre as label')->get(),
            'lineasInvestigacionRelacionadas'               => $evaluacion->proyecto->lineasInvestigacion()->select('lineas_investigacion.id as value', 'lineas_investigacion.nombre as label')->get(),
            'semillerosInvestigacionRelacionados'           => $evaluacion->proyecto->semillerosInvestigacion()->select('semilleros_investigacion.id as value', 'semilleros_investigacion.nombre as label')->get(),
            'disciplinasSubareaConocimientoRelacionadas'    => $evaluacion->proyecto->ta->disciplinasSubareaConocimiento()->select('disciplinas_subarea_conocimiento.id as value', 'disciplinas_subarea_conocimiento.nombre as label')->get(),
            'redesConocimientoRelacionadas'                 => $evaluacion->proyecto->ta->redesConocimiento()->select('redes_conocimiento.id as value', 'redes_conocimiento.nombre as label')->get(),
            'tematicasEstrategicasRelacionadas'             => $evaluacion->proyecto->ta->tematicasEstrategicas()->select('tematicas_estrategicas.id as value', 'tematicas_estrategicas.nombre as label')->get(),
            'actividadesEconomicasRelacionadas'             => $evaluacion->proyecto->ta->actividadesEconomicas()->select('actividades_economicas.id as value', 'actividades_economicas.nombre as label')->get(),
        ]);
    }

    /**
     * storeArticulacionSennova
     *
     * @param  mixed $request
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function storeArticulacionSennova(ArticulacionSennovaRequest $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $proyecto->gruposInvestigacion()->sync($request->grupos_investigacion);
        $proyecto->lineasInvestigacion()->sync($request->lineas_investigacion);
        $proyecto->ta->actividadesEconomicas()->sync($request->actividades_economicas);
        $proyecto->ta->disciplinasSubareaConocimiento()->sync($request->disciplinas_subarea_conocimiento);
        $proyecto->ta->redesConocimiento()->sync($request->redes_conocimiento);
        $proyecto->ta->tematicasEstrategicas()->sync($request->tematicas_estrategicas);

        if ($request->articulacion_semillero == 1) {
            $proyecto->semillerosInvestigacion()->sync($request->semilleros_investigacion);
        } else {
            $proyecto->semillerosInvestigacion()->sync([]);
        }

        $proyecto->ta->update([
            'proyectos_ejecucion'           => $request->proyectos_ejecucion,
            'articulacion_semillero'        => $request->articulacion_semillero,
            'semilleros_en_formalizacion'   => $request->semilleros_en_formalizacion
        ]);

        return back()->with('success', 'El recurso se ha guardado correctamente.');
    }

    /**
     * updatedArticulacionSennovaEvaluacion
     *
     * @param  mixed $request
     * @param  mixed $convocatoria
     * @param  mixed $evaluacion
     * @return void
     */
    public function updatedArticulacionSennovaEvaluacion(Request $request, Convocatoria $convocatoria, Evaluacion $evaluacion)
    {
        $this->authorize('modificar-evaluacion-autor', $evaluacion);

        $evaluacion->taEvaluacion()->update([
            'articulacion_sennova_comentario'   => $request->articulacion_sennova_requiere_comentario == false ? $request->articulacion_sennova_comentario : null
        ]);

        return back()->with('success', 'El recurso se ha actualizado correctamente.');
    }
}
