<?php

namespace App\Http\Controllers;

use App\Http\Requests\RDIRequest;
use App\Models\Citie;
use App\Models\Project;
use App\Models\RDI;
use App\Models\Call;
use App\Models\SectorBasedCommittee;
use App\Models\TechnoAcademy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class RDIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Call $call)
    {
        $this->authorize('viewAny', [RDI::class]);

        return Inertia::render('Calls/Projects/RDI/Index', [
            'filters'   => request()->all('search'),
            'call'      => $call,
            'rdi'       => RDI::select('rdi.id', 'rdi.title', 'rdi.fecha_incio', 'rdi.fecha_finalizacion')->join('projects', 'rdi.id', 'projects.id')->where('projects.call_id', $call->id)->orderBy('title', 'ASC')
                ->filterRDI(request()->only('search'))->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Call $call)
    {
        $this->authorize('create', [RDI::class]);

        return Inertia::render('Calls/Projects/RDI/Create', [
            'call' => $call->only('id')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RDIRequest $request, Call $call)
    {
        $this->authorize('create', [RDI::class]);

        $project = new Project();
        $project->academicCentre()->associate($request->academic_centre_id);
        $project->projectType()->associate($request->project_type_id);
        $project->call()->associate($call);
        $project->save();

        $rdi = new RDI();
        $rdi->title                             = $request->title;
        $rdi->fecha_incio                        = $request->fecha_incio;
        $rdi->fecha_finalizacion                          = $request->fecha_finalizacion;
        $rdi->video                             = null;
        $rdi->justificacion_industria_4          = null;
        $rdi->justificacion_economica_naranja      = null;
        $rdi->justificacion_politica_discapacidad = null;
        $rdi->abstract                          = 'Por favor diligencie el resumen del proyecto';
        $rdi->antecedentes                = 'Por favor diligencie los antecedentes del proyecto';
        $rdi->marco_conceptual              = 'Por favor diligencie el marco conceptual del proyecto';
        $rdi->metodologia               = 'Por favor diligencie la metodología del proyecto';
        $rdi->propuesta_sostenibilidad           = 'Por favor diligencie la propuesta de sotenibilidad del proyecto';
        $rdi->bibliografia                      = 'Por favor diligencie la bibliografía';
        $rdi->numero_aprendices                          = 0;
        $rdi->impacto_ciudades                     = 'Describa el beneficio en los municipios';
        $rdi->impacto_centro_formacion                   = 'Describa el beneficio en los municipios';

        $rdi->muestreo                          = null;
        $rdi->actividades_muestreo                 = $request->muestreo == 1 ? $request->actividades_muestreo : null;
        $rdi->muestreo_objective                = $request->muestreo == 1 ? $request->muestreo_objective : null;

        $rdi->researchLine()->associate($request->linea_investigacion_id);
        $rdi->disciplinaSubareaConocimiento()->associate($request->disciplina_subarea_conocimiento_id);
        $rdi->strategicThematic()->associate($request->tematica_estrategica_id);
        $rdi->knowledgeNetwork()->associate($request->red_conocimiento_id);
        $rdi->economicActivity()->associate($request->actividad_economica_id);

        $project->rdi()->save($rdi);

        return redirect()->route('calls.rdi.edit', [$call, $rdi])->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RDI  $rdi
     * @return \Illuminate\Http\Response
     */
    public function show(Call $call, RDI $rdi)
    {
        $this->authorize('view', [RDI::class, $rdi]);

        $rdi->project;

        return Inertia::render('Calls/Projects/RDI/Show', [
            'call'  => $call,
            'rdi'   => $rdi
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RDI  $rdi
     * @return \Illuminate\Http\Response
     */
    public function edit(Call $call, RDI $rdi)
    {
        $this->authorize('update', [RDI::class, $rdi]);

        $rdi->project->projectType->programmaticLine;

        return Inertia::render('Calls/Projects/RDI/Editar', [
            'call'                          => $call->only('id'),
            'rdi'                           => $rdi,
            'relatedSectorBasedCommittees'  => $rdi->sectorBasedCommittees()->pluck('id'),
            'relatedTechnologicalLines'     => $rdi->technologicalLines()->pluck('id'),
            'technoAcademy'                 => $rdi->technologicalLines->first() ? $rdi->technologicalLines->first()->technoAcademy->only('id', 'name') : null,
            'sectorBasedCommittees'         => SectorBasedCommittee::select('id', 'name')->get('id'),
            'technoAcademies'               => TechnoAcademy::select('id as value', 'name as label')->get(),
            'rdiDropdownOptions'            => json_decode(Storage::get('json/rdi-dropdown-options.json'), true),
            'projectCities'                 => $rdi->project->cities()->select('cities.id as value', 'cities.name as label', 'departments.name as group')->join('departments', 'departments.id', 'cities.department_id')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RDI  $rdi
     * @return \Illuminate\Http\Response
     */
    public function update(RDIRequest $request, Call $call, RDI $rdi)
    {
        $this->authorize('update', [RDI::class, $rdi]);

        $rdi->title                             = $request->title;
        $rdi->fecha_incio                        = $request->fecha_incio;
        $rdi->fecha_finalizacion                          = $request->fecha_finalizacion;
        $rdi->video                             = $request->video;
        $rdi->justificacion_industria_4          = $request->justificacion_industria_4;
        $rdi->justificacion_economica_naranja      = $request->justificacion_economica_naranja;
        $rdi->justificacion_politica_discapacidad = $request->justificacion_politica_discapacidad;
        $rdi->abstract                          = $request->abstract;
        $rdi->antecedentes                = $request->antecedentes;
        $rdi->marco_conceptual              = $request->marco_conceptual;
        $rdi->metodologia               = $request->metodologia;
        $rdi->propuesta_sostenibilidad           = $request->propuesta_sostenibilidad;
        $rdi->bibliografia                      = $request->bibliografia;
        $rdi->numero_aprendices                          = $request->numero_aprendices;
        $rdi->impacto_ciudades                     = $request->impacto_ciudades;
        $rdi->impacto_centro_formacion                   = $request->impacto_centro_formacion;

        $rdi->muestreo                          = $request->muestreo;
        $rdi->actividades_muestreo                 = $request->muestreo == 1 ? $request->actividades_muestreo : null;
        $rdi->muestreo_objective                = $request->muestreo == 1 ? $request->muestreo_objective : null;

        $rdi->researchLine()->associate($request->linea_investigacion_id);
        $rdi->disciplinaSubareaConocimiento()->associate($request->disciplina_subarea_conocimiento_id);
        $rdi->strategicThematic()->associate($request->tematica_estrategica_id);
        $rdi->knowledgeNetwork()->associate($request->red_conocimiento_id);
        $rdi->economicActivity()->associate($request->actividad_economica_id);

        $rdi->relacionado_plan_tecnologico           = $request->relacionado_plan_tecnologico;
        $rdi->relacionado_agendas_competitividad   = $request->relacionado_agendas_competitividad;
        $rdi->relacionado_mesas_sectoriales       = $request->relacionado_mesas_sectoriales;
        $rdi->relacionado_tecnoacademia               = $request->relacionado_tecnoacademia;

        $rdi->project()->update(['project_type_id' => $request->project_type_id]);
        $rdi->project()->update(['academic_centre_id' => $request->academic_centre_id]);
        $rdi->project->cities()->sync($request->cities);

        $rdi->save();

        $request->relacionado_mesas_sectoriales == 1 ? $rdi->sectorBasedCommittees()->sync($request->sector_based_committee_id) : $rdi->sectorBasedCommittees()->detach();
        $request->relacionado_tecnoacademia == 1 ? $rdi->technologicalLines()->sync($request->technological_line_id) : $rdi->technologicalLines()->detach();


        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RDI  $rdi
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Call $call, RDI $rdi)
    {
        $this->authorize('delete', [RDI::class, $rdi]);

        if ( !Hash::check($request->password, Auth::user()->password) ) {
            return redirect()->back()
                ->withErrors(['password' => __('The password is incorrect.')]);
        }

        $rdi->delete();

        return redirect()->route('calls.rdi.index', [$call])->with('success', 'The resource has been deleted successfully.');
    }
}
