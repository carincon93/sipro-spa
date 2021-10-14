<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductoRequest;
use App\Models\Actividad;
use App\Models\Convocatoria;
use App\Models\Evaluacion\CulturaInnovacionEvaluacion;
use App\Models\Evaluacion\Evaluacion;
use App\Models\Evaluacion\IdiEvaluacion;
use App\Models\Evaluacion\ServicioTecnologicoEvaluacion;
use App\Models\Evaluacion\TaEvaluacion;
use App\Models\Evaluacion\TpEvaluacion;
use App\Models\Proyecto;
use App\Models\Producto;
use App\Models\ProductoCulturaInnovacion;
use App\Models\ProductoIdi;
use App\Models\ProductoServicioTecnologico;
use App\Models\ProductoTaTp;
use App\Models\Resultado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Convocatoria $convocatoria, Proyecto $proyecto, Request $request)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);

        $proyecto->load('evaluaciones.idiEvaluacion');

        if ($proyecto->ta()->exists()) {
            foreach ($proyecto->efectosDirectos as $efectoDirecto) {
                foreach ($efectoDirecto->resultados as $resultado) {
                    foreach ($resultado->productos as $producto) {
                        $productoAprendices = $producto->where('resultado_id', $resultado->id)->where('nombre', 'like', '%aprendices matriculados de acuerdo con la meta establecida para el 2022.%')->first();

                        if ($productoAprendices) {
                            $productoAprendices->productoTaTp()->update(['valor_proyectado' => $proyecto->meta_aprendices . ' aprendices']);
                        }
                    }
                }
            }
        }

        $resultado = $proyecto->efectosDirectos()->with('resultados')->get()->pluck('resultados')->flatten()->filter();
        $proyecto->codigo_linea_programatica = $proyecto->lineaProgramatica->codigo;

        $validacionResultados = null;
        $cantidadActividades = $proyecto->causasDirectas->map(function ($causaDirecta) {
            return $causaDirecta->causasIndirectas->map(function ($causasIndirecta) {
                return $causasIndirecta->actividad;
            });
        })->flatten()->count();

        $cantidadResultados = $proyecto->efectosDirectos()->whereHas('resultados', function ($query) {
            $query->where('descripcion', '!=', null);
        })->with('resultados:id as value,descripcion as label,efecto_directo_id')->get()->pluck('resultados')->count();

        if ($cantidadActividades == 0 && $cantidadResultados == 0) {
            $validacionResultados = 'Para poder crear productos debe primero generar los resultados y/o actividades en el \'Árbol de objetivos\'';
        }

        return Inertia::render('Convocatorias/Proyectos/Productos/Index', [
            'convocatoria'          => $convocatoria->only('id', 'fase_formateada', 'fase', 'mostrar_recomendaciones'),
            'proyecto'              => $proyecto->only('id', 'codigo_linea_programatica', 'precio_proyecto', 'modificable', 'en_subsanacion', 'evaluaciones', 'mostrar_recomendaciones'),
            'filters'               => request()->all('search'),
            'validacionResultados'  => $validacionResultados,
            'productos'             => Producto::whereIn(
                'resultado_id',
                $resultado->map(function ($resultado) {
                    return $resultado->id;
                })
            )->with('resultado.objetivoEspecifico')->orderBy('resultado_id', 'ASC')
                ->filterProducto(request()->only('search'))->paginate()->appends(['search' => request()->search]),
            'productosGantt'        => Producto::whereIn(
                'resultado_id',
                $resultado->map(function ($resultado) {
                    return $resultado->id;
                })
            )->orderBy('fecha_inicio', 'ASC')->get(),
            'to_pdf'          => ($request->to_pdf == 1) ? true : false
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);

        $proyecto->idi;
        $proyecto->culturaInnovacion;
        $proyecto->ta;
        $proyecto->tp;
        $proyecto->servicioTecnologico;

        $proyectoId = $proyecto->id;

        return Inertia::render('Convocatorias/Proyectos/Productos/Create', [
            'convocatoria'      => $convocatoria->only('id', 'fase_formateada', 'fase', 'min_fecha_inicio_proyectos', 'max_fecha_finalizacion_proyectos'),
            'proyecto'          => $proyecto,
            'resultados'        => Resultado::select('resultados.id as value', 'resultados.descripcion as label', 'resultados.id as id')->whereHas('efectoDirecto', function ($query) use ($proyectoId) {
                $query->where('efectos_directos.proyecto_id', $proyectoId);
            })->where('resultados.descripcion', '!=', null)->with('actividades')->get(),
            'tiposProducto'     => json_decode(Storage::get('json/tipos-producto.json'), true),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductoRequest $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        $producto = new Producto();
        $producto->nombre               = $request->nombre;
        $producto->fecha_inicio         = $request->fecha_inicio;
        $producto->fecha_finalizacion   = $request->fecha_finalizacion;
        $producto->indicador            = $request->indicador;
        $producto->resultado()->associate($request->resultado_id);
        $producto->save();

        $producto->actividades()->attach($request->actividad_id);

        // Valida si es un producto de I+D+i
        if ($proyecto->idi()->exists()) {
            $request->validate([
                'tipo'                          => ['required', 'between:1,4'],
                'subtipologia_minciencias_id'   => 'required|min:0|max:2147483647|integer|exists:subtipologias_minciencias,id'
            ]);

            $productoIdi = new ProductoIdi();
            $productoIdi->tipo = $request->tipo['value'];
            $productoIdi->subtipologiaMinciencias()->associate($request->subtipologia_minciencias_id);
            $producto->productoIdi()->save($productoIdi);
        }

        // Valida si es un producto de cultura innovación
        if ($proyecto->culturaInnovacion()->exists()) {
            $request->validate([
                'tipo'                          => ['required', 'between:1,4'],
                'subtipologia_minciencias_id'   => 'required|min:0|max:2147483647|integer|exists:subtipologias_minciencias,id'
            ]);

            $productoIdi = new ProductoCulturaInnovacion();
            $productoIdi->tipo = $request->tipo['value'];
            $productoIdi->subtipologiaMinciencias()->associate($request->subtipologia_minciencias_id);
            $producto->productoCulturaInnovacion()->save($productoIdi);
        }

        // Valida si es un producto de TaTp
        if ($proyecto->ta()->exists() || $proyecto->tp()->exists()) {
            $request->validate([
                'medio_verificacion' => 'required|string',
                'valor_proyectado'   => 'required|string',
            ]);
            $productoTaTp = new ProductoTaTp();
            $productoTaTp->producto()->associate($producto->id);
            $productoTaTp->valor_proyectado     = $request->valor_proyectado;
            $productoTaTp->medio_verificacion   = $request->medio_verificacion;
            $producto->productoTaTp()->save($productoTaTp);
        }

        // Valida si es un producto de Servicios tecnológicos
        if ($proyecto->servicioTecnologico()->exists()) {
            $request->validate([
                'medio_verificacion' => 'required|string',
            ]);
            $productoServicioTecnologico = new ProductoServicioTecnologico();
            $productoServicioTecnologico->producto()->associate($producto->id);
            $productoServicioTecnologico->medio_verificacion    = $request->medio_verificacion;
            $productoServicioTecnologico->nombre_indicador      = $request->nombre_indicador;
            $productoServicioTecnologico->formula_indicador      = $request->formula_indicador;
            $producto->productoServicioTecnologico()->save($productoServicioTecnologico);
        }

        return redirect()->route('convocatorias.proyectos.productos.index', [$convocatoria, $proyecto])->with('success', 'El recurso se ha creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show(Convocatoria $convocatoria, Proyecto $proyecto, Producto $producto)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function edit(Convocatoria $convocatoria, Proyecto $proyecto, Producto $producto)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);

        $proyecto->idi;
        $producto->productoIdi;
        $proyecto->culturaInnovacion;
        $producto->productoCulturaInnovacion;
        $proyecto->ta;
        $proyecto->tp;
        $producto->productoTaTp;
        $proyecto->servicioTecnologico;
        $producto->productoServicioTecnologico;

        $proyectoId = $proyecto->id;

        return Inertia::render('Convocatorias/Proyectos/Productos/Edit', [
            'convocatoria'              => $convocatoria->only('id', 'fase_formateada', 'fase', 'min_fecha_inicio_proyectos', 'max_fecha_finalizacion_proyectos'),
            'proyecto'                  => $proyecto,
            'producto'                  => $producto,
            'actividadesRelacionadas'   => $producto->actividades()->pluck('id'),
            'resultados'                => Resultado::select('resultados.id as value', 'resultados.descripcion as label', 'resultados.id as id')->whereHas('efectoDirecto', function ($query) use ($proyectoId) {
                $query->where('efectos_directos.proyecto_id', $proyectoId);
            })->where('resultados.descripcion', '!=', null)->with('actividades')->get(),
            'tiposProducto'             => json_decode(Storage::get('json/tipos-producto.json'), true),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(ProductoRequest $request, Convocatoria $convocatoria, Proyecto $proyecto, Producto $producto)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        $producto->nombre               = $request->nombre;
        $producto->fecha_inicio         = $request->fecha_inicio;
        $producto->fecha_finalizacion   = $request->fecha_finalizacion;
        $producto->indicador            = $request->indicador;
        $producto->resultado()->associate($request->resultado_id);

        if ($producto->resultado_id != $request->resultado_id) {
            $producto->actividades()->sync([]);
        } else {
            $producto->actividades()->sync($request->actividad_id);
        }

        if ($proyecto->idi()->exists()) {
            $request->validate([
                'tipo'                          => 'required|between:1,4',
                'subtipologia_minciencias_id'   => 'required|min:0|max:2147483647|integer|exists:subtipologias_minciencias,id'
            ]);
            $producto->productoIdi()->update(['subtipologia_minciencias_id' => $request->subtipologia_minciencias_id, 'tipo' => $request->tipo['value']]);
        }

        if ($proyecto->culturaInnovacion()->exists()) {
            $request->validate([
                'tipo'                          => 'required|between:1,4',
                'subtipologia_minciencias_id'   => 'required|min:0|max:2147483647|integer|exists:subtipologias_minciencias,id'
            ]);
            $producto->productoCulturaInnovacion()->update(['subtipologia_minciencias_id' => $request->subtipologia_minciencias_id, 'tipo' => $request->tipo['value']]);
        }

        if ($proyecto->ta()->exists() || $proyecto->tp()->exists()) {
            $request->validate([
                'medio_verificacion' => 'required|string',
                'valor_proyectado'   => 'required|string',
            ]);
            $producto->productoTaTp()->update(['valor_proyectado' => $request->valor_proyectado, 'medio_verificacion' => $request->medio_verificacion]);
        }

        if ($proyecto->servicioTecnologico()->exists()) {
            $request->validate([
                'medio_verificacion' => 'required|string',
            ]);
            $producto->productoServicioTecnologico()->update([
                'medio_verificacion' => $request->medio_verificacion,
                'nombre_indicador'   => $request->nombre_indicador,
                'formula_indicador'  => $request->formula_indicador,
            ]);
        }

        $producto->save();

        return back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Convocatoria $convocatoria, Proyecto $proyecto, Producto $producto)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        $producto->delete();

        return redirect()->route('convocatorias.proyectos.productos.index', [$convocatoria, $proyecto])->with('success', 'El recurso se ha eliminado correctamente.');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showProductosEvaluacion(Convocatoria $convocatoria, Evaluacion $evaluacion)
    {
        $this->authorize('visualizar-evaluacion-autor', $evaluacion);

        $resultado = $evaluacion->proyecto->efectosDirectos()->with('resultados')->get()->pluck('resultados')->flatten()->filter();
        $evaluacion->proyecto->codigo_linea_programatica = $evaluacion->proyecto->lineaProgramatica->codigo;

        switch ($evaluacion->proyecto) {
            case $evaluacion->proyecto->idi()->exists():
                $evaluacion->idiEvaluacion;
                $idi = $evaluacion->proyecto->idi;

                $segundaEvaluacion = IdiEvaluacion::whereHas('evaluacion', function ($query) use ($idi) {
                    $query->where('evaluaciones.proyecto_id', $idi->id)->where('evaluaciones.habilitado', true);
                })->where('idi_evaluaciones.id', '!=', $evaluacion->idiEvaluacion->id)->first();
                break;
            case $evaluacion->proyecto->ta()->exists():
                $evaluacion->taEvaluacion;
                $ta = $evaluacion->proyecto->ta;

                $segundaEvaluacion = TaEvaluacion::whereHas('evaluacion', function ($query) use ($ta) {
                    $query->where('evaluaciones.proyecto_id', $ta->id)->where('evaluaciones.habilitado', true);
                })->where('ta_evaluaciones.id', '!=', $evaluacion->taEvaluacion->id)->first();
                break;
            case $evaluacion->proyecto->tp()->exists():
                $evaluacion->tpEvaluacion;
                $tp = $evaluacion->proyecto->tp;

                $segundaEvaluacion = TpEvaluacion::whereHas('evaluacion', function ($query) use ($tp) {
                    $query->where('evaluaciones.proyecto_id', $tp->id)->where('evaluaciones.habilitado', true);
                })->where('tp_evaluaciones.id', '!=', $evaluacion->tpEvaluacion->id)->first();
                break;
            case $evaluacion->proyecto->culturaInnovacion()->exists():
                $evaluacion->culturaInnovacionEvaluacion;
                $culturaInnovacion = $evaluacion->proyecto->culturaInnovacion;

                $segundaEvaluacion = CulturaInnovacionEvaluacion::whereHas('evaluacion', function ($query) use ($culturaInnovacion) {
                    $query->where('evaluaciones.proyecto_id', $culturaInnovacion->id)->where('evaluaciones.habilitado', true);
                })->where('cultura_innovacion_evaluaciones.id', '!=', $evaluacion->culturaInnovacionEvaluacion->id)->first();
                break;
            case $evaluacion->proyecto->servicioTecnologico()->exists():
                $servicioTecnologico = $evaluacion->proyecto->servicioTecnologico;

                $segundaEvaluacion = ServicioTecnologicoEvaluacion::whereHas('evaluacion', function ($query) use ($servicioTecnologico) {
                    $query->where('evaluaciones.proyecto_id', $servicioTecnologico->id)->where('evaluaciones.habilitado', true);
                })->where('servicios_tecnologicos_evaluaciones.id', '!=', $evaluacion->servicioTecnologicoEvaluacion->id)->first();
                break;
            default:
                break;
        }

        return Inertia::render('Convocatorias/Evaluaciones/Productos/Index', [
            'convocatoria'          => $convocatoria->only('id', 'fase_formateada', 'fase'),
            'evaluacion'            => $evaluacion,
            'segundaEvaluacion'     => $segundaEvaluacion,
            'proyecto'              => $evaluacion->proyecto->only('id', 'codigo_linea_programatica', 'precio_proyecto', 'finalizado', 'cantidad_objetivos'),
            'filters'               => request()->all('search'),
            'productos'             => Producto::whereIn(
                'resultado_id',
                $resultado->map(function ($resultado) {
                    return $resultado->id;
                })
            )->with('resultado.objetivoEspecifico', 'productoTaTp')->orderBy('resultado_id', 'ASC')
                ->filterProducto(request()->only('search'))->paginate()->appends(['search' => request()->search]),
            'productosGantt'        => Producto::whereIn(
                'resultado_id',
                $resultado->map(function ($resultado) {
                    return $resultado->id;
                })
            )->orderBy('fecha_inicio', 'ASC')->get(),
        ]);
    }

    /**
     * updateProductosEvaluacion
     *
     * @param  mixed $request
     * @param  mixed $convocatoria
     * @param  mixed $evaluacion
     * @return void
     */
    public function updateProductosEvaluacion(Request $request, Convocatoria $convocatoria, Evaluacion $evaluacion)
    {
        $this->authorize('modificar-evaluacion-autor', $evaluacion);

        switch ($evaluacion) {
            case $evaluacion->idiEvaluacion()->exists():
                $evaluacion->idiEvaluacion()->update([
                    'productos_puntaje'      => $request->productos_puntaje,
                    'productos_comentario'   => $request->productos_requiere_comentario == false ? $request->productos_comentario : null
                ]);
                break;
            case $evaluacion->culturaInnovacionEvaluacion()->exists():
                $evaluacion->culturaInnovacionEvaluacion()->update([
                    'productos_puntaje'      => $request->productos_puntaje,
                    'productos_comentario'   => $request->productos_requiere_comentario == false ? $request->productos_comentario : null
                ]);
                break;

            case $evaluacion->taEvaluacion()->exists():
                $evaluacion->taEvaluacion()->update([
                    'productos_comentario'  => $request->productos_requiere_comentario == false ? $request->productos_comentario : null
                ]);
                break;
            case $evaluacion->tpEvaluacion()->exists():
                $evaluacion->tpEvaluacion()->update([
                    'productos_comentario'  => $request->productos_requiere_comentario == false ? $request->productos_comentario : null
                ]);
                break;

            case $evaluacion->servicioTecnologicoEvaluacion()->exists():
                $evaluacion->servicioTecnologicoEvaluacion()->update([
                    'productos_primer_obj_puntaje'      => $request->productos_primer_obj_puntaje,
                    'productos_primer_obj_comentario'   => $request->productos_primer_obj_requiere_comentario == false ? $request->productos_primer_obj_comentario : null,

                    'productos_segundo_obj_puntaje'      => $request->productos_segundo_obj_puntaje,
                    'productos_segundo_obj_comentario'   => $request->productos_segundo_obj_requiere_comentario == false ? $request->productos_segundo_obj_comentario : null,

                    'productos_tercer_obj_puntaje'      => $request->productos_tercer_obj_puntaje,
                    'productos_tercer_obj_comentario'   => $request->productos_tercer_obj_requiere_comentario == false ? $request->productos_tercer_obj_comentario : null,

                    'productos_cuarto_obj_puntaje'      => $request->productos_cuarto_obj_puntaje,
                    'productos_cuarto_obj_comentario'   => $request->productos_cuarto_obj_requiere_comentario == false ? $request->productos_cuarto_obj_comentario : null,
                ]);
                break;
            default:
                break;
        }

        $evaluacion->save();

        return back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function productoEvaluacion(Convocatoria $convocatoria, Evaluacion $evaluacion, Producto $producto)
    {
        $this->authorize('visualizar-evaluacion-autor', $evaluacion);

        $evaluacion->proyecto->idi;
        $producto->productoIdi;
        $evaluacion->proyecto->culturaInnovacion;
        $producto->productoCulturaInnovacion;
        $evaluacion->proyecto->ta;
        $evaluacion->proyecto->tp;
        $producto->productoTaTp;
        $evaluacion->proyecto->servicioTecnologico;
        $producto->productoServicioTecnologico;

        $resultados = $evaluacion->proyecto->efectosDirectos()->whereHas('resultados', function ($query) {
            $query->where('descripcion', '!=', null);
        })->with('resultados:id as value,descripcion as label,efecto_directo_id')->get()->pluck('resultados')->flatten();

        $objetivoEspecifico = $evaluacion->proyecto->causasDirectas()->with('objetivoEspecifico')->get()->pluck('objetivoEspecifico')->flatten()->filter();

        return Inertia::render('Convocatorias/Evaluaciones/Productos/Edit', [
            'convocatoria'              => $convocatoria->only('id', 'fase_formateada', 'fase', 'min_fecha_inicio_proyectos', 'max_fecha_finalizacion_proyectos'),
            'evaluacion'                => $evaluacion->only('id'),
            'proyecto'                  => $evaluacion->proyecto,
            'producto'                  => $producto,
            'actividades'               => Actividad::whereIn(
                'objetivo_especifico_id',
                $objetivoEspecifico->map(function ($objetivoEspecifico) {
                    return $objetivoEspecifico->id;
                })
            )->orderBy('fecha_inicio', 'ASC')->get(),
            'actividadesRelacionadas'   => $producto->actividades()->pluck('id'),
            'resultados'                => $resultados->where('label', '!=', null)->flatten(),
            'tiposProducto'     => json_decode(Storage::get('json/tipos-producto.json'), true),
        ]);
    }
}
