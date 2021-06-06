<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import Pagination from '@/Shared/Pagination'
    import Button from '@/Shared/Button'
    import { Inertia } from '@inertiajs/inertia'

    import Stepper from '@/Shared/Stepper'
    import Gantt from '@/Shared/Gantt'

    export let convocatoria
    export let proyecto
    export let productos

    $title = 'Productos'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let filters = {}
</script>

<AuthenticatedLayout>
    <Stepper {convocatoria} {proyecto} />

    <h1 class="text-3xl m-24 text-center">Productos</h1>
    <div class="mb-6 flex justify-end items-center">
        <!-- <SearchFilter class="w-full max-w-md mr-4" bind:filters /> -->
        <div>
            {#if isSuperAdmin || checkPermission(authUser, [1, 5, 8])}
                <Button on:click={() => Inertia.visit(route('convocatorias.proyectos.productos.create', [convocatoria.id, proyecto.id]))}>
                    <div>
                        <span>Crear</span>
                        <span class="hidden md:inline">producto</span>
                    </div>
                </Button>
            {/if}
        </div>
    </div>

    <Gantt
        items={productos.data}
        request={isSuperAdmin || checkPermission(authUser, [3, 4, 6, 7, 9, 10])
            ? {
                  uri: 'convocatorias.proyectos.productos.edit',
                  params: [convocatoria.id, proyecto.id],
              }
            : null}
    />

    <Pagination links={productos.links} />
</AuthenticatedLayout>
