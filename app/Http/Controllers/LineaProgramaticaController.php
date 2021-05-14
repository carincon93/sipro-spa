<?php

namespace App\Http\Controllers;

use App\Http\Requests\LineaProgramaticaRequest;
use App\Models\LineaProgramatica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class LineaProgramaticaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', [LineaProgramatica::class]);

        return Inertia::render('LineasProgramaticas/Index', [
            'filters'               => request()->all('search'),
            'lineasProgramaticas'   => LineaProgramatica::selectRaw("lineas_programaticas.id, lineas_programaticas.nombre, lineas_programaticas.codigo, CASE lineas_programaticas.categoria_proyecto
                WHEN '1' THEN	'Tecnoacademia-Tecnoparque'
                WHEN '2' THEN	'I+D+i'
                WHEN '3' THEN	'Servicios tecnológicos'
                WHEN '4' THEN	'Otro'
            END as categoria_proyecto")
            ->orderBy('nombre', 'ASC')
                ->filterLineaProgramatica(request()->only('search'))->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', [LineaProgramatica::class]);

        return Inertia::render('LineasProgramaticas/Crear', [
            'categoriasProyectos' => json_decode(Storage::get('json/categorias-proyectos.json'), true),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LineaProgramaticaRequest $request)
    {
        $this->authorize('create', [LineaProgramatica::class]);

        $lineaProgramatica = new LineaProgramatica();
        $lineaProgramatica->nombre               = $request->nombre;
        $lineaProgramatica->codigo               = $request->codigo;
        $lineaProgramatica->categoria_proyecto   = $request->categoria_proyecto;
        $lineaProgramatica->descripcion          = $request->descripcion;

        $lineaProgramatica->save();

        return redirect()->route('lineas-programaticas.index')->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LineaProgramatica  $lineaProgramatica
     * @return \Illuminate\Http\Response
     */
    public function show(LineaProgramatica $lineaProgramatica)
    {
        $this->authorize('view', [LineaProgramatica::class, $lineaProgramatica]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LineaProgramatica  $lineaProgramatica
     * @return \Illuminate\Http\Response
     */
    public function edit(LineaProgramatica $lineaProgramatica)
    {
        $this->authorize('update', [LineaProgramatica::class, $lineaProgramatica]);

        return Inertia::render('LineasProgramaticas/Editar', [
            'lineaProgramatica'   => $lineaProgramatica->only('id', 'nombre', 'descripcion', 'codigo', 'categoria_proyecto', 'descripcion'),
            'categoriasProyectos' => json_decode(Storage::get('json/categorias-proyectos.json'), true),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LineaProgramatica  $lineaProgramatica
     * @return \Illuminate\Http\Response
     */
    public function update(LineaProgramaticaRequest $request, LineaProgramatica $lineaProgramatica)
    {
        $this->authorize('update', [LineaProgramatica::class, $lineaProgramatica]);

        $lineaProgramatica->nombre              = $request->nombre;
        $lineaProgramatica->codigo              = $request->codigo;
        $lineaProgramatica->categoria_proyecto  = $request->categoria_proyecto;
        $lineaProgramatica->descripcion         = $request->descripcion;

        $lineaProgramatica->save();

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LineaProgramatica  $lineaProgramatica
     * @return \Illuminate\Http\Response
     */
    public function destroy(LineaProgramatica $lineaProgramatica)
    {
        $this->authorize('delete', [LineaProgramatica::class, $lineaProgramatica]);

        $lineaProgramatica->delete();

        return redirect()->route('lineas-programaticas.index')->with('success', 'The resource has been deleted successfully.');
    }
}
