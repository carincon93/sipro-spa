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

        $IDi->proyecto->proyectoType->programmaticLine;
        $IDi->proyecto->makeHidden(
            'IDi', 
            'proyectoSennovaBudgets', 
            'updated_at',
        );

        return Inertia::render('Convocatorias/Proyectos/IDi/EntidadAliadas/Index', [
            'convocatoria'      => $convocatoria->only('id'),
            'IDi'       => $IDi,
            'filters'   => request()->all('search'),
            'EntidadAliadas' => EntidadAliada::where('IDi_id', $IDi->id)->orderBy('nombre', 'ASC')
                ->filterEntidadAliada(request()->only('search'))->select('id', 'name')->paginate(),
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

        $specificObjective = $IDi->proyecto->directCauses()->with('specificObjective')->get()->pluck('specificObjective')->flatten()->filter();

        return Inertia::render('Convocatorias/Proyectos/IDi/EntidadAliadas/Create', [
            'convocatoria'          => $convocatoria,
            'IDi'           => $IDi,
            'activities'    => Activity::whereIn('specific_objective_id',
                $specificObjective->map(function ($specificObjective) {
                    return $specificObjective->id;
                }))->orderBy('fecha_inicio', 'ASC')->get(),
            'EntidadAliadaTypes'  => json_decode(Storage::get('json/partner-organization-types.json'), true),
            'legalStatus'               => json_decode(Storage::get('json/legal-status.json'), true),
            'companyTypes'              => json_decode(Storage::get('json/company-types.json'), true)
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

        $EntidadAliada = new EntidadAliada();
        $EntidadAliada->partner_organization_type         = $request->partner_organization_type;
        $EntidadAliada->name                              = $request->name;
        $EntidadAliada->legal_status                      = $request->legal_status;
        $EntidadAliada->company_type                      = $request->company_type;
        $EntidadAliada->nit                               = $request->nit;
        $EntidadAliada->agreement_description             = $request->agreement_description;
        $EntidadAliada->research_group                    = $request->research_group;
        $EntidadAliada->gruplac_code                      = $request->gruplac_code;
        $EntidadAliada->gruplac_link                      = $request->gruplac_link;
        $EntidadAliada->knowledge_transfer_activities     = $request->knowledge_transfer_activities;
        $EntidadAliada->in_kind                           = $request->in_kind;
        $EntidadAliada->in_kind_description               = $request->in_kind_description;
        $EntidadAliada->funds                             = $request->funds;
        $EntidadAliada->funds_description                 = $request->funds_description;

        $companyName   = Str::slug(substr($request->name, 0, 30), '-');
        $random = Str::random(5);

        $letterOfIntent = $request->letter_of_intent;

        $letterOfIntentFileName = "{$IDi->proyecto->code}-carta-de-intencion-$companyName-cod$random.".$letterOfIntent->extension();
        $letterOfIntentFile = $letterOfIntent->storeAs(
            'letters-of-intent', $letterOfIntentFileName
        );
        $EntidadAliada->letter_of_intent = $letterOfIntentFile;

        $intellectualProperty = $request->intellectual_property;
        $intelectualPropertyFileName = "{$IDi->proyecto->code}-propiedad-intelectual-$companyName.".$intellectualProperty->extension();
        $intelectualPropertyFile = $intellectualProperty->storeAs(
            'intellectual-properties', $intelectualPropertyFileName
        );

        $EntidadAliada->intellectual_property = $intelectualPropertyFile;

        $EntidadAliada->IDi()->associate($IDi);
        $EntidadAliada->save();

        $EntidadAliada->activities()->attach($request->activity_id);

        return redirect()->route('convocatorias.IDi.partner-organizations.partner-organization-members.index', [$convocatoria, $IDi, $EntidadAliada])->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EntidadAliada  $EntidadAliada
     * @return \Illuminate\Http\Response
     */
    public function show(Convocatoria $convocatoria, IDi $IDi, EntidadAliada $EntidadAliada)
    {
        $this->authorize('view', [EntidadAliada::class, $EntidadAliada]);

        return Inertia::render('Convocatorias/Proyectos/IDi/EntidadAliadas/Show', [
            'EntidadAliada' => $EntidadAliada
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EntidadAliada  $EntidadAliada
     * @return \Illuminate\Http\Response
     */
    public function edit(Convocatoria $convocatoria, IDi $IDi, EntidadAliada $EntidadAliada)
    {
        $this->authorize('update', [EntidadAliada::class, $EntidadAliada]);

        $specificObjectives = $IDi->proyecto->directCauses()->with('specificObjective')->get()->pluck('specificObjective')->flatten()->filter();

        $EntidadAliada->EntidadAliadaMembers->only('id', 'name', 'email', 'cellphone_number');

        return Inertia::render('Convocatorias/Proyectos/IDi/EntidadAliadas/Editar', [
            'convocatoria'                  => $convocatoria,
            'IDi'                   => $IDi,
            'EntidadAliada'   => $EntidadAliada,
            'activities'            => Activity::whereIn('specific_objective_id',
                $specificObjectives->map(function ($specificObjective) {
                    return $specificObjective->id;
                }))->orderBy('fecha_inicio', 'ASC')->get(),
            'activityEntidadAliadas'  => $EntidadAliada->activities()->pluck('id'),
            'activitySpecificObjective'     => $EntidadAliada->activities()->with('specificObjective')->get()->pluck('specificObjective'),
            'EntidadAliadaTypes'      => json_decode(Storage::get('json/partner-organization-types.json'), true),
            'legalStatus'                   => json_decode(Storage::get('json/legal-status.json'), true),
            'companyTypes'                  => json_decode(Storage::get('json/company-types.json'), true)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EntidadAliada  $EntidadAliada
     * @return \Illuminate\Http\Response
     */
    public function update(EntidadAliadaRequest $request, Convocatoria $convocatoria, IDi $IDi, EntidadAliada $EntidadAliada)
    {
        $this->authorize('update', [EntidadAliada::class, $EntidadAliada]);

        $EntidadAliada->partner_organization_type         = $request->partner_organization_type;
        $EntidadAliada->name                              = $request->name;
        $EntidadAliada->legal_status                      = $request->legal_status;
        $EntidadAliada->company_type                      = $request->company_type;
        $EntidadAliada->nit                               = $request->nit;
        $EntidadAliada->agreement_description             = $request->agreement_description;
        $EntidadAliada->research_group                    = $request->research_group;
        $EntidadAliada->gruplac_code                      = $request->gruplac_code;
        $EntidadAliada->gruplac_link                      = $request->gruplac_link;
        $EntidadAliada->knowledge_transfer_activities     = $request->knowledge_transfer_activities;
        $EntidadAliada->in_kind                           = $request->in_kind;
        $EntidadAliada->in_kind_description               = $request->in_kind_description;
        $EntidadAliada->funds                             = $request->funds;
        $EntidadAliada->funds_description                 = $request->funds_description;

        $companyName    = Str::slug(substr($request->name, 0, 30), '-');
        $random         = Str::random(5);
        if ($request->hasFile('letter_of_intent')) {
            Storage::delete($EntidadAliada->letter_of_intent);
            $letterOfIntent = $request->letter_of_intent;
            $letterOfIntentFileName = "{$IDi->proyecto->code}-carta-de-intencion-$companyName-cod$random.".$letterOfIntent->extension();
            $letterOfIntentFile = $letterOfIntent->storeAs(
                'letters-of-intent', $letterOfIntentFileName
            );
            $EntidadAliada->letter_of_intent = $letterOfIntentFile;
        }

        if ($request->hasFile('intellectual_property')) {
            Storage::delete($EntidadAliada->intellectual_property);
            $intellectualProperty = $request->intellectual_property;
            $intelectualPropertyFileName = "{$IDi->proyecto->code}-propiedad-intelectual-$companyName-cod$random.".$intellectualProperty->extension();
            $intelectualPropertyFile = $intellectualProperty->storeAs(
                'intellectual-properties', $intelectualPropertyFileName
            );
            $EntidadAliada->intellectual_property = $intelectualPropertyFile;
        }

        $EntidadAliada->IDi()->associate($IDi);
        $EntidadAliada->activities()->sync($request->activity_id);

        $EntidadAliada->save();

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EntidadAliada  $EntidadAliada
     * @return \Illuminate\Http\Response
     */
    public function destroy(Convocatoria $convocatoria, IDi $IDi, EntidadAliada $EntidadAliada)
    {
        $this->authorize('delete', [EntidadAliada::class, $EntidadAliada]);

        Storage::delete($EntidadAliada->letter_of_intent);
        Storage::delete($EntidadAliada->intellectual_property);

        $EntidadAliada->delete();

        return redirect()->route('convocatorias.IDi.partner-organizations.index', [$convocatoria, $IDi])->with('success', 'The resource has been deleted successfully.');
    }
}
