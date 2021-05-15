<?php

namespace App\Http\Controllers;

use App\Http\Requests\OutputRequest;
use App\Models\Convocatoria;
use App\Models\Proyecto;
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
    public function index(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('viewAny', [Output::class]);

        $proyectoResult = $proyecto->directEffects()->with('projectResult')->get()->pluck('projectResult')->flatten()->filter();
        
        $proyecto->projectType->programmaticLine;
        $proyecto->makeHidden(
            'rdi', 
            'projectSennovaBudgets', 
            'updated_at',
        );

        return Inertia::render('Convocatorias/Proyectos/Outputs/Index', [
            'convocatoria'    => $convocatoria,
            'project' => $proyecto,
            'filters' => request()->all('search'),
            'outputs' => Output::whereIn('project_result_id',
                        $proyectoResult->map(function ($proyectoResult) {
                            return $proyectoResult->id;
                        }))->filterOutput(request()->only('search'))->paginate(),
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('create', [Output::class]);

        $proyecto->rdi;

        return Inertia::render('Convocatorias/Proyectos/Outputs/Create', [
            'convocatoria'              => $convocatoria,
            'project'           => $proyecto,
            'projectResults'    => $proyecto->directEffects()->whereHas('projectResult', function ($query) {
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
    public function store(OutputRequest $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('create', [Output::class]);

        $output = new Output();
        $output->name       = $request->name;
        $output->fecha_inicio = $request->fecha_inicio;
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

        return redirect()->route('convocatorias.projects.outputs.index', [$convocatoria, $proyecto])->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Output  $output
     * @return \Illuminate\Http\Response
     */
    public function show(Convocatoria $convocatoria, Proyecto $proyecto, Output $output)
    {
        $this->authorize('view', [Output::class, $output]);

        return Inertia::render('Convocatorias/Proyectos/Outputs/Show', [
            'convocatoria' => $convocatoria,
            'project'  => $proyecto,
            'output' => $output
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Output  $output
     * @return \Illuminate\Http\Response
     */
    public function edit(Convocatoria $convocatoria, Proyecto $proyecto, Output $output)
    {
        $this->authorize('update', [Output::class, $output]);

        $proyecto->rdi;
        $output->rdiOutput;

        return Inertia::render('Convocatorias/Proyectos/Outputs/Editar', [
            'convocatoria'              => $convocatoria->only('id'),
            'project'           => $proyecto->only('id'),
            'output'            => $output,
            'projectResults'    => $proyecto->directEffects()->whereHas('projectResult', function ($query) {
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
    public function update(OutputRequest $request, Convocatoria $convocatoria, Proyecto $proyecto, Output $output)
    {
        $this->authorize('update', [Output::class, $output]);

        $output->name = $request->name;
        $output->fecha_inicio = $request->fecha_inicio;
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
    public function destroy(Convocatoria $convocatoria, Proyecto $proyecto, Output $output)
    {
        $this->authorize('delete', [Output::class, $output]);

        $output->delete();

        return redirect()->route('convocatorias.projects.outputs.index', [$convocatoria, $proyecto])->with('success', 'The resource has been deleted successfully.');
    }
}
