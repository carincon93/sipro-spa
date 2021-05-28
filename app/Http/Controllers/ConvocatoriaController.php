<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConvocatoriaRequest;
use App\Models\Convocatoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ConvocatoriaController extends Controller
{
    /**
     * Display the dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard(Convocatoria $convocatoria)
    {
        $this->authorize('viewAny', [Convocatoria::class]);

        return Inertia::render('Convocatorias/Dashboard', [
            'convocatoria' => $convocatoria
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', [Convocatoria::class]);

        return Inertia::render('Convocatorias/Index', [
            'filters'       => request()->all('search'),
            'convocatorias' => Convocatoria::orderBy('fecha_inicio', 'DESC')
                ->filterConvocatoria(request()->only('search'))->paginate(),
            'convocatoriaActiva' => Convocatoria::where('esta_activa', 1)->first(),

        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', [Convocatoria::class]);

        return Inertia::render('Convocatorias/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ConvocatoriaRequest $request)
    {
        $this->authorize('create', [Convocatoria::class]);

        $convocatoria = new Convocatoria();
        $convocatoria->descripcion                      = $request->descripcion;
        $convocatoria->fecha_inicio                     = $request->fecha_inicio;
        $convocatoria->fecha_finalizacion               = $request->fecha_finalizacion;
        $convocatoria->min_fecha_inicio_proyectos       = $request->min_fecha_inicio_proyectos;
        $convocatoria->max_fecha_finalizacion_proyectos = $request->max_fecha_finalizacion_proyectos;
        if ($request->esta_activa) {
            $convocatoriaPrevActiva = Convocatoria::where('esta_activa', true)->first();
            $convocatoriaPrevActiva->esta_activa = false;
            $convocatoriaPrevActiva->save();
        }
        $convocatoria->esta_activa = $request->esta_activa;

        $convocatoria->save();

        return redirect()->route('convocatorias.index')->with('success', 'El recurso se ha creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Convocatoria  $convocatoria
     * @return \Illuminate\Http\Response
     */
    public function show(Convocatoria $convocatoria)
    {
        $this->authorize('view', [Convocatoria::class, $convocatoria]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Convocatoria  $convocatoria
     * @return \Illuminate\Http\Response
     */
    public function edit(Convocatoria $convocatoria)
    {
        $this->authorize('update', [Convocatoria::class, $convocatoria]);

        return Inertia::render('Convocatorias/Edit', [
            'convocatoria' => $convocatoria
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Convocatoria  $convocatoria
     * @return \Illuminate\Http\Response
     */
    public function update(ConvocatoriaRequest $request, Convocatoria $convocatoria)
    {
        $this->authorize('update', [Convocatoria::class, $convocatoria]);

        $convocatoria->descripcion                      = $request->descripcion;
        $convocatoria->fecha_inicio                     = $request->fecha_inicio;
        $convocatoria->fecha_finalizacion               = $request->fecha_finalizacion;
        $convocatoria->min_fecha_inicio_proyectos       = $request->min_fecha_inicio_proyectos;
        $convocatoria->max_fecha_finalizacion_proyectos = $request->max_fecha_finalizacion_proyectos;
        if ($request->esta_activa) {
            $convocatoriaPrevActiva = Convocatoria::where('esta_activa', true)->first();
            if ($convocatoriaPrevActiva) {
                $convocatoriaPrevActiva->esta_activa = false;
                $convocatoriaPrevActiva->save();
            }
        }
        $convocatoria->esta_activa = $request->esta_activa;

        $convocatoria->save();

        return redirect()->back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Convocatoria  $convocatoria
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Convocatoria $convocatoria)
    {
        $this->authorize('delete', [Convocatoria::class, $convocatoria]);
        if (!Hash::check($request->password, Auth::user()->password)) {
            return redirect()->back()
                ->withErrors(['password' => 'Contraseña incorrecta']);
        }

        $convocatoria->delete();

        return redirect()->route('convocatorias.index')->with('success', 'El recurso se ha eliminado correctamente.');
    }
}
