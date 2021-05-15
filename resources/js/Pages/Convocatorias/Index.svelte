<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import Button from '@/Components/Button'
    import { Inertia } from '@inertiajs/inertia'

    export let convocatorias
    export let convocatoriaActiva

    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0

    // prettier-ignore
    let canIndexConvocatorias = authUser.can.find((element) => element == 'convocatorias.index') == 'convocatorias.index'
    // prettier-ignore
    let canShowConvocatorias = authUser.can.find((element) => element == 'convocatorias.show') == 'convocatorias.show'
    // prettier-ignore
    let canCreateConvocatorias = authUser.can.find((element) => element == 'convocatorias.create') == 'convocatorias.create'
    // prettier-ignore
    let canEditConvocatorias = authUser.can.find((element) => element == 'convocatorias.edit') == 'convocatorias.edit'
    // prettier-ignore
    let canDestroyConvocatorias = authUser.can.find((element) => element == 'convocatorias.destroy') == 'convocatorias.destroy'

    $title = 'Convocatorias'
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div
            class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6"
        >
            <div>
                {#if convocatoriaActiva}
                    <h1 class="font-bold text-5xl">
                        Convocatoria {convocatoriaActiva.year}
                    </h1>
                    <p class="text-2xl mt-4">
                        La convocatoria empezó el {convocatoriaActiva.fecha_inicio_formateado}
                        y finaliza el {convocatoriaActiva.fecha_finalizacion_formateado}.
                    </p>
                    {#if canIndexConvocatorias || isSuperAdmin}
                        <Button
                            on:click={() =>
                                Inertia.visit(
                                    route(
                                        'convocatorias.dashboard',
                                        convocatoriaActiva.id,
                                    ),
                                )}
                            variant="raised"
                            class="mt-4 inline-block"
                        >
                            Convocatoria
                            {convocatoriaActiva.year}
                        </Button>
                    {/if}
                {:else}
                    <h1 class="font-bold text-5xl">
                        Aún no hay una convocatoria activa.
                    </h1>
                    <a
                        use:inertia
                        href={route('dashboard')}
                        class="bg-indigo-600 hover:bg-indigo-500 inline-block mt-4 overflow-hidden px-6 py-2 shadow-sm sm:rounded-lg text-white transition-colors"
                    >
                        Panel de control
                    </a>
                    {#if canCreateConvocatorias || isSuperAdmin}
                        <a
                            use:inertia
                            href={route('convocatorias.create')}
                            class="bg-indigo-600 hover:bg-indigo-500 inline-block mt-4 overflow-hidden px-6 py-2 shadow-sm sm:rounded-lg text-white transition-colors"
                        >
                            <span>Crear</span>
                            <span class="hidden md:inline">Convocatoria</span>
                        </a>
                    {/if}
                {/if}
            </div>
            <div>
                <figure>
                    <img src="/storage/images/dashboard.png" alt="" />
                </figure>
            </div>
        </div>
    </header>
    <div class="py-12">
        {#if canCreateConvocatorias || isSuperAdmin}
            <div class="flex justify-center items-center flex-col">
                <p>
                    A continuación, se listan todas las convocatorias, si desea
                    crear una nueva de clic en el siguiente botón.
                </p>
                <div>
                    <Button
                        on:click={() =>
                            Inertia.visit(route('convocatorias.create'))}
                        class="mt-8 mb-20"
                        variant="raised"
                    >
                        Crear convocatoria
                    </Button>
                </div>
            </div>
        {/if}
        <div class="grid grid-cols-3 gap-10">
            {#if canIndexConvocatorias || isSuperAdmin}
                {#each convocatorias.data as convocatoria (convocatoria.id)}
                    <a
                        use:inertia
                        href={route('convocatorias.dashboard', convocatoria.id)}
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col"
                    >
                        <span>ICON</span>
                        Convocatoria
                        {convocatoria.year}
                        {#if convocatoria.active}
                            <small>Convocatoria activa</small>
                        {/if}
                    </a>
                {/each}
            {/if}
        </div>
    </div>
</AuthenticatedLayout>
