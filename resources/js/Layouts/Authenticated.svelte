<script context="module">
    import { writable } from 'svelte/store'
    export const title = writable(null)
    export const permissions = writable(null)
</script>

<script>
    import { Inertia } from '@inertiajs/inertia'
    import { inertia, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { onMount } from 'svelte'
    import moment from 'moment'

    import ApplicationLogo from '@/Shared/ApplicationLogo'
    import Dropdown from '@/Shared/Dropdown'
    import Icon from '@/Shared/Icon'
    import ResponsiveNavLink from '@/Shared/ResponsiveNavLink'
    import FlashMessages from '@/Shared/FlashMessages'
    import Dialog from '@/Shared/Dialog'
    import Button from '@/Shared/Button'
    import Loading from '@/Shared/Loading'
    import DataTableMenu from '@/Shared/DataTableMenu'
    import { Item, Text } from '@smui/list'

    let dialogOpen = false
    let dialogNotifications = false
    let showingNavigationDropdown = false

    let authUser = $page.props.auth
    let isSuperAdmin = checkRole(authUser.user, [1])

    let loading = true
    Inertia.on('start', () => {
        loading = false
    })
    Inertia.on('finish', () => {
        loading = true
    })

    let body = document.getElementById('body')
    onMount(() => {
        body.classList.remove('mdc-dialog-scroll-lock')
    })
</script>

<svelte:head>
    <title>{$title ? `${$title} - SGPS-SIPRO` : 'SGPS-SIPRO'}</title>
</svelte:head>

<div class="min-h-screen app-wrapper">
    <nav class="bg-white border-b border-gray-100">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <a use:inertia href={route('dashboard')}>
                            <ApplicationLogo class="w-10" />
                        </a>
                    </div>

                    <!-- Navigation Links -->
                    <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex sm:items-center">
                        <Button on:click={() => (dialogOpen = true)} variant={null}>Menú de navegación</Button>
                    </div>
                </div>

                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    {#if $page.props.convocatoria}
                        <div class="bg-cyan-500 text-white py-2 px-4 shadow border-b-2 flex">
                            {@html $page.props.convocatoria.fase_formateada}
                            {#if isSuperAdmin}
                                <Button on:click={() => Inertia.visit(route('convocatorias.edit', [$page.props.convocatoria.id]))} class="ml-4 mb-2" variant="raised">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                    </svg>
                                </Button>
                            {/if}
                        </div>
                    {/if}

                    <!-- Settings Dropdown -->
                    <div class="mr-5 ml-5 relative">
                        <button on:click={() => (dialogNotifications = true)} class="flex items-center hover:text-cyan-600 {authUser.numeroNotificaciones > 0 ? 'tada' : ''}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            {#if authUser.numeroNotificaciones > 0}
                                <span class="absolute bg-cyan-500 flex h-5/6 items-center justify-center rounded-full text-center text-white text-xs w-5/6" style="top: -10px; left: 10px;">{authUser.numeroNotificaciones}</span>
                            {/if}
                        </button>
                    </div>
                    <div class="ml-3 relative">
                        <Dropdown class="mt-1" placement="bottom-end">
                            <div class="flex items-center cursor-pointer select-none group">
                                <div class="text-gray-700 group-hover:text-cyan-600 focus:text-cyan-600 mr-1 whitespace-no-wrap">
                                    <span class="capitalize">{authUser.user.nombre}</span>
                                </div>
                                <Icon name="cheveron-down" class="w-5 h-5 group-hover:fill-cyan-600 fill-gray-700 focus:fill-cyan-600" />
                            </div>
                            <div slot="dropdown" class="mt-2 py-2 shadow-xl bg-white rounded text-sm">
                                <div class="flex items-center px-6 py-2 hover:bg-cyan-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" style="flex-basis: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    <span class="ml-1.5"><a href="mailto:sgpssipro@sena.edu.co">sgpssipro@sena.edu.co</a></span>
                                </div>

                                <a use:inertia href={route('users.perfil')} class="flex items-center px-6 py-2 hover:bg-cyan-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" style="flex-basis: 20px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="ml-1.5">Perfil</span>
                                </a>

                                <a use:inertia={{ method: 'post' }} href={route('logout')} class="flex items-center px-6 py-2 hover:bg-cyan-500 hover:text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="ml-1.5">{$_('Logout')}</span>
                                </a>
                            </div>
                        </Dropdown>
                    </div>
                </div>
            </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div class="sm:hidden{(showingNavigationDropdown ? ' block' : '', !showingNavigationDropdown ? ' hidden' : '')}">
            <div class="pt-2 pb-3 space-y-1">
                <ResponsiveNavLink href={route('dashboard')} active={route().current('dashboard')}>Menú de navegación</ResponsiveNavLink>
            </div>

            <!-- Responsive Settings Options -->
            <div class="pt-4 pb-1 border-t border-gray-200">
                <div class="flex items-center px-4">
                    <div class="font-medium text-base text-gray-800">
                        {authUser.user.name}
                    </div>
                    <div class="font-medium text-sm text-gray-500">
                        {authUser.user.email}
                    </div>
                </div>

                <div class="mt-3 space-y-1">
                    <ResponsiveNavLink href={route('logout')} method="post" as="button">
                        {$_('Logout')}
                    </ResponsiveNavLink>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Heading -->
    {#if $$slots.header}
        <slot name="header" />
    {/if}

    <!-- Page Content -->
    <main class="lg:px-8 max-w-7xl md:p-12 mx-auto px-4 py-8 sm:px-6">
        <FlashMessages />
        <Loading {loading} />
        <slot />
    </main>
</div>

<!-- Dialog -->
<Dialog bind:open={dialogOpen} id="main-menu" fullscreen>
    <div slot="title" class="mb-6 text-center text-cyan-600">
        <div class="">Menú de navegación</div>
    </div>
    <div slot="content">
        <div class="grid grid-cols-3 gap-5 p-8">
            {#if isSuperAdmin}
                <Button on:click={() => Inertia.visit(route('anexos.index'))} variant={route().current('anexos.*') ? 'raised' : 'outlined'} class="p-2">Anexos</Button>
            {/if}

            {#if isSuperAdmin}
                <Button on:click={() => Inertia.visit(route('centros-formacion.index'))} variant={route().current('centros-formacion.*') ? 'raised' : 'outlined'} class="p-2">Centros de formación</Button>
            {/if}

            {#if isSuperAdmin || checkRole(authUser.user, [11]) || checkPermission(authUser.user, [1, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 17, 18, 19, 14, 15, 16, 20, 21])}
                <Button on:click={() => Inertia.visit(route('convocatorias.index'))} variant={route().current('convocatorias.*') ? 'raised' : 'outlined'} class="p-2">Convocatorias</Button>
            {/if}

            {#if isSuperAdmin || checkRole(authUser.user, [20, 18, 19, 5, 17])}
                <Button on:click={() => Inertia.visit(route('evaluaciones.index'))} variant={route().current('evaluaciones.*') ? 'raised' : 'outlined'} class="p-2">Evaluaciones</Button>
            {/if}

            <Button on:click={() => Inertia.visit(route('grupos-investigacion.index'))} variant={route().current('grupos-investigacion.*') ? 'raised' : 'outlined'} class="p-2">Grupos, líneas y semilleros de investigación</Button>

            {#if isSuperAdmin}
                <Button on:click={() => Inertia.visit(route('lineas-programaticas.index'))} variant={route().current('lineas-programaticas.*') ? 'raised' : 'outlined'} class="p-2">Líneas programáticas</Button>
            {/if}

            {#if isSuperAdmin}
                <Button on:click={() => Inertia.visit(route('lineas-tecnoacademia.index'))} variant={route().current('lineas-tecnoacademia.*') ? 'raised' : 'outlined'} class="p-2">Líneas TecnoAcademia</Button>
            {/if}

            {#if isSuperAdmin}
                <Button on:click={() => Inertia.visit(route('mesas-tecnicas.index'))} variant={route().current('mesas-tecnicas.*') ? 'raised' : 'outlined'} class="p-2">Mesas técnicas</Button>
            {/if}

            {#if isSuperAdmin || checkRole(authUser.user, [4]) || checkPermission(authUser.user, [8, 17])}
                <Button on:click={() => Inertia.visit(route('nuevos-proyectos-ta-tp'))} variant={route().current('nuevos-proyectos-ta-tp') ? 'raised' : 'outlined'} class="p-2">Nuevos proyectos TecnoAcademia - Tecnoparque</Button>
            {/if}

            {#if isSuperAdmin}
                <Button on:click={() => Inertia.visit(route('configuracion-presupuesto-sennova'))} variant={route().current('configuracion-presupuesto-sennova.*') ? 'raised' : 'outlined'} class="p-2">Presupuesto SENNOVA</Button>
            {/if}

            {#if isSuperAdmin || checkRole(authUser.user, [4, 21, 20, 18, 19, 5, 17])}
                <Button on:click={() => Inertia.visit(route('programas-formacion.index'))} variant={route().current('programas-formacion.*') ? 'raised' : 'outlined'} class="p-2">Programas de formación</Button>
            {/if}

            {#if isSuperAdmin || checkRole(authUser.user, [20, 18, 19, 5, 17])}
                <Button on:click={() => Inertia.visit(route('proyectos.index'))} variant={route().current('proyectos.*') ? 'raised' : 'outlined'} class="p-2">Proyectos</Button>
            {/if}

            {#if isSuperAdmin || checkRole(authUser.user, [4, 6]) || checkPermission(authUser.user, [22])}
                <Button on:click={() => Inertia.visit(route('proyectos-capacidad-instalada.index'))} variant={route().current('proyectos-capacidad-instalada.*') ? 'raised' : 'outlined'} class="p-2">Proyectos de capacidad instalada</Button>
            {/if}

            {#if isSuperAdmin || checkRole(authUser.user, [5, 10, 12, 22])}
                <Button on:click={() => Inertia.visit(route('proyectos-idi-tecnoacademia.index'))} variant={route().current('proyectos-idi-tecnoacademia.*') ? 'raised' : 'outlined'} class="p-2">Proyectos e iniciativas I+D+i TecnoAcademias</Button>
            {/if}

            {#if isSuperAdmin}
                <Button on:click={() => Inertia.visit(route('redes-conocimiento.index'))} variant={route().current('redes-conocimiento.*') ? 'raised' : 'outlined'} class="p-2">Redes de conocimiento</Button>
            {/if}

            {#if isSuperAdmin}
                <Button on:click={() => Inertia.visit(route('regionales.index'))} variant={route().current('regionales.*') ? 'raised' : 'outlined'} class="p-2">Regionales</Button>
            {/if}

            {#if isSuperAdmin || checkRole(authUser.user, [20, 18, 19, 5, 17])}
                <Button on:click={() => Inertia.visit(route('reportes.index'))} variant={route().current('reportes.*') ? 'raised' : 'outlined'} class="p-2">Reportes</Button>
            {/if}

            {#if isSuperAdmin}
                <Button on:click={() => Inertia.visit(route('roles-sennova.index'))} variant={route().current('roles-sennova.*') ? 'raised' : 'outlined'} class="p-2">Roles SENNOVA</Button>
            {/if}

            {#if isSuperAdmin}
                <Button on:click={() => Inertia.visit(route('roles.index'))} variant={route().current('roles.*') ? 'raised' : 'outlined'} class="p-2">Roles de sistema</Button>
            {/if}

            {#if isSuperAdmin}
                <Button on:click={() => Inertia.visit(route('ambientes-modernizacion.index'))} variant={route().current('ambientes-modernizacion.*') ? 'raised' : 'outlined'} class="p-2">Seguimiento post cierre - Ambientes de modernización SENNOVA</Button>
            {/if}

            {#if isSuperAdmin || checkRole(authUser.user, [5])}
                <Button on:click={() => Inertia.visit(route('tecnoacademias.index'))} variant={route().current('tecnoacademias.*') ? 'raised' : 'outlined'} class="p-2">Tecnoacademias</Button>
            {/if}

            {#if isSuperAdmin}
                <Button on:click={() => Inertia.visit(route('tematicas-estrategicas.index'))} variant={route().current('tematicas-estrategicas.*') ? 'raised' : 'outlined'} class="p-2">Temáticas estratégicas SENA</Button>
            {/if}

            {#if isSuperAdmin || checkRole(authUser.user, [4, 21, 18, 19, 5, 17])}
                <Button on:click={() => Inertia.visit(route('users.index'))} variant={route().current('users.*') ? 'raised' : 'outlined'} class="p-2">Usuarios</Button>
            {/if}
        </div>
    </div>
    <div slot="actions">
        <div class="p-4">
            <Button on:click={() => (dialogOpen = false)} variant={null}>Cancelar</Button>
        </div>
    </div>
</Dialog>

<Dialog bind:open={dialogNotifications} id="notificaciones" fullscreen hexBgColor="#58badd">
    <div slot="title" class="mb-6 text-center text-cyan-600 mb-4">
        <div class="text-white">Notificaciones recientes</div>
        <img src="/images/notifications.png" alt="" class="w-80 mx-auto" />
    </div>
    <div slot="content" class="text-white scrollbar style-scrollbar">
        <div>
            <ul class="divide-y">
                {#each authUser.notificaciones as notificacion}
                    <li class="flex justify-between py-4">
                        <div>
                            {notificacion.data.message}
                            <br />
                            <small class="mr-2">{moment(notificacion.created_at).locale('es').fromNow()}</small>
                        </div>
                        <DataTableMenu style="color: #fff">
                            {#if notificacion.data.action}
                                <Item on:SMUI:action={() => Inertia.visit(notificacion.data.action)}>
                                    <Text>Ver detalles</Text>
                                </Item>
                            {:else}
                                <Item>
                                    <Text>No hay acciones disponibles</Text>
                                </Item>
                            {/if}
                        </DataTableMenu>
                    </li>
                {/each}
            </ul>
            <div class="flex items-center justify-center">
                <Button on:click={() => Inertia.visit(route('notificaciones.index'))} class="!text-white !border-white hover:opacity-90 transition-all" variant="outlined">Administrar todas las notificaciones</Button>
            </div>
        </div>
    </div>
    <div slot="actions">
        <div class="p-4">
            <Button on:click={() => (dialogNotifications = false)} class="!text-white !border-white hover:opacity-90 transition-all" variant="outlined">Cancelar</Button>
        </div>
    </div>
</Dialog>

<style>
    .tada {
        -webkit-animation: tada 1.5s ease infinite;
        animation: tada 1.5s ease infinite;
    }

    @-webkit-keyframes tada {
        from {
            -webkit-transform: scale3d(1, 1, 1);
            transform: scale3d(1, 1, 1);
        }
        10%,
        20% {
            -webkit-transform: scale3d(0.95, 0.95, 0.95) rotate3d(0, 0, 1, -10deg);
            transform: scale3d(0.95, 0.95, 0.95) rotate3d(0, 0, 1, -10deg);
        }
        30%,
        50%,
        70%,
        90% {
            -webkit-transform: scale3d(1, 1, 1) rotate3d(0, 0, 1, 10deg);
            transform: scale3d(1, 1, 1) rotate3d(0, 0, 1, 10deg);
        }
        40%,
        60%,
        80% {
            -webkit-transform: scale3d(1, 1, 1) rotate3d(0, 0, 1, -10deg);
            transform: scale3d(1, 1, 1) rotate3d(0, 0, 1, -10deg);
        }
        to {
            -webkit-transform: scale3d(1, 1, 1);
            transform: scale3d(1, 1, 1);
        }
    }
    @keyframes tada {
        from {
            -webkit-transform: scale3d(1, 1, 1);
            transform: scale3d(1, 1, 1);
        }
        10%,
        20% {
            -webkit-transform: scale3d(0.95, 0.95, 0.95) rotate3d(0, 0, 1, -10deg);
            transform: scale3d(0.95, 0.95, 0.95) rotate3d(0, 0, 1, -10deg);
        }
        30%,
        50%,
        70%,
        90% {
            -webkit-transform: scale3d(1, 1, 1) rotate3d(0, 0, 1, 10deg);
            transform: scale3d(1, 1, 1) rotate3d(0, 0, 1, 10deg);
        }
        40%,
        60%,
        80% {
            -webkit-transform: rotate3d(0, 0, 1, -10deg);
            transform: rotate3d(0, 0, 1, -10deg);
        }
        to {
            -webkit-transform: scale3d(1, 1, 1);
            transform: scale3d(1, 1, 1);
        }
    }
</style>
