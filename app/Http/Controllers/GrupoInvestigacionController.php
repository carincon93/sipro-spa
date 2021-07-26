<?php

namespace App\Http\Controllers;

use App\Http\Requests\GrupoInvestigacionRequest;
use App\Models\GrupoInvestigacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class GrupoInvestigacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', [GrupoInvestigacion::class]);

        return Inertia::render('GruposInvestigacion/Index', [
            'filters'               => request()->all('search'),
            'gruposInvestigacion'   => GrupoInvestigacion::select('grupos_investigacion.id', 'grupos_investigacion.nombre', 'grupos_investigacion.centro_formacion_id')->orderBy('grupos_investigacion.nombre', 'ASC')->with('centroFormacion.regional')
                ->filterGrupoInvestigacion(request()->only('search'))->paginate()->appends(['search' => request()->search]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', [GrupoInvestigacion::class]);

        return Inertia::render('GruposInvestigacion/Create', [
            'categoriasMinciencias' => json_decode(Storage::get('json/categorias-minciencias.json'), true),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GrupoInvestigacionRequest $request)
    {
        $this->authorize('create', [GrupoInvestigacion::class]);

        $grupoInvestigacion = new GrupoInvestigacion();
        $grupoInvestigacion->nombre                   = $request->nombre;
        $grupoInvestigacion->acronimo                 = $request->acronimo;
        $grupoInvestigacion->email                    = $request->email;
        $grupoInvestigacion->enlace_gruplac           = $request->enlace_gruplac;
        $grupoInvestigacion->codigo_minciencias       = $request->codigo_minciencias;
        $grupoInvestigacion->categoria_minciencias    = $request->categoria_minciencias;
        $grupoInvestigacion->centroFormacion()->associate($request->centro_formacion_id);

        $grupoInvestigacion->save();

        return redirect()->route('grupos-investigacion.index')->with('success', 'El recurso se ha creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GrupoInvestigacion  $grupoInvestigacion
     * @return \Illuminate\Http\Response
     */
    public function show(GrupoInvestigacion $grupoInvestigacion)
    {
        $this->authorize('view', [GrupoInvestigacion::class, $grupoInvestigacion]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\GrupoInvestigacion  $grupoInvestigacion
     * @return \Illuminate\Http\Response
     */
    public function edit(GrupoInvestigacion $grupoInvestigacion)
    {
        $this->authorize('update', [GrupoInvestigacion::class, $grupoInvestigacion]);

        return Inertia::render('GruposInvestigacion/Edit', [
            'grupoInvestigacion'    => $grupoInvestigacion,
            'categoriasMinciencias' => json_decode(Storage::get('json/categorias-minciencias.json'), true),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\GrupoInvestigacion  $grupoInvestigacion
     * @return \Illuminate\Http\Response
     */
    public function update(GrupoInvestigacionRequest $request, GrupoInvestigacion $grupoInvestigacion)
    {
        $this->authorize('update', [GrupoInvestigacion::class, $grupoInvestigacion]);

        $grupoInvestigacion->nombre                  = $request->nombre;
        $grupoInvestigacion->acronimo                = $request->acronimo;
        $grupoInvestigacion->email                   = $request->email;
        $grupoInvestigacion->enlace_gruplac          = $request->enlace_gruplac;
        $grupoInvestigacion->codigo_minciencias      = $request->codigo_minciencias;
        $grupoInvestigacion->categoria_minciencias   = $request->categoria_minciencias;
        $grupoInvestigacion->centroFormacion()->associate($request->centro_formacion_id);

        $grupoInvestigacion->save();

        return redirect()->back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GrupoInvestigacion  $grupoInvestigacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(GrupoInvestigacion $grupoInvestigacion)
    {
        $this->authorize('delete', [GrupoInvestigacion::class, $grupoInvestigacion]);

        // $grupoInvestigacion->delete();

        return redirect()->back()->with('error', 'No se puede eliminar el recurso debido a que hay información relacionada. Comuníquese con el administrador del sistema.');
    }
}
