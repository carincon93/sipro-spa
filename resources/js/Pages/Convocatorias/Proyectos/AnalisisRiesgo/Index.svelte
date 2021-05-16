<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'

    import Button from '@/Components/Button'
    import Pagination from '@/Components/Pagination'
    import DataTable from '@/Components/DataTable'
    import ResourceMenu from '@/Components/ResourceMenu'
    import { Item, Text } from '@smui/list'
    import Stepper from '@/Components/Stepper'

    export let convocatoria
    export let proyecto
    export let analisisRiesgos

    $title = 'Análisis de riesgos'

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
        <div slot="title">Análisis de riesgos</div>

        <h2 class="text-center mt-10 mb-24" slot="caption">Debe ingresar mínimo un análisis de riesgo por cada nivel (A nivel de objetivo general - A nivel de actividades - A nivel de productos).</h2>

        <div slot="actions">
            {#if isSuperAdmin}
                <Button on:click={() => Inertia.visit(route('convocatorias.proyectos.analisis-riesgos.create', [convocatoria.id, proyecto.id]))} variant="raised">Crear análisis de riesgo</Button>
            {/if}
        </div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">Nombre</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">Nivel</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">Acciones</th>
            </tr>
        </thead>

        <tbody slot="tbody">
            {#each analisisRiesgos.data as analisisRiesgo (analisisRiesgo.id)}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p class="px-6 py-4 flex items-center focus:text-indigo-500">
                            {analisisRiesgo.descripcion}
                        </p>
                    </td>

                    <td class="border-t">
                        <p class="px-6 py-4 flex items-center focus:text-indigo-500">
                            {analisisRiesgo.nivel}
                        </p>
                    </td>

                    <td class="border-t td-actions">
                        <ResourceMenu>
                            {#if isSuperAdmin}
                                <Item on:SMUI:action={() => Inertia.visit(route('convocatorias.proyectos.analisis-riesgos.edit', [convocatoria.id, proyecto.id, analisisRiesgo.id]))}>
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

            {#if analisisRiesgos.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="3">Sin información registrada</td>
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={analisisRiesgos.links} />
</AuthenticatedLayout>
