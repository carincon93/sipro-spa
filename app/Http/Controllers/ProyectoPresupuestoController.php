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
use App\Models\Evaluacion\Evaluacion;
use App\Models\Evaluacion\ProyectoPresupuestoEvaluacion;
use App\Models\ServicioEdicionInfo;
use Illuminate\Support\Str;
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
        $proyecto->total_maquinaria_industrial              = PresupuestoValidationTrait::totalSegundoGrupoPresupuestalProyecto($proyecto, '2040115');
        $proyecto->total_viaticos                           = PresupuestoValidationTrait::totalSegundoGrupoPresupuestalProyecto($proyecto, '2042186') + PresupuestoValidationTrait::totalSegundoGrupoPresupuestalProyecto($proyecto, '2041102');
        $proyecto->total_mantenimiento_maquinaria           = PresupuestoValidationTrait::totalSegundoGrupoPresupuestalProyecto($proyecto, '2040516');
        $proyecto->total_servicios_especiales_construccion  = PresupuestoValidationTrait::totalSegundoGrupoPresupuestalProyecto($proyecto, '2045110');
        $proyecto->total_equipo_sistemas                    = PresupuestoValidationTrait::totalSegundoGrupoPresupuestalProyecto($proyecto, '2040106');
        $proyecto->otras_compras_equipos                    = PresupuestoValidationTrait::totalSegundoGrupoPresupuestalProyecto($proyecto, '2040125');
        $proyecto->software                                 = PresupuestoValidationTrait::totalSegundoGrupoPresupuestalProyecto($proyecto, '2040108');
        $proyecto->viaticos_exterior                        = PresupuestoValidationTrait::totalSegundoGrupoPresupuestalProyecto($proyecto, '2041104');
        $proyecto->viaticos_interior                        = PresupuestoValidationTrait::totalSegundoGrupoPresupuestalProyecto($proyecto, '2041102');

        $salarioMinimo = json_decode(Storage::get('json/salario-minimo.json'), true);
        $proyecto->salarios_minimos = ($salarioMinimo['value'] * 100);

        if ($proyecto->codigo_linea_programatica == 70) {
            $proyecto->max_valor_materiales_formacion   = $proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->max_valor_materiales_formacion;
            $proyecto->max_valor_bienestar_alumnos      = $proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->max_valor_bienestar_alumnos;
            $proyecto->max_valor_viaticos_interior      = $proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->max_valor_viaticos_interior;
            $proyecto->max_valor_edt                    = $proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->max_valor_edt;
            $proyecto->max_valor_mantenimiento_equipos  = $proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->max_valor_mantenimiento_equipos;
            $proyecto->max_valor_proyecto               = $proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->suma_max_valores + $proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->max_valor_materiales_formacion + $proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->max_valor_bienestar_alumnos;
        }

        return Inertia::render('Convocatorias/Proyectos/ProyectoPresupuesto/Index', [
            'convocatoria'              => $convocatoria->only('id', 'fase_formateada'),
            'proyecto'                  => $proyecto->only('id', 'codigo_linea_programatica', 'precio_proyecto', 'modificable', 'codigo', 'diff_meses', 'total_proyecto_presupuesto', 'total_maquinaria_industrial', 'total_servicios_especiales_construccion', 'total_viaticos', 'total_mantenimiento_maquinaria', 'max_valor_materiales_formacion', 'max_valor_bienestar_alumnos', 'max_valor_viaticos_interior', 'max_valor_edt', 'max_valor_mantenimiento_equipos', 'max_valor_proyecto', 'salarios_minimos', 'en_subsanacion'),
            'filters'                   => request()->all('search', 'presupuestos'),
            'proyectoPresupuesto'       => ProyectoPresupuesto::select('proyecto_presupuesto.id', 'proyecto_presupuesto.convocatoria_presupuesto_id', 'proyecto_presupuesto.proyecto_id', 'proyecto_presupuesto.valor_total')->where('proyecto_id', $proyecto->id)->filterProyectoPresupuesto(request()->only('search', 'presupuestos'))->with('convocatoriaPresupuesto.presupuestoSennova.tercerGrupoPresupuestal:id,nombre', 'convocatoriaPresupuesto.presupuestoSennova.segundoGrupoPresupuestal:id,nombre,codigo', 'convocatoriaPresupuesto.presupuestoSennova.usoPresupuestal:id,descripcion')->paginate()->appends(['search' => request()->search, 'presupuestos' => request()->presupuestos]),
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
            'convocatoria'              => $convocatoria->only('id', 'fase_formateada'),
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

        if ($proyecto->lineaProgramatica->codigo != 69 && $proyecto->lineaProgramatica->codigo != 70) {
            if (PresupuestoValidationTrait::viaticosValidation($proyecto, $convocatoriaPresupuesto, null, $request->valor_total, 4460000)) {
                return back()->with('error', "La sumatoria de todos los rubros de viáticos no debe superar el valor de $4.460.000");
            }
        } else if ($proyecto->lineaProgramatica->codigo == 69) {
            if (PresupuestoValidationTrait::viaticosValidation($proyecto, $convocatoriaPresupuesto, null, $request->valor_total, 10000000)) {
                return back()->with('error', "La sumatoria de todos los rubros de viáticos no debe superar el valor de $10.000.000");
            }
        }

        /**
         * Línea 66
         */
        if ($proyecto->lineaProgramatica->codigo == 66) {
            if (PresupuestoValidationTrait::serviciosEspecialesConstruccionValidation($proyecto, $convocatoriaPresupuesto, null, $request->valor_total)) {
                return back()->with('error', "Este estudio de mercado supera el 5% del total del rubro 'Maquinaria industrial'. Vuelva a diligenciar.");
            }

            if (PresupuestoValidationTrait::serviciosMantenimientoValidation($proyecto, $convocatoriaPresupuesto, null, $request->valor_total)) {
                $porcentajeProyecto = $proyecto->getPrecioProyectoAttribute() * 0.05;
                return back()->with('error', "Este estudio de mercado supera el 5% ($ {$porcentajeProyecto}) del COP total del proyecto. Vuelva a diligenciar.");
            }
        }

        /**
         * Línea 23
         */
        if ($proyecto->lineaProgramatica->codigo == 23) {
            if (PresupuestoValidationTrait::adecuacionesYContruccionesValidation($proyecto, $convocatoriaPresupuesto, null, $request->valor_total)) {
                return back()->with('error', "Antes de diligenciar información sobre este rubro de 'Adecuaciones y construcciones' tenga en cuenta que el total NO debe superar el valor de 100 salarios mínimos.");
            }

            if (PresupuestoValidationTrait::serviciosMantenimientoValidation($proyecto, $convocatoriaPresupuesto, null, $request->valor_total)) {
                $porcentajeProyecto = $proyecto->getPrecioProyectoAttribute() * 0.05;
                return back()->with('error', "Este estudio de mercado supera el 5% ($ {$porcentajeProyecto}) del COP total del proyecto. Vuelva a diligenciar.");
            }
        }

        /**
         * Línea 69
         */
        if ($proyecto->lineaProgramatica->codigo == 69) {
            if (PresupuestoValidationTrait::primerReglaTp($proyecto, $convocatoriaPresupuesto, null, $request->valor_total)) {
                return back()->with('error', "La sumatoria de rubros adecuaciones y construcciones, equipo de sistemas, mantenimiento de maquinaria y equipo, transporte y sofware, maquinaria industrial, otras compras de equipos, software no debe superar los $250.000.000.");
            }

            if (PresupuestoValidationTrait::segundaReglaTp($proyecto, $convocatoriaPresupuesto, null, $request->valor_total)) {
                return back()->with('error', "La sumatoria del rubro materiales para la formación profesional no debe superar los $120.000.000.");
            }
        }

        /**
         * Línea 70
         */
        if ($proyecto->lineaProgramatica->codigo == 70) {
            if (PresupuestoValidationTrait::bienestarAlumnos($proyecto, $convocatoriaPresupuesto, null, $request->valor_total)) {
                return back()->with('error', "La sumatoria del rubro bienestar alumnos no debe superar los $" . $proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->max_valor_bienestar_alumnos);
            }

            if (PresupuestoValidationTrait::viaticosInterior($proyecto, $convocatoriaPresupuesto, null, $request->valor_total)) {
                return back()->with('error', "La sumatoria del rubro viaticos y gastos de viaje al interior formacion profesional no debe superar los $" . $proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->max_valor_viaticos_interior);
            }

            if (PresupuestoValidationTrait::materialesFormacion($proyecto, $convocatoriaPresupuesto, null, $request->valor_total)) {
                return back()->with('error', "La sumatoria del rubro materiales para la formación profesional no debe superar los $" . $proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->max_valor_materiales_formacion);
            }

            if (PresupuestoValidationTrait::mantenimientoEquipos($proyecto, $convocatoriaPresupuesto, null, $request->valor_total)) {
                return back()->with('error', "La sumatoria de los rubros equipo de sistemas, maquinaria industrial, otras compras de equipos, adecuaciones y construccione, mantenimiento de maquinaria, equipo, transporte y sofware no debe superar los $" . $proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->max_valor_mantenimiento_equipos);
            }

            $valorMaxEdt = $proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->max_valor_edt;
            if (PresupuestoValidationTrait::edt($proyecto, $convocatoriaPresupuesto, null, $request->valor_total, $valorMaxEdt)) {
                return back()->with('error', "La sumatoria del EDT no debe superar los $" . $valorMaxEdt);
            }
        }

        $presupuesto = new ProyectoPresupuesto();
        $presupuesto->descripcion      = $request->descripcion;
        $presupuesto->justificacion    = $request->justificacion;
        $presupuesto->valor_total      = $request->valor_total;

        if ($request->hasFile('formato_estudio_mercado')) {
            $nombreArchivo = $this->cleanFileName($proyecto->codigo, $convocatoriaPresupuesto->presupuestoSennova->usoPresupuestal, $request->formato_estudio_mercado);
            $archivo = $request->formato_estudio_mercado->storeAs(
                'estudios-mercado',
                $nombreArchivo
            );

            $presupuesto->formato_estudio_mercado = $archivo;
        }

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

        return redirect()->route('convocatorias.proyectos.presupuesto.soportes.index', [$convocatoria, $proyecto, $presupuesto])->with('success', 'El recurso se ha creado correctamente.');
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

        $presupuesto->load('proyectoPresupuestosEvaluaciones.evaluacion');

        $presupuesto->softwareInfo;
        $presupuesto->servicioEdicionInfo;
        $presupuesto->convocatoriaPresupuesto->presupuestoSennova->usoPresupuestal;
        $proyecto->lineaProgramatica;

        return Inertia::render('Convocatorias/Proyectos/ProyectoPresupuesto/Edit', [
            'convocatoria'              => $convocatoria->only('id', 'fase_formateada', 'mostrar_recomendaciones'),
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

        if ($proyecto->lineaProgramatica->codigo != 69 && $proyecto->lineaProgramatica->codigo != 70) {
            if (PresupuestoValidationTrait::viaticosValidation($proyecto, $convocatoriaPresupuesto, $presupuesto, $request->valor_total, 4460000)) {
                return back()->with('error', "La sumatoria de todos los rubros de viáticos no debe superar el valor de $4.460.000");
            }
        } else if ($proyecto->lineaProgramatica->codigo == 69) {
            if (PresupuestoValidationTrait::viaticosValidation($proyecto, $convocatoriaPresupuesto, $presupuesto, $request->valor_total, 10000000)) {
                return back()->with('error', "La sumatoria de todos los rubros de viáticos no debe superar el valor de $10.000.000");
            }
        }

        /**
         * Línea 66
         */
        if ($proyecto->lineaProgramatica->codigo == 66) {
            if (PresupuestoValidationTrait::serviciosEspecialesConstruccionValidation($proyecto, $convocatoriaPresupuesto, $presupuesto, $request->valor_total)) {
                return back()->with('error', "Este estudio de mercado supera el 5% del total del rubro 'Maquinaria industrial'. Vuelva a diligenciar.");
            }

            if (PresupuestoValidationTrait::serviciosMantenimientoValidation($proyecto, $convocatoriaPresupuesto, $presupuesto, $request->valor_total)) {
                $porcentajeProyecto = $proyecto->getPrecioProyectoAttribute() * 0.05;
                return back()->with('error', "Este estudio de mercado supera el 5% del ($ {$porcentajeProyecto}) COP total del proyecto. Vuelva a diligenciar.");
            }
        }

        /**
         * Línea 23
         */
        if ($proyecto->lineaProgramatica->codigo == 23) {
            if (PresupuestoValidationTrait::adecuacionesYContruccionesValidation($proyecto, $convocatoriaPresupuesto, $presupuesto, $request->valor_total)) {
                return back()->with('error', "Antes de diligenciar información sobre este rubro de 'Adecuaciones y construcciones' tenga en cuenta que el total NO debe superar el valor de 100 salarios mínimos.");
            }

            if (PresupuestoValidationTrait::serviciosMantenimientoValidation($proyecto, $convocatoriaPresupuesto, null, $request->valor_total)) {
                $porcentajeProyecto = $proyecto->getPrecioProyectoAttribute() * 0.05;
                return back()->with('error', "Este estudio de mercado supera el 5% ($ {$porcentajeProyecto}) del COP total del proyecto. Vuelva a diligenciar.");
            }
        }

        /**
         * Línea 69
         */
        if ($proyecto->lineaProgramatica->codigo == 69) {
            if (PresupuestoValidationTrait::primerReglaTp($proyecto, $convocatoriaPresupuesto, $presupuesto, $request->valor_total)) {
                return back()->with('error', "La sumatoria de los rubros: adecuaciones y construcciones, equipo de sistemas, mantenimiento de maquinaria y equipo, transporte y sofware, maquinaria industrial, otras compras de equipos, software no debe superar los $250.000.000.");
            }

            if (PresupuestoValidationTrait::segundaReglaTp($proyecto, $convocatoriaPresupuesto, $presupuesto, $request->valor_total)) {
                return back()->with('error', "La sumatoria del rubro materiales para la formación profesional no debe superar los $120.000.000.");
            }
        }

        /**
         * Línea 70
         */
        if ($proyecto->lineaProgramatica->codigo == 70) {
            if (PresupuestoValidationTrait::bienestarAlumnos($proyecto, $convocatoriaPresupuesto, $presupuesto, $request->valor_total)) {
                return back()->with('error', "La sumatoria del rubro bienestar alumnos no debe superar los $" . $proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->max_valor_bienestar_alumnos);
            }

            if (PresupuestoValidationTrait::viaticosInterior($proyecto, $convocatoriaPresupuesto, $presupuesto, $request->valor_total)) {
                return back()->with('error', "La sumatoria del rubro viaticos y gastos de viaje al interior formacion profesional no debe superar los $" . $proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->max_valor_viaticos_interior);
            }

            if (PresupuestoValidationTrait::materialesFormacion($proyecto, $convocatoriaPresupuesto, $presupuesto, $request->valor_total)) {
                return back()->with('error', "La sumatoria del rubro materiales para la formación profesional no debe superar los $" . $proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->max_valor_materiales_formacion);
            }

            if (PresupuestoValidationTrait::mantenimientoEquipos($proyecto, $convocatoriaPresupuesto, $presupuesto, $request->valor_total)) {
                return back()->with('error', "La sumatoria de los rubros equipo de sistemas, maquinaria industrial, otras compras de equipos, adecuaciones y construccione, mantenimiento de maquinaria, equipo, transporte y sofware no debe superar los $" . $proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->max_valor_mantenimiento_equipos);
            }

            $valorMaxEdt = $proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->max_valor_edt;
            if (PresupuestoValidationTrait::edt($proyecto, $convocatoriaPresupuesto, $presupuesto, $request->valor_total, $valorMaxEdt)) {
                return back()->with('error', "La sumatoria del EDT no debe superar los $" . $valorMaxEdt);
            }
        }

        if ($convocatoriaPresupuesto->presupuestoSennova->requiere_estudio_mercado == false) {
            foreach ($presupuesto->soportesEstudioMercado as $soporte) {
                Storage::delete($soporte->soporte);
                $soporte->delete();
            }
        }

        $presupuesto->descripcion      = $request->descripcion;
        $presupuesto->justificacion    = $request->justificacion;
        $presupuesto->valor_total      = $request->valor_total;

        if ($request->hasFile('formato_estudio_mercado')) {
            $nombreArchivo = $this->cleanFileName($proyecto->codigo, $convocatoriaPresupuesto->presupuestoSennova->usoPresupuestal, $request->formato_estudio_mercado);
            Storage::delete($presupuesto->formato_estudio_mercado);
            $archivo = $request->formato_estudio_mercado->storeAs(
                'estudios-mercado',
                $nombreArchivo
            );

            $presupuesto->formato_estudio_mercado = $archivo;
        }

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

        return back()->with('success', 'El recurso se ha actualizado correctamente.');
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

    /**
     * download
     *
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @param  mixed $presupuesto
     * @return void
     */
    public function download(Convocatoria $convocatoria, Proyecto $proyecto, ProyectoPresupuesto $presupuesto)
    {
        return response()->download(storage_path("app/$presupuesto->formato_estudio_mercado"));
    }

    /**
     * download
     *
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @param  mixed $presupuesto
     * @return void
     */
    public function downloadFormatoEstudioMercado(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        return response()->download(storage_path("app/anexos/formatoGuia4EstudioMercado.xlsx"));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function proyectoPresupuestoEvaluacion(Convocatoria $convocatoria, Evaluacion $evaluacion)
    {
        $this->authorize('visualizar-evaluacion-autor', $evaluacion);

        $evaluacion->proyecto->codigo_linea_programatica                = $evaluacion->proyecto->lineaProgramatica->codigo;
        $evaluacion->proyecto->total_maquinaria_industrial              = PresupuestoValidationTrait::totalSegundoGrupoPresupuestalProyecto($evaluacion->proyecto, '2040115');
        $evaluacion->proyecto->total_viaticos                           = PresupuestoValidationTrait::totalSegundoGrupoPresupuestalProyecto($evaluacion->proyecto, '2042186') + PresupuestoValidationTrait::totalSegundoGrupoPresupuestalProyecto($evaluacion->proyecto, '2041102');
        $evaluacion->proyecto->total_mantenimiento_maquinaria           = PresupuestoValidationTrait::totalSegundoGrupoPresupuestalProyecto($evaluacion->proyecto, '2040516');
        $evaluacion->proyecto->total_servicios_especiales_construccion  = PresupuestoValidationTrait::totalSegundoGrupoPresupuestalProyecto($evaluacion->proyecto, '2045110');
        $evaluacion->proyecto->total_equipo_sistemas                    = PresupuestoValidationTrait::totalSegundoGrupoPresupuestalProyecto($evaluacion->proyecto, '2040106');
        $evaluacion->proyecto->otras_compras_equipos                    = PresupuestoValidationTrait::totalSegundoGrupoPresupuestalProyecto($evaluacion->proyecto, '2040125');
        $evaluacion->proyecto->software                                 = PresupuestoValidationTrait::totalSegundoGrupoPresupuestalProyecto($evaluacion->proyecto, '2040108');
        $evaluacion->proyecto->viaticos_exterior                        = PresupuestoValidationTrait::totalSegundoGrupoPresupuestalProyecto($evaluacion->proyecto, '2041104');
        $evaluacion->proyecto->viaticos_interior                        = PresupuestoValidationTrait::totalSegundoGrupoPresupuestalProyecto($evaluacion->proyecto, '2041102');

        $salarioMinimo = json_decode(Storage::get('json/salario-minimo.json'), true);
        $evaluacion->proyecto->salarios_minimos = ($salarioMinimo['value'] * 100);

        if ($evaluacion->proyecto->codigo_linea_programatica == 70) {
            $evaluacion->proyecto->max_valor_materiales_formacion   = $evaluacion->proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->max_valor_materiales_formacion;
            $evaluacion->proyecto->max_valor_bienestar_alumnos      = $evaluacion->proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->max_valor_bienestar_alumnos;
            $evaluacion->proyecto->max_valor_viaticos_interior      = $evaluacion->proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->max_valor_viaticos_interior;
            $evaluacion->proyecto->max_valor_edt                    = $evaluacion->proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->max_valor_edt;
            $evaluacion->proyecto->max_valor_mantenimiento_equipos  = $evaluacion->proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->max_valor_mantenimiento_equipos;
            $evaluacion->proyecto->max_valor_proyecto               = $evaluacion->proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->suma_max_valores + $evaluacion->proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->max_valor_materiales_formacion + $evaluacion->proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->max_valor_bienestar_alumnos;
        }

        return Inertia::render('Convocatorias/Evaluaciones/ProyectoPresupuesto/Index', [
            'convocatoria'              => $convocatoria->only('id', 'fase_formateada'),
            'evaluacion'                => $evaluacion->only('id'),
            'proyecto'                  => $evaluacion->proyecto->only('id', 'codigo_linea_programatica', 'precio_proyecto', 'finalizado', 'codigo', 'diff_meses', 'total_proyecto_presupuesto', 'total_maquinaria_industrial', 'total_servicios_especiales_construccion', 'total_viaticos', 'total_mantenimiento_maquinaria', 'max_valor_materiales_formacion', 'max_valor_bienestar_alumnos', 'max_valor_viaticos_interior', 'max_valor_edt', 'max_valor_mantenimiento_equipos', 'max_valor_proyecto', 'salarios_minimos'),
            'filters'                   => request()->all('search', 'presupuestos'),
            'proyectoPresupuesto'       => ProyectoPresupuesto::select('proyecto_presupuesto.id', 'proyecto_presupuesto.convocatoria_presupuesto_id', 'proyecto_presupuesto.proyecto_id', 'proyecto_presupuesto.valor_total')->where('proyecto_id', $evaluacion->proyecto->id)->filterProyectoPresupuesto(request()->only('search', 'presupuestos'))->with('convocatoriaPresupuesto.presupuestoSennova.tercerGrupoPresupuestal:id,nombre', 'convocatoriaPresupuesto.presupuestoSennova.segundoGrupoPresupuestal:id,nombre,codigo', 'convocatoriaPresupuesto.presupuestoSennova.usoPresupuestal:id,descripcion', 'proyectoPresupuestosEvaluaciones')->paginate()->appends(['search' => request()->search, 'presupuestos' => request()->presupuestos]),
            'segundoGrupoPresupuestal'  => SegundoGrupoPresupuestal::orderBy('nombre', 'ASC')->get('nombre'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProyectoPresupuesto  $proyectoRolSennova
     * @return \Illuminate\Http\Response
     */
    public function evaluacionForm(Convocatoria $convocatoria, Evaluacion $evaluacion, ProyectoPresupuesto $presupuesto)
    {
        $this->authorize('visualizar-evaluacion-autor', $evaluacion);

        $presupuesto->softwareInfo;
        $presupuesto->servicioEdicionInfo;
        $presupuesto->convocatoriaPresupuesto->presupuestoSennova->usoPresupuestal;
        $evaluacion->proyecto->lineaProgramatica;
        $proyecto = $evaluacion->proyecto;

        return Inertia::render('Convocatorias/Evaluaciones/ProyectoPresupuesto/Edit', [
            'convocatoria'                  => $convocatoria->only('id', 'fase_formateada'),
            'evaluacion'                    => $evaluacion->only('id', 'iniciado', 'finalizado', 'habilitado', 'modificable'),
            'segundaEvaluacion'             => ProyectoPresupuestoEvaluacion::whereHas('evaluacion', function ($query) use ($proyecto) {
                $query->where('evaluaciones.proyecto_id', $proyecto->id)->where('evaluaciones.habilitado', true);
            })->where('proyecto_presupuesto_evaluacion.evaluacion_id', '!=', $evaluacion->id)->first(),
            'proyecto'                      => $evaluacion->proyecto,
            'proyectoPresupuesto'           => $presupuesto,
            'tiposLicencia'                 => json_decode(Storage::get('json/tipos-licencia-software.json'), true),
            'opcionesServiciosEdicion'      => json_decode(Storage::get('json/opciones-servicios-edicion.json'), true),
            'tiposSoftware'                 => json_decode(Storage::get('json/tipos-software.json'), true),
            'proyectoPresupuestoEvaluacion' => ProyectoPresupuestoEvaluacion::where('evaluacion_id', $evaluacion->id)->where('proyecto_presupuesto_id', $presupuesto->id)->first()
        ]);
    }

    public function updateEvaluacion(Request $request, Convocatoria $convocatoria, Evaluacion $evaluacion, ProyectoPresupuesto $presupuesto)
    {
        $this->authorize('modificar-evaluacion-autor', $evaluacion);

        ProyectoPresupuestoEvaluacion::updateOrCreate(
            ['evaluacion_id' => $evaluacion->id, 'proyecto_presupuesto_id' => $presupuesto->id],
            ['correcto' => $request->correcto, 'comentario' => $request->correcto == false ? $request->comentario : null]
        );

        return back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * cleanFileName
     *
     * @param  mixed $nombre
     * @return void
     */
    public function cleanFileName($codigoProyecto, $nombre, $archivo)
    {
        $cleanName = str_replace(' ', '', substr($nombre, 0, 30));
        $cleanName = preg_replace('/[-`~!@#_$%\^&*()+={}[\]\\\\|;:\'",.><?\/]/', '', $cleanName);

        $cleanProyectoCodigo = str_replace(' ', '', substr($codigoProyecto, 0, 30));
        $cleanProyectoCodigo = preg_replace('/[-`~!@#_$%\^&*()+={}[\]\\\\|;:\'",.><?\/]/', '', $cleanProyectoCodigo);

        $random    = Str::random(10);

        return str_replace(array("\r", "\n"), '', "{$cleanProyectoCodigo}cod{$random}." . $archivo->extension());
    }
}
