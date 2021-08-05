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
                    <span class="capitalize">{$page.props.auth.user.nombre}</span>!
                </h1>
                {#if checkRole(authUser, [1])}
                    <p class="text-indigo-700 mt-4">Su rol principal es: Administrador</p>
                {:else if checkRole(authUser, [2])}
                    <p class="text-indigo-700 mt-4">Su rol principal es: Director regional</p>
                {:else if checkRole(authUser, [3])}
                    <p class="text-indigo-700 mt-4">Su rol principal es: Subdirector de centro de formación</p>
                {:else if checkRole(authUser, [5, 17, 18, 19, 20])}
                    <p class="text-indigo-700 mt-4">Su rol principal es: Activador (a)</p>
                {:else if checkRole(authUser, [4])}
                    <p class="text-indigo-700 mt-4">Su rol principal es: Dinamizador (a) SENNOVA</p>
                {:else if checkRole(authUser, [11])}
                    <p class="text-indigo-700 mt-4">Su rol principal es: Evaluador</p>
                {:else}
                    <p class="text-2xl mt-4">Formule proyectos de I+D+i, Tecnoacademia-Tecnoparque, Servicios Tecnológicos y/o Cultura de la innovación.</p>
                {/if}
                <p class="mt-4">Revise las convocatorias haciendo clic en el botón <strong>Revisar convocatorias</strong> y empiece la revisión o formulación de proyectos.</p>

                {#if isSuperAdmin || checkRole(authUser, [11]) || checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 17, 18, 19, 14, 15, 16, 20, 21])}
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

            {#if isSuperAdmin || checkRole(authUser, [11]) || checkPermission(authUser, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 17, 18, 19, 14, 15, 16, 20, 21])}
                <a use:inertia class="bg-white overflow-hidden shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col" href={route('convocatorias.index')}>Convocatorias</a>
            {/if}

            {#if isSuperAdmin}
                <a use:inertia class="bg-white overflow-hidden shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col" href={route('evaluaciones.index')}>Evaluaciones</a>
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
                <a use:inertia class="bg-white overflow-hidden shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col" href={route('lineas-tecnoacademia.index')}>Líneas TecnoAcademia</a>
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

            {#if isSuperAdmin || checkRole(authUser, [4])}
                <a use:inertia class="bg-white overflow-hidden shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col" href={route('semilleros-investigacion.index')}>Semilleros de investigación</a>
            {/if}

            {#if isSuperAdmin || checkRole(authUser, [5])}
                <a use:inertia class="bg-white overflow-hidden shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col" href={route('tecnoacademias.index')}>Tecnoacademias</a>
            {/if}

            {#if isSuperAdmin}
                <a use:inertia class="bg-white overflow-hidden shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col" href={route('tematicas-estrategicas.index')}>Temáticas estratégicas SENA</a>
            {/if}

            {#if isSuperAdmin || checkRole(authUser, [4, 18, 19, 5, 17])}
                <a use:inertia class="bg-white overflow-hidden shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col" href={route('users.index')}>Usuarios</a>
            {/if}
        </div>
    </div>

    <Dialog bind:open={dialogOpen} id="informacion">
        <div slot="title" class="flex items-center flex-col mb-10">
            <figure>
                <img src={window.basePath + '/images/megaphone.png'} alt="Megafono" />
            </figure>
            Anuncios
        </div>
        <div slot="content">
            <small>Agosto 2</small>
            <div>
                <h1 class="text-purple-200 mb-4">Manual de usuario</h1>

                <p>Antes de empezar la formulación descargue el manual de usuario.</p>
                <a target="_blank" class="text-white underline inline-block mb-4 flex" download href={route('manual-usuario.download')}>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Manual de usuario
                </a>
            </div>
            <hr class="mt-10 mb-10" />
            <div>
                <h1 class="text-purple-200 mb-4">Usuarios del curso de estructuración de proyectos</h1>
                <p>Si usted es una de las personas que realizó el curso de estructuración de proyectos y su usuario requiere un rol diferente al de Proponente I+D+i por favor escriba al correo ccvasquez@sena.edu.co solicitando cambio de rol.</p>
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
