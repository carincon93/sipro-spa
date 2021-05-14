<?php

namespace App\Http\Controllers;

use App\Http\Requests\CallSennovaRoleRequest;
use App\Models\Call;
use App\Models\CallSennovaRole;
use App\Models\SennovaRole;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CallSennovaRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Call $call)
    {
        $this->authorize('viewAny', [CallSennovaRole::class]);

        return Inertia::render('Calls/CallSennovaRoles/Index', [
            'filters'   => request()->all('search'),
            'callSennovaRoles' =>
                $call->callSennovaRoles()
                ->selectRaw("call_sennova_roles.id, call_sennova_roles.salary, call_sennova_roles.qty_months, CASE call_sennova_roles.academic_degree
                        WHEN '0' THEN	concat(sennova_roles.name, ' - Nivel académico: Ninguno')
                        WHEN '1' THEN	concat(sennova_roles.name, ' - Nivel académico: Técnico')
                        WHEN '2' THEN	concat(sennova_roles.name, ' - Nivel académico: Tecnólogo')
                        WHEN '3' THEN	concat(sennova_roles.name, ' - Nivel académico: Pregrado')
                        WHEN '4' THEN	concat(sennova_roles.name, ' - Nivel académico: Especalización')
                        WHEN '5' THEN	concat(sennova_roles.name, ' - Nivel académico: Maestría')
                        WHEN '6' THEN	concat(sennova_roles.name, ' - Nivel académico: Doctorado')
                    END as name")->join('sennova_roles', 'call_sennova_roles.sennova_role_id', 'sennova_roles.id')
                    ->filterCallSennovaRole(request()->only('search'))->paginate(),
            'call' => $call,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Call $call)
    {
        $this->authorize('create', [CallSennovaRole::class]);

        return Inertia::render('Calls/CallSennovaRoles/Create', [
            'call' => $call,
            'sennovaRoles'      => CallSennovaRole::selectRaw("id as value, CASE academic_degree
                WHEN '0' THEN	concat(sennova_roles.name, ' - Nivel académico: Ninguno')
                WHEN '1' THEN	concat(sennova_roles.name, ' - Nivel académico: Técnico')
                WHEN '2' THEN	concat(sennova_roles.name, ' - Nivel académico: Tecnólogo')
                WHEN '3' THEN	concat(sennova_roles.name, ' - Nivel académico: Pregrado')
                WHEN '4' THEN	concat(sennova_roles.name, ' - Nivel académico: Especalización')
                WHEN '5' THEN	concat(sennova_roles.name, ' - Nivel académico: Maestría')
                WHEN '6' THEN	concat(sennova_roles.name, ' - Nivel académico: Doctorado')
            END as label")
            ->join('sennova_roles', 'call_sennova_roles.sennova_role_id', 'sennova_roles.id')
            ->orderBy('sennova_roles.name', 'ASC')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CallSennovaRoleRequest $request, Call $call)
    {
        $this->authorize('create', [CallSennovaRole::class]);

        $callSennovaRole = new CallSennovaRole();
        $callSennovaRole->salary            = $request->salary;
        $callSennovaRole->qty_months        = $request->qty_months;
        $callSennovaRole->qty_roles         = $request->qty_months;
        $callSennovaRole->academic_degree   = $request->academic_degree;
        $callSennovaRole->call()->associate($call);
        $callSennovaRole->sennovaRole()->associate($request->sennova_role_id);
        $callSennovaRole->programmaticLine()->associate($request->programmatic_line_id);

        $callSennovaRole->save();

        return redirect()->route('calls.call-sennova-roles.index', [$call])->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CallSennovaRole  $callSennovaRole
     * @return \Illuminate\Http\Response
     */
    public function show(Call $call, CallSennovaRole $callSennovaRole)
    {
        $this->authorize('view', [CallSennovaRole::class, $callSennovaRole]);

        return Inertia::render('Calls/CallSennovaRoles/Show', [
            'callSennovaRole' => $callSennovaRole
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CallSennovaRole  $callSennovaRole
     * @return \Illuminate\Http\Response
     */
    public function edit(Call $call, CallSennovaRole $callSennovaRole)
    {
        $this->authorize('update', [CallSennovaRole::class, $callSennovaRole]);

        $callSennovaRole->sennovaRole;

        return Inertia::render('Calls/CallSennovaRoles/Editar', [
            'callSennovaRole'   => $callSennovaRole,
            'call'              => $call,
            'sennovaRoles'      => CallSennovaRole::selectRaw("id as value, CASE academic_degree
                WHEN '0' THEN	concat(sennova_roles.name, ' - Nivel académico: Ninguno')
                WHEN '1' THEN	concat(sennova_roles.name, ' - Nivel académico: Técnico')
                WHEN '2' THEN	concat(sennova_roles.name, ' - Nivel académico: Tecnólogo')
                WHEN '3' THEN	concat(sennova_roles.name, ' - Nivel académico: Pregrado')
                WHEN '4' THEN	concat(sennova_roles.name, ' - Nivel académico: Especalización')
                WHEN '5' THEN	concat(sennova_roles.name, ' - Nivel académico: Maestría')
                WHEN '6' THEN	concat(sennova_roles.name, ' - Nivel académico: Doctorado')
            END as label")
            ->join('sennova_roles', 'call_sennova_roles.sennova_role_id', 'sennova_roles.id')
            ->orderBy('sennova_roles.name', 'ASC')->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CallSennovaRole  $callSennovaRole
     * @return \Illuminate\Http\Response
     */
    public function update(CallSennovaRoleRequest $request, Call $call, CallSennovaRole $callSennovaRole)
    {
        $this->authorize('update', [CallSennovaRole::class, $callSennovaRole]);

        $callSennovaRole->salary            = $request->salary;
        $callSennovaRole->qty_months        = $request->qty_months;
        $callSennovaRole->qty_roles         = $request->qty_roles;
        $callSennovaRole->academic_degree   = $request->academic_degree;
        $callSennovaRole->call()->associate($call);
        $callSennovaRole->sennovaRole()->associate($request->sennova_role_id);
        $callSennovaRole->programmaticLine()->associate($request->programmatic_line_id);

        $callSennovaRole->save();

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CallSennovaRole  $callSennovaRole
     * @return \Illuminate\Http\Response
     */
    public function destroy(Call $call, CallSennovaRole $callSennovaRole)
    {
        $this->authorize('delete', [CallSennovaRole::class, $callSennovaRole]);

        $callSennovaRole->delete();

        return redirect()->route('calls.call-sennova-roles.index', [$call])->with('success', 'The resource has been deleted successfully.');
    }
}
