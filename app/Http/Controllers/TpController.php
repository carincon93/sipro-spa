<?php

namespace App\Http\Controllers;

use App\Models\Convocatoria;
use App\Models\Proyecto;
use App\Models\Tp;
use App\Http\Controllers\Controller;
use App\Http\Requests\TpRequest;
use App\Models\Regional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class TpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Convocatoria $convocatoria)
    {
        $this->authorize('formular-proyecto');

        return Inertia::render('Convocatorias/Proyectos/Tp/Index', [
            'convocatoria'  => $convocatoria->only('id'),
            'filters'       => request()->all('search'),
            'tp'            => Tp::getProyectosPorRol($convocatoria)->appends(['search' => request()->search]),
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

        return Inertia::render('Convocatorias/Proyectos/Tp/Create', [
            'convocatoria'      => $convocatoria->only('id', 'min_fecha_inicio_proyectos_tp', 'max_fecha_finalizacion_proyectos_tp'),
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
    public function store(TpRequest $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('formular-proyecto');

        $proyecto = new Proyecto();
        $proyecto->centroFormacion()->associate($request->centro_formacion_id);
        $proyecto->lineaProgramatica()->associate(4);
        $proyecto->convocatoria()->associate($convocatoria);
        $proyecto->save();

        $tp = new Tp();
        $tp->fecha_inicio                         = $request->fecha_inicio;
        $tp->fecha_finalizacion                   = $request->fecha_finalizacion;
        $tp->max_meses_ejecucion                  = $request->max_meses_ejecucion;
        $tp->resumen                              = '';
        $tp->antecedentes                         = '';
        $tp->marco_conceptual                     = '';
        $tp->metodologia                          = '';
        $tp->propuesta_sostenibilidad             = '';
        $tp->bibliografia                         = '';
        $tp->impacto_municipios                   = '';
        $tp->impacto_centro_formacion             = '';
        $tp->identificacion_problema              = '';

        $tp->resumen_regional                     = '';
        $tp->antecedentes_regional                = '';
        $tp->retos_oportunidades                  = '';
        $tp->pertinencia_territorio               = '';
        $tp->metodologia_local                    = '';
        $tp->numero_instituciones                 = 0;
        $tp->nombre_instituciones                 = null;

        $tp->nodoTecnoparque()->associate($request->nodo_tecnoparque_id);

        $proyecto->tp()->save($tp);

        $proyecto->participantes()->attach(
            Auth::user()->id,
            [
                'es_formulador'     => true,
                'cantidad_meses'    => $request->cantidad_meses,
                'cantidad_horas'    => $request->cantidad_horas,
                'rol_sennova'       => $request->rol_sennova,
            ]
        );

        return redirect()->route('convocatorias.tp.edit', [$convocatoria, $tp])->with('success', 'El recurso se ha creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tp  $tp
     * @return \Illuminate\Http\Response
     */
    public function show(Convocatoria $convocatoria, Proyecto $proyecto, Tp $tp)
    {
        $this->authorize('visualizar-proyecto-autor', [$tp->proyecto]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tp  $tp
     * @return \Illuminate\Http\Response
     */
    public function edit(Convocatoria $convocatoria, Proyecto $proyecto, Tp $tp)
    {
        $this->authorize('visualizar-proyecto-autor', [$tp->proyecto]);

        $tp->codigo_linea_programatica = $tp->proyecto->lineaProgramatica->codigo;
        $tp->precio_proyecto           = $tp->proyecto->precioProyecto;
        $tp->proyecto->centroFormacion;

        return Inertia::render('Convocatorias/Proyectos/Tp/Edit', [
            'convocatoria'       => $convocatoria->only('id', 'min_fecha_inicio_proyectos_tp', 'max_fecha_finalizacion_proyectos_tp'),
            'tp'                 => $tp,
            'regionales'         => Regional::select('id as value', 'nombre as label', 'codigo')->orderBy('nombre')->get(),
            'proyectoMunicipios' => $tp->proyecto->municipios()->select('municipios.id as value', 'municipios.nombre as label', 'regionales.nombre as group', 'regionales.codigo')->join('regionales', 'regionales.id', 'municipios.regional_id')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tp  $tp
     * @return \Illuminate\Http\Response
     */
    public function update(TpRequest $request, Convocatoria $convocatoria, Proyecto $proyecto, Tp $tp)
    {
        $this->authorize('modificar-proyecto-autor', [$tp->proyecto]);

        $tp->fecha_inicio                         = $request->fecha_inicio;
        $tp->fecha_finalizacion                   = $request->fecha_finalizacion;
        $tp->max_meses_ejecucion                  = $request->max_meses_ejecucion;
        $tp->resumen                              = $request->resumen;
        $tp->antecedentes                         = $request->antecedentes;
        $tp->justificacion                        = $request->justificacion;
        $tp->marco_conceptual                     = $request->marco_conceptual;
        $tp->bibliografia                         = $request->bibliografia;
        $tp->impacto_municipios                   = $request->impacto_municipios;
        $tp->impacto_centro_formacion             = $request->impacto_centro_formacion;

        $tp->resumen_regional                     = $request->resumen_regional;
        $tp->antecedentes_regional                = $request->antecedentes_regional;
        $tp->retos_oportunidades                  = $request->retos_oportunidades;
        $tp->pertinencia_territorio               = $request->pertinencia_territorio;
        $tp->metodologia_local                    = $request->metodologia_local;

        $tp->numero_instituciones                 = null;
        $tp->nombre_instituciones                 = null;
        $tp->nodoTecnoparque()->associate($request->nodo_tecnoparque_id);

        $tp->proyecto->municipios()->sync($request->municipios);

        $tp->save();

        return redirect()->back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tp  $tp
     * @return \Illuminate\Http\Response
     */
    public function destroy(Convocatoria $convocatoria, Proyecto $proyecto, Tp $tp)
    {
        $this->authorize('modificar-proyecto-autor', [$tp->proyecto]);

        $tp->proyecto()->delete();

        return redirect()->route('convocatorias.tp.index', [$convocatoria, $proyecto])->with('success', 'El recurso se ha eliminado correctamente.');
    }
}
