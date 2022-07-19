<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'

    import InfoMessage from '@/Shared/InfoMessage'
    import Dialog from '@/Shared/Dialog'
    import Button from '@/Shared/Button'
    import Pagination from '@/Shared/Pagination'
    import DataTable from '@/Shared/DataTable'
    import DataTableMenu from '@/Shared/DataTableMenu'
    import { Item, Text } from '@smui/list'

    export let ambientesModernizacion
    export let codigosSgpsFaltantes
    export let allowedToCreate

    let seguimientosDialog = false

    $title = 'Ambientes de modernización'

    /**
     * Validar si el usuario autenticado es SuperAdmin
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let filters = {}

    let seguimientos = []
    let seguimientoId
    function configurarDialogoSeguimiento(ambienteModernizacion) {
        seguimientos = ambienteModernizacion.seguimiento_ambiente_modernizacion.ambientes_modernizacion
        seguimientoId = ambienteModernizacion.seguimiento_ambiente_modernizacion.id
        seguimientosDialog = true
    }

    let ambienteModernizacionId
    let dialogEliminar = false
    let allowedToDestroy
    function destroy() {
        if (allowedToDestroy) {
            Inertia.delete(route('ambientes-modernizacion.destroy', ambienteModernizacionId), {
                preserveScroll: true,
                onFinish: () => {
                    dialogEliminar = false
                },
            })
        }
    }
</script>

<AuthenticatedLayout>
    <h1 class="text-2xl text-center">Seguimiento post cierre - Ambientes de modernización SENNOVA</h1>

    <InfoMessage class="mt-10">
        <h1 class="font-black text-center mb-10">Proyectos sin seguimiento</h1>
        <ul class="pl-4 list-disc">
            {#each codigosSgpsFaltantes as codigo}
                <li>
                    <strong>Título: </strong>{codigo.titulo}
                    <br />
                    <strong>Código: </strong>SGPS-{codigo.codigo_sgps + '-' + codigo.year_ejecucion}
                </li>
            {/each}
        </ul>
    </InfoMessage>
    <DataTable class="mt-20">
        <div slot="actions">
            {#if allowedToCreate}
                <Button on:click={() => Inertia.visit(route('ambientes-modernizacion.create'))} variant="raised">Crear seguimiento ambiente de modernización</Button>
            {/if}
        </div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Código </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Regional </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Nombre </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Estado </th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl text-center th-actions"> Acciones </th>
            </tr>
        </thead>
        <tbody slot="tbody">
            {#each ambientesModernizacion.data as ambienteModernizacion (ambienteModernizacion.id)}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-violet-500">
                            {ambienteModernizacion.seguimiento_ambiente_modernizacion.codigo}
                        </p>
                    </td>
                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-violet-500">
                            {ambienteModernizacion.seguimiento_ambiente_modernizacion.centro_formacion.regional.nombre}
                        </p>
                    </td>
                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-violet-500">
                            {ambienteModernizacion.nombre_ambiente}
                        </p>
                    </td>

                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-violet-500">{ambienteModernizacion.estado}</p>
                    </td>

                    <td class="border-t td-actions">
                        <DataTableMenu class={ambientesModernizacion.data.length < 3 ? 'z-50' : ''}>
                            <Item on:SMUI:action={() => configurarDialogoSeguimiento(ambienteModernizacion)}>
                                <Text>Revisar seguimientos</Text>
                            </Item>

                            {#if ambienteModernizacion.allowed.to_destroy}
                                <Item on:SMUI:action={() => ((ambienteModernizacionId = ambienteModernizacion.id), (dialogEliminar = true), (allowedToDestroy = ambienteModernizacion.allowed.to_destroy))}>
                                    <Text>Eliminar</Text>
                                </Item>
                            {/if}
                        </DataTableMenu>
                    </td>
                </tr>
            {/each}

            {#if ambientesModernizacion.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="5"> Sin información registrada </td>
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={ambientesModernizacion.links} />

    <Dialog bind:open={seguimientosDialog}>
        <div slot="title" class="flex items-center flex-col mt-4">Seleccione una fecha de realización de seguimiento:</div>
        <div slot="content">
            <div class="grid grid-cols-3 gap-2">
                {#each seguimientos as seguimiento}
                    <Button on:click={() => Inertia.visit(route('ambientes-modernizacion.edit', seguimiento.id))} variant="outlined">{seguimiento.fecha_seguimiento}</Button>
                {/each}
            </div>
        </div>
        <div slot="actions">
            <div class="p-4">
                <Button on:click={() => ((seguimientosDialog = false), (seguimientoId = null))} variant={null}>Cancelar</Button>
                <Button on:click={() => Inertia.visit(route('ambientes-modernizacion.create', 'seguimiento_id=' + seguimientoId))} variant="raised">Asociar seguimiento ambiente de modernización</Button>
            </div>
        </div>
    </Dialog>

    <Dialog bind:open={dialogEliminar}>
        <div slot="title">
            <div class="text-center">Eliminar recurso</div>
            <div class="relative bg-violet-100 text-violet-600 p-5 h-44 w-1/3 m-auto my-10" style="border-radius: 41% 59% 70% 30% / 32% 40% 60% 68% ;">
                <figure>
                    <img src="/images/eliminar.png" alt="" class="h-44 m-auto" />
                </figure>
            </div>
            <div class="text-center">
                ¿Está seguro (a) que desea eliminar este proyecto?<br />Una vez eliminado el proyecto, todos sus recursos y datos se eliminarán de forma permanente.
            </div>
        </div>
        <div slot="content" />
        <div slot="actions">
            <div class="p-4">
                <Button on:click={() => (dialogEliminar = false)} variant={null}>Cancelar</Button>
                <Button variant="raised" type="button" on:click={() => destroy()}>Confirmar</Button>
            </div>
        </div>
    </Dialog>
</AuthenticatedLayout>
