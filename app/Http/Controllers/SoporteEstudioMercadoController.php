<?php

namespace App\Http\Controllers;

use App\Models\SoporteEstudioMercado;
use App\Http\Controllers\Controller;
use App\Http\Requests\SoporteEstudioMercadoRequest;
use App\Models\Convocatoria;
use App\Models\Evaluacion\Evaluacion;
use App\Models\Proyecto;
use App\Models\ProyectoPresupuesto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class SoporteEstudioMercadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Convocatoria $convocatoria, Proyecto $proyecto, ProyectoPresupuesto $presupuesto)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);

        /**
         * Denega el acceso si el rubro no requiere de estudio de mercado.
         */
        if (!$presupuesto->convocatoriaPresupuesto->presupuestoSennova->requiere_estudio_mercado && $presupuesto->convocatoriaPresupuesto->presupuestoSennova->usoPresupuestal->codigo != '020202008005096') {
            return redirect()->route('convocatorias.proyectos.presupuesto.index', [$convocatoria, $proyecto])->with('error', 'Este rubro presupuestal no requiere estudio de mercado.');
        }

        return Inertia::render('Convocatorias/Proyectos/ProyectoPresupuesto/SoportesEstudioMercado/Index', [
            'convocatoria'              => $convocatoria->only('id'),
            'proyecto'                  => $proyecto->only('id', 'modificable'),
            'proyectoPresupuesto'       => $presupuesto->load('convocatoriaPresupuesto.presupuestoSennova.usoPresupuestal'),
            'filters'                   => request()->all('search'),
            'soportesEstudioMercado'    => $presupuesto->soportesEstudioMercado()->orderBy('id', 'ASC')
                ->filterSoporteEstudioMercado(request()->only('search'))->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Convocatoria $convocatoria, Proyecto $proyecto, ProyectoPresupuesto $presupuesto)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);

        /**
         * Denega el acceso si el rubro no requiere de estudio de mercado.
         */
        if (!$presupuesto->convocatoriaPresupuesto->presupuestoSennova->requiere_estudio_mercado && $presupuesto->convocatoriaPresupuesto->presupuestoSennova->usoPresupuestal->codigo != '020202008005096') {
            return redirect()->route('convocatorias.proyectos.presupuesto.index', [$convocatoria, $proyecto]);
        }

        return Inertia::render('Convocatorias/Proyectos/ProyectoPresupuesto/SoportesEstudioMercado/Create', [
            'convocatoria'              => $convocatoria->only('id'),
            'proyecto'                  => $proyecto->only('id', 'modificable'),
            'proyectoPresupuesto'       => $presupuesto->load('convocatoriaPresupuesto.presupuestoSennova.usoPresupuestal'),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SoporteEstudioMercadoRequest $request, Convocatoria $convocatoria, Proyecto $proyecto, ProyectoPresupuesto $presupuesto)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        if ($presupuesto->soportesEstudioMercado()->count() == 3) {
            return redirect()->back()->with('error', 'No se ha podido crear el recurso. Ha superado el máximo de soportes/cotizaciones.');
        }

        $soporte = new SoporteEstudioMercado();

        $nombreArchivo = $this->cleanFileName($proyecto->codigo, $request->empresa, $request->soporte);
        $archivo = $request->soporte->storeAs(
            'soportes',
            $nombreArchivo
        );

        $soporte->soporte = $archivo;
        $soporte->empresa = $request->empresa;
        $soporte->ProyectoPresupuesto()->associate($presupuesto->id);

        $soporte->save();

        return redirect()->route('convocatorias.proyectos.presupuesto.soportes.index', [$convocatoria, $proyecto, $presupuesto])->with('success', 'El recurso se ha creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SoporteEstudioMercado  $soporte
     * @return \Illuminate\Http\Response
     */
    public function show(Convocatoria $convocatoria, Proyecto $proyecto, ProyectoPresupuesto $presupuesto, SoporteEstudioMercado $soporte)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SoporteEstudioMercado  $soporte
     * @return \Illuminate\Http\Response
     */
    public function edit(Convocatoria $convocatoria, Proyecto $proyecto, ProyectoPresupuesto $presupuesto, SoporteEstudioMercado $soporte)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);

        /**
         * Denega el acceso si el rubro no requiere de estudio de mercado.
         */
        if (!$presupuesto->convocatoriaPresupuesto->presupuestoSennova->requiere_estudio_mercado && $presupuesto->convocatoriaPresupuesto->presupuestoSennova->usoPresupuestal->codigo != '020202008005096') {
            return redirect()->route('convocatorias.proyectos.presupuesto.index', [$convocatoria, $proyecto]);
        }

        return Inertia::render('Convocatorias/Proyectos/ProyectoPresupuesto/SoportesEstudioMercado/Edit', [
            'convocatoria'          => $convocatoria->only('id'),
            'proyecto'              => $proyecto->only('id', 'modificable'),
            'proyectoPresupuesto'   => $presupuesto->load('convocatoriaPresupuesto.presupuestoSennova.usoPresupuestal'),
            'soporteEstudioMercado' => $soporte
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SoporteEstudioMercado  $soporte
     * @return \Illuminate\Http\Response
     */
    public function update(SoporteEstudioMercadoRequest $request, Convocatoria $convocatoria, Proyecto $proyecto, ProyectoPresupuesto $presupuesto, SoporteEstudioMercado $soporte)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        if ($request->hasFile('soporte')) {
            Storage::delete($soporte->soporte);
            $nombreArchivo     = $this->cleanFileName($proyecto->codigo, $request->empresa, $request->soporte);
            $archivo = $request->soporte->storeAs(
                'soportes',
                $nombreArchivo
            );

            $soporte->soporte = $archivo;
        }
        $soporte->empresa = $request->empresa;
        $soporte->save();

        return redirect()->back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SoporteEstudioMercado  $soporte
     * @return \Illuminate\Http\Response
     */
    public function destroy(Convocatoria $convocatoria, Proyecto $proyecto, ProyectoPresupuesto $presupuesto, SoporteEstudioMercado $soporte)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        $soporte->delete();

        return redirect()->route('convocatorias.proyectos.presupuesto.soportes.index', [$convocatoria, $proyecto, $presupuesto])->with('success', 'El recurso se ha eliminado correctamente.');
    }

    /**
     * download
     *
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @param  mixed $presupuesto
     * @param  mixed $soporte
     * @return void
     */
    public function download(Convocatoria $convocatoria, Proyecto $proyecto, ProyectoPresupuesto $presupuesto, SoporteEstudioMercado $soporte)
    {
        return response()->download(storage_path("app/$soporte->soporte"));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function soportesEvaluacion(Convocatoria $convocatoria, Evaluacion $evaluacion, ProyectoPresupuesto $presupuesto)
    {
        /**
         * Denega el acceso si el rubro no requiere de estudio de mercado.
         */
        if (!$presupuesto->convocatoriaPresupuesto->presupuestoSennova->requiere_estudio_mercado && $presupuesto->convocatoriaPresupuesto->presupuestoSennova->usoPresupuestal->codigo != '020202008005096') {
            return redirect()->back()->with('error', 'Este rubro presupuestal no requiere estudio de mercado.');
        }

        return Inertia::render('Convocatorias/Evaluaciones/ProyectoPresupuesto/SoportesEstudioMercado/Index', [
            'convocatoria'              => $convocatoria->only('id'),
            'evaluacion'                => $evaluacion->only('id'),
            'proyecto'                  => $evaluacion->proyecto->only('id', 'finalizado'),
            'proyectoPresupuesto'       => $presupuesto->load('convocatoriaPresupuesto.presupuestoSennova.usoPresupuestal'),
            'filters'                   => request()->all('search'),
            'soportesEstudioMercado'    => $presupuesto->soportesEstudioMercado()->orderBy('id', 'ASC')
                ->filterSoporteEstudioMercado(request()->only('search'))->paginate(),
        ]);
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
