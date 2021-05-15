<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'

    import ResourceMenu from '@/Components/ResourceMenu'
    import { Item, Text } from '@smui/list'
    import DataTable from '@/Components/DataTable'
    import Button from '@/Components/Button'
    import Pagination from '@/Components/Pagination'

    export let call
    export let rdi
    export let partnerOrganization
    export let partnerOrganizationMembers = []

    $title = $_('Partner organization members.plural')

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    let canIndexPartnerOrganizationMembers =
        authUser.can.find(
            (element) => element == 'partner-organization-members.index',
        ) == 'partner-organization-members.index'
    let canShowPartnerOrganizationMembers =
        authUser.can.find(
            (element) => element == 'partner-organization-members.show',
        ) == 'partner-organization-members.show'
    let canCreatePartnerOrganizationMembers =
        authUser.can.find(
            (element) => element == 'partner-organization-members.create',
        ) == 'partner-organization-members.create'
    let canEditPartnerOrganizationMembers =
        authUser.can.find(
            (element) => element == 'partner-organization-members.edit',
        ) == 'partner-organization-members.edit'
    let canDestroyPartnerOrganizationMembers =
        authUser.can.find(
            (element) => element == 'partner-organization-members.delete',
        ) == 'partner-organization-members.delete'

    let canIndexPartnerOrganizations =
        authUser.can.find(
            (element) => element == 'partner-organizations.index',
        ) == 'partner-organizations.index'
    let canEditPartnerOrganizations =
        authUser.can.find(
            (element) => element == 'partner-organizations.edit',
        ) == 'partner-organizations.edit'

    let filters = {}
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div
            class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6"
        >
            <div>
                <h1>
                    {#if canIndexPartnerOrganizations || canEditPartnerOrganizations || isSuperAdmin}
                        <a
                            use:inertia
                            href={route(
                                'calls.rdi.partner-organizations.index',
                                [call.id, rdi.id],
                            )}
                            class="text-indigo-400 hover:text-indigo-600"
                        >
                            {$_('Partner organizations.plural')}
                        </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    <a
                        use:inertia
                        href={route('calls.rdi.partner-organizations.edit', [
                            call.id,
                            rdi.id,
                            partnerOrganization.id,
                        ])}
                        class="text-indigo-400 hover:text-indigo-600"
                    >
                        {partnerOrganization.name}
                    </a>
                    <span class="text-indigo-400 font-medium">/</span>
                    {$_('Partner organization members.plural')}
                </h1>
            </div>
        </div>
    </header>

    <DataTable class="mt-20">
        <div slot="title">{$_('Partner organization members.plural')}</div>

        <div slot="actions">
            {#if canCreatePartnerOrganizationMembers || isSuperAdmin}
                <Button
                    on:click={() =>
                        Inertia.visit(
                            route(
                                'calls.rdi.partner-organizations.partner-organization-members.create',
                                [call.id, rdi.id, partnerOrganization.id],
                            ),
                        )}
                    variant="raised"
                >
                    Crear
                    {$_('Partner organization members.singular')}
                </Button>
            {/if}
        </div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl"
                    >Nombre</th
                >
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl"
                    >Correo electrónico</th
                >
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl"
                    >Número de celular</th
                >
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl"
                    >Acciones</th
                >
            </tr>
        </thead>

        <tbody slot="tbody">
            {#each partnerOrganizationMembers.data as partnerOrganizationMember (partnerOrganizationMember.id)}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p
                            class="px-6 py-4 flex items-center focus:text-indigo-500"
                        >
                            {partnerOrganizationMember.name}
                        </p>
                    </td>

                    <td class="border-t">
                        <p
                            class="px-6 py-4 flex items-center focus:text-indigo-500"
                        >
                            {partnerOrganizationMember.email}
                        </p>
                    </td>

                    <td class="border-t">
                        <p
                            class="px-6 py-4 flex items-center focus:text-indigo-500"
                        >
                            {partnerOrganizationMember.cellphone_number}
                        </p>
                    </td>

                    <td class="border-t td-actions">
                        <ResourceMenu>
                            {#if canShowPartnerOrganizationMembers || canEditPartnerOrganizationMembers || canDestroyPartnerOrganizationMembers || isSuperAdmin}
                                <Item
                                    on:SMUI:action={() =>
                                        Inertia.visit(
                                            route(
                                                'calls.rdi.partner-organizations.partner-organization-members.edit',
                                                [
                                                    call.id,
                                                    rdi.id,
                                                    partnerOrganization.id,
                                                    partnerOrganizationMember.id,
                                                ],
                                            ),
                                        )}
                                >
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

            {#if partnerOrganizationMembers.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="4"
                        >Sin información registrada</td
                    >
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={partnerOrganizationMembers.links} />
</AuthenticatedLayout>
