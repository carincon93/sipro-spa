<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActividadRequest;
use App\Models\Convocatoria;
use App\Models\Proyecto;
use App\Models\Actividad;
use App\Models\ProyectoPresupuesto;
use Illuminate\Http\Request;
use Inertia\Inertia;

use function PHPSTORM_META\map;

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
        $proyecto->codigo_linea_programatica = $proyecto->lineaProgramatica->codigo;

        switch ($proyecto) {
            case $proyecto->idi()->exists():
                $proyecto->metodologia = $proyecto->idi->metodologia;
                break;
            case $proyecto->taTp()->exists():
                $proyecto->metodologia = $proyecto->tatp->metodologia;
                $proyecto->metodologia_local = $proyecto->tatp->metodologia_local;
                break;
            case $proyecto->culturaInnovacion()->exists():
                $proyecto->metodologia = $proyecto->culturaInnovacion->metodologia;
                break;
            case $proyecto->servicioTecnologico()->exists():
                $proyecto->metodologia = $proyecto->servicioTecnologico->metodologia;
                break;
            default:
                break;
        }

        return Inertia::render('Convocatorias/Proyectos/Actividades/Index', [
            'convocatoria'   => $convocatoria->only('id'),
            'proyecto'       => $proyecto->only('id', 'codigo_linea_programatica', 'precio_proyecto', 'modificable', 'metodologia', 'metodologia_local'),
            'filters'        => request()->all('search'),
            'actividades'    => Actividad::whereIn(
                'objetivo_especifico_id',
                $objetivoEspecifico->map(function ($objetivoEspecifico) {
                    return $objetivoEspecifico->id;
                })
            )->orderBy('fecha_inicio', 'ASC')->get(),
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

        $resultados = $proyecto->efectosDirectos()->whereHas('resultados', function ($query) {
            $query->where('descripcion', '!=', null);
        })->with('resultados')->get()->pluck('resultados')->flatten();

        $productos = $resultados->map(function ($resultado) {
            return $resultado->productos;
        })->flatten();

        return Inertia::render('Convocatorias/Proyectos/Actividades/Edit', [
            'convocatoria'                   => $convocatoria->only('id', 'min_fecha_inicio_proyectos', 'max_fecha_finalizacion_proyectos'),
            'proyecto'                       => $proyecto->only('id', 'fecha_inicio', 'fecha_finalizacion', 'modificable'),
            'productos'                      => $productos,
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

    /**
     * updateMetodologia
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Proyecto  $proyecto
     * @return \Illuminate\Http\Response
     */
    public function updateMetodologia(Request $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        $request->validate([
            'metodologia' => 'required|string|max:20000',
        ]);

        switch ($proyecto) {
            case $proyecto->idi()->exists():
                $idi              = $proyecto->idi;
                $idi->metodologia = $request->metodologia;

                $idi->save();
                break;
            case $proyecto->taTp()->exists():
                $tatp              = $proyecto->taTp;
                $tatp->metodologia = $request->metodologia;
                $tatp->metodologia_local = $request->metodologia_local;

                $tatp->save();
                break;
            case $proyecto->culturaInnovacion()->exists():
                $culturaInnovacion              = $proyecto->culturaInnovacion;
                $culturaInnovacion->metodologia = $request->metodologia;

                $culturaInnovacion->save();
                break;
            case $proyecto->servicioTecnologico()->exists():
                $servicioTecnologico              = $proyecto->servicioTecnologico;
                $servicioTecnologico->metodologia = $request->metodologia;

                $servicioTecnologico->save();
                break;
            default:
                break;
        }

        return redirect()->back()->with('success', 'El recurso se ha guardado correctamente.');
    }
}
