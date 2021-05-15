<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { Inertia } from '@inertiajs/inertia'
    import { inertia, page, useForm } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Label from '@/Components/Label'
    import LoadingButton from '@/Components/LoadingButton'
    import Button from '@/Components/Button'
    import Textarea from '@/Components/Textarea'
    import Input from '@/Components/Input'
    import Switch from '@/Components/Switch'
    import File from '@/Components/File'
    import Select from '@/Components/Select'
    import Checkbox from '@smui/checkbox'
    import FormField from '@smui/form-field'
    import { Item, Text } from '@smui/list'
    import DataTable from '@/Components/DataTable'
    import Dialog from '@/Components/Dialog'
    import ResourceMenu from '@/Components/ResourceMenu'
    import InputError from '@/Components/InputError'

    export let errors
    export let call
    export let rdi
    export let partnerOrganization
    export let activities
    export let activityPartnerOrganizations
    export let activitySpecificObjective
    export let partnerOrganizationTypes
    export let legalStatus
    export let companyTypes

    let researchGroup = partnerOrganization.research_group != null ? true : false
    let agreement = partnerOrganization.agreement_description != null ? true : false

    $: $title = partnerOrganization ? partnerOrganization.name : null

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    let canIndexPartnerOrganizations = authUser.can.find((element) => element == 'partner-organizations.index') == 'partner-organizations.index'
    let canShowPartnerOrganizations = authUser.can.find((element) => element == 'partner-organizations.show') == 'partner-organizations.show'
    let canCreatePartnerOrganizations = authUser.can.find((element) => element == 'partner-organizations.create') == 'partner-organizations.create'
    let canEditPartnerOrganizations = authUser.can.find((element) => element == 'partner-organizations.edit') == 'partner-organizations.edit'
    let canDestroyPartnerOrganizations = authUser.can.find((element) => element == 'partner-organizations.delete') == 'partner-organizations.delete'

    let canCreatePartnerOrganizationMembers = authUser.can.find((element) => element == 'partner-organization-members.create') == 'partner-organization-members.create'
    let canEditPartnerOrganizationMembers = authUser.can.find((element) => element == 'partner-organization-members.edit') == 'partner-organization-members.edit'

    let dialog_open = false
    let sending = false

    let form = useForm({
        partner_organization_type: { value: partnerOrganizationTypes.find((item) => item.label == partnerOrganization.partner_organization_type)?.value, label: partnerOrganizationTypes.find((item) => item.label == partnerOrganization.partner_organization_type)?.label },
        name: partnerOrganization.name,
        legal_status: { value: legalStatus.find((item) => item.label == partnerOrganization.legal_status)?.value, label: legalStatus.find((item) => item.label == partnerOrganization.legal_status)?.label },
        company_type: { value: companyTypes.find((item) => item.label == partnerOrganization.company_type)?.value, label: companyTypes.find((item) => item.label == partnerOrganization.company_type)?.label },
        nit: partnerOrganization.nit,
        agreement_description: partnerOrganization.agreement_description,
        research_group: partnerOrganization.research_group,
        gruplac_code: partnerOrganization.gruplac_code,
        gruplac_link: partnerOrganization.gruplac_link,
        knowledge_transfer_activities: partnerOrganization.knowledge_transfer_activities,
        in_kind: partnerOrganization.in_kind,
        in_kind_description: partnerOrganization.in_kind_description,
        funds: partnerOrganization.funds,
        funds_description: partnerOrganization.funds_description,
        letter_of_intent: null,
        intellectual_property: null,
        activity_id: activityPartnerOrganizations,
    })

    function submit() {
        if (canEditPartnerOrganizations || isSuperAdmin) {
            $form.put(route('calls.rdi.partner-organizations.update', [call.id, rdi.id, partnerOrganization.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    function destroy() {
        if (canDestroyPartnerOrganizations || isSuperAdmin) {
            $form.delete(route('calls.rdi.partner-organizations.destroy', [call.id, rdi.id, partnerOrganization.id]))
        }
    }

    function handleFile(e, test) {
        $form[test] = e.target.files[0]
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    {#if canIndexPartnerOrganizations || canShowPartnerOrganizations || canEditPartnerOrganizations || canDestroyPartnerOrganizations || isSuperAdmin}
                        <a use:inertia href={route('calls.rdi.partner-organizations.index', [call.id, rdi.id])} class="text-indigo-400 hover:text-indigo-600">
                            {$_('Partner organizations.plural')}
                        </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    {partnerOrganization.name}
                </h1>
            </div>
        </div>
    </header>

    <div class="flex">
        <div class="bg-white rounded shadow max-w-3xl">
            <form on:submit|preventDefault={submit}>
                <fieldset class="p-8" disabled={canEditPartnerOrganizations || isSuperAdmin ? undefined : true}>
                    <div class="mt-4">
                        <Label required class="mb-4" labelFor="partner_organization_type" value="Tipo de entidad aliada" />
                        <Select id="partner_organization_type" items={partnerOrganizationTypes} bind:selectedValue={$form.partner_organization_type} error={errors.partner_organization_type} autocomplete="off" placeholder="Seleccione el nivel del riesgo" required />
                    </div>

                    <div class="mt-4">
                        <Label required class="mb-4" labelFor="name" value="Nombre de la entidad aliada/Centro de formación" />
                        <Textarea rows="4" id="name" error={errors.name} bind:value={$form.name} required />
                    </div>

                    <div class="mt-4">
                        <Label required class="mb-4" labelFor="legal_status" value="Naturaleza de la entidad" />
                        <Select id="legal_status" items={legalStatus} bind:selectedValue={$form.legal_status} error={errors.legal_status} autocomplete="off" placeholder="Seleccione el tipo de riesgo" required />
                    </div>

                    <div class="mt-4">
                        <Label required class="mb-4" labelFor="company_type" value="Tipo de empresa" />
                        <Select id="company_type" items={companyTypes} bind:selectedValue={$form.company_type} error={errors.company_type} autocomplete="off" placeholder="Seleccione la probabilidad" required />
                    </div>

                    <div class="mt-4">
                        <Label required class="mb-4" labelFor="nit" value="NIT" />
                        <Input id="nit" type="text" class="mt-1 block w-full" bind:value={$form.nit} error={errors.nit} required />
                    </div>

                    <div class="mt-4">
                        <p>¿Hay convenio?</p>
                        <Switch bind:checked={agreement} />
                    </div>
                    {#if agreement}
                        <div class="mt-4">
                            <Label required class="mb-4" labelFor="agreement_description" value="Descipción del convenio" />
                            <Textarea rows="4" id="agreement_description" error={errors.agreement_description} bind:value={$form.agreement_description} required />
                        </div>
                    {/if}

                    <div class="mt-4">
                        <p>¿La entidad aliada tiene grupo de investigación?</p>
                        <Switch bind:checked={researchGroup} />
                    </div>
                    {#if researchGroup}
                        <div class="mt-4">
                            <Label required class="mb-4" labelFor="research_group" value="Grupo de investigación" />
                            <Textarea rows="4" id="research_group" error={errors.research_group} bind:value={$form.research_group} required />
                        </div>

                        <div class="mt-4">
                            <Label required class="mb-4" labelFor="gruplac_code" value="Código del GrupLAC" />
                            <Input id="gruplac_code" type="text" class="mt-1 block w-full" error={errors.gruplac_code} placeholder="Ejemplo: COL0000000" bind:value={$form.gruplac_code} required={!researchGroup ? undefined : 'required'} />
                        </div>

                        <div class="mt-4">
                            <Label required class="mb-4" labelFor="gruplac_link" value="Enlace del GrupLAC" />
                            <Input id="gruplac_link" type="url" class="mt-1 block w-full" error={errors.gruplac_link} placeholder="Ejemplo: https://scienti.minciencias.gov.co/gruplac/jsp/Medicion/graficas/verPerfiles.jsp?id_convocatoria=0nroIdGrupo=0000000" bind:value={$form.gruplac_link} required={!researchGroup ? undefined : 'required'} />
                        </div>
                    {/if}

                    <div class="mt-4">
                        <Label required class="mb-4" labelFor="in_kind" value="Recursos en especie entidad aliada ($COP)" />
                        <Input id="in_kind" type="number" min="0" class="mt-1 block w-full" error={errors.in_kind} placeholder="COP" bind:value={$form.in_kind} required />
                    </div>

                    <div class="mt-4">
                        <Label required class="mb-4" labelFor="in_kind_description" value="Descripción de los recursos en especie aportados" />
                        <Textarea rows="4" id="in_kind_description" error={errors.in_kind_description} bind:value={$form.in_kind_description} required />
                    </div>

                    <div class="mt-4">
                        <Label required class="mb-4" labelFor="funds" value="Recursos en especie entidad aliada ($COP)" />
                        <Input id="funds" type="number" min="0" class="mt-1 block w-full" error={errors.funds} placeholder="COP" bind:value={$form.funds} required />
                    </div>

                    <div class="mt-4">
                        <Label required class="mb-4" labelFor="funds_description" value="Descripción de la destinación del dinero aportado" />
                        <Textarea rows="4" id="funds_description" error={errors.funds_description} bind:value={$form.funds_description} required />
                    </div>

                    <div class="mt-4">
                        <Label required class="mb-4" labelFor="knowledge_transfer_activities" value="Metodología o actividades de transferencia al centro de formación" />
                        <Textarea rows="4" id="knowledge_transfer_activities" error={errors.knowledge_transfer_activities} bind:value={$form.knowledge_transfer_activities} required />
                    </div>

                    <div class="mt-4">
                        <Label class="mb-4" labelFor="letter_of_intent" value="ANEXO 7. Carta de intención o acta que soporta el trabajo articulado con entidades aliadas (diferentes al SENA)" />
                        <File id="letter_of_intent" type="file" accept="application/pdf" class="mt-1 block w-full" bind:value={$form.letter_of_intent} error={errors.letter_of_intent} />
                    </div>

                    <div class="mt-4">
                        <Label class="mb-4" labelFor="intellectual_property" value="ANEXO 8. Propiedad intelectual" />
                        <File id="intellectual_property" type="file" accept="application/pdf" class="mt-1 block w-full" bind:value={$form.intellectual_property} error={errors.intellectual_property} />
                    </div>

                    {#if $form.progress}
                        <progress value={$form.progress.percentage} max="100" class="mt-4">
                            {$form.progress.percentage}%
                        </progress>
                    {/if}

                    <h6 class="mt-20 mb-12 text-2xl" id="activities">
                        {$_('Activities.plural')}
                    </h6>

                    <div class="bg-white rounded shadow overflow-hidden">
                        <div class="p-4">
                            <Label required class="mb-4" labelFor="activity_id" value="Relacione alguna actividad" />
                            <InputError message={errors.activity_id} />
                        </div>
                        <div class="grid grid-cols-2">
                            {#each activities as { id, description }, i}
                                <FormField>
                                    <Checkbox bind:group={$form.activity_id} value={id} />
                                    <span slot="label">{description}</span>
                                </FormField>
                            {/each}
                        </div>
                    </div>
                </fieldset>
                <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                    {#if canDestroyPartnerOrganizations || isSuperAdmin}
                        <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={(event) => (dialog_open = true)}>
                            Eliminar
                            {$_('Partner organizations.singular').toLowerCase()}
                        </button>
                    {/if}
                    {#if canEditPartnerOrganizations || isSuperAdmin}
                        <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">
                            Editar
                            {$_('Partner organizations.singular')}
                        </LoadingButton>
                    {/if}
                </div>
            </form>
        </div>
        <div class="px-4">
            <h1 class="mb-4">Enlaces de interés</h1>
            <ul>
                <li>
                    <a class="bg-indigo-100 hover:bg-indigo-200 mb-4 px-6 py-2 rounded-3xl text-center text-indigo-400" href="#partner-organization-members">
                        {$_('Partner organization members.plural')}
                    </a>
                </li>
                <li class="mt-6">
                    <a class="bg-indigo-100 hover:bg-indigo-200 mb-4 px-6 py-2 rounded-3xl text-center text-indigo-400" href="#specific-objectives">
                        {$_('Specific objectives.plural')} relacionados
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <DataTable class="mt-20">
        <div slot="title" id="partner-organization-members">
            {$_('Partner organization members.plural')}
        </div>

        <div slot="actions">
            {#if canCreatePartnerOrganizationMembers || isSuperAdmin}
                <Button on:click={() => Inertia.visit(route('calls.rdi.partner-organizations.partner-organization-members.create', [call.id, rdi.id, partnerOrganization.id]))} variant="raised">
                    {$_('Partner organization members.singular')}
                </Button>
            {/if}
        </div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl"> Nombre </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl"> Correo electrónico </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl"> Número de celular </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl"> Acciones </th>
            </tr>
        </thead>

        <tbody slot="tbody">
            {#each partnerOrganization.partner_organization_members as partnerOrganizationMember (partnerOrganizationMember.id)}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p class="px-6 py-4 flex items-center focus:text-indigo-500">
                            {partnerOrganizationMember.name}
                        </p>
                    </td>

                    <td class="border-t">
                        <p class="px-6 py-4 flex items-center focus:text-indigo-500">
                            {partnerOrganizationMember.email}
                        </p>
                    </td>

                    <td class="border-t">
                        <p class="px-6 py-4 flex items-center focus:text-indigo-500">
                            {partnerOrganizationMember.cellphone_number}
                        </p>
                    </td>

                    <td class="border-t td-actions">
                        <ResourceMenu>
                            {#if canShowPartnerOrganizations || canEditPartnerOrganizationMembers || canDestroyPartnerOrganizations || isSuperAdmin}
                                <Item on:SMUI:action={() => Inertia.visit(route('calls.rdi.partner-organizations.partner-organization-members.edit', [call.id, rdi.id, partnerOrganization.id, partnerOrganizationMember.id]))}>
                                    <Text>Ver detalles</Text>
                                </Item>
                            {:else}
                                <Item>
                                    <Text>No tiene permisos</Text>
                                </Item>
                            {/if}
                        </ResourceMenu>
                    </td>
                </tr>
            {/each}
        </tbody>
    </DataTable>

    <DataTable class="mt-20">
        <div slot="title" id="specific-objectives">
            {$_('Specific objectives.plural')}
        </div>

        <p class="mb-6" slot="caption">
            A continuación, se listan los objetivos específicos relacionados con la entidad aliada. Si dice 'Sin información registrada' por favor diríjase a las <a href="#activities" class="text-indigo-400">actividades</a> y relacione alguna.
        </p>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl"> Descripción </th>
            </tr>
        </thead>

        <tbody slot="tbody">
            {#each activitySpecificObjective as { id, description }}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p class="px-6 py-4 flex items-center focus:text-indigo-500">
                            {description}
                        </p>
                    </td>
                </tr>
            {/each}

            {#if activitySpecificObjective.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="4"> Sin información registrada </td>
                </tr>
            {/if}
        </tbody>
    </DataTable>

    <Dialog bind:open={dialog_open}>
        <div slot="title" class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            Eliminar recurso
        </div>
        <div slot="content">
            <p>
                ¿Está seguro(a) que desea eliminar este recurso?
                <br />
                Todos los datos se eliminarán de forma permanente.
                <br />
                Está acción no se puede deshacer.
            </p>
        </div>
        <div slot="actions">
            <div class="p-4">
                <Button on:click={(event) => (dialog_open = false)} variant={null}>Cancelar</Button>
                <Button variant="raised" on:click={destroy}>Confirmar</Button>
            </div>
        </div>
    </Dialog>
</AuthenticatedLayout>
