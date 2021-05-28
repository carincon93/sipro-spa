<?php

namespace App\Http\Controllers;

use App\Http\Requests\NuevoProponenteRequest;
use App\Http\Requests\ProponenteRequest;
use App\Models\Convocatoria;
use App\Models\User;
use App\Models\ProgramaFormacion;
use App\Models\Proyecto;
use App\Models\SemilleroInvestigacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProyectoController extends Controller
{
    public function showCadenaValor(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $proyecto->codigo_linea_programatica = $proyecto->tipoProyecto->lineaProgramatica->codigo;

        if ($proyecto->idi()->exists()) {
            $objetivoGeneral = $proyecto->idi->objetivo_general;
        }

        if ($proyecto->tatp()->exists()) {
            $objetivoGeneral = $proyecto->tatp->objetivo_general;
        }

        $objetivos = collect(['Objetivo general' => $objetivoGeneral]);
        $productos = collect([]);

        foreach ($proyecto->causasDirectas as $causaDirecta) {
            $objetivos->prepend($causaDirecta->objetivoEspecifico->descripcion, $causaDirecta->objetivoEspecifico->numero);
        }

        foreach ($proyecto->efectosDirectos as $efectoDirecto) {

            foreach ($efectoDirecto->resultado->productos as $producto) {
                $productos->prepend(['v' => 'prod' . $producto->id,  'f' => $producto->nombre, 'fkey' =>  $efectoDirecto->resultado->objetivoEspecifico->numero, 'tootlip' => 'prod' . $producto->id, 'actividades' => $producto->actividades]);
            }
        }

        return Inertia::render('Convocatorias/Proyectos/CadenaValor/Index', [
            'convocatoria'  => $convocatoria->only('id'),
            'proyecto'      => $proyecto->only('id', 'codigo_linea_programatica', 'precio_proyecto'),
            'productos'     => $productos,
            'objetivos'     => $objetivos
        ]);
    }

    public function edit(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        switch ($proyecto) {
            case $proyecto->idi()->exists():
                return redirect()->route('convocatorias.idi.edit', [$convocatoria, $proyecto]);
                break;
            case $proyecto->tatp()->exists():
                return redirect()->route('convocatorias.tatp.edit', [$convocatoria, $proyecto]);
                break;
            default:
                # code...
                break;
        }
    }

    /**
     * Participantes
     *
     * @return void
     */
    public function participantes(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        // $this->authorize('viewAny', [Project::class]);

        $proyecto->codigo_linea_programatica = $proyecto->tipoProyecto->lineaProgramatica->codigo;
        $proyecto->participantes;
        $proyecto->programasFormacion;
        $proyecto->semillerosInvestigacion;

        $proyecto->load('participantes.centroFormacion.regional');
        $proyecto->load('programasFormacion.centroFormacion.regional');
        $proyecto->load('semillerosInvestigacion.lineaInvestigacion.grupoInvestigacion');

        return Inertia::render('Convocatorias/Proyectos/Participantes/Index', [
            'convocatoria'          => $convocatoria,
            'proyecto'              => $proyecto->only('id', 'codigo_linea_programatica', 'precio_proyecto', 'participantes', 'programasFormacion', 'semillerosInvestigacion'),
            'tiposDocumento'        => json_decode(Storage::get('json/tipos-documento.json'), true),
            'tiposParticipacion'    => json_decode(Storage::get('json/tipos-participacion.json'), true),
        ]);
    }

    public function filterParticipantes(Request $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        if (!empty($request->search_participante)) {
            $query = User::orderBy('nombre', 'ASC')
                ->filterUser(['search' => $request->search_participante])
                ->with('centroFormacion.regional')->take(6);

            if ($proyecto->participantes->count() > 0) {
                $query->whereNotIn('id', explode(',', $proyecto->participantes->implode('id', ',')));
            }

            $users = $query->get();

            return $users->makeHidden('can', 'roles', 'user_name', 'permissions')->toJson();
        }

        return null;
    }

    public function linkParticipante(ProponenteRequest $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $data = $request->only('es_autor', 'cantidad_horas', 'cantidad_meses');

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

    public function updateParticipante(ProponenteRequest $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $data = $request->only('es_autor', 'cantidad_horas', 'cantidad_meses');

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

    public function registerParticipante(NuevoProponenteRequest $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('create', [User::class]);

        $user = new User();

        $user->nombre               = $request->nombre;
        $user->email                = $request->email;
        $user->password             = $user::makePassword($request->numero_documento);
        $user->tipo_documento       = $request->tipo_documento;
        $user->numero_documento     = $request->numero_documento;
        $user->numero_celular       = $request->numero_celular;
        $user->habilitado           = 0;
        $user->tipo_participacion   = $request->tipo_participacion;
        $user->centroFormacion()->associate($request->centro_formacion_id);

        $user->save();

        $data = $request->only('es_autor', 'cantidad_horas', 'cantidad_meses');
        $data['user_id'] = $user->id;

        return $this->linkParticipante(new ProponenteRequest($data), $convocatoria, $proyecto);
    }

    public function filterSemillerosInvestigacion(Request $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        if (!empty($request->search_semillero_investigacion)) {
            $query = SemilleroInvestigacion::orderBy('nombre', 'ASC')
                ->filterSemilleroInvestigacion(['search' => $request->search_semillero_investigacion])
                ->with('lineaInvestigacion.grupoInvestigacion');

            if ($proyecto->semillerosInvestigacion->count() > 0) {
                $query->whereNotIn('id', explode(',', $proyecto->semillerosInvestigacion->implode('id', ',')));
            }

            $semillerosInvestigacion = $query->get();

            return $semillerosInvestigacion->makeHidden('created_at', 'updated_at')->toJson();
        }

        return null;
    }

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

    public function filterProgramasFormacion(Request $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        if (!empty($request->search_programa_formacion)) {
            $query = ProgramaFormacion::orderBy('nombre', 'ASC')
                ->filterProgramaFormacion(['search' => $request->search_programa_formacion])
                ->with('centroFormacion.regional');

            if ($proyecto->programasFormacion->count() > 0) {
                $query->whereNotIn('id', explode(',', $proyecto->programasFormacion->implode('id', ',')));
            }

            $programasFormacion = $query->get();

            return $programasFormacion->makeHidden('created_at', 'updated_at')->toJson();
        }

        return null;
    }

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
