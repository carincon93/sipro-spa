<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConvocatoriaRequest;
use App\Models\Convocatoria;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProyectoController extends Controller
{
    /**
     * participantes
     *
     * @param  mixed $convocatoria
     * @param  mixed $proyecto
     * @return void
     */
    public function participantes(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        $proyecto->codigo_linea_programatica = $proyecto->tipoProyecto->lineaProgramatica->codigo;

        return Inertia::render('Convocatorias/Proyectos/Finder/Participantes', [
            'convocatoria'  => $convocatoria->only('id'),
            'proyecto'      => $proyecto->only('id', 'codigo_linea_programatica', 'precio_proyecto')
        ]);
    }

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
}
