<?php

namespace App\Http\Controllers\Evaluacion;

use App\Models\Evaluacion\TaEvaluacion;
use App\Http\Controllers\Controller;
use App\Http\Requests\Evaluacion\TaEvaluacionRequest;
use App\Models\Convocatoria;
use App\Models\DisCurricular;
use App\Models\Regional;
use App\Models\Tecnoacademia;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TaEvaluacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Convocatoria $convocatoria)
    {
        return Inertia::render('Convocatorias/Evaluaciones/Ta/Index', [
            'convocatoria'  => $convocatoria->only('id'),
            'filters'       => request()->all('search'),
            'ta'            => TaEvaluacion::getProyectosPorEvaluador($convocatoria)->appends(['search' => request()->search]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Convocatoria $convocatoria)
    {
        // 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaEvaluacionRequest $request)
    {
        // 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TaEvaluacion  $taEvaluacion
     * @return \Illuminate\Http\Response
     */
    public function show(Convocatoria $convocatoria, TaEvaluacion $taEvaluacion)
    {
        //   
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TaEvaluacion  $taEvaluacion
     * @return \Illuminate\Http\Response
     */
    public function edit(Convocatoria $convocatoria, TaEvaluacion $taEvaluacion)
    {
        $taEvaluacion->evaluacion->proyecto;
        $ta = $taEvaluacion->evaluacion->proyecto->ta;
        $ta->codigo_linea_programatica = $taEvaluacion->ta->proyecto->lineaProgramatica->codigo;
        $ta->precio_proyecto           = $taEvaluacion->ta->proyecto->precioProyecto;
        $taEvaluacion->ta->proyecto->centroFormacion;

        if (auth()->user()->hasRole(12)) {
            $tecnoAcademias = Tecnoacademia::selectRaw("tecnoacademias.id as value, CASE modalidad
                WHEN '1' THEN	concat(tecnoacademias.nombre, chr(10), '∙ Modalidad: itinerante', chr(10), '∙ Centro de formación: ', centros_formacion.nombre)
                WHEN '2' THEN	concat(tecnoacademias.nombre, chr(10), '∙ Modalidad: itinerante - vehículo', chr(10), '∙ Centro de formación: ', centros_formacion.nombre)
                WHEN '3' THEN	concat(tecnoacademias.nombre, chr(10), '∙ Modalidad: fija con extensión', chr(10), '∙ Centro de formación: ', centros_formacion.nombre)
            END as label, centros_formacion.id as centro_formacion_id")
                ->join('centros_formacion', 'tecnoacademias.centro_formacion_id', 'centros_formacion.id')
                ->where('tecnoacademias.centro_formacion_id', auth()->user()->centroFormacion->id)->get();
        } else {
            $tecnoAcademias = $tecnoAcademias = Tecnoacademia::selectRaw("tecnoacademias.id as value, CASE modalidad
                WHEN '1' THEN	concat(tecnoacademias.nombre, chr(10), '∙ Modalidad: itinerante', chr(10), '∙ Centro de formación: ', centros_formacion.nombre)
                WHEN '2' THEN	concat(tecnoacademias.nombre, chr(10), '∙ Modalidad: itinerante - vehículo', chr(10), '∙ Centro de formación: ', centros_formacion.nombre)
                WHEN '3' THEN	concat(tecnoacademias.nombre, chr(10), '∙ Modalidad: fija con extensión', chr(10), '∙ Centro de formación: ', centros_formacion.nombre)
            END as label, centros_formacion.id as centro_formacion_id")
                ->join('centros_formacion', 'tecnoacademias.centro_formacion_id', 'centros_formacion.id')
                ->get();
        }

        return Inertia::render('Convocatorias/Proyectos/Ta/Edit', [
            'convocatoria'                          => $convocatoria->only('id', 'min_fecha_inicio_proyectos_ta', 'max_fecha_finalizacion_proyectos_ta', 'fecha_maxima_ta'),
            'ta'                                    => $ta,
            'taEvaluacion'                          => $taEvaluacion,
            'tecnoacademiaRelacionada'              => $taEvaluacion->ta->proyecto->tecnoacademiaLineasTecnoacademia()->first() ? $taEvaluacion->ta->proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->id : null,
            'lineasTecnoacademiaRelacionadas'       => $taEvaluacion->ta->proyecto->tecnoacademiaLineasTecnoacademia()->select('tecnoacademia_linea_tecnoacademia.id as value', 'lineas_tecnoacademia.nombre')->join('lineas_tecnoacademia', 'tecnoacademia_linea_tecnoacademia.linea_tecnoacademia_id', 'lineas_tecnoacademia.id')->get(),
            'regionales'                            => Regional::select('id as value', 'nombre as label', 'codigo')->orderBy('nombre')->get(),
            'tecnoacademias'                        => TecnoAcademia::select('id as value', 'nombre as label')->get(),
            'proyectoMunicipios'                    => $taEvaluacion->ta->proyecto->municipios()->select('municipios.id as value', 'municipios.nombre as label', 'regionales.nombre as group', 'regionales.codigo')->join('regionales', 'regionales.id', 'municipios.regional_id')->get(),
            'proyectoMunicipiosImpactar'            => $taEvaluacion->ta->proyecto->municipiosAImpactar()->select('municipios.id as value', 'municipios.nombre as label', 'regionales.nombre as group', 'regionales.codigo')->join('regionales', 'regionales.id', 'municipios.regional_id')->get(),
            'proyectoProgramasFormacionArticulados' => $taEvaluacion->ta->proyecto->taProgramasFormacion()->selectRaw('id as value, concat(programas_formacion.nombre, chr(10), \'∙ Código: \', programas_formacion.codigo) as label')->get(),
            'proyectoDisCurriculares'               => $taEvaluacion->ta->proyecto->disCurriculares()->selectRaw('id as value, concat(nombre, \' ∙ Código: \', codigo) as label')->get(),
            'disCurriculares'                       => DisCurricular::selectRaw('id as value, concat(nombre, \' ∙ Código: \', codigo) as label')->get(),
            'tecnoAcademias'                        => $tecnoAcademias
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TaEvaluacion  $taEvaluacion
     * @return \Illuminate\Http\Response
     */
    public function update(TaEvaluacionRequest $request, Convocatoria $convocatoria, TaEvaluacion $taEvaluacion)
    {
        $taEvaluacion->fieldName = $request->fieldName;
        $taEvaluacion->fieldName = $request->fieldName;
        $taEvaluacion->fieldName = $request->fieldName;

        $taEvaluacion->save();

        return redirect()->back()->with('success', 'El recurso ha sido actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TaEvaluacion  $taEvaluacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Convocatoria $convocatoria, TaEvaluacion $taEvaluacion)
    {
        $taEvaluacion->delete();

        return redirect()->route('convocatorias.ta-evaluaciones.index')->with('error', 'El recurso se no se ha podido eliminar.');
    }
}
