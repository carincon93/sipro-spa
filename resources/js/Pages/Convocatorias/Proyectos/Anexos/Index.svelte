<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Pagination from '@/Shared/Pagination'
    import DataTable from '@/Shared/DataTable'
    import Create from './Create'

    import Stepper from '@/Shared/Stepper'

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
    let isSuperAdmin = checkRole(authUser, [1])
</script>

<AuthenticatedLayout>
    <Stepper {convocatoria} {proyecto} />

    <DataTable class="mt-20" routeParams={[convocatoria.id, proyecto.id]}>
        <div slot="title">Anexos</div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Nombre</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Archivo</th>
            </tr>
        </thead>

        <tbody slot="tbody">
            {#each anexos.data as anexo (anexo.id)}
                <tr>
                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-indigo-500">
                            {anexo.nombre}
                            {#if anexo.archivo}
                                <a target="_blank" class="text-indigo-400 underline inline-block mt-4 mb-4 flex" download href={route('anexos.download', [anexo.id])}>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Descargar formato
                                </a>
                            {/if}
                        </p>
                    </td>
                    <td class="border-t">
                        {#if isSuperAdmin || checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13])}
                            <Create {convocatoria} {proyecto} {anexo} bind:proyectoAnexo bind:sending />
                        {/if}
                    </td>
                </tr>
            {/each}

            {#if anexos.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="4">Sin información registrada</td>
                </tr>
            {:else if !isSuperAdmin || (!checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]) && proyecto.modificable == false)}
                <tr>
                    <td class="border-t px-6 py-4" colspan="4">No tiene permisos</td>
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={anexos.links} />
</AuthenticatedLayout>
