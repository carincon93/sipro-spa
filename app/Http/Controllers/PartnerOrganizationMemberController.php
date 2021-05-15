<?php

namespace App\Http\Controllers;

use App\Http\Requests\EntidadAliadaMemberRequest;
use App\Models\Convocatoria;
use App\Models\IDi;
use App\Models\EntidadAliada;
use App\Models\EntidadAliadaMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class EntidadAliadaMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Convocatoria $convocatoria, IDi $IDi, EntidadAliada $EntidadAliada)
    {
        $this->authorize('viewAny', [EntidadAliadaMember::class]);

        return Inertia::render('Convocatorias/Proyectos/IDi/EntidadAliadas/EntidadAliadaMembers/Index', [
            'filters'   => request()->all('search'),
            'EntidadAliadaMembers' => EntidadAliadaMember::orderBy('nombre', 'ASC')
                ->filterEntidadAliadaMember(request()->only('search'))->paginate(),
            'convocatoria'                  => $convocatoria,
            'IDi'                   => $IDi,
            'EntidadAliada'   => $EntidadAliada
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Convocatoria $convocatoria, IDi $IDi, EntidadAliada $EntidadAliada)
    {
        $this->authorize('create', [EntidadAliadaMember::class]);

        return Inertia::render('Convocatorias/Proyectos/IDi/EntidadAliadas/EntidadAliadaMembers/Create', [
            'documentTypes'         => json_decode(Storage::get('json/document-types.json'), true),
            'convocatoria'                  => $convocatoria->only('id'),
            'IDi'                   => $IDi->only('id'),
            'EntidadAliada'   => $EntidadAliada->only('id')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EntidadAliadaMemberRequest $request, Convocatoria $convocatoria, IDi $IDi, EntidadAliada $EntidadAliada)
    {
        $this->authorize('create', [EntidadAliadaMember::class]);

        $EntidadAliadaMember = new EntidadAliadaMember();
        $EntidadAliadaMember->name                = $request->name;
        $EntidadAliadaMember->email               = $request->email;
        $EntidadAliadaMember->document_type       = $request->document_type;
        $EntidadAliadaMember->document_number     = $request->document_number;
        $EntidadAliadaMember->cellphone_number    = $request->cellphone_number;
        $EntidadAliadaMember->EntidadAliada()->associate($EntidadAliada);

        $EntidadAliadaMember->save();

        return redirect()->route('convocatorias.IDi.partner-organizations.partner-organization-members.index', [$convocatoria, $IDi, $EntidadAliada])->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EntidadAliadaMember  $EntidadAliadaMember
     * @return \Illuminate\Http\Response
     */
    public function show(Convocatoria $convocatoria, IDi $IDi, EntidadAliada $EntidadAliada, EntidadAliadaMember $EntidadAliadaMember)
    {
        $this->authorize('view', [EntidadAliadaMember::class, $EntidadAliadaMember]);

        return Inertia::render('Convocatorias/Proyectos/IDi/EntidadAliadas/EntidadAliadaMembers/Show', [
            'convocatoria'                      => $convocatoria,
            'IDi'                       => $IDi,
            'EntidadAliada'       => $EntidadAliada,
            'EntidadAliadaMember' => $EntidadAliadaMember
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EntidadAliadaMember  $EntidadAliadaMember
     * @return \Illuminate\Http\Response
     */
    public function edit(Convocatoria $convocatoria, IDi $IDi, EntidadAliada $EntidadAliada, EntidadAliadaMember $EntidadAliadaMember)
    {
        $this->authorize('update', [EntidadAliadaMember::class, $EntidadAliadaMember]);

        return Inertia::render('Convocatorias/Proyectos/IDi/EntidadAliadas/EntidadAliadaMembers/Editar', [
            'convocatoria'                      => $convocatoria->only('id'),
            'IDi'                       => $IDi->only('id'),
            'documentTypes'         => json_decode(Storage::get('json/document-types.json'), true),
            'EntidadAliada'       => $EntidadAliada->only('id'),
            'EntidadAliadaMember' => $EntidadAliadaMember,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EntidadAliadaMember  $EntidadAliadaMember
     * @return \Illuminate\Http\Response
     */
    public function update(EntidadAliadaMemberRequest $request, Convocatoria $convocatoria, IDi $IDi, EntidadAliada $EntidadAliada, EntidadAliadaMember $EntidadAliadaMember)
    {
        $this->authorize('update', [EntidadAliadaMember::class, $EntidadAliadaMember]);

        $EntidadAliadaMember->name                = $request->name;
        $EntidadAliadaMember->email               = $request->email;
        $EntidadAliadaMember->document_type       = $request->document_type;
        $EntidadAliadaMember->document_number     = $request->document_number;
        $EntidadAliadaMember->cellphone_number    = $request->cellphone_number;
        $EntidadAliadaMember->EntidadAliada()->associate($EntidadAliada);

        $EntidadAliadaMember->save();

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EntidadAliadaMember  $EntidadAliadaMember
     * @return \Illuminate\Http\Response
     */
    public function destroy(Convocatoria $convocatoria, IDi $IDi, EntidadAliada $EntidadAliada, EntidadAliadaMember $EntidadAliadaMember)
    {
        $this->authorize('delete', [EntidadAliadaMember::class, $EntidadAliadaMember]);

        $EntidadAliadaMember->delete();

        return redirect()->route('convocatorias.IDi.partner-organizations.partner-organization-members.index', [$convocatoria, $IDi, $EntidadAliada])->with('success', 'The resource has been deleted successfully.');
    }
}
