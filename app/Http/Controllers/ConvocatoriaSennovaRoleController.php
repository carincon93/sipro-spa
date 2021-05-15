<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConvocatoriaSennovaRoleRequest;
use App\Models\Convocatoria;
use App\Models\ConvocatoriaSennovaRole;
use App\Models\SennovaRole;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ConvocatoriaSennovaRoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Convocatoria $convocatoria)
    {
        $this->authorize('viewAny', [ConvocatoriaSennovaRole::class]);

        return Inertia::render('Convocatorias/ConvocatoriaSennovaRoles/Index', [
            'filters'   => request()->all('search'),
            'convocatoriaSennovaRoles' =>
                $convocatoria->convocatoriaSennovaRoles()
                ->selectRaw("convocatoria_sennova_roles.id, convocatoria_sennova_roles.salary, convocatoria_sennova_roles.qty_months, CASE convocatoria_sennova_roles.academic_degree
                        WHEN '0' THEN	concat(sennova_roles.name, ' - Nivel académico: Ninguno')
                        WHEN '1' THEN	concat(sennova_roles.name, ' - Nivel académico: Técnico')
                        WHEN '2' THEN	concat(sennova_roles.name, ' - Nivel académico: Tecnólogo')
                        WHEN '3' THEN	concat(sennova_roles.name, ' - Nivel académico: Pregrado')
                        WHEN '4' THEN	concat(sennova_roles.name, ' - Nivel académico: Especalización')
                        WHEN '5' THEN	concat(sennova_roles.name, ' - Nivel académico: Maestría')
                        WHEN '6' THEN	concat(sennova_roles.name, ' - Nivel académico: Doctorado')
                    END as name")->join('sennova_roles', 'convocatoria_sennova_roles.sennova_role_id', 'sennova_roles.id')
                    ->filterConvocatoriaSennovaRole(request()->only('search'))->paginate(),
            'convocatoria' => $convocatoria,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Convocatoria $convocatoria)
    {
        $this->authorize('create', [ConvocatoriaSennovaRole::class]);

        return Inertia::render('Convocatorias/ConvocatoriaSennovaRoles/Create', [
            'convocatoria' => $convocatoria,
            'sennovaRoles'      => ConvocatoriaSennovaRole::selectRaw("id as value, CASE academic_degree
                WHEN '0' THEN	concat(sennova_roles.name, ' - Nivel académico: Ninguno')
                WHEN '1' THEN	concat(sennova_roles.name, ' - Nivel académico: Técnico')
                WHEN '2' THEN	concat(sennova_roles.name, ' - Nivel académico: Tecnólogo')
                WHEN '3' THEN	concat(sennova_roles.name, ' - Nivel académico: Pregrado')
                WHEN '4' THEN	concat(sennova_roles.name, ' - Nivel académico: Especalización')
                WHEN '5' THEN	concat(sennova_roles.name, ' - Nivel académico: Maestría')
                WHEN '6' THEN	concat(sennova_roles.name, ' - Nivel académico: Doctorado')
            END as label")
            ->join('sennova_roles', 'convocatoria_sennova_roles.sennova_role_id', 'sennova_roles.id')
            ->orderBy('sennova_roles.name', 'ASC')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ConvocatoriaSennovaRoleRequest $request, Convocatoria $convocatoria)
    {
        $this->authorize('create', [ConvocatoriaSennovaRole::class]);

        $convocatoriaSennovaRole = new ConvocatoriaSennovaRole();
        $convocatoriaSennovaRole->salary            = $request->salary;
        $convocatoriaSennovaRole->qty_months        = $request->qty_months;
        $convocatoriaSennovaRole->qty_roles         = $request->qty_months;
        $convocatoriaSennovaRole->academic_degree   = $request->academic_degree;
        $convocatoriaSennovaRole->convocatoria()->associate($convocatoria);
        $convocatoriaSennovaRole->sennovaRole()->associate($request->sennova_role_id);
        $convocatoriaSennovaRole->programmaticLine()->associate($request->linea_programatica_id);

        $convocatoriaSennovaRole->save();

        return redirect()->route('convocatorias.convocatoria-sennova-roles.index', [$convocatoria])->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ConvocatoriaSennovaRole  $convocatoriaSennovaRole
     * @return \Illuminate\Http\Response
     */
    public function show(Convocatoria $convocatoria, ConvocatoriaSennovaRole $convocatoriaSennovaRole)
    {
        $this->authorize('view', [ConvocatoriaSennovaRole::class, $convocatoriaSennovaRole]);

        return Inertia::render('Convocatorias/ConvocatoriaSennovaRoles/Show', [
            'convocatoriaSennovaRole' => $convocatoriaSennovaRole
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ConvocatoriaSennovaRole  $convocatoriaSennovaRole
     * @return \Illuminate\Http\Response
     */
    public function edit(Convocatoria $convocatoria, ConvocatoriaSennovaRole $convocatoriaSennovaRole)
    {
        $this->authorize('update', [ConvocatoriaSennovaRole::class, $convocatoriaSennovaRole]);

        $convocatoriaSennovaRole->sennovaRole;

        return Inertia::render('Convocatorias/ConvocatoriaSennovaRoles/Editar', [
            'convocatoriaSennovaRole'   => $convocatoriaSennovaRole,
            'convocatoria'              => $convocatoria,
            'sennovaRoles'      => ConvocatoriaSennovaRole::selectRaw("id as value, CASE academic_degree
                WHEN '0' THEN	concat(sennova_roles.name, ' - Nivel académico: Ninguno')
                WHEN '1' THEN	concat(sennova_roles.name, ' - Nivel académico: Técnico')
                WHEN '2' THEN	concat(sennova_roles.name, ' - Nivel académico: Tecnólogo')
                WHEN '3' THEN	concat(sennova_roles.name, ' - Nivel académico: Pregrado')
                WHEN '4' THEN	concat(sennova_roles.name, ' - Nivel académico: Especalización')
                WHEN '5' THEN	concat(sennova_roles.name, ' - Nivel académico: Maestría')
                WHEN '6' THEN	concat(sennova_roles.name, ' - Nivel académico: Doctorado')
            END as label")
            ->join('sennova_roles', 'convocatoria_sennova_roles.sennova_role_id', 'sennova_roles.id')
            ->orderBy('sennova_roles.name', 'ASC')->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ConvocatoriaSennovaRole  $convocatoriaSennovaRole
     * @return \Illuminate\Http\Response
     */
    public function update(ConvocatoriaSennovaRoleRequest $request, Convocatoria $convocatoria, ConvocatoriaSennovaRole $convocatoriaSennovaRole)
    {
        $this->authorize('update', [ConvocatoriaSennovaRole::class, $convocatoriaSennovaRole]);

        $convocatoriaSennovaRole->salary            = $request->salary;
        $convocatoriaSennovaRole->qty_months        = $request->qty_months;
        $convocatoriaSennovaRole->qty_roles         = $request->qty_roles;
        $convocatoriaSennovaRole->academic_degree   = $request->academic_degree;
        $convocatoriaSennovaRole->convocatoria()->associate($convocatoria);
        $convocatoriaSennovaRole->sennovaRole()->associate($request->sennova_role_id);
        $convocatoriaSennovaRole->programmaticLine()->associate($request->linea_programatica_id);

        $convocatoriaSennovaRole->save();

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ConvocatoriaSennovaRole  $convocatoriaSennovaRole
     * @return \Illuminate\Http\Response
     */
    public function destroy(Convocatoria $convocatoria, ConvocatoriaSennovaRole $convocatoriaSennovaRole)
    {
        $this->authorize('delete', [ConvocatoriaSennovaRole::class, $convocatoriaSennovaRole]);

        $convocatoriaSennovaRole->delete();

        return redirect()->route('convocatorias.convocatoria-sennova-roles.index', [$convocatoria])->with('success', 'The resource has been deleted successfully.');
    }
}
