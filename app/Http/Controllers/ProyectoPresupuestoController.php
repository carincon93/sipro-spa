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
        $this->authorize('viewAny', ProyectoPresupuesto::class);

        $proyecto->codigo_linea_programatica        = $proyecto->tipoProyecto->lineaProgramatica->codigo;
        $proyecto->total_maquinaria_industrial      = PresupuestoValidationTrait::totalMaquinariaIndustrial($proyecto);
        $proyecto->total_viaticos                   = PresupuestoValidationTrait::totalViaticosInteriorGastosAlumnos($proyecto);
        $proyecto->total_mantenimiento_maquinaria   = PresupuestoValidationTrait::totalMantenimientoMaquinaria($proyecto);
        $proyecto->total_servicios_especiales_construccion = PresupuestoValidationTrait::totalServiciosEspecialesConstruccion($proyecto);

        return Inertia::render('Convocatorias/Proyectos/ProyectoPresupuesto/Index', [
            'convocatoria'              => $convocatoria->only('id'),
            'proyecto'                  => $proyecto->only('id', 'codigo_linea_programatica', 'codigo', 'diff_meses', 'total_proyecto_presupuesto', 'total_maquinaria_industrial', 'total_servicios_especiales_construccion', 'total_viaticos', 'total_mantenimiento_maquinaria'),
            'filters'                   => request()->all('search'),
            'proyectoPresupuesto'       => ProyectoPresupuesto::where('proyecto_id', $proyecto->id)->filterProyectoPresupuesto(request()->only('search'))->with('convocatoriaPresupuesto.presupuestoSennova.tercerGrupoPresupuestal:id,nombre', 'convocatoriaPresupuesto.presupuestoSennova.segundoGrupoPresupuestal:id,nombre', 'convocatoriaPresupuesto.presupuestoSennova.usoPresupuestal:id,descripcion')->paginate(),
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
        $this->authorize('create', [ProyectoPresupuesto::class]);

        $proyecto->tipoProyecto->lineaProgramatica->only('id');

        return Inertia::render('Convocatorias/Proyectos/ProyectoPresupuesto/Create', [
            'convocatoria'  => $convocatoria->only('id'),
            'proyecto'      => $proyecto,
            'tiposLicencia' => json_decode(Storage::get('json/tipos-licencia-software.json'), true),
            'tiposSoftware' => json_decode(Storage::get('json/tipos-software.json'), true)
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
        $this->authorize('create', [ProyectoPresupuesto::class]);

        $convocatoriaPresupuesto = ConvocatoriaPresupuesto::find($request->convocatoria_presupuesto_id);
        // Validaciones
        if (PresupuestoValidationTrait::viaticosValidation($proyecto, $convocatoriaPresupuesto->presupuestoSennova->segundoGrupoPresupuestal->codigo, $request->valor, $request->numero_items, 0, 0)) {
            return redirect()->back()->with('error', "La sumatoria de todos los rubros de viáticos no debe superar el valor de $4.000.000");
        }

        $proyectoPresupuesto = new ProyectoPresupuesto();
        $proyectoPresupuesto->descripcion      = $request->descripcion;
        $proyectoPresupuesto->justificacion    = $request->justificacion;
        $proyectoPresupuesto->valor            = $request->valor;
        $proyectoPresupuesto->numero_items     = $request->numero_items;

        $proyectoPresupuesto->proyecto()->associate($proyecto);
        $proyectoPresupuesto->convocatoriaPresupuesto()->associate($convocatoriaPresupuesto);
        $proyectoPresupuesto->save();

        if ($request->codigo_uso_presupuestal == '2010100600203101') {
            $softwareInfo = new SoftwareInfo();
            $softwareInfo->tipo_licencia        = $request->tipo_licencia;
            $softwareInfo->tipo_software        = $request->tipo_software;
            $softwareInfo->fecha_inicio         = $request->fecha_inicio;
            $softwareInfo->fecha_finalizacion   = $request->fecha_finalizacion;

            $proyectoPresupuesto->softwareInfo()->save($softwareInfo);
        }

        return redirect()->route('convocatorias.proyectos.proyecto-presupuesto.proyecto-lote-estudio-mercado.index', [$convocatoria, $proyecto, $proyectoPresupuesto])->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProyectoPresupuesto  $proyectoPresupuesto
     * @return \Illuminate\Http\Response
     */
    public function show(Convocatoria $convocatoria, Proyecto $proyecto, ProyectoPresupuesto $proyectoPresupuesto)
    {
        $this->authorize('view', [ProyectoPresupuesto::class, $proyectoPresupuesto]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProyectoPresupuesto  $proyectoPresupuesto
     * @return \Illuminate\Http\Response
     */
    public function edit(Convocatoria $convocatoria, Proyecto $proyecto, ProyectoPresupuesto $proyectoPresupuesto)
    {
        $this->authorize('update', [ProyectoPresupuesto::class, $proyectoPresupuesto]);

        $proyectoPresupuesto->softwareInfo;
        $proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->usoPresupuestal;
        $proyecto->tipoProyecto->lineaProgramatica;

        return Inertia::render('Convocatorias/Proyectos/ProyectoPresupuesto/Edit', [
            'convocatoria'         => $convocatoria->only('id'),
            'proyecto'             => $proyecto,
            'proyectoPresupuesto'  => $proyectoPresupuesto,
            'tiposLicencia'        => json_decode(Storage::get('json/tipos-licencia-software.json'), true),
            'tiposSoftware'        => json_decode(Storage::get('json/tipos-software.json'), true)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProyectoPresupuesto  $proyectoPresupuesto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Convocatoria $convocatoria, Proyecto $proyecto, ProyectoPresupuesto $proyectoPresupuesto)
    {
        $this->authorize('update', [ProyectoPresupuesto::class, $proyectoPresupuesto]);

        $convocatoriaPresupuesto = ConvocatoriaPresupuesto::find($request->convocatoria_presupuesto_id);
        // Validaciones
        if (PresupuestoValidationTrait::viaticosValidation($proyecto, $convocatoriaPresupuesto->presupuestoSennova->segundoGrupoPresupuestal->codigo, $request->valor, $request->numero_items, $proyectoPresupuesto->valor, $proyectoPresupuesto->numero_items)) {
            return redirect()->back()->with('error', "La sumatoria de todos los rubros de viáticos no debe superar el valor de $4.000.000");
        }

        if ($convocatoriaPresupuesto->presupuestoSennova->requiere_estudio_mercado == false) {
            foreach ($proyectoPresupuesto->proyectoLoteEstudioMercado as $loteEstudioMercado) {
                Storage::delete($loteEstudioMercado->ficha_tecnica);

                foreach ($loteEstudioMercado->estudiosMercado as $estudioMercado) {
                    Storage::delete($estudioMercado->valor);
                }

                $loteEstudioMercado->delete();
            }

            $proyectoPresupuesto->valor        = $request->valor;
            $proyectoPresupuesto->numero_items = $request->numero_items;
        }

        $proyectoPresupuesto->descripcion      = $request->descripcion;
        $proyectoPresupuesto->justificacion    = $request->justificacion;
        $proyectoPresupuesto->valor            = null;
        $proyectoPresupuesto->numero_items     = null;

        $proyectoPresupuesto->proyecto()->associate($proyecto);
        $proyectoPresupuesto->convocatoriaPresupuesto()->associate($convocatoriaPresupuesto);
        $proyectoPresupuesto->save();

        $softwareInfo = SoftwareInfo::where('proyecto_presupuesto_id', $proyectoPresupuesto->id)->first();
        if ($request->codigo_uso_presupuestal == '2010100600203101') {
            $proyectoPresupuesto->softwareInfo()->updateOrCreate(
                ['id' => $softwareInfo ? $softwareInfo->id : null],
                [
                    'tipo_licencia'      => $request->tipo_licencia,
                    'tipo_software'      => $request->tipo_software,
                    'fecha_inicio'       => $request->fecha_inicio,
                    'fecha_finalizacion' => $request->fecha_finalizacion
                ]
            );
        } else {
            $proyectoPresupuesto->softwareInfo()->delete();
        }

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProyectoPresupuesto  $proyectoPresupuesto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Convocatoria $convocatoria, Proyecto $proyecto, ProyectoPresupuesto $proyectoPresupuesto)
    {
        $this->authorize('delete', [ProyectoPresupuesto::class, $proyectoPresupuesto]);

        $proyectoPresupuesto->delete();

        return redirect()->route('convocatorias.proyectos.proyecto-presupuesto.index', [$convocatoria, $proyecto])->with('success', 'The resource has been deleted successfully.');
    }
}
