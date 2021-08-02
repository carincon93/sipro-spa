<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnexoRequest;
use App\Models\Anexo;
use App\Models\LineaProgramatica;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class AnexoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', [Anexo::class]);

        return Inertia::render('Anexos/Index', [
            'filters'  => request()->all('search'),
            'anexos'   => Anexo::orderBy('nombre', 'ASC')
                ->filterAnexo(request()->only('search'))->paginate()->appends(['search' => request()->search]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', [Anexo::class]);

        return Inertia::render('Anexos/Create', [
            'lineasProgramaticas' => LineaProgramatica::orderBy('nombre', 'ASC')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AnexoRequest $request)
    {
        $this->authorize('create', [Anexo::class]);

        $anexo = new Anexo();
        $anexo->nombre         = $request->nombre;
        $anexo->descripcion    = $request->descripcion;
        $anexo->obligatorio    = $request->obligatorio;

        if ($request->hasFile('archivo')) {
            $nombreArchivo     = $this->cleanFileName($request->nombre, $request->archivo);
            $archivo = $request->archivo->storeAs(
                'anexos',
                $nombreArchivo
            );

            $anexo->archivo = $archivo;
        }

        $anexo->save();

        $anexo->lineasProgramaticas()->attach($request->linea_programatica_id);


        return redirect()->route('anexos.index')->with('success', 'El recurso se ha creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Anexo  $anexo
     * @return \Illuminate\Http\Response
     */
    public function show(Anexo $anexo)
    {
        $this->authorize('view', [Anexo::class, $anexo]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Anexo  $anexo
     * @return \Illuminate\Http\Response
     */
    public function edit(Anexo $anexo)
    {
        $this->authorize('update', [Anexo::class, $anexo]);

        return Inertia::render('Anexos/Edit', [
            'anexo'                     => $anexo,
            'lineasProgramaticas'       => LineaProgramatica::orderBy('nombre', 'ASC')->get(),
            'anexoLineasProgramaticas'  => $anexo->lineasProgramaticas()->pluck('id'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Anexo  $anexo
     * @return \Illuminate\Http\Response
     */
    public function update(AnexoRequest $request, Anexo $anexo)
    {
        $this->authorize('update', [Anexo::class, $anexo]);

        $anexo->nombre         = $request->nombre;
        $anexo->descripcion    = $request->descripcion;
        $anexo->obligatorio    = $request->obligatorio;
        $anexo->lineasProgramaticas()->sync($request->linea_programatica_id);

        if ($request->hasFile('archivo')) {
            Storage::delete($anexo->archivo);
            $nombreArchivo     = $this->cleanFileName($request->nombre, $request->archivo);
            $archivo = $request->archivo->storeAs(
                'anexos',
                $nombreArchivo
            );

            $anexo->archivo = $archivo;
        }

        $anexo->save();

        return back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Anexo  $anexo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Anexo $anexo)
    {
        $this->authorize('delete', [Anexo::class, $anexo]);

        $anexo->delete();

        return redirect()->route('anexos.index')->with('success', 'El recurso se ha eliminado correctamente.');
    }

    /**
     * download
     *
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @param  mixed $proyectoAnexo
     * @return void
     */
    public function download(Anexo $anexo)
    {
        return response()->download(storage_path("app/$anexo->archivo"));
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
        $random    = Str::random(5);

        return "formato{$cleanName}cod{$random}." . $archivo->extension();
    }
}
