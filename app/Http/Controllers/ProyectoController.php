<?php

namespace App\Http\Controllers;

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
    public function participantes(Convocatoria $convocatoria, Proyecto $proyecto)
    {
        // $this->authorize('viewAny', [Proyecto::class]);

        return Inertia::render('Convocatorias/Proyectos/Finder/Participantes', [
            'convocatoria'  => $convocatoria,
            'proyecto'      => $proyecto
        ]);
    }
}
