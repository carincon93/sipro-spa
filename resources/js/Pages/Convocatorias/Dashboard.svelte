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
    {#if isSuperAdmin || checkRole(authUser, [11])}
        <a class="flex bg-orangered-900 bottom-0 fixed hover:bg-orangered-600 mb-4 px-6 py-2 rounded-3xl shadow-2xl text-center text-white z-50" href="#evaluacion">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
            Ir a la sección de evaluación
        </a>
    {/if}

    <div class="py-12">
        {#if isSuperAdmin || checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 17, 18, 19, 14, 15, 16, 20, 21])}
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
            <hr class="mt-20 mb-20" />
        {/if}

        {#if isSuperAdmin || checkRole(authUser, [11])}
            <h1 class="text-4xl text-center" id="evaluacion">
                A continuación, se listan la(s) línea(s) programática(s) de la vigencia {convocatoria.year} para realizar las respectivas evaluaciones.
            </h1>
            <div class="flex justify-around mt-24 gap-4">
                <a use:inertia href={route('convocatorias.idi-evaluaciones.index', convocatoria.id)} class="bg-white overflow-hidden text-center shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col w-80 h-96">
                    <figure>
                        <img src={window.basePath + '/images/evaidi.png'} alt="Línea programática - I+D+i" class="bg-white h-44 w-44 object-contain rounded-full" />
                    </figure>
                    I+D+i
                </a>

                <a use:inertia href={route('convocatorias.ta-evaluaciones.index', convocatoria.id)} class="bg-white overflow-hidden text-center shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col w-80 h-96">
                    <figure>
                        <img src={window.basePath + '/images/evata.png'} alt="Línea programática - Tecnoacademia" class="bg-white h-44 w-44 object-contain rounded-full" />
                    </figure>
                    Tecnoacademia
                </a>

                <a use:inertia href={route('convocatorias.tp-evaluaciones.index', convocatoria.id)} class="bg-white overflow-hidden text-center shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col w-80 h-96">
                    <figure>
                        <img src={window.basePath + '/images/evatp.png'} alt="Línea programática - Tecnoparque" class="bg-white h-44 w-44 object-contain rounded-full" />
                    </figure>
                    Tecnoparque
                </a>

                <a use:inertia href={route('convocatorias.servicios-tecnologicos-evaluaciones.index', convocatoria.id)} class="bg-white overflow-hidden text-center shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col w-80 h-96">
                    <figure>
                        <img src={window.basePath + '/images/evast.png'} alt="Línea programática - Servicios tecnológicos" class="bg-white h-44 w-44 object-contain rounded-full" />
                    </figure>
                    Servicios tecnológicos
                </a>

                <a use:inertia href={route('convocatorias.cultura-innovacion-evaluaciones.index', convocatoria.id)} class="bg-white overflow-hidden text-center shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col w-80 h-96">
                    <figure>
                        <img src={window.basePath + '/images/evacultura.png'} alt="Línea programática - Servicios tecnológicos" class="bg-white h-44 w-44 object-contain rounded-full" />
                    </figure>
                    Apropiación de la cultura de la innovación
                </a>
            </div>
        {/if}
    </div>
</AuthenticatedLayout>
