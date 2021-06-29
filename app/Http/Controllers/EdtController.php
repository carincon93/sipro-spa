<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\EdtRequest;
use App\Models\Convocatoria;
use App\Models\Edt;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class EdtController extends Controller
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

        /**
         * Si el proyecto es diferente de la línea programática 70 se prohibe el acceso. No requiere de edt
         */
        if ($proyecto->codigo_linea_programatica != 70) {
            return redirect()->route('convocatorias.proyectos.edit', [$convocatoria, $proyecto])->with('error', 'Esta línea programática no requiere de edt');
        }

        return Inertia::render('Convocatorias/Proyectos/EDT/Index', [
            'convocatoria'     => $convocatoria->only('id'),
            'proyecto'         => $proyecto->only('id', 'codigo_linea_programatica', 'precio_proyecto', 'modificable'),
            'filters'          => request()->all('search'),
            'eventos'          => Edt::orderBy('descripcion_evento', 'ASC')
                ->filterEdt(request()->only('search'))->select('id', 'descripcion_evento', 'numero_asistentes', 'presupuesto')->paginate(),
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

        /**
         * Si el proyecto es diferente de la línea programática 70 se prohibe el acceso. No requiere de edt
         */
        if ($proyecto->lineaProgramatica->codigo != 70) {
            return redirect()->route('convocatorias.proyectos.edit', [$convocatoria, $proyecto])->with('error', 'Esta línea programática no requiere de edt');
        }

        return Inertia::render('Convocatorias/Proyectos/EDT/Create', [
            'convocatoria' => $convocatoria,
            'proyecto'     => $proyecto,
            'tiposEvento'  => json_decode(Storage::get('json/tipos-edt.json'), true),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EdtRequest $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        $edt = new Edt();
        $edt->tipo_evento                           = $request->tipo_evento;
        $edt->descripcion_evento                    = $request->descripcion_evento;
        $edt->descripcion_participacion_entidad     = $request->descripcion_participacion_entidad;
        $edt->publico_objetivo                      = $request->publico_objetivo;
        $edt->numero_asistentes                     = $request->numero_asistentes;
        $edt->estrategia_comunicacion               = $request->estrategia_comunicacion;
        $edt->presupuesto                           = $request->presupuesto;
        $edt->ta()->associate($proyecto);

        $edt->save();

        return redirect()->route('convocatorias.proyectos.edt.index', [$convocatoria, $proyecto])->with('success', 'El recurso se ha creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Convocatoria $convocatoria, Proyecto $proyecto, Edt $edt)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Convocatoria $convocatoria, Proyecto $proyecto, Edt $edt)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);

        /**
         * Si el proyecto es diferente de la línea programática 70 se prohibe el acceso. No requiere de edt
         */
        if ($proyecto->lineaProgramatica->codigo != 70) {
            return redirect()->route('convocatorias.proyectos.edit', [$convocatoria, $proyecto])->with('error', 'Esta línea programática no requiere de edt');
        }


        return Inertia::render('Convocatorias/Proyectos/EDT/Edit', [
            'convocatoria' => $convocatoria,
            'proyecto'     => $proyecto,
            'edt'           => $edt,
            'tiposEvento'  => json_decode(Storage::get('json/tipos-edt.json'), true),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(EdtRequest $request, Convocatoria $convocatoria, Proyecto $proyecto, Edt $edt)
    {
        $edt->tipo_evento                           = $request->tipo_evento;
        $edt->descripcion_evento                    = $request->descripcion_evento;
        $edt->descripcion_participacion_entidad     = $request->descripcion_participacion_entidad;
        $edt->publico_objetivo                      = $request->publico_objetivo;
        $edt->numero_asistentes                     = $request->numero_asistentes;
        $edt->estrategia_comunicacion               = $request->estrategia_comunicacion;
        $edt->presupuesto                           = $request->presupuesto;

        $edt->save();

        return redirect()->route('convocatorias.proyectos.edt.index', [$convocatoria, $proyecto])->with('success', 'El recurso se ha modificado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Convocatoria $convocatoria, Proyecto $proyecto, Edt $edt)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        $edt->delete();

        return redirect()->route('convocatorias.proyectos.edt.index', [$convocatoria, $proyecto])->with('success', 'El recurso se ha eliminado correctamente.');
    }
}
