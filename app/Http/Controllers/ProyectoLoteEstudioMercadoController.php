<?php

namespace App\Http\Controllers;

use App\Models\Convocatoria;
use App\Models\Proyecto;
use App\Models\ProyectoPresupuesto;
use App\Models\ProyectoLoteEstudioMercado;
use App\Models\EstudioMercado;
use App\Http\Requests\ProyectoLoteEstudioMercadoRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Traits\PresupuestoValidationTrait;
use Inertia\Inertia;

class ProyectoLoteEstudioMercadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Convocatoria $convocatoria, Proyecto $proyecto, ProyectoPresupuesto $presupuesto)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);

        // Denega si el rubro no requiere lotes de estudio de mercado y ya hay un estudio de mercado guardado o si el rubro no requiere de estudios de mercado.
        if (!$presupuesto->convocatoriaPresupuesto->presupuestoSennova->requiere_estudio_mercado) {
            return redirect()->route('convocatorias.proyectos.presupuesto.index', [$convocatoria, $proyecto])->with('success', 'El recurso se ha creado correctamente.');
        }

        return Inertia::render('Convocatorias/Proyectos/ProyectoPresupuesto/EstudioMercado/Index', [
            'filters'               => request()->all('search'),
            'proyectoLotesEstudioMercado'  => $presupuesto->proyectoLoteEstudioMercado()
                ->with('estudiosMercado')
                ->filterProyectoLoteEstudioMercado(request()->only('search'))->paginate()->appends(['search' => request()->search]),
            'convocatoria'                  => $convocatoria->only('id'),
            'proyecto'                      => $proyecto->only('id', 'modificable'),
            'proyectoPresupuesto'           => $presupuesto->only('id', 'promedio'),
            'presupuestoSennova'            => $presupuesto->convocatoriaPresupuesto->presupuestoSennova,
            'usoPresupuestal'               => $presupuesto->convocatoriaPresupuesto->presupuestoSennova->usoPresupuestal,
            'convocatoriaPresupuesto'       => $presupuesto->convocatoriaPresupuesto->only('id'),
            'requiereEstudioMercado'        => $presupuesto->convocatoriaPresupuesto->presupuestoSennova->requiere_estudio_mercado,
            'requiereLoteEstudioMercado'    => $presupuesto->convocatoriaPresupuesto->presupuestoSennova->requiere_lote_estudio_mercado,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Convocatoria $convocatoria, Proyecto $proyecto, ProyectoPresupuesto $presupuesto)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);

        return redirect()->route('convocatorias.proyectos.presupuesto.lote.index', [$convocatoria, $proyecto, $presupuesto]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProyectoLoteEstudioMercadoRequest $request, Convocatoria $convocatoria, Proyecto $proyecto, ProyectoPresupuesto $presupuesto)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        /**
         * Denega si el rubro no requiere lotes y ya hay un estudio de mercado guardado o si el rubro no requiere de estudio de mercado.
         */
        if (!$presupuesto->convocatoriaPresupuesto->presupuestoSennova->requiere_lote_estudio_mercado && $presupuesto->proyectoLoteEstudioMercado->count() > 0 || !$presupuesto->convocatoriaPresupuesto->presupuestoSennova->requiere_estudio_mercado) {
            return redirect()->route('convocatorias.proyectos.presupuesto.index', [$convocatoria, $proyecto]);
        }

        /**
         * Línea 66
         */
        if ($proyecto->lineaProgramatica->codigo == 66) {
            if (PresupuestoValidationTrait::serviciosEspecialesConstruccionValidation($proyecto, $presupuesto, 'store', $request->primer_valor, $request->segundo_valor, $request->tercer_valor)) {
                $porcentajeMaquinariaIndustrial = PresupuestoValidationTrait::porcentajeMaquinariaIndustrial($proyecto);
                return redirect()->back()->with('error', "Este estudio de mercado supera el 5% ($ {$porcentajeMaquinariaIndustrial} COP) del total del rubro 'Maquinaria industrial'. Vuelva a diligenciar.");
            }

            if (PresupuestoValidationTrait::serviciosMantenimientoValidation($proyecto,  $presupuesto, 'store', $request->primer_valor, $request->segundo_valor, $request->tercer_valor)) {
                $porcentajeProyecto = $proyecto->getTotalProyectoPresupuestoAttribute() * 0.05;
                return redirect()->back()->with('error', "Este estudio de mercado supera el 5% ($ {$porcentajeProyecto}) del COP total del proyecto. Vuelva a diligenciar.");
            }
        }

        /**
         * Línea 23
         */
        if ($proyecto->lineaProgramatica->codigo == 23) {
            if (PresupuestoValidationTrait::adecuacionesYContruccionesValidation($proyecto,  $presupuesto, 'store', $request->primer_valor, $request->segundo_valor, $request->tercer_valor)) {
                return redirect()->back()->with('error', "Antes de diligenciar información sobre este rubro de 'Adecuaciones y construcciones' tenga en cuenta que el total NO debe superar el valor de 100 salarios mínimos.");
            }
        }

        $lote = new ProyectoLoteEstudioMercado();
        if ($request->hasFile('ficha_tecnica')) {
            $lote->numero_items = $request->numero_items;

            $nombreArchivoFichaTecnica  = $this->cleanFileName($proyecto->codigo, $presupuesto->convocatoriaPresupuesto->presupuestoSennova->segundoGrupoPresupuestal->nombre, $request->ficha_tecnica);
            $archivoFichaTecnica        = $request->ficha_tecnica->storeAs(
                'fichas-tecnicas',
                $nombreArchivoFichaTecnica
            );
            $lote->ficha_tecnica = $archivoFichaTecnica;
        }

        $lote->proyectoPresupuesto()->associate($presupuesto);
        $lote->save();

        $this->storeEstudioMercado($lote, $proyecto, $request->primer_valor, $request->primer_empresa, $request->primer_archivo);
        $this->storeEstudioMercado($lote, $proyecto, $request->segundo_valor, $request->segunda_empresa, $request->segundo_archivo);

        if ($request->tercer_valor && $request->tercer_empresa && $request->hasFile('tercer_archivo')) {
            // Tercer estudio de mercado
            $this->storeEstudioMercado($lote, $proyecto, $request->tercer_valor, $request->tercer_empresa, $request->tercer_archivo);
        }

        if ($presupuesto->convocatoriaPresupuesto->presupuestoSennova->requiere_lote_estudio_mercado) {
            return redirect()->back()->with('success', 'El recurso se ha creado correctamente.');
        }

        return redirect()->route('convocatorias.proyectos.presupuesto.index', [$convocatoria, $proyecto])->with('success', 'El recurso se ha creado correctamente.');
    }

    private function storeEstudioMercado($lote, $proyecto, $valor, $empresa, $archivo)
    {
        $estudioMercado = new EstudioMercado();

        $estudioMercado->valor      = $valor;
        $estudioMercado->empresa    = $empresa;

        $nombreArchivo = $this->cleanFileName($proyecto->codigo, $empresa, $archivo);
        $ruta = $archivo->storeAs(
            'estudios-mercado',
            $nombreArchivo
        );
        $estudioMercado->soporte = $ruta;

        $estudioMercado->proyectoLoteEstudioMercado()->associate($lote);
        $lote->estudiosMercado()->save($estudioMercado);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProyectoLoteEstudioMercado  $lote
     * @return \Illuminate\Http\Response
     */
    public function show(Convocatoria $convocatoria, Proyecto $proyecto, ProyectoPresupuesto $presupuesto, ProyectoLoteEstudioMercado $lote)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProyectoLoteEstudioMercado  $lote
     * @return \Illuminate\Http\Response
     */
    public function edit(Convocatoria $convocatoria, Proyecto $proyecto, ProyectoPresupuesto $presupuesto, ProyectoLoteEstudioMercado $lote)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);

        /**
         * Denega si el rubro no requiere lotes y ya hay un estudio de mercado guardado o si el rubro no requiere de estudio de mercado.
         */
        if (!$presupuesto->convocatoriaPresupuesto->presupuestoSennova->requiere_estudio_mercado) {
            return redirect()->route('convocatorias.proyectos.presupuesto.index', [$convocatoria, $proyecto])->with('success', 'El recurso se ha creado correctamente.');
        }

        $presupuesto->convocatoriaPresupuesto->presupuestoSennova->usoPresupuestal;
        $lote->estudiosMercado;

        return Inertia::render('Convocatorias/Proyectos/ProyectoPresupuesto/EstudioMercado/Edit', [
            'convocatoria'               => $convocatoria->only('id'),
            'proyecto'                   => $proyecto->only('id', 'modificable'),
            'proyectoPresupuesto'        => $presupuesto,
            'proyectoLoteEstudioMercado' => $lote
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProyectoLoteEstudioMercado  $lote
     * @return \Illuminate\Http\Response
     */
    public function update(ProyectoLoteEstudioMercadoRequest $request, Convocatoria $convocatoria, Proyecto $proyecto, ProyectoPresupuesto $presupuesto, ProyectoLoteEstudioMercado $lote)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        /**
         * Denega si el rubro no requiere lotes y ya hay un estudio de mercado guardado o si el rubro no requiere de estudio de mercado.
         */
        if (!$presupuesto->convocatoriaPresupuesto->presupuestoSennova->requiere_estudio_mercado) {
            return redirect()->route('convocatorias.proyectos.presupuesto.index', [$convocatoria, $proyecto]);
        }

        /**
         * Línea 66
         */
        if ($proyecto->lineaProgramatica->codigo == 66) {
            if (PresupuestoValidationTrait::serviciosEspecialesConstruccionValidation($proyecto, $presupuesto, 'update', $request->primer_valor, $request->segundo_valor, $request->tercer_valor)) {
                $porcentajeMaquinariaIndustrial = PresupuestoValidationTrait::porcentajeMaquinariaIndustrial($proyecto);
                return redirect()->back()->with('error', "Este estudio de mercado supera el 5% ($ {$porcentajeMaquinariaIndustrial} COP) del total del rubro 'Maquinaria industrial'. Vuelva a diligenciar.");
            }

            if (PresupuestoValidationTrait::serviciosMantenimientoValidation($proyecto,  $presupuesto, 'update', $request->primer_valor, $request->segundo_valor, $request->tercer_valor)) {
                $porcentajeProyecto = $proyecto->getTotalProyectoPresupuestoAttribute() * 0.05;
                return redirect()->back()->with('error', "Este estudio de mercado supera el 5% del ($ {$porcentajeProyecto}) COP total del proyecto. Vuelva a diligenciar.");
            }
        }

        /**
         * Línea 23
         */
        if ($proyecto->lineaProgramatica->codigo == 23) {
            if (PresupuestoValidationTrait::adecuacionesYContruccionesValidation($proyecto,  $presupuesto, 'update', $request->primer_valor, $request->segundo_valor, $request->tercer_valor)) {
                return redirect()->back()->with('error', "Antes de diligenciar información sobre este rubro de 'Adecuaciones y construcciones' tenga en cuenta que el total NO debe superar el valor de 100 salarios mínimos.");
            }
        }

        if ($request->hasFile('ficha_tecnica')) {
            $lote->numero_items = $request->numero_items;
            Storage::delete($lote->ficha_tecnica);
            $nombreArchivoFichaTecnica  = $this->cleanFileName($proyecto->codigo, $presupuesto->convocatoriaPresupuesto->presupuestoSennova->segundoGrupoPresupuestal->nombre, $request->ficha_tecnica);
            $archivoFichaTecnica        = $request->ficha_tecnica->storeAs(
                'fichas-tecnicas',
                $nombreArchivoFichaTecnica
            );
            $lote->ficha_tecnica = $archivoFichaTecnica;
        }
        $lote->proyectoPresupuesto()->associate($presupuesto);
        $lote->save();

        // Primer estudio de mercado
        $this->updateEstudioMercado($proyecto, $lote, $request->primer_estudio_mercado_id, $request->primer_empresa, $request->primer_valor, $request->primer_archivo, false);

        // Segundo estudio de mercado
        $this->updateEstudioMercado($proyecto, $lote, $request->segundo_estudio_mercado_id, $request->segunda_empresa, $request->segundo_valor, $request->segundo_archivo, false);


        // Tercer estudio de mercado
        if ($request->tercer_valor || $request->tercer_empresa) {
            $this->updateEstudioMercado($proyecto, $lote, $request->tercer_estudio_mercado_id, $request->tercer_empresa,  $request->tercer_valor, $request->tercer_archivo, true);
        }

        return redirect()->route('convocatorias.proyectos.presupuesto.lote.index', [$convocatoria, $proyecto, $presupuesto])->with('success', 'El recurso se ha actualizado correctamente.');
    }

    public function updateEstudioMercado($proyecto, $lote, $estudioMercadoId, $empresa, $valor, $archivo, $tercerEstudioMercado)
    {
        $estudioMercado = $lote->estudiosMercado()->where('id', $estudioMercadoId)->first();
        if ($archivo) {
            Storage::delete($estudioMercado->soporte);
            $ruta       = $this->cleanFileName($proyecto->codigo, $empresa, $archivo);
            $soporte    = $archivo->storeAs(
                'estudios-mercado',
                $ruta
            );
        }

        if ($tercerEstudioMercado) {
            $lote->estudiosMercado()->where('id', $estudioMercadoId)->updateOrCreate(
                ['id' => $estudioMercadoId],
                [
                    'valor'    => $valor,
                    'empresa'  => $empresa,
                    'soporte'  => $soporte ?? $estudioMercado->soporte,
                ]
            );
        } else {
            $estudioMercado->update(
                [
                    'valor'    => $valor,
                    'empresa'  => $empresa,
                    'soporte'  => $soporte ?? $estudioMercado->soporte,
                ]
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProyectoLoteEstudioMercado  $lote
     * @return \Illuminate\Http\Response
     */
    public function destroy(Convocatoria $convocatoria, Proyecto $proyecto, ProyectoPresupuesto $presupuesto, ProyectoLoteEstudioMercado $lote)
    {
        $this->authorize('modificar-proyecto-autor', $proyecto);

        foreach ($lote->estudiosMercado as $estudioMercado) {
            Storage::delete($estudioMercado->soporte);
        }

        $lote->delete();

        return redirect()->route('convocatorias.proyectos.presupuesto.lote.index', [$convocatoria, $proyecto, $presupuesto])->with('success', 'El recurso se ha eliminado correctamente.');
    }

    /**
     * download
     *
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @param  mixed $presupuesto
     * @param  mixed $lote
     * @return void
     */
    public function download(Convocatoria $convocatoria, Proyecto $proyecto, ProyectoPresupuesto $presupuesto, ProyectoLoteEstudioMercado $lote)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);

        return response()->download(storage_path("app/$lote->ficha_tecnica"));
    }

    /**
     * download
     *
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @param  mixed $presupuesto
     * @param  mixed $estudioMercado
     * @return void
     */
    public function downloadSoporte(Convocatoria $convocatoria, Proyecto $proyecto, ProyectoPresupuesto $presupuesto, EstudioMercado $estudioMercado)
    {
        $this->authorize('visualizar-proyecto-autor', $proyecto);

        return response()->download(storage_path("app/$estudioMercado->soporte"));
    }

    /**
     * cleanFileName
     *
     * @param  mixed $nombre
     * @return void
     */
    public function cleanFileName($codigoProyecto, $nombre, $fichaTecnica)
    {
        $cleanName = str_replace(' ', '', substr($nombre, 0, 30));
        $cleanName = preg_replace('/[-`~!@#_$%\^&*()+={}[\]\\\\|;:\'",.><?\/]/', '', $cleanName);

        $cleanProyectoCodigo = str_replace(' ', '', substr($codigoProyecto, 0, 30));
        $cleanProyectoCodigo = preg_replace('/[-`~!@#_$%\^&*()+={}[\]\\\\|;:\'",.><?\/]/', '', $cleanProyectoCodigo);

        $random    = Str::random(5);

        return "{$cleanProyectoCodigo}{$cleanName}cod{$random}." . $fichaTecnica->extension();
    }
}
