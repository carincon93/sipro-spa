<?php

namespace App\Http\Controllers;

use App\Models\Convocatoria;
use App\Models\Proyecto;
use App\Models\Ta;
use App\Models\TecnoAcademia;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaRequest;
use App\Models\Regional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $this->authorize('formular-proyecto');

        return Inertia::render('Convocatorias/Proyectos/Ta/Index', [
            'convocatoria'  => $convocatoria->only('id'),
            'filters'       => request()->all('search'),
            'ta'            => Ta::getProyectosPorRol($convocatoria)->appends(['search' => request()->search]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Convocatoria $convocatoria)
    {
        $this->authorize('formular-proyecto');

        return Inertia::render('Convocatorias/Proyectos/Ta/Create', [
            'convocatoria'              => $convocatoria->only('id', 'min_fecha_inicio_proyectos_ta', 'max_fecha_finalizacion_proyectos_ta'),
            'tecnoacademias'            => TecnoAcademia::select('id as value', 'nombre as label')->get(),
            'authUserCentroFormacion'   => Auth::user()->centroFormacion->id
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
        $this->authorize('formular-proyecto');

        $proyecto = new Proyecto();
        $proyecto->centroFormacion()->associate($request->centro_formacion_id);
        $proyecto->lineaProgramatica()->associate(5);
        $proyecto->convocatoria()->associate($convocatoria);
        $proyecto->save();

        $ta = new Ta();
        $ta->fecha_inicio                         = $request->fecha_inicio;
        $ta->fecha_finalizacion                   = $request->fecha_finalizacion;
        $ta->max_meses_ejecucion                  = $request->max_meses_ejecucion;
        $ta->resumen                              = 'Por favor diligencie el resumen del proyecto';
        $ta->antecedentes                         = 'Por favor diligencie los antecedentes del proyecto';
        $ta->marco_conceptual                     = 'Por favor diligencie el marco conceptual del proyecto';
        $ta->metodologia                          = 'Por favor diligencie la metodología del proyecto';
        $ta->bibliografia                         = 'Por favor diligencie la bibliografía';
        $ta->impacto_municipios                   = 'Describa el beneficio en los municipios';
        $ta->articulacion_centro_formacion        = 'Describa la articulación con el centro de formación';
        $ta->identificacion_problema              = 'Describa la identificación del problema';

        $ta->resumen_regional                     = 'Por favor diligencie el resumen regional';
        $ta->antecedentes_tecnoacademia           = 'Por favor diligencie los antecedentes de la Tecnoacademia y su impacto en la región';
        $ta->retos_oportunidades                  = 'Descripción de Retos y prioridades locales y regionales en los cuales la Tecnoacademia tiene impacto';
        $ta->pertinencia_territorio               = 'Justificacion y pertinencia en el territorio';
        $ta->metodologia_local                    = 'Descripcion de La Metodología aplicada a nivel local';
        $ta->numero_instituciones                 = 0;
        $ta->nombre_instituciones                 = null;
        $ta->nombre_instituciones_programas       = null;

        $ta->tecnoacademiaLineaTecnologica()->associate($request->tecnoacademia_linea_tecnologica_id);

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
    public function show(Convocatoria $convocatoria, Proyecto $proyecto, Ta $ta)
    {
        $this->authorize('visualizar-proyecto-autor', [$ta->proyecto]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ta  $ta
     * @return \Illuminate\Http\Response
     */
    public function edit(Convocatoria $convocatoria, Proyecto $proyecto, Ta $ta)
    {
        $this->authorize('visualizar-proyecto-autor', [$ta->proyecto]);

        $ta->codigo_linea_programatica = $ta->proyecto->lineaProgramatica->codigo;
        $ta->precio_proyecto           = $ta->proyecto->precioProyecto;
        $ta->proyecto->centroFormacion;

        return Inertia::render('Convocatorias/Proyectos/Ta/Edit', [
            'convocatoria'                          => $convocatoria->only('id', 'min_fecha_inicio_proyectos_ta', 'max_fecha_finalizacion_proyectos_ta'),
            'ta'                                    => $ta,
            'tecnoacademiaRelacionada'              => $ta->tecnoacademiaLineaTecnologica()->exists() ? $ta->tecnoacademiaLineaTecnologica->tecnoacademia->id : null,
            'lineaTecnologicaRelacionada'           => $ta->tecnoacademiaLineaTecnologica()->exists() ? $ta->tecnoacademiaLineaTecnologica->id : null,
            'regionales'                            => Regional::select('id as value', 'nombre as label', 'codigo')->orderBy('nombre')->get(),
            'tecnoacademias'                        => TecnoAcademia::select('id as value', 'nombre as label')->get(),
            'proyectoMunicipios'                    => $ta->proyecto->municipios()->select('municipios.id as value', 'municipios.nombre as label', 'regionales.nombre as group', 'regionales.codigo')->join('regionales', 'regionales.id', 'municipios.regional_id')->get(),
            'proyectoProgramasFormacionArticulados' => $ta->proyecto->programasFormacionArticulados()->selectRaw('id as value, concat(programas_formacion.nombre, chr(10), \'∙ Código: \', programas_formacion.codigo) as label')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ta  $ta
     * @return \Illuminate\Http\Response
     */
    public function update(TaRequest $request, Convocatoria $convocatoria, Proyecto $proyecto, Ta $ta)
    {
        $this->authorize('modificar-proyecto-autor', [$ta->proyecto]);

        $ta->fecha_inicio                         = $request->fecha_inicio;
        $ta->fecha_finalizacion                   = $request->fecha_finalizacion;
        $ta->max_meses_ejecucion                  = $request->max_meses_ejecucion;
        $ta->resumen                              = $request->resumen;
        $ta->antecedentes                         = $request->antecedentes;
        $ta->justificacion                        = $request->justificacion;
        $ta->marco_conceptual                     = $request->marco_conceptual;
        $ta->bibliografia                         = $request->bibliografia;
        $ta->impacto_municipios                   = $request->impacto_municipios;
        $ta->articulacion_centro_formacion        = $request->articulacion_centro_formacion;

        $ta->resumen_regional                     = $request->resumen_regional;
        $ta->antecedentes_tecnoacademia           = $request->antecedentes_tecnoacademia;
        $ta->retos_oportunidades                  = $request->retos_oportunidades;
        $ta->pertinencia_territorio               = $request->pertinencia_territorio;
        $ta->metodologia_local                    = $request->metodologia_local;

        $ta->numero_instituciones                 = count(json_decode($request->nombre_instituciones));
        $ta->nombre_instituciones                 = $request->nombre_instituciones;
        $ta->nombre_instituciones_programas       = $request->nombre_instituciones_programas;

        $ta->proyecto->municipios()->sync($request->municipios);
        $ta->proyecto->programasFormacionArticulados()->sync($request->programas_formacion_articulados);
        $ta->tecnoacademiaLineaTecnologica()->associate($request->tecnoacademia_linea_tecnologica_id);

        $ta->save();

        return redirect()->back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ta  $ta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Convocatoria $convocatoria, Proyecto $proyecto, Ta $ta)
    {
        $this->authorize('modificar-proyecto-autor', [$ta->proyecto]);

        $ta->proyecto()->delete();

        return redirect()->route('convocatorias.ta.index', [$convocatoria, $proyecto])->with('success', 'El recurso se ha eliminado correctamente.');
    }

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

        return redirect()->back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    public function showArticulacionSennova(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('visualizar-proyecto-autor', [$proyecto]);

        $proyecto->codigo_linea_programatica = $proyecto->lineaProgramatica->codigo;
        $proyecto->precio_proyecto           = $proyecto->precioProyecto;

        return Inertia::render('Convocatorias/Proyectos/ArticulacionSennova/Index', [
            'convocatoria'  => $convocatoria->only('id', 'min_fecha_inicio_proyectos_ta', 'max_fecha_finalizacion_proyectos_ta'),
            'proyecto'      => $proyecto->only('id', 'precio_proyecto', 'codigo_linea_programatica'),
        ]);
    }
}
