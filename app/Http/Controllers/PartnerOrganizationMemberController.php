<?php

namespace App\Http\Controllers;

use App\Http\Requests\PartnerOrganizationMemberRequest;
use App\Models\Call;
use App\Models\RDI;
use App\Models\PartnerOrganization;
use App\Models\PartnerOrganizationMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PartnerOrganizationMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Call $call, RDI $rdi, PartnerOrganization $partnerOrganization)
    {
        $this->authorize('viewAny', [PartnerOrganizationMember::class]);

        return Inertia::render('Calls/Projects/RDI/PartnerOrganizations/PartnerOrganizationMembers/Index', [
            'filters'   => request()->all('search'),
            'partnerOrganizationMembers' => PartnerOrganizationMember::orderBy('nombre', 'ASC')
                ->filterPartnerOrganizationMember(request()->only('search'))->paginate(),
            'call'                  => $call,
            'rdi'                   => $rdi,
            'partnerOrganization'   => $partnerOrganization
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Call $call, RDI $rdi, PartnerOrganization $partnerOrganization)
    {
        $this->authorize('create', [PartnerOrganizationMember::class]);

        return Inertia::render('Calls/Projects/RDI/PartnerOrganizations/PartnerOrganizationMembers/Create', [
            'documentTypes'         => json_decode(Storage::get('json/document-types.json'), true),
            'call'                  => $call->only('id'),
            'rdi'                   => $rdi->only('id'),
            'partnerOrganization'   => $partnerOrganization->only('id')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PartnerOrganizationMemberRequest $request, Call $call, RDI $rdi, PartnerOrganization $partnerOrganization)
    {
        $this->authorize('create', [PartnerOrganizationMember::class]);

        $partnerOrganizationMember = new PartnerOrganizationMember();
        $partnerOrganizationMember->name                = $request->name;
        $partnerOrganizationMember->email               = $request->email;
        $partnerOrganizationMember->document_type       = $request->document_type;
        $partnerOrganizationMember->document_number     = $request->document_number;
        $partnerOrganizationMember->cellphone_number    = $request->cellphone_number;
        $partnerOrganizationMember->partnerOrganization()->associate($partnerOrganization);

        $partnerOrganizationMember->save();

        return redirect()->route('calls.rdi.partner-organizations.partner-organization-members.index', [$call, $rdi, $partnerOrganization])->with('success', 'The resource has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PartnerOrganizationMember  $partnerOrganizationMember
     * @return \Illuminate\Http\Response
     */
    public function show(Call $call, RDI $rdi, PartnerOrganization $partnerOrganization, PartnerOrganizationMember $partnerOrganizationMember)
    {
        $this->authorize('view', [PartnerOrganizationMember::class, $partnerOrganizationMember]);

        return Inertia::render('Calls/Projects/RDI/PartnerOrganizations/PartnerOrganizationMembers/Show', [
            'call'                      => $call,
            'rdi'                       => $rdi,
            'partnerOrganization'       => $partnerOrganization,
            'partnerOrganizationMember' => $partnerOrganizationMember
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PartnerOrganizationMember  $partnerOrganizationMember
     * @return \Illuminate\Http\Response
     */
    public function edit(Call $call, RDI $rdi, PartnerOrganization $partnerOrganization, PartnerOrganizationMember $partnerOrganizationMember)
    {
        $this->authorize('update', [PartnerOrganizationMember::class, $partnerOrganizationMember]);

        return Inertia::render('Calls/Projects/RDI/PartnerOrganizations/PartnerOrganizationMembers/Editar', [
            'call'                      => $call->only('id'),
            'rdi'                       => $rdi->only('id'),
            'documentTypes'         => json_decode(Storage::get('json/document-types.json'), true),
            'partnerOrganization'       => $partnerOrganization->only('id'),
            'partnerOrganizationMember' => $partnerOrganizationMember,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PartnerOrganizationMember  $partnerOrganizationMember
     * @return \Illuminate\Http\Response
     */
    public function update(PartnerOrganizationMemberRequest $request, Call $call, RDI $rdi, PartnerOrganization $partnerOrganization, PartnerOrganizationMember $partnerOrganizationMember)
    {
        $this->authorize('update', [PartnerOrganizationMember::class, $partnerOrganizationMember]);

        $partnerOrganizationMember->name                = $request->name;
        $partnerOrganizationMember->email               = $request->email;
        $partnerOrganizationMember->document_type       = $request->document_type;
        $partnerOrganizationMember->document_number     = $request->document_number;
        $partnerOrganizationMember->cellphone_number    = $request->cellphone_number;
        $partnerOrganizationMember->partnerOrganization()->associate($partnerOrganization);

        $partnerOrganizationMember->save();

        return redirect()->back()->with('success', 'The resource has been updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PartnerOrganizationMember  $partnerOrganizationMember
     * @return \Illuminate\Http\Response
     */
    public function destroy(Call $call, RDI $rdi, PartnerOrganization $partnerOrganization, PartnerOrganizationMember $partnerOrganizationMember)
    {
        $this->authorize('delete', [PartnerOrganizationMember::class, $partnerOrganizationMember]);

        $partnerOrganizationMember->delete();

        return redirect()->route('calls.rdi.partner-organizations.partner-organization-members.index', [$call, $rdi, $partnerOrganization])->with('success', 'The resource has been deleted successfully.');
    }
}
