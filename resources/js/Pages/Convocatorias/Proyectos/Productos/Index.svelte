<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'

    import Button from '@/Shared/Button'
    import Stepper from '@/Shared/Stepper'
    import Gantt from '@/Shared/Gantt'
    import InfoMessage from '@/Shared/InfoMessage'
    import Pagination from '@/Shared/Pagination'
    import DataTable from '@/Shared/DataTable'
    import DataTableMenu from '@/Shared/DataTableMenu'
    import { Item, Text } from '@smui/list'
    import RecomendacionEvaluador from '@/Shared/RecomendacionEvaluador'

    export let convocatoria
    export let proyecto
    export let productos
    export let productosGantt
    export let validacionResultados
    export let to_pdf

    $title = 'Productos'

    /**
     * Validar si el usuario autenticado es SuperAdmin
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let showGantt = false
</script>

<AuthenticatedLayout>
    <header slot="header">
        <Stepper {convocatoria} {proyecto} />
    </header>

    <h1 class="text-3xl m-24 text-center">Productos</h1>

    <p class="text-center mb-10">Los productos se entienden como los bienes o servicios que se generan y entregan en un proceso productivo. Los productos materializan los objetivos específicos de los proyectos. De esta forma, los productos de un proyecto deben agotar los objetivos específicos del mismo y deben cumplir a cabalidad con el objetivo general del proyecto.</p>

    {#if validacionResultados}
        <InfoMessage message={validacionResultados} class="mt-10 mb-10" />
    {/if}

    {#if proyecto.codigo_linea_programatica == 70}
        <InfoMessage message="Debe asociar las fechas a cada uno de los productos haciendo clic en los tres puntos, a continuación, clic en 'Ver detalles'. (<strong>Se deben registrar todas las fechas para visualizar el diagrama de Gantt</strong>)." />
    {/if}

    {#if proyecto.codigo_linea_programatica == 23 || proyecto.codigo_linea_programatica == 65 || proyecto.codigo_linea_programatica == 66 || proyecto.codigo_linea_programatica == 68 || proyecto.codigo_linea_programatica == 82}
        {#if isSuperAdmin || proyecto.mostrar_recomendaciones}
            <RecomendacionEvaluador class="mt-8">
                {#each proyecto.evaluaciones as evaluacion, i}
                    {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                        <div class="bg-zinc-900 p-4 rounded shadow text-white my-2">
                            <p class="text-xs">Evaluador COD-{evaluacion.id}:</p>
                            {#if evaluacion.idi_evaluacion}
                                <p class="whitespace-pre-line text-xs">{evaluacion.idi_evaluacion?.productos_comentario ? evaluacion.idi_evaluacion.productos_comentario : 'Sin recomendación'}</p>
                            {:else if evaluacion.cultura_innovacion_evaluacion}
                                <p class="whitespace-pre-line text-xs">{evaluacion.cultura_innovacion_evaluacion?.productos_comentario ? evaluacion.cultura_innovacion_evaluacion.productos_comentario : 'Sin recomendación'}</p>
                            {:else if evaluacion.servicio_tecnologico_evaluacion}
                                <hr class="mt-10 mb-10 border-black-200" />
                                <h1 class="font-black">Productos</h1>

                                <ul class="list-disc pl-4">
                                    <li class="whitespace-pre-line text-xs mb-10">{evaluacion.servicio_tecnologico_evaluacion?.productos_primer_obj_comentario ? 'Recomendación productos del primer objetivo específico: ' + evaluacion.servicio_tecnologico_evaluacion.productos_primer_obj_comentario : 'Sin recomendación'}</li>
                                    <li class="whitespace-pre-line text-xs mb-10">{evaluacion.servicio_tecnologico_evaluacion?.productos_segundo_obj_comentario ? 'Recomendación productos del segundo objetivo específico: ' + evaluacion.servicio_tecnologico_evaluacion.productos_segundo_obj_comentario : 'Sin recomendación'}</li>
                                    <li class="whitespace-pre-line text-xs mb-10">{evaluacion.servicio_tecnologico_evaluacion?.productos_tercer_obj_comentario ? 'Recomendación productos del tercer objetivo específico: ' + evaluacion.servicio_tecnologico_evaluacion.productos_tercer_obj_comentario : 'Sin recomendación'}</li>
                                    <li class="whitespace-pre-line text-xs mb-10">{evaluacion.servicio_tecnologico_evaluacion?.productos_cuarto_obj_comentario ? 'Recomendación productos del cuarto objetivo específico: ' + evaluacion.servicio_tecnologico_evaluacion.productos_cuarto_obj_comentario : 'Sin recomendación'}</li>
                                </ul>
                            {/if}
                        </div>
                    {/if}
                {/each}
            </RecomendacionEvaluador>
        {/if}
    {/if}

    {#if showGantt || to_pdf}
        <Button on:click={() => (showGantt = false)}>Ocultar diagrama de Gantt</Button>
    {:else}
        <Button on:click={() => (showGantt = true)}>Visualizar diagrama de Gantt</Button>
    {/if}

    {#if showGantt || to_pdf}
        <Gantt
            items={productosGantt}
            request={isSuperAdmin || checkPermission(authUser, [3, 4, 6, 7, 9, 10, 12, 13, 18, 19, 21, 14, 16, 15, 20])
                ? {
                      uri: 'convocatorias.proyectos.productos.edit',
                      params: [convocatoria.id, proyecto.id],
                  }
                : null}
        />
    {:else}
        <DataTable class="mt-20" routeParams={[convocatoria.id, proyecto.id]}>
            <div slot="actions">
                {#if (isSuperAdmin && validacionResultados == null) || (checkPermission(authUser, [1, 5, 8, 11, 17]) && validacionResultados == null && proyecto.modificable == true && proyecto.codigo_linea_programatica != 70)}
                    <Button on:click={() => Inertia.visit(route('convocatorias.proyectos.productos.create', [convocatoria.id, proyecto.id]))} variant="raised">Crear producto</Button>
                {/if}
            </div>

            <thead slot="thead">
                <tr class="text-left font-bold">
                    <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Descripción</th>
                    <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Objetivo específico</th>
                    {#if proyecto.codigo_linea_programatica != 70}
                        <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Resultado</th>
                    {:else if proyecto.codigo_linea_programatica == 70}
                        <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Meta</th>
                    {/if}
                    <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl text-center th-actions">Acciones</th>
                </tr>
            </thead>

            <tbody slot="tbody">
                {#each productos.data as producto (producto.id)}
                    <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                        <td class="border-t">
                            <p class="focus:text-cyan-500 my-2 paragraph-ellipsis px-6">
                                {producto.nombre}
                            </p>
                        </td>

                        <td class="border-t">
                            <p class="focus:text-cyan-500 my-2 paragraph-ellipsis px-6">
                                {producto.resultado.objetivo_especifico.descripcion}
                            </p>
                        </td>

                        <td class="border-t">
                            <p class="focus:text-cyan-500 my-2 paragraph-ellipsis px-6">
                                {#if proyecto.codigo_linea_programatica != 70}
                                    {producto.resultado.descripcion}
                                {:else if proyecto.codigo_linea_programatica == 70}
                                    {producto.producto_ta_tp.valor_proyectado}
                                {/if}
                            </p>
                        </td>

                        <td class="border-t td-actions">
                            <DataTableMenu class={productos.data.length < 4 ? 'z-50' : ''}>
                                {#if isSuperAdmin || checkPermission(authUser, [3, 4, 6, 7, 9, 10, 12, 13, 18, 19, 21, 14, 16, 15, 20])}
                                    <Item on:SMUI:action={() => Inertia.visit(route('convocatorias.proyectos.productos.edit', [convocatoria.id, proyecto.id, producto.id]))}>
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

                {#if productos.data.length === 0}
                    <tr>
                        <td class="border-t px-6 py-4" colspan="4">Sin información registrada</td>
                    </tr>
                {/if}
            </tbody>
        </DataTable>
        <Pagination links={productos.links} />
    {/if}
</AuthenticatedLayout>
