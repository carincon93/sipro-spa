<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnalisisRiesgoRequest;
use App\Models\Convocatoria;
use App\Models\Proyecto;
use App\Models\AnalisisRiesgo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AnalisisRiesgoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('validar-autor', $proyecto);

        $proyecto->codigo_linea_programatica = $proyecto->tipoProyecto->lineaProgramatica->codigo;

        return Inertia::render('Convocatorias/Proyectos/AnalisisRiesgo/Index', [
            'convocatoria'    => $convocatoria->only('id'),
            'proyecto'        => $proyecto->only('id', 'codigo_linea_programatica', 'precio_proyecto'),
            'filters'         => request()->all('search'),
            'analisisRiesgos' => AnalisisRiesgo::where('proyecto_id', $proyecto->id)->orderBy('descripcion', 'ASC')
                ->filterAnalisisRiesgo(request()->only('search'))->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('validar-autor', $proyecto);

        return Inertia::render('Convocatorias/Proyectos/AnalisisRiesgo/Create', [
            'convocatoria'          => $convocatoria,
            'proyecto'              => $proyecto,
            'nivelesRiesgo'         => json_decode(Storage::get('json/niveles-riesgo.json'), true),
            'tiposRiesgo'           => json_decode(Storage::get('json/tipos-riesgo.json'), true),
            'probabilidadesRiesgo'  => json_decode(Storage::get('json/probabilidades-riesgo.json'), true),
            'impactosRiesgo'        => json_decode(Storage::get('json/impactos-riesgo.json'), true)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AnalisisRiesgoRequest $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('validar-autor', $proyecto);

        $analisisRiesgo = new AnalisisRiesgo();
        $analisisRiesgo->nivel               = $request->nivel;
        $analisisRiesgo->tipo                = $request->tipo;
        $analisisRiesgo->descripcion         = $request->descripcion;
        $analisisRiesgo->probabilidad        = $request->probabilidad;
        $analisisRiesgo->impacto             = $request->impacto;
        $analisisRiesgo->efectos             = $request->efectos;
        $analisisRiesgo->medidas_mitigacion  = $request->medidas_mitigacion;
        $analisisRiesgo->proyecto()->associate($proyecto);

        $analisisRiesgo->save();

        return redirect()->route('convocatorias.proyectos.analisis-riesgos.index', [$convocatoria, $proyecto])->with('success', 'El recurso se ha creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AnalisisRiesgo  $analisisRiesgo
     * @return \Illuminate\Http\Response
     */
    public function show(Convocatoria $convocatoria, Proyecto $proyecto, AnalisisRiesgo $analisisRiesgo)
    {
        $this->authorize('validar-autor', $proyecto);

        return Inertia::render('Convocatorias/Proyectos/AnalisisRiesgo/Show', [
            'analisisRiesgo' => $analisisRiesgo
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AnalisisRiesgo  $analisisRiesgo
     * @return \Illuminate\Http\Response
     */
    public function edit(Convocatoria $convocatoria, Proyecto $proyecto, AnalisisRiesgo $analisisRiesgo)
    {
        $this->authorize('validar-autor', $proyecto);

        return Inertia::render('Convocatorias/Proyectos/AnalisisRiesgo/Edit', [
            'convocatoria'         => $convocatoria,
            'proyecto'             => $proyecto,
            'analisisRiesgo'       => $analisisRiesgo,
            'nivelesRiesgo'        => json_decode(Storage::get('json/niveles-riesgo.json'), true),
            'tiposRiesgo'          => json_decode(Storage::get('json/tipos-riesgo.json'), true),
            'probabilidadesRiesgo' => json_decode(Storage::get('json/probabilidades-riesgo.json'), true),
            'impactosRiesgo'       => json_decode(Storage::get('json/impactos-riesgo.json'), true)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AnalisisRiesgo  $analisisRiesgo
     * @return \Illuminate\Http\Response
     */
    public function update(AnalisisRiesgoRequest $request, Convocatoria $convocatoria, Proyecto $proyecto, AnalisisRiesgo $analisisRiesgo)
    {
        $this->authorize('validar-autor', $proyecto);

        $analisisRiesgo->nivel                = $request->nivel;
        $analisisRiesgo->tipo                 = $request->tipo;
        $analisisRiesgo->descripcion          = $request->descripcion;
        $analisisRiesgo->probabilidad          = $request->probabilidad;
        $analisisRiesgo->impacto               = $request->impacto;
        $analisisRiesgo->efectos              = $request->efectos;
        $analisisRiesgo->medidas_mitigacion  = $request->medidas_mitigacion;
        $analisisRiesgo->proyecto()->associate($proyecto);

        $analisisRiesgo->save();

        return redirect()->back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AnalisisRiesgo  $analisisRiesgo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Convocatoria $convocatoria, Proyecto $proyecto, AnalisisRiesgo $analisisRiesgo)
    {
        $this->authorize('validar-autor', $proyecto);

        $analisisRiesgo->delete();

        return redirect()->route('convocatorias.proyectos.analisis-riesgos.index', [$convocatoria, $proyecto])->with('success', 'El recurso se ha eliminado correctamente.');
    }
}
