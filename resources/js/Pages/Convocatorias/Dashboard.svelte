<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'

    import Button from '@/Shared/Button'

    export let convocatoria

    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    $title = 'Convocatorias - Dashboard'
</script>

<AuthenticatedLayout>
    <div class="py-12">
        {#if isSuperAdmin}
            <div class="flex justify-center items-center flex-col">
                <p>
                    Si desea revisar, {#if isSuperAdmin} editar {/if} la información de la convocatoria, de clic en el siguiente botón
                </p>
                <div>
                    <Button on:click={() => Inertia.visit(route('convocatorias.edit', [convocatoria.id]))} class="mt-8 mb-20" variant="raised">Ver detalles</Button>
                </div>
            </div>
        {/if}
        <h1 class="text-4xl text-center">
            A continuación, se listan la(s) línea(s) programática(s) de la vigencia {convocatoria.year} en la(s) que puede formular proyectos.
        </h1>
        <div class="flex justify-around mt-24 gap-4">
            {#if isSuperAdmin || checkPermission(authUser, [1, 3, 4, 14])}
                <a use:inertia href={route('convocatorias.idi.index', convocatoria.id)} class="bg-white overflow-hidden text-center shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col w-80 h-96">
                    <figure>
                        <img src={window.basePath + '/images/idi.png'} alt="Línea programática - I+D+i" class="bg-white h-44 w-44 object-contain rounded-full" />
                    </figure>
                    I+D+i
                </a>
            {/if}
            {#if isSuperAdmin || checkPermission(authUser, [8, 9, 10, 15])}
                <a use:inertia href={route('convocatorias.ta.index', convocatoria.id)} class="bg-white overflow-hidden text-center shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col w-80 h-96">
                    <figure>
                        <img src={window.basePath + '/images/ta.png'} alt="Línea programática - Tecnoacademia" class="bg-white h-44 w-44 object-contain rounded-full" />
                    </figure>
                    Tecnoacademia
                </a>
            {/if}

            {#if isSuperAdmin || checkPermission(authUser, [17, 18, 19, 20])}
                <a use:inertia href={route('convocatorias.tp.index', convocatoria.id)} class="bg-white overflow-hidden text-center shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col w-80 h-96">
                    <figure>
                        <img src={window.basePath + '/images/tp.png'} alt="Línea programática - Tecnoparque" class="bg-white h-44 w-44 object-contain rounded-full" />
                    </figure>
                    Tecnoparque
                </a>
            {/if}

            {#if isSuperAdmin || checkPermission(authUser, [5, 6, 7, 16])}
                <a use:inertia href={route('convocatorias.servicios-tecnologicos.index', convocatoria.id)} class="bg-white overflow-hidden text-center shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col w-80 h-96">
                    <figure>
                        <img src={window.basePath + '/images/st.png'} alt="Línea programática - Servicios tecnológicos" class="bg-white h-44 w-44 object-contain rounded-full" />
                    </figure>
                    Servicios tecnológicos
                </a>
            {/if}

            {#if isSuperAdmin || checkPermission(authUser, [11, 12, 13, 21])}
                <a use:inertia href={route('convocatorias.cultura-innovacion.index', convocatoria.id)} class="bg-white overflow-hidden text-center shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col w-80 h-96">
                    <figure>
                        <img src={window.basePath + '/images/cultura-innovacion.png'} alt="Línea programática - Servicios tecnológicos" class="bg-white h-44 w-44 object-contain rounded-full" />
                    </figure>
                    Apropiación de la cultura de la innovación
                </a>
            {/if}
        </div>
    </div>
</AuthenticatedLayout>
