<?php

namespace App\Http\Controllers;

use App\Http\Requests\ThirdBudgetInfoRequest;
use App\Models\ThirdBudgetInfo;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ThirdBudgetInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', [ThirdBudgetInfo::class]);

        return Inertia::render('Budgets/ThirdBudgetInfo/Index', [
            'filters'   => request()->all('search'),
            'thirdBudgetInfo' => ThirdBudgetInfo::orderBy('nombre', 'ASC')
                ->filterThirdBudgetInfo(request()->only('search'))->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', [ThirdBudgetInfo::class]);

        return Inertia::render('Budgets/ThirdBudgetInfo/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ThirdBudgetInfoRequest $request)
    {
        $this->authorize('create', [ThirdBudgetInfo::class]);

        $thirdBudgetInfo = new ThirdBudgetInfo();
        $thirdBudgetInfo->name = $request->name;
        $thirdBudgetInfo->code = $request->code;

        $thirdBudgetInfo->save();

        return redirect()->route('third-budget-info.index')->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ThirdBudgetInfo  $thirdBudgetInfo
     * @return \Illuminate\Http\Response
     */
    public function show(ThirdBudgetInfo $thirdBudgetInfo)
    {
        $this->authorize('view', [ThirdBudgetInfo::class, $thirdBudgetInfo]);

        return Inertia::render('Budgets/ThirdBudgetInfo/Show', [
            'thirdBudgetInfo' => $thirdBudgetInfo
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ThirdBudgetInfo  $thirdBudgetInfo
     * @return \Illuminate\Http\Response
     */
    public function edit(ThirdBudgetInfo $thirdBudgetInfo)
    {
        $this->authorize('update', [ThirdBudgetInfo::class, $thirdBudgetInfo]);

        return Inertia::render('Budgets/ThirdBudgetInfo/Editar', [
            'thirdBudgetInfo' => $thirdBudgetInfo
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ThirdBudgetInfo  $thirdBudgetInfo
     * @return \Illuminate\Http\Response
     */
    public function update(ThirdBudgetInfoRequest $request, ThirdBudgetInfo $thirdBudgetInfo)
    {
        $this->authorize('update', [ThirdBudgetInfo::class, $thirdBudgetInfo]);

        $thirdBudgetInfo->name = $request->name;
        $thirdBudgetInfo->code = $request->code;

        $thirdBudgetInfo->save();

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ThirdBudgetInfo  $thirdBudgetInfo
     * @return \Illuminate\Http\Response
     */
    public function destroy(ThirdBudgetInfo $thirdBudgetInfo)
    {
        $this->authorize('delete', [ThirdBudgetInfo::class, $thirdBudgetInfo]);

        $thirdBudgetInfo->delete();

        return redirect()->route('third-budget-info.index')->with('success', 'The resource has been deleted successfully.');
    }
}
