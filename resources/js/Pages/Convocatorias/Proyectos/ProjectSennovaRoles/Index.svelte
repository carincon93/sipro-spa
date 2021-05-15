<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'

    import Pagination from '@/Components/Pagination'
    import ResourceMenu from '@/Components/ResourceMenu'
    import { Item, Text } from '@smui/list'
    import Button from '@/Components/Button'
    import DataTable from '@/Components/DataTable'
    import Stepper from '@/Components/Stepper'

    export let call
    export let project
    export let projectSennovaRoles = []

    $title = $_('Project sennova roles.plural')

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    let canIndexProjectSennovaRoles =
        authUser.can.find(
            (element) => element == 'project-sennova-roles.index',
        ) == 'project-sennova-roles.index'
    let canShowProjectSennovaRoles =
        authUser.can.find(
            (element) => element == 'project-sennova-roles.show',
        ) == 'project-sennova-roles.show'
    let canCreateProjectSennovaRoles =
        authUser.can.find(
            (element) => element == 'project-sennova-roles.create',
        ) == 'project-sennova-roles.create'
    let canEditProjectSennovaRoles =
        authUser.can.find(
            (element) => element == 'project-sennova-roles.edit',
        ) == 'project-sennova-roles.edit'
    let canDestroyProjectSennovaRoles =
        authUser.can.find(
            (element) => element == 'project-sennova-roles.delete',
        ) == 'project-sennova-roles.delete'

    let filters = {}
</script>

<AuthenticatedLayout>
    <Stepper {call} {project} />

    <DataTable class="mt-20">
        <div slot="title">{$_('Project sennova roles.plural')}</div>

        <div slot="actions">
            {#if canCreateProjectSennovaRoles || isSuperAdmin}
                <Button
                    on:click={() =>
                        Inertia.visit(
                            route(
                                'calls.projects.project-sennova-roles.create',
                                [call.id, project.id],
                            ),
                        )}
                    variant="raised"
                >
                    Crear
                    {$_('Project sennova roles.singular')}
                </Button>
            {/if}
        </div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl"
                    >Nombre</th
                >
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl"
                    >Salario</th
                >
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl"
                    >Acciones</th
                >
            </tr>
        </thead>

        <tbody slot="tbody">
            {#each projectSennovaRoles.data as projectSennovaRole (projectSennovaRole.id)}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p
                            class="px-6 py-4 flex items-center focus:text-indigo-500"
                        >
                            {projectSennovaRole?.call_sennova_role?.sennova_role
                                ?.name}
                        </p>
                    </td>
                    <td class="border-t">
                        <p
                            class="px-6 py-4 flex items-center focus:text-indigo-500"
                        >
                            {projectSennovaRole?.call_sennova_role?.salary}
                        </p>
                    </td>
                    <td class="border-t td-actions">
                        <ResourceMenu>
                            {#if canShowProjectSennovaRoles || canEditProjectSennovaRoles || canDestroyProjectSennovaRoles || isSuperAdmin}
                                <Item
                                    on:SMUI:action={() =>
                                        Inertia.visit(
                                            route(
                                                'calls.projects.project-sennova-roles.edit',
                                                [
                                                    call.id,
                                                    project.id,
                                                    projectSennovaRole.id,
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

            {#if projectSennovaRoles.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="4"
                        >Sin información registrada</td
                    >
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={projectSennovaRoles.links} />
</AuthenticatedLayout>
