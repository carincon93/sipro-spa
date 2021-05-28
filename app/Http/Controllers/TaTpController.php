<?php

namespace App\Http\Controllers;

use App\Models\Convocatoria;
use App\Models\Proyecto;
use App\Models\TaTp;
use App\Models\TecnoAcademia;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaTpRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TaTpController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('viewAny', [TaTp::class]);

        return Inertia::render('Convocatorias/Proyectos/TaTp/Index', [
            'convocatoria'  => $convocatoria->only('id'),
            'filters'       => request()->all('search'),
            'tatp'          => TaTp::orderBy('titulo', 'ASC')
                ->filterTaTp(request()->only('search'))->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('create', [TaTp::class]);

        return Inertia::render('Convocatorias/Proyectos/TaTp/Create', [
            'convocatoria'      => $convocatoria->only('id'),
            'tecnoacademias'    => TecnoAcademia::select('id as value', 'nombre as label')->get(),
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
        $this->authorize('create', [TaTp::class]);

        $proyecto = new Proyecto();
        $proyecto->centroFormacion()->associate($request->centro_formacion_id);
        $proyecto->tipoProyecto()->associate($request->tipo_proyecto_id);
        $proyecto->convocatoria()->associate($convocatoria);
        $proyecto->save();

        $tatp = new TaTp();
        $tatp->titulo                               = $request->titulo;
        $tatp->fecha_inicio                         = $request->fecha_inicio;
        $tatp->fecha_finalizacion                   = $request->fecha_finalizacion;
        $tatp->resumen                              = 'Por favor diligencie el resumen del proyecto';
        $tatp->antecedentes                         = 'Por favor diligencie los antecedentes del proyecto';
        $tatp->marco_conceptual                     = 'Por favor diligencie el marco conceptual del proyecto';
        $tatp->metodologia                          = 'Por favor diligencie la metodología del proyecto';
        $tatp->propuesta_sostenibilidad             = 'Por favor diligencie la propuesta de sotenibilidad del proyecto';
        $tatp->bibliografia                         = 'Por favor diligencie la bibliografía';
        $tatp->impacto_municipios                   = 'Describa el beneficio en los municipios';
        $tatp->impacto_centro_formacion             = 'Describa el beneficio en los municipios';

        $tatp->impacto_municipios                   = 'Por favor diligencie el impacto en los municipios';
        $tatp->resumen_regional                     = 'Por favor diligencie el resumen regional';
        $tatp->antecedentes_regional                = 'Por favor diligencie los antecedentes regional';
        $tatp->retos_oportunidades                  = 'Descripción de Retos y prioridades locales y regionales en los cuales la Tecnoacademia tiene impacto';
        $tatp->pertinencia_territorio               = 'Justificacion y pertinencia en el territorio';
        $tatp->metodologia_local                    = 'Descripcion de La Metodología aplicada a nivel local';
        $tatp->numero_instituciones                 = 0;
        $tatp->nombre_instituciones                 = null;

        $tatp->tecnoacademiaLineaTecnologica()->associate($request->tecnoacademia_linea_tecnologica_id);

        $proyecto->tatp()->save($tatp);

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
        $this->authorize('view', [TaTp::class, $tatp]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TaTp  $tatp
     * @return \Illuminate\Http\Response
     */
    public function edit(Convocatoria $convocatoria, Proyecto $proyecto, TaTp $tatp)
    {
        $this->authorize('update', [TaTp::class, $tatp]);

        $tatp->codigo_linea_programatica = $tatp->proyecto->tipoProyecto->lineaProgramatica->codigo;
        $tatp->precio_proyecto           = $tatp->proyecto->precioProyecto;

        return Inertia::render('Convocatorias/Proyectos/TaTp/Edit', [
            'convocatoria'                      => $convocatoria->only('id'),
            'tatp'                              => $tatp,
            'tecnoacademiaRelacionada'          => $tatp->tecnoacademiaLineaTecnologica->tecnoacademia->id,
            'lineaTecnologicaRelacionada'       => $tatp->tecnoacademiaLineaTecnologica->id,
            'tecnoacademias'                    => TecnoAcademia::select('id as value', 'nombre as label')->get(),
            'proyectoMunicipios'                => $tatp->proyecto->municipios()->select('municipios.id as value', 'municipios.nombre as label', 'departamentos.nombre as group')->join('departamentos', 'departamentos.id', 'municipios.departamento_id')->get(),
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
        $this->authorize('update', [TaTp::class, $tatp]);

        $tatp->titulo                               = $request->titulo;
        $tatp->fecha_inicio                         = $request->fecha_inicio;
        $tatp->fecha_finalizacion                   = $request->fecha_finalizacion;
        $tatp->resumen                              = $request->resumen;
        $tatp->antecedentes                         = $request->antecedentes;
        $tatp->marco_conceptual                     = $request->marco_conceptual;
        $tatp->metodologia                          = $request->metodologia;
        $tatp->propuesta_sostenibilidad             = $request->propuesta_sostenibilidad;
        $tatp->bibliografia                         = $request->bibliografia;
        $tatp->impacto_municipios                   = $request->impacto_municipios;
        $tatp->impacto_centro_formacion             = $request->impacto_centro_formacion;

        $tatp->impacto_municipios                   = $request->impacto_municipios;
        $tatp->resumen_regional                     = $request->resumen_regional;
        $tatp->antecedentes_regional                = $request->antecedentes_regional;
        $tatp->retos_oportunidades                  = $request->retos_oportunidades;
        $tatp->pertinencia_territorio               = $request->pertinencia_territorio;
        $tatp->metodologia_local                    = $request->metodologia_local;
        $tatp->numero_instituciones                 = count(json_decode($request->nombre_instituciones));
        $tatp->nombre_instituciones                 = $request->nombre_instituciones;

        $tatp->tecnoacademiaLineaTecnologica()->associate($request->tecnoacademia_linea_tecnologica_id);
        $tatp->proyecto()->update(['tipo_proyecto_id' => $request->tipo_proyecto_id]);
        $tatp->proyecto()->update(['centro_formacion_id' => $request->centro_formacion_id]);
        $tatp->proyecto->municipios()->sync($request->municipios);

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
        $this->authorize('delete', [TaTp::class, $tatp]);

        $tatp->delete();

        return redirect()->route('convocatorias.proyectos.tatp.index', [$convocatoria, $proyecto])->with('success', 'El recurso se ha eliminado correctamente.');
    }
}
