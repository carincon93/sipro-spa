<?php

namespace App\Http\Controllers;

use App\Models\ProyectoCapacidadInstalada;
use App\Http\Controllers\Controller;
use App\Http\Requests\NuevoProponenteRequest;
use App\Http\Requests\ProyectoCapacidadInstaladaEntidadAliadaRequest;
use App\Http\Requests\ProyectoCapacidadInstaladaObjetivoEspecificoRequest;
use App\Http\Requests\ProyectoCapacidadInstaladaProductoRequest;
use App\Http\Requests\ProyectoCapacidadInstaladaRequest;
use App\Models\CentroFormacion;
use App\Models\ProyectoCapacidadInstaladaEntidadAliada;
use App\Models\ProyectoCapacidadInstaladaObjetivoEspecifico;
use App\Models\ProyectoCapacidadInstaladaProducto;
use App\Models\ProyectoCapacidadInstaladaResultado;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProyectoCapacidadInstaladaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('ProyectosCapacidadInstalada/Index', [
            'filters'                     => request()->all('search'),
            'proyectosCapacidadInstalada' => ProyectoCapacidadInstalada::getProyectosPorRol()->appends(['search' => request()->search]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', [ProyectoCapacidadInstalada::class]);

        $centrosFormacion = CentroFormacion::selectRaw('centros_formacion.id as value, concat(centros_formacion.nombre, chr(10), \'∙ Código: \', centros_formacion.codigo) as label')->orderBy('centros_formacion.nombre', 'ASC')->get();

        return Inertia::render('ProyectosCapacidadInstalada/Create', [
            'centrosFormacion'  => $centrosFormacion,
            'listaBeneficiados' => json_decode(Storage::get('json/proyectos-capacidad-instalada-beneficiados.json'), true),
            'roles'             => json_decode(Storage::get('json/roles-sennova-idi.json'), true),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProyectoCapacidadInstaladaRequest $request)
    {
        $this->authorize('create', [ProyectoCapacidadInstalada::class]);

        $proyectoCapacidadInstalada = new ProyectoCapacidadInstalada();

        $proyectoCapacidadInstalada->titulo             = $request->titulo;
        $proyectoCapacidadInstalada->fecha_inicio       = $request->fecha_inicio;
        $proyectoCapacidadInstalada->fecha_finalizacion = $request->fecha_finalizacion;
        $proyectoCapacidadInstalada->beneficia_a        = $request->beneficia_a;

        $proyectoCapacidadInstalada->semilleroInvestigacion()->associate($request->semillero_investigacion_id);
        $proyectoCapacidadInstalada->subtipoProyectoCapacidadInstalada()->associate($request->subtipo_proyecto_capacidad_instalada_id);
        $proyectoCapacidadInstalada->disciplinaSubareaConocimiento()->associate($request->disciplina_subarea_conocimiento_id);
        $proyectoCapacidadInstalada->redConocimiento()->associate($request->red_conocimiento_id);
        $proyectoCapacidadInstalada->actividadEconomica()->associate($request->actividad_economica_id);

        $proyectoCapacidadInstalada->save();

        $proyectoCapacidadInstalada->integrantes()->attach(auth()->user()->id, ['rol_sennova' => $request->rol_sennova['value'], 'cantidad_meses' => $request->cantidad_meses, 'cantidad_horas' => $request->cantidad_horas, 'autor_principal' => true]);

        return redirect()->route('proyectos-capacidad-instalada.edit', [$proyectoCapacidadInstalada])->with('success', 'Por favor continue diligenciado la información básica.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProyectoCapacidadInstalada  $proyectoCapacidadInstalada
     * @return \Illuminate\Http\Response
     */
    public function show(ProyectoCapacidadInstalada $proyectoCapacidadInstalada)
    {
        $this->authorize('update', [ProyectoCapacidadInstalada::class, $proyectoCapacidadInstalada]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProyectoCapacidadInstalada  $proyectoCapacidadInstalada
     * @return \Illuminate\Http\Response
     */
    public function edit(ProyectoCapacidadInstalada $proyectoCapacidadInstalada)
    {
        $this->authorize('update', [ProyectoCapacidadInstalada::class, $proyectoCapacidadInstalada]);

        $centrosFormacion = CentroFormacion::selectRaw('centros_formacion.id as value, concat(centros_formacion.nombre, chr(10), \'∙ Código: \', centros_formacion.codigo) as label')->orderBy('centros_formacion.nombre', 'ASC')->get();
        $proyectoCapacidadInstalada->semilleroInvestigacion;
        $proyectoCapacidadInstalada->semilleroInvestigacion->lineaInvestigacion;
        $proyectoCapacidadInstalada->semilleroInvestigacion->lineaInvestigacion->grupoInvestigacion;
        $proyectoCapacidadInstalada->disciplinaSubareaConocimiento->subareaConocimiento->areaConocimiento;
        $proyectoCapacidadInstalada->subtipoProyectoCapacidadInstalada;
        $proyectoCapacidadInstalada->integrantes;

        return Inertia::render('ProyectosCapacidadInstalada/Edit', [
            'proyectoCapacidadInstalada'            => $proyectoCapacidadInstalada,
            'centrosFormacion'                      => $centrosFormacion,
            'listaBeneficiados'                     => json_decode(Storage::get('json/proyectos-capacidad-instalada-beneficiados.json'), true),
            'proyectoProgramasFormacion'            => $proyectoCapacidadInstalada->programasFormacionImpactados()->selectRaw('programas_formacion.id as value, concat(programas_formacion.nombre, chr(10), \'∙ Código: \', programas_formacion.codigo) as label')->get(),
            'proyectoProgramasFormacionArticulados' => $proyectoCapacidadInstalada->programasFormacionArticulados()->selectRaw('programas_formacion_articulados.id as value, concat(programas_formacion_articulados.nombre, chr(10), \'∙ Código: \', programas_formacion_articulados.codigo) as label')->get(),
            'autorPrincipal'                        => $proyectoCapacidadInstalada->integrantes()->where('proyecto_capacidad_instalada_integrante.autor_principal', true)->first(),
            'roles'                                 => json_decode(Storage::get('json/roles-sennova-idi.json'), true),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProyectoCapacidadInstalada  $proyectoCapacidadInstalada
     * @return \Illuminate\Http\Response
     */
    public function update(ProyectoCapacidadInstaladaRequest $request, ProyectoCapacidadInstalada $proyectoCapacidadInstalada)
    {
        $this->authorize('update', [ProyectoCapacidadInstalada::class, $proyectoCapacidadInstalada]);

        $proyectoCapacidadInstalada->titulo             = $request->titulo;
        $proyectoCapacidadInstalada->fecha_inicio       = $request->fecha_inicio;
        $proyectoCapacidadInstalada->fecha_finalizacion = $request->fecha_finalizacion;
        $proyectoCapacidadInstalada->beneficia_a        = $request->beneficia_a;

        $proyectoCapacidadInstalada->semilleroInvestigacion()->associate($request->semillero_investigacion_id);
        $proyectoCapacidadInstalada->subtipoProyectoCapacidadInstalada()->associate($request->subtipo_proyecto_capacidad_instalada_id);
        $proyectoCapacidadInstalada->disciplinaSubareaConocimiento()->associate($request->disciplina_subarea_conocimiento_id);
        $proyectoCapacidadInstalada->redConocimiento()->associate($request->red_conocimiento_id);
        $proyectoCapacidadInstalada->actividadEconomica()->associate($request->actividad_economica_id);

        $proyectoCapacidadInstalada->programasFormacionImpactados()->sync($request->programas_formacion);
        $proyectoCapacidadInstalada->programasFormacionArticulados()->sync($request->programas_formacion_articulados);

        $proyectoCapacidadInstalada->save();

        $autorPrincipal = $proyectoCapacidadInstalada->integrantes()->where('proyecto_capacidad_instalada_integrante.autor_principal', true)->first();
        if ($autorPrincipal) {
            $proyectoCapacidadInstalada->integrantes()->updateExistingPivot($autorPrincipal->id, ['rol_sennova' => $request->rol_sennova['value'], 'cantidad_meses' => $request->cantidad_meses, 'cantidad_horas' => $request->cantidad_horas]);
        }

        return redirect()->route('proyectos-capacidad-instalada.integrantes.index', [$proyectoCapacidadInstalada])->with('success', 'Por favor continue asociando los integrantes.');
    }

    public function updateLongColumn(Request $request, ProyectoCapacidadInstalada $proyectoCapacidadInstalada, $column)
    {
        $proyectoCapacidadInstalada->update($request->only($column));

        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProyectoCapacidadInstalada  $proyectoCapacidadInstalada
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProyectoCapacidadInstalada $proyectoCapacidadInstalada)
    {
        $this->authorize('delete', [ProyectoCapacidadInstalada::class, $proyectoCapacidadInstalada]);

        $proyectoCapacidadInstalada->delete();

        return redirect()->route('proyectos-capacidad-instalada.index')->with('success', 'El recurso se ha eliminado correctamente.');
    }

    /**
     * indexIntegrantes
     *
     * @param  mixed $proyectoCapacidadInstalada
     * @return void
     */
    public function indexIntegrantes(ProyectoCapacidadInstalada $proyectoCapacidadInstalada)
    {
        $this->authorize('update', [ProyectoCapacidadInstalada::class, $proyectoCapacidadInstalada]);

        $proyectoCapacidadInstalada->integrantes;
        $proyectoCapacidadInstalada->entidadesAliadas;
        $proyectoCapacidadInstalada->load('integrantes.centroFormacion.regional');

        return Inertia::render('ProyectosCapacidadInstalada/Integrantes/Index', [
            'proyectoCapacidadInstalada'    => $proyectoCapacidadInstalada,
            'tiposDocumento'                => json_decode(Storage::get('json/tipos-documento.json'), true),
            'tiposVinculacion'              => json_decode(Storage::get('json/tipos-vinculacion.json'), true),
            'roles'                         => json_decode(Storage::get('json/roles-sennova-idi.json'), true),
        ]);
    }

    /**
     * filterIntegrantes
     *
     * @param  mixed $request
     * @param  mixed $proyecto
     * @return void
     */
    public function filterIntegrantes(Request $request, ProyectoCapacidadInstalada $proyectoCapacidadInstalada)
    {
        if (!empty($request->search_integrante)) {
            $query = User::orderBy('users.nombre', 'ASC')
                ->filterUser(['search' => $request->search_integrante])
                ->with('centroFormacion.regional')->take(6);

            if ($proyectoCapacidadInstalada->integrantes->count() > 0) {
                $query->whereNotIn('users.id', explode(',', $proyectoCapacidadInstalada->integrantes->implode('id', ',')));
            }

            $users = $query->get()->take(5);

            return $users->makeHidden('can', 'roles', 'user_name', 'permissions')->toJson();
        }

        return null;
    }

    /**
     * linkIntegrante
     *
     * @param  mixed $request
     * @param  mixed $proyectoCapacidadInstalada
     * @return void
     */
    public function linkIntegrante(Request $request, ProyectoCapacidadInstalada $proyectoCapacidadInstalada)
    {
        $this->authorize('update', [ProyectoCapacidadInstalada::class, $proyectoCapacidadInstalada]);

        if ($proyectoCapacidadInstalada->integrantes()->where('proyecto_capacidad_instalada_integrante.id', $request->user_id)->exists()) {
            return back()->with('error', 'El recurso ya está vinculado.');
        }

        $proyectoCapacidadInstalada->integrantes()->attach($request->user_id, ['rol_sennova' => is_array($request->rol_sennova) ? $request->rol_sennova['value'] : $request->rol_sennova, 'cantidad_meses' => $request->cantidad_meses, 'cantidad_horas' => $request->cantidad_horas]);
        return back()->with('success', 'El recurso se ha vinculado correctamente.');
    }

    /**
     * unlinkIntegrante 
     *
     * @param  mixed $request
     * @param  mixed $proyectoCapacidadInstalada
     * @return void
     */
    public function unlinkIntegrante(Request $request,  ProyectoCapacidadInstalada $proyectoCapacidadInstalada)
    {
        $this->authorize('update', [ProyectoCapacidadInstalada::class, $proyectoCapacidadInstalada]);

        $request->validate(['user_id' => 'required']);

        if ($proyectoCapacidadInstalada->integrantes()->where('proyecto_capacidad_instalada_integrante.user_id', $request->user_id)->exists()) {
            $proyectoCapacidadInstalada->integrantes()->detach($request->user_id);
            return back()->with('success', 'El recurso se ha desvinculado correctamente.');
        }
        return back()->with('success', 'El recurso ya está desvinculado.');
    }

    public function updateParticipante(Request $request, ProyectoCapacidadInstalada $proyectoCapacidadInstalada)
    {
        $this->authorize('update', [ProyectoCapacidadInstalada::class, $proyectoCapacidadInstalada]);

        if ($proyectoCapacidadInstalada->integrantes()->where('users.id', $request->user_id)->exists()) {
            $proyectoCapacidadInstalada->integrantes()->updateExistingPivot($request->user_id, ['rol_sennova' => is_array($request->rol_sennova) ? $request->rol_sennova['value'] : $request->rol_sennova, 'cantidad_meses' => $request->cantidad_meses, 'cantidad_horas' => $request->cantidad_horas]);
            return back()->with('success', 'El recurso se ha actualizado correctamente.');
        }
        return back()->with('error', 'El recurso ya está desvinculado.');
    }

    /**
     * registerIntegrante
     *
     * @param  mixed $request
     * @param  mixed $proyectoCapacidadInstalada
     * @return void
     */
    public function registerIntegrante(NuevoProponenteRequest $request, ProyectoCapacidadInstalada $proyectoCapacidadInstalada)
    {
        $this->authorize('update', [ProyectoCapacidadInstalada::class, $proyectoCapacidadInstalada]);

        $user = new User();

        $user->nombre               = $request->nombre;
        $user->email                = $request->email;
        $user->password             = $user::makePassword($request->numero_documento);
        $user->tipo_documento       = $request->tipo_documento;
        $user->numero_documento     = $request->numero_documento;
        $user->numero_celular       = $request->numero_celular;
        $user->habilitado           = 0;
        $user->tipo_vinculacion     = $request->tipo_vinculacion;
        $user->autorizacion_datos   = $request->autorizacion_datos;
        $user->centroFormacion()->associate($request->centro_formacion_id);

        $user->save();

        $user->assignRole(14);

        $data['user_id'] = $user->id;
        $data['rol_sennova']    = $request->rol_sennova;
        $data['cantidad_meses'] = $request->cantidad_meses;
        $data['cantidad_horas'] = $request->cantidad_horas;
        $data['autor_principal'] = true;

        return $this->linkIntegrante(new Request($data), $proyectoCapacidadInstalada);
    }

    public function createEntidadAliada(ProyectoCapacidadInstalada $proyectoCapacidadInstalada)
    {
        $this->authorize('update', [ProyectoCapacidadInstalada::class, $proyectoCapacidadInstalada]);

        $proyectoCapacidadInstalada->integrantes;

        return Inertia::render('ProyectosCapacidadInstalada/Integrantes/EntidadesAliadas/Create', [
            'proyectoCapacidadInstalada' => $proyectoCapacidadInstalada,
        ]);
    }

    public function storeEntidadAliada(ProyectoCapacidadInstaladaEntidadAliadaRequest $request, ProyectoCapacidadInstalada $proyectoCapacidadInstalada)
    {
        $this->authorize('update', [ProyectoCapacidadInstalada::class, $proyectoCapacidadInstalada]);

        $entidadAliada = new ProyectoCapacidadInstaladaEntidadAliada();
        $entidadAliada->nombre      = $request->nombre;
        $entidadAliada->nit         = $request->nit;
        $entidadAliada->documento   = $request->documento;

        $entidadAliada->proyectoCapacidadInstalada()->associate($proyectoCapacidadInstalada);
        $entidadAliada->save();

        return redirect()->route('proyectos-capacidad-instalada.integrantes.index', $proyectoCapacidadInstalada)->with('success', 'El recurso se ha creado correctamente.');
    }

    public function editEntidadAliada(ProyectoCapacidadInstalada $proyectoCapacidadInstalada, ProyectoCapacidadInstaladaEntidadAliada $entidadAliada)
    {
        $this->authorize('update', [ProyectoCapacidadInstalada::class, $proyectoCapacidadInstalada]);

        $proyectoCapacidadInstalada->integrantes;

        return Inertia::render('ProyectosCapacidadInstalada/Integrantes/EntidadesAliadas/Edit', [
            'proyectoCapacidadInstalada' => $proyectoCapacidadInstalada,
            'entidadAliada' => $entidadAliada
        ]);
    }

    public function updateEntidadAliada(ProyectoCapacidadInstaladaEntidadAliadaRequest $request, ProyectoCapacidadInstalada $proyectoCapacidadInstalada, ProyectoCapacidadInstaladaEntidadAliada $entidadAliada)
    {
        $this->authorize('update', [ProyectoCapacidadInstalada::class, $proyectoCapacidadInstalada]);

        $entidadAliada->nombre      = $request->nombre;
        $entidadAliada->nit         = $request->nit;
        $entidadAliada->documento   = $request->documento;

        $entidadAliada->save();

        return redirect()->back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    public function destroyEntidadAliada(ProyectoCapacidadInstalada $proyectoCapacidadInstalada, ProyectoCapacidadInstaladaEntidadAliada $entidadAliada)
    {
        $this->authorize('update', [ProyectoCapacidadInstalada::class, $proyectoCapacidadInstalada]);

        $entidadAliada->delete();

        return redirect()->route('proyectos-capacidad-instalada.integrantes.index', $proyectoCapacidadInstalada)->with('success', 'El recurso se ha eliminado correctamente.');
    }

    public function download(ProyectoCapacidadInstalada $proyectoCapacidadInstalada, ProyectoCapacidadInstaladaEntidadAliada $entidadAliada)
    {
        $this->authorize('update', [ProyectoCapacidadInstalada::class, $proyectoCapacidadInstalada]);

        return response()->download(storage_path("app/$entidadAliada->documento"));
    }

    /**
     * indexObjetivosEspecificos
     *
     * @param  mixed $proyectoCapacidadInstalada
     * @return void
     */
    public function indexObjetivosEspecificos(ProyectoCapacidadInstalada $proyectoCapacidadInstalada)
    {
        $this->authorize('update', [ProyectoCapacidadInstalada::class, $proyectoCapacidadInstalada]);

        $proyectoCapacidadInstalada->integrantes;

        return Inertia::render('ProyectosCapacidadInstalada/ObjetivosEspecificos/Index', [
            'proyectoCapacidadInstalada'    => $proyectoCapacidadInstalada,
            'objetivosEspecificos'          => ProyectoCapacidadInstaladaObjetivoEspecifico::where('proyecto_capacidad_instalada_id', $proyectoCapacidadInstalada->id)->with('resultado')->orderBy('numero', 'ASC')->paginate(15)
        ]);
    }

    public function createObjetivoEspecifico(ProyectoCapacidadInstalada $proyectoCapacidadInstalada)
    {
        $this->authorize('update', [ProyectoCapacidadInstalada::class, $proyectoCapacidadInstalada]);

        $proyectoCapacidadInstalada->integrantes;

        return Inertia::render('ProyectosCapacidadInstalada/ObjetivosEspecificos/Create', [
            'proyectoCapacidadInstalada' => $proyectoCapacidadInstalada,
        ]);
    }

    public function storeObjetivoEspecifico(ProyectoCapacidadInstaladaObjetivoEspecificoRequest $request, ProyectoCapacidadInstalada $proyectoCapacidadInstalada)
    {
        $this->authorize('update', [ProyectoCapacidadInstalada::class, $proyectoCapacidadInstalada]);

        $totalObjetivosEspecificos = ProyectoCapacidadInstaladaObjetivoEspecifico::where('proyecto_capacidad_instalada_id', $proyectoCapacidadInstalada->id)->count();

        if ($totalObjetivosEspecificos < 4) {
            $objetivoEspecifico = new ProyectoCapacidadInstaladaObjetivoEspecifico();
            $objetivoEspecifico->numero = $totalObjetivosEspecificos + 1;
            $objetivoEspecifico->descripcion = $request->descripcion;

            $objetivoEspecifico->proyectoCapacidadInstalada()->associate($proyectoCapacidadInstalada);

            $objetivoEspecifico->save();

            $resultado = new ProyectoCapacidadInstaladaResultado();
            $resultado->descripcion = $request->descripcion_resultado;
            $resultado->objetivoEspecifico()->associate($proyectoCapacidadInstalada);

            $objetivoEspecifico->resultado()->save($resultado);

            return redirect()->route('proyectos-capacidad-instalada.objetivos-especificos.index', $proyectoCapacidadInstalada)->with('success', 'El recurso se ha creado correctamente.');
        }

        return redirect()->route('proyectos-capacidad-instalada.objetivos-especificos.index', $proyectoCapacidadInstalada)->with('error', 'No puede superar el máximo de 4 objetivos específicos.');
    }

    public function editObjetivoEspecifico(ProyectoCapacidadInstalada $proyectoCapacidadInstalada, ProyectoCapacidadInstaladaObjetivoEspecifico $objetivoEspecifico)
    {
        $this->authorize('update', [ProyectoCapacidadInstalada::class, $proyectoCapacidadInstalada]);

        $objetivoEspecifico->resultado;
        $proyectoCapacidadInstalada->integrantes;

        return Inertia::render('ProyectosCapacidadInstalada/ObjetivosEspecificos/Edit', [
            'proyectoCapacidadInstalada'    => $proyectoCapacidadInstalada,
            'objetivoEspecifico'            => $objetivoEspecifico
        ]);
    }

    public function updateObjetivoEspecifico(ProyectoCapacidadInstaladaObjetivoEspecificoRequest $request, ProyectoCapacidadInstalada $proyectoCapacidadInstalada, ProyectoCapacidadInstaladaObjetivoEspecifico $objetivoEspecifico)
    {
        $this->authorize('update', [ProyectoCapacidadInstalada::class, $proyectoCapacidadInstalada]);

        $objetivoEspecifico->descripcion = $request->descripcion;
        $objetivoEspecifico->save();

        $objetivoEspecifico->resultado()->update(['descripcion' => $request->descripcion_resultado]);

        return redirect()->back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    public function destroyObjetivoEspecifico(ProyectoCapacidadInstalada $proyectoCapacidadInstalada, ProyectoCapacidadInstaladaObjetivoEspecifico $objetivoEspecifico)
    {
        $this->authorize('update', [ProyectoCapacidadInstalada::class, $proyectoCapacidadInstalada]);

        $objetivoEspecifico->delete();

        return redirect()->route('proyectos-capacidad-instalada.objetivos-especificos.index', $proyectoCapacidadInstalada)->with('success', 'El recurso se ha eliminado correctamente.');
    }

    /**
     * indexProductos
     *
     * @param  mixed $proyectoCapacidadInstalada
     * @return void
     */
    public function indexProductos(ProyectoCapacidadInstalada $proyectoCapacidadInstalada)
    {
        $this->authorize('update', [ProyectoCapacidadInstalada::class, $proyectoCapacidadInstalada]);

        $proyectoCapacidadInstalada->integrantes;

        return Inertia::render('ProyectosCapacidadInstalada/Productos/Index', [
            'proyectoCapacidadInstalada'    => $proyectoCapacidadInstalada,
            'productos'                     => ProyectoCapacidadInstaladaProducto::select('proyectos_capacidad_producto.*')->join('proyectos_capacidad_resultado', 'proyectos_capacidad_producto.proyecto_capacidad_resultado_id', 'proyectos_capacidad_resultado.id')->with('resultado')->paginate(15)
        ]);
    }

    public function createProducto(ProyectoCapacidadInstalada $proyectoCapacidadInstalada)
    {
        $this->authorize('update', [ProyectoCapacidadInstalada::class, $proyectoCapacidadInstalada]);

        $proyectoCapacidadInstalada->integrantes;

        return Inertia::render('ProyectosCapacidadInstalada/Productos/Create', [
            'proyectoCapacidadInstalada' => $proyectoCapacidadInstalada,
            'resultados'                 => ProyectoCapacidadInstaladaResultado::select('proyectos_capacidad_resultado.id as value', 'proyectos_capacidad_resultado.descripcion as label', 'proyectos_capacidad_resultado.id as id')->whereHas('objetivoEspecifico', function ($query) use ($proyectoCapacidadInstalada) {
                $query->where('proyecto_capacidad_objetivo_especifico.proyecto_capacidad_instalada_id', $proyectoCapacidadInstalada->id);
            })->where('proyectos_capacidad_resultado.descripcion', '!=', null)->get(),
            'tipologiasMinciencias'      => json_decode(Storage::get('json/tipologia-minciencias.json'), true),
        ]);
    }

    public function storeProducto(ProyectoCapacidadInstaladaProductoRequest $request, ProyectoCapacidadInstalada $proyectoCapacidadInstalada)
    {
        $this->authorize('update', [ProyectoCapacidadInstalada::class, $proyectoCapacidadInstalada]);

        $producto = new ProyectoCapacidadInstaladaProducto();
        $producto->descripcion = $request->descripcion;
        $producto->tipologia_minciencias = $request->tipologia_minciencias;
        $producto->resultado()->associate($request->resultado_id);

        $producto->save();

        return redirect()->route('proyectos-capacidad-instalada.productos.index', $proyectoCapacidadInstalada)->with('success', 'El recurso se ha creado correctamente.');
    }

    public function editProducto(ProyectoCapacidadInstalada $proyectoCapacidadInstalada, ProyectoCapacidadInstaladaProducto $producto)
    {
        $this->authorize('update', [ProyectoCapacidadInstalada::class, $proyectoCapacidadInstalada]);

        $proyectoCapacidadInstalada->integrantes;

        $producto->resultado;

        return Inertia::render('ProyectosCapacidadInstalada/Productos/Edit', [
            'proyectoCapacidadInstalada'    => $proyectoCapacidadInstalada,
            'producto'                      => $producto,
            'resultados'                    => ProyectoCapacidadInstaladaResultado::select('proyectos_capacidad_resultado.id as value', 'proyectos_capacidad_resultado.descripcion as label', 'proyectos_capacidad_resultado.id as id')->whereHas('objetivoEspecifico', function ($query) use ($proyectoCapacidadInstalada) {
                $query->where('proyecto_capacidad_objetivo_especifico.proyecto_capacidad_instalada_id', $proyectoCapacidadInstalada->id);
            })->where('proyectos_capacidad_resultado.descripcion', '!=', null)->get(),
            'tipologiasMinciencias'         => json_decode(Storage::get('json/tipologia-minciencias.json'), true),
        ]);
    }

    public function updateProducto(ProyectoCapacidadInstaladaProductoRequest $request, ProyectoCapacidadInstalada $proyectoCapacidadInstalada, ProyectoCapacidadInstaladaProducto $producto)
    {
        $this->authorize('update', [ProyectoCapacidadInstalada::class, $proyectoCapacidadInstalada]);

        $producto->descripcion = $request->descripcion;
        $producto->tipologia_minciencias = $request->tipologia_minciencias;
        $producto->resultado()->associate($request->resultado_id);
        $producto->save();

        return redirect()->back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    public function destroyProducto(ProyectoCapacidadInstalada $proyectoCapacidadInstalada, ProyectoCapacidadInstaladaProducto $producto)
    {
        $this->authorize('update', [ProyectoCapacidadInstalada::class, $proyectoCapacidadInstalada]);

        $producto->delete();

        return redirect()->route('proyectos-capacidad-instalada.productos.index', $proyectoCapacidadInstalada)->with('success', 'El recurso se ha eliminado correctamente.');
    }

    public function finalizar(ProyectoCapacidadInstalada $proyectoCapacidadInstalada)
    {
        $this->authorize('update', [ProyectoCapacidadInstalada::class, $proyectoCapacidadInstalada]);

        $proyectoCapacidadInstalada->integrantes;

        return Inertia::render('ProyectosCapacidadInstalada/FinalizarProyecto', [
            'proyectoCapacidadInstalada'        => $proyectoCapacidadInstalada,
            'estadosProyectoCapacidadInstalada' => json_decode(Storage::get('json/estados-proyecto-capacidad-instalada.json'), true),

        ]);
    }

    public function storeFinalizar(Request $request, ProyectoCapacidadInstalada $proyectoCapacidadInstalada)
    {
        $this->authorize('update', [ProyectoCapacidadInstalada::class, $proyectoCapacidadInstalada]);

        $proyectoCapacidadInstalada->update(['estado_proyecto' => $request->estado_proyecto['value']]);

        return redirect()->back()->with('success', 'El proyecto se ha finalizado correctamente.');
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

        return str_replace(array("\r", "\n"), '', str_replace(array("\r", "\n"), '', "{$cleanName}cod{$random}." . $archivo->extension()));
    }
}
