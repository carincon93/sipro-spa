<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectAnexoRequest;
use App\Models\Convocatoria;
use App\Models\Proyecto;
use App\Models\Anexo;
use App\Models\ProjectAnexo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectAnexoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('viewAny', [ProjectAnexo::class]);

        return Inertia::render('Convocatorias/Proyectos/ProjectAnexos/Index', [
            'filters'           => request()->all('search'),
            'projectAnexos'    => $proyecto->projectAnexos()->select('project_annexes.id', 'project_annexes.annexe_id', 'project_annexes.file', 'annexes.name')
                ->join('annexes', 'project_annexes.annexe_id', 'annexes.id')
                ->filterProjectAnexo(request()->only('search'))->paginate(),
            'convocatoria'      => $convocatoria,
            'project'   => $proyecto,
            'annexes'   => Anexo::select('id', 'name')->join('annexe_programmatic_line', 'annexes.id', 'annexe_programmatic_line.annexe_id')->where('annexe_programmatic_line.linea_programatica_id', $proyecto->projectType->programmaticLine->id)->paginate()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('create', [ProjectAnexo::class]);

        return Inertia::render('Convocatorias/Proyectos/ProjectAnexos/Create', [
            'convocatoria'      => $convocatoria,
            'project'   => $proyecto,
            'annexes'   => Anexo::select('id as value', 'name as label')->join('annexe_programmatic_line', 'annexes.id', 'annexe_programmatic_line.annexe_id')->where('annexe_programmatic_line.linea_programatica_id', $proyecto->projectType->programmaticLine->id)->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectAnexoRequest $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('create', [ProjectAnexo::class]);

        $annexe = Anexo::select('id', 'name')->where('id', $request->annexe_id)->first();

        $annexeName     = Str::slug(substr($annexe->name, 0, 30), '-');
        $random         = Str::random(5);
        $requestFile    = $request->file;
        $fileName       = "$proyecto->code-$annexeName-cod$random.".$requestFile->extension();
        $file = $requestFile->storeAs(
            'annexes', $fileName
        );

        ProjectAnexo::updateOrCreate(
            ['project_id' => $proyecto->id, 'annexe_id' => $annexe->id],
            ['file' => $file]
        );

        return redirect()->route('convocatorias.projects.project-annexes.index', [$convocatoria, $proyecto])->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProjectAnexo  $proyectoAnexo
     * @return \Illuminate\Http\Response
     */
    public function show (Convocatoria $convocatoria, Proyecto $proyecto, ProjectAnexo $proyectoAnexo)
    {
        $this->authorize('view', [ProjectAnexo::class, $proyectoAnexo]);

        return Inertia::render('Convocatorias/Proyectos/ProjectAnexos/Show', [
            'projectAnexo' => $proyectoAnexo
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProjectAnexo  $proyectoAnexo
     * @return \Illuminate\Http\Response
     */
    public function edit (Convocatoria $convocatoria, Proyecto $proyecto, ProjectAnexo $proyectoAnexo)
    {
        $this->authorize('update', [ProjectAnexo::class, $proyectoAnexo]);

        return Inertia::render('Convocatorias/Proyectos/ProjectAnexos/Editar', [
            'convocatoria'          => $convocatoria,
            'project'       => $proyecto,
            'projectAnexo' => $proyectoAnexo,
            'annexes'       => Anexo::select('id as value', 'name as label')->join('annexe_programmatic_line', 'annexes.id', 'annexe_programmatic_line.annexe_id')->where('annexe_programmatic_line.linea_programatica_id', $proyecto->projectType->programmaticLine->id)->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProjectAnexo  $proyectoAnexo
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectAnexoRequest $request, Convocatoria $convocatoria, Proyecto $proyecto, ProjectAnexo $proyectoAnexo)
    {
        $this->authorize('update', [ProjectAnexo::class, $proyectoAnexo]);

        $proyectoAnexo->fieldName = $request->fieldName;
        $proyectoAnexo->fieldName = $request->fieldName;
        $proyectoAnexo->fieldName = $request->fieldName;

        $proyectoAnexo->save();

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProjectAnexo  $proyectoAnexo
     * @return \Illuminate\Http\Response
     */
    public function destroy (Convocatoria $convocatoria, Proyecto $proyecto, ProjectAnexo $proyectoAnexo)
    {
        $this->authorize('delete', [ProjectAnexo::class, $proyectoAnexo]);

        $proyectoAnexo->delete();

        return redirect()->route('convocatorias.projects.project-annexes.index', [$convocatoria, $proyecto])->with('success', 'The resource has been deleted successfully.');
    }

    /**
     * download
     *
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @param  mixed $proyectoAnexo
     * @return void
     */
    public function download (Convocatoria $convocatoria, Proyecto $proyecto, ProjectAnexo $proyectoAnexo)
    {
        return response()->download(storage_path("app/$proyectoAnexo->file"));
    }
}
