<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProyectoAnexoRequest;
use App\Models\Convocatoria;
use App\Models\Proyecto;
use App\Models\Anexo;
use App\Models\ProyectoAnexo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProyectoAnexoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);

        $proyecto->codigo_linea_programatica = $proyecto->lineaProgramatica->codigo;

        return Inertia::render('Convocatorias/Proyectos/Anexos/Index', [
            'filters'           => request()->all('search'),
            'convocatoria'      => $convocatoria->only('id'),
            'proyecto'          => $proyecto->only('id', 'codigo_linea_programatica', 'precio_proyecto', 'modificable'),
            'proyectoAnexo'     => $proyecto->proyectoAnexo()->select('proyecto_anexo.id', 'proyecto_anexo.anexo_id', 'proyecto_anexo.archivo', 'anexos.nombre')
                ->join('anexos', 'proyecto_anexo.anexo_id', 'anexos.id')->get(),
            'anexos'            => Anexo::select('id', 'nombre')->join('anexo_lineas_programaticas', 'anexos.id', 'anexo_lineas_programaticas.anexo_id')
                ->where('anexo_lineas_programaticas.linea_programatica_id', $proyecto->lineaProgramatica->id)->filterAnexo(request()->only('search'))->paginate()->appends(['search' => request()->search])
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);

        return Inertia::render('Convocatorias/Proyectos/Anexos/Create', [
            'convocatoria'  => $convocatoria->only('id'),
            'proyecto'      => $proyecto->only('id', 'modificable'),
            'anexos'        => Anexo::select('id as value', 'nombre as label')->join('anexo_lineas_programaticas', 'anexos.id', 'anexo_lineas_programaticas.anexo_id')->where('anexo_lineas_programaticas.linea_programatica_id', $proyecto->lineaProgramatica->id)->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProyectoAnexoRequest $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        $anexo = Anexo::select('id', 'nombre')->where('id', $request->anexo_id)->first();

        $anexoName          = Str::slug(substr($anexo->nombre, 0, 30), '-');
        $random             = Str::random(5);
        $requestFile        = $request->archivo;
        $nombreArchivo      = "$proyecto->codigo-$anexoName-cod$random." . $requestFile->extension();
        $archivo = $requestFile->storeAs(
            'anexos',
            $nombreArchivo
        );

        ProyectoAnexo::updateOrCreate(
            ['proyecto_id'  => $proyecto->id, 'anexo_id' => $anexo->id],
            ['archivo'      => $archivo]
        );

        return redirect()->route('convocatorias.proyectos.proyecto-anexos.index', [$convocatoria, $proyecto])->with('success', 'El recurso se ha creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProyectoAnexo  $proyectoAnexo
     * @return \Illuminate\Http\Response
     */
    public function show(Convocatoria $convocatoria, Proyecto $proyecto, ProyectoAnexo $proyectoAnexo)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProyectoAnexo  $proyectoAnexo
     * @return \Illuminate\Http\Response
     */
    public function edit(Convocatoria $convocatoria, Proyecto $proyecto, ProyectoAnexo $proyectoAnexo)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);

        return Inertia::render('Convocatorias/Proyectos/Anexos/Edit', [
            'convocatoria'  => $convocatoria,
            'proyecto'      => $proyecto,
            'proyectoAnexo' => $proyectoAnexo,
            'anexos'        => Anexo::select('id as value', 'nombre as label')->join('anexo_lineas_programaticas', 'anexos.id', 'anexo_lineas_programaticas.anexo_id')->where('anexo_lineas_programaticas.linea_programatica_id', $proyecto->lineaProgramatica->id)->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProyectoAnexo  $proyectoAnexo
     * @return \Illuminate\Http\Response
     */
    public function update(ProyectoAnexoRequest $request, Convocatoria $convocatoria, Proyecto $proyecto, ProyectoAnexo $proyectoAnexo)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        return redirect()->back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProyectoAnexo  $proyectoAnexo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Convocatoria $convocatoria, Proyecto $proyecto, ProyectoAnexo $proyectoAnexo)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        $proyectoAnexo->delete();

        return redirect()->route('convocatorias.proyectos.proyecto-anexos.index', [$convocatoria, $proyecto])->with('success', 'El recurso se ha eliminado correctamente.');
    }

    /**
     * download
     *
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @param  mixed $proyectoAnexo
     * @return void
     */
    public function download(Convocatoria $convocatoria, Proyecto $proyecto, ProyectoAnexo $proyectoAnexo)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);

        return response()->download(storage_path("app/$proyectoAnexo->archivo"));
    }
}
