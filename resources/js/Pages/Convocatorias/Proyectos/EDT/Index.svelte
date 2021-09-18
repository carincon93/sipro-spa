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
    import Stepper from '@/Shared/Stepper'
    import InfoMessage from '@/Shared/InfoMessage'

    export let convocatoria
    export let proyecto
    export let eventos

    $title = 'EDT'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])
</script>

<AuthenticatedLayout>
    <Stepper {convocatoria} {proyecto} />

    <DataTable class="mt-20" routeParams={[convocatoria.id, proyecto.id]}>
        <div slot="title">EDT</div>

        <div slot="caption">
            {#if proyecto.servicios_organizacion == false}
                <InfoMessage message="Para poder agregar un EDT debe generar primero el uso presupuestal <strong>Servicios personales indirectos (persona jurídica)</strong> >  <strong>Servicios de organización y asistencia de convenciones y ferias</strong>." />
            {:else}
                <p class="mb-20 text-center">A continuación, proyecte los EDTs que se realizarán durante la vigencia del proyecto:</p>
            {/if}

            {#if isSuperAdmin || convocatoria.mostrar_recomendaciones}
                {#each proyecto.evaluaciones as evaluacion, i}
                    {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                        <div class="bg-gray-200 p-4 rounded border-orangered border mb-5">
                            <div class="flex text-orangered-900 font-black">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                                Recomendación del evaluador COD-{evaluacion.id}:
                            </div>

                            {#if evaluacion.ta_evaluacion}
                                <p class="whitespace-pre-line">{evaluacion.ta_evaluacion?.edt_comentario ? evaluacion.ta_evaluacion.edt_comentario : 'Sin recomendación'}</p>
                            {/if}
                        </div>
                    {/if}
                {/each}
            {/if}
        </div>

        <div slot="actions">
            {#if (isSuperAdmin && proyecto.servicios_organizacion) || (checkPermission(authUser, [8]) && proyecto.modificable == true && proyecto.servicios_organizacion)}
                <Button on:click={() => Inertia.visit(route('convocatorias.proyectos.edt.create', [convocatoria.id, proyecto.id]))} variant="raised">Crear EDT</Button>
            {/if}
        </div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Descripción del evento</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Número de asistentes</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Presupuesto</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl text-center th-actions">Acciones</th>
            </tr>
        </thead>

        <tbody slot="tbody">
            {#each eventos.data as evento (evento.id)}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p class="focus:text-indigo-500 my-2 paragraph-ellipsis px-6">
                            {evento.descripcion_evento}
                        </p>
                    </td>

                    <td class="border-t">
                        <p class="focus:text-indigo-500 my-2 paragraph-ellipsis px-6">
                            {evento.numero_asistentes}
                        </p>
                    </td>

                    <td class="border-t">
                        <p class="focus:text-indigo-500 my-2 paragraph-ellipsis px-6">
                            ${new Intl.NumberFormat('de-DE').format(!isNaN(evento.proyecto_presupuesto.valor_total) ? evento.proyecto_presupuesto.valor_total : 0)}
                        </p>
                    </td>

                    <td class="border-t td-actions">
                        <DataTableMenu class={eventos.data.length < 4 ? 'z-50' : ''}>
                            {#if isSuperAdmin || checkPermission(authUser, [9, 10, 15])}
                                <Item on:SMUI:action={() => Inertia.visit(route('convocatorias.proyectos.edt.edit', [convocatoria.id, proyecto.id, evento.id]))}>
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

            {#if eventos.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="4">Sin información registrada</td>
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={eventos.links} />
</AuthenticatedLayout>
