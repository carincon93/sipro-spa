<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'

    import Button from '@/Components/Button'
    import Pagination from '@/Components/Pagination'
    import ResourceMenu from '@/Components/ResourceMenu'
    import { Item, Text } from '@smui/list'
    import DataTable from '@/Components/DataTable'
    import Stepper from '@/Components/Stepper'

    export let convocatoria
    export let proyecto
    export let entidadesAliadas

    $title = 'Entidades aliadas'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(1)

    function checkRole(roleId) {
        return (
            authUser.roles.filter(function (role) {
                return role.id == roleId
            }).length > 0
        )
    }

    let filters = {}
</script>

<AuthenticatedLayout>
    <Stepper {convocatoria} {proyecto} />

    <DataTable class="mt-20">
        <div slot="title">Entidades aliadas</div>

        <div slot="actions">
            {#if isSuperAdmin || checkRole(74)}
                <Button on:click={() => Inertia.visit(route('convocatorias.proyectos.entidades-aliadas.create', [convocatoria.id, proyecto.id]))} variant="raised">Crear entidad aliada</Button>
            {/if}
        </div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">Nombre</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl">Acciones</th>
            </tr>
        </thead>

        <tbody slot="tbody">
            {#each entidadesAliadas.data as entidadAliada (entidadAliada.id)}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p class="px-6 py-4 flex items-center focus:text-indigo-500">
                            {entidadAliada.nombre}
                        </p>
                    </td>
                    <td class="border-t td-actions">
                        <ResourceMenu>
                            {#if isSuperAdmin || checkRole(74)}
                                <Item on:SMUI:action={() => Inertia.visit(route('convocatorias.proyectos.entidades-aliadas.edit', [convocatoria.id, proyecto.id, entidadAliada.id]))}>
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

            {#if entidadesAliadas.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="4">Sin información registrada</td>
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={entidadesAliadas.links} />
</AuthenticatedLayout>
