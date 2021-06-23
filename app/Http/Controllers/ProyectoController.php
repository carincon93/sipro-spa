<?php

namespace App\Http\Controllers;

use App\Http\Requests\NuevoProponenteRequest;
use App\Http\Requests\ProponenteRequest;
use App\Models\Convocatoria;
use App\Models\User;
use App\Models\ProgramaFormacion;
use App\Models\Proyecto;
use App\Models\SemilleroInvestigacion;
use App\Notifications\ComentarioProyecto;
use App\Notifications\ProyectoFinalizado;
use App\Notifications\ProyectoRadicado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProyectoController extends Controller
{
    /**
     * showCadenaValor
     *
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function showCadenaValor(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);

        $proyecto->codigo_linea_programatica = $proyecto->lineaProgramatica->codigo;

        if ($proyecto->idi()->exists()) {
            $objetivoGeneral = $proyecto->idi->objetivo_general;
            $proyecto->propuesta_sostenibilidad = $proyecto->idi->propuesta_sostenibilidad;
        }

        if ($proyecto->taTp()->exists()) {
            $objetivoGeneral = $proyecto->taTp->objetivo_general;
            $proyecto->propuesta_sostenibilidad = $proyecto->taTp->propuesta_sostenibilidad;
        }

        if ($proyecto->servicioTecnologico()->exists()) {
            $objetivoGeneral = $proyecto->servicioTecnologico->objetivo_general;
            $proyecto->propuesta_sostenibilidad = $proyecto->servicioTecnologico->propuesta_sostenibilidad;
        }

        if ($proyecto->culturaInnovacion()->exists()) {
            $objetivoGeneral = $proyecto->culturaInnovacion->objetivo_general;
            $proyecto->propuesta_sostenibilidad = $proyecto->culturaInnovacion->propuesta_sostenibilidad;
        }

        $objetivos = collect(['Objetivo general' => $objetivoGeneral]);
        $productos = collect([]);

        foreach ($proyecto->causasDirectas as $causaDirecta) {
            $objetivos->prepend($causaDirecta->objetivoEspecifico->descripcion, $causaDirecta->objetivoEspecifico->numero);
        }

        foreach ($proyecto->efectosDirectos as $efectoDirecto) {
            foreach ($efectoDirecto->resultados as $resultado) {
                foreach ($resultado->productos as $producto) {
                    if ($producto->id == 196) {
                        dd($producto);
                    }
                    $productos->prepend(['v' => 'prod' . $producto->id,  'f' => $producto->nombre, 'fkey' =>  $resultado->objetivoEspecifico->numero, 'tootlip' => 'prod' . $producto->id, 'actividades' => $producto->actividades]);
                }
            }
        }

        return Inertia::render('Convocatorias/Proyectos/CadenaValor/Index', [
            'convocatoria'  => $convocatoria->only('id'),
            'proyecto'      => $proyecto->only('id', 'codigo_linea_programatica', 'precio_proyecto', 'propuesta_sostenibilidad', 'modificable'),
            'productos'     => $productos,
            'objetivos'     => $objetivos
        ]);
    }

    public function updatePropuestaSostenibilidad(Request $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        $request->validate([
            'propuesta_sostenibilidad' => 'required|string|max:10000',
        ]);

        switch ($proyecto) {
            case $proyecto->idi()->exists():
                $idi                            = $proyecto->idi;
                $idi->propuesta_sostenibilidad  = $request->propuesta_sostenibilidad;

                $idi->save();
                break;
            case $proyecto->taTp()->exists():
                $tatp                           = $proyecto->taTp;
                $tatp->propuesta_sostenibilidad = $request->propuesta_sostenibilidad;

                $tatp->save();
                break;
            case $proyecto->culturaInnovacion()->exists():
                $culturaInnovacion                              = $proyecto->culturaInnovacion;
                $culturaInnovacion->propuesta_sostenibilidad    = $request->propuesta_sostenibilidad;

                $culturaInnovacion->save();
                break;
            case $proyecto->servicioTecnologico()->exists():
                $servicioTecnologico                            = $proyecto->servicioTecnologico;
                $servicioTecnologico->propuesta_sostenibilidad  = $request->propuesta_sostenibilidad;

                $servicioTecnologico->save();
                break;
            default:
                break;
        }

        return redirect()->back()->with('success', 'El recurso se ha guardado correctamente.');
    }

    /**
     * edit
     *
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function edit(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        switch ($proyecto) {
            case $proyecto->idi()->exists():
                return redirect()->route('convocatorias.idi.edit', [$convocatoria, $proyecto]);
                break;
            case $proyecto->taTp()->exists():
                return redirect()->route('convocatorias.tatp.edit', [$convocatoria, $proyecto]);
                break;
            case $proyecto->servicioTecnologico()->exists():
                return redirect()->route('convocatorias.servicios-tecnologicos.edit', [$convocatoria, $proyecto]);
                break;
            case $proyecto->culturaInnovacion()->exists():
                return redirect()->route('convocatorias.cultura-innovacion.edit', [$convocatoria, $proyecto]);
                break;
            default:
                break;
        }
    }

    /**
     * Show summary.
     *
     * @param  \App\Models\Proyecto  $proyecto
     * @return \Illuminate\Http\Response
     */
    public function summary(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('visualizar-proyecto-autor', [$proyecto]);

        $proyecto->codigo_linea_programatica = $proyecto->lineaProgramatica->codigo;
        $proyecto->precio_proyecto           = $proyecto->precioProyecto;

        $proyecto->logs = $proyecto::getLog($proyecto->id);

        return Inertia::render('Convocatorias/Proyectos/Summary', [
            'convocatoria' => $convocatoria->only('id', 'min_fecha_inicio_proyectos', 'max_fecha_finalizacion_proyectos'),
            'proyecto'     => $proyecto,
        ]);
    }

    /**
     * Finsih project.
     *
     * @param  \App\Models\Proyecto  $proyecto
     * @return \Illuminate\Http\Response
     */
    public function finishProject(Request $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('visualizar-proyecto-autor', [$proyecto]);

        if (!Hash::check($request->password, Auth::user()->password)) {
            return redirect()->back()
                ->withErrors(['password' => __('The password is incorrect.')]);
        }

        $proyecto->modificable = false;
        $proyecto->finalizado = true;
        $proyecto->save();

        $proyecto->centroFormacion->dinamizadorSennova->notify(new ProyectoFinalizado($convocatoria, $proyecto));

        return redirect()->back()->with('success', 'Se ha finalizado el proyecto exitosamente.');
    }

    /**
     * Finsih project.
     *
     * @param  \App\Models\Proyecto  $proyecto
     * @return \Illuminate\Http\Response
     */
    public function sendProject(Request $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('validar-dinamizador', [$proyecto]);

        if (!Hash::check($request->password, Auth::user()->password)) {
            return redirect()->back()
                ->withErrors(['password' => __('The password is incorrect.')]);
        }

        $proyecto->radicado = true;
        $proyecto->finalizado = true;
        $proyecto->modificable = false;
        $proyecto->save();

        $user = $proyecto->participantes()->where('es_formulador', true)->first();
        $user->notify(new ProyectoRadicado($proyecto, Auth::user()));

        return redirect()->back()->with('success', 'Se ha radicado el proyecto exitosamente.');
    }

    /**
     * Finsih project.
     *
     * @param  \App\Models\Proyecto  $proyecto
     * @return \Illuminate\Http\Response
     */
    public function returnProject(Request $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('validar-dinamizador', [$proyecto]);

        $proyecto->radicado = false;
        $proyecto->finalizado = false;
        $proyecto->modificable = true;
        $proyecto->save();

        $user = $proyecto->participantes()->where('es_formulador', true)->first();
        $user->notify(new ComentarioProyecto($convocatoria, $proyecto, $request->comentario));

        return redirect()->back()->with('success', 'Se ha notificado al proponente.');
    }

    /**
     * participantes
     *
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function participantes(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);

        $proyecto->codigo_linea_programatica = $proyecto->lineaProgramatica->codigo;
        $proyecto->participantes;
        $proyecto->programasFormacion;
        $proyecto->semillerosInvestigacion;

        if ($proyecto->codigo_linea_programatica == 23 || $proyecto->codigo_linea_programatica == 65 || $proyecto->codigo_linea_programatica == 66 || $proyecto->codigo_linea_programatica == 82) {
            $rolesSennova = collect(json_decode(Storage::get('json/roles-sennova-idi.json'), true));
        } elseif ($proyecto->codigo_linea_programatica == 70) {
            $rolesSennova = collect(json_decode(Storage::get('json/roles-sennova-ta.json'), true));
        } elseif ($proyecto->codigo_linea_programatica == 69) {
            $rolesSennova = collect(json_decode(Storage::get('json/roles-sennova-tp.json'), true));
        } elseif ($proyecto->codigo_linea_programatica == 68) {
            $rolesSennova = collect(json_decode(Storage::get('json/roles-sennova-st.json'), true));
        }

        $proyecto->load('participantes.centroFormacion.regional');
        $proyecto->load('semillerosInvestigacion.lineaInvestigacion.grupoInvestigacion');

        return Inertia::render('Convocatorias/Proyectos/Participantes/Index', [
            'convocatoria'          => $convocatoria,
            'proyecto'              => $proyecto->only('id', 'codigo_linea_programatica', 'precio_proyecto', 'modificable', 'diff_meses', 'participantes', 'semillerosInvestigacion'),
            'tiposDocumento'        => json_decode(Storage::get('json/tipos-documento.json'), true),
            'tiposVinculacion'      => json_decode(Storage::get('json/tipos-vinculacion.json'), true),
            'roles'                 => $rolesSennova,
        ]);
    }

    /**
     * filterParticipantes
     *
     * @param  mixed $request
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function filterParticipantes(Request $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        if (!empty($request->search_participante)) {
            $query = User::orderBy('nombre', 'ASC')
                ->filterUser(['search' => $request->search_participante])
                ->with('centroFormacion.regional')->take(6);

            if ($proyecto->participantes->count() > 0) {
                $query->whereNotIn('id', explode(',', $proyecto->participantes->implode('id', ',')));
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
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function linkParticipante(ProponenteRequest $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $data = $request->only('cantidad_horas', 'cantidad_meses', 'rol_sennova');

        if (is_array($data['rol_sennova'])) {
            $data['rol_sennova'] = $data['rol_sennova']['value'];
        }

        try {
            if ($proyecto->participantes()->where('id', $request->user_id)->exists()) {
                return redirect()->back()->with('error', 'El recurso ya está vinculado.');
            }

            $proyecto->participantes()->attach($request->user_id, $data);
            return redirect()->back()->with('success', 'El recurso se ha vinculado correctamente.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Oops! Algo salió mal.');
        }

        return redirect()->back()->with('error', 'Oops! Algo salió mal.');
    }

    /**
     * unlinkParticipante
     *
     * @param  mixed $request
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function unlinkParticipante(Request $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $request->validate(['user_id' => 'required']);

        try {
            if ($proyecto->participantes()->where('id', $request->user_id)->exists()) {
                $proyecto->participantes()->detach($request->user_id);
                return redirect()->back()->with('success', 'El recurso se ha desvinculado correctamente.');
            }
            return redirect()->back()->with('success', 'El recurso ya está desvinculado.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Oops! Algo salió mal.');
        }
        return redirect()->back()->with('error', 'Oops! Algo salió mal.');
    }

    /**
     * updateParticipante
     *
     * @param  mixed $request
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function updateParticipante(ProponenteRequest $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $data = $request->only('cantidad_horas', 'cantidad_meses', 'rol_sennova');

        try {
            if ($proyecto->participantes()->where('id', $request->user_id)->exists()) {
                $proyecto->participantes()->updateExistingPivot($request->user_id, $data);
                return redirect()->back()->with('success', 'El recurso se ha vinculado correctamente.');
            }
            return redirect()->back()->with('error', 'El recurso ya está desvinculado.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Oops! Algo salió mal.');
        }

        return redirect()->back()->with('error', 'Oops! Algo salió mal.');
    }

    /**
     * registerParticipante
     *
     * @param  mixed $request
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function registerParticipante(NuevoProponenteRequest $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);

        $user = new User();

        $user->nombre               = $request->nombre;
        $user->email                = $request->email;
        $user->password             = $user::makePassword($request->numero_documento);
        $user->tipo_documento       = $request->tipo_documento;
        $user->numero_documento     = $request->numero_documento;
        $user->numero_celular       = $request->numero_celular;
        $user->habilitado           = 0;
        $user->tipo_vinculacion   = $request->tipo_vinculacion;
        $user->autorizacion_datos   = $request->autorizacion_datos;
        $user->centroFormacion()->associate($request->centro_formacion_id);

        $user->save();

        $user->assignRole(14);

        $data = $request->only('cantidad_horas', 'cantidad_meses', 'rol_sennova');
        $data['user_id'] = $user->id;

        return $this->linkParticipante(new ProponenteRequest($data), $convocatoria, $proyecto);
    }

    /**
     * filterSemillerosInvestigacion
     *
     * @param  mixed $request
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function filterSemillerosInvestigacion(Request $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        if (!empty($request->search_semillero_investigacion)) {
            $query = SemilleroInvestigacion::select('semilleros_investigacion.id', 'semilleros_investigacion.nombre', 'semilleros_investigacion.linea_investigacion_id')->orderBy('semilleros_investigacion.nombre', 'ASC')
                ->filterSemilleroInvestigacion(['search' => $request->search_semillero_investigacion])
                ->with('lineaInvestigacion.grupoInvestigacion');

            if ($proyecto->semillerosInvestigacion->count() > 0) {
                $query->whereNotIn('semilleros_investigacion.id', explode(',', $proyecto->semillerosInvestigacion->implode('id', ',')));
            }

            $semillerosInvestigacion = $query->get()->take(5);

            return $semillerosInvestigacion->makeHidden('created_at', 'updated_at')->toJson();
        }

        return null;
    }

    /**
     * linkSemilleroInvestigacion
     *
     * @param  mixed $request
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function linkSemilleroInvestigacion(Request $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $request->validate(['semillero_investigacion_id' => 'required']);

        try {
            if ($proyecto->semillerosInvestigacion()->where('id', $request->semillero_investigacion_id)->exists()) {
                return redirect()->back()->with('error', 'El recurso ya está vinculado.');
            }
            $proyecto->semillerosInvestigacion()->attach($request->semillero_investigacion_id);
            return redirect()->back()->with('success', 'El recurso se ha vinculado correctamente.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Oops! Algo salió mal.');
        }

        return redirect()->back()->with('error', 'Oops! Algo salió mal.');
    }

    /**
     * unlinkSemilleroInvestigacion
     *
     * @param  mixed $request
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function unlinkSemilleroInvestigacion(Request $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $request->validate(['semillero_investigacion_id' => 'required']);

        try {
            if ($proyecto->semillerosInvestigacion()->where('id', $request->semillero_investigacion_id)->exists()) {
                $proyecto->semillerosInvestigacion()->detach($request->semillero_investigacion_id);
                return redirect()->back()->with('success', 'El recurso se ha desvinculado correctamente.');
            }
            return redirect()->back()->with('success', 'El recurso ya está desvinculado.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Oops! Algo salió mal.');
        }
        return redirect()->back()->with('error', 'Oops! Algo salió mal.');
    }

    /**
     * filterProgramasFormacion
     *
     * @param  mixed $request
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function filterProgramasFormacion(Request $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        if (!empty($request->search_programa_formacion)) {
            $query = ProgramaFormacion::select('programas_formacion.id', 'programas_formacion.nombre', 'programas_formacion.codigo', 'programas_formacion.modalidad', 'programas_formacion.centro_formacion_id')->orderBy('programas_formacion.nombre', 'ASC')
                ->filterProgramaFormacion(['search' => $request->search_programa_formacion])
                ->with('centroFormacion.regional');

            if ($proyecto->programasFormacion->count() > 0) {
                $query->whereNotIn('programas_formacion.id', explode(',', $proyecto->programasFormacion->implode('id', ',')));
            }

            $programasFormacion = $query->get()->take(5);

            return $programasFormacion->makeHidden('created_at', 'updated_at')->toJson();
        }

        return null;
    }

    /**
     * linkProgramaFormacion
     *
     * @param  mixed $request
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function linkProgramaFormacion(Request $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $request->validate(['programa_formacion_id' => 'required']);

        try {
            if ($proyecto->programasFormacion()->where('id', $request->programa_formacion_id)->exists()) {
                return redirect()->back()->with('error', 'El recurso ya está vinculado.');
            }
            $proyecto->programasFormacion()->attach($request->programa_formacion_id);
            return redirect()->back()->with('success', 'El recurso se ha vinculado correctamente.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Oops! Algo salió mal.');
        }

        return redirect()->back()->with('error', 'Oops! Algo salió mal.');
    }

    /**
     * unlinkProgramaFormacion
     *
     * @param  mixed $request
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function unlinkProgramaFormacion(Request $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $request->validate(['programa_formacion_id' => 'required']);

        try {
            if ($proyecto->programasFormacion()->where('id', $request->programa_formacion_id)->exists()) {
                $proyecto->programasFormacion()->detach($request->programa_formacion_id);
                return redirect()->back()->with('success', 'El recurso se ha desvinculado correctamente.');
            }
            return redirect()->back()->with('success', 'El recurso ya está desvinculado.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Oops! Algo salió mal.');
        }
        return redirect()->back()->with('error', 'Oops! Algo salió mal.');
    }
}
