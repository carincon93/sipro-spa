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

    export let call
    export let project
    export let riskAnalysis = []

    $title = $_('Risk analysis.plural')

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    let canIndexRiskAnalysis =
        authUser.can.find((element) => element == 'risk-analysis.index') ==
        'risk-analysis.index'
    let canShowRiskAnalysis =
        authUser.can.find((element) => element == 'risk-analysis.show') ==
        'risk-analysis.show'
    let canCreateRiskAnalysis =
        authUser.can.find((element) => element == 'risk-analysis.create') ==
        'risk-analysis.create'
    let canEditRiskAnalysis =
        authUser.can.find((element) => element == 'risk-analysis.edit') ==
        'risk-analysis.edit'
    let canDestroyRiskAnalysis =
        authUser.can.find((element) => element == 'risk-analysis.destroy') ==
        'risk-analysis.delete'

    let filters = {}
</script>

<AuthenticatedLayout>
    <Stepper {call} {project} />

    <DataTable class="mt-20">
        <div slot="title">{$_('Risk analysis.plural')}</div>

        <h2 class="text-center mt-10 mb-24" slot="caption">
            Debe ingresar mínimo un análisis de riesgo por cada nivel (A nivel
            de objetivo general - A nivel de actividades - A nivel de
            productos).
        </h2>

        <div slot="actions">
            {#if canCreateRiskAnalysis || isSuperAdmin}
                <Button
                    on:click={() =>
                        Inertia.visit(
                            route('calls.projects.risk-analysis.create', [
                                call.id,
                                project.id,
                            ]),
                        )}
                    variant="raised"
                >
                    Crear
                    {$_('Risk analysis.singular')}
                </Button>
            {/if}
        </div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl"
                    >Nombre</th
                >
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl"
                    >Nivel</th
                >
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl"
                    >Acciones</th
                >
            </tr>
        </thead>

        <tbody slot="tbody">
            {#each riskAnalysis.data as riskAnalysis (riskAnalysis.id)}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p
                            class="px-6 py-4 flex items-center focus:text-indigo-500"
                        >
                            {riskAnalysis.description}
                        </p>
                    </td>

                    <td class="border-t">
                        <p
                            class="px-6 py-4 flex items-center focus:text-indigo-500"
                        >
                            {riskAnalysis.level}
                        </p>
                    </td>

                    <td class="border-t td-actions">
                        <ResourceMenu>
                            {#if canShowRiskAnalysis || canEditRiskAnalysis || canDestroyRiskAnalysis || isSuperAdmin}
                                <Item
                                    on:SMUI:action={() =>
                                        Inertia.visit(
                                            route(
                                                'calls.projects.risk-analysis.edit',
                                                [
                                                    call.id,
                                                    project.id,
                                                    riskAnalysis.id,
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

            {#if riskAnalysis.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="3"
                        >Sin información registrada</td
                    >
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={riskAnalysis.links} />
</AuthenticatedLayout>
