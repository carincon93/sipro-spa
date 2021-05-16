<?php

namespace App\Http\Controllers;

use App\Models\Municipio;
use App\Models\Proyecto;
use App\Models\IDi;
use App\Models\Convocatoria;
use App\Models\MesaSectorial;
use App\Models\Tecnoacademia;
use App\Http\Requests\IDiRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class IDiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Convocatoria $convocatoria)
    {
        $this->authorize('viewAny', [IDi::class]);

        return Inertia::render('Convocatorias/Proyectos/IDi/Index', [
            'filters'       => request()->all('search'),
            'convocatoria'  => $convocatoria->only('id'),
            'IDi'           => IDi::select('idi.id', 'idi.titulo', 'idi.fecha_inicio', 'idi.fecha_finalizacion')->join('proyectos', 'idi.id', 'proyectos.id')->where('proyectos.convocatoria_id', $convocatoria->id)->orderBy('titulo', 'ASC')
                ->filterIDi(request()->only('search'))->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Convocatoria $convocatoria)
    {
        $this->authorize('create', [IDi::class]);

        return Inertia::render('Convocatorias/Proyectos/IDi/Create', [
            'convocatoria' => $convocatoria->only('id')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(IDiRequest $request, Convocatoria $convocatoria)
    {
        $this->authorize('create', [IDi::class]);

        $proyecto = new Proyecto();
        $proyecto->centroFormacion()->associate($request->centro_formacion_id);
        $proyecto->tipoProyecto()->associate($request->tipo_proyecto_id);
        $proyecto->convocatoria()->associate($convocatoria);
        $proyecto->save();

        $IDi = new IDi();
        $IDi->titulo                                = $request->titulo;
        $IDi->fecha_inicio                          = $request->fecha_inicio;
        $IDi->fecha_finalizacion                    = $request->fecha_finalizacion;
        $IDi->video                                 = null;
        $IDi->justificacion_industria_4             = null;
        $IDi->justificacion_economia_naranja       = null;
        $IDi->justificacion_politica_discapacidad   = null;
        $IDi->resumen                               = 'Por favor diligencie el resumen del proyecto';
        $IDi->antecedentes                          = 'Por favor diligencie los antecedentes del proyecto';
        $IDi->marco_conceptual                      = 'Por favor diligencie el marco conceptual del proyecto';
        $IDi->metodologia                           = 'Por favor diligencie la metodología del proyecto';
        $IDi->propuesta_sostenibilidad              = 'Por favor diligencie la propuesta de sotenibilidad del proyecto';
        $IDi->bibliografia                          = 'Por favor diligencie la bibliografía';
        $IDi->numero_aprendices                     = 0;
        $IDi->impacto_                      = 'Describa el beneficio en los municipios';
        $IDi->impacto_centro_formacion              = 'Describa el beneficio en los municipios';

        $IDi->muestreo                              = null;
        $IDi->actividades_muestreo                  = $request->muestreo == 1 ? $request->actividades_muestreo : null;
        $IDi->objetivo_muestreo                     = $request->muestreo == 1 ? $request->objetivo_muestreo  : null;

        $IDi->lineaInvestigacion()->associate($request->linea_investigacion_id);
        $IDi->disciplinaSubareaConocimiento()->associate($request->disciplina_subarea_conocimiento_id);
        $IDi->tematicaEstrategica()->associate($request->tematica_estrategica_id);
        $IDi->redConocimiento()->associate($request->red_conocimiento_id);
        $IDi->actividadEconomica()->associate($request->actividad_economica_id);

        $proyecto->IDi()->save($IDi);

        return redirect()->route('convocatorias.idi.edit', [$convocatoria, $IDi])->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\IDi  $IDi
     * @return \Illuminate\Http\Response
     */
    public function show(Convocatoria $convocatoria, IDi $IDi)
    {
        $this->authorize('view', [IDi::class, $IDi]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\IDi  $IDi
     * @return \Illuminate\Http\Response
     */
    public function edit(Convocatoria $convocatoria, IDi $IDi)
    {
        $this->authorize('update', [IDi::class, $IDi]);

        $IDi->codigo_linea_programatica = $IDi->proyecto->tipoProyecto->lineaProgramatica->codigo;

        return Inertia::render('Convocatorias/Proyectos/IDi/Edit', [
            'convocatoria'                      => $convocatoria->only('id'),
            'idi'                               => $IDi,
            'mesasSectorialesRelacionadas'      => $IDi->mesasSectoriales()->pluck('id'),
            'lineasTecnologicasRelacionadas'    => $IDi->lineasTecnologicas()->pluck('id'),
            'tecnoacademia'                     => $IDi->lineasTecnologicas()->first() ? $IDi->lineasTecnologicas()->first()->tecnoacademia->only('id', 'nombre') : null,
            'mesasSectoriales'                  => MesaSectorial::select('id', 'nombre')->get('id'),
            'tecnoacademias'                    => TecnoAcademia::select('id as value', 'nombre as label')->get(),
            'opcionesIDiDropdown'               => json_decode(Storage::get('json/opciones-idi-dropdown.json'), true),
            'proyectoMunicipios'                => $IDi->proyecto->municipios()->select('municipios.id as value', 'municipios.nombre as label', 'departamentos.nombre as group')->join('departamentos', 'departamentos.id', 'municipios.departamento_id')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\IDi  $IDi
     * @return \Illuminate\Http\Response
     */
    public function update(IDiRequest $request, Convocatoria $convocatoria, IDi $IDi)
    {
        $this->authorize('update', [IDi::class, $IDi]);

        $IDi->titulo                                = $request->titulo;
        $IDi->fecha_inicio                          = $request->fecha_inicio;
        $IDi->fecha_finalizacion                    = $request->fecha_finalizacion;
        $IDi->video                                 = $request->video;
        $IDi->justificacion_industria_4             = $request->justificacion_industria_4;
        $IDi->justificacion_economia_naranja        = $request->justificacion_economia_naranja;
        $IDi->justificacion_politica_discapacidad   = $request->justificacion_politica_discapacidad;
        $IDi->resumen                               = $request->resumen ;
        $IDi->antecedentes                          = $request->antecedentes;
        $IDi->marco_conceptual                      = $request->marco_conceptual;
        $IDi->metodologia                           = $request->metodologia;
        $IDi->propuesta_sostenibilidad              = $request->propuesta_sostenibilidad;
        $IDi->bibliografia                          = $request->bibliografia;
        $IDi->numero_aprendices                     = $request->numero_aprendices;
        $IDi->impacto_municipios                    = $request->impacto_municipios;
        $IDi->impacto_centro_formacion              = $request->impacto_centro_formacion;

        $IDi->muestreo                              = $request->muestreo;
        $IDi->actividades_muestreo                  = $request->muestreo == 1 ? $request->actividades_muestreo : null;
        $IDi->objetivo_muestreo                     = $request->muestreo == 1 ? $request->objetivo_muestreo  : null;

        $IDi->lineaInvestigacion()->associate($request->linea_investigacion_id);
        $IDi->disciplinaSubareaConocimiento()->associate($request->disciplina_subarea_conocimiento_id);
        $IDi->tematicaEstrategica()->associate($request->tematica_estrategica_id);
        $IDi->redConocimiento()->associate($request->red_conocimiento_id);
        $IDi->actividadEconomica()->associate($request->actividad_economica_id);

        $IDi->relacionado_plan_tecnologico          = $request->relacionado_plan_tecnologico;
        $IDi->relacionado_agendas_competitividad    = $request->relacionado_agendas_competitividad;
        $IDi->relacionado_mesas_sectoriales         = $request->relacionado_mesas_sectoriales;
        $IDi->relacionado_tecnoacademia             = $request->relacionado_tecnoacademia;

        $IDi->proyecto()->update(['tipo_proyecto_id' => $request->tipo_proyecto_id]);
        $IDi->proyecto()->update(['centro_formacion_id' => $request->centro_formacion_id]);
        $IDi->proyecto->municipios()->sync($request->municipios);

        $IDi->save();

        $request->relacionado_mesas_sectoriales == 1 ? $IDi->mesasSectoriales()->sync($request->mesa_sectorial_id) : $IDi->mesasSectoriales()->detach();
        $request->relacionado_tecnoacademia == 1 ? $IDi->lineasTecnologicas()->sync($request->linea_tecnologica_id) : $IDi->lineasTecnologicas()->detach();


        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\IDi  $IDi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Convocatoria $convocatoria, IDi $IDi)
    {
        $this->authorize('delete', [IDi::class, $IDi]);

        if ( !Hash::check($request->password, Auth::user()->password) ) {
            return redirect()->back()
                ->withErrors(['password' => __('The password is incorrect.')]);
        }

        $IDi->delete();

        return redirect()->route('convocatorias.idi.index', [$convocatoria])->with('success', 'The resource has been deleted successfully.');
    }
}
