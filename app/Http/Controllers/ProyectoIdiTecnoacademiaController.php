<?php

namespace App\Http\Controllers;

use App\Helpers\AppHelper;
use App\Models\ProyectoIdiTecnoacademia;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProyectoIdiTecnoacademiaProductoRequest;
use App\Http\Requests\ProyectoIdiTecnoacademiaRequest;
use App\Models\ProgramaSennova;
use App\Models\Proyecto;
use App\Models\ProyectoIdiTecnoacademiaProducto;
use App\Models\Regional;
use App\Models\SemilleroInvestigacion;
use App\Models\Tecnoacademia;
use App\Models\TipoBeneficiadoTa;
use App\Models\TipoProductoIdi;
use App\Models\User;
use App\Rules\Email;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProyectoIdiTecnoacademiaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('crear-proyecto-idi-tecnoacademia');

        return Inertia::render('ProyectosIdiTecnoacademia/Index', [
            'filters'                   => request()->all('search'),
            'proyectosIdiTecnoacademia' => ProyectoIdiTecnoacademia::getProyectosPorRol()->appends(['search' => request()->search]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('crear-proyecto-idi-tecnoacademia');

        return Inertia::render('ProyectosIdiTecnoacademia/Create', [
            'tecnoacademias'                    => Tecnoacademia::select('id as value', 'nombre as label')->orderBy('nombre', 'ASC')->get(),
            'programasSennova'                  => ProgramaSennova::select('id as value', 'nombre as label')->orderBy('nombre', 'ASC')->get(),
            'semillerosInvestigacion'           => SemilleroInvestigacion::selectRaw("semilleros_investigacion.id as value, CONCAT(semilleros_investigacion.nombre, chr(10), '- Grupo de investigación: ', grupos_investigacion.nombre, chr(10), '- Centro de formación: ', centros_formacion.nombre) as label")->join('lineas_investigacion', 'semilleros_investigacion.linea_investigacion_id', 'lineas_investigacion.id')->join('grupos_investigacion', 'lineas_investigacion.grupo_investigacion_id', 'grupos_investigacion.id')->join('centros_formacion', 'grupos_investigacion.centro_formacion_id', 'centros_formacion.id')->orderBy('semilleros_investigacion.nombre', 'ASC')->get(),
            'estadosProyectoIdiTecnoacademia'   => json_decode(Storage::get('json/estados-proyecto-idi-tecnoacademia.json'), true),
            'beneficiados'                      => TipoBeneficiadoTa::select('id as value', 'nombre as label')->orderBy('nombre', 'ASC')->get(),
            'roles'                             => json_decode(Storage::get('json/roles-sennova-ta.json'), true),
            'proyectos'                         => Proyecto::selectRaw("id as value, CONCAT('SGPS-', id + 8000) as label")->orderBy('id', 'ASC')->get(),
            'regionales'                        => Regional::select('id as value', 'nombre as label', 'codigo')->orderBy('nombre')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProyectoIdiTecnoacademiaRequest $request)
    {
        $this->authorize('crear-proyecto-idi-tecnoacademia');

        $proyectoIdiTecnoacademia = new ProyectoIdiTecnoacademia();
        $proyectoIdiTecnoacademia->titulo                               = $request->titulo;
        $proyectoIdiTecnoacademia->fecha_inicio                         = $request->fecha_inicio;
        $proyectoIdiTecnoacademia->fecha_finalizacion                   = $request->fecha_finalizacion;
        $proyectoIdiTecnoacademia->resumen                              = $request->resumen;
        $proyectoIdiTecnoacademia->palabras_clave                       = $request->palabras_clave;
        $proyectoIdiTecnoacademia->especies                             = $request->especies;
        $proyectoIdiTecnoacademia->tiene_linea_investigacion            = $request->tiene_linea_investigacion;
        $proyectoIdiTecnoacademia->lineas_investigacion                 = $request->lineas_investigacion;
        $proyectoIdiTecnoacademia->proyecto_nuevo                       = $request->proyecto_nuevo;
        $proyectoIdiTecnoacademia->proyecto_con_continuidad             = $request->proyecto_con_continuidad;
        $proyectoIdiTecnoacademia->productos_premios                    = $request->productos_premios;
        $proyectoIdiTecnoacademia->texto_exposicion                     = $request->texto_exposicion;
        $proyectoIdiTecnoacademia->resultados_obtenidos                 = $request->resultados_obtenidos;
        $proyectoIdiTecnoacademia->observaciones_resultados             = $request->observaciones_resultados;
        $proyectoIdiTecnoacademia->nombre_aprendices_vinculados         = $request->nombre_aprendices_vinculados;
        $proyectoIdiTecnoacademia->nombre_instituciones_educativas      = $request->nombre_instituciones_educativas;
        $proyectoIdiTecnoacademia->nuevas_instituciones_educativas      = $request->nuevas_instituciones_educativas;
        $proyectoIdiTecnoacademia->programa_formacion_articulado_media  = $request->programa_formacion_articulado_media;
        $proyectoIdiTecnoacademia->entidades_vinculadas                 = $request->entidades_vinculadas;
        $proyectoIdiTecnoacademia->fuente_recursos                      = $request->fuente_recursos;
        $proyectoIdiTecnoacademia->presupuesto                          = $request->presupuesto;
        $proyectoIdiTecnoacademia->hace_parte_de_semillero              = $request->hace_parte_de_semillero;
        $proyectoIdiTecnoacademia->estado_proyecto                      = $request->estado_proyecto;
        $proyectoIdiTecnoacademia->poblacion_beneficiada                = $request->poblacion_beneficiada;
        $proyectoIdiTecnoacademia->otra_poblacion_beneficiada           = $request->otra_poblacion_beneficiada;
        $proyectoIdiTecnoacademia->nombre_centro_programa               = $request->nombre_centro_programa;
        $proyectoIdiTecnoacademia->documentos_resultados                = '';
        $proyectoIdiTecnoacademia->pdf_proyecto                         = '';

        if ($request->proyecto_id) {
            $proyectoIdiTecnoacademia->proyecto()->associate($request->proyecto_id);
        }
        $proyectoIdiTecnoacademia->tecnoacademia()->associate($request->tecnoacademia_id);
        $proyectoIdiTecnoacademia->semilleroInvestigacion()->associate($request->semillero_investigacion_id);

        if ($proyectoIdiTecnoacademia->save()) {

            // Crear las carpetas y subcarpetas del proyecto | CALDAS - 9220 CENTRO DE PROCESOS INDUSTRIALES Y CONSTRUCCION/TECNOACADEMIA CALDAS/IDITA-00022
            AppHelper::createFolder($proyectoIdiTecnoacademia->tecnoacademia->centroFormacion->nombre_carpeta_sharepoint . '/' . mb_strtoupper($proyectoIdiTecnoacademia->tecnoacademia->nombre) . '/' . $proyectoIdiTecnoacademia->codigo);

            if ($request->hasFile('pdf_proyecto')) {
                // Nombre del archivo
                $fileName = AppHelper::cleanFileName($proyectoIdiTecnoacademia->codigo . '-pdf-proyecto', $request->pdf_proyecto);

                // Subir el archvio a la carpeta anteriormente creada - Ej: CALDAS - 9220 CENTRO DE PROCESOS INDUSTRIALES Y CONSTRUCCION/TECNOACADEMIA CALDAS/IDITA-00022/proyectoIDITA-00022sGhff.pdf
                $fileName = AppHelper::uploadFile($proyectoIdiTecnoacademia->tecnoacademia->centroFormacion->nombre_carpeta_sharepoint . '/' . mb_strtoupper($proyectoIdiTecnoacademia->tecnoacademia->nombre) . '/' . $proyectoIdiTecnoacademia->codigo, $request->pdf_proyecto, $fileName);

                // Actualiza la ruta del pdf en la db
                $proyectoIdiTecnoacademia->update(['pdf_proyecto' => $fileName]);
            }

            if ($request->hasFile('documentos_resultados')) {
                // Nombre del archivo
                $fileName = AppHelper::cleanFileName($proyectoIdiTecnoacademia->codigo . '-pdf-proyecto', $request->documentos_resultados);

                // Subir el archvio a la carpeta anteriormente creada - Ej: CALDAS - 9220 CENTRO DE PROCESOS INDUSTRIALES Y CONSTRUCCION/TECNOACADEMIA CALDAS/IDITA-00022/proyectoIDITA-00022sGhff.pdf
                $fileName = AppHelper::uploadFile($proyectoIdiTecnoacademia->tecnoacademia->centroFormacion->nombre_carpeta_sharepoint . '/' . mb_strtoupper($proyectoIdiTecnoacademia->tecnoacademia->nombre) . '/' . $proyectoIdiTecnoacademia->codigo, $request->documentos_resultados, $fileName);

                // Actualiza la ruta del pdf en la db
                $proyectoIdiTecnoacademia->update(['documentos_resultados' => $fileName]);
            }

            $proyectoIdiTecnoacademia->municipios()->attach($request->municipios);
            $proyectoIdiTecnoacademia->programasSennova()->attach($request->programa_sennova_participante);
            $proyectoIdiTecnoacademia->beneficiados()->attach($request->beneficiados);
            $proyectoIdiTecnoacademia->lineas()->attach($request->tecnoacademia_linea_tecnoacademia_id);

            $proyectoIdiTecnoacademia->participantes()->attach(auth()->user()->id, ['rol_sennova' => $request->rol_sennova['value'], 'cantidad_meses' => $request->cantidad_meses, 'cantidad_horas' => $request->cantidad_horas, 'autor_principal' => true]);
        }

        return redirect()->route('proyectos-idi-tecnoacademia.participantes.index', $proyectoIdiTecnoacademia)->with('success', 'El recurso se ha creado correctamente.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProyectoIdiTecnoacademia  $proyectoIdiTecnoacademia
     * @return \Illuminate\Http\Response
     */
    public function show(ProyectoIdiTecnoacademia $proyectoIdiTecnoacademia)
    {
        $this->authorize('view', [ProyectoIdiTecnoacademia::class, $proyectoIdiTecnoacademia]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProyectoIdiTecnoacademia  $proyectoIdiTecnoacademia
     * @return \Illuminate\Http\Response
     */
    public function edit(ProyectoIdiTecnoacademia $proyectoIdiTecnoacademia)
    {
        $this->authorize('modificar-proyecto-idi-tecnoacademia', [$proyectoIdiTecnoacademia]);

        return Inertia::render('ProyectosIdiTecnoacademia/Edit', [
            'proyectoIdiTecnoacademia'          => $proyectoIdiTecnoacademia,
            'tecnoacademias'                    => Tecnoacademia::select('id as value', 'nombre as label')->orderBy('nombre', 'ASC')->get(),
            'programasSennova'                  => ProgramaSennova::select('id as value', 'nombre as label')->orderBy('nombre', 'ASC')->get(),
            'semillerosInvestigacion'           => SemilleroInvestigacion::selectRaw("semilleros_investigacion.id as value, CONCAT(semilleros_investigacion.nombre, chr(10), '- Grupo de investigación: ', grupos_investigacion.nombre, chr(10), '- Centro de formación: ', centros_formacion.nombre) as label")->join('lineas_investigacion', 'semilleros_investigacion.linea_investigacion_id', 'lineas_investigacion.id')->join('grupos_investigacion', 'lineas_investigacion.grupo_investigacion_id', 'grupos_investigacion.id')->join('centros_formacion', 'grupos_investigacion.centro_formacion_id', 'centros_formacion.id')->orderBy('semilleros_investigacion.nombre', 'ASC')->get(),
            'estadosProyectoIdiTecnoacademia'   => json_decode(Storage::get('json/estados-proyecto-idi-tecnoacademia.json'), true),
            'beneficiados'                      => TipoBeneficiadoTa::select('id as value', 'nombre as label')->orderBy('nombre', 'ASC')->get(),
            'lineasRelacionadas'                => $proyectoIdiTecnoacademia->lineas()->select('tecnoacademia_linea_tecnoacademia.id as value', 'lineas_tecnoacademia.nombre')->join('lineas_tecnoacademia', 'tecnoacademia_linea_tecnoacademia.linea_tecnoacademia_id', 'lineas_tecnoacademia.id')->get(),
            'programasRelacionados'             => $proyectoIdiTecnoacademia->programasSennova()->select('programas_sennova.id as value', 'programas_sennova.nombre as label')->get(),
            'beneficiadosRelacionados'          => $proyectoIdiTecnoacademia->beneficiados()->select('tipos_beneficiados_ta.id as value', 'tipos_beneficiados_ta.nombre as label')->get(),
            'municipiosRelacionados'            => $proyectoIdiTecnoacademia->municipios()->select('municipios.id as value', 'municipios.nombre as label', 'regionales.nombre as group', 'regionales.codigo')->join('regionales', 'regionales.id', 'municipios.regional_id')->get(),
            'roles'                             => json_decode(Storage::get('json/roles-sennova-ta.json'), true),
            'autorPrincipal'                    => $proyectoIdiTecnoacademia->participantes()->where('proyecto_idi_tecnoacademia_participante.autor_principal', true)->first(),
            'proyectos'                         => Proyecto::selectRaw("id as value, id + 8000 as label")->orderBy('id', 'ASC')->get(),
            'regionales'                        => Regional::select('id as value', 'nombre as label', 'codigo')->orderBy('nombre')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProyectoIdiTecnoacademia  $proyectoIdiTecnoacademia
     * @return \Illuminate\Http\Response
     */
    public function update(ProyectoIdiTecnoacademiaRequest $request, ProyectoIdiTecnoacademia $proyectoIdiTecnoacademia)
    {
        $this->authorize('modificar-proyecto-idi-tecnoacademia', [$proyectoIdiTecnoacademia]);

        $proyectoIdiTecnoacademia->titulo                               = $request->titulo;
        $proyectoIdiTecnoacademia->fecha_inicio                         = $request->fecha_inicio;
        $proyectoIdiTecnoacademia->fecha_finalizacion                   = $request->fecha_finalizacion;
        $proyectoIdiTecnoacademia->resumen                              = $request->resumen;
        $proyectoIdiTecnoacademia->palabras_clave                       = $request->palabras_clave;
        $proyectoIdiTecnoacademia->especies                             = $request->especies;
        $proyectoIdiTecnoacademia->tiene_linea_investigacion            = $request->tiene_linea_investigacion;
        $proyectoIdiTecnoacademia->lineas_investigacion                 = $request->lineas_investigacion;
        $proyectoIdiTecnoacademia->proyecto_nuevo                       = $request->proyecto_nuevo;
        $proyectoIdiTecnoacademia->proyecto_con_continuidad             = $request->proyecto_con_continuidad;
        $proyectoIdiTecnoacademia->productos_premios                    = $request->productos_premios;
        $proyectoIdiTecnoacademia->texto_exposicion                     = $request->texto_exposicion;
        $proyectoIdiTecnoacademia->resultados_obtenidos                 = $request->resultados_obtenidos;
        $proyectoIdiTecnoacademia->observaciones_resultados             = $request->observaciones_resultados;
        $proyectoIdiTecnoacademia->nombre_aprendices_vinculados         = $request->nombre_aprendices_vinculados;
        $proyectoIdiTecnoacademia->nombre_instituciones_educativas      = $request->nombre_instituciones_educativas;
        $proyectoIdiTecnoacademia->nuevas_instituciones_educativas      = $request->nuevas_instituciones_educativas;
        $proyectoIdiTecnoacademia->programa_formacion_articulado_media  = $request->programa_formacion_articulado_media;
        $proyectoIdiTecnoacademia->entidades_vinculadas                 = $request->entidades_vinculadas;
        $proyectoIdiTecnoacademia->fuente_recursos                      = $request->fuente_recursos;
        $proyectoIdiTecnoacademia->presupuesto                          = $request->presupuesto;
        $proyectoIdiTecnoacademia->hace_parte_de_semillero              = $request->hace_parte_de_semillero;
        $proyectoIdiTecnoacademia->estado_proyecto                      = $request->estado_proyecto;
        $proyectoIdiTecnoacademia->poblacion_beneficiada                = $request->poblacion_beneficiada;
        $proyectoIdiTecnoacademia->otra_poblacion_beneficiada           = $request->otra_poblacion_beneficiada;
        $proyectoIdiTecnoacademia->nombre_centro_programa               = $request->nombre_centro_programa;

        if ($request->hasFile('pdf_proyecto')) {
            $fileNamePdfProyecto = AppHelper::cleanFileName($proyectoIdiTecnoacademia->codigo . '-pdf-proyecto', $request->pdf_proyecto);
            AppHelper::deleteFile($proyectoIdiTecnoacademia->pdf_proyecto);
            $fileNamePdfProyecto = AppHelper::uploadFile($proyectoIdiTecnoacademia->tecnoacademia->centroFormacion->nombre_carpeta_sharepoint . '/' . mb_strtoupper($proyectoIdiTecnoacademia->tecnoacademia->nombre) . '/' . $proyectoIdiTecnoacademia->codigo, $request->pdf_proyecto, $fileNamePdfProyecto);
            $proyectoIdiTecnoacademia->pdf_proyecto = $fileNamePdfProyecto;
        }

        if ($request->hasFile('documentos_resultados')) {
            $fileNameDocumentoResultados = AppHelper::cleanFileName($proyectoIdiTecnoacademia->codigo . '-documento-resultados', $request->documentos_resultados);
            AppHelper::deleteFile($proyectoIdiTecnoacademia->documentos_resultados);
            $fileNameDocumentoResultados = AppHelper::uploadFile($proyectoIdiTecnoacademia->tecnoacademia->centroFormacion->nombre_carpeta_sharepoint . '/' . mb_strtoupper($proyectoIdiTecnoacademia->tecnoacademia->nombre) . '/' . $proyectoIdiTecnoacademia->codigo, $request->documentos_resultados, $fileNameDocumentoResultados);
            $proyectoIdiTecnoacademia->documentos_resultados = $fileNameDocumentoResultados;
        }

        if ($request->proyecto_id) {
            $proyectoIdiTecnoacademia->proyecto()->associate($request->proyecto_id);
        }

        $proyectoIdiTecnoacademia->tecnoacademia()->associate($request->tecnoacademia_id);
        $proyectoIdiTecnoacademia->semilleroInvestigacion()->associate($request->semillero_investigacion_id);

        $proyectoIdiTecnoacademia->save();

        $proyectoIdiTecnoacademia->municipios()->sync($request->municipios);
        $proyectoIdiTecnoacademia->programasSennova()->sync($request->programa_sennova_participante);
        $proyectoIdiTecnoacademia->beneficiados()->sync($request->beneficiados);
        $proyectoIdiTecnoacademia->lineas()->sync($request->tecnoacademia_linea_tecnoacademia_id);

        $autorPrincipal = $proyectoIdiTecnoacademia->participantes()->where('proyecto_idi_tecnoacademia_participante.autor_principal', true)->first();
        if ($autorPrincipal) {
            $proyectoIdiTecnoacademia->participantes()->updateExistingPivot($autorPrincipal->id, ['rol_sennova' => $request->rol_sennova['value'], 'cantidad_meses' => $request->cantidad_meses, 'cantidad_horas' => $request->cantidad_horas]);
        }

        return redirect()->route('proyectos-idi-tecnoacademia.participantes.index', $proyectoIdiTecnoacademia)->with('success', 'Por favor continue asociando los participantes.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProyectoIdiTecnoacademia  $proyectoIdiTecnoacademia
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProyectoIdiTecnoacademia $proyectoIdiTecnoacademia)
    {
        $this->authorize('modificar-proyecto-idi-tecnoacademia', [$proyectoIdiTecnoacademia]);

        $proyectoIdiTecnoacademia->delete();

        return redirect()->route('proyectos-idi-tecnoacademia.index')->with('success', 'El recurso se ha eliminado correctamente.');
    }

    /**
     * indexParticipantes
     *
     * @param  mixed $proyectoIdiTecnoacademia
     * @return void
     */
    public function indexParticipantes(ProyectoIdiTecnoacademia $proyectoIdiTecnoacademia)
    {
        $this->authorize('modificar-proyecto-idi-tecnoacademia', [$proyectoIdiTecnoacademia]);

        $proyectoIdiTecnoacademia->participantes;
        $proyectoIdiTecnoacademia->load('participantes.centroFormacion.regional');

        return Inertia::render('ProyectosIdiTecnoacademia/Participantes/Index', [
            'proyectoIdiTecnoacademia'    => $proyectoIdiTecnoacademia,
            'tiposDocumento'              => json_decode(Storage::get('json/tipos-documento.json'), true),
            'tiposVinculacion'            => json_decode(Storage::get('json/tipos-vinculacion.json'), true),
            'roles'                       => json_decode(Storage::get('json/roles-sennova-ta.json'), true),
        ]);
    }

    /**
     * filterParticipantes
     *
     * @param  mixed $request
     * @param  mixed $proyecto
     * @return void
     */
    public function filterParticipantes(Request $request, ProyectoIdiTecnoacademia $proyectoIdiTecnoacademia)
    {
        if (!empty($request->search_participante)) {
            $query = User::orderBy('users.nombre', 'ASC')
                ->filterUser(['search' => $request->search_participante])
                ->with('centroFormacion.regional')->take(6);

            if ($proyectoIdiTecnoacademia->participantes->count() > 0) {
                $query->whereNotIn('users.id', explode(',', $proyectoIdiTecnoacademia->participantes->implode('id', ',')));
            }

            $users = $query->get()->take(5);

            return $users->makeHidden('can', 'roles', 'user_name', 'permissions')->toJson();
        }

        return null;
    }

    /**
     * linkParticipante
     *
     * @param  mixed $request
     * @param  mixed $proyectoIdiTecnoacademia
     * @return void
     */
    public function linkParticipante(Request $request, ProyectoIdiTecnoacademia $proyectoIdiTecnoacademia)
    {
        $this->authorize('modificar-proyecto-idi-tecnoacademia', [$proyectoIdiTecnoacademia]);

        if ($proyectoIdiTecnoacademia->participantes()->where('proyecto_idi_tecnoacademia_participante.id', $request->user_id)->exists()) {
            return back()->with('error', 'El recurso ya está vinculado.');
        }

        $proyectoIdiTecnoacademia->participantes()->attach($request->user_id, ['rol_sennova' => $request->rol_sennova['value'], 'cantidad_meses' => $request->cantidad_meses, 'cantidad_horas' => $request->cantidad_horas]);
        return back()->with('success', 'El recurso se ha vinculado correctamente.');
    }

    /**
     * unlinkParticipante 
     *
     * @param  mixed $request
     * @param  mixed $proyectoIdiTecnoacademia
     * @return void
     */
    public function unlinkParticipante(Request $request, ProyectoIdiTecnoacademia $proyectoIdiTecnoacademia)
    {
        $this->authorize('modificar-proyecto-idi-tecnoacademia', [$proyectoIdiTecnoacademia]);

        $request->validate(['user_id' => 'required']);

        if ($proyectoIdiTecnoacademia->participantes()->where('proyecto_idi_tecnoacademia_participante.user_id', $request->user_id)->exists()) {
            $proyectoIdiTecnoacademia->participantes()->detach($request->user_id);
            return back()->with('success', 'El recurso se ha desvinculado correctamente.');
        }
        return back()->with('success', 'El recurso ya está desvinculado.');
    }


    public function updateParticipante(Request $request, ProyectoIdiTecnoacademia $proyectoIdiTecnoacademia)
    {
        $this->authorize('modificar-proyecto-idi-tecnoacademia', [$proyectoIdiTecnoacademia]);

        if ($proyectoIdiTecnoacademia->participantes()->where('users.id', $request->user_id)->exists()) {
            $proyectoIdiTecnoacademia->participantes()->updateExistingPivot($request->user_id, ['rol_sennova' => $request->rol_sennova['value'], 'cantidad_meses' => $request->cantidad_meses, 'cantidad_horas' => $request->cantidad_horas]);
            return back()->with('success', 'El recurso se ha actualizado correctamente.');
        }
        return back()->with('error', 'El recurso ya está desvinculado.');
    }

    /**
     * registerParticipante
     *
     * @param  mixed $request
     * @param  mixed $proyectoIdiTecnoacademia
     * @return void
     */
    public function registerParticipante(Request $request, ProyectoIdiTecnoacademia $proyectoIdiTecnoacademia)
    {
        $this->authorize('modificar-proyecto-idi-tecnoacademia', [$proyectoIdiTecnoacademia]);

        $request->validate(
            [
                'centro_formacion_id'   => 'required', 'min:0', 'max:2147483647', 'integer', 'exists:centros_formacion,id',
                'nombre'                => 'required', 'max:255', 'string',
                'email'                 => 'required', 'max:255', new Email, 'unique:users,email', 'email',
                'tipo_documento'        => 'required', 'max:2',
                'numero_documento'      => 'required', 'min:55555', 'unique:users,numero_documento', 'max:9999999999999', 'integer',
                'numero_celular'        => 'required', 'min:3000000000', 'max:9999999999', 'integer',
                'tipo_vinculacion'      => 'required', 'max:191',
                'autorizacion_datos'    => 'required', 'boolean'
            ]
        );

        $user = new User();

        $user->nombre               = $request->nombre;
        $user->email                = $request->email;
        $user->password             = $user::makePassword($request->numero_documento);
        $user->tipo_documento       = $request->tipo_documento['value'];
        $user->numero_documento     = $request->numero_documento;
        $user->numero_celular       = $request->numero_celular;
        $user->habilitado           = 0;
        $user->tipo_vinculacion     = $request->tipo_vinculacion['value'];
        $user->autorizacion_datos   = $request->autorizacion_datos;
        $user->centroFormacion()->associate($request->centro_formacion_id);

        $user->save();

        $user->assignRole(14);

        $data['user_id']        = $user->id;
        $data['rol_sennova']    = $request->rol_sennova;
        $data['cantidad_meses'] = $request->cantidad_meses;
        $data['cantidad_horas'] = $request->cantidad_horas;

        return $this->linkParticipante(new Request($data), $proyectoIdiTecnoacademia);
    }

    /**
     * indexProductos
     *
     * @param  mixed $proyectoIdiTecnoacademia
     * @return void
     */
    public function indexProductos(ProyectoIdiTecnoacademia $proyectoIdiTecnoacademia)
    {
        $this->authorize('modificar-proyecto-idi-tecnoacademia', [$proyectoIdiTecnoacademia]);

        return Inertia::render('ProyectosIdiTecnoacademia/Productos/Index', [
            'proyectoIdiTecnoacademia'    => $proyectoIdiTecnoacademia,
            'productos'                   => ProyectoIdiTecnoacademiaProducto::where('proyecto_idi_tecnoacademia_id', $proyectoIdiTecnoacademia->id)->with('tipoProductoIdi')->paginate(15)
        ]);
    }

    public function createProducto(ProyectoIdiTecnoacademia $proyectoIdiTecnoacademia)
    {
        $this->authorize('modificar-proyecto-idi-tecnoacademia', [$proyectoIdiTecnoacademia]);

        return Inertia::render('ProyectosIdiTecnoacademia/Productos/Create', [
            'proyectoIdiTecnoacademia' => $proyectoIdiTecnoacademia,
            'tiposProductos'           => TipoProductoIdi::select('id as value', 'tipo as label')->get(),
            'estadosProductos'         => json_decode(Storage::get('json/estados-productos-ta.json'), true),
        ]);
    }

    public function storeProducto(ProyectoIdiTecnoacademiaProductoRequest $request, ProyectoIdiTecnoacademia $proyectoIdiTecnoacademia)
    {
        $this->authorize('modificar-proyecto-idi-tecnoacademia', [$proyectoIdiTecnoacademia]);

        $producto = new ProyectoIdiTecnoacademiaProducto();

        $producto->descripcion          = $request->descripcion;
        $producto->estado               = $request->estado;
        $producto->soporte              = $request->soporte;
        $producto->link                 = $request->link;
        $producto->lugar                = $request->lugar;
        $producto->fecha_realizacion    = $request->fecha_realizacion;
        $producto->tipoProductoIdi()->associate($request->tipo_producto_idi_id);
        $producto->proyectoIdiTecnoacademia()->associate($proyectoIdiTecnoacademia);

        $producto->save();

        return redirect()->route('proyectos-idi-tecnoacademia.productos.index', $proyectoIdiTecnoacademia)->with('success', 'El recurso se ha creado correctamente.');
    }

    public function editProducto(ProyectoIdiTecnoacademia $proyectoIdiTecnoacademia, ProyectoIdiTecnoacademiaProducto $producto)
    {
        $this->authorize('modificar-producto-idi-tecnoacademia', [$proyectoIdiTecnoacademia, $producto]);

        $producto->resultado;

        return Inertia::render('ProyectosIdiTecnoacademia/Productos/Edit', [
            'proyectoIdiTecnoacademia'  => $proyectoIdiTecnoacademia,
            'producto'                  => $producto,
            'tiposProductos'            => TipoProductoIdi::select('id as value', 'tipo as label')->get(),
            'estadosProductos'          => json_decode(Storage::get('json/estados-productos-ta.json'), true),
        ]);
    }

    public function updateProducto(ProyectoIdiTecnoacademiaProductoRequest $request, ProyectoIdiTecnoacademia $proyectoIdiTecnoacademia, ProyectoIdiTecnoacademiaProducto $producto)
    {
        $this->authorize('modificar-producto-idi-tecnoacademia', [$proyectoIdiTecnoacademia, $producto]);

        $producto->descripcion          = $request->descripcion;
        $producto->estado               = $request->estado;
        $producto->soporte              = $request->soporte;

        $producto->link                 = $request->link;
        $producto->lugar                = $request->lugar;
        $producto->fecha_realizacion    = $request->fecha_realizacion;
        $producto->tipoProductoIdi()->associate($request->tipo_producto_idi_id);
        $producto->proyectoIdiTecnoacademia()->associate($proyectoIdiTecnoacademia);
        $producto->save();

        return redirect()->back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    public function destroyProducto(ProyectoIdiTecnoacademia $proyectoIdiTecnoacademia, ProyectoIdiTecnoacademiaProducto $producto)
    {
        $this->authorize('modificar-producto-idi-tecnoacademia', [$proyectoIdiTecnoacademia, $producto]);

        $producto->delete();

        return redirect()->route('proyectos-idi-tecnoacademia.productos.index', $proyectoIdiTecnoacademia)->with('success', 'El recurso se ha eliminado correctamente.');
    }

    public function descargarSoportesProducto(ProyectoIdiTecnoacademia $proyectoIdiTecnoacademia, ProyectoIdiTecnoacademiaProducto $producto)
    {
        return response()->download(storage_path("app/" . $producto->soporte));
    }

    public function downloadFiles(ProyectoIdiTecnoacademia $proyectoIdiTecnoacademia, $tipoArchivo)
    {
        $fileName = '';

        $fileName = $proyectoIdiTecnoacademia->filename($tipoArchivo);

        if ($fileName) {
            AppHelper::downloadFile($proyectoIdiTecnoacademia->tecnoacademia->centroFormacion->nombre_carpeta_sharepoint . '/' . mb_strtoupper($proyectoIdiTecnoacademia->tecnoacademia->nombre) . '/' . $proyectoIdiTecnoacademia->codigo, $fileName);
        } else {
            return back();
        }
    }
}
