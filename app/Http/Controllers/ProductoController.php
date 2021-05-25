<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductoRequest;
use App\Models\Convocatoria;
use App\Models\Proyecto;
use App\Models\Producto;
use App\Models\ProductoIdi;
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
        $this->authorize('viewAny', [Producto::class]);

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
        $this->authorize('create', [Producto::class]);

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
        $this->authorize('create', [Producto::class]);

        $producto = new Producto();
        $producto->nombre               = $request->nombre;
        $producto->fecha_inicio         = $request->fecha_inicio;
        $producto->fecha_finalizacion   = $request->fecha_finalizacion;
        $producto->indicador            = $request->indicador;
        $producto->resultado()->associate($request->resultado_id);
        $producto->save();

        // Valida si es un producto de I+D+i
        if ($request->subtipologia_minciencias_id) {
            $productoIdi = new ProductoIdi();
            $productoIdi->trl = $request->trl;
            $productoIdi->subtipologiaMinciencias()->associate($request->subtipologia_minciencias_id);
            $producto->productoIdi()->save($productoIdi);
        }

        // Valida si es un producto de TaTp
        if ($request->valor_proyectado) {
            $productoTaTp = new ProductoTaTp();
            $productoTaTp->producto()->associate($producto->id);
            $productoTaTp->valor_proyectado = $request->valor_proyectado;
            $producto->productoTaTp()->save($productoTaTp);
        }

        return redirect()->route('convocatorias.proyectos.productos.index', [$convocatoria, $proyecto])->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show(Convocatoria $convocatoria, Proyecto $proyecto, Producto $producto)
    {
        $this->authorize('view', [Producto::class, $producto]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function edit(Convocatoria $convocatoria, Proyecto $proyecto, Producto $producto)
    {
        $this->authorize('update', [Producto::class, $producto]);

        $proyecto->idi;
        $producto->idiProducto;
        $proyecto->taTp;
        $producto->productoTaTp;

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
        $this->authorize('update', [Producto::class, $producto]);

        $producto->nombre               = $request->nombre;
        $producto->fecha_inicio         = $request->fecha_inicio;
        $producto->fecha_finalizacion   = $request->fecha_finalizacion;
        $producto->indicador            = $request->indicador;
        $producto->resultado()->associate($request->resultado_id);

        if ($request->subtipologia_minciencias_id) {
            $producto->productoIdi()->update(['subtipologia_minciencias_id' => $request->subtipologia_minciencias_id, 'trl' => $request->trl]);
        }

        if ($request->valor_proyectado) {
            $producto->ProductoTaTp()->update(['valor_proyectado' => $request->valor_proyectado]);
        }

        $producto->save();

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Convocatoria $convocatoria, Proyecto $proyecto, Producto $producto)
    {
        $this->authorize('delete', [Producto::class, $producto]);

        $producto->delete();

        return redirect()->route('convocatorias.proyectos.productos.index', [$convocatoria, $proyecto])->with('success', 'The resource has been deleted successfully.');
    }
}
