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
    public function index(Request $request, Convocatoria $convocatoria, Proyecto $proyecto, ProyectoPresupuesto $proyectoPresupuesto)
    {
        $this->authorize('validar-autor', $proyecto);

        // Denega si el rubro no requiere lotes de estudio de mercado y ya hay un estudio de mercado guardado o si el rubro no requiere de estudios de mercado.
        if (!$proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->requiere_estudio_mercado) {
            return redirect()->route('convocatorias.proyectos.proyecto-presupuesto.index', [$convocatoria, $proyecto])->with('success', 'El recurso se ha creado correctamente.');
        }

        return Inertia::render('Convocatorias/Proyectos/ProyectoPresupuesto/EstudioMercado/Index', [
            'filters'               => request()->all('search'),
            'proyectoLotesEstudioMercado'  => $proyectoPresupuesto->proyectoLoteEstudioMercado()
                ->with('estudiosMercado')
                ->filterProyectoLoteEstudioMercado(request()->only('search'))->paginate(),
            'convocatoria'                  => $convocatoria->only('id'),
            'proyecto'                      => $proyecto->only('id'),
            'proyectoPresupuesto'           => $proyectoPresupuesto->only('id', 'promedio'),
            'presupuestoSennova'            => $proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova,
            'usoPresupuestal'               => $proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->usoPresupuestal,
            'convocatoriaPresupuesto'       => $proyectoPresupuesto->convocatoriaPresupuesto->only('id'),
            'requiereEstudioMercado'        => $proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->requiere_estudio_mercado,
            'requiereLoteEstudioMercado'    => $proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->requiere_lote_estudio_mercado,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Convocatoria $convocatoria, Proyecto $proyecto, ProyectoPresupuesto $proyectoPresupuesto)
    {
        $this->authorize('validar-autor', $proyecto);

        return redirect()->route('convocatorias.proyectos.proyecto-presupuesto.proyecto-lote-estudio-mercado.index', [$convocatoria, $proyecto, $proyectoPresupuesto]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProyectoLoteEstudioMercadoRequest $request, Convocatoria $convocatoria, Proyecto $proyecto, ProyectoPresupuesto $proyectoPresupuesto)
    {
        $this->authorize('validar-autor', $proyecto);

        // Denega si el rubro no requiere lotes y ya hay un estudio de mercado guardado o si el rubro no requiere de estudio de mercado.
        if (!$proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->requiere_lote_estudio_mercado && $proyectoPresupuesto->proyectoLoteEstudioMercado->count() > 0 || !$proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->requiere_estudio_mercado) {
            return redirect()->route('convocatorias.proyectos.proyecto-presupuesto.index', [$convocatoria, $proyecto]);
        }

        if ($proyecto->tipoProyecto->lineaProgramatica->codigo == 66) {
            if (PresupuestoValidationTrait::serviciosEspecialesConstruccionValidation($proyecto, $proyectoPresupuesto, 'store', $request->primer_valor, $request->segundo_valor, $request->tercer_valor)) {
                $porcentajeMaquinariaIndustrial = PresupuestoValidationTrait::porcentajeMaquinariaIndustrial($proyecto);
                return redirect()->back()->with('error', "Este estudio de mercado supera el 5% ($ {$porcentajeMaquinariaIndustrial} COP) del total del rubro 'Maquinaria industrial'. Vuelva a diligenciar.");
            }

            if (PresupuestoValidationTrait::serviciosMantenimientoValidation($proyecto,  $proyectoPresupuesto, 'store', $request->primer_valor, $request->segundo_valor, $request->tercer_valor)) {
                $porcentajeProyecto = $proyecto->getTotalProyectoPresupuestoAttribute() * 0.05;
                return redirect()->back()->with('error', "Este estudio de mercado supera el 5% ($ {$porcentajeProyecto}) del COP total del proyecto. Vuelva a diligenciar.");
            }
        }

        if ($proyecto->tipoProyecto->lineaProgramatica->codigo == 23) {
            if (PresupuestoValidationTrait::adecuacionesYContruccionesValidation($proyecto,  $proyectoPresupuesto, 'store', $request->primer_valor, $request->segundo_valor, $request->tercer_valor)) {
                return redirect()->back()->with('error', "Antes de diligenciar información sobre este rubro de 'Adecuaciones y construcciones' tenga en cuenta que el total NO debe superar el valor de 100 salarios mínimos.");
            }
        }

        $proyectoLoteEstudioMercado = new ProyectoLoteEstudioMercado();
        $proyectoLoteEstudioMercado->numero_items = $request->numero_items;
        $segundoGrupoPresupuestal   = Str::slug(substr($proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->segundoGrupoPresupuestal->nombre, 0, 30), '-');

        $random = Str::random(5);
        $fichaTecnica = $request->ficha_tecnica;
        $nombreArchivoFichaTecnica  = "$proyecto->codigo-ficha-tecnica-$segundoGrupoPresupuestal-cod$random." . $fichaTecnica->extension();
        $archivoFichaTecnica        = $fichaTecnica->storeAs(
            'fichas-tecnicas',
            $nombreArchivoFichaTecnica
        );
        $proyectoLoteEstudioMercado->ficha_tecnica = $archivoFichaTecnica;

        $proyectoLoteEstudioMercado->proyectoPresupuesto()->associate($proyectoPresupuesto);
        $proyectoLoteEstudioMercado->save();

        $this->storeEstudioMercado($proyectoLoteEstudioMercado, $proyecto, $request->primer_valor, $request->primer_empresa, $request->primer_archivo);
        $this->storeEstudioMercado($proyectoLoteEstudioMercado, $proyecto, $request->segundo_valor, $request->segunda_empresa, $request->segundo_archivo);

        if ($request->tercer_valor && $request->tercer_empresa && $request->hasFile('tercer_archivo')) {
            // Tercer estudio de mercado
            $this->storeEstudioMercado($proyectoLoteEstudioMercado, $proyecto, $request->tercer_valor, $request->tercer_empresa, $request->tercer_archivo);
        }

        if ($proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->requiere_lote_estudio_mercado) {
            return redirect()->back()->with('success', 'El recurso se ha creado correctamente.');
        }

        return redirect()->route('convocatorias.proyectos.proyecto-presupuesto.index', [$convocatoria, $proyecto])->with('success', 'El recurso se ha creado correctamente.');
    }

    private function storeEstudioMercado($proyectoLoteEstudioMercado, $proyecto, $valor, $empresa, $archivo)
    {
        $estudioMercado = new EstudioMercado();

        $estudioMercado->valor      = $valor;
        $estudioMercado->empresa    = $empresa;

        $nombrePrimerEmpresa        = Str::slug(substr($empresa, 0, 30), '-');

        $nombreArchivo = "$proyecto->codigo-estudio-de-mercado-$nombrePrimerEmpresa-cod" . Str::random(5) . "." . $archivo->extension();
        $ruta = $archivo->storeAs(
            'estudios-mercado',
            $nombreArchivo
        );
        $estudioMercado->soporte = $ruta;

        $estudioMercado->proyectoLoteEstudioMercado()->associate($proyectoLoteEstudioMercado);
        $proyectoLoteEstudioMercado->estudiosMercado()->save($estudioMercado);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProyectoLoteEstudioMercado  $proyectoLoteEstudioMercado
     * @return \Illuminate\Http\Response
     */
    public function show(Convocatoria $convocatoria, Proyecto $proyecto, ProyectoPresupuesto $proyectoPresupuesto, ProyectoLoteEstudioMercado $proyectoLoteEstudioMercado)
    {
        $this->authorize('validar-autor', $proyecto);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProyectoLoteEstudioMercado  $proyectoLoteEstudioMercado
     * @return \Illuminate\Http\Response
     */
    public function edit(Convocatoria $convocatoria, Proyecto $proyecto, ProyectoPresupuesto $proyectoPresupuesto, ProyectoLoteEstudioMercado $proyectoLoteEstudioMercado)
    {
        $this->authorize('validar-autor', $proyecto);

        // Denega si el rubro no requiere lotes y ya hay un estudio de mercado guardado o si el rubro no requiere de estudio de mercado.
        if (!$proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->requiere_estudio_mercado) {
            return redirect()->route('convocatorias.proyectos.proyecto-presupuesto.index', [$convocatoria, $proyecto])->with('success', 'El recurso se ha creado correctamente.');
        }

        $proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->usoPresupuestal;
        $proyectoLoteEstudioMercado->estudiosMercado;

        return Inertia::render('Convocatorias/Proyectos/ProyectoPresupuesto/EstudioMercado/Edit', [
            'convocatoria'               => $convocatoria->only('id'),
            'proyecto'                   => $proyecto->only('id'),
            'proyectoPresupuesto'        => $proyectoPresupuesto,
            'proyectoLoteEstudioMercado' => $proyectoLoteEstudioMercado
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProyectoLoteEstudioMercado  $proyectoLoteEstudioMercado
     * @return \Illuminate\Http\Response
     */
    public function update(ProyectoLoteEstudioMercadoRequest $request, Convocatoria $convocatoria, Proyecto $proyecto, ProyectoPresupuesto $proyectoPresupuesto, ProyectoLoteEstudioMercado $proyectoLoteEstudioMercado)
    {
        $this->authorize('validar-autor', $proyecto);

        // Denega si el rubro no requiere lotes y ya hay un estudio de mercado guardado o si el rubro no requiere de estudio de mercado.
        if (!$proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->requiere_estudio_mercado) {
            return redirect()->route('convocatorias.proyectos.proyecto-presupuesto.index', [$convocatoria, $proyecto]);
        }

        if ($proyecto->tipoProyecto->lineaProgramatica->codigo == 66) {
            if (PresupuestoValidationTrait::serviciosEspecialesConstruccionValidation($proyecto, $proyectoPresupuesto, 'update', $request->primer_valor, $request->segundo_valor, $request->tercer_valor)) {
                $porcentajeMaquinariaIndustrial = PresupuestoValidationTrait::porcentajeMaquinariaIndustrial($proyecto);
                return redirect()->back()->with('error', "Este estudio de mercado supera el 5% ($ {$porcentajeMaquinariaIndustrial} COP) del total del rubro 'Maquinaria industrial'. Vuelva a diligenciar.");
            }

            if (PresupuestoValidationTrait::serviciosMantenimientoValidation($proyecto,  $proyectoPresupuesto, 'update', $request->primer_valor, $request->segundo_valor, $request->tercer_valor)) {
                $porcentajeProyecto = $proyecto->getTotalProyectoPresupuestoAttribute() * 0.05;
                return redirect()->back()->with('error', "Este estudio de mercado supera el 5% del ($ {$porcentajeProyecto}) COP total del proyecto. Vuelva a diligenciar.");
            }
        }

        if ($proyecto->tipoProyecto->lineaProgramatica->codigo == 23) {
            if (PresupuestoValidationTrait::adecuacionesYContruccionesValidation($proyecto,  $proyectoPresupuesto, 'update', $request->primer_valor, $request->segundo_valor, $request->tercer_valor)) {
                return redirect()->back()->with('error', "Antes de diligenciar información sobre este rubro de 'Adecuaciones y construcciones' tenga en cuenta que el total NO debe superar el valor de 100 salarios mínimos.");
            }
        }

        $proyectoLoteEstudioMercado->numero_items = $request->numero_items;
        if ($request->hasFile('ficha_tecnica')) {
            Storage::delete($proyectoLoteEstudioMercado->ficha_tecnica);
            $segundoGrupoPresupuestal   = Str::slug(substr($proyectoPresupuesto->convocatoriaPresupuesto->presupuestoSennova->segundoGrupoPresupuestal->nombre, 0, 30), '-');
            $random                     = Str::random(5);
            $fichaTecnica               = $request->ficha_tecnica;
            $nombreArchivoFichaTecnica  = "$proyecto->codigo-ficha-tecnica-$segundoGrupoPresupuestal-cod$random." . $fichaTecnica->extension();
            $archivoFichaTecnica        = $fichaTecnica->storeAs(
                'fichas-tecnicas',
                $nombreArchivoFichaTecnica
            );
            $proyectoLoteEstudioMercado->ficha_tecnica = $archivoFichaTecnica;
        }
        $proyectoLoteEstudioMercado->proyectoPresupuesto()->associate($proyectoPresupuesto);
        $proyectoLoteEstudioMercado->save();

        // Primer estudio de mercado
        $this->updateEstudioMercado($proyecto, $proyectoLoteEstudioMercado, $request->primer_estudio_mercado_id, $request->primer_empresa, $request->primer_valor, $request->primer_archivo, false);

        // Segundo estudio de mercado
        $this->updateEstudioMercado($proyecto, $proyectoLoteEstudioMercado, $request->segundo_estudio_mercado_id, $request->segunda_empresa, $request->segundo_valor, $request->segundo_archivo, false);


        // Tercer estudio de mercado
        if ($request->tercer_valor || $request->tercer_empresa) {
            $this->updateEstudioMercado($proyecto, $proyectoLoteEstudioMercado, $request->tercer_estudio_mercado_id, $request->tercer_empresa,  $request->tercer_valor, $request->tercer_archivo, true);
        }

        return redirect()->route('convocatorias.proyectos.proyecto-presupuesto.proyecto-lote-estudio-mercado.index', [$convocatoria, $proyecto, $proyectoPresupuesto])->with('success', 'El recurso se ha actualizado correctamente.');
    }

    public function updateEstudioMercado($proyecto, $proyectoLoteEstudioMercado, $estudioMercadoId, $empresa, $valor, $archivo, $tercerEstudioMercado)
    {
        $estudioMercado = $proyectoLoteEstudioMercado->estudiosMercado()->where('id', $estudioMercadoId)->first();
        if ($archivo) {
            Storage::delete($estudioMercado->soporte);
            $nombreEmpresa  = Str::slug(substr($empresa, 0, 30), '-');
            $ruta           = "$proyecto->codigo-estudio-de-mercado-$nombreEmpresa-cod" . Str::random(5) . "." . $archivo->extension();
            $soporte    = $archivo->storeAs(
                'estudios-mercado',
                $ruta
            );
        }

        if ($tercerEstudioMercado) {
            $proyectoLoteEstudioMercado->estudiosMercado()->where('id', $estudioMercadoId)->updateOrCreate(
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
     * @param  \App\Models\ProyectoLoteEstudioMercado  $proyectoLoteEstudioMercado
     * @return \Illuminate\Http\Response
     */
    public function destroy(Convocatoria $convocatoria, Proyecto $proyecto, ProyectoPresupuesto $proyectoPresupuesto, ProyectoLoteEstudioMercado $proyectoLoteEstudioMercado)
    {
        $this->authorize('validar-autor', $proyecto);

        foreach ($proyectoLoteEstudioMercado->estudiosMercado as $estudioMercado) {
            Storage::delete($estudioMercado->soporte);
        }

        $proyectoLoteEstudioMercado->delete();

        return redirect()->route('convocatorias.proyectos.proyecto-presupuesto.proyecto-lote-estudio-mercado.index', [$convocatoria, $proyecto, $proyectoPresupuesto])->with('success', 'El recurso se ha eliminado correctamente.');
    }

    /**
     * download
     *
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @param  mixed $proyectoPresupuesto
     * @param  mixed $proyectoLoteEstudioMercado
     * @return void
     */
    public function download(Convocatoria $convocatoria, Proyecto $proyecto, ProyectoPresupuesto $proyectoPresupuesto, ProyectoLoteEstudioMercado $proyectoLoteEstudioMercado)
    {
        return response()->download(storage_path("app/$proyectoLoteEstudioMercado->ficha_tecnica"));
    }

    /**
     * download
     *
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @param  mixed $proyectoPresupuesto
     * @param  mixed $estudioMercado
     * @return void
     */
    public function downloadSoporte(Convocatoria $convocatoria, Proyecto $proyecto, ProyectoPresupuesto $proyectoPresupuesto, EstudioMercado $estudioMercado)
    {
        return response()->download(storage_path("app/$estudioMercado->soporte"));
    }
}
