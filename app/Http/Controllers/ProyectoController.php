<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProyectoRequest;
use App\Models\Convocatoria;
use App\Models\Proyecto;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProyectoController extends Controller
{
    /**
     * participants
     *
     * @return void
     */
    public function participants(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        // $this->authorize('viewAny', [Proyecto::class]);

        $proyecto->tipoProyecto->lineaProgramatica;
        $proyecto->makeHidden(
            'idi', 
            'projectSennovaBudgets', 
            'updated_at',
        );

        return Inertia::render('Convocatorias/Proyectos/Finder/Participants', [
            'convocatoria'  => $convocatoria,
            'proyecto'      => $proyecto
        ]);
    }
}
