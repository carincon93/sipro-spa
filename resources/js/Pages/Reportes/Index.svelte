<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'

    import Button from '@/Shared/Button'
    import Pagination from '@/Shared/Pagination'
    import DataTable from '@/Shared/DataTable'
    import DataTableMenu from '@/Shared/DataTableMenu'
    import { Item, Text } from '@smui/list'
    import Label from '@/Shared/Label'
    import Select from '@/Shared/Select'

    $title = 'Reportes de sistema'

    export let convocatorias
    export let errors

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let form = useForm({
        _token: $page.props.csrf_token,
        convocatoria: null,
    })

    function downloadReport(report) {
        if (report != null && $form.convocatoria != null) {
            window.open(route('reportes.' + report, { convocatoria: $form.convocatoria.value }))
        }
    }
</script>

<AuthenticatedLayout>
    <DataTable class="mt-20">
        <div slot="title">Reportes de sistema</div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Nombre</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Convocatoria</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl text-center th-actions">Acciones</th>
            </tr>
        </thead>
        <tbody slot="tbody">
            <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                <td class="border-t">
                    <p class="px-6 py-4 focus:text-indigo-500">Resumen proyectos</p>
                </td>
                <td class="border-t">
                    <Select id="convocatorias" items={convocatorias} bind:selectedValue={$form.convocatoria} error={errors.convocatoria} autocomplete="off" placeholder="Seleccione una convocatoria" required />
                </td>
                <td class="border-t td-actions">
                    {#if isSuperAdmin}
                        <Button variant="raised" on:click={() => downloadReport('resumeProjects')}>Descargar</Button>
                    {/if}
                </td>
            </tr>

            <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                <td class="border-t">
                    <p class="px-6 py-4 focus:text-indigo-500">Evaluaciones</p>
                </td>
                <td class="border-t">
                    <Select id="convocatorias" items={convocatorias} bind:selectedValue={$form.convocatoria} error={errors.convocatoria} autocomplete="off" placeholder="Seleccione una convocatoria" required />
                </td>
                <td class="border-t td-actions">
                    {#if isSuperAdmin && $form.convocatoria}
                        <a class="bg-indigo-600 p-2 rounded shadow text-white uppercase" style="letter-spacing: 0.0892857143em; font-size: 14.7px;" target="_blank" href={route('reportes.evaluaciones', [$form.convocatoria?.value])}>Descargar</a>
                    {/if}
                </td>
            </tr>
        </tbody>
    </DataTable>
</AuthenticatedLayout>
