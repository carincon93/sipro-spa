<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { Inertia } from '@inertiajs/inertia'
    import { inertia, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Button from '@/Shared/Button'

    export let convocatorias
    export let convocatoriaActiva

    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    $title = 'Convocatorias'
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                {#if convocatoriaActiva}
                    <h1 class="font-bold text-5xl">
                        Convocatoria activa: {convocatoriaActiva?.year}
                    </h1>

                    {#if isSuperAdmin || checkRole(authUser, [11]) || checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 17, 18, 19, 14, 15, 16, 20, 21])}
                        <Button on:click={() => Inertia.visit(route('convocatorias.dashboard', convocatoriaActiva?.id))} variant="raised" class="mt-4 inline-block">
                            Revisar convocatoria
                            {convocatoriaActiva?.year}
                        </Button>
                    {/if}
                {:else}
                    <h1 class="font-bold text-5xl">Aún no hay una convocatoria activa.</h1>
                    <p>Debe crear una nueva convocatoria y activarla o activar una convocatoria previamente creada.</p>
                {/if}
            </div>
            <div>
                <figure>
                    <img src={window.basePath + '/images/dashboard.png'} alt="" />
                </figure>
            </div>
        </div>
    </header>
    <div class={isSuperAdmin ? 'py-12' : ''}>
        <h1 class="text-3xl m-10 text-center">Lista de convocatorias</h1>
        {#if isSuperAdmin}
            <div class="flex justify-center items-center flex-col">
                <p>A continuación, se listan todas las convocatorias, si desea crear una nueva de clic en el siguiente botón.</p>
                <div>
                    <Button on:click={() => Inertia.visit(route('convocatorias.create'))} class="mt-8 mb-20" variant="raised">Crear convocatoria</Button>
                </div>
            </div>
        {/if}

        <div class="grid grid-cols-3 gap-4">
            {#if isSuperAdmin || checkRole(authUser, [11]) || checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 17, 18, 19, 14, 15, 16, 20, 21])}
                {#each convocatorias.data as convocatoria (convocatoria.id)}
                    <div>
                        <a use:inertia href={route('convocatorias.dashboard', convocatoria.id)} class="bg-white overflow-hidden shadow-sm sm:rounded-tr-lg sm:rounded-tl-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-center items-center flex-col">
                            {convocatoria.tipo_convocatoria == 1 ? 'Proyectos de convocatoria' : 'Proyectos de ejercicio (DEMO)'}
                            <h1 class="text-4xl text-center mt-6">
                                {convocatoria.year}
                            </h1>
                            <p>
                                {convocatoria.descripcion}
                            </p>
                            {#if convocatoria.esta_activa}
                                <small class="mt-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5" style="transform: translateX(-24px); position: absolute;">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Convocatoria activa
                                </small>
                            {/if}
                        </a>
                        {#if isSuperAdmin}
                            <div class="bg-white p-2 flex justify-center rounded-bl-lg rounded-br-lg">
                                <Button on:click={() => Inertia.visit(route('convocatorias.edit', convocatoria.id))} variant="raised">Editar</Button>
                            </div>
                        {/if}
                    </div>
                {/each}
            {/if}
        </div>
    </div>
</AuthenticatedLayout>
