<?php

namespace App\Http\Controllers;

use App\Http\Requests\SennovaBudgetRequest;
use App\Models\SennovaBudget;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SennovaBudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', [SennovaBudget::class]);

        return Inertia::render('SennovaBudgets/Index', [
            'filters'   => request()->all('search'),
            'sennovaBudgets' => SennovaBudget::orderBy('', 'ASC')
                ->filterSennovaBudget(request()->only('search'))->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', [SennovaBudget::class]);

        return Inertia::render('SennovaBudgets/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SennovaBudgetRequest $request)
    {
        $this->authorize('create', [SennovaBudget::class]);

        $sennovaBudget = new SennovaBudget();
        $sennovaBudget->fieldName = $request->fieldName;
        $sennovaBudget->fieldName = $request->fieldName;
        $sennovaBudget->fieldName = $request->fieldName;

        $sennovaBudget->save();

        return redirect()->route('resourceRoute.index')->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SennovaBudget  $sennovaBudget
     * @return \Illuminate\Http\Response
     */
    public function show(SennovaBudget $sennovaBudget)
    {
        $this->authorize('view', [SennovaBudget::class, $sennovaBudget]);

        return Inertia::render('SennovaBudgets/Show', [
            'sennovaBudget' => $sennovaBudget
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SennovaBudget  $sennovaBudget
     * @return \Illuminate\Http\Response
     */
    public function edit(SennovaBudget $sennovaBudget)
    {
        $this->authorize('update', [SennovaBudget::class, $sennovaBudget]);

        return Inertia::render('SennovaBudgets/Edit', [
            'sennovaBudget' => $sennovaBudget
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SennovaBudget  $sennovaBudget
     * @return \Illuminate\Http\Response
     */
    public function update(SennovaBudgetRequest $request, SennovaBudget $sennovaBudget)
    {
        $this->authorize('update', [SennovaBudget::class, $sennovaBudget]);

        $sennovaBudget->fieldName = $request->fieldName;
        $sennovaBudget->fieldName = $request->fieldName;
        $sennovaBudget->fieldName = $request->fieldName;

        $sennovaBudget->save();

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SennovaBudget  $sennovaBudget
     * @return \Illuminate\Http\Response
     */
    public function destroy(SennovaBudget $sennovaBudget)
    {
        $this->authorize('delete', [SennovaBudget::class, $sennovaBudget]);

        $sennovaBudget->delete();

        return redirect()->route('resourceRoute.index')->with('success', 'The resource has been deleted successfully.');
    }
}
