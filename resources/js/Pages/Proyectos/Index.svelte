<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page, useForm } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'

    import Pagination from '@/Shared/Pagination'
    import DataTable from '@/Shared/DataTable'
    import DataTableMenu from '@/Shared/DataTableMenu'
    import LoadingButton from '@/Shared/LoadingButton'
    import Tags from '@/Shared/Tags'
    import Label from '@/Shared/Label'
    import Switch from '@/Shared/Switch'
    import { Item, Text } from '@smui/list'

    export let proyectos
    export let proyectosId

    $title = 'Proyectos'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let filters = {
        year: $page.props.filters.year,
    }

    let sending = false
    let form = useForm({
        proyectos_id: null,
        estado_subsanable: false,
    })
    function submit() {
        if (isSuperAdmin || checkRole(authUser, [20, 18, 19, 5, 17])) {
            $form.post(route('proyectos.update.actualizar-estados-proyectos'), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }
</script>

<AuthenticatedLayout>
    <DataTable class="mt-20" bind:filters showFilters={false}>
        <div slot="title">Proyectos</div>

        <div slot="caption">
            <form on:submit|preventDefault={submit}>
                <Tags enforceWhitelist={false} id="proyectos_id" class="mt-4" whitelist={proyectosId} bind:tags={$form.proyectos_id} placeholder="Código(s) SGPS" required />

                <div class="mt-4">
                    {#if $form.estado_subsanable}
                        <Label labelFor="estado_subsanable" value="La opción de proyectos subsanables está seleccionada Nota: Los proyectos serán modificables, además se finalizarán todas la evaluaciones y se mostrarán las recomendaciones de los evaluadores." class="inline-block mb-4" />
                    {:else}
                        <Label labelFor="estado_subsanable" value="La opción de proyectos finalizados está seleccionada. Nota: Los proyectos finalizados no podrán ser modifcados." class="inline-block mb-4" />
                    {/if}
                    <br />
                    <Switch bind:checked={$form.estado_subsanable} onMessage="Subsanable(s)" offMessage="Finalizado(s)" />
                </div>

                <div class="py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                    {#if isSuperAdmin || checkRole(authUser, [20, 18, 19, 5, 17])}
                        <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Actualizar estados de los proyectos</LoadingButton>
                    {/if}
                </div>
            </form>
        </div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Código </th>
                {#if isSuperAdmin || checkRole(authUser, [20, 18, 19, 5, 17])}
                    <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full"> Versiones (.pdf) </th>
                {/if}
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl text-center th-actions"> Acciones </th>
            </tr>
        </thead>

        <tbody slot="tbody">
            {#each proyectos.data as { id, estado, titulo, codigo, fecha_ejecucion, pdf_versiones, convocatoria }}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p class="px-6 py-4 focus:text-indigo-500">
                            {codigo}
                            {#if JSON.parse(estado)?.requiereSubsanar}
                                <br />
                                <span class="bg-red-100 inline-block mt-2 p-2 rounded text-red-400"> Requiere modificaciones </span>
                            {/if}
                        </p>
                    </td>
                    {#if isSuperAdmin || checkRole(authUser, [20, 18, 19, 5, 17])}
                        <td class="border-t">
                            {#if pdf_versiones}
                                <ul>
                                    {#each pdf_versiones as version}
                                        <li>
                                            {#if version.estado == 1}
                                                <a class="text-indigo-500 underline" href={route('convocatorias.proyectos.version', [convocatoria.id, id, version.version])}> {version.version}.pdf - Descargar</a>
                                                <small class="block">{version.created_at}</small>
                                            {/if}
                                        </li>
                                    {/each}
                                    {#if pdf_versiones.length == 0}
                                        <p>No se ha generado algún pdf</p>
                                    {/if}
                                </ul>
                            {:else}
                                <p>No se ha generado algún pdf</p>
                            {/if}
                        </td>
                    {/if}

                    <td class="border-t td-actions">
                        <DataTableMenu class={proyectos.data.length < 4 ? 'z-50' : ''}>
                            {#if isSuperAdmin || checkRole(authUser, [20, 18, 19, 5, 17])}
                                <Item on:SMUI:action={() => Inertia.visit(route('proyectos.edit', [id]))}>
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

            {#if proyectos.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="5"> Sin información registrada </td>
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={proyectos.links} />
</AuthenticatedLayout>
