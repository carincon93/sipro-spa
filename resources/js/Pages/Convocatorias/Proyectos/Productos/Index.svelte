<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import Pagination from '@/Components/Pagination'
    import Button from '@/Components/Button'
    import { Inertia } from '@inertiajs/inertia'

    import Stepper from '@/Components/Stepper'
    import Gantt from '@/Components/Gantt'

    export let convocatoria
    export let proyecto
    export let productos

    $title = 'Productos'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0

    let filters = {}
</script>

<AuthenticatedLayout>
    <Stepper {convocatoria} {proyecto} />

    <h1 class="text-3xl m-24 text-center">Productos</h1>
    <div class="mb-6 flex justify-end items-center">
        <!-- <SearchFilter class="w-full max-w-md mr-4" bind:filters /> -->
        <div>
            {#if isSuperAdmin}
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
        request={isSuperAdmin
            ? {
                  uri: 'convocatorias.proyectos.productos.edit',
                  params: [convocatoria.id, proyecto.id],
              }
            : null}
    />

    <Pagination links={productos.links} />
</AuthenticatedLayout>
