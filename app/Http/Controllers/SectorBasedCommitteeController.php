<?php

namespace App\Http\Controllers;

use App\Http\Requests\SectorBasedCommitteeRequest;
use App\Models\SectorBasedCommittee;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SectorBasedCommitteeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', [SectorBasedCommittee::class]);

        return Inertia::render('SectorBasedCommittees/Index', [
            'filters'   => request()->all('search'),
            'sectorBasedCommittees' => SectorBasedCommittee::orderBy('nombre', 'ASC')
                ->filterSectorBasedCommittee(request()->only('search'))->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', [SectorBasedCommittee::class]);

        return Inertia::render('SectorBasedCommittees/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SectorBasedCommitteeRequest $request)
    {
        $this->authorize('create', [SectorBasedCommittee::class]);

        $sectorBasedCommittee = new SectorBasedCommittee();
        $sectorBasedCommittee->name = $request->name;

        $sectorBasedCommittee->save();

        return redirect()->route('sector-based-committees.index')->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SectorBasedCommittee  $sectorBasedCommittee
     * @return \Illuminate\Http\Response
     */
    public function show(SectorBasedCommittee $sectorBasedCommittee)
    {
        $this->authorize('view', [SectorBasedCommittee::class, $sectorBasedCommittee]);

        return Inertia::render('SectorBasedCommittees/Show', [
            'sectorBasedCommittee' => $sectorBasedCommittee
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SectorBasedCommittee  $sectorBasedCommittee
     * @return \Illuminate\Http\Response
     */
    public function edit(SectorBasedCommittee $sectorBasedCommittee)
    {
        $this->authorize('update', [SectorBasedCommittee::class, $sectorBasedCommittee]);

        return Inertia::render('SectorBasedCommittees/Editar', [
            'sectorBasedCommittee' => $sectorBasedCommittee
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SectorBasedCommittee  $sectorBasedCommittee
     * @return \Illuminate\Http\Response
     */
    public function update(SectorBasedCommitteeRequest $request, SectorBasedCommittee $sectorBasedCommittee)
    {
        $this->authorize('update', [SectorBasedCommittee::class, $sectorBasedCommittee]);

        $sectorBasedCommittee->name = $request->name;

        $sectorBasedCommittee->save();

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SectorBasedCommittee  $sectorBasedCommittee
     * @return \Illuminate\Http\Response
     */
    public function destroy(SectorBasedCommittee $sectorBasedCommittee)
    {
        $this->authorize('delete', [SectorBasedCommittee::class, $sectorBasedCommittee]);

        $sectorBasedCommittee->delete();

        return redirect()->route('sector-based-committees.index')->with('success', 'The resource has been deleted successfully.');
    }
}
