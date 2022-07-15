<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Http\Requests\SemilleroInvestigacionRequest;
use App\Models\GrupoInvestigacion;
use App\Models\LineaInvestigacion;
use App\Models\ProgramaFormacion;
use App\Models\RedConocimiento;
use App\Models\SemilleroInvestigacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Illuminate\Support\Str;

class SemilleroInvestigacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(GrupoInvestigacion $grupoInvestigacion)
    {
        $this->authorize('viewAny', [SemilleroInvestigacion::class]);

        return Inertia::render('SemillerosInvestigacion/Index', [
            'filters'                   => request()->all('search'),
            'allowedToCreate'           => Gate::inspect('create', [SemilleroInvestigacion::class])->allowed(),
            'grupoInvestigacion'        => $grupoInvestigacion,
            'semillerosInvestigacion'   => SemilleroInvestigacion::select('semilleros_investigacion.id', 'semilleros_investigacion.nombre', 'semilleros_investigacion.codigo', 'semilleros_investigacion.linea_investigacion_id', 'lineas_investigacion.nombre as nombre_linea_principal')
                ->join('lineas_investigacion', 'semilleros_investigacion.linea_investigacion_id', 'lineas_investigacion.id')
                ->where('lineas_investigacion.grupo_investigacion_id', $grupoInvestigacion->id)
                ->filterSemilleroInvestigacion(request()->only('search'))
                ->orderBy('semilleros_investigacion.nombre', 'ASC')->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(GrupoInvestigacion $grupoInvestigacion)
    {
        $this->authorize('create', [SemilleroInvestigacion::class]);

        return Inertia::render('SemillerosInvestigacion/Create', [
            'grupoInvestigacion'    => $grupoInvestigacion,
            'lineasInvestigacion'   => LineaInvestigacion::select('id as value', 'nombre as label')->where('lineas_investigacion.grupo_investigacion_id', $grupoInvestigacion->id)->get(),
            'redesConocimiento'     => RedConocimiento::select('id as value', 'nombre as label')->get('id'),
            'programasFormacion'    => ProgramaFormacion::selectRaw('programas_formacion.id as value, CONCAT(programas_formacion.nombre, chr(10), \'∙ Código: \', programas_formacion.codigo) as label, linea_investigacion_programa_formacion.linea_investigacion_id as linea_investigacion_id')->join('linea_investigacion_programa_formacion', 'programas_formacion.id', 'linea_investigacion_programa_formacion.programa_formacion_id')->orderBy('programas_formacion.nombre', 'ASC')->get(),
            'allowedToCreate'       => Gate::inspect('create', [SemilleroInvestigacion::class])->allowed()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SemilleroInvestigacionRequest $request, GrupoInvestigacion $grupoInvestigacion)
    {
        $this->authorize('create', [SemilleroInvestigacion::class]);

        $semilleroInvestigacion = new SemilleroInvestigacion();
        $semilleroInvestigacion->nombre                                     = $request->nombre;
        $semilleroInvestigacion->fecha_creacion_semillero                   = $request->fecha_creacion_semillero;
        $semilleroInvestigacion->nombre_lider_semillero                     = $request->nombre_lider_semillero;
        $semilleroInvestigacion->email_contacto                             = $request->email_contacto;
        $semilleroInvestigacion->reconocimientos_semillero_investigacion    = $request->reconocimientos_semillero_investigacion;
        $semilleroInvestigacion->vision                                     = $request->vision;
        $semilleroInvestigacion->mision                                     = $request->mision;
        $semilleroInvestigacion->objetivo_general                           = $request->objetivo_general;
        $semilleroInvestigacion->objetivos_especificos                      = $request->objetivos_especificos;
        $semilleroInvestigacion->link_semillero                             = $request->link_semillero;
        $semilleroInvestigacion->es_semillero_tecnoacademia                 = $request->es_semillero_tecnoacademia;

        $semilleroInvestigacion->lineaInvestigacion()->associate($request->linea_investigacion_id);

        if ($semilleroInvestigacion->save()) {

            AppHelper::checkFolderAndCreate($semilleroInvestigacion->ruta_final_sharepoint);

            $semilleroInvestigacion->update(['codigo' => 'SGPS-SEM-' . $semilleroInvestigacion->id]);

            $semilleroInvestigacion->redesConocimiento()->attach($request->redes_conocimiento);
            $semilleroInvestigacion->programasFormacion()->attach($request->programas_formacion);
            $semilleroInvestigacion->lineasInvestigacionArticulados()->attach($request->lineas_investigacion);

            return redirect()->route('grupos-investigacion.semilleros-investigacion.edit', [$grupoInvestigacion, $semilleroInvestigacion])->with('success', 'El recurso se ha creado correctamente.');
        } else {
            abort(500, 'No se ha podido crear el semillero de investigación.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SemilleroInvestigacion  $semilleroInvestigacion
     * @return \Illuminate\Http\Response
     */
    public function show(GrupoInvestigacion $grupoInvestigacion, SemilleroInvestigacion $semilleroInvestigacion)
    {
        $this->authorize('view', [SemilleroInvestigacion::class, $semilleroInvestigacion]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SemilleroInvestigacion  $semilleroInvestigacion
     * @return \Illuminate\Http\Response
     */
    public function edit(GrupoInvestigacion $grupoInvestigacion, SemilleroInvestigacion $semilleroInvestigacion)
    {
        $this->authorize('view', [SemilleroInvestigacion::class, $semilleroInvestigacion]);

        $semilleroInvestigacion->lineaInvestigacion->grupoInvestigacion;

        return Inertia::render('SemillerosInvestigacion/Edit', [
            'semilleroInvestigacion'                    => $semilleroInvestigacion,
            'grupoInvestigacion'                        => $grupoInvestigacion,
            'lineasInvestigacion'                       => LineaInvestigacion::select('id as value', 'nombre as label')->where('lineas_investigacion.grupo_investigacion_id', $grupoInvestigacion->id)->get(),
            'redesConocimiento'                         => RedConocimiento::select('id as value', 'nombre as label')->get('id'),
            'programasFormacion'                        => ProgramaFormacion::selectRaw('programas_formacion.id as value, CONCAT(programas_formacion.nombre, chr(10), \'∙ Código: \', programas_formacion.codigo) as label, linea_investigacion_programa_formacion.linea_investigacion_id as linea_investigacion_id')->join('linea_investigacion_programa_formacion', 'programas_formacion.id', 'linea_investigacion_programa_formacion.programa_formacion_id')->orderBy('programas_formacion.nombre', 'ASC')->get(),
            'redesConocimientoSemilleroInvestigacion'   => $semilleroInvestigacion->redesConocimiento()->select('redes_conocimiento.id as value', 'redes_conocimiento.nombre as label')->get(),
            'programasFormacionSemilleroInvestigacion'  => $semilleroInvestigacion->programasFormacion()->select('programas_formacion.id as value', 'programas_formacion.nombre as label')->get(),
            'lineasInvestigacionSemilleroInvestigacion' => $semilleroInvestigacion->lineasInvestigacionArticulados()->select('lineas_investigacion.id as value', 'lineas_investigacion.nombre as label')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SemilleroInvestigacion  $semilleroInvestigacion
     * @return \Illuminate\Http\Response
     */
    public function update(SemilleroInvestigacionRequest $request, GrupoInvestigacion $grupoInvestigacion, SemilleroInvestigacion $semilleroInvestigacion)
    {
        $this->authorize('update', [SemilleroInvestigacion::class, $semilleroInvestigacion]);

        $semilleroInvestigacion->nombre                                     = $request->nombre;
        $semilleroInvestigacion->fecha_creacion_semillero                   = $request->fecha_creacion_semillero;
        $semilleroInvestigacion->nombre_lider_semillero                     = $request->nombre_lider_semillero;
        $semilleroInvestigacion->email_contacto                             = $request->email_contacto;
        $semilleroInvestigacion->reconocimientos_semillero_investigacion    = $request->reconocimientos_semillero_investigacion;
        $semilleroInvestigacion->vision                                     = $request->vision;
        $semilleroInvestigacion->mision                                     = $request->mision;
        $semilleroInvestigacion->objetivo_general                           = $request->objetivo_general;
        $semilleroInvestigacion->objetivos_especificos                      = $request->objetivos_especificos;
        $semilleroInvestigacion->link_semillero                             = $request->link_semillero;
        $semilleroInvestigacion->es_semillero_tecnoacademia                 = $request->es_semillero_tecnoacademia;

        $semilleroInvestigacion->lineaInvestigacion()->associate($request->linea_investigacion_id);

        if ($semilleroInvestigacion->save()) {
            $semilleroInvestigacion->redesConocimiento()->sync($request->redes_conocimiento);
            $semilleroInvestigacion->programasFormacion()->sync($request->programas_formacion);
            $semilleroInvestigacion->lineasInvestigacionArticulados()->sync($request->lineas_investigacion);

            return back()->with('success', 'El recurso se ha actualizado correctamente.');
        } else {
            abort(500, 'No se ha podido modificar el semillero de investigación.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SemilleroInvestigacion  $semilleroInvestigacion
     * @return \Illuminate\Http\Response
     */
    public function destroy(GrupoInvestigacion $grupoInvestigacion, SemilleroInvestigacion $semilleroInvestigacion)
    {
        $this->authorize('delete', [SemilleroInvestigacion::class, $semilleroInvestigacion]);

        $semilleroInvestigacion->delete();

        return redirect()->route('grupos-investigacion.semilleros-investigacion.index', [$grupoInvestigacion])->with('success', 'El recurso se ha eliminado correctamente.');
    }

    /**
     * cleanFileName
     *
     * @param  mixed $nombre
     * @return void
     */
    public function cleanFileName($nombre, $archivo)
    {
        $cleanName = str_replace(' ', '', substr($nombre, 0, 30));
        $cleanName = preg_replace('/[-`~!@#_$%\^&*()+={}[\]\\\\|;:\'",.><?\/]/', '', $cleanName);
        $random    = Str::random(10);

        return str_replace(array("\r", "\n"), '', "{$nombre}{$random}." . $archivo->extension());
    }

    public function saveFilesSharepoint(Request $request, GrupoInvestigacion $grupoInvestigacion, SemilleroInvestigacion $semilleroInvestigacion)
    {
        $request->validate([
            'formato_gic_f_021'         => 'nullable|file|max:10240',
            'formato_gic_f_032'         => 'nullable|file|max:10240',
            'formato_aval_semillero'    => 'nullable|file|max:10240',
        ]);

        $status = AppHelper::checkFolderAndCreate($semilleroInvestigacion->ruta_final_sharepoint);
        $success = null;

        if ($request->hasFile('formato_gic_f_021') && $status == true) {
            $fileNameFormatoGICF021 = AppHelper::cleanFileName($request->nombre . 'formato_gic_f_021', $request->formato_gic_f_021);
            AppHelper::deleteFile($semilleroInvestigacion->formato_gic_f_021);

            $fileNameFormatoGICF021 = AppHelper::uploadFile($semilleroInvestigacion->ruta_final_sharepoint, $request->formato_gic_f_021, $fileNameFormatoGICF021);

            $semilleroInvestigacion->update(['formato_gic_f_021' => $fileNameFormatoGICF021]);

            $success = true;
        }

        if ($request->hasFile('formato_gic_f_032') && $status == true) {
            $fileNameFormatoGICF032 = AppHelper::cleanFileName($request->nombre . 'formato_gic_f_032', $request->formato_gic_f_032);
            AppHelper::deleteFile($semilleroInvestigacion->formato_gic_f_032);

            $fileNameFormatoGICF032 = AppHelper::uploadFile($semilleroInvestigacion->ruta_final_sharepoint, $request->formato_gic_f_032, $fileNameFormatoGICF032);

            $semilleroInvestigacion->update(['formato_gic_f_032' => $fileNameFormatoGICF032]);

            $success = true;
        }

        if ($request->hasFile('formato_aval_semillero') && $status == true) {
            $fileNameFormatoAvalSemillero = AppHelper::cleanFileName($request->nombre . 'formato_aval_semillero', $request->formato_aval_semillero);
            AppHelper::deleteFile($semilleroInvestigacion->formato_aval_semillero);

            $fileNameFormatoAvalSemillero = AppHelper::uploadFile($semilleroInvestigacion->ruta_final_sharepoint, $request->formato_aval_semillero, $fileNameFormatoAvalSemillero);

            $semilleroInvestigacion->update(['formato_aval_semillero' => $fileNameFormatoAvalSemillero]);

            $success = true;
        }

        if ($success) {
            return back()->with('success', 'Los archivos se han cargado correctamente');
        } else {
            return back()->with('error', 'No se han podido cargar los archivos. Por favor vuelva a intentar');
        }
    }

    /**
     * descargarFormato
     *
     * @param  mixed $request
     * @param  mixed $grupoInvestigacion
     * @param  mixed $semilleroInvestigacion
     * @return void
     */
    public function descargarFormato(Request $request, GrupoInvestigacion $grupoInvestigacion, SemilleroInvestigacion $semilleroInvestigacion)
    {
        if ($request->formato == 'formato_gic_f_021') {
            $ruta = $semilleroInvestigacion->formato_gic_f_021;
        } else if ($request->formato == 'formato_gic_f_032') {
            $ruta = $semilleroInvestigacion->formato_gic_f_032;
        } else if ($request->formato == 'formato_aval_semillero') {
            $ruta = $semilleroInvestigacion->formato_aval_semillero;
        }

        return response()->download(storage_path("app/$ruta"));
    }

    public function downloadFileSharepoint(GrupoInvestigacion $grupoInvestigacion, SemilleroInvestigacion $semilleroInvestigacion, $tipoArchivo)
    {
        $fileName = '';

        $fileName = $semilleroInvestigacion->filename($tipoArchivo);

        if ($fileName) {
            AppHelper::downloadFile($semilleroInvestigacion->ruta_final_sharepoint, $fileName);
        } else {
            return redirect()->route('grupos-investigacion.semilleros-investigacion.edit', [$grupoInvestigacion, $semilleroInvestigacion])->with('error', 'No se encontró el archivo.');
        }
    }
}
