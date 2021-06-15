<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import CreateEstudioMercado from '@/Pages/Convocatorias/Proyectos/ProyectoPresupuesto/EstudioMercado/Create'
    import { inertia, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'

    import LoadingButton from '@/Shared/LoadingButton'
    import Pagination from '@/Shared/Pagination'
    import Button from '@/Shared/Button'
    import DataTableMenu from '@/Shared/DataTableMenu'
    import { Item, Text } from '@smui/list'
    import Dialog from '@/Shared/Dialog'

    export let errors
    export let convocatoria
    export let proyecto
    export let proyectoPresupuesto
    export let proyectoLotesEstudioMercado
    export let usoPresupuestal
    export let requiereEstudioMercado
    export let requiereLoteEstudioMercado
    export let convocatoriaPresupuesto
    export let presupuestoSennova

    $title = 'Estudios de mercado'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let sending = false
    let dialogOpen
    $: dialogOpen = Object.keys(errors).length > 0 ? true : false
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1 class="overflow-ellipsis overflow-hidden w-breadcrumb-ellipsis whitespace-nowrap">
                    {#if isSuperAdmin || checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13])}
                        <a use:inertia href={route('convocatorias.proyectos.presupuesto.index', [convocatoria.id, proyecto.id])} class="text-indigo-400 hover:text-indigo-600"> Presupuesto </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    {#if isSuperAdmin || checkPermission(authUser, [3, 5, 8, 11])}
                        <a use:inertia href={route('convocatorias.proyectos.presupuesto.edit', [convocatoria.id, proyecto.id, proyectoPresupuesto.id])} class="text-indigo-400 hover:text-indigo-600">
                            {usoPresupuestal.descripcion}
                        </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    Estudios de mercado
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-indigo-100 p-5 text-indigo-600 mb-10">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5" style="transform: translateX(-50px)">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        {#if requiereEstudioMercado}
            {#if requiereLoteEstudioMercado}
                <p class="mb-4">
                    El uso presupuestal <strong>{usoPresupuestal.descripcion}</strong>
                    permite que se agreguen multiples estudios de mercado. De clic en el botón
                    <strong> 'Añadir estudio de mercado' </strong> y añada los que considere necesarios.
                </p>
            {/if}
            {#if presupuestoSennova.mensaje}
                <p class="mb-4">
                    <strong>Importante: </strong>{presupuestoSennova.mensaje}
                </p>
            {/if}
        {/if}

        <h1 class="mt-24 mb-8 text-center text-3xl">Estudios de mercado</h1>

        <div>
            {#if (!requiereLoteEstudioMercado && requiereEstudioMercado && proyectoLotesEstudioMercado.data.length < 1) || (requiereEstudioMercado && requiereLoteEstudioMercado)}
                <div class="mb-6 flex justify-end items-center">
                    {#if isSuperAdmin || (checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]) && proyecto.modificable == true)}
                        <Button on:click={() => (dialogOpen = true)} variant="raised">Añadir estudio de mercado</Button>
                    {/if}
                </div>
            {/if}
        </div>
        <div class="bg-white rounded shadow">
            <table class="w-full whitespace-no-wrap table-fixed data-table">
                <thead>
                    <tr class="text-left font-bold">
                        <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full" colspan="3">Estudio(s) de mercado</th>
                    </tr>
                </thead>

                <tbody>
                    {#each proyectoLotesEstudioMercado.data as loteEstudioMercado, i}
                        <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                            <td class="border-t px-6 pt-6 pb-4">
                                <h1>Estudio de mercado #{i + 1}</h1>
                                <p>
                                    Cantidad de items: {loteEstudioMercado.numero_items}
                                </p>
                                <a target="_blank" class="text-indigo-400 underline inline-block mb-4" download href={route('convocatorias.proyectos.presupuesto.lote.download', [convocatoria.id, proyecto.id, proyectoPresupuesto.id, loteEstudioMercado.id])}>Descargar ficha técnica</a>
                            </td>
                            <td class="border-t px-6 pt-6 pb-4">
                                {#each loteEstudioMercado.estudios_mercado as { id, empresa, valor }}
                                    <div>
                                        <strong>Nombre de la compañía: </strong>{empresa}
                                    </div>
                                    <div>
                                        <strong>Valor: </strong>${new Intl.NumberFormat('de-DE').format(valor)} COP
                                    </div>

                                    <a target="_blank" class="text-indigo-400 underline inline-block mb-4" download href={route('convocatorias.proyectos.presupuesto.download-soporte', [convocatoria.id, proyecto.id, proyectoPresupuesto.id, id])}>Descargar soporte</a>
                                {/each}
                            </td>

                            <td class="border-t px-6 pt-6 pb-4">
                                <DataTableMenu class={proyectoLotesEstudioMercado.data.length < 4 ? 'z-50' : ''}>
                                    {#if isSuperAdmin || (checkPermission(authUser, [3, 5, 8, 11]) && proyecto.modificable == true)}
                                        <Item on:SMUI:action={() => Inertia.visit(route('convocatorias.proyectos.presupuesto.lote.edit', [convocatoria.id, proyecto.id, proyectoPresupuesto.id, loteEstudioMercado.id]))}>
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

                    {#if proyectoLotesEstudioMercado.data.length === 0}
                        <tr>
                            <td class="border-t px-6 py-4" colspan="3">Sin información registrada</td>
                        </tr>
                    {/if}
                </tbody>

                <tfoot>
                    <tr>
                        <td class="border-t px-6 pt-6 pb-4" colspan="3">
                            <h1>
                                Valor promedio del estudio de mercado: <strong>${new Intl.NumberFormat('de-DE').format(proyectoPresupuesto.promedio)} COP</strong>
                            </h1>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Dialog -->
        <Dialog bind:open={dialogOpen} id="estudio-mercado">
            <div slot="title" />
            <div slot="content">
                <CreateEstudioMercado bind:dialogOpen {sending} {errors} {convocatoria} {proyecto} {proyectoPresupuesto} {proyectoLotesEstudioMercado} {convocatoriaPresupuesto} />
            </div>

            <div slot="actions" class="block flex w-full">
                <Button on:click={() => (dialogOpen = false)} type="button" variant={null}>Cancelar</Button>
                {#if isSuperAdmin || (checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13]) && proyecto.modificable == true)}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit" form="form-estudio-mercado">Guardar</LoadingButton>
                {/if}
            </div>
        </Dialog>

        <Pagination links={proyectoLotesEstudioMercado.links} />
    </div></AuthenticatedLayout
>

<style>
    :global(#estudio-mercado-dialog .mdc-dialog__surface) {
        width: 750px;
        max-width: calc(100vw - 32px) !important;
    }

    :global(#estudio-mercado-dialog .mdc-dialog__content) {
        padding-top: 40px !important;
    }

    :global(#estudio-mercado-dialog .mdc-dialog__title) {
        border-bottom: 1px solid rgba(0, 0, 0, 0.12);
        margin-bottom: 0;
    }
</style>
