<?php

namespace App\Http\Controllers;

use App\Http\Requests\SemilleroInvestigacionRequest;
use App\Models\LineaInvestigacion;
use App\Models\SemilleroInvestigacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Str;

class SemilleroInvestigacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', [SemilleroInvestigacion::class]);

        return Inertia::render('SemillerosInvestigacion/Index', [
            'filters'   => request()->all('search'),
            'semillerosInvestigacion' => SemilleroInvestigacion::getSemillerosInvestigacionByRol()->appends(['search' => request()->search]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', [SemilleroInvestigacion::class]);

        return Inertia::render('SemillerosInvestigacion/Create', [
            'lineasInvestigacion' => LineaInvestigacion::orderBy('nombre', 'ASC')->select(['id as value', 'nombre as label'])->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SemilleroInvestigacionRequest $request)
    {
        $this->authorize('create', [SemilleroInvestigacion::class]);

        $semilleroInvestigacion = new SemilleroInvestigacion();
        $semilleroInvestigacion->nombre = $request->nombre;
        $semilleroInvestigacion->codigo = 'test';
        $semilleroInvestigacion->fecha_creacion_semillero = $request->fecha_creacion_semillero;
        $semilleroInvestigacion->nombre_lider_semillero = $request->nombre_lider_semillero;
        $semilleroInvestigacion->email_contacto = $request->email_contacto;
        $semilleroInvestigacion->reconocimientos_semillero_investigacion = $request->reconocimientos_semillero_investigacion;
        $semilleroInvestigacion->vision = $request->vision;
        $semilleroInvestigacion->mision = $request->mision;
        $semilleroInvestigacion->objetivo_general = $request->objetivo_general;
        $semilleroInvestigacion->objetivos_especificos = $request->objetivos_especificos;
        $semilleroInvestigacion->link_semillero = $request->link_semillero;


        $nombre_gic_f020 = $this->cleanFileName('formato_gic_f020', $request->formato_gic_f020);

        $formato_gic_f020 = $request->formato_gic_f020->storeAs(
            'formatos_semillero_investigacion',
            $nombre_gic_f020
        );
        $semilleroInvestigacion->formato_gic_f020 = $formato_gic_f020;

        $formato_gic_f032 = $this->cleanFileName('formato_gic_f032', $request->formato_gic_f032);

        $formato_gic_f032 = $request->formato_gic_f032->storeAs(
            'formatos_semillero_investigacion',
            $formato_gic_f032
        );
        $semilleroInvestigacion->formato_gic_f032 = $formato_gic_f032;

        $formato_aval_semillero = $this->cleanFileName('formato_aval_semillero', $request->formato_aval_semillero);

        $formato_aval_semillero = $request->formato_aval_semillero->storeAs(
            'formatos_semillero_investigacion',
            $formato_aval_semillero
        );
        $semilleroInvestigacion->formato_aval_semillero = $formato_aval_semillero;




        $semilleroInvestigacion->lineaInvestigacion()->associate($request->linea_investigacion_id);

        $semilleroInvestigacion->save();

        return redirect()->route('semilleros-investigacion.index')->with('success', 'El recurso se ha creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SemilleroInvestigacion  $semilleroInvestigacion
     * @return \Illuminate\Http\Response
     */
    public function show(SemilleroInvestigacion $semilleroInvestigacion)
    {
        $this->authorize('view', [SemilleroInvestigacion::class, $semilleroInvestigacion]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SemilleroInvestigacion  $semilleroInvestigacion
     * @return \Illuminate\Http\Response
     */
    public function edit(SemilleroInvestigacion $semilleroInvestigacion)
    {
        $this->authorize('update', [SemilleroInvestigacion::class, $semilleroInvestigacion]);

        $semilleroInvestigacion->lineaInvestigacion->grupoInvestigacion;

        return Inertia::render('SemillerosInvestigacion/Edit', [
            'semilleroInvestigacion'  => $semilleroInvestigacion,
            'lineasInvestigacion'     => LineaInvestigacion::orderBy('nombre', 'ASC')->select(['id as value', 'nombre as label'])->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SemilleroInvestigacion  $semilleroInvestigacion
     * @return \Illuminate\Http\Response
     */
    public function update(SemilleroInvestigacionRequest $request, SemilleroInvestigacion $semilleroInvestigacion)
    {
        $this->authorize('update', [SemilleroInvestigacion::class, $semilleroInvestigacion]);

        $semilleroInvestigacion->nombre = $request->nombre;
        $semilleroInvestigacion->codigo = $request->codigo;
        $semilleroInvestigacion->fecha_creacion_semillero = $request->fecha_creacion_semillero;
        $semilleroInvestigacion->nombre_lider_semillero = $request->nombre_lider_semillero;
        $semilleroInvestigacion->email_contacto = $request->email_contacto;
        $semilleroInvestigacion->reconocimientos_semillero_investigacion = $request->reconocimientos_semillero_investigacion;
        $semilleroInvestigacion->vision = $request->vision;
        $semilleroInvestigacion->mision = $request->mision;
        $semilleroInvestigacion->objetivo_general = $request->objetivo_general;
        $semilleroInvestigacion->objetivos_especificos = $request->objetivos_especificos;
        $semilleroInvestigacion->link_semillero = $request->link_semillero;

        if ($request->hasFile('formato_gic_f020')) {
            $formato_gic_f020 = $this->cleanFileName('formato_gic_f020', $request->formato_gic_f020);
            Storage::delete($semilleroInvestigacion->formato_gic_f020);
            $formato_gic_f020 = $request->formato_gic_f020->storeAs(
                'formatos_semillero_investigacion',
                $formato_gic_f020
            );
            $semilleroInvestigacion->formato_gic_f020 = $formato_gic_f020;
        }

        if ($request->hasFile('formato_gic_f032')) {
            $formato_gic_f032 = $this->cleanFileName('formato_gic_f032', $request->formato_gic_f032);
            Storage::delete($semilleroInvestigacion->formato_gic_f032);
            $formato_gic_f032 = $request->formato_gic_f032->storeAs(
                'formatos_semillero_investigacion',
                $formato_gic_f032
            );
            $semilleroInvestigacion->formato_gic_f032 = $formato_gic_f032;
        }

        if ($request->hasFile('formato_aval_semillero')) {
            $formato_aval_semillero = $this->cleanFileName('formato_aval_semillero', $request->formato_aval_semillero);
            Storage::delete($semilleroInvestigacion->formato_aval_semillero);
            $formato_aval_semillero = $request->formato_aval_semillero->storeAs(
                'formatos_semillero_investigacion',
                $formato_aval_semillero
            );
            $semilleroInvestigacion->formato_aval_semillero = $formato_aval_semillero;
        }

        $semilleroInvestigacion->lineaInvestigacion()->associate($request->linea_investigacion_id);

        $semilleroInvestigacion->save();

        return back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SemilleroInvestigacion  $semilleroInvestigacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(SemilleroInvestigacion $semilleroInvestigacion)
    {
        $this->authorize('delete', [SemilleroInvestigacion::class, $semilleroInvestigacion]);

        Storage::delete($semilleroInvestigacion->formato_gic_f020);
        Storage::delete($semilleroInvestigacion->formato_gic_f032);
        Storage::delete($semilleroInvestigacion->formato_aval_semillero);

        $semilleroInvestigacion->delete();


        return redirect()->route('semilleros-investigacion.index')->with('success', 'El recurso se ha eliminado correctamente.');
    }

    /**
     * cleanFileName
     *
     * @param  mixed $nombre
     * @return void
     */
    public function cleanFileName($nombre, $archivo)
    {
        $cleanName = str_replace(' ', '', substr($nombre, 0, 30));
        $cleanName = preg_replace('/[-`~!@#_$%\^&*()+={}[\]\\\\|;:\'",.><?\/]/', '', $cleanName);
        $random    = Str::random(10);

        return str_replace(array("\r", "\n"), '', "{$nombre}{$random}." . $archivo->extension());
    }
}
