<?php

namespace App\Http\Controllers;

use App\Http\Requests\CallRequest;
use App\Models\Call;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class CallController extends Controller
{
    /**
     * Display the dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard(Call $call)
    {
        $this->authorize('viewAny', [Call::class]);

        return Inertia::render('Calls/Dashboard', [
            'call' => $call
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', [Call::class]);

        return Inertia::render('Calls/Index', [
            'filters'   => request()->all('search'),
            'calls' => Call::orderBy('fecha_incio', 'DESC')
                ->filterCall(request()->only('search'))->paginate(),
            'activeCall' => Call::where('active', 1)->first(),

        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', [Call::class]);

        return Inertia::render('Calls/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CallRequest $request)
    {
        $this->authorize('create', [Call::class]);

        $call = new Call();
        $call->description          = $request->description;
        $call->fecha_incio           = $request->fecha_incio;
        $call->fecha_finalizacion             = $request->fecha_finalizacion;
        $call->project_fecha_incio   = $request->project_fecha_incio;
        $call->project_fecha_finalizacion     = $request->project_fecha_finalizacion;
        if ($request->active) {
            $oldCall = Call::where('active', true)->first();
            $oldCall->active = false;
            $oldCall->save();
        }
        $call->active               = $request->active;

        $call->save();

        return redirect()->route('calls.index')->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Call  $call
     * @return \Illuminate\Http\Response
     */
    public function show(Call $call)
    {
        $this->authorize('view', [Call::class, $call]);

        return Inertia::render('Calls/Show', [
            'call' => $call
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Call  $call
     * @return \Illuminate\Http\Response
     */
    public function edit(Call $call)
    {
        $this->authorize('update', [Call::class, $call]);

        return Inertia::render('Calls/Editar', [
            'call' => $call
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Call  $call
     * @return \Illuminate\Http\Response
     */
    public function update(CallRequest $request, Call $call)
    {
        $this->authorize('update', [Call::class, $call]);

        $call->description          = $request->description;
        $call->fecha_incio           = $request->fecha_incio;
        $call->fecha_finalizacion             = $request->fecha_finalizacion;
        $call->project_fecha_incio   = $request->project_fecha_incio;
        $call->project_fecha_finalizacion     = $request->project_fecha_finalizacion;
        if ($request->active) {
            $oldCall = Call::where('active', true)->first();
            $oldCall->active = false;
            $oldCall->save();
        }
        $call->active               = $request->active;

        $call->save();

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Call  $call
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Call $call)
    {
        $this->authorize('delete', [Call::class, $call]);
        if ( !Hash::check($request->password, Auth::user()->password) ) {
            return redirect()->back()
                ->withErrors(['password' => __('The password is incorrect.')]);
        }

        $call->delete();

        return redirect()->route('calls.index')->with('success', 'The resource has been deleted successfully.');
    }
}
