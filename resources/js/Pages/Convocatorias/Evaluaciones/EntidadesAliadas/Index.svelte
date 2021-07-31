<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page } from '@inertiajs/inertia-svelte'
    import { route, checkRole } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'

    import Pagination from '@/Shared/Pagination'
    import DataTableMenu from '@/Shared/DataTableMenu'
    import { Item, Text } from '@smui/list'
    import DataTable from '@/Shared/DataTable'
    import EvaluationStepper from '@/Shared/EvaluationStepper'
    import InfoMessage from '@/Shared/InfoMessage'

    export let convocatoria
    export let evaluacion
    export let proyecto
    export let tipoEntidad
    export let entidadesAliadas

    $title = 'Entidades aliadas'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])
</script>

<AuthenticatedLayout>
    <EvaluationStepper {convocatoria} {evaluacion} {proyecto} />

    <DataTable class="mt-20" routeParams={[convocatoria.id, evaluacion.id]}>
        <div slot="title">Entidades aliadas</div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Nombre</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Tipo de entidad aliada</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl text-center th-actions">Acciones</th>
            </tr>
        </thead>

        <tbody slot="tbody">
            {#each entidadesAliadas.data as entidadAliada (entidadAliada.id)}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-indigo-500">
                            {entidadAliada.nombre}
                        </p>
                    </td>
                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-indigo-500">
                            {entidadAliada.tipo}
                        </p>
                    </td>
                    <td class="border-t td-actions">
                        <DataTableMenu class={entidadesAliadas.data.length < 4 ? 'z-50' : ''}>
                            {#if isSuperAdmin || checkRole(authUser, [11])}
                                <Item on:SMUI:action={() => Inertia.visit(route('convocatorias.evaluaciones.entidades-aliadas.edit', [convocatoria.id, evaluacion.id, entidadAliada.id]))}>
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

            {#if entidadesAliadas.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="4">Sin información registrada</td>
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={entidadesAliadas.links} />

    <hr class="mt-10 mb-10" />

    <h1 class="text-3xl mt-24 mb-8 text-center">Evaluación</h1>
    <InfoMessage>
        El puntaje se asigna automáticamente.
        <br />
        <strong>Puntaje:</strong>
        {evaluacion.entidad_aliada_puntaje}
        <br />
        <strong>Tipo de entidad aliada:</strong>
        {tipoEntidad}
        <br />
        <strong>Código dependencia presupuestal (SIIF):</strong>
        {proyecto.codigo_linea_programatica}
    </InfoMessage>
</AuthenticatedLayout>
