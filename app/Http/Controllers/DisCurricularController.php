<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Convocatoria;
use App\Models\DisCurricular;
use App\Models\Proyecto;
use Illuminate\Http\Request;

class DisCurricularController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function storeDisCurricular(Request $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $disCurricular = new DisCurricular();
        $disCurricular->nombre = $request->nombre;
        $disCurricular->codigo = 0;

        $disCurricular->save();

        $proyecto->disCurriculares()->attach($disCurricular);

        return back()->with('success', 'El recurso se ha creado correctamente.');
    }
}
