<?php

namespace App\Http\Controllers;

use App\Http\Requests\RolSennovaRequest;
use App\Models\RolSennova;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class RolSennovaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', [RolSennova::class]);

        return Inertia::render('RolSennovas/Index', [
            'filters'   => request()->all('search'),
            'rolSennovas' => RolSennova::orderBy('nombre', 'ASC')
                ->filterRolSennova(request()->only('search'))->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', [RolSennova::class]);

        return Inertia::render('RolSennovas/Create', [
            'academicDegrees' => json_decode(Storage::get('json/academic-degrees.json'), true)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RolSennovaRequest $request)
    {
        $this->authorize('create', [RolSennova::class]);

        $rolSennova = new RolSennova();
        $rolSennova->name              = $request->name;
        $rolSennova->description       = $request->description;

        $rolSennova->save();

        return redirect()->route('sennova-roles.index')->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RolSennova  $rolSennova
     * @return \Illuminate\Http\Response
     */
    public function show(RolSennova $rolSennova)
    {
        $this->authorize('view', [RolSennova::class, $rolSennova]);

        return Inertia::render('RolSennovas/Show', [
            'rolSennova' => $rolSennova
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\RolSennova  $rolSennova
     * @return \Illuminate\Http\Response
     */
    public function edit(RolSennova $rolSennova)
    {
        $this->authorize('update', [RolSennova::class, $rolSennova]);

        return Inertia::render('RolSennovas/Editar', [
            'rolSennova'       => $rolSennova,
            'academicDegrees'   => json_decode(Storage::get('json/academic-degrees.json'), true)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RolSennova  $rolSennova
     * @return \Illuminate\Http\Response
     */
    public function update(RolSennovaRequest $request, RolSennova $rolSennova)
    {
        $this->authorize('update', [RolSennova::class, $rolSennova]);

        $rolSennova->name              = $request->name;
        $rolSennova->description       = $request->description;

        $rolSennova->save();

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RolSennova  $rolSennova
     * @return \Illuminate\Http\Response
     */
    public function destroy(RolSennova $rolSennova)
    {
        $this->authorize('delete', [RolSennova::class, $rolSennova]);

        $rolSennova->delete();

        return redirect()->route('sennova-roles.index')->with('success', 'The resource has been deleted successfully.');
    }
}
