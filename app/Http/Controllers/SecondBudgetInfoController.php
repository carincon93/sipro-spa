<?php

namespace App\Http\Controllers;

use App\Http\Requests\SecondBudgetInfoRequest;
use App\Models\SecondBudgetInfo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SecondBudgetInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', [SecondBudgetInfo::class]);

        return Inertia::render('Budgets/SecondBudgetInfo/Index', [
            'filters'   => request()->all('search'),
            'secondBudgetInfo' => SecondBudgetInfo::orderBy('nombre', 'ASC')
                ->filterSecondBudgetInfo(request()->only('search'))->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', [SecondBudgetInfo::class]);

        return Inertia::render('Budgets/SecondBudgetInfo/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SecondBudgetInfoRequest $request)
    {
        $this->authorize('create', [SecondBudgetInfo::class]);

        $secondBudgetInfo = new SecondBudgetInfo();
        $secondBudgetInfo->name = $request->name;
        $secondBudgetInfo->code = $request->code;

        $secondBudgetInfo->save();

        return redirect()->route('second-budget-info.index')->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SecondBudgetInfo  $secondBudgetInfo
     * @return \Illuminate\Http\Response
     */
    public function show(SecondBudgetInfo $secondBudgetInfo)
    {
        $this->authorize('view', [SecondBudgetInfo::class, $secondBudgetInfo]);

        return Inertia::render('Budgets/SecondBudgetInfo/Show', [
            'secondBudgetInfo' => $secondBudgetInfo
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SecondBudgetInfo  $secondBudgetInfo
     * @return \Illuminate\Http\Response
     */
    public function edit(SecondBudgetInfo $secondBudgetInfo)
    {
        $this->authorize('update', [SecondBudgetInfo::class, $secondBudgetInfo]);

        return Inertia::render('Budgets/SecondBudgetInfo/Edit', [
            'secondBudgetInfo' => $secondBudgetInfo
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SecondBudgetInfo  $secondBudgetInfo
     * @return \Illuminate\Http\Response
     */
    public function update(SecondBudgetInfoRequest $request, SecondBudgetInfo $secondBudgetInfo)
    {
        $this->authorize('update', [SecondBudgetInfo::class, $secondBudgetInfo]);

        $secondBudgetInfo->name = $request->name;
        $secondBudgetInfo->code = $request->code;

        $secondBudgetInfo->save();

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SecondBudgetInfo  $secondBudgetInfo
     * @return \Illuminate\Http\Response
     */
    public function destroy(SecondBudgetInfo $secondBudgetInfo)
    {
        $this->authorize('delete', [SecondBudgetInfo::class, $secondBudgetInfo]);

        $secondBudgetInfo->delete();

        return redirect()->route('second-budget-info.index')->with('success', 'The resource has been deleted successfully.');
    }
}
