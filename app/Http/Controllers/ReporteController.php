<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Exports\ProyectosExport;
use App\Exports\PresupuestoRolesSennovaExport;
use App\Exports\EvaluacionesExport;
use App\Exports\EvaluacionesProyectosPresupuestoExport;
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
        $this->authorize('descargarReportes', [User::class]);

        return Inertia::render('Reportes/Index', [
            'filters'   => request()->all('search'),
            'convocatorias' => Convocatoria::select('id as value', 'descripcion as label')->get()
        ]);
    }

    /**
     * resumeProjects
     *
     * @param  mixed $convocatoria
     * @return void
     */
    public function resumeProjects(Convocatoria $convocatoria)
    {
        $this->authorize('descargarReportes', [User::class]);

        $proyectos = Proyecto::all();

        foreach ($proyectos as $proyecto) {
            $proyecto->update(['precio_proyecto' => $proyecto->precio_proyecto]);
        }

        return Excel::download(new ProyectosExport($convocatoria), 'proyectos-' . time() . '.xlsx');
    }

    /**
     * EvaluacionesExcel
     *
     * @return void
     */
    public function EvaluacionesExcel(Convocatoria $convocatoria)
    {
        $this->authorize('descargarReportes', [User::class]);

        return Excel::download(new EvaluacionesExport($convocatoria), 'evaluaciones-' . time() . '.xlsx');
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
        return Excel::download(new EvaluacionesProyectosPresupuestoExport($convocatoria), 'evaluaciones-proyecto-prespuestos-aprobados-'.time().'.xlsx');
    }
}
