<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductoRequest;
use App\Models\Convocatoria;
use App\Models\Proyecto;
use App\Models\Producto;
use App\Models\ProductoIdi;
use App\Models\ProductoServicioTecnologico;
use App\Models\ProductoTaTp;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('validar-autor', $proyecto);

        $resultado = $proyecto->efectosDirectos()->with('resultado')->get()->pluck('resultado')->flatten()->filter();
        $proyecto->codigo_linea_programatica = $proyecto->tipoProyecto->lineaProgramatica->codigo;

        return Inertia::render('Convocatorias/Proyectos/Productos/Index', [
            'convocatoria'  => $convocatoria->only('id'),
            'proyecto'      => $proyecto->only('id', 'codigo_linea_programatica', 'precio_proyecto'),
            'filters'       => request()->all('search'),
            'productos'     => Producto::whereIn(
                'resultado_id',
                $resultado->map(function ($resultado) {
                    return $resultado->id;
                })
            )->orderBy('fecha_inicio', 'ASC')->filterProducto(request()->only('search'))->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('validar-autor', $proyecto);

        $proyecto->idi;
        $proyecto->taTp;

        return Inertia::render('Convocatorias/Proyectos/Productos/Create', [
            'convocatoria'      => $convocatoria,
            'proyecto'          => $proyecto,
            'resultados'        => $proyecto->efectosDirectos()->whereHas('resultado', function ($query) {
                $query->where('descripcion', '!=', null);
            })->with('resultado:id as value,descripcion as label,efecto_directo_id')->get()->pluck('resultado')
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
        $this->authorize('validar-autor', $proyecto);

        $producto = new Producto();
        $producto->nombre               = $request->nombre;
        $producto->fecha_inicio         = $request->fecha_inicio;
        $producto->fecha_finalizacion   = $request->fecha_finalizacion;
        $producto->indicador            = $request->indicador;
        $producto->resultado()->associate($request->resultado_id);
        $producto->save();

        // Valida si es un producto de I+D+i
        if ($proyecto->idi()->exists()) {
            $productoIdi = new ProductoIdi();
            $productoIdi->trl = $request->trl;
            $productoIdi->subtipologiaMinciencias()->associate($request->subtipologia_minciencias_id);
            $producto->productoIdi()->save($productoIdi);
        }

        // Valida si es un producto de TaTp
        if ($proyecto->taTp()->exists()) {
            $productoTaTp = new ProductoTaTp();
            $productoTaTp->producto()->associate($producto->id);
            $productoTaTp->valor_proyectado     = $request->valor_proyectado;
            $productoTaTp->medio_verificacion   = $request->medio_verificacion;
            $producto->productoTaTp()->save($productoTaTp);
        }

        // Valida si es un producto de TaTp
        if ($proyecto->servicioTecnologico()->exists()) {
            $productoServicioTecnologico = new ProductoServicioTecnologico();
            $productoServicioTecnologico->producto()->associate($producto->id);
            $productoServicioTecnologico->medio_verificacion = $request->medio_verificacion;
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
        $this->authorize('validar-autor', $proyecto);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function edit(Convocatoria $convocatoria, Proyecto $proyecto, Producto $producto)
    {
        $this->authorize('validar-autor', $proyecto);

        $proyecto->idi;
        $producto->productoIdi;
        $proyecto->taTp;
        $producto->productoTaTp;
        $proyecto->servicioTecnologico;
        $producto->productoServicioTecnologico;

        return Inertia::render('Convocatorias/Proyectos/Productos/Edit', [
            'convocatoria'      => $convocatoria->only('id'),
            'proyecto'          => $proyecto,
            'producto'          => $producto,
            'resultados'        => $proyecto->efectosDirectos()->whereHas('resultado', function ($query) {
                $query->where('descripcion', '!=', null);
            })->with('resultado:id as value,descripcion as label,efecto_directo_id')->get()->pluck('resultado'),
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
        $this->authorize('validar-autor', $proyecto);

        $producto->nombre               = $request->nombre;
        $producto->fecha_inicio         = $request->fecha_inicio;
        $producto->fecha_finalizacion   = $request->fecha_finalizacion;
        $producto->indicador            = $request->indicador;
        $producto->resultado()->associate($request->resultado_id);

        if ($proyecto->idi()->exists()) {
            $producto->productoIdi()->update(['subtipologia_minciencias_id' => $request->subtipologia_minciencias_id, 'trl' => $request->trl]);
        }

        if ($proyecto->taTp()->exists()) {
            $producto->productoTaTp()->update(['valor_proyectado' => $request->valor_proyectado, 'medio_verificacion' => $request->medio_verificacion]);
        }

        if ($proyecto->servicioTecnologico()->exists()) {
            $producto->productoServicioTecnologico()->update(['medio_verificacion' => $request->medio_verificacion]);
        }

        $producto->save();

        return redirect()->back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Convocatoria $convocatoria, Proyecto $proyecto, Producto $producto)
    {
        $this->authorize('validar-autor', $proyecto);

        $producto->delete();

        return redirect()->route('convocatorias.proyectos.productos.index', [$convocatoria, $proyecto])->with('success', 'El recurso se ha eliminado correctamente.');
    }
}
