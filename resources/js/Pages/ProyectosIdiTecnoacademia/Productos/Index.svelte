<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page, inertia } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import DataTableMenu from '@/Shared/DataTableMenu'
    import { Item, Text } from '@smui/list'
    import Pagination from '@/Shared/Pagination'
    import Button from '@/Shared/Button'
    import { Inertia } from '@inertiajs/inertia'

    export let proyectoIdiTecnoacademia
    export let productos

    $title = 'Productos'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    <a use:inertia href={route('proyectos-idi-tecnoacademia.index')} class="text-indigo-400 hover:text-indigo-600"> Proyectos I+D+i TecnoAcademia </a>
                    <span class="text-indigo-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-idi-tecnoacademia.edit', proyectoIdiTecnoacademia.id)} class="text-indigo-400 hover:text-indigo-600">Información básica</a>
                    <span class="text-indigo-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-idi-tecnoacademia.participantes.index', proyectoIdiTecnoacademia.id)} class="text-indigo-400 hover:text-indigo-600">Participantes</a>
                    <span class="text-indigo-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-idi-tecnoacademia.productos.index', proyectoIdiTecnoacademia.id)} class="text-indigo-400 hover:text-indigo-600 font-extrabold underline">Productos</a>
                </h1>
            </div>
        </div>
    </header>

    <h1 class="mt-24 mb-8 text-center text-3xl">Productos</h1>

    <div class="mb-6 flex justify-between items-center">
        {#if isSuperAdmin || checkRole(authUser, [5, 10, 12, 22])}
            <Button on:click={() => Inertia.visit(route('proyectos-idi-tecnoacademia.productos.create', proyectoIdiTecnoacademia.id))} variant="raised">Crear producto</Button>
        {/if}
    </div>

    <div class="bg-white rounded shadow">
        <table class="w-full whitespace-no-wrap table-fixed data-table">
            <thead>
                <tr class="text-left font-bold">
                    <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Descripción</th>
                    <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Tipo de producto</th>
                    <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl text-center th-actions">Acciones</th>
                </tr>
            </thead>
            <tbody>
                {#each productos.data as producto (producto.id)}
                    <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                        <td class="border-t">
                            <p class="px-6 py-4">
                                {producto.descripcion}
                            </p>
                        </td>
                        <td class="border-t">
                            <p class="px-6 py-4">
                                {producto.tipo_producto_idi.tipo}
                            </p>
                        </td>
                        <td class="border-t td-actions">
                            <DataTableMenu class={productos.data.length < 4 ? 'z-50' : ''}>
                                {#if isSuperAdmin || checkRole(authUser, [5, 10, 12, 22])}
                                    <Item on:SMUI:action={() => Inertia.visit(route('proyectos-idi-tecnoacademia.productos.edit', [proyectoIdiTecnoacademia.id, producto.id]))}>
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
                        <td class="border-t px-6 py-4" colspan="4"> Sin información registrada </td>
                    </tr>
                {/if}
            </tbody>
        </table>
    </div>
    <Pagination links={productos.links} />
</AuthenticatedLayout>
