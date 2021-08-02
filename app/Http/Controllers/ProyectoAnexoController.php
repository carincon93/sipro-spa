<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProyectoAnexoRequest;
use App\Models\Convocatoria;
use App\Models\Proyecto;
use App\Models\Anexo;
use App\Models\Evaluacion\Evaluacion;
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
        $proyecto->codigo_linea_programatica == 68 ? $proyecto->infraestructura_adecuada = $proyecto->servicioTecnologico->infraestructura_adecuada : $proyecto->infraestructura_adecuada = null;
        $proyecto->codigo_linea_programatica == 68 ? $proyecto->especificaciones_area = $proyecto->servicioTecnologico->especificaciones_area : $proyecto->especificaciones_area = null;
        $proyecto->codigo_linea_programatica == 68 ? $proyecto->video = $proyecto->servicioTecnologico->video : $proyecto->video = null;

        return Inertia::render('Convocatorias/Proyectos/Anexos/Index', [
            'filters'           => request()->all('search'),
            'convocatoria'      => $convocatoria->only('id'),
            'proyecto'          => $proyecto->only('id', 'codigo_linea_programatica', 'precio_proyecto', 'modificable', 'video', 'infraestructura_adecuada', 'especificaciones_area'),
            'proyectoAnexo'     => $proyecto->proyectoAnexo()->select('proyecto_anexo.id', 'proyecto_anexo.anexo_id', 'proyecto_anexo.archivo', 'anexos.nombre')
                ->join('anexos', 'proyecto_anexo.anexo_id', 'anexos.id')->get(),
            'anexos'            => Anexo::select('id', 'nombre', 'archivo', 'obligatorio')->join('anexo_lineas_programaticas', 'anexos.id', 'anexo_lineas_programaticas.anexo_id')
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

        $nombreArchivo = $this->cleanFileName($proyecto->codigo, $anexo->nombre, $request->archivo);
        $archivo = $request->archivo->storeAs(
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

        return back()->with('success', 'El recurso se ha actualizado correctamente.');
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function anexoEvaluacion(Convocatoria $convocatoria, Evaluacion $evaluacion)
    {
        $evaluacion->proyecto->codigo_linea_programatica = $evaluacion->proyecto->lineaProgramatica->codigo;
        $evaluacion->proyecto->codigo_linea_programatica == 68 ? $evaluacion->proyecto->infraestructura_adecuada = $evaluacion->proyecto->servicioTecnologico->infraestructura_adecuada : $evaluacion->proyecto->infraestructura_adecuada = null;
        $evaluacion->proyecto->codigo_linea_programatica == 68 ? $evaluacion->proyecto->especificaciones_area = $evaluacion->proyecto->servicioTecnologico->especificaciones_area : $evaluacion->proyecto->especificaciones_area = null;
        $evaluacion->proyecto->codigo_linea_programatica == 68 ? $evaluacion->proyecto->video = $evaluacion->proyecto->servicioTecnologico->video : $evaluacion->proyecto->video = null;

        switch ($evaluacion->proyecto) {
            case $evaluacion->proyecto->idi()->exists():
                $evaluacion->idiEvaluacion;
                break;
            case $evaluacion->proyecto->ta()->exists():
                break;
            case $evaluacion->proyecto->tp()->exists():
                break;
            case $evaluacion->proyecto->culturaInnovacion()->exists():
                $evaluacion->culturaInnovacionEvaluacion;
                break;
            case $evaluacion->proyecto->servicioTecnologico()->exists():
                break;
            default:
                break;
        }

        return Inertia::render('Convocatorias/Evaluaciones/Anexos/Index', [
            'filters'           => request()->all('search'),
            'convocatoria'      => $convocatoria->only('id'),
            'evaluacion'        => $evaluacion,
            'proyecto'          => $evaluacion->proyecto->only('id', 'codigo_linea_programatica', 'precio_proyecto', 'finalizado', 'video', 'infraestructura_adecuada', 'especificaciones_area'),
            'proyectoAnexo'     => $evaluacion->proyecto->proyectoAnexo()->select('proyecto_anexo.id', 'proyecto_anexo.anexo_id', 'proyecto_anexo.archivo', 'anexos.nombre')
                ->join('anexos', 'proyecto_anexo.anexo_id', 'anexos.id')->get(),
            'anexos'            => Anexo::select('id', 'nombre', 'archivo', 'obligatorio')->join('anexo_lineas_programaticas', 'anexos.id', 'anexo_lineas_programaticas.anexo_id')
                ->where('anexo_lineas_programaticas.linea_programatica_id', $evaluacion->proyecto->lineaProgramatica->id)->filterAnexo(request()->only('search'))->paginate()->appends(['search' => request()->search])
        ]);
    }

    /**
     * updateAnexosEvaluacion
     *
     * @param  mixed $request
     * @param  mixed $convocatoria
     * @param  mixed $evaluacion
     * @return void
     */
    public function updateAnexosEvaluacion(Request $request, Convocatoria $convocatoria, Evaluacion $evaluacion)
    {
        switch ($evaluacion) {
            case $evaluacion->idiEvaluacion()->exists():
                $evaluacion->idiEvaluacion()->update([
                    'anexos_comentario'   => $request->anexos_requiere_comentario == true ? $request->anexos_comentario : null
                ]);
                break;
            case $evaluacion->culturaInnovacionEvaluacion()->exists():
                $evaluacion->culturaInnovacionEvaluacion()->update([
                    'anexos_comentario'   => $request->anexos_requiere_comentario == true ? $request->anexos_comentario : null
                ]);
                break;
            default:
                break;
        }

        $evaluacion->save();

        return back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * cleanFileName
     *
     * @param  mixed $nombre
     * @return void
     */
    public function cleanFileName($codigoProyecto, $nombre, $archivo)
    {
        $cleanName = str_replace(' ', '', substr($nombre, 0, 30));
        $cleanName = preg_replace('/[-`~!@#_$%\^&*()+={}[\]\\\\|;:\'",.><?\/]/', '', $cleanName);

        $cleanProyectoCodigo = str_replace(' ', '', substr($codigoProyecto, 0, 30));
        $cleanProyectoCodigo = preg_replace('/[-`~!@#_$%\^&*()+={}[\]\\\\|;:\'",.><?\/]/', '', $cleanProyectoCodigo);

        $random    = Str::random(5);

        return "{$cleanProyectoCodigo}{$cleanName}cod{$random}." . $archivo->extension();
    }
}
