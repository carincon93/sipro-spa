<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProjectRequest;
use App\Models\Call;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectController extends Controller
{
    /**
     * participants
     *
     * @return void
     */
    public function participants(Call $call, Project $project)
    {
        // $this->authorize('viewAny', [Project::class]);

        $project->projectType->programmaticLine;
        $project->makeHidden(
            'rdi', 
            'projectSennovaBudgets', 
            'updated_at',
        );

        return Inertia::render('Calls/Projects/Finder/Participants', [
            'call'      => $call,
            'project'   => $project
        ]);
    }
}
