<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Exports\ProyectosExport;
use App\Models\Convocatoria;

class ReporteController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('viewAny', [User::class]);

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
        $this->authorize('viewAny', [User::class]);

        return Excel::download(new ProyectosExport($convocatoria), 'proyectos-' . time() . '.xlsx');
    }

    /**
     * EvaluacionesExcel
     *
     * @return void
     */
    public function EvaluacionesExcel(Convocatoria $convocatoria)
    {
        return Excel::download(new EvaluacionesExport($convocatoria), 'Evaluaciones-' . time() . '.xlsx');
    }
}
