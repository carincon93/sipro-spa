<?php

namespace App\Http\Controllers;

use App\Http\Requests\OutputRequest;
use App\Models\Call;
use App\Models\Project;
use App\Models\Output;
use App\Models\RDIOutput;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OutputController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Call $call, Project $project)
    {
        $this->authorize('viewAny', [Output::class]);

        $projectResult = $project->directEffects()->with('projectResult')->get()->pluck('projectResult')->flatten()->filter();
        
        $project->projectType->programmaticLine;
        $project->makeHidden(
            'rdi', 
            'projectSennovaBudgets', 
            'updated_at',
        );

        return Inertia::render('Calls/Projects/Outputs/Index', [
            'call'    => $call,
            'project' => $project,
            'filters' => request()->all('search'),
            'outputs' => Output::whereIn('project_result_id',
                        $projectResult->map(function ($projectResult) {
                            return $projectResult->id;
                        }))->filterOutput(request()->only('search'))->paginate(),
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Call $call, Project $project)
    {
        $this->authorize('create', [Output::class]);

        $project->rdi;

        return Inertia::render('Calls/Projects/Outputs/Create', [
            'call'              => $call,
            'project'           => $project,
            'projectResults'    => $project->directEffects()->whereHas('projectResult', function ($query) {
                    $query->where('description', '!=', null);
                })->with('projectResult:id as value,description as label,direct_effect_id')->get()->pluck('projectResult')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OutputRequest $request, Call $call, Project $project)
    {
        $this->authorize('create', [Output::class]);

        $output = new Output();
        $output->name       = $request->name;
        $output->fecha_incio = $request->fecha_incio;
        $output->fecha_finalizacion   = $request->fecha_finalizacion;
        $output->projectResult()->associate($request->project_result_id);
        $output->save();

        // Valida si es un producto de I+D+i
        if ($request->minciencias_subtypology_id) {
            $rdiOutput = new RDIOutput();
            $rdiOutput->output()->associate($output->id);
            $rdiOutput->mincienciasSubtypology()->associate($request->minciencias_subtypology_id);
            $rdiOutput->save();
        }

        return redirect()->route('calls.projects.outputs.index', [$call, $project])->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Output  $output
     * @return \Illuminate\Http\Response
     */
    public function show(Call $call, Project $project, Output $output)
    {
        $this->authorize('view', [Output::class, $output]);

        return Inertia::render('Calls/Projects/Outputs/Show', [
            'call' => $call,
            'project'  => $project,
            'output' => $output
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Output  $output
     * @return \Illuminate\Http\Response
     */
    public function edit(Call $call, Project $project, Output $output)
    {
        $this->authorize('update', [Output::class, $output]);

        $project->rdi;
        $output->rdiOutput;

        return Inertia::render('Calls/Projects/Outputs/Editar', [
            'call'              => $call->only('id'),
            'project'           => $project->only('id'),
            'output'            => $output,
            'projectResults'    => $project->directEffects()->whereHas('projectResult', function ($query) {
                    $query->where('description', '!=', null);
                })->with('projectResult:id as value,description as label,direct_effect_id')->get()->pluck('projectResult'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Output  $output
     * @return \Illuminate\Http\Response
     */
    public function update(OutputRequest $request, Call $call, Project $project, Output $output)
    {
        $this->authorize('update', [Output::class, $output]);

        $output->name = $request->name;
        $output->fecha_incio = $request->fecha_incio;
        $output->fecha_finalizacion = $request->fecha_finalizacion;
        $output->projectResult()->associate($request->project_result_id);

        if ($request->minciencias_subtypology_id) {
            $output->rdiOutput()->update(['minciencias_subtypology_id' => $request->minciencias_subtypology_id]);
        }

        $output->save();

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Output  $output
     * @return \Illuminate\Http\Response
     */
    public function destroy(Call $call, Project $project, Output $output)
    {
        $this->authorize('delete', [Output::class, $output]);

        $output->delete();

        return redirect()->route('calls.projects.outputs.index', [$call, $project])->with('success', 'The resource has been deleted successfully.');
    }
}
