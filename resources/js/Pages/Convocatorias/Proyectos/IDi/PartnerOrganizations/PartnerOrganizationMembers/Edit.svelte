<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Components/Input'
    import Label from '@/Components/Label'
    import Button from '@/Components/Button'
    import LoadingButton from '@/Components/LoadingButton'
    import Select from '@/Components/Select'
    import Dialog from '@/Components/Dialog'

    export let errors
    export let call
    export let rdi
    export let partnerOrganization
    export let partnerOrganizationMember
    export let documentTypes

    $: $title = partnerOrganizationMember
        ? partnerOrganizationMember.name
        : null

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    let canIndextPartnerOrganizationMembers =
        authUser.can.find(
            (element) => element == 'partner-organization-members.index',
        ) == 'partner-organization-members.index'
    let canShowtPartnerOrganizationMembers =
        authUser.can.find(
            (element) => element == 'partner-organization-members.show',
        ) == 'partner-organization-members.show'
    let canCreatetPartnerOrganizationMembers =
        authUser.can.find(
            (element) => element == 'partner-organization-members.create',
        ) == 'partner-organization-members.create'
    let canEditPartnerOrganizationMembers =
        authUser.can.find(
            (element) => element == 'partner-organization-members.edit',
        ) == 'partner-organization-members.edit'
    let canDestroytPartnerOrganizationMembers =
        authUser.can.find(
            (element) => element == 'partner-organization-members.delete',
        ) == 'partner-organization-members.delete'

    let dialog_open = false
    let sending = false
    let form = useForm({
        name: partnerOrganizationMember.name,
        email: partnerOrganizationMember.email,
        document_type: {
            value: partnerOrganizationMember.document_type,
            label: documentTypes.find(
                (item) => item.value == partnerOrganizationMember.document_type,
            )?.label,
        },
        document_number: partnerOrganizationMember.document_number,
        cellphone_number: partnerOrganizationMember.cellphone_number,
    })

    function submit() {
        if (canEditPartnerOrganizationMembers || isSuperAdmin) {
            $form.put(
                route(
                    'calls.rdi.partner-organizations.partner-organization-members.update',
                    [
                        call.id,
                        rdi.id,
                        partnerOrganization.id,
                        partnerOrganizationMember.id,
                    ],
                ),
                {
                    onStart: () => (sending = true),
                    onFinish: () => (sending = false),
                    preserveScroll: true,
                },
            )
        }
    }

    function destroy() {
        if (canDestroytPartnerOrganizationMembers || isSuperAdmin) {
            $form.delete(
                route(
                    'calls.rdi.partner-organizations.partner-organization-members.destroy',
                    [
                        call.id,
                        rdi.id,
                        partnerOrganization.id,
                        partnerOrganizationMember.id,
                    ],
                ),
            )
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div
            class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6"
        >
            <div>
                <h1>
                    {#if canIndextPartnerOrganizationMembers || canShowtPartnerOrganizationMembers || canEditPartnerOrganizationMembers || canDestroytPartnerOrganizationMembers || isSuperAdmin}
                        <a
                            use:inertia
                            href={route(
                                'calls.rdi.partner-organizations.partner-organization-members.index',
                                [call.id, rdi.id, partnerOrganization.id],
                            )}
                            class="text-indigo-400 hover:text-indigo-600"
                        >
                            {$_('Partner organization members.plural')}
                        </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    {partnerOrganizationMember.name}
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset
                class="p-8"
                disabled={canCreatetPartnerOrganizationMembers || isSuperAdmin
                    ? undefined
                    : true}
            >
                <div class="mt-4">
                    <Label
                        required
                        class="mb-4"
                        labelFor="name"
                        value="Nombre completo"
                    />
                    <Input
                        id="name"
                        type="text"
                        class="mt-1 block w-full"
                        bind:value={$form.name}
                        error={errors.name}
                        required
                    />
                </div>

                <div class="mt-4">
                    <Label
                        required
                        class="mb-4"
                        labelFor="email"
                        value="Correo electrónico"
                    />
                    <Input
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        bind:value={$form.email}
                        error={errors.email}
                        required
                    />
                </div>

                <div class="mt-4">
                    <Label
                        required
                        class="mb-4"
                        labelFor="document_type"
                        value="Tipo de documento"
                    />
                    <Select
                        id="document_type"
                        items={documentTypes}
                        bind:selectedValue={$form.document_type}
                        error={errors.document_type}
                        autocomplete="off"
                        placeholder="Seleccione un tipo de documento"
                        required
                    />
                </div>

                <div class="mt-4">
                    <Label
                        required
                        class="mb-4"
                        labelFor="document_number"
                        value="Número de documento"
                    />
                    <Input
                        id="document_number"
                        type="number"
                        min="0"
                        class="mt-1 block w-full"
                        bind:value={$form.document_number}
                        error={errors.document_number}
                        required
                    />
                </div>

                <div class="mt-4">
                    <Label
                        required
                        class="mb-4"
                        labelFor="cellphone_number"
                        value="Número de celular"
                    />
                    <Input
                        id="cellphone_number"
                        type="number"
                        min="0"
                        class="mt-1 block w-full"
                        bind:value={$form.cellphone_number}
                        error={errors.cellphone_number}
                        required
                    />
                </div>
            </fieldset>
            <div
                class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0"
            >
                {#if canDestroytPartnerOrganizationMembers || isSuperAdmin}
                    <button
                        class="text-red-600 hover:underline text-left"
                        tabindex="-1"
                        type="button"
                        on:click={(event) => (dialog_open = true)}
                    >
                        Eliminar
                        {$_(
                            'Partner organization members.singular',
                        ).toLowerCase()}
                    </button>
                {/if}
                {#if canEditPartnerOrganizationMembers || isSuperAdmin}
                    <LoadingButton
                        loading={sending}
                        class="btn-indigo ml-auto"
                        type="submit"
                    >
                        Editar
                        {$_('Partner organization members.singular')}
                    </LoadingButton>
                {/if}
            </div>
        </form>

        <Dialog bind:open={dialog_open}>
            <div slot="title" class="flex items-center">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6 mr-2 text-red-500"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                    />
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
                    <Button
                        on:click={(event) => (dialog_open = false)}
                        variant={null}>Cancelar</Button
                    >
                    <Button variant="raised" on:click={destroy}>
                        Confirmar
                    </Button>
                </div>
            </div>
        </Dialog>
    </div>
</AuthenticatedLayout>
