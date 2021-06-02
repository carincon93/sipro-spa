<script>
    import AuthenticatedLayout from '@/Layouts/Authenticated'
    import { inertia, page } from '@inertiajs/inertia-svelte'
    import { route, links } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import Button from '@/Components/Button'
    import { Inertia } from '@inertiajs/inertia'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(1)

    function checkRole(roleId) {
        return (
            authUser.roles.filter(function (role) {
                return role.id == roleId
            }).length > 0
        )
    }
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

                {#if isSuperAdmin || checkRole(74)}
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
            {#each links as link}
                {#if isSuperAdmin}
                    <a use:inertia href={route(link.route + '.index')} class="bg-white overflow-hidden shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col">
                        <span>ICON</span>
                        {link.name}
                    </a>
                {/if}
            {/each}
        </div>
    </div>
</AuthenticatedLayout>
