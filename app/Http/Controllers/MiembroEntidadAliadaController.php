<?php

namespace App\Http\Controllers;

use App\Http\Requests\MiembroEntidadAliadaRequest;
use App\Models\Convocatoria;
use App\Models\IDi;
use App\Models\EntidadAliada;
use App\Models\MiembroEntidadAliada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class MiembroEntidadAliadaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Convocatoria $convocatoria, IDi $IDi, EntidadAliada $entidadAliada)
    {
        $this->authorize('viewAny', [MiembroEntidadAliada::class]);

        return Inertia::render('Convocatorias/Proyectos/IDi/EntidadesAliadas/MiembrosEntidadAliada/Index', [
            'convocatoria'          => $convocatoria->only('id'),
            'idi'                   => $IDi->only('id'),
            'entidadAliada'         => $entidadAliada,
            'filters'               => request()->all('search'),
            'miembrosEntidadAliada' => MiembroEntidadAliada::orderBy('nombre', 'ASC')
                ->filterMiembroEntidadAliada(request()->only('search'))->paginate()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Convocatoria $convocatoria, IDi $IDi, EntidadAliada $entidadAliada)
    {
        $this->authorize('create', [MiembroEntidadAliada::class]);

        return Inertia::render('Convocatorias/Proyectos/IDi/EntidadesAliadas/MiembrosEntidadAliada/Create', [
            'convocatoria'    => $convocatoria->only('id'),
            'idi'             => $IDi->only('id'),
            'entidadAliada'   => $entidadAliada->only('id'),
            'tiposDocumento'  => json_decode(Storage::get('json/tipos-documento.json'), true),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MiembroEntidadAliadaRequest $request, Convocatoria $convocatoria, IDi $IDi, EntidadAliada $entidadAliada)
    {
        $this->authorize('create', [MiembroEntidadAliada::class]);

        $miembroEntidadAliada = new MiembroEntidadAliada();
        $miembroEntidadAliada->nombre            = $request->nombre;
        $miembroEntidadAliada->email             = $request->email;
        $miembroEntidadAliada->tipo_documento    = $request->tipo_documento;
        $miembroEntidadAliada->numero_documento  = $request->numero_documento;
        $miembroEntidadAliada->numero_celular    = $request->numero_celular;
        $miembroEntidadAliada->entidadAliada()->associate($entidadAliada);

        $miembroEntidadAliada->save();

        return redirect()->route('convocatorias.idi.entidades-aliadas.miembros-entidad-aliada.index', [$convocatoria, $IDi, $entidadAliada])->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MiembroEntidadAliada  $miembroEntidadAliada
     * @return \Illuminate\Http\Response
     */
    public function show(Convocatoria $convocatoria, IDi $IDi, EntidadAliada $entidadAliada, MiembroEntidadAliada $miembroEntidadAliada)
    {
        $this->authorize('view', [MiembroEntidadAliada::class, $miembroEntidadAliada]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MiembroEntidadAliada  $miembroEntidadAliada
     * @return \Illuminate\Http\Response
     */
    public function edit(Convocatoria $convocatoria, IDi $IDi, EntidadAliada $entidadAliada, MiembroEntidadAliada $miembroEntidadAliada)
    {
        $this->authorize('update', [MiembroEntidadAliada::class, $miembroEntidadAliada]);

        return Inertia::render('Convocatorias/Proyectos/IDi/EntidadesAliadas/MiembrosEntidadAliada/Edit', [
            'convocatoria'         => $convocatoria->only('id'),
            'idi'                  => $IDi->only('id'),
            'miembroEntidadAliada' => $miembroEntidadAliada,
            'tiposDocumento'       => json_decode(Storage::get('json/tipos-documento.json'), true),
            'entidadAliada'        => $entidadAliada->only('id'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MiembroEntidadAliada  $miembroEntidadAliada
     * @return \Illuminate\Http\Response
     */
    public function update(MiembroEntidadAliadaRequest $request, Convocatoria $convocatoria, IDi $IDi, EntidadAliada $entidadAliada, MiembroEntidadAliada $miembroEntidadAliada)
    {
        $this->authorize('update', [MiembroEntidadAliada::class, $miembroEntidadAliada]);

        $miembroEntidadAliada->nombre            = $request->nombre;
        $miembroEntidadAliada->email             = $request->email;
        $miembroEntidadAliada->tipo_documento    = $request->tipo_documento;
        $miembroEntidadAliada->numero_documento  = $request->numero_documento;
        $miembroEntidadAliada->numero_celular    = $request->numero_celular;
        $miembroEntidadAliada->entidadAliada()->associate($entidadAliada);

        $miembroEntidadAliada->save();

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MiembroEntidadAliada  $miembroEntidadAliada
     * @return \Illuminate\Http\Response
     */
    public function destroy(Convocatoria $convocatoria, IDi $IDi, EntidadAliada $entidadAliada, MiembroEntidadAliada $miembroEntidadAliada)
    {
        $this->authorize('delete', [MiembroEntidadAliada::class, $miembroEntidadAliada]);

        $miembroEntidadAliada->delete();

        return redirect()->route('convocatorias.idi.entidades-aliadas.miembros-entidad-aliada.index', [$convocatoria, $IDi, $entidadAliada])->with('success', 'The resource has been deleted successfully.');
    }
}
