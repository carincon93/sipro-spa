<?php

namespace App\Http\Controllers;

use App\Http\Requests\PresupuestoSennovaRequest;
use App\Models\LineaProgramatica;
use App\Models\PresupuestoSennova;
use App\Models\PrimerGrupoPresupuestal;
use App\Models\SegundoGrupoPresupuestal;
use App\Models\TercerGrupoPresupuestal;
use App\Models\UsoPresupuestal;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PresupuestoSennovaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', [PresupuestoSennova::class]);

        return Inertia::render('Presupuesto/PresupuestoSennova/Index', [
            'filters'               => request()->all('search'),
            'presupuestoSennova'    => PresupuestoSennova::orderBy('linea_programatica_id', 'ASC')->orderBy('segundo_grupo_presupuestal_id', 'ASC')->with('usoPresupuestal', 'segundoGrupoPresupuestal', 'tercerGrupoPresupuestal', 'lineaProgramatica')
                ->filterPresupuestoSennova(request()->only('search'))->paginate()->appends(['search' => request()->search]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', [PresupuestoSennova::class]);

        return Inertia::render('Presupuesto/PresupuestoSennova/Create', [
            'primerGrupoPresupuestal'   => PrimerGrupoPresupuestal::select('id as value', 'nombre as label')->get(),
            'segundoGrupoPresupuestal'  => SegundoGrupoPresupuestal::select('id as value', 'nombre as label')->get(),
            'tercerGrupoPresupuestal'   => TercerGrupoPresupuestal::select('id as value', 'nombre as label')->get(),
            'usosPresupuestales'        => UsoPresupuestal::select('id as value', 'descripcion as label')->get(),
            'lineasProgramaticas'       => LineaProgramatica::selectRaw('lineas_programaticas.id as value, concat(lineas_programaticas.nombre, chr(10), \'∙ Código: \', lineas_programaticas.codigo) as label')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PresupuestoSennovaRequest $request)
    {
        $this->authorize('create', [PresupuestoSennova::class]);

        $presupuestoSennova = new PresupuestoSennova();

        $presupuestoSennova->requiere_estudio_mercado   = $request->requiere_estudio_mercado;
        $presupuestoSennova->sumar_al_presupuesto       = $request->sumar_al_presupuesto;
        $presupuestoSennova->mensaje                    = $request->mensaje;
        $presupuestoSennova->habilitado                 = $request->habilitado;

        $presupuestoSennova->primerGrupoPresupuestal()->associate($request->primer_grupo_presupuestal_id);
        $presupuestoSennova->segundoGrupoPresupuestal()->associate($request->segundo_grupo_presupuestal_id);
        $presupuestoSennova->tercerGrupoPresupuestal()->associate($request->tercer_grupo_presupuestal_id);
        $presupuestoSennova->usoPresupuestal()->associate($request->uso_presupuestal_id);
        $presupuestoSennova->lineaProgramatica()->associate($request->linea_programatica_id);

        $presupuestoSennova->save();

        return redirect()->route('presupuesto-sennova.index')->with('success', 'El recurso se ha creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PresupuestoSennova  $presupuestoSennova
     * @return \Illuminate\Http\Response
     */
    public function show(PresupuestoSennova $presupuestoSennova)
    {
        $this->authorize('view', [PresupuestoSennova::class, $presupuestoSennova]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PresupuestoSennova  $presupuestoSennova
     * @return \Illuminate\Http\Response
     */
    public function edit(PresupuestoSennova $presupuestoSennova)
    {
        $this->authorize('update', [PresupuestoSennova::class, $presupuestoSennova]);

        return Inertia::render('Presupuesto/PresupuestoSennova/Edit', [
            'presupuestoSennova'        => $presupuestoSennova,
            'primerGrupoPresupuestal'   => PrimerGrupoPresupuestal::select('id as value', 'nombre as label')->get(),
            'segundoGrupoPresupuestal'  => SegundoGrupoPresupuestal::select('id as value', 'nombre as label')->get(),
            'tercerGrupoPresupuestal'   => TercerGrupoPresupuestal::select('id as value', 'nombre as label')->get(),
            'usosPresupuestales'        => UsoPresupuestal::select('id as value', 'descripcion as label')->get(),
            'lineasProgramaticas'       => LineaProgramatica::selectRaw('lineas_programaticas.id as value, concat(lineas_programaticas.nombre, chr(10), \'∙ Código: \', lineas_programaticas.codigo) as label')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PresupuestoSennova  $presupuestoSennova
     * @return \Illuminate\Http\Response
     */
    public function update(PresupuestoSennovaRequest $request, PresupuestoSennova $presupuestoSennova)
    {
        $this->authorize('update', [PresupuestoSennova::class, $presupuestoSennova]);

        $presupuestoSennova->requiere_estudio_mercado   = $request->requiere_estudio_mercado;
        $presupuestoSennova->sumar_al_presupuesto       = $request->sumar_al_presupuesto;
        $presupuestoSennova->mensaje                    = $request->mensaje;
        $presupuestoSennova->habilitado                 = $request->habilitado;

        $presupuestoSennova->primerGrupoPresupuestal()->associate($request->primer_grupo_presupuestal_id);
        $presupuestoSennova->segundoGrupoPresupuestal()->associate($request->segundo_grupo_presupuestal_id);
        $presupuestoSennova->tercerGrupoPresupuestal()->associate($request->tercer_grupo_presupuestal_id);
        $presupuestoSennova->usoPresupuestal()->associate($request->uso_presupuestal_id);
        $presupuestoSennova->lineaProgramatica()->associate($request->linea_programatica_id);

        $presupuestoSennova->save();

        return back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PresupuestoSennova  $presupuestoSennova
     * @return \Illuminate\Http\Response
     */
    public function destroy(PresupuestoSennova $presupuestoSennova)
    {
        $this->authorize('delete', [PresupuestoSennova::class, $presupuestoSennova]);

        $presupuestoSennova->delete();

        return redirect()->route('presupuesto-sennova.index')->with('success', 'El recurso se ha eliminado correctamente.');
    }


    /**
     * configuracionPresupuestoSennova
     *
     * @return void
     */
    public function configuracionPresupuestoSennova()
    {
        $this->authorize('viewAny', [PresupuestoSennova::class]);

        return Inertia::render('Presupuesto/Dashboard');
    }
}
