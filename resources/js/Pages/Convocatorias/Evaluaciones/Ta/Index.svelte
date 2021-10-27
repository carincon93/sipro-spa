<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'

    import Pagination from '@/Shared/Pagination'
    import DataTableMenu from '@/Shared/DataTableMenu'
    import { Item, Text } from '@smui/list'
    import DataTable from '@/Shared/DataTable'

    export let convocatoria
    export let ta

    $title = 'Proyectos Tecnoacademia'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])
</script>

<AuthenticatedLayout>
    <DataTable class="mt-20" routeParams={[convocatoria.id]}>
        <div slot="title">Tecnoacademia</div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Código </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Teacnoacademia </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Fecha de ejecución </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Estado </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl text-center th-actions"> Acciones </th>
            </tr>
        </thead>

        <tbody slot="tbody">
            {#each ta.data as { evaluacion_id, proyecto, fecha_ejecucion, iniciado, habilitado, finalizado }}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-indigo-500">
                            {proyecto.codigo}

                            {#if !habilitado}
                                <span class="block text-danger">Evaluación deshabilitada. No puede realizar la evaluación.</span>
                            {/if}
                        </p>
                    </td>
                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-indigo-500">
                            {proyecto.tecnoacademia_lineas_tecnoacademia[0]?.tecnoacademia.nombre}
                        </p>
                    </td>
                    <td class="border-t">
                        <p class="px-6 py-4">
                            {fecha_ejecucion}
                        </p>
                    </td>
                    <td class="border-t">
                        <p class="px-6 py-4">
                            {#if proyecto.modificable == true && proyecto.finalizado == false && proyecto.a_evaluar == false}
                                El proyecto se encuentra en subsanación
                            {:else}
                                {finalizado ? 'Evaluación finalizada' : iniciado ? 'Evaluación iniciada' : 'Sin evaluar'}
                            {/if}
                        </p>
                    </td>
                    <td class="border-t td-actions">
                        <DataTableMenu class={ta.data.length < 4 ? 'z-50' : ''}>
                            {#if isSuperAdmin || checkRole(authUser, [11])}
                                <Item on:SMUI:action={() => Inertia.visit(route('convocatorias.ta-evaluaciones.edit', [convocatoria.id, evaluacion_id]))}>
                                    <Text>Evaluar</Text>
                                </Item>
                            {:else}
                                <Item>
                                    <Text>No tiene permisos</Text>
                                </Item>
                            {/if}
                        </DataTableMenu>
                    </td>
                </tr>
            {/each}

            {#if ta.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="5"> Sin información registrada </td>
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={ta.links} />
</AuthenticatedLayout>
