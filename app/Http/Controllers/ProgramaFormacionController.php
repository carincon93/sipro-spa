<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProgramaFormacionRequest;
use App\Models\Regional;
use App\Models\ProgramaFormacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProgramaFormacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', [ProgramaFormacion::class]);

        return Inertia::render('ProgramasFormacion/Index', [
            'filters'   => request()->all('search'),
            'programasFormacion' => ProgramaFormacion::with(['centroFormacion' => function ($query) {
                    $query->orderBy('nombre', 'ASC');
                }])
                ->filterProgramaFormacion(request()->only('search'))->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', [ProgramaFormacion::class]);

        return Inertia::render('ProgramasFormacion/Create', [
            'modalidades' => json_decode(Storage::get('json/modalidades-estudio.json'), true)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProgramaFormacionRequest $request)
    {
        $this->authorize('create', [ProgramaFormacion::class]);

        $programaFormacion = new ProgramaFormacion();
        $programaFormacion->nombre      = $request->nombre;
        $programaFormacion->codigo      = $request->codigo;
        $programaFormacion->modalidad   = $request->modalidad;
        $programaFormacion->centroFormacion()->associate($request->centro_formacion_id);

        $programaFormacion->save();

        return redirect()->route('programas-formacion.index')->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProgramaFormacion  $programaFormacion
     * @return \Illuminate\Http\Response
     */
    public function show(ProgramaFormacion $programaFormacion)
    {
        $this->authorize('view', [ProgramaFormacion::class, $programaFormacion]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProgramaFormacion  $programaFormacion
     * @return \Illuminate\Http\Response
     */
    public function edit(ProgramaFormacion $programaFormacion)
    {
        $this->authorize('update', [ProgramaFormacion::class, $programaFormacion]);

        return Inertia::render('ProgramasFormacion/Edit', [
            'programaFormacion'   => $programaFormacion->only(['id', 'nombre', 'codigo', 'modalidad', 'centro_formacion_id']),
            'modalidades'         => json_decode(Storage::get('json/modalidades-estudio.json'), true)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProgramaFormacion  $programaFormacion
     * @return \Illuminate\Http\Response
     */
    public function update(ProgramaFormacionRequest $request, ProgramaFormacion $programaFormacion)
    {
        $this->authorize('update', [ProgramaFormacion::class, $programaFormacion]);

        $programaFormacion->nombre      = $request->nombre;
        $programaFormacion->codigo      = $request->codigo;
        $programaFormacion->modalidad   = $request->modalidad;
        $programaFormacion->centroFormacion()->associate($request->centro_formacion_id);

        $programaFormacion->save();

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProgramaFormacion  $programaFormacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProgramaFormacion $programaFormacion)
    {
        $this->authorize('delete', [ProgramaFormacion::class, $programaFormacion]);

        $programaFormacion->delete();

        return redirect()->route('programas-formacion.index')->with('success', 'The resource has been deleted successfully.');
    }
}
