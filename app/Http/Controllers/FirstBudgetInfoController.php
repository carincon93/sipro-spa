<?php

namespace App\Http\Controllers;

use App\Http\Requests\FirstBudgetInfoRequest;
use App\Models\FirstBudgetInfo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FirstBudgetInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', [FirstBudgetInfo::class]);

        return Inertia::render('Budgets/FirstBudgetInfo/Index', [
            'filters'   => request()->all('search'),
            'Budgets/firstBudgetInfo' => FirstBudgetInfo::orderBy('nombre', 'ASC')
                ->filterFirstBudgetInfo(request()->only('search'))->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', [FirstBudgetInfo::class]);

        return Inertia::render('Budgets/FirstBudgetInfo/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FirstBudgetInfoRequest $request)
    {
        $this->authorize('create', [FirstBudgetInfo::class]);

        $firstBudgetInfo = new FirstBudgetInfo();
        $firstBudgetInfo->description   = $request->description;
        $firstBudgetInfo->code          = $request->code;
        $firstBudgetInfo->cta           = $request->cta;
        $firstBudgetInfo->bpin          = $request->bpin;

        $firstBudgetInfo->save();

        return redirect()->route('first-budget-info.index')->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FirstBudgetInfo  $firstBudgetInfo
     * @return \Illuminate\Http\Response
     */
    public function show(FirstBudgetInfo $firstBudgetInfo)
    {
        $this->authorize('view', [FirstBudgetInfo::class, $firstBudgetInfo]);

        return Inertia::render('Budgets/FirstBudgetInfo/Show', [
            'firstBudgetInfo' => $firstBudgetInfo
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FirstBudgetInfo  $firstBudgetInfo
     * @return \Illuminate\Http\Response
     */
    public function edit(FirstBudgetInfo $firstBudgetInfo)
    {
        $this->authorize('update', [FirstBudgetInfo::class, $firstBudgetInfo]);

        return Inertia::render('Budgets/FirstBudgetInfo/Editar', [
            'firstBudgetInfo' => $firstBudgetInfo
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FirstBudgetInfo  $firstBudgetInfo
     * @return \Illuminate\Http\Response
     */
    public function update(FirstBudgetInfoRequest $request, FirstBudgetInfo $firstBudgetInfo)
    {
        $this->authorize('update', [FirstBudgetInfo::class, $firstBudgetInfo]);

        $firstBudgetInfo->description   = $request->description;
        $firstBudgetInfo->code          = $request->code;
        $firstBudgetInfo->cta           = $request->cta;
        $firstBudgetInfo->bpin          = $request->bpin;

        $firstBudgetInfo->save();

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FirstBudgetInfo  $firstBudgetInfo
     * @return \Illuminate\Http\Response
     */
    public function destroy(FirstBudgetInfo $firstBudgetInfo)
    {
        $this->authorize('delete', [FirstBudgetInfo::class, $firstBudgetInfo]);

        $firstBudgetInfo->delete();

        return redirect()->route('first-budget-info.index')->with('success', 'The resource has been deleted successfully.');
    }
}
