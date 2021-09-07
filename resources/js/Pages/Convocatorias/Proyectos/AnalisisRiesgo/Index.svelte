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

    export let convocatoria
    export let proyecto
    export let analisisRiesgos

    $title = 'Análisis de riesgos'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])
</script>

<AuthenticatedLayout>
    <Stepper {convocatoria} {proyecto} />

    <DataTable class="mt-20" routeParams={[convocatoria.id, proyecto.id]}>
        <div slot="title">Análisis de riesgos</div>

        <div slot="caption">
            <p class="text-center mt-10 mb-24">
                Los riesgos son eventos inciertos que pueden llegar a suceder en el futuro, dentro del horizonte de la ejecución del proyecto y representaran efectos de diferente magnitud en uno o más de sus objetivos.
                <br />
                Se debe establecer un riesgo por cada nivel (A nivel de objetivo general - A nivel de actividades - A nivel de productos). Estos riesgos podrán ser clasificados conforme los siguientes tipos: mercados, operacionales, legales, administrativos.
            </p>

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
                            {#if evaluacion.idi_evaluacion}
                                <p class="whitespace-pre-line">{evaluacion.idi_evaluacion?.analisis_riesgos_comentario ? evaluacion.idi_evaluacion.analisis_riesgos_comentario : 'Sin recomendación'}</p>
                            {:else if evaluacion.ta_evaluacion}
                                <p class="whitespace-pre-line">{evaluacion.ta_evaluacion?.analisis_riesgos_comentario ? evaluacion.ta_evaluacion.analisis_riesgos_comentario : 'Sin recomendación'}</p>
                            {:else if evaluacion.servicio_tecnologico_evaluacion}
                                <hr class="mt-10 mb-10 border-black-200" />
                                <h1 class="font-black">Análisis de riesgos</h1>

                                <ul class="list-disc pl-4">
                                    <li class="whitespace-pre-line mb-10">{evaluacion.servicio_tecnologico_evaluacion?.riesgos_objetivo_general_comentario ? 'Recomendación riesgos a nivel de objetivo general: ' + evaluacion.servicio_tecnologico_evaluacion.riesgos_objetivo_general_comentario : 'Sin recomendación'}</li>
                                    <li class="whitespace-pre-line mb-10">{evaluacion.servicio_tecnologico_evaluacion?.riesgos_productos_comentario ? 'Recomendación riesgos a nivel de productos: ' + evaluacion.servicio_tecnologico_evaluacion.riesgos_productos_comentario : 'Sin recomendación'}</li>
                                    <li class="whitespace-pre-line mb-10">{evaluacion.servicio_tecnologico_evaluacion?.riesgos_actividades_comentario ? 'Recomendación riesgos a nivel de actividades: ' + evaluacion.servicio_tecnologico_evaluacion.riesgos_actividades_comentario : 'Sin recomendación'}</li>
                                </ul>
                            {/if}
                        </div>
                    {/if}
                {/each}
            {/if}
        </div>

        <div slot="actions">
            {#if isSuperAdmin || (checkPermission(authUser, [1, 5, 8, 11, 17]) && proyecto.modificable == true)}
                <Button on:click={() => Inertia.visit(route('convocatorias.proyectos.analisis-riesgos.create', [convocatoria.id, proyecto.id]))} variant="raised">Crear análisis de riesgos</Button>
            {/if}
        </div>

        <thead slot="thead">
            <tr class="text-left font-bold">
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Descripción</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Nivel</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Tipo de riesgo</th>
                <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl text-center th-actions">Acciones</th>
            </tr>
        </thead>

        <tbody slot="tbody">
            {#each analisisRiesgos.data as analisisRiesgo (analisisRiesgo.id)}
                <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                    <td class="border-t">
                        <p class="focus:text-indigo-500 my-2 paragraph-ellipsis px-6">
                            {analisisRiesgo.descripcion}
                        </p>
                    </td>

                    <td class="border-t">
                        <p class="focus:text-indigo-500 my-2 paragraph-ellipsis px-6">
                            {analisisRiesgo.nivel}
                        </p>
                    </td>

                    <td class="border-t">
                        <p class="focus:text-indigo-500 my-2 paragraph-ellipsis px-6">
                            {analisisRiesgo.tipo}
                        </p>
                    </td>

                    <td class="border-t td-actions">
                        <DataTableMenu class={analisisRiesgos.data.length < 4 ? 'z-50' : ''}>
                            {#if isSuperAdmin || checkPermission(authUser, [3, 4, 6, 7, 9, 10, 12, 13, 18, 19, 21, 14, 16, 15, 20])}
                                <Item on:SMUI:action={() => Inertia.visit(route('convocatorias.proyectos.analisis-riesgos.edit', [convocatoria.id, proyecto.id, analisisRiesgo.id]))}>
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

            {#if analisisRiesgos.data.length === 0}
                <tr>
                    <td class="border-t px-6 py-4" colspan="4">Sin información registrada</td>
                </tr>
            {/if}
        </tbody>
    </DataTable>
    <Pagination links={analisisRiesgos.links} />
</AuthenticatedLayout>
