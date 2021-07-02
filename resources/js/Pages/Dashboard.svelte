<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'

    import Button from '@/Shared/Button'
    import Dialog from '@/Shared/Dialog'

    $title = 'Panel de control'

    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let dialogOpen = true
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div class="max-w-2xl">
                <h1 class="text-4xl">
                    ¡Bienvenido(a)
                    <span class="capitalize">{$page.props.auth.user.nombre}</span>! Formule proyectos de I+D+i, Tecnoacademia-Tecnoparque y de Servicios Tecnológicos
                </h1>
                <p class="text-2xl mt-4">Revise las convocatorias y empiece la formulación de proyectos.</p>

                {#if isSuperAdmin || checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 16, 15, 20, 21])}
                    <Button variant="raised" on:click={() => Inertia.visit(route('convocatorias.index'))} class="mt-4 inline-block">Revisar convocatorias</Button>
                {/if}
            </div>
            <div>
                <figure>
                    <img src={window.basePath + '/images/dashboard.png'} alt="" />
                </figure>
            </div>
        </div>
    </header>
    <div class="py-12">
        <div class="grid grid-cols-3 gap-10">
            {#if isSuperAdmin}
                <a use:inertia class="bg-white overflow-hidden shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col" href={route('anexos.index')}>Anexos</a>
            {/if}

            {#if isSuperAdmin}
                <a use:inertia class="bg-white overflow-hidden shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col" href={route('centros-formacion.index')}>Centros de formación</a>
            {/if}

            {#if isSuperAdmin || checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 16, 15, 20, 21])}
                <a use:inertia class="bg-white overflow-hidden shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col" href={route('convocatorias.index')}>Convocatorias</a>
            {/if}

            {#if isSuperAdmin}
                <a use:inertia class="bg-white overflow-hidden shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col" href={route('grupos-investigacion.index')}>Grupos de investigación</a>
            {/if}

            {#if isSuperAdmin}
                <a use:inertia class="bg-white overflow-hidden shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col" href={route('lineas-investigacion.index')}>Líneas de investigación</a>
            {/if}

            {#if isSuperAdmin}
                <a use:inertia class="bg-white overflow-hidden shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col" href={route('lineas-programaticas.index')}>Líneas programáticas</a>
            {/if}

            {#if isSuperAdmin}
                <a use:inertia class="bg-white overflow-hidden shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col" href={route('mesas-tecnicas.index')}>Mesas técnicas</a>
            {/if}

            {#if isSuperAdmin}
                <a use:inertia class="bg-white overflow-hidden shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col" href={route('programas-formacion.index')}>Programas de formación</a>
            {/if}

            {#if isSuperAdmin}
                <a use:inertia class="bg-white overflow-hidden shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col" href={route('redes-conocimiento.index')}>Redes de conocimiento</a>
            {/if}

            {#if isSuperAdmin}
                <a use:inertia class="bg-white overflow-hidden shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col" href={route('regionales.index')}>Regionales</a>
            {/if}

            {#if isSuperAdmin}
                <a use:inertia class="bg-white overflow-hidden shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col" href={route('roles-sennova.index')}>Roles SENNOVA</a>
            {/if}

            {#if isSuperAdmin}
                <a use:inertia class="bg-white overflow-hidden shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col" href={route('roles.index')}>Roles de sistema</a>
            {/if}

            {#if isSuperAdmin}
                <a use:inertia class="bg-white overflow-hidden shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col" href={route('sectores-productivos.index')}>Sectores productivos</a>
            {/if}

            {#if isSuperAdmin}
                <a use:inertia class="bg-white overflow-hidden shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col" href={route('semilleros-investigacion.index')}>Semilleros de investigación</a>
            {/if}

            {#if isSuperAdmin}
                <a use:inertia class="bg-white overflow-hidden shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col" href={route('tecnoacademias.index')}>Tecnoacademias</a>
            {/if}

            {#if isSuperAdmin}
                <a use:inertia class="bg-white overflow-hidden shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col" href={route('tematicas-estrategicas.index')}>Temáticas estratégicas SENA</a>
            {/if}

            {#if isSuperAdmin || checkRole(authUser, [4])}
                <a use:inertia class="bg-white overflow-hidden shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col" href={route('users.index')}>Usuarios</a>
            {/if}
        </div>
    </div>

    <Dialog bind:open={dialogOpen} id="informacion">
        <div slot="title" class="flex items-center flex-col mb-10">
            <figure>
                <img src={window.basePath + '/images/megaphone.png'} alt="Megafono" />
            </figure>
            Novedades
        </div>
        <div slot="content">
            <small>Junio 8</small>
            <div>
                <h1 class="text-purple-200 mb-4">Cambiar contraseña</h1>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 149 31" class="w-1/4">
                    <defs>
                        <style>
                            .cls-1 {
                                fill: #fefefe;
                            }
                            .cls-2 {
                                fill: #cecece;
                            }
                        </style>
                    </defs>
                    <g>
                        <g>
                            <rect class="cls-1" width="149" height="31" rx="2.25" />
                            <path class="cls-2" d="M33.89,22.28l-2.32-3.87-2.31,3.87-2-1.13,2.28-3.82H25.24V15.12h4.37l-2.3-3.84,1.93-1.13L31.57,14l2.32-3.89,1.93,1.13-2.29,3.84h4.23v2.21H33.54l2.28,3.82Z" /><path class="cls-2" d="M47.88,22.28l-2.32-3.87-2.3,3.87-2-1.13,2.29-3.82H39.23V15.12H43.6l-2.3-3.84,1.93-1.13L45.56,14l2.32-3.89,1.93,1.13-2.29,3.84h4.23v2.21H47.53l2.28,3.82Z" />
                            <path class="cls-2" d="M61.87,22.28l-2.31-3.87-2.31,3.87-2-1.13,2.29-3.82H53.22V15.12h4.37l-2.3-3.84,1.94-1.13L59.55,14l2.32-3.89,1.94,1.13-2.29,3.84h4.22v2.21H61.52l2.29,3.82Z" /><path class="cls-2" d="M75.87,22.28l-2.32-3.87-2.31,3.87-2-1.13,2.29-3.82H67.21V15.12h4.37l-2.3-3.84,1.94-1.13L73.54,14l2.33-3.89,1.93,1.13-2.29,3.84h4.22v2.21H75.51l2.29,3.82Z" />
                            <path class="cls-2" d="M89.86,22.28l-2.32-3.87-2.31,3.87-2-1.13,2.28-3.82H81.21V15.12h4.37l-2.3-3.84,1.93-1.13L87.54,14l2.32-3.89,1.93,1.13L89.5,15.12h4.23v2.21H89.51l2.28,3.82Z" />
                            <path class="cls-2" d="M103.85,22.28l-2.31-3.87-2.31,3.87-2-1.13,2.29-3.82H95.2V15.12h4.37l-2.3-3.84,1.93-1.13L101.53,14l2.32-3.89,1.94,1.13-2.3,3.84h4.23v2.21H103.5l2.29,3.82Z" /><path class="cls-2" d="M117.84,22.28l-2.31-3.87-2.31,3.87-2-1.13,2.29-3.82h-4.36V15.12h4.37l-2.3-3.84,1.94-1.13L115.52,14l2.32-3.89,1.94,1.13-2.29,3.84h4.22v2.21h-4.22l2.29,3.82Z" />
                        </g>
                    </g>
                </svg>
                <p>Diríjase a la opción <strong>Cambiar contraseña</strong> dando clic en su nombre de usuario ubicado en la parte superior derecha.</p>
            </div>
            <hr class="mt-10 mb-10" />
            <div>
                <h1 class="text-purple-200 mb-4">Reportar errores</h1>
                <p>Si desea notificar un error diríjase a la opción <strong>Soporte</strong> dando clic en su nombre de usuario ubicado en la parte superior derecha.</p>
            </div>
        </div>
        <div slot="actions">
            <div class="p-4">
                <Button variant="raised" on:click={(event) => (dialogOpen = false)}>Entendido</Button>
            </div>
        </div>
    </Dialog>
</AuthenticatedLayout>
