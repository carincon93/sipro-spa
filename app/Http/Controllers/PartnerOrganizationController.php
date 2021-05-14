<?php

namespace App\Http\Controllers;

use App\Http\Requests\PartnerOrganizationRequest;
use App\Models\Call;
use App\Models\RDI;
use App\Models\PartnerOrganization;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PartnerOrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Call $call, RDI $rdi)
    {
        $this->authorize('viewAny', [PartnerOrganization::class, $rdi]);

        $rdi->project->projectType->programmaticLine;
        $rdi->project->makeHidden(
            'rdi', 
            'projectSennovaBudgets', 
            'updated_at',
        );

        return Inertia::render('Calls/Projects/RDI/PartnerOrganizations/Index', [
            'call'      => $call->only('id'),
            'rdi'       => $rdi,
            'filters'   => request()->all('search'),
            'partnerOrganizations' => PartnerOrganization::where('rdi_id', $rdi->id)->orderBy('nombre', 'ASC')
                ->filterPartnerOrganization(request()->only('search'))->select('id', 'name')->paginate(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Call $call, RDI $rdi)
    {
        $this->authorize('create', [PartnerOrganization::class]);

        $specificObjective = $rdi->project->directCauses()->with('specificObjective')->get()->pluck('specificObjective')->flatten()->filter();

        return Inertia::render('Calls/Projects/RDI/PartnerOrganizations/Create', [
            'call'          => $call,
            'rdi'           => $rdi,
            'activities'    => Activity::whereIn('specific_objective_id',
                $specificObjective->map(function ($specificObjective) {
                    return $specificObjective->id;
                }))->orderBy('fecha_incio', 'ASC')->get(),
            'partnerOrganizationTypes'  => json_decode(Storage::get('json/partner-organization-types.json'), true),
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
    public function store(PartnerOrganizationRequest $request, Call $call, RDI $rdi)
    {
        $this->authorize('create', [PartnerOrganization::class]);

        $partnerOrganization = new PartnerOrganization();
        $partnerOrganization->partner_organization_type         = $request->partner_organization_type;
        $partnerOrganization->name                              = $request->name;
        $partnerOrganization->legal_status                      = $request->legal_status;
        $partnerOrganization->company_type                      = $request->company_type;
        $partnerOrganization->nit                               = $request->nit;
        $partnerOrganization->agreement_description             = $request->agreement_description;
        $partnerOrganization->research_group                    = $request->research_group;
        $partnerOrganization->gruplac_code                      = $request->gruplac_code;
        $partnerOrganization->gruplac_link                      = $request->gruplac_link;
        $partnerOrganization->knowledge_transfer_activities     = $request->knowledge_transfer_activities;
        $partnerOrganization->in_kind                           = $request->in_kind;
        $partnerOrganization->in_kind_description               = $request->in_kind_description;
        $partnerOrganization->funds                             = $request->funds;
        $partnerOrganization->funds_description                 = $request->funds_description;

        $companyName   = Str::slug(substr($request->name, 0, 30), '-');
        $random = Str::random(5);

        $letterOfIntent = $request->letter_of_intent;

        $letterOfIntentFileName = "{$rdi->project->code}-carta-de-intencion-$companyName-cod$random.".$letterOfIntent->extension();
        $letterOfIntentFile = $letterOfIntent->storeAs(
            'letters-of-intent', $letterOfIntentFileName
        );
        $partnerOrganization->letter_of_intent = $letterOfIntentFile;

        $intellectualProperty = $request->intellectual_property;
        $intelectualPropertyFileName = "{$rdi->project->code}-propiedad-intelectual-$companyName.".$intellectualProperty->extension();
        $intelectualPropertyFile = $intellectualProperty->storeAs(
            'intellectual-properties', $intelectualPropertyFileName
        );

        $partnerOrganization->intellectual_property = $intelectualPropertyFile;

        $partnerOrganization->rdi()->associate($rdi);
        $partnerOrganization->save();

        $partnerOrganization->activities()->attach($request->activity_id);

        return redirect()->route('calls.rdi.partner-organizations.partner-organization-members.index', [$call, $rdi, $partnerOrganization])->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PartnerOrganization  $partnerOrganization
     * @return \Illuminate\Http\Response
     */
    public function show(Call $call, RDI $rdi, PartnerOrganization $partnerOrganization)
    {
        $this->authorize('view', [PartnerOrganization::class, $partnerOrganization]);

        return Inertia::render('Calls/Projects/RDI/PartnerOrganizations/Show', [
            'partnerOrganization' => $partnerOrganization
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PartnerOrganization  $partnerOrganization
     * @return \Illuminate\Http\Response
     */
    public function edit(Call $call, RDI $rdi, PartnerOrganization $partnerOrganization)
    {
        $this->authorize('update', [PartnerOrganization::class, $partnerOrganization]);

        $specificObjectives = $rdi->project->directCauses()->with('specificObjective')->get()->pluck('specificObjective')->flatten()->filter();

        $partnerOrganization->partnerOrganizationMembers->only('id', 'name', 'email', 'cellphone_number');

        return Inertia::render('Calls/Projects/RDI/PartnerOrganizations/Editar', [
            'call'                  => $call,
            'rdi'                   => $rdi,
            'partnerOrganization'   => $partnerOrganization,
            'activities'            => Activity::whereIn('specific_objective_id',
                $specificObjectives->map(function ($specificObjective) {
                    return $specificObjective->id;
                }))->orderBy('fecha_incio', 'ASC')->get(),
            'activityPartnerOrganizations'  => $partnerOrganization->activities()->pluck('id'),
            'activitySpecificObjective'     => $partnerOrganization->activities()->with('specificObjective')->get()->pluck('specificObjective'),
            'partnerOrganizationTypes'      => json_decode(Storage::get('json/partner-organization-types.json'), true),
            'legalStatus'                   => json_decode(Storage::get('json/legal-status.json'), true),
            'companyTypes'                  => json_decode(Storage::get('json/company-types.json'), true)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PartnerOrganization  $partnerOrganization
     * @return \Illuminate\Http\Response
     */
    public function update(PartnerOrganizationRequest $request, Call $call, RDI $rdi, PartnerOrganization $partnerOrganization)
    {
        $this->authorize('update', [PartnerOrganization::class, $partnerOrganization]);

        $partnerOrganization->partner_organization_type         = $request->partner_organization_type;
        $partnerOrganization->name                              = $request->name;
        $partnerOrganization->legal_status                      = $request->legal_status;
        $partnerOrganization->company_type                      = $request->company_type;
        $partnerOrganization->nit                               = $request->nit;
        $partnerOrganization->agreement_description             = $request->agreement_description;
        $partnerOrganization->research_group                    = $request->research_group;
        $partnerOrganization->gruplac_code                      = $request->gruplac_code;
        $partnerOrganization->gruplac_link                      = $request->gruplac_link;
        $partnerOrganization->knowledge_transfer_activities     = $request->knowledge_transfer_activities;
        $partnerOrganization->in_kind                           = $request->in_kind;
        $partnerOrganization->in_kind_description               = $request->in_kind_description;
        $partnerOrganization->funds                             = $request->funds;
        $partnerOrganization->funds_description                 = $request->funds_description;

        $companyName    = Str::slug(substr($request->name, 0, 30), '-');
        $random         = Str::random(5);
        if ($request->hasFile('letter_of_intent')) {
            Storage::delete($partnerOrganization->letter_of_intent);
            $letterOfIntent = $request->letter_of_intent;
            $letterOfIntentFileName = "{$rdi->project->code}-carta-de-intencion-$companyName-cod$random.".$letterOfIntent->extension();
            $letterOfIntentFile = $letterOfIntent->storeAs(
                'letters-of-intent', $letterOfIntentFileName
            );
            $partnerOrganization->letter_of_intent = $letterOfIntentFile;
        }

        if ($request->hasFile('intellectual_property')) {
            Storage::delete($partnerOrganization->intellectual_property);
            $intellectualProperty = $request->intellectual_property;
            $intelectualPropertyFileName = "{$rdi->project->code}-propiedad-intelectual-$companyName-cod$random.".$intellectualProperty->extension();
            $intelectualPropertyFile = $intellectualProperty->storeAs(
                'intellectual-properties', $intelectualPropertyFileName
            );
            $partnerOrganization->intellectual_property = $intelectualPropertyFile;
        }

        $partnerOrganization->rdi()->associate($rdi);
        $partnerOrganization->activities()->sync($request->activity_id);

        $partnerOrganization->save();

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PartnerOrganization  $partnerOrganization
     * @return \Illuminate\Http\Response
     */
    public function destroy(Call $call, RDI $rdi, PartnerOrganization $partnerOrganization)
    {
        $this->authorize('delete', [PartnerOrganization::class, $partnerOrganization]);

        Storage::delete($partnerOrganization->letter_of_intent);
        Storage::delete($partnerOrganization->intellectual_property);

        $partnerOrganization->delete();

        return redirect()->route('calls.rdi.partner-organizations.index', [$call, $rdi])->with('success', 'The resource has been deleted successfully.');
    }
}
