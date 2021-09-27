<?php

namespace App\Http\Controllers\Evaluacion;

use App\Models\Evaluacion\Evaluacion;
use App\Http\Controllers\Controller;
use App\Http\Requests\Evaluacion\EvaluacionRequest;
use App\Models\Convocatoria;
use App\Models\Proyecto;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EvaluacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', [Evaluacion::class]);

        return Inertia::render('Evaluaciones/Index', [
            'filters'       => request()->all('search'),
            'evaluaciones'  => Evaluacion::with('proyecto.tecnoacademiaLineasTecnoacademia.tecnoacademia', 'proyecto.ta:id,fecha_inicio,fecha_finalizacion', 'proyecto.idi:id,titulo,fecha_inicio,fecha_finalizacion', 'proyecto.tp:id,nodo_tecnoparque_id,fecha_inicio,fecha_finalizacion', 'proyecto.culturaInnovacion:id,titulo,fecha_inicio,fecha_finalizacion', 'proyecto.servicioTecnologico:id,titulo,fecha_inicio,fecha_finalizacion', 'proyecto.centroFormacion', 'evaluador:id,nombre')->orderBy('evaluaciones.id', 'ASC')
                ->filterEvaluacion(request()->only('search', 'estado'))->paginate()->appends(['search' => request()->search, 'estado' => request()->estado]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', [Evaluacion::class]);

        return Inertia::render('Evaluaciones/Create', [
            'proyectos'     => Proyecto::selectRaw("id as value, concat('SGPS-', id + 8000, '-SIPRO') as label")->with('idi', 'ta', 'tp', 'servicioTecnologico', 'culturaInnovacion')->orderBy('id', 'ASC')->get(),
            'evaluadores'   => User::select('users.id as value', 'users.nombre as label')->join('model_has_roles', 'users.id', 'model_has_roles.model_id')->join('roles', 'model_has_roles.role_id', 'roles.id')->where('roles.id', 11)->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EvaluacionRequest $request)
    {
        $this->authorize('create', [Evaluacion::class]);

        $proyecto = Proyecto::find($request->proyecto_id);

        if (Evaluacion::where('proyecto_id', $request->proyecto_id)->where('habilitado', true)->count() == 2 && $request->habilitado && $proyecto->lineaProgramatica->codigo != 68) {
            return redirect()->back()->with('error', 'Este proyecto ya tiene dos evaluaciones habilitadas. Debe modificar alguna evaluación.');
        } else if (Evaluacion::where('proyecto_id', $request->proyecto_id)->where('habilitado', true)->count() == 3 && $request->habilitado && $proyecto->lineaProgramatica->codigo == 68) {
            return redirect()->back()->with('error', 'Este proyecto ya tiene tres evaluaciones habilitadas. Debe modificar alguna evaluación.');
        }

        $evaluacion = new Evaluacion();
        $evaluacion->habilitado  = $request->habilitado;
        $evaluacion->modificable = $request->modificable;
        $evaluacion->iniciado    = false;
        $evaluacion->finalizado  = $request->finalizado;
        $evaluacion->evaluador()->associate($request->user_id);
        $evaluacion->proyecto()->associate($request->proyecto_id);

        $evaluacion->save();

        $proyecto->finalizado = true;
        $proyecto->modificable = false;
        $proyecto->save();

        switch ($proyecto) {
            case $proyecto->idi()->exists():
                $evaluacion->idiEvaluacion()->create([
                    'id' => $evaluacion->id
                ]);
                break;
            case $proyecto->ta()->exists():
                $evaluacion->taEvaluacion()->create([
                    'id' => $evaluacion->id
                ]);
                break;
            case $proyecto->tp()->exists():
                $evaluacion->tpEvaluacion()->create([
                    'id' => $evaluacion->id
                ]);
                break;
            case $proyecto->servicioTecnologico()->exists():
                $evaluacion->servicioTecnologicoEvaluacion()->create([
                    'id' => $evaluacion->id
                ]);
                break;
            case $proyecto->culturaInnovacion()->exists():
                $evaluacion->culturaInnovacionEvaluacion()->create([
                    'id' => $evaluacion->id
                ]);
                break;
            default:
                break;
        }

        return redirect()->route('evaluaciones.index')->with('success', 'El recurso se ha creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Evaluacion\Evaluacion  $evaluacion
     * @return \Illuminate\Http\Response
     */
    public function show(Evaluacion $evaluacion)
    {
        $this->authorize('view', [Evaluacion::class, $evaluacion]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Evaluacion\Evaluacion  $evaluacion
     * @return \Illuminate\Http\Response
     */
    public function edit(Evaluacion $evaluacion)
    {
        $this->authorize('update', [Evaluacion::class, $evaluacion]);

        $evaluacion->proyecto->only('codigo');

        return Inertia::render('Evaluaciones/Edit', [
            'evaluacion'    => $evaluacion,
            'proyectos'     => Proyecto::selectRaw("id as value, concat('SGPS-', id + 8000, '-SIPRO') as label")->with('idi', 'ta', 'tp', 'servicioTecnologico', 'culturaInnovacion')->orderBy('id', 'ASC')->get(),
            'evaluadores'   => User::select('users.id as value', 'users.nombre as label')->join('model_has_roles', 'users.id', 'model_has_roles.model_id')->join('roles', 'model_has_roles.role_id', 'roles.id')->where('roles.id', 11)->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Evaluacion\Evaluacion  $evaluacion
     * @return \Illuminate\Http\Response
     */
    public function update(EvaluacionRequest $request, Evaluacion $evaluacion)
    {
        $this->authorize('update', [Evaluacion::class, $evaluacion]);

        if (Evaluacion::where('proyecto_id', $request->proyecto_id)->where('habilitado', true)->where('evaluaciones.id', '!=', $evaluacion->id)->count() == 2 && $request->habilitado && $evaluacion->proyecto->lineaProgramatica->codigo != 68) {
            return redirect()->back()->with('error', 'Este proyecto ya tiene dos evaluaciones habilitadas. Debe modificar alguna evaluación.');
        } else if (Evaluacion::where('proyecto_id', $request->proyecto_id)->where('habilitado', true)->where('evaluaciones.id', '!=', $evaluacion->id)->count() == 3 && $request->habilitado && $evaluacion->proyecto->lineaProgramatica->codigo == 68) {
            return redirect()->back()->with('error', 'Este proyecto ya tiene tres evaluaciones habilitadas. Debe modificar alguna evaluación.');
        }

        $evaluacion->habilitado  = $request->habilitado;
        $evaluacion->modificable = $request->modificable;
        $evaluacion->finalizado  = $request->finalizado;
        $evaluacion->evaluador()->associate($request->user_id);
        $evaluacion->proyecto()->associate($evaluacion->proyecto_id);

        $evaluacion->save();

        return back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Evaluacion\Evaluacion  $evaluacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Evaluacion $evaluacion)
    {
        $this->authorize('delete', [Evaluacion::class, $evaluacion]);

        $evaluacion->delete();

        return redirect()->route('evaluaciones.index')->with('success', 'El recurso se ha eliminado correctamente.');
    }

    /**
     * redireccionar
     *
     * @param  mixed $convocatoria
     * @param  mixed $evaluacion
     * @return void
     */
    public function redireccionar(Convocatoria $convocatoria, Evaluacion $evaluacion)
    {
        switch ($evaluacion) {
            case $evaluacion->proyecto->idi()->exists():
                return redirect()->route('convocatorias.idi-evaluaciones.edit', [$convocatoria, $evaluacion]);
                break;
            case $evaluacion->proyecto->ta()->exists():
                return redirect()->route('convocatorias.ta-evaluaciones.edit', [$convocatoria, $evaluacion]);
                break;
            case $evaluacion->proyecto->tp()->exists():
                return redirect()->route('convocatorias.tp-evaluaciones.edit', [$convocatoria, $evaluacion]);
                break;
            case $evaluacion->proyecto->servicioTecnologico()->exists():
                return redirect()->route('convocatorias.servicios-tecnologicos-evaluaciones.edit', [$convocatoria, $evaluacion]);
                break;
            case $evaluacion->proyecto->culturaInnovacion()->exists():
                return redirect()->route('convocatorias.cultura-innovacion-evaluaciones.edit', [$convocatoria, $evaluacion]);
                break;
            default:
                break;
        }
    }

    /**
     * editCausalRechazo
     *
     * @param  mixed $convocatoria
     * @param  mixed $evaluacion
     * @return void
     */
    public function editCausalRechazo(Convocatoria $convocatoria, Evaluacion $evaluacion)
    {
        $this->authorize('visualizar-evaluacion-autor', $evaluacion);

        $evaluacion->proyecto->codigo_linea_programatica = $evaluacion->proyecto->lineaProgramatica->codigo;

        return Inertia::render('Convocatorias/Evaluaciones/CausalRechazo', [
            'convocatoria'                  => $convocatoria->only('id', 'fase_formateada', 'fase'),
            'evaluacion'                    => $evaluacion,
            'causalesRechazoRegistradas'    => $evaluacion->evaluacionCausalesRechazo()->pluck('causal_rechazo'),
            'proyecto'                      => $evaluacion->proyecto,
        ]);
    }

    /**
     * updateCausalRechazo
     *
     * @param  mixed $request
     * @param  mixed $convocatoria
     * @param  mixed $evaluacion
     * @return void
     */
    public function updateCausalRechazo(Request $request, Convocatoria $convocatoria, Evaluacion $evaluacion)
    {
        $this->authorize('modificar-evaluacion-autor', $evaluacion);

        $evaluacion->evaluacionCausalesRechazo()->delete();
        $evaluacion->update(['justificacion_causal_rechazo' => $request->justificacion_causal_rechazo]);
        foreach ($request->causal_rechazo as $causalRechazo) {
            $evaluacion->evaluacionCausalesRechazo()->updateOrCreate(
                ['evaluacion_id' => $evaluacion->id, 'causal_rechazo' => $causalRechazo],
            );
        }

        return back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * showComentariosGeneralesForm
     *
     * @param  mixed $convocatoria
     * @param  mixed $evaluacion
     * @return void
     */
    public function showComentariosGeneralesForm(Convocatoria $convocatoria, Evaluacion $evaluacion)
    {
        $this->authorize('visualizar-evaluacion-autor', $evaluacion);

        $evaluacion->proyecto->codigo_linea_programatica = $evaluacion->proyecto->lineaProgramatica->codigo;

        return Inertia::render('Convocatorias/Evaluaciones/ComentariosGenerales', [
            'convocatoria'                  => $convocatoria->only('id', 'fase_formateada', 'fase'),
            'evaluacion'                    => $evaluacion->only('id', 'finalizado', 'habilitado', 'justificacion_causal_rechazo', ' comentario_formulador', 'comentario_evaluador', 'replicas'),
            'proyecto'                      => $evaluacion->proyecto,
        ]);
    }

    /**
     * udpdateComentariosGenerales
     *
     * @param  mixed $convocatoria
     * @param  mixed $evaluacion
     * @return void
     */
    public function udpdateComentariosGenerales(Request $request, Convocatoria $convocatoria, Evaluacion $evaluacion)
    {
        $this->authorize('modificar-evaluacion-autor', $evaluacion);

        if ($request->has('comentario_evaluador')) {
            $evaluacion->update(
                ['evaluacion_id' => $evaluacion->id, 'comentario_evaluador' => $request->comentario_evaluador],
            );
        }

        return back()->with('success', 'El recurso se ha actualizado correctamente.');
    }
}
