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
            'convocatoria'  => $convocatoria->only('id', 'fase_formateada'),
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
        $this->authorize('visualizar-evaluacion-autor', $taEvaluacion->evaluacion);

        $taEvaluacion->evaluacion->proyecto;
        $ta = $taEvaluacion->evaluacion->proyecto->ta;
        $ta->proyecto->codigo_linea_programatica = $ta->proyecto->lineaProgramatica->codigo;
        $ta->proyecto->precio_proyecto           = $ta->proyecto->precioProyecto;
        $ta->proyecto->centroFormacion;

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

        return Inertia::render('Convocatorias/Evaluaciones/Ta/Edit', [
            'convocatoria'                              => $convocatoria->only('id', 'fase_formateada', 'min_fecha_inicio_proyectos_ta', 'max_fecha_finalizacion_proyectos_ta', 'fecha_maxima_ta'),
            'ta'                                        => $ta,
            'taEvaluacion'                              => $taEvaluacion,
            'idiSegundaEvaluacion'                      => TaEvaluacion::whereHas('evaluacion', function ($query) use ($ta) {
                $query->where('evaluaciones.proyecto_id', $ta->id)->where('evaluaciones.habilitado', true);
            })->where('ta_evaluaciones.id', '!=', $taEvaluacion->id)->first(),
            'tecnoacademiaRelacionada'                  => $ta->proyecto->tecnoacademiaLineasTecnoacademia()->first() ? $ta->proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia : null,
            'lineasTecnoacademiaRelacionadas'           => $ta->proyecto->tecnoacademiaLineasTecnoacademia()->select('tecnoacademia_linea_tecnoacademia.id as value', 'lineas_tecnoacademia.nombre')->join('lineas_tecnoacademia', 'tecnoacademia_linea_tecnoacademia.linea_tecnoacademia_id', 'lineas_tecnoacademia.id')->get(),
            'regionales'                                => Regional::select('id as value', 'nombre as label', 'codigo')->orderBy('nombre')->get(),
            'tecnoacademias'                            => TecnoAcademia::select('id as value', 'nombre as label')->get(),
            'proyectoMunicipios'                        => $ta->proyecto->municipios()->select('municipios.id as value', 'municipios.nombre as label', 'regionales.nombre as group', 'regionales.codigo')->join('regionales', 'regionales.id', 'municipios.regional_id')->get(),
            'proyectoMunicipiosImpactar'                => $ta->proyecto->municipiosAImpactar()->select('municipios.id as value', 'municipios.nombre as label', 'regionales.nombre as group', 'regionales.codigo')->join('regionales', 'regionales.id', 'municipios.regional_id')->get(),
            'proyectoProgramasFormacionArticulados'     => $ta->proyecto->taProgramasFormacion()->selectRaw('id as value, concat(programas_formacion.nombre, chr(10), \'∙ Código: \', programas_formacion.codigo) as label')->get(),
            'proyectoDisCurriculares'                   => $ta->proyecto->disCurriculares()->selectRaw('id as value, concat(nombre, \' ∙ Código: \', codigo) as label')->get(),
            'disCurriculares'                           => DisCurricular::selectRaw('id as value, concat(nombre, \' ∙ Código: \', codigo) as label')->get(),
            'tecnoAcademias'                            => $tecnoAcademias
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
        $this->authorize('modificar-evaluacion-autor', $taEvaluacion->evaluacion);

        $taEvaluacion->evaluacion()->update([
            'iniciado' => true,
            'clausula_confidencialidad' => $request->clausula_confidencialidad
        ]);

        $taEvaluacion->resumen_regional_comentario              = $request->resumen_regional_requiere_comentario == false ? $request->resumen_regional_comentario : null;
        $taEvaluacion->antecedentes_tecnoacademia_comentario    = $request->antecedentes_tecnoacademia_requiere_comentario == false ? $request->antecedentes_tecnoacademia_comentario : null;
        $taEvaluacion->retos_oportunidades_comentario           = $request->retos_oportunidades_requiere_comentario == false ? $request->retos_oportunidades_comentario : null;
        $taEvaluacion->lineas_medulares_centro_comentario       = $request->lineas_medulares_centro_requiere_comentario == false ? $request->lineas_medulares_centro_comentario : null;
        $taEvaluacion->lineas_tecnologicas_centro_comentario    = $request->lineas_tecnologicas_centro_requiere_comentario == false ? $request->lineas_tecnologicas_centro_comentario : null;
        $taEvaluacion->municipios_comentario                    = $request->municipios_requiere_comentario == false ? $request->municipios_comentario : null;
        $taEvaluacion->instituciones_comentario                 = $request->instituciones_requiere_comentario == false ? $request->instituciones_comentario : null;
        $taEvaluacion->fecha_ejecucion_comentario               = $request->fecha_ejecucion_requiere_comentario == false ? $request->fecha_ejecucion_comentario : null;
        $taEvaluacion->proyectos_macro_comentario               = $request->proyectos_macro_requiere_comentario == false ? $request->proyectos_macro_comentario : null;
        $taEvaluacion->bibliografia_comentario                  = $request->bibliografia_requiere_comentario == false ? $request->bibliografia_comentario : null;
        $taEvaluacion->articulacion_centro_formacion_comentario = $request->articulacion_centro_formacion_requiere_comentario == false ? $request->articulacion_centro_formacion_comentario : null;

        $taEvaluacion->ortografia_comentario                    = $request->ortografia_requiere_comentario == false ? $request->ortografia_comentario : null;
        $taEvaluacion->redaccion_comentario                     = $request->redaccion_requiere_comentario == false ? $request->redaccion_comentario : null;
        $taEvaluacion->normas_apa_comentario                    = $request->normas_apa_requiere_comentario == false ? $request->normas_apa_comentario : null;

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
        $this->authorize('modificar-evaluacion-autor', $taEvaluacion->evaluacion);

        return redirect()->route('convocatorias.ta-evaluaciones.index', [$convocatoria])->with('error', 'El recurso se no se ha podido eliminar.');
    }
}
