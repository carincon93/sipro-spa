<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page } from '@inertiajs/inertia-svelte'
    import { _ } from 'svelte-i18n'

    import Pagination from '@/Components/Pagination'
    import DataTable from '@/Components/DataTable'
    import Create from './Create'

    import Stepper from '@/Components/Stepper'

    export let call
    export let project
    export let projectAnnexes = []
    export let annexes

    let sending = false

    $title = $_('Project annexes.plural')

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    let canIndexProjectAnnexes = authUser.can.find((element) => element == 'project-annexes.index') == 'project-annexes.index'
    let canShowProjectAnnexes = authUser.can.find((element) => element == 'project-annexes.show') == 'project-annexes.show'
    let canCreateProjectAnnexes = authUser.can.find((element) => element == 'project-annexes.create') == 'project-annexes.create'
    let canEditProjectAnnexes = authUser.can.find((element) => element == 'project-annexes.edit') == 'project-annexes.edit'
    let canDestroyProjectAnnexes = authUser.can.find((element) => element == 'project-annexes.destroy') == 'project-annexes.delete'

    let filters = {}
</script>

<AuthenticatedLayout>
    <Stepper {call} {project} />

    <DataTable class="mt-20">
        <div slot="title">{$_('Project annexes.plural')}</div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">Nombre</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">Archivo</th>
            </tr>
        </thead>

        <tbody slot="tbody">
            {#each annexes.data as annexe (annexe.id)}
                <tr>
                    <td class="border-t">
                        {#if canShowProjectAnnexes || canCreateProjectAnnexes || canEditProjectAnnexes || isSuperAdmin}
                            <p class="px-6 py-4 flex items-center focus:text-indigo-500">
                                {annexe.name}
                            </p>
                        {/if}
                    </td>
                    <td class="border-t">
                        {#if canShowProjectAnnexes || canCreateProjectAnnexes || canEditProjectAnnexes || isSuperAdmin}
                            <Create {call} {project} {annexe} {projectAnnexes} bind:sending />
                        {/if}
                    </td>
                </tr>
            {/each}

            {#if annexes.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="4">Sin información registrada</td>
                </tr>
            {:else if !canCreateProjectAnnexes && !canEditProjectAnnexes && !isSuperAdmin}
                <tr>
                    <td class="border-t px-6 py-4" colspan="4">No tiene permisos</td>
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={annexes.links} />
</AuthenticatedLayout>
