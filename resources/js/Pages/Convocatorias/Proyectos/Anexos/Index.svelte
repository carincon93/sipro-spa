<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page } from '@inertiajs/inertia-svelte'
    import { _ } from 'svelte-i18n'

    import Pagination from '@/Components/Pagination'
    import DataTable from '@/Components/DataTable'
    import Create from './Create'

    import Stepper from '@/Components/Stepper'

    export let convocatoria
    export let proyecto
    export let proyectoAnexo
    export let anexos

    let sending = false

    $title = 'Anexos'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0

    let filters = {}
</script>

<AuthenticatedLayout>
    <Stepper {convocatoria} {proyecto} />

    <DataTable class="mt-20">
        <div slot="title">Anexos</div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">Nombre</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">Archivo</th>
            </tr>
        </thead>

        <tbody slot="tbody">
            {#each anexos.data as anexo (anexo.id)}
                <tr>
                    <td class="border-t">
                        {#if isSuperAdmin}
                            <p class="px-6 py-4 flex items-center focus:text-indigo-500">
                                {anexo.nombre}
                            </p>
                        {/if}
                    </td>
                    <td class="border-t">
                        {#if isSuperAdmin}
                            <Create {convocatoria} {proyecto} {anexo} {proyectoAnexo} bind:sending />
                        {/if}
                    </td>
                </tr>
            {/each}

            {#if anexos.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="4">Sin información registrada</td>
                </tr>
            {:else if !canCreateProyectoAnexo && !canEditProyectoAnexo && !isSuperAdmin}
                <tr>
                    <td class="border-t px-6 py-4" colspan="4">No tiene permisos</td>
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={anexos.links} />
</AuthenticatedLayout>
