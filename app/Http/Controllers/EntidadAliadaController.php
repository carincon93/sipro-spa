<?php

namespace App\Http\Controllers;

use App\Http\Requests\EntidadAliadaRequest;
use App\Models\Convocatoria;
use App\Models\IDi;
use App\Models\EntidadAliada;
use App\Models\Actividad;
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
    public function index(Convocatoria $convocatoria, IDi $IDi)
    {
        $this->authorize('viewAny', [EntidadAliada::class, $IDi]);

        $IDi->codigo_linea_programatica = $IDi->proyecto->tipoProyecto->lineaProgramatica->codigo;

        /**
         * Si el proyecto es de la línea programática 23 se prohibe el acceso. No requiere de entidades aliadas
         */
        if ($IDi->codigo_linea_programatica == 23) {
            return redirect()->route('convocatorias.proyectos.analisis-riesgos.index', [$convocatoria, $IDi->proyecto])->with('error', 'Esta línea programática no requiere de entidades aliadas');
        }

        return Inertia::render('Convocatorias/Proyectos/IDi/EntidadesAliadas/Index', [
            'convocatoria'  => $convocatoria->only('id'),
            'idi'           => $IDi->only('id', 'codigo_linea_programatica'),
            'filters'           => request()->all('search'),
            'entidadesAliadas'  => EntidadAliada::where('idi_id', $IDi->id)->orderBy('nombre', 'ASC')
                ->filterEntidadAliada(request()->only('search'))->select('id', 'nombre')->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Convocatoria $convocatoria, IDi $IDi)
    {
        $this->authorize('create', [EntidadAliada::class]);

        $objetivoEspecifico = $IDi->proyecto->causasDirectas()->with('objetivoEspecifico')->get()->pluck('objetivoEspecifico')->flatten()->filter();

        return Inertia::render('Convocatorias/Proyectos/IDi/EntidadesAliadas/Create', [
            'convocatoria'  => $convocatoria->only('id'),
            'idi'           => $IDi->only('id'),
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
    public function store(EntidadAliadaRequest $request, Convocatoria $convocatoria, IDi $IDi)
    {
        $this->authorize('create', [EntidadAliada::class]);

        $entidadAliada = new EntidadAliada();
        $entidadAliada->tipo                                    = $request->tipo;
        $entidadAliada->nombre                                  = $request->nombre;
        $entidadAliada->naturaleza                              = $request->naturaleza;
        $entidadAliada->tipo_empresa                            = $request->tipo_empresa;
        $entidadAliada->nit                                     = $request->nit;
        $entidadAliada->descripcion_convenio                    = $request->descripcion_convenio;
        $entidadAliada->grupo_investigacion                     = $request->grupo_investigacion;
        $entidadAliada->codigo_gruplac                          = $request->codigo_gruplac;
        $entidadAliada->enlace_gruplac                          = $request->enlace_gruplac;
        $entidadAliada->actividades_transferencia_conocimiento  = $request->actividades_transferencia_conocimiento;
        $entidadAliada->recursos_especie                        = $request->recursos_especie;
        $entidadAliada->descripcion_recursos_especie            = $request->descripcion_recursos_especie;
        $entidadAliada->recursos_dinero                         = $request->recursos_dinero;
        $entidadAliada->descripcion_recursos_dinero             = $request->descripcion_recursos_dinero;

        $nombreEmpresa  = Str::slug(substr($request->nombre, 0, 30), '-');
        $random         = Str::random(5);

        $cartaIntencion = $request->carta_intencion;

        $nombreArchivoCartaIntencion = "{$IDi->proyecto->codigo}-carta-de-intencion-$nombreEmpresa-cod$random." . $cartaIntencion->extension();
        $archivoCartaIntencion = $cartaIntencion->storeAs(
            'cartas-intencion',
            $nombreArchivoCartaIntencion
        );
        $entidadAliada->carta_intencion = $archivoCartaIntencion;

        $cartaPropiedadIntelectual = $request->carta_propiedad_intelectual;
        $nombreArchivoPropiedadIntelectual = "{$IDi->proyecto->codigo}-propiedad-intelectual-$nombreEmpresa." . $cartaPropiedadIntelectual->extension();
        $archivoPropiedadIntelectual = $cartaPropiedadIntelectual->storeAs(
            'cartas-propiedad-intelectual',
            $nombreArchivoPropiedadIntelectual
        );

        $entidadAliada->carta_propiedad_intelectual = $archivoPropiedadIntelectual;

        $entidadAliada->IDi()->associate($IDi);
        $entidadAliada->save();

        $entidadAliada->actividades()->attach($request->actividad_id);

        return redirect()->route('convocatorias.idi.entidades-aliadas.miembros-entidad-aliada.index', [$convocatoria, $IDi, $entidadAliada])->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EntidadAliada  $entidadAliada
     * @return \Illuminate\Http\Response
     */
    public function show(Convocatoria $convocatoria, IDi $IDi, EntidadAliada $entidadAliada)
    {
        $this->authorize('view', [EntidadAliada::class, $entidadAliada]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EntidadAliada  $entidadAliada
     * @return \Illuminate\Http\Response
     */
    public function edit(Convocatoria $convocatoria, IDi $IDi, EntidadAliada $entidadAliada)
    {
        $this->authorize('update', [EntidadAliada::class, $entidadAliada]);

        $objetivoEspecificos = $IDi->proyecto->causasDirectas()->with('objetivoEspecifico')->get()->pluck('objetivoEspecifico')->flatten()->filter();

        $entidadAliada->miembrosEntidadAliada->only('id', 'nombre', 'email', 'numero_celular');

        return Inertia::render('Convocatorias/Proyectos/IDi/EntidadesAliadas/Edit', [
            'convocatoria'    => $convocatoria->only('id'),
            'idi'             => $IDi->only('id'),
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
    public function update(EntidadAliadaRequest $request, Convocatoria $convocatoria, IDi $IDi, EntidadAliada $entidadAliada)
    {
        $this->authorize('update', [EntidadAliada::class, $entidadAliada]);

        $entidadAliada->tipo                                        = $request->tipo;
        $entidadAliada->nombre                                      = $request->nombre;
        $entidadAliada->naturaleza                                  = $request->naturaleza;
        $entidadAliada->tipo_empresa                                = $request->tipo_empresa;
        $entidadAliada->nit                                         = $request->nit;
        $entidadAliada->descripcion_convenio                        = $request->descripcion_convenio;
        $entidadAliada->grupo_investigacion                         = $request->grupo_investigacion;
        $entidadAliada->codigo_gruplac                              = $request->codigo_gruplac;
        $entidadAliada->enlace_gruplac                              = $request->enlace_gruplac;
        $entidadAliada->actividades_transferencia_conocimiento      = $request->actividades_transferencia_conocimiento;
        $entidadAliada->recursos_especie                            = $request->recursos_especie;
        $entidadAliada->descripcion_recursos_especie                = $request->descripcion_recursos_especie;
        $entidadAliada->recursos_dinero                             = $request->recursos_dinero;
        $entidadAliada->descripcion_recursos_dinero                 = $request->descripcion_recursos_dinero;

        $nombreEmpresa  = Str::slug(substr($request->nombre, 0, 30), '-');
        $random         = Str::random(5);
        if ($request->hasFile('carta_intencion')) {
            Storage::delete($entidadAliada->carta_intencion);
            $cartaIntencion                 = $request->carta_intencion;
            $nombreArchivoCartaIntencion    = "{$IDi->proyecto->codigo}-carta-de-intencion-$nombreEmpresa-cod$random." . $cartaIntencion->extension();
            $archivoCartaIntencion          = $cartaIntencion->storeAs(
                'cartas-intencion',
                $nombreArchivoCartaIntencion
            );
            $entidadAliada->carta_intencion = $archivoCartaIntencion;
        }

        if ($request->hasFile('carta_propiedad_intelectual')) {
            Storage::delete($entidadAliada->carta_propiedad_intelectual);
            $cartaPropiedadIntelectual          = $request->carta_propiedad_intelectual;
            $nombreArchivoPropiedadIntelectual  = "{$IDi->proyecto->codigo}-propiedad-intelectual-$nombreEmpresa-cod$random." . $cartaPropiedadIntelectual->extension();
            $archivoPropiedadIntelectual        = $cartaPropiedadIntelectual->storeAs(
                'cartas-propiedad-intelectual',
                $nombreArchivoPropiedadIntelectual
            );
            $entidadAliada->carta_propiedad_intelectual = $archivoPropiedadIntelectual;
        }

        $entidadAliada->IDi()->associate($IDi);
        $entidadAliada->actividades()->sync($request->actividad_id);

        $entidadAliada->save();

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EntidadAliada  $entidadAliada
     * @return \Illuminate\Http\Response
     */
    public function destroy(Convocatoria $convocatoria, IDi $IDi, EntidadAliada $entidadAliada)
    {
        $this->authorize('delete', [EntidadAliada::class, $entidadAliada]);

        Storage::delete($entidadAliada->carta_intencion);
        Storage::delete($entidadAliada->propiedad_intelectual);

        $entidadAliada->delete();

        return redirect()->route('convocatorias.idi.entidades-aliadas.index', [$convocatoria, $IDi])->with('success', 'The resource has been deleted successfully.');
    }
}
