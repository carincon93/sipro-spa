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
            $proyecto->max_valor_materiales_formacion   = $proyecto->max_material_formacion;
            $proyecto->max_valor_bienestar_alumnos      = $proyecto->max_bienestar_aprendiz;
            $proyecto->max_valor_viaticos_interior      = $proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->max_valor_viaticos_interior;
            $proyecto->max_valor_edt                    = $proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->max_valor_edt;
            $proyecto->max_valor_mantenimiento_equipos  = $proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->max_valor_mantenimiento_equipos;
            $proyecto->max_valor_proyecto               = $proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->suma_max_valores + $proyecto->max_material_formacion + $proyecto->max_bienestar_aprendiz;
        }

        return Inertia::render('Convocatorias/Proyectos/ProyectoPresupuesto/Index', [
            'convocatoria'              => $convocatoria->only('id'),
            'proyecto'                  => $proyecto->only('id', 'codigo_linea_programatica', 'precio_proyecto', 'modificable', 'codigo', 'diff_meses', 'total_proyecto_presupuesto', 'total_maquinaria_industrial', 'total_servicios_especiales_construccion', 'total_viaticos', 'total_mantenimiento_maquinaria', 'max_valor_materiales_formacion', 'max_valor_bienestar_alumnos', 'max_valor_viaticos_interior', 'max_valor_edt', 'max_valor_mantenimiento_equipos', 'max_valor_proyecto', 'salarios_minimos'),
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

        if ($proyecto->lineaProgramatica->codigo != 69 && $proyecto->lineaProgramatica->codigo != 70) {
            if (PresupuestoValidationTrait::viaticosValidation($proyecto, $convocatoriaPresupuesto, null, $request->valor_total, 4460000)) {
                return redirect()->back()->with('error', "La sumatoria de todos los rubros de viáticos no debe superar el valor de $4.460.000");
            }
        } else if ($proyecto->lineaProgramatica->codigo == 69) {
            if (PresupuestoValidationTrait::viaticosValidation($proyecto, $convocatoriaPresupuesto, null, $request->valor_total, 10000000)) {
                return redirect()->back()->with('error', "La sumatoria de todos los rubros de viáticos no debe superar el valor de $10.000.000");
            }
        }

        if ($proyecto->lineaProgramatica->codigo == 70 && PresupuestoValidationTrait::bienestarAlumnos($proyecto, $convocatoriaPresupuesto, null, $request->valor_total)) {
            return redirect()->back()->with('error', "La sumatoria del rubro bienestar alumnos no debe superar los $" . $proyecto->ta->max_bienestar_aprendiz);
        }

        if ($proyecto->lineaProgramatica->codigo == 70 && PresupuestoValidationTrait::viaticosInterior($proyecto, $convocatoriaPresupuesto, null, $request->valor_total)) {
            return redirect()->back()->with('error', "La sumatoria del rubro viaticos y gastos de viaje al interior formacion profesional no debe superar los $" . $proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->max_valor_viaticos_interior);
        }

        /**
         * Línea 66
         */
        if ($proyecto->lineaProgramatica->codigo == 66) {
            if (PresupuestoValidationTrait::serviciosEspecialesConstruccionValidation($proyecto, $convocatoriaPresupuesto, null, $request->valor_total)) {
                return redirect()->back()->with('error', "Este estudio de mercado supera el 5% del total del rubro 'Maquinaria industrial'. Vuelva a diligenciar.");
            }

            if (PresupuestoValidationTrait::serviciosMantenimientoValidation($proyecto, $convocatoriaPresupuesto, null, $request->valor_total)) {
                $porcentajeProyecto = $proyecto->getPrecioProyectoAttribute() * 0.05;
                return redirect()->back()->with('error', "Este estudio de mercado supera el 5% ($ {$porcentajeProyecto}) del COP total del proyecto. Vuelva a diligenciar.");
            }
        }

        /**
         * Línea 23
         */
        if ($proyecto->lineaProgramatica->codigo == 23) {
            if (PresupuestoValidationTrait::adecuacionesYContruccionesValidation($proyecto, $convocatoriaPresupuesto, null, $request->valor_total)) {
                return redirect()->back()->with('error', "Antes de diligenciar información sobre este rubro de 'Adecuaciones y construcciones' tenga en cuenta que el total NO debe superar el valor de 100 salarios mínimos.");
            }

            if (PresupuestoValidationTrait::serviciosMantenimientoValidation($proyecto, $convocatoriaPresupuesto, null, $request->valor_total)) {
                $porcentajeProyecto = $proyecto->getPrecioProyectoAttribute() * 0.05;
                return redirect()->back()->with('error', "Este estudio de mercado supera el 5% ($ {$porcentajeProyecto}) del COP total del proyecto. Vuelva a diligenciar.");
            }
        }

        /**
         * Línea 69
         */
        if ($proyecto->lineaProgramatica->codigo == 69) {
            if (PresupuestoValidationTrait::primerReglaTp($proyecto, $convocatoriaPresupuesto, null, $request->valor_total)) {
                return redirect()->back()->with('error', "La sumatoria de rubros adecuaciones y construcciones, equipo de sistemas, mantenimiento de maquinaria y equipo, transporte y sofware, maquinaria industrial, otras compras de equipos, software no debe superar los $200.000.000.");
            }

            if (PresupuestoValidationTrait::segundaReglaTp($proyecto, $convocatoriaPresupuesto, null, $request->valor_total)) {
                return redirect()->back()->with('error', "La sumatoria del rubro materiales para la formación profesional no debe superar los $120.000.000.");
            }
        }

        /**
         * Línea 70
         */
        if ($proyecto->lineaProgramatica->codigo == 70) {
            if (PresupuestoValidationTrait::materialesFormacion($proyecto, $convocatoriaPresupuesto, null, $request->valor_total)) {
                return redirect()->back()->with('error', "La sumatoria del rubro materiales para la formación profesional no debe superar los $" . $proyecto->max_material_formacion);
            }

            if (PresupuestoValidationTrait::mantenimientoEquipos($proyecto, $convocatoriaPresupuesto, null, $request->valor_total)) {
                return redirect()->back()->with('error', "La sumatoria del rubro viaticos y gastos de viaje al interior formacion profesional no debe superar los $" . $proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->max_valor_mantenimiento_equipos);
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

        if ($proyecto->lineaProgramatica->codigo != 69 && $proyecto->lineaProgramatica->codigo != 70) {
            if (PresupuestoValidationTrait::viaticosValidation($proyecto, $convocatoriaPresupuesto, $presupuesto, $request->valor_total, 4460000)) {
                return redirect()->back()->with('error', "La sumatoria de todos los rubros de viáticos no debe superar el valor de $4.460.000");
            }
        } else if ($proyecto->lineaProgramatica->codigo == 69) {
            if (PresupuestoValidationTrait::viaticosValidation($proyecto, $convocatoriaPresupuesto, $presupuesto, $request->valor_total, 10000000)) {
                return redirect()->back()->with('error', "La sumatoria de todos los rubros de viáticos no debe superar el valor de $10.000.000");
            }
        }

        if ($proyecto->lineaProgramatica->codigo == 70  && PresupuestoValidationTrait::bienestarAlumnos($proyecto, $convocatoriaPresupuesto, $presupuesto, $request->valor_total)) {
            return redirect()->back()->with('error', "La sumatoria del rubro bienestar alumnos no debe superar los $" . $proyecto->ta->max_bienestar_aprendiz);
        }

        if ($proyecto->lineaProgramatica->codigo == 70 && PresupuestoValidationTrait::viaticosInterior($proyecto, $convocatoriaPresupuesto, $presupuesto, $request->valor_total)) {
            return redirect()->back()->with('error', "La sumatoria del rubro viaticos y gastos de viaje al interior formacion profesional no debe superar los $" . $proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->max_valor_viaticos_interior);
        }

        /**
         * Línea 66
         */
        if ($proyecto->lineaProgramatica->codigo == 66) {
            if (PresupuestoValidationTrait::serviciosEspecialesConstruccionValidation($proyecto, $convocatoriaPresupuesto, $presupuesto, $request->valor_total)) {
                return redirect()->back()->with('error', "Este estudio de mercado supera el 5% del total del rubro 'Maquinaria industrial'. Vuelva a diligenciar.");
            }

            if (PresupuestoValidationTrait::serviciosMantenimientoValidation($proyecto, $convocatoriaPresupuesto, $presupuesto, $request->valor_total)) {
                $porcentajeProyecto = $proyecto->getPrecioProyectoAttribute() * 0.05;
                return redirect()->back()->with('error', "Este estudio de mercado supera el 5% del ($ {$porcentajeProyecto}) COP total del proyecto. Vuelva a diligenciar.");
            }
        }

        /**
         * Línea 23
         */
        if ($proyecto->lineaProgramatica->codigo == 23) {
            if (PresupuestoValidationTrait::adecuacionesYContruccionesValidation($proyecto, $convocatoriaPresupuesto, $presupuesto, $request->valor_total)) {
                return redirect()->back()->with('error', "Antes de diligenciar información sobre este rubro de 'Adecuaciones y construcciones' tenga en cuenta que el total NO debe superar el valor de 100 salarios mínimos.");
            }

            if (PresupuestoValidationTrait::serviciosMantenimientoValidation($proyecto, $convocatoriaPresupuesto, null, $request->valor_total)) {
                $porcentajeProyecto = $proyecto->getPrecioProyectoAttribute() * 0.05;
                return redirect()->back()->with('error', "Este estudio de mercado supera el 5% ($ {$porcentajeProyecto}) del COP total del proyecto. Vuelva a diligenciar.");
            }
        }

        /**
         * Línea 69
         */
        if ($proyecto->lineaProgramatica->codigo == 69) {
            if (PresupuestoValidationTrait::primerReglaTp($proyecto, $convocatoriaPresupuesto, $presupuesto, $request->valor_total)) {
                return redirect()->back()->with('error', "La sumatoria de los rubros: adecuaciones y construcciones, equipo de sistemas, mantenimiento de maquinaria y equipo, transporte y sofware, maquinaria industrial, otras compras de equipos, software no debe superar los $200.000.000.");
            }

            if (PresupuestoValidationTrait::segundaReglaTp($proyecto, $convocatoriaPresupuesto, $presupuesto, $request->valor_total)) {
                return redirect()->back()->with('error', "La sumatoria del rubro materiales para la formación profesional no debe superar los $120.000.000.");
            }
        }

        /**
         * Línea 70
         */
        if ($proyecto->lineaProgramatica->codigo == 70) {

            if (PresupuestoValidationTrait::materialesFormacion($proyecto, $convocatoriaPresupuesto, $presupuesto, $request->valor_total)) {
                return redirect()->back()->with('error', "La sumatoria del rubro materiales para la formación profesional no debe superar los $" . $proyecto->max_material_formacion);
            }

            if (PresupuestoValidationTrait::mantenimientoEquipos($proyecto, $convocatoriaPresupuesto, $presupuesto, $request->valor_total)) {
                return redirect()->back()->with('error', "La sumatoria del rubro viaticos y gastos de viaje al interior formacion profesional no debe superar los $" . $proyecto->tecnoacademiaLineasTecnoacademia()->first()->tecnoacademia->max_valor_mantenimiento_equipos);
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

        $random    = Str::random(5);

        return "{$cleanProyectoCodigo}{$cleanName}cod{$random}." . $archivo->extension();
    }
}
