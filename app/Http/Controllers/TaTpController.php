<?php

namespace App\Http\Controllers;

use App\Models\Convocatoria;
use App\Models\Proyecto;
use App\Models\TaTp;
use App\Models\TecnoAcademia;
use App\Http\Controllers\Controller;
use App\Http\Requests\TaTpRequest;
use App\Models\Regional;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $this->authorize('viewAny', [TaTp::class]);

        return Inertia::render('Convocatorias/Proyectos/TaTp/Index', [
            'convocatoria'  => $convocatoria->only('id'),
            'filters'       => request()->all('search'),
            'tatp'          => TaTp::select('id', 'fecha_inicio', 'fecha_finalizacion', 'tecnoacademia_linea_tecnologica_id', 'nodo_tecnoparque_id')->with('tecnoacademiaLineaTecnologica.tecnoacademia', 'nodoTecnoparque')->filterTaTp(request()->only('search'))->paginate()->appends(['search' => request()->search]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Convocatoria $convocatoria)
    {
        $this->authorize('create', [TaTp::class]);

        return Inertia::render('Convocatorias/Proyectos/TaTp/Create', [
            'convocatoria'      => $convocatoria->only('id', 'min_fecha_inicio_proyectos', 'max_fecha_finalizacion_proyectos'),
            'tecnoacademias'    => TecnoAcademia::select('id as value', 'nombre as label')->get(),
            'roles'             => Role::select('id as value', 'name as label')->where('visible_participantes', 1)->orderBy('name', 'ASC')->get(),
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

        $proyecto->taTp()->save($tatp);

        $proyecto->participantes()->attach(
            Auth::user()->id,
            [
                'es_autor'          => true,
                'cantidad_meses'    => $request->cantidad_meses,
                'cantidad_horas'    => $request->cantidad_horas,
                'rol_id'            => $request->rol_id,
            ]
        );

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
            'convocatoria'                      => $convocatoria->only('id', 'min_fecha_inicio_proyectos', 'max_fecha_finalizacion_proyectos'),
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
        $this->authorize('update', [TaTp::class, $tatp]);

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

        $tatp->proyecto()->update(['tipo_proyecto_id' => $request->tipo_proyecto_id]);
        $tatp->proyecto()->update(['centro_formacion_id' => $request->centro_formacion_id]);
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
        $this->authorize('delete', [TaTp::class, $tatp]);

        $tatp->delete();

        return redirect()->route('convocatorias.proyectos.tatp.index', [$convocatoria, $proyecto])->with('success', 'El recurso se ha eliminado correctamente.');
    }
}
