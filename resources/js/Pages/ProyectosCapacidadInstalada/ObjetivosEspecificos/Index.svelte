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

    export let proyectoCapacidadInstalada
    export let objetivosEspecificos
    export let autorPrincipal

    $title = 'Objetivos específicos'

    /**
     * Validar si el usuario autenticado es SuperAdmin
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    <a use:inertia href={route('proyectos-capacidad-instalada.index')} class="text-indigo-400 hover:text-indigo-600"> Proyectos de capacidad instalada </a>
                    <span class="text-indigo-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-capacidad-instalada.edit', proyectoCapacidadInstalada.id)} class="text-indigo-400 hover:text-indigo-600">Información básica</a>
                    <span class="text-indigo-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-capacidad-instalada.integrantes.index', proyectoCapacidadInstalada.id)} class="text-indigo-400 hover:text-indigo-600">Integrantes</a>
                    <span class="text-indigo-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-capacidad-instalada.objetivos-especificos.index', proyectoCapacidadInstalada.id)} class="text-indigo-400 hover:text-indigo-600 font-extrabold underline">Objetivos específicos y resultados</a>
                    <span class="text-indigo-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-capacidad-instalada.productos.index', proyectoCapacidadInstalada.id)} class="text-indigo-400 hover:text-indigo-600">Productos</a>
                    <span class="text-indigo-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-capacidad-instalada.finalizar', proyectoCapacidadInstalada.id)} class="text-indigo-400 hover:text-indigo-600">Estado</a>
                </h1>
            </div>
        </div>
    </header>

    <a use:inertia href={route('proyectos-capacidad-instalada.productos.index', proyectoCapacidadInstalada.id)} class="flex bottom-0 fixed hover:bg-cyan-600 mb-4 px-6 py-2 bg-cyan-700 rounded-lg shadow-2xl text-center text-white z-50">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
        </svg>
        Ir a los productos
    </a>

    <h1 class="mt-24 mb-8 text-center text-3xl">Objetivos específicos y resultados</h1>

    {#if isSuperAdmin || (proyectoCapacidadInstalada.estado_proyecto != 'Finalizado' && checkRole(authUser, [4]) && proyectoCapacidadInstalada.semillero_investigacion.linea_investigacion.grupo_investigacion.centro_formacion.id && authUser.centro_formacion_id) || (proyectoCapacidadInstalada.estado_proyecto != 'Finalizado' && checkRole(authUser, [6]) && authUser.id == autorPrincipal.id)}
        <div class="mb-6 flex justify-between items-center">
            <Button on:click={() => Inertia.visit(route('proyectos-capacidad-instalada.objetivos-especificos.create', [proyectoCapacidadInstalada.id]))} variant="raised">Crear objetivo específico</Button>
        </div>
    {/if}
    <div class="bg-white rounded shadow">
        <table class="w-full whitespace-no-wrap table-fixed data-table">
            <thead>
                <tr class="text-left font-bold">
                    <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">#</th>
                    <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Objetivo específico</th>
                    <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl w-full">Resultado</th>
                    <th class="px-6 pt-6 pb-4 sticky top-0 z-10 bg-white shadow-xl text-center th-actions">Acciones</th>
                </tr>
            </thead>
            <tbody>
                {#each objetivosEspecificos.data as objetivoEspecifico (objetivoEspecifico.id)}
                    <tr class="hover:bg-gray-100 focus-within:bg-gray-100">
                        <td class="border-t">
                            <p class="px-6 py-4 focus:text-cyan-500">
                                {objetivoEspecifico.numero}
                            </p>
                        </td>
                        <td class="border-t">
                            <p class="px-6 py-4">
                                {objetivoEspecifico.descripcion}
                            </p>
                        </td>
                        <td class="border-t">
                            <p class="px-6 py-4">
                                {objetivoEspecifico.resultado.descripcion}
                            </p>
                        </td>
                        <td class="border-t td-actions">
                            <DataTableMenu class={objetivosEspecificos.data.length < 4 ? 'z-50' : ''}>
                                <Item on:SMUI:action={() => Inertia.visit(route('proyectos-capacidad-instalada.objetivos-especificos.edit', [proyectoCapacidadInstalada.id, objetivoEspecifico.id]))}>
                                    <Text>Ver detalles</Text>
                                </Item>
                            </DataTableMenu>
                        </td>
                    </tr>
                {/each}

                {#if objetivosEspecificos.data.length === 0}
                    <tr>
                        <td class="border-t px-6 py-4" colspan="4"> Sin información registrada </td>
                    </tr>
                {/if}
            </tbody>
        </table>
    </div>
    <Pagination links={objetivosEspecificos.links} />
</AuthenticatedLayout>
