<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { Inertia } from '@inertiajs/inertia'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Label from '@/Components/Label'
    import LoadingButton from '@/Components/LoadingButton'
    import Textarea from '@/Components/Textarea'
    import Input from '@/Components/Input'
    import Switch from '@/Components/Switch'
    import File from '@/Components/File'
    import Select from '@/Components/Select'
    import Checkbox from '@smui/checkbox'
    import FormField from '@smui/form-field'
    import InputError from '@/Components/InputError'

    export let call
    export let rdi
    export let errors
    export let activities
    export let partnerOrganizationTypes
    export let legalStatus
    export let companyTypes

    let researchGroup = false
    let agreement = false

    $: $title = $_('Create') + ' ' + $_('Partner organizations.singular').toLowerCase()

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

    let sending = false
    let form = useForm({
        partner_organization_type: '',
        name: '',
        legal_status: '',
        company_type: '',
        nit: '',
        agreement_description: '',
        research_group: '',
        gruplac_code: '',
        gruplac_link: '',
        knowledge_transfer_activities: '',
        in_kind: '',
        in_kind_description: '',
        funds: '',
        funds_description: '',
        letter_of_intent: null,
        intellectual_property: null,
        activity_id: [],
    })

    function submit() {
        if (canCreatePartnerOrganizations || isSuperAdmin) {
            $form.post(route('calls.rdi.partner-organizations.store', [call.id, rdi.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
            })
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    {#if canIndexPartnerOrganizations || canCreatePartnerOrganizations || isSuperAdmin}
                        <a use:inertia href={route('calls.rdi.partner-organizations.index', [call.id, rdi.id])} class="text-indigo-400 hover:text-indigo-600">
                            {$_('Partner organizations.plural')}
                        </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    Crear
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={canCreatePartnerOrganizations || isSuperAdmin ? undefined : true}>
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
                    <Label required class="mb-4" labelFor="letter_of_intent" value="ANEXO 7. Carta de intención o acta que soporta el trabajo articulado con entidades aliadas (diferentes al SENA)" />
                    <File id="letter_of_intent" type="file" accept="application/pdf" class="mt-1 block w-full" bind:value={$form.letter_of_intent} error={errors.letter_of_intent} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="intellectual_property" value="ANEXO 8. Propiedad intelectual" />
                    <File id="intellectual_property" type="file" accept="application/pdf" class="mt-1 block w-full" bind:value={$form.intellectual_property} error={errors.intellectual_property} required />
                </div>

                {#if $form.progress}
                    <progress value={$form.progress.percentage} max="100" class="mt-4">
                        {$form.progress.percentage}%
                    </progress>
                {/if}

                <h6 class="mt-20">{$_('Activities.plural')}</h6>
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
                {#if canCreatePartnerOrganizations || isSuperAdmin}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">
                        Crear
                        {$_('Partner organizations.singular')}
                    </LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
