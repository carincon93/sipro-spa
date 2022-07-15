<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    export let convocatoria

    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    $title = 'Convocatorias - Dashboard'
</script>

<AuthenticatedLayout>
    <div class="py-12">
        {#if isSuperAdmin || checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 17, 18, 19, 14, 15, 16, 20, 21])}
            <h1 class="text-4xl text-center">
                A continuación, se listan las líneas programáticas de la vigencia {convocatoria.year} en las que puede formular proyectos.
            </h1>
            <div class="flex justify-around mt-24 gap-4">
                <div>
                    {#if isSuperAdmin || checkPermission(authUser, [1, 3, 4, 14])}
                        <a use:inertia href={route('convocatorias.idi.index', convocatoria.id)} class="bg-white overflow-hidden z-[2] relative text-center shadow-sm sm:rounded-lg px-6 py-2 hover:bg-violet-500 hover:text-white flex justify-around items-center flex-col w-80 h-96">
                            <figure>
                                <img src={window.basePath + '/images/idi.png'} alt="Línea programática - I+D+i" class="bg-white h-44 w-44 object-contain rounded-full" />
                            </figure>
                            Formular proyectos de I+D+i
                        </a>
                    {/if}
                    {#if isSuperAdmin || checkRole(authUser, [11, 18])}
                        <a use:inertia href={route('convocatorias.idi-evaluaciones.index', convocatoria.id)} class="bg-violet-500 px-6 py-4 shadow-sm sm:rounded-lg transition duration-300 text-center text-white hover:bg-white hover:text-black z-[1] relative top-[-0.6rem] block hover:h-[104px] hover:pt-[13%]"> Evaluar proyectos de I+D+i </a>
                    {/if}
                </div>
                <div>
                    {#if isSuperAdmin || (checkPermission(authUser, [8, 9, 10, 15]) && convocatoria.tipo_convocatoria == 1) || (checkPermission(authUser, [8, 9, 10, 15]) && convocatoria.tipo_convocatoria == 3)}
                        <a use:inertia href={route('convocatorias.ta.index', convocatoria.id)} class="bg-white overflow-hidden z-[2] relative text-center shadow-sm sm:rounded-lg px-6 py-2 hover:bg-violet-500 hover:text-white flex justify-around items-center flex-col w-80 h-96">
                            <figure>
                                <img src={window.basePath + '/images/ta.png'} alt="Línea programática - Tecnoacademia" class="bg-white h-44 w-44 object-contain rounded-full" />
                            </figure>
                            Formular proyectos de Tecnoacademia
                        </a>
                    {/if}
                    {#if isSuperAdmin || checkRole(authUser, [11, 5])}
                        <a use:inertia href={route('convocatorias.ta-evaluaciones.index', convocatoria.id)} class="bg-violet-500 px-6 py-4 shadow-sm sm:rounded-lg transition duration-300 text-center text-white hover:bg-white hover:text-black z-[1] relative top-[-0.6rem] block hover:h-[104px] hover:pt-[13%]"> Evaluar proyectos de Tecnoacademia </a>
                    {/if}
                </div>

                <div>
                    {#if isSuperAdmin || (checkPermission(authUser, [17, 18, 19, 20]) && convocatoria.tipo_convocatoria == 1) || (checkPermission(authUser, [17, 18, 19, 20]) && convocatoria.tipo_convocatoria == 3)}
                        <a use:inertia href={route('convocatorias.tp.index', convocatoria.id)} class="bg-white overflow-hidden z-[2] relative text-center shadow-sm sm:rounded-lg px-6 py-2 hover:bg-violet-500 hover:text-white flex justify-around items-center flex-col w-80 h-96">
                            <figure>
                                <img src={window.basePath + '/images/tp.png'} alt="Línea programática - Tecnoparque" class="bg-white h-44 w-44 object-contain rounded-full" />
                            </figure>
                            Formular proyectos de Tecnoparque
                        </a>
                    {/if}
                    {#if isSuperAdmin || checkRole(authUser, [11, 17])}
                        <a use:inertia href={route('convocatorias.tp-evaluaciones.index', convocatoria.id)} class="bg-violet-500 px-6 py-4 shadow-sm sm:rounded-lg transition duration-300 text-center text-white hover:bg-white hover:text-black z-[1] relative top-[-0.6rem] block hover:h-[104px] hover:pt-[13%]"> Evaluar proyectos de Tecnoparque </a>
                    {/if}
                </div>

                <div>
                    {#if isSuperAdmin || (checkPermission(authUser, [5, 6, 7, 16]) && convocatoria.tipo_convocatoria == 1) || (checkPermission(authUser, [5, 6, 7, 16]) && convocatoria.tipo_convocatoria == 3)}
                        <a use:inertia href={route('convocatorias.servicios-tecnologicos.index', convocatoria.id)} class="bg-white overflow-hidden z-[2] relative text-center shadow-sm sm:rounded-lg px-6 py-2 hover:bg-violet-500 hover:text-white flex justify-around items-center flex-col w-80 h-96">
                            <figure>
                                <img src={window.basePath + '/images/st.png'} alt="Línea programática - Servicios tecnológicos" class="bg-white h-44 w-44 object-contain rounded-full" />
                            </figure>
                            Formular proyectos de Servicios Tecnológicos
                        </a>
                    {/if}
                    {#if isSuperAdmin || checkRole(authUser, [11, 19])}
                        <a use:inertia href={route('convocatorias.servicios-tecnologicos-evaluaciones.index', convocatoria.id)} class="bg-violet-500 px-6 py-4 shadow-sm sm:rounded-lg transition duration-300 text-center text-white hover:bg-white hover:text-black z-[1] relative top-[-0.6rem] block hover:h-[104px] hover:pt-[9.7%]"> Evaluar proyectos Servicios Tecnológicos </a>
                    {/if}
                </div>

                <div>
                    {#if isSuperAdmin || (checkPermission(authUser, [11, 12, 13, 21]) && convocatoria.tipo_convocatoria == 1) || (checkPermission(authUser, [11, 12, 13, 21]) && convocatoria.tipo_convocatoria == 3)}
                        <a use:inertia href={route('convocatorias.cultura-innovacion.index', convocatoria.id)} class="bg-white overflow-hidden z-[2] relative text-center shadow-sm sm:rounded-lg px-6 py-2 hover:bg-violet-500 hover:text-white flex justify-around items-center flex-col w-80 h-96">
                            <figure>
                                <img src={window.basePath + '/images/cultura-innovacion.png'} alt="Línea programática - Servicios tecnológicos" class="bg-white h-44 w-44 object-contain rounded-full" />
                            </figure>
                            Formular proyectos de Apropiación de la cultura de la innovación
                        </a>
                    {/if}
                    {#if isSuperAdmin || checkRole(authUser, [11, 20])}
                        <a use:inertia href={route('convocatorias.cultura-innovacion-evaluaciones.index', convocatoria.id)} class="bg-violet-500 px-6 py-4 shadow-sm sm:rounded-lg transition duration-150 text-center text-white hover:bg-white hover:text-black z-[1] relative top-[-0.6rem] block"> Evaluar proyectos de apropiación proyectos de la cultura de la innovación </a>
                    {/if}
                </div>
            </div>
        {/if}
    </div>
</AuthenticatedLayout>
