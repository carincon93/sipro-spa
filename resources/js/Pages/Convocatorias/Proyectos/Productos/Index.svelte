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

    export let convocatoria
    export let proyecto
    export let productos
    export let productosGantt
    export let validacionResultados

    $title = 'Productos'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let showGantt = false
</script>

<AuthenticatedLayout>
    <Stepper {convocatoria} {proyecto} />

    <h1 class="text-3xl m-24 text-center">Productos</h1>

    <p class="text-center mb-10">Los productos se entienden como los bienes o servicios que se generan y entregan en un proceso productivo. Los productos materializan los objetivos específicos de los proyectos. De esta forma, los productos de un proyecto deben agotar los objetivos específicos del mismo y deben cumplir a cabalidad con el objetivo general del proyecto.</p>

    {#if validacionResultados}
        <InfoMessage message={validacionResultados} class="mt-10 mb-10" />
    {/if}

    {#if proyecto.codigo_linea_programatica == 70}
        <InfoMessage message="Debe asociar las fechas a cada uno de los productos haciendo clic en los tres puntos, a continuación, clic en 'Ver detalles'. (<strong>Se deben registrar todas las fechas para visualizar el diagrama de Gantt</strong>)." />
    {/if}
    {#if showGantt}
        <Button on:click={() => (showGantt = false)}>Ocultar diagrama de Gantt</Button>
    {:else}
        <Button on:click={() => (showGantt = true)}>Visualizar diagrama de Gantt</Button>
    {/if}

    {#if showGantt}
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
                {#if (isSuperAdmin && validacionResultados == null) || (checkPermission(authUser, [1, 5, 8, 11, 17]) && validacionResultados == null && proyecto.modificable == true)}
                    <Button on:click={() => Inertia.visit(route('convocatorias.proyectos.productos.create', [convocatoria.id, proyecto.id]))} variant="raised">Crear producto</Button>
                {/if}
            </div>

            <thead slot="thead">
                <tr class="text-left font-bold">
                    <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Descripción</th>
                    <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Fechas</th>
                    <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Objetivo específico</th>
                    <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl text-center th-actions">Acciones</th>
                </tr>
            </thead>

            <tbody slot="tbody">
                {#each productos.data as producto (producto.id)}
                    <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                        <td class="border-t">
                            <p class="focus:text-indigo-500 my-2 paragraph-ellipsis px-6">
                                {producto.nombre}
                            </p>
                        </td>

                        <td class="border-t">
                            <p class="focus:text-indigo-500 my-2 paragraph-ellipsis px-6">
                                {#if producto.fecha_inicio}
                                    Del {producto.fecha_inicio} al {producto.fecha_finalizacion}
                                {:else}
                                    <span class="bg-red-100 text-red-400 hover:bg-red-200 px-2 py-1 rounded-3xl text-center inline-block mt-2 mb-2">Sin fechas definidas</span>
                                {/if}
                            </p>
                        </td>
                        <td class="border-t">
                            <p class="focus:text-indigo-500 my-2 paragraph-ellipsis px-6">
                                {producto.resultado.objetivo_especifico.descripcion}
                            </p>
                        </td>

                        <td class="border-t td-actions">
                            <DataTableMenu class={productos.data.length < 4 ? 'z-50' : ''}>
                                {#if isSuperAdmin || checkPermission(authUser, [3, 4, 6, 7, 9, 10, 12, 13, 18, 19, 21, 14, 16, 15, 20])}
                                    <Item on:SMUI:action={() => Inertia.visit(route('convocatorias.proyectos.edt.edit', [convocatoria.id, proyecto.id, producto.id]))}>
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
