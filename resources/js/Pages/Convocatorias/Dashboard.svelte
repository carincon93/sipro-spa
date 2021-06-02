<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'

    import Button from '@/Components/Button'

    export let convocatoria

    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(1)

    function checkRole(roleId) {
        return (
            authUser.roles.filter(function (role) {
                return role.id == roleId
            }).length > 0
        )
    }

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
        <div class="flex justify-around mt-24">
            {#if isSuperAdmin || checkRole(74)}
                <a use:inertia href={route('convocatorias.idi.index', convocatoria.id)} class="bg-white overflow-hidden shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col w-80 h-96">
                    <figure>
                        <img src={window.basePath + '/images/idi.png'} alt="" class="rounded-full w-52" />
                    </figure>
                    I+D+i
                </a>
            {/if}
            {#if isSuperAdmin}
                <a use:inertia href={route('convocatorias.tatp.index', convocatoria.id)} class="bg-white overflow-hidden shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col w-80 h-96">
                    <span>ICON</span>
                    Tecnoacademia - Tecnoparque
                </a>
            {/if}
            {#if isSuperAdmin}
                <a use:inertia href={route('convocatorias.servicios-tecnologicos.index', convocatoria.id)} class="bg-white overflow-hidden shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col w-80 h-96">
                    <span>ICON</span>
                    Servicios tecnológicos
                </a>
            {/if}
        </div>
    </div>
</AuthenticatedLayout>
