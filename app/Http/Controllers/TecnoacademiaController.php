<?php

namespace App\Http\Controllers;

use App\Http\Requests\TecnoacademiaRequest;
use App\Models\LineaTecnoacademia;
use App\Models\Tecnoacademia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class TecnoacademiaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', [Tecnoacademia::class]);

        return Inertia::render('Tecnoacademias/Index', [
            'filters'           => request()->all('search'),
            'tecnoacademias'    => Tecnoacademia::selectRaw("id, nombre, CASE modalidad
                WHEN '1' THEN 'itinerante'
                WHEN '2' THEN 'itinerante - vehículo'
                WHEN '3' THEN 'fija con extensión'
            END as modalidad")->orderBy('nombre', 'ASC')
                ->filterTecnoacademia(request()->only('search'))->paginate()->appends(['search' => request()->search]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', [Tecnoacademia::class]);

        return Inertia::render('Tecnoacademias/Create', [
            'lineasTecnoacademia'   => LineaTecnoacademia::orderBy('nombre', 'ASC')->get(),
            'modalidades'           => json_decode(Storage::get('json/modalidades-tecnoacademia.json'), true),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TecnoacademiaRequest $request)
    {
        $this->authorize('create', [Tecnoacademia::class]);

        $tecnoacademia = new Tecnoacademia();
        $tecnoacademia->nombre                          = $request->nombre;
        $tecnoacademia->modalidad                       = $request->modalidad;
        $tecnoacademia->fecha_creacion                  = $request->fecha_creacion;
        $tecnoacademia->foco                            = $request->foco;
        $tecnoacademia->max_valor_viaticos_interior     = $request->max_valor_viaticos_interior;
        $tecnoacademia->max_valor_edt                   = $request->max_valor_edt;
        $tecnoacademia->max_valor_mantenimiento_equipos = $request->max_valor_mantenimiento_equipos;
        $tecnoacademia->max_valor_roles                 = $request->max_valor_roles;

        $tecnoacademia->centroFormacion()->associate($request->centro_formacion_id);
        $tecnoacademia->save();

        $tecnoacademia->lineasTecnoacademia()->attach($request->linea_tecnoacademia_id);

        return redirect()->route('tecnoacademias.index')->with('success', 'El recurso se ha creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tecnoacademia  $tecnoacademia
     * @return \Illuminate\Http\Response
     */
    public function show(Tecnoacademia $tecnoacademia)
    {
        $this->authorize('view', [Tecnoacademia::class, $tecnoacademia]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tecnoacademia  $tecnoacademia
     * @return \Illuminate\Http\Response
     */
    public function edit(Tecnoacademia $tecnoacademia)
    {
        $this->authorize('update', [Tecnoacademia::class, $tecnoacademia]);

        return Inertia::render('Tecnoacademias/Edit', [
            'tecnoacademia'                     => $tecnoacademia,
            'lineasTecnoacademia'               => LineaTecnoacademia::orderBy('nombre', 'ASC')->get(),
            'lineasTecnoacademiaRelacionadas'   => $tecnoacademia->lineasTecnoacademia()->pluck('lineas_tecnoacademia.id'),
            'modalidades'                       => json_decode(Storage::get('json/modalidades-tecnoacademia.json'), true),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tecnoacademia  $tecnoacademia
     * @return \Illuminate\Http\Response
     */
    public function update(TecnoacademiaRequest $request, Tecnoacademia $tecnoacademia)
    {
        $this->authorize('update', [Tecnoacademia::class, $tecnoacademia]);

        $tecnoacademia->nombre                          = $request->nombre;
        $tecnoacademia->modalidad                       = $request->modalidad;
        $tecnoacademia->fecha_creacion                  = $request->fecha_creacion;
        $tecnoacademia->foco                            = $request->foco;
        $tecnoacademia->max_valor_viaticos_interior     = $request->max_valor_viaticos_interior;
        $tecnoacademia->max_valor_edt                   = $request->max_valor_edt;
        $tecnoacademia->max_valor_mantenimiento_equipos = $request->max_valor_mantenimiento_equipos;
        $tecnoacademia->max_valor_roles                 = $request->max_valor_roles;
        $tecnoacademia->centroFormacion()->associate($request->centro_formacion_id);
        $tecnoacademia->lineasTecnoacademia()->sync($request->linea_tecnoacademia_id);
        $tecnoacademia->save();

        return redirect()->back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tecnoacademia  $tecnoacademia
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tecnoacademia $tecnoacademia)
    {
        $this->authorize('delete', [Tecnoacademia::class, $tecnoacademia]);

        $tecnoacademia->delete();

        return redirect()->route('tecnoacademias.index')->with('success', 'El recurso se ha eliminado correctamente.');
    }
}
