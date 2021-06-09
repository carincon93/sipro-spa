<?php

namespace App\Http\Controllers;

use App\Http\Requests\EntidadAliadaRequest;
use App\Models\Convocatoria;
use App\Models\Proyecto;
use App\Models\EntidadAliada;
use App\Models\Actividad;
use App\Models\EntidadAliadaIdi;
use App\Models\EntidadAliadaTaTp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class EntidadAliadaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('validar-autor', $proyecto);

        $proyecto->codigo_linea_programatica = $proyecto->tipoProyecto->lineaProgramatica->codigo;

        /**
         * Si el proyecto es de la línea programática 23 o 65 se prohibe el acceso. No requiere de entidades aliadas
         */
        if ($proyecto->codigo_linea_programatica == 23 || $proyecto->codigo_linea_programatica == 65) {
            return redirect()->route('convocatorias.proyectos.analisis-riesgos.index', [$convocatoria, $proyecto])->with('error', 'Esta línea programática no requiere de entidades aliadas');
        }

        return Inertia::render('Convocatorias/Proyectos/EntidadesAliadas/Index', [
            'convocatoria'      => $convocatoria->only('id'),
            'proyecto'               => $proyecto->only('id', 'codigo_linea_programatica'),
            'filters'           => request()->all('search'),
            'entidadesAliadas'  => EntidadAliada::where('proyecto_id', $proyecto->id)->orderBy('nombre', 'ASC')
                ->filterEntidadAliada(request()->only('search'))->select('id', 'nombre')->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('validar-autor', $proyecto);

        $objetivoEspecifico = $proyecto->causasDirectas()->with('objetivoEspecifico')->get()->pluck('objetivoEspecifico')->flatten()->filter();

        $proyecto->idi;
        $proyecto->ta_tp;

        return Inertia::render('Convocatorias/Proyectos/EntidadesAliadas/Create', [
            'convocatoria'  => $convocatoria->only('id'),
            'proyecto'      => $proyecto,
            'actividades'   => Actividad::whereIn(
                'objetivo_especifico_id',
                $objetivoEspecifico->map(function ($objetivoEspecifico) {
                    return $objetivoEspecifico->id;
                })
            )->orderBy('fecha_inicio', 'ASC')->get(),
            'tiposEntidadAliada'        => json_decode(Storage::get('json/tipos-entidades-aliadas.json'), true),
            'naturalezaEntidadAliada'   => json_decode(Storage::get('json/naturaleza-empresa.json'), true),
            'tiposEmpresa'              => json_decode(Storage::get('json/tipos-empresa.json'), true)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EntidadAliadaRequest $request, Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $this->authorize('validar-autor', $proyecto);

        $entidadAliada = new EntidadAliada();
        $entidadAliada->tipo                                    = $request->tipo;
        $entidadAliada->nombre                                  = $request->nombre;
        $entidadAliada->naturaleza                              = $request->naturaleza;
        $entidadAliada->tipo_empresa                            = $request->tipo_empresa;
        $entidadAliada->nit                                     = $request->nit;
        $entidadAliada->recursos_especie                        = $request->recursos_especie;
        $entidadAliada->descripcion_recursos_especie            = $request->descripcion_recursos_especie;
        $entidadAliada->recursos_dinero                         = $request->recursos_dinero;
        $entidadAliada->descripcion_recursos_dinero             = $request->descripcion_recursos_dinero;

        $entidadAliada->proyecto()->associate($proyecto);

        $entidadAliada->save();

        $entidadAliada->actividades()->attach($request->actividad_id);

        $nombreEmpresa  = Str::slug(substr($request->nombre, 0, 30), '-');
        $random         = Str::random(5);
        if ($proyecto->idi()->exists()) {

            $entidadAliadaIdi = new EntidadAliadaIdi();
            $entidadAliadaIdi->descripcion_convenio                    = $request->descripcion_convenio;
            $entidadAliadaIdi->grupo_investigacion                     = $request->grupo_investigacion;
            $entidadAliadaIdi->codigo_gruplac                          = $request->codigo_gruplac;
            $entidadAliadaIdi->enlace_gruplac                          = $request->enlace_gruplac;
            $entidadAliadaIdi->actividades_transferencia_conocimiento  = $request->actividades_transferencia_conocimiento;

            $cartaIntencion = $request->carta_intencion;
            $nombreArchivoCartaIntencion = "{$proyecto->codigo}-carta-de-intencion-$nombreEmpresa-cod$random." . $cartaIntencion->extension();
            $archivoCartaIntencion = $cartaIntencion->storeAs(
                'cartas-intencion',
                $nombreArchivoCartaIntencion
            );
            $entidadAliadaIdi->carta_intencion = $archivoCartaIntencion;

            $cartaPropiedadIntelectual = $request->carta_propiedad_intelectual;
            $nombreArchivoPropiedadIntelectual = "{$proyecto->codigo}-propiedad-intelectual-$nombreEmpresa." . $cartaPropiedadIntelectual->extension();
            $archivoPropiedadIntelectual = $cartaPropiedadIntelectual->storeAs(
                'cartas-propiedad-intelectual',
                $nombreArchivoPropiedadIntelectual
            );

            $entidadAliadaIdi->carta_propiedad_intelectual = $archivoPropiedadIntelectual;

            $entidadAliada->entidadAliadaIdi()->save($entidadAliadaIdi);

            return redirect()->route('convocatorias.proyectos.entidades-aliadas.miembros-entidad-aliada.index', [$convocatoria, $proyecto, $entidadAliada])->with('success', 'El recurso se ha creado correctamente.');
        } elseif ($proyecto->taTp()->exists()) {
            $entidadAliadaTaTp = new EntidadAliadaTaTp();
            $soporteConvenio        = $request->soporte_convenio;
            $nombreSoporteConvenio  = "{$proyecto->codigo}-soporte-convenio-$nombreEmpresa-cod$random." . $soporteConvenio->extension();
            $rutaSoporteConvenio        = $soporteConvenio->storeAs(
                'soportes-convenio',
                $nombreSoporteConvenio
            );
            $entidadAliadaTaTp->soporte_convenio = $rutaSoporteConvenio;

            $entidadAliada->entidadAliadaTaTp()->save($entidadAliadaTaTp);

            return redirect()->route('convocatorias.proyectos.entidades-aliadas.index', [$convocatoria, $proyecto])->with('success', 'El recurso se ha creado correctamente.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EntidadAliada  $entidadAliada
     * @return \Illuminate\Http\Response
     */
    public function show(Convocatoria $convocatoria, Proyecto $proyecto, EntidadAliada $entidadAliada)
    {
        $this->authorize('validar-autor', $proyecto);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EntidadAliada  $entidadAliada
     * @return \Illuminate\Http\Response
     */
    public function edit(Convocatoria $convocatoria, Proyecto $proyecto, EntidadAliada $entidadAliada)
    {
        $this->authorize('validar-autor', $proyecto);

        $objetivoEspecificos = $proyecto->causasDirectas()->with('objetivoEspecifico')->get()->pluck('objetivoEspecifico')->flatten()->filter();

        $entidadAliada->miembrosEntidadAliada->only('id', 'nombre', 'email', 'numero_celular');
        $entidadAliada->entidadAliadaIdi;
        $entidadAliada->entidadAliadaTaTp;
        $proyecto->idi;
        $proyecto->ta_tp;

        return Inertia::render('Convocatorias/Proyectos/EntidadesAliadas/Edit', [
            'convocatoria'    => $convocatoria->only('id'),
            'proyecto'        => $proyecto,
            'entidadAliada'   => $entidadAliada,
            'actividades'     => Actividad::whereIn(
                'objetivo_especifico_id',
                $objetivoEspecificos->map(function ($objetivoEspecifico) {
                    return $objetivoEspecifico->id;
                })
            )->orderBy('fecha_inicio', 'ASC')->get(),
            'actividadesRelacionadas'           => $entidadAliada->actividades()->pluck('id'),
            'objetivosEspecificosRelacionados'  => $entidadAliada->actividades()->with('objetivoEspecifico')->get()->pluck('objetivoEspecifico'),
            'tiposEntidadAliada'                => json_decode(Storage::get('json/tipos-entidades-aliadas.json'), true),
            'naturalezaEntidadAliada'           => json_decode(Storage::get('json/naturaleza-empresa.json'), true),
            'tiposEmpresa'                      => json_decode(Storage::get('json/tipos-empresa.json'), true)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EntidadAliada  $entidadAliada
     * @return \Illuminate\Http\Response
     */
    public function update(EntidadAliadaRequest $request, Convocatoria $convocatoria, Proyecto $proyecto, EntidadAliada $entidadAliada)
    {
        $this->authorize('validar-autor', $proyecto);

        $entidadAliada->tipo                                        = $request->tipo;
        $entidadAliada->nombre                                      = $request->nombre;
        $entidadAliada->naturaleza                                  = $request->naturaleza;
        $entidadAliada->tipo_empresa                                = $request->tipo_empresa;
        $entidadAliada->nit                                         = $request->nit;
        $entidadAliada->recursos_especie                            = $request->recursos_especie;
        $entidadAliada->descripcion_recursos_especie                = $request->descripcion_recursos_especie;
        $entidadAliada->recursos_dinero                             = $request->recursos_dinero;
        $entidadAliada->descripcion_recursos_dinero                 = $request->descripcion_recursos_dinero;

        $nombreEmpresa  = Str::slug(substr($request->nombre, 0, 30), '-');
        $random         = Str::random(5);
        if ($entidadAliada->entidadAliadaIdi()->exists()) {
            $entidadAliada->entidadAliadaIdi()->update([
                'descripcion_convenio'                        => $request->tiene_convenio ? $request->descripcion_convenio : null,
                'grupo_investigacion'                         => $request->tiene_grupo_investigacion ? $request->grupo_investigacion : null,
                'codigo_gruplac'                              => $request->tiene_grupo_investigacion ? $request->codigo_gruplac : null,
                'enlace_gruplac'                              => $request->tiene_grupo_investigacion ? $request->enlace_gruplac : null,
                'actividades_transferencia_conocimiento'      => $request->actividades_transferencia_conocimiento,
            ]);

            if ($request->hasFile('carta_intencion')) {
                Storage::delete($entidadAliada->entidadAliadaIdi->carta_intencion);
                $cartaIntencion                 = $request->carta_intencion;
                $nombreArchivoCartaIntencion    = "{$proyecto->codigo}-carta-de-intencion-$nombreEmpresa-cod$random." . $cartaIntencion->extension();
                $rutaCartaIntencion          = $cartaIntencion->storeAs(
                    'cartas-intencion',
                    $nombreArchivoCartaIntencion
                );

                $entidadAliada->entidadAliadaIdi()->update(['carta_intencion' => $rutaCartaIntencion]);
            }

            if ($request->hasFile('carta_propiedad_intelectual')) {
                Storage::delete($entidadAliada->entidadAliadaIdi->carta_propiedad_intelectual);
                $cartaPropiedadIntelectual          = $request->carta_propiedad_intelectual;
                $nombreArchivoPropiedadIntelectual  = "{$proyecto->codigo}-propiedad-intelectual-$nombreEmpresa-cod$random." . $cartaPropiedadIntelectual->extension();
                $rutaPropiedadIntelectual        = $cartaPropiedadIntelectual->storeAs(
                    'cartas-propiedad-intelectual',
                    $nombreArchivoPropiedadIntelectual
                );

                $entidadAliada->entidadAliadaIdi()->update(['carta_propiedad_intelectual' => $rutaPropiedadIntelectual]);
            }
        } elseif ($proyecto->taTp()->exists()) {
            if ($request->hasFile('soporte_convenio')) {
                Storage::delete($entidadAliada->entidadAliadaTaTp->soporte_convenio);
                $soporteConvenio        = $request->soporte_convenio;
                $nombreSoporteConvenio  = "{$proyecto->codigo}-soporte-convenio-$nombreEmpresa-cod$random." . $soporteConvenio->extension();
                $rutaSoporteConvenio    = $soporteConvenio->storeAs(
                    'soportes-convenio',
                    $nombreSoporteConvenio
                );

                $entidadAliada->entidadAliadaTaTp()->update(['soporte_convenio' => $rutaSoporteConvenio]);
            }
        }

        $entidadAliada->proyecto()->associate($proyecto);
        $entidadAliada->actividades()->sync($request->actividad_id);

        $entidadAliada->save();

        return redirect()->back()->with('success', 'El recurso se ha actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EntidadAliada  $entidadAliada
     * @return \Illuminate\Http\Response
     */
    public function destroy(Convocatoria $convocatoria, Proyecto $proyecto, EntidadAliada $entidadAliada)
    {
        $this->authorize('validar-autor', $proyecto);

        Storage::delete($entidadAliada->carta_intencion);
        Storage::delete($entidadAliada->propiedad_intelectual);

        $entidadAliada->delete();

        return redirect()->route('convocatorias.proyectos.entidades-aliadas.index', [$convocatoria, $proyecto])->with('success', 'El recurso se ha eliminado correctamente.');
    }
}
