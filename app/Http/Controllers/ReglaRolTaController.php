<?php

namespace App\Http\Controllers;

use App\Models\ReglaRolTa;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReglaRolTaRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReglaRolTaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', [ReglaRolTa::class]);

        return Inertia::render('ReglasRolesTa/Index', [
            'filters'       => request()->all('search'),
            'reglasRolesTa' => ReglaRolTa::select('reglas_roles_ta.id', 'roles_sennova.nombre as nombre_rol', 'tecnoacademias.nombre as nombre_tecnoacademia')->join('roles_sennova', 'reglas_roles_ta.rol_sennova_id', 'roles_sennova.id')->join('tecnoacademias', 'reglas_roles_ta.tecnoacademia_id', 'tecnoacademias.id')->orderBy('roles_sennova.nombre', 'ASC')
                ->filterReglaRolTa(request()->only('search'))->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', [ReglaRolTa::class]);

        return Inertia::render('ReglasRolesTa/Create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReglaRolTaRequest $request)
    {
        $this->authorize('create', [ReglaRolTa::class]);

        $reglaRolTa = new ReglaRolTa();
        $reglaRolTa->maximo = $request->maximo;
        $reglaRolTa->tecnoacademia()->associate($request->tecnoacademia_id);
        $reglaRolTa->rolSennova()->associate($request->rol_sennova_id);

        $reglaRolTa->save();

        return redirect()->route('resourceRoute.index')->with('success', 'El recurso se ha creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ReglaRolTa  $reglaRolTa
     * @return \Illuminate\Http\Response
     */
    public function show(ReglaRolTa $reglaRolTa)
    {
        $this->authorize('view', [ReglaRolTa::class, $reglaRolTa]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ReglaRolTa  $reglaRolTa
     * @return \Illuminate\Http\Response
     */
    public function edit(ReglaRolTa $reglaRolTa)
    {
        $this->authorize('update', [ReglaRolTa::class, $reglaRolTa]);

        return Inertia::render('ReglasRolesTa/Edit', [
            'reglasRolesTa' => $reglaRolTa
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ReglaRolTa  $reglaRolTa
     * @return \Illuminate\Http\Response
     */
    public function update(ReglaRolTaRequest $request, ReglaRolTa $reglaRolTa)
    {
        $this->authorize('update', [ReglaRolTa::class, $reglaRolTa]);

        $reglaRolTa->maximo = $request->maximo;
        $reglaRolTa->tecnoacademia()->associate($request->tecnoacademia_id);
        $reglaRolTa->rolSennova()->associate($request->rol_sennova_id);

        $reglaRolTa->save();

        return redirect()->back()->with('success', 'El recurso se ha modificado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ReglaRolTa  $reglaRolTa
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReglaRolTa $reglaRolTa)
    {
        $this->authorize('delete', [ReglaRolTa::class, $reglaRolTa]);

        $reglaRolTa->delete();

        return redirect()->route('resourceRoute.index')->with('success', 'El recurso se ha eliminado correctamente.');
    }
}
