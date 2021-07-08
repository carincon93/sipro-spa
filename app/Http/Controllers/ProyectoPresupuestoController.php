<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProyectoPresupuestoRequest;
use App\Models\Convocatoria;
use App\Models\ConvocatoriaPresupuesto;
use App\Models\SegundoGrupoPresupuestal;
use App\Models\Proyecto;
use App\Models\ProyectoPresupuesto;
use App\Models\SoftwareInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Traits\PresupuestoValidationTrait;
use App\Models\ServicioEdicionInfo;
use Inertia\Inertia;

class ProyectoPresupuestoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);

        $proyecto->codigo_linea_programatica                = $proyecto->lineaProgramatica->codigo;
        $proyecto->total_maquinaria_industrial              = PresupuestoValidationTrait::totalUsoPresupuestal($proyecto, '2040115');
        $proyecto->total_viaticos                           = PresupuestoValidationTrait::totalUsoPresupuestal($proyecto, '2042186') + PresupuestoValidationTrait::totalUsoPresupuestal($proyecto, '2041102');
        $proyecto->total_mantenimiento_maquinaria           = PresupuestoValidationTrait::totalUsoPresupuestal($proyecto, '2040516');
        $proyecto->total_servicios_especiales_construccion  = PresupuestoValidationTrait::totalUsoPresupuestal($proyecto, '2045110');
        $proyecto->total_equipo_sistemas                    = PresupuestoValidationTrait::totalUsoPresupuestal($proyecto, '2040106');
        $proyecto->otras_compras_equipos                    = PresupuestoValidationTrait::totalUsoPresupuestal($proyecto, '2040125');
        $proyecto->software                                 = PresupuestoValidationTrait::totalUsoPresupuestal($proyecto, '2040108');
        $proyecto->viaticos_exterior                        = PresupuestoValidationTrait::totalUsoPresupuestal($proyecto, '2041104');
        $proyecto->viaticos_interior                        = PresupuestoValidationTrait::totalUsoPresupuestal($proyecto, '2041102');

        return Inertia::render('Convocatorias/Proyectos/ProyectoPresupuesto/Index', [
            'convocatoria'              => $convocatoria->only('id'),
            'proyecto'                  => $proyecto->only('id', 'codigo_linea_programatica', 'precio_proyecto', 'modificable', 'codigo', 'diff_meses', 'total_proyecto_presupuesto', 'total_maquinaria_industrial', 'total_servicios_especiales_construccion', 'total_viaticos', 'total_mantenimiento_maquinaria'),
            'filters'                   => request()->all('search'),
            'proyectoPresupuesto'       => ProyectoPresupuesto::where('proyecto_id', $proyecto->id)->filterProyectoPresupuesto(request()->only('search'))->with('convocatoriaPresupuesto.presupuestoSennova.tercerGrupoPresupuestal:id,nombre', 'convocatoriaPresupuesto.presupuestoSennova.segundoGrupoPresupuestal:id,nombre,codigo', 'convocatoriaPresupuesto.presupuestoSennova.usoPresupuestal:id,descripcion')->paginate(),
            'segundoGrupoPresupuestal'  => SegundoGrupoPresupuestal::orderBy('nombre', 'ASC')->get('nombre'),
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

        $proyecto->lineaProgramatica->only('id');

        return Inertia::render('Convocatorias/Proyectos/ProyectoPresupuesto/Create', [
            'convocatoria'              => $convocatoria->only('id'),
            'proyecto'                  => $proyecto,
            'tiposLicencia'             => json_decode(Storage::get('json/tipos-licencia-software.json'), true),
            'opcionesServiciosEdicion'  => json_decode(Storage::get('json/opciones-servicios-edicion.json'), true),
            'tiposSoftware'             => json_decode(Storage::get('json/tipos-software.json'), true)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProyectoPresupuestoRequest $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        $convocatoriaPresupuesto = ConvocatoriaPresupuesto::find($request->convocatoria_presupuesto_id);

        /**
         * Línea 66 y 82
         */
        if ($proyecto->lineaProgramatica->codigo == 66 || $proyecto->lineaProgramatica->codigo == 82) {
            if (PresupuestoValidationTrait::viaticosValidation($proyecto, $convocatoriaPresupuesto->presupuestoSennova->segundoGrupoPresupuestal->codigo, $request->valor, $request->numero_items)) {
                return redirect()->back()->with('error', "La sumatoria de todos los rubros de viáticos no debe superar el valor de $4.460.000");
            }
        }

        $presupuesto = new ProyectoPresupuesto();
        $presupuesto->descripcion      = $request->descripcion;
        $presupuesto->justificacion    = $request->justificacion;
        $presupuesto->valor            = $request->valor;
        $presupuesto->numero_items     = $request->numero_items;

        $presupuesto->proyecto()->associate($proyecto);
        $presupuesto->convocatoriaPresupuesto()->associate($convocatoriaPresupuesto);
        $presupuesto->save();

        if ($request->codigo_uso_presupuestal == '2010100600203101') {
            $request->validate([
                'tipo_licencia'             => 'required|integer',
                'tipo_licencia'             => 'required|integer',
                'tipo_software'             => 'required|integer',
                'fecha_inicio'              => 'required|date|date_format:Y-m-d|before:fecha_finalizacion',
                'fecha_finalizacion'        => 'required|date|date_format:Y-m-d|after:fecha_inicio',
            ]);

            $softwareInfo = new SoftwareInfo();
            $softwareInfo->tipo_licencia        = $request->tipo_licencia;
            $softwareInfo->tipo_software        = $request->tipo_software;
            $softwareInfo->fecha_inicio         = $request->fecha_inicio;
            $softwareInfo->fecha_finalizacion   = $request->fecha_finalizacion;

            $presupuesto->softwareInfo()->save($softwareInfo);
        } else if ($request->codigo_uso_presupuestal == '2020200800901') {
            $request->servicio_edicion_info = $request->servicio_edicion_info['value'];
            $servicioEdicionInfo = new ServicioEdicionInfo();
            $servicioEdicionInfo->info = $request->servicio_edicion_info;

            $presupuesto->servicioEdicionInfo()->save($servicioEdicionInfo);
        }

        return redirect()->route('convocatorias.proyectos.presupuesto.lote.index', [$convocatoria, $proyecto, $presupuesto])->with('success', 'El recurso se ha creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProyectoPresupuesto  $presupuesto
     * @return \Illuminate\Http\Response
     */
    public function show(Convocatoria $convocatoria, Proyecto $proyecto, ProyectoPresupuesto $presupuesto)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProyectoPresupuesto  $presupuesto
     * @return \Illuminate\Http\Response
     */
    public function edit(Convocatoria $convocatoria, Proyecto $proyecto, ProyectoPresupuesto $presupuesto)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);

        $presupuesto->softwareInfo;
        $presupuesto->servicioEdicionInfo;
        $presupuesto->convocatoriaPresupuesto->presupuestoSennova->usoPresupuestal;
        $proyecto->lineaProgramatica;

        return Inertia::render('Convocatorias/Proyectos/ProyectoPresupuesto/Edit', [
            'convocatoria'              => $convocatoria->only('id'),
            'proyecto'                  => $proyecto,
            'proyectoPresupuesto'       => $presupuesto,
            'tiposLicencia'             => json_decode(Storage::get('json/tipos-licencia-software.json'), true),
            'opcionesServiciosEdicion'  => json_decode(Storage::get('json/opciones-servicios-edicion.json'), true),
            'tiposSoftware'             => json_decode(Storage::get('json/tipos-software.json'), true)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProyectoPresupuesto  $presupuesto
     * @return \Illuminate\Http\Response
     */
    public function update(ProyectoPresupuestoRequest $request, Convocatoria $convocatoria, Proyecto $proyecto, ProyectoPresupuesto $presupuesto)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        $convocatoriaPresupuesto = ConvocatoriaPresupuesto::find($request->convocatoria_presupuesto_id);

        /**
         * Línea 66 y 82
         */
        if ($proyecto->lineaProgramatica->codigo == 66 || $proyecto->lineaProgramatica->codigo == 82) {
            if (PresupuestoValidationTrait::viaticosValidation($proyecto, $convocatoriaPresupuesto->presupuestoSennova->segundoGrupoPresupuestal->codigo, $request->valor, $request->numero_items)) {
                return redirect()->back()->with('error', "La sumatoria de todos los rubros de viáticos no debe superar el valor de $4.460.000");
            }
        }

        if ($convocatoriaPresupuesto->presupuestoSennova->requiere_estudio_mercado == false) {
            foreach ($presupuesto->proyectoLoteEstudioMercado as $loteEstudioMercado) {
                Storage::delete($loteEstudioMercado->ficha_tecnica);

                foreach ($loteEstudioMercado->estudiosMercado as $estudioMercado) {
                    Storage::delete($estudioMercado->valor);
                }

                $loteEstudioMercado->delete();
            }

            $presupuesto->valor        = $request->valor;
            $presupuesto->numero_items = $request->numero_items;
        } else {
            $presupuesto->valor            = null;
            $presupuesto->numero_items     = null;
        }

        $presupuesto->descripcion      = $request->descripcion;
        $presupuesto->justificacion    = $request->justificacion;

        $presupuesto->proyecto()->associate($proyecto);
        $presupuesto->convocatoriaPresupuesto()->associate($convocatoriaPresupuesto);
        $presupuesto->save();

        $softwareInfo = SoftwareInfo::where('proyecto_presupuesto_id', $presupuesto->id)->first();
        if ($request->codigo_uso_presupuestal == '2010100600203101') {
            $request->validate([
                'tipo_licencia'             => 'required|integer',
                'tipo_licencia'             => 'required|integer',
                'tipo_software'             => 'required|integer',
                'fecha_inicio'              => 'required|date|date_format:Y-m-d|before:fecha_finalizacion',
                'fecha_finalizacion'        => 'required|date|date_format:Y-m-d|after:fecha_inicio',
            ]);

            $presupuesto->softwareInfo()->updateOrCreate(
                ['id' => $softwareInfo ? $softwareInfo->id : null],
                [
                    'tipo_licencia'      => $request->tipo_licencia,
                    'tipo_software'      => $request->tipo_software,
                    'fecha_inicio'       => $request->fecha_inicio,
                    'fecha_finalizacion' => $request->fecha_finalizacion
                ]
            );
        } else {
            $presupuesto->softwareInfo()->delete();
        }

        $servicioEdicionInfo = ServicioEdicionInfo::where('proyecto_presupuesto_id', $presupuesto->id)->first();
        if ($request->codigo_uso_presupuestal == '2020200800901') {
            $request->servicio_edicion_info = $request->servicio_edicion_info['value'];
            $presupuesto->servicioEdicionInfo()->updateOrCreate(
                ['id' => $servicioEdicionInfo ? $servicioEdicionInfo->id : null],
                [
                    'info' => $request->servicio_edicion_info,
                ]
            );
        } else {
            $presupuesto->servicioEdicionInfo()->delete();
        }

        return redirect()->back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProyectoPresupuesto  $presupuesto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Convocatoria $convocatoria, Proyecto $proyecto, ProyectoPresupuesto $presupuesto)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        $presupuesto->delete();

        return redirect()->route('convocatorias.proyectos.presupuesto.index', [$convocatoria, $proyecto])->with('success', 'El recurso se ha eliminado correctamente.');
    }
}
