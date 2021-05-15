<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActividadRequest;
use App\Models\Convocatoria;
use App\Models\Proyecto;
use App\Models\Actividad;
use App\Models\ProjectSennovaBudget;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('viewAny', [Activity::class]);

        $specificObjective = $proyecto->directCauses()->with('specificObjective')->get()->pluck('specificObjective')->flatten()->filter();

        $proyecto->projectType->programmaticLine;
        $proyecto->makeHidden(
            'rdi', 
            'projectSennovaBudgets', 
            'updated_at',
        );

        return Inertia::render('Convocatorias/Proyectos/Activities/Index', [
            'convocatoria'          => $convocatoria,
            'proyecto'      => $proyecto,
            'filters'       => request()->all('search'),
            'activities'    => Activity::whereIn('specific_objective_id',
                            $specificObjective->map(function ($specificObjective) {
                                return $specificObjective->id;
                            }))->filterActivity(request()->only('search'))->orderBy('fecha_inicio', 'ASC')->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('create', [Activity::class]);

        return Inertia::render('Convocatorias/Proyectos/Activities/Create', [
            'convocatoria' => $convocatoria,
            'proyecto'=> $proyecto,
            'outputs' => $proyecto->outputs
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ActividadRequest $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('create', [Activity::class]);

        $activity = new Activity();
        $activity->fieldName = $request->fieldName;
        $activity->fieldName = $request->fieldName;
        $activity->fieldName = $request->fieldName;

        $activity->save();

        return redirect()->route('projects.activities.index')->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function show( Convocatoria $convocatoria, Proyecto $proyecto, Activity $activity)
    {
        $this->authorize('view', [Activity::class, $activity]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function edit( Convocatoria $convocatoria, Proyecto $proyecto, Activity $activity)
    {
        $this->authorize('update', [Activity::class, $activity]);

        return Inertia::render('Convocatorias/Proyectos/Activities/Editar', [
            'activity'                      => $activity,
            'convocatoria'                  => $convocatoria,
            'proyecto'                      => $proyecto,
            'outputs'                       => $proyecto->directEffects()->with('projectResult.outputs')->get()->pluck('projectResult.outputs')->flatten(),
            'projectSennovaBudgets'         => ProjectSennovaBudget::where('project_id', $proyecto->id)->with('convocatoriaBudget.sennovaBudget.thirdBudgetInfo:id,name', 'convocatoriaBudget.sennovaBudget.secondBudgetInfo:id,name', 'convocatoriaBudget.sennovaBudget.budgetUsage:id,description')->get(),
            'activityOutputs'               => $activity->outputs()->pluck('id'),
            'activityProjectSennovaBudgets' => $activity->projectSennovaBudgets()->pluck('id')
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function update(ActividadRequest $request,  Convocatoria $convocatoria, Proyecto $proyecto, Activity $activity)
    {
        $this->authorize('update', [Activity::class, $activity]);

        $activity->description  = $request->description;
        $activity->fecha_inicio   = $request->fecha_inicio;
        $activity->fecha_finalizacion     = $request->fecha_finalizacion;

        $activity->save();

        $activity->outputs()->sync($request->output_id);
        $activity->projectSennovaBudgets()->sync($request->project_sennova_budget_id);

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\Response
     */
    public function destroy( Convocatoria $convocatoria, Proyecto $proyecto, Activity $activity)
    {
        $this->authorize('delete', [Activity::class, $activity]);

        $activity->delete();

        return redirect()->route('convocatorias.projects.activities.index', [$convocatoria, $proyecto])->with('success', 'The resource has been deleted successfully.');
    }
}
