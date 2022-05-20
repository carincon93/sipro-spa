<?php

namespace App\Http\Controllers;

use App\Exports\ComentariosEvaluacionesExport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Inertia\Inertia;
use App\Exports\PresupuestoRolesSennovaExport;
use App\Exports\EvaluacionesExport;
use App\Exports\EvaluacionesProyectosPresupuestoExport;
use App\Exports\InfoGruposLineasSemillerosExport;
use App\Exports\InfoProyectosCapacidadInstaladaExport;
use App\Exports\InfoProyectosExport;
use App\Exports\InfoProyectosIdiExport;
use App\Exports\InfoProyectosStExport;
use App\Exports\InfoProyectosCulturaInnovacionExport;
use App\Exports\InfoProyectosTaExport;
use App\Exports\InfoProyectosTpExport;
use App\Models\Convocatoria;
use App\Models\Proyecto;

class ReporteController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('descargar-reportes', [User::class]);

        return Inertia::render('Reportes/Index', [
            'filters'   => request()->all('search'),
            'convocatorias' => Convocatoria::select('id as value', 'descripcion as label')->get()
        ]);
    }

    /**
     * resumenProyectos
     *
     * @param  mixed $convocatoria
     * @return void
     */
    public function resumenProyectos(Convocatoria $convocatoria)
    {
        $this->authorize('descargar-reportes', [User::class]);

        $proyectos = Proyecto::all();

        foreach ($proyectos as $proyecto) {
            $proyecto->update(['precio_proyecto' => $proyecto->precio_proyecto]);
        }

        return Excel::download(new InfoProyectosExport($convocatoria), 'proyectos-' . time() . '.xlsx');
    }

    /**
     * EvaluacionesExcel
     *
     * @return void
     */
    public function EvaluacionesExcel(Convocatoria $convocatoria)
    {
        $this->authorize('descargar-reportes', [User::class]);

        return Excel::download(new EvaluacionesExport($convocatoria), 'evaluaciones-' . time() . '.xlsx');
    }

    /**
     * comentariosEvaluacionesExcel
     *
     * @return void
     */
    public function comentariosEvaluacionesExcel(Convocatoria $convocatoria)
    {
        $this->authorize('descargar-reportes', [User::class]);

        return Excel::download(new ComentariosEvaluacionesExport($convocatoria), 'comentarios-evaluaciones-' . time() . '.xlsx');
    }

    /**
     * resumePresupuestos
     *
     * @param  mixed $convocatoria
     * @return void
     */
    public function resumePresupuestos(Convocatoria $convocatoria)
    {
        return Excel::download(new PresupuestoRolesSennovaExport($convocatoria), 'prespuestos-roles-' . time() . '.xlsx');
    }

    /**
     * EvaluacionesProyectosPresupuestoExport
     *
     * @return void
     */
    public function EvaluacionesProyectosPresupuestoExport(Convocatoria $convocatoria)
    {
        return Excel::download(new EvaluacionesProyectosPresupuestoExport($convocatoria), 'evaluaciones-proyecto-prespuestos-aprobados-' . time() . '.xlsx');
    }

    /**
     * proyectosTaExport
     *
     * @return void
     */
    public function proyectosTaExport(Convocatoria $convocatoria)
    {
        return Excel::download(new InfoProyectosTaExport($convocatoria), 'proyectos-ta' . time() . '.xlsx');
    }

    /**
     * proyectosIdiExport
     *
     * @return void
     */
    public function proyectosIdiExport(Convocatoria $convocatoria)
    {
        return Excel::download(new InfoProyectosIdiExport($convocatoria), 'proyectos-idi' . time() . '.xlsx');
    }

    /**
     * proyectosTpExport
     *
     * @return void
     */
    public function proyectosTpExport(Convocatoria $convocatoria)
    {
        return Excel::download(new InfoProyectosTpExport($convocatoria), 'proyectos-tp' . time() . '.xlsx');
    }

    /**
     * proyectosStExport
     *
     * @return void
     */
    public function proyectosStExport(Convocatoria $convocatoria)
    {
        return Excel::download(new InfoProyectosStExport($convocatoria), 'proyectos-st' . time() . '.xlsx');
    }

    /** proyectosCulturaInnovacionExport
     *
     * @return void
     */
    public function proyectosCulturaInnovacionExport(Convocatoria $convocatoria)
    {
        return Excel::download(new InfoProyectosCulturaInnovacionExport($convocatoria), 'proyectosCulturaInnovacion' . time() . '.xlsx');
    }

    /** gruposLineasSemillerosExport
     *
     * @return void
     */
    public function gruposLineasSemillerosExport()
    {
        return Excel::download(new InfoGruposLineasSemillerosExport(), 'gruposLineasSemilleros' . time() . '.xlsx');
    }

    /** proyectosCapacidadInstaladaExport
     *
     * @return void
     */
    public function proyectosCapacidadInstaladaExport()
    {
        return Excel::download(new InfoProyectosCapacidadInstaladaExport(), 'proyectosCapacidadInstalada' . time() . '.xlsx');
    }
}
