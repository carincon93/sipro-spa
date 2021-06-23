<?php

namespace App\Http\Controllers;

use App\Models\Convocatoria;
use App\Models\Proyecto;
use App\Models\TaTp;
use App\Models\TecnoAcademia;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaTpRequest;
use App\Models\Regional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class TaTpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Convocatoria $convocatoria)
    {
        $this->authorize('formular-proyecto');

        return Inertia::render('Convocatorias/Proyectos/TaTp/Index', [
            'convocatoria'  => $convocatoria->only('id'),
            'filters'       => request()->all('search'),
            'tatp'          => TaTp::getProyectosPorRol($convocatoria)->appends(['search' => request()->search]),
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

        return Inertia::render('Convocatorias/Proyectos/TaTp/Create', [
            'convocatoria'      => $convocatoria->only('id', 'min_fecha_inicio_proyectos_tatp', 'max_fecha_finalizacion_proyectos_tatp'),
            'tecnoacademias'    => TecnoAcademia::select('id as value', 'nombre as label')->get(),
            'rolesTa'           => collect(json_decode(Storage::get('json/roles-sennova-ta.json'), true)),
            'rolesTp'           => collect(json_decode(Storage::get('json/roles-sennova-tp.json'), true)),
            'authUserRegional'  => Auth::user()->centroFormacion->regional->id
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaTpRequest $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('formular-proyecto');

        $proyecto = new Proyecto();
        $proyecto->centroFormacion()->associate($request->centro_formacion_id);
        $proyecto->lineaProgramatica()->associate($request->linea_programatica_id);
        $proyecto->convocatoria()->associate($convocatoria);
        $proyecto->save();

        $tatp = new TaTp();
        $tatp->fecha_inicio                         = $request->fecha_inicio;
        $tatp->fecha_finalizacion                   = $request->fecha_finalizacion;
        $tatp->max_meses_ejecucion                  = $request->max_meses_ejecucion;
        $tatp->resumen                              = 'Por favor diligencie el resumen del proyecto';
        $tatp->antecedentes                         = 'Por favor diligencie los antecedentes del proyecto';
        $tatp->marco_conceptual                     = 'Por favor diligencie el marco conceptual del proyecto';
        $tatp->metodologia                          = 'Por favor diligencie la metodología del proyecto';
        $tatp->propuesta_sostenibilidad             = 'Por favor diligencie la propuesta de sotenibilidad del proyecto';
        $tatp->bibliografia                         = 'Por favor diligencie la bibliografía';
        $tatp->impacto_municipios                   = 'Describa el beneficio en los municipios';
        $tatp->impacto_centro_formacion             = 'Describa el beneficio en el centro de formación';
        $tatp->identificacion_problema              = 'Describa la identificación del problema';

        $tatp->resumen_regional                     = 'Por favor diligencie el resumen regional';
        $tatp->antecedentes_regional                = 'Por favor diligencie los antecedentes regional';
        $tatp->retos_oportunidades                  = 'Descripción de Retos y prioridades locales y regionales en los cuales la Tecnoacademia tiene impacto';
        $tatp->pertinencia_territorio               = 'Justificacion y pertinencia en el territorio';
        $tatp->metodologia_local                    = 'Descripcion de La Metodología aplicada a nivel local';
        $tatp->numero_instituciones                 = 0;
        $tatp->nombre_instituciones                 = null;

        $tatp->tecnoacademiaLineaTecnologica()->associate($request->tecnoacademia_linea_tecnologica_id);
        if ($request->codigo_linea_programatica == 69) {
            $tatp->nodoTecnoparque()->associate($request->nodo_tecnoparque_id);
        }

        $proyecto->taTp()->save($tatp);

        $proyecto->participantes()->attach(
            Auth::user()->id,
            [
                'es_formulador'     => true,
                'cantidad_meses'    => $request->cantidad_meses,
                'cantidad_horas'    => $request->cantidad_horas,
                'rol_sennova'       => $request->rol_sennova,
            ]
        );

        DB::select('SELECT public."generalidades_ta"(' . $proyecto->id . ')');

        return redirect()->route('convocatorias.tatp.edit', [$convocatoria, $tatp])->with('success', 'El recurso se ha creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TaTp  $tatp
     * @return \Illuminate\Http\Response
     */
    public function show(Convocatoria $convocatoria, Proyecto $proyecto, TaTp $tatp)
    {
        $this->authorize('visualizar-proyecto-autor', [$tatp->proyecto]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TaTp  $tatp
     * @return \Illuminate\Http\Response
     */
    public function edit(Convocatoria $convocatoria, Proyecto $proyecto, TaTp $tatp)
    {
        $this->authorize('visualizar-proyecto-autor', [$tatp->proyecto]);

        $tatp->codigo_linea_programatica = $tatp->proyecto->lineaProgramatica->codigo;
        $tatp->precio_proyecto           = $tatp->proyecto->precioProyecto;
        $tatp->proyecto->centroFormacion;

        return Inertia::render('Convocatorias/Proyectos/TaTp/Edit', [
            'convocatoria'                      => $convocatoria->only('id', 'min_fecha_inicio_proyectos_tatp', 'max_fecha_finalizacion_proyectos_tatp'),
            'tatp'                              => $tatp,
            'tecnoacademiaRelacionada'          => $tatp->tecnoacademiaLineaTecnologica()->exists() ? $tatp->tecnoacademiaLineaTecnologica->tecnoacademia->id : null,
            'lineaTecnologicaRelacionada'       => $tatp->tecnoacademiaLineaTecnologica()->exists() ? $tatp->tecnoacademiaLineaTecnologica->id : null,
            'regionales'                        => Regional::select('id as value', 'nombre as label', 'codigo')->orderBy('nombre')->get(),
            'tecnoacademias'                    => TecnoAcademia::select('id as value', 'nombre as label')->get(),
            'proyectoMunicipios'                => $tatp->proyecto->municipios()->select('municipios.id as value', 'municipios.nombre as label', 'regionales.nombre as group', 'regionales.codigo')->join('regionales', 'regionales.id', 'municipios.regional_id')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TaTp  $tatp
     * @return \Illuminate\Http\Response
     */
    public function update(TaTpRequest $request, Convocatoria $convocatoria, Proyecto $proyecto, TaTp $tatp)
    {
        $this->authorize('modificar-proyecto-autor', [$tatp->proyecto]);

        $tatp->fecha_inicio                         = $request->fecha_inicio;
        $tatp->fecha_finalizacion                   = $request->fecha_finalizacion;
        $tatp->max_meses_ejecucion                  = $request->max_meses_ejecucion;
        $tatp->resumen                              = $request->resumen;
        $tatp->antecedentes                         = $request->antecedentes;
        $tatp->justificacion                        = $request->justificacion;
        $tatp->marco_conceptual                     = $request->marco_conceptual;
        $tatp->bibliografia                         = $request->bibliografia;
        $tatp->impacto_municipios                   = $request->impacto_municipios;
        $tatp->impacto_centro_formacion             = $request->impacto_centro_formacion;

        $tatp->resumen_regional                     = $request->resumen_regional;
        $tatp->antecedentes_regional                = $request->antecedentes_regional;
        $tatp->retos_oportunidades                  = $request->retos_oportunidades;
        $tatp->pertinencia_territorio               = $request->pertinencia_territorio;
        $tatp->metodologia_local                    = $request->metodologia_local;

        $tatp->proyecto->municipios()->sync($request->municipios);

        if ($request->codigo_linea_programatica == 70) {
            $tatp->numero_instituciones                 = count(json_decode($request->nombre_instituciones));
            $tatp->nombre_instituciones                 = $request->nombre_instituciones;
            $tatp->tecnoacademiaLineaTecnologica()->associate($request->tecnoacademia_linea_tecnologica_id);
            $tatp->nodoTecnoparque()->associate(null);
        } else if ($request->codigo_linea_programatica == 69) {
            $tatp->numero_instituciones                 = null;
            $tatp->nombre_instituciones                 = null;
            $tatp->tecnoacademiaLineaTecnologica()->associate(null);
            $tatp->nodoTecnoparque()->associate($request->nodo_tecnoparque_id);
        }

        $tatp->save();

        return redirect()->back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TaTp  $tatp
     * @return \Illuminate\Http\Response
     */
    public function destroy(Convocatoria $convocatoria, Proyecto $proyecto, TaTp $tatp)
    {
        $this->authorize('modificar-proyecto-autor', [$tatp->proyecto]);

        $tatp->proyecto()->delete();

        return redirect()->route('convocatorias.tatp.index', [$convocatoria, $proyecto])->with('success', 'El recurso se ha eliminado correctamente.');
    }
}
