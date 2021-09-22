<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'

    import Button from '@/Shared/Button'
    import Pagination from '@/Shared/Pagination'
    import DataTable from '@/Shared/DataTable'
    import DataTableMenu from '@/Shared/DataTableMenu'
    import { Item, Text } from '@smui/list'

    export let evaluaciones

    $title = 'Evaluaciones'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let filters = {
        year: $page.props.filters.year,
    }

    console.log(evaluaciones)
</script>

<AuthenticatedLayout>
    <DataTable class="mt-20" bind:filters showFilters={true}>
        <div slot="title">Evaluaciones</div>

        <div slot="filters">
            <label for="year" class="block text-gray-700">Año:</label>
            <select id="year" class="mt-1 w-full form-select" bind:value={filters.year}>
                <option value={null}>Seleccione un año</option>
                <option value="2022">2022</option>
            </select>
        </div>
        <div slot="actions">
            {#if isSuperAdmin}
                <Button on:click={() => Inertia.visit(route('evaluaciones.create'))} variant="raised">Crear evaluación</Button>
            {/if}
        </div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Código</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Título</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Centro de formación</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Evaluador</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Estado</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl text-center th-actions">Acciones</th>
            </tr>
        </thead>
        <tbody slot="tbody">
            {#each evaluaciones.data as evaluacion}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-indigo-500">
                            {evaluacion.proyecto.codigo}
                        </p>
                    </td>
                    <td class="border-t">
                        <p class="px-6 py-4">
                            {evaluacion.proyecto.idi
                                ? evaluacion.proyecto.idi.titulo
                                : evaluacion.proyecto.cultura_innovacion
                                ? evaluacion.proyecto.cultura_innovacion.titulo
                                : evaluacion.proyecto.servicio_tecnologico
                                ? evaluacion.proyecto.servicio_tecnologico.titulo
                                : evaluacion.proyecto.tp?.nodo_tecnoparque
                                ? evaluacion.proyecto.tp?.titulo
                                : evaluacion.proyecto.tecnoacademia_lineas_tecnoacademia
                                ? evaluacion.proyecto.tecnoacademia_lineas_tecnoacademia[0]?.tecnoacademia.nombre
                                : null}
                        </p>
                    </td>
                    <td class="border-t">
                        <p class="px-6 py-4">
                            {evaluacion.proyecto.centro_formacion.nombre + ' - Código: ' + evaluacion.proyecto.centro_formacion.codigo}
                        </p>
                    </td>
                    <td class="border-t">
                        <p class="px-6 py-4">
                            {evaluacion.evaluador.nombre}
                        </p>
                    </td>
                    <td class="border-t">
                        <p class="px-6 py-4">
                            {evaluacion.habilitado ? 'Habilitado' : 'Deshabilitado'}
                        </p>
                    </td>
                    <td class="border-t td-actions">
                        <DataTableMenu class={evaluaciones.data.length < 4 ? 'z-50' : ''}>
                            {#if isSuperAdmin || checkRole(authUser, [4, 17, 18, 20, 19, 5])}
                                <Item on:SMUI:action={() => Inertia.visit(route('evaluaciones.edit', evaluacion.id))}>
                                    <Text>Ver detalles</Text>
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

            {#if evaluaciones.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="6">Sin información registrada</td>
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={evaluaciones.links} />
</AuthenticatedLayout>
