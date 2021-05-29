<?php

namespace App\Http\Controllers;

use App\Models\Proyecto;
use App\Models\Idi;
use App\Models\Convocatoria;
use App\Models\MesaSectorial;
use App\Models\Tecnoacademia;
use App\Http\Requests\IdiRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class IdiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Convocatoria $convocatoria)
    {
        $this->authorize('viewAny', [Idi::class]);

        return Inertia::render('Convocatorias/Proyectos/Idi/Index', [
            'convocatoria'  => $convocatoria->only('id'),
            'filters'       => request()->all('search'),
            'idi'           => Idi::select('idi.id', 'idi.titulo', 'idi.fecha_inicio', 'idi.fecha_finalizacion')->join('proyectos', 'idi.id', 'proyectos.id')->where('proyectos.convocatoria_id', $convocatoria->id)->orderBy('titulo', 'ASC')
                ->filterIdi(request()->only('search'))->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Convocatoria $convocatoria)
    {
        $this->authorize('create', [Idi::class]);

        return Inertia::render('Convocatorias/Proyectos/Idi/Create', [
            'convocatoria' => $convocatoria->only('id')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(IdiRequest $request, Convocatoria $convocatoria)
    {
        $this->authorize('create', [Idi::class]);

        $proyecto = new Proyecto();
        $proyecto->centroFormacion()->associate($request->centro_formacion_id);
        $proyecto->tipoProyecto()->associate($request->tipo_proyecto_id);
        $proyecto->convocatoria()->associate($convocatoria);
        $proyecto->save();

        $idi = new Idi();
        $idi->titulo                                = $request->titulo;
        $idi->fecha_inicio                          = $request->fecha_inicio;
        $idi->fecha_finalizacion                    = $request->fecha_finalizacion;
        $idi->video                                 = null;
        $idi->justificacion_industria_4             = null;
        $idi->justificacion_economia_naranja        = null;
        $idi->justificacion_politica_discapacidad   = null;
        $idi->resumen                               = 'Por favor diligencie el resumen del proyecto';
        $idi->antecedentes                          = 'Por favor diligencie los antecedentes del proyecto';
        $idi->marco_conceptual                      = 'Por favor diligencie el marco conceptual del proyecto';
        $idi->metodologia                           = 'Por favor diligencie la metodología del proyecto';
        $idi->propuesta_sostenibilidad              = 'Por favor diligencie la propuesta de sotenibilidad del proyecto';
        $idi->bibliografia                          = 'Por favor diligencie la bibliografía';
        $idi->numero_aprendices                     = 0;
        $idi->impacto_municipios                    = 'Describa el beneficio en los municipios';
        $idi->impacto_centro_formacion              = 'Describa el beneficio en los municipios';

        $idi->muestreo                              = null;
        $idi->actividades_muestreo                  = $request->muestreo == 1 ? $request->actividades_muestreo : null;
        $idi->objetivo_muestreo                     = $request->muestreo == 1 ? $request->objetivo_muestreo  : null;

        $idi->lineaInvestigacion()->associate($request->linea_investigacion_id);
        $idi->disciplinaSubareaConocimiento()->associate($request->disciplina_subarea_conocimiento_id);
        $idi->tematicaEstrategica()->associate($request->tematica_estrategica_id);
        $idi->redConocimiento()->associate($request->red_conocimiento_id);
        $idi->actividadEconomica()->associate($request->actividad_economica_id);

        $proyecto->idi()->save($idi);

        return redirect()->route('convocatorias.idi.edit', [$convocatoria, $idi])->with('success', 'El recurso se ha creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Idi  $idi
     * @return \Illuminate\Http\Response
     */
    public function show(Convocatoria $convocatoria, Idi $idi)
    {
        $this->authorize('view', [Idi::class, $idi]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Idi  $idi
     * @return \Illuminate\Http\Response
     */
    public function edit(Convocatoria $convocatoria, Idi $idi)
    {
        $this->authorize('update', [Idi::class, $idi]);

        $idi->codigo_linea_programatica = $idi->proyecto->tipoProyecto->lineaProgramatica->codigo;
        $idi->precio_proyecto           = $idi->proyecto->precioProyecto;

        return Inertia::render('Convocatorias/Proyectos/Idi/Edit', [
            'convocatoria'                      => $convocatoria->only('id'),
            'idi'                               => $idi,
            'mesasSectorialesRelacionadas'      => $idi->mesasSectoriales()->pluck('id'),
            'lineasTecnologicasRelacionadas'    => $idi->tecnoacademiaLineasTecnologicas()->pluck('id'),
            'tecnoacademia'                     => $idi->tecnoacademiaLineasTecnologicas()->first() ? $idi->tecnoacademiaLineasTecnologicas()->first()->tecnoacademia->only('id', 'nombre') : null,
            'mesasSectoriales'                  => MesaSectorial::select('id', 'nombre')->get('id'),
            'tecnoacademias'                    => TecnoAcademia::select('id as value', 'nombre as label')->get(),
            'opcionesIDiDropdown'               => json_decode(Storage::get('json/opciones-idi-dropdown.json'), true),
            'proyectoMunicipios'                => $idi->proyecto->municipios()->select('municipios.id as value', 'municipios.nombre as label', 'regionales.nombre as group')->join('regionales', 'regionales.id', 'municipios.regional_id')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Idi  $idi
     * @return \Illuminate\Http\Response
     */
    public function update(IdiRequest $request, Convocatoria $convocatoria, Idi $idi)
    {
        $this->authorize('update', [Idi::class, $idi]);

        $idi->titulo                                = $request->titulo;
        $idi->fecha_inicio                          = $request->fecha_inicio;
        $idi->fecha_finalizacion                    = $request->fecha_finalizacion;
        $idi->video                                 = $request->video;
        $idi->justificacion_industria_4             = $request->justificacion_industria_4;
        $idi->justificacion_economia_naranja        = $request->justificacion_economia_naranja;
        $idi->justificacion_politica_discapacidad   = $request->justificacion_politica_discapacidad;
        $idi->resumen                               = $request->resumen;
        $idi->antecedentes                          = $request->antecedentes;
        $idi->marco_conceptual                      = $request->marco_conceptual;
        $idi->metodologia                           = $request->metodologia;
        $idi->propuesta_sostenibilidad              = $request->propuesta_sostenibilidad;
        $idi->bibliografia                          = $request->bibliografia;
        $idi->numero_aprendices                     = $request->numero_aprendices;
        $idi->impacto_municipios                    = $request->impacto_municipios;
        $idi->impacto_centro_formacion              = $request->impacto_centro_formacion;

        $idi->muestreo                              = $request->muestreo;
        $idi->actividades_muestreo                  = $request->muestreo == 1 ? $request->actividades_muestreo : null;
        $idi->objetivo_muestreo                     = $request->muestreo == 1 ? $request->objetivo_muestreo  : null;

        $idi->lineaInvestigacion()->associate($request->linea_investigacion_id);
        $idi->disciplinaSubareaConocimiento()->associate($request->disciplina_subarea_conocimiento_id);
        $idi->tematicaEstrategica()->associate($request->tematica_estrategica_id);
        $idi->redConocimiento()->associate($request->red_conocimiento_id);
        $idi->actividadEconomica()->associate($request->actividad_economica_id);

        $idi->relacionado_plan_tecnologico          = $request->relacionado_plan_tecnologico;
        $idi->relacionado_agendas_competitividad    = $request->relacionado_agendas_competitividad;
        $idi->relacionado_mesas_sectoriales         = $request->relacionado_mesas_sectoriales;
        $idi->relacionado_tecnoacademia             = $request->relacionado_tecnoacademia;

        $idi->proyecto()->update(['tipo_proyecto_id' => $request->tipo_proyecto_id]);
        $idi->proyecto()->update(['centro_formacion_id' => $request->centro_formacion_id]);
        $idi->proyecto->municipios()->sync($request->municipios);

        $idi->save();

        $request->relacionado_mesas_sectoriales == 1 ? $idi->mesasSectoriales()->sync($request->mesa_sectorial_id) : $idi->mesasSectoriales()->detach();
        $request->relacionado_tecnoacademia == 1 ? $idi->tecnoacademiaLineasTecnologicas()->sync($request->linea_tecnologica_id) : $idi->tecnoacademiaLineasTecnologicas()->detach();


        return redirect()->back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Idi  $idi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Convocatoria $convocatoria, Idi $idi)
    {
        $this->authorize('delete', [Idi::class, $idi]);

        if (!Hash::check($request->password, Auth::user()->password)) {
            return redirect()->back()
                ->withErrors(['password' => __('The password is incorrect.')]);
        }

        $idi->delete();

        return redirect()->route('convocatorias.idi.index', [$convocatoria])->with('success', 'El recurso se ha eliminado correctamente.');
    }
}
