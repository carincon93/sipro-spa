<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActividadRequest;
use App\Models\Convocatoria;
use App\Models\Proyecto;
use App\Models\Actividad;
use App\Models\ProyectoPresupuesto;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ActividadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);

        $objetivoEspecifico = $proyecto->causasDirectas()->with('objetivoEspecifico')->get()->pluck('objetivoEspecifico')->flatten()->filter();
        $proyecto->codigo_linea_programatica = $proyecto->tipoProyecto->lineaProgramatica->codigo;

        return Inertia::render('Convocatorias/Proyectos/Actividades/Index', [
            'convocatoria'   => $convocatoria->only('id'),
            'proyecto'       => $proyecto->only('id', 'codigo_linea_programatica', 'precio_proyecto'),
            'filters'        => request()->all('search'),
            'actividades'    => Actividad::whereIn(
                'objetivo_especifico_id',
                $objetivoEspecifico->map(function ($objetivoEspecifico) {
                    return $objetivoEspecifico->id;
                })
            )->orderBy('fecha_inicio', 'ASC')->filterActividad(request()->only('search'))->paginate()->appends(['search' => request()->search]),
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
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ActividadRequest $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        $actividad = new Actividad();
        $actividad->fieldName = $request->fieldName;
        $actividad->fieldName = $request->fieldName;
        $actividad->fieldName = $request->fieldName;

        $actividad->save();

        return redirect()->route('convocatorias.proyectos.actividades.index')->with('success', 'El recurso se ha creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Actividad  $actividad
     * @return \Illuminate\Http\Response
     */
    public function show(Convocatoria $convocatoria, Proyecto $proyecto, Actividad $actividad)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Actividad  $actividad
     * @return \Illuminate\Http\Response
     */
    public function edit(Convocatoria $convocatoria, Proyecto $proyecto, Actividad $actividad)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);

        return Inertia::render('Convocatorias/Proyectos/Actividades/Edit', [
            'convocatoria'                   => $convocatoria->only('id', 'min_fecha_inicio_proyectos', 'max_fecha_finalizacion_proyectos'),
            'proyecto'                       => $proyecto->only('id', 'fecha_inicio', 'fecha_finalizacion'),
            'productos'                      => $proyecto->efectosDirectos()->with('resultado.productos')->get()->pluck('resultado.productos')->flatten(),
            'proyectoPresupuesto'            => ProyectoPresupuesto::where('proyecto_id', $proyecto->id)->with('convocatoriaPresupuesto.presupuestoSennova.segundoGrupoPresupuestal:id,nombre', 'convocatoriaPresupuesto.presupuestoSennova.tercerGrupoPresupuestal:id,nombre', 'convocatoriaPresupuesto.presupuestoSennova.usoPresupuestal:id,descripcion')->get(),
            'actividad'                      => $actividad,
            'productosRelacionados'          => $actividad->productos()->pluck('id'),
            'proyectoPresupuestoRelacionado' => $actividad->proyectoPresupuesto()->pluck('id')
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Actividad  $actividad
     * @return \Illuminate\Http\Response
     */
    public function update(ActividadRequest $request,  Convocatoria $convocatoria, Proyecto $proyecto, Actividad $actividad)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        $actividad->descripcion         = $request->descripcion;
        $actividad->fecha_inicio        = $request->fecha_inicio;
        $actividad->fecha_finalizacion  = $request->fecha_finalizacion;

        $actividad->save();

        $actividad->productos()->sync($request->producto_id);
        $actividad->proyectoPresupuesto()->sync($request->proyecto_presupuesto_id);

        return redirect()->back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Actividad  $actividad
     * @return \Illuminate\Http\Response
     */
    public function destroy(Convocatoria $convocatoria, Proyecto $proyecto, Actividad $actividad)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        $actividad->delete();

        return redirect()->route('convocatorias.proyectos.actividades.index', [$convocatoria, $proyecto])->with('success', 'El recurso se ha eliminado correctamente.');
    }
}
