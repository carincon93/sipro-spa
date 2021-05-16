<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'

    import Button from '@/Components/Button'

    export let convocatoria

    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0

    let canIndexIDi = authUser.can.find((element) => element == 'idi.index') == 'idi.index'

    let canIndexConvocatorias = authUser.can.find((element) => element == 'convocatorias.index') == 'convocatorias.index'

    let canShowConvocatorias = authUser.can.find((element) => element == 'convocatorias.show') == 'convocatorias.show'

    let canCreateConvocatorias = authUser.can.find((element) => element == 'convocatorias.create') == 'convocatorias.create'

    let canEditConvocatorias = authUser.can.find((element) => element == 'convocatorias.edit') == 'convocatorias.edit'

    let canDestroyConvocatorias = authUser.can.find((element) => element == 'convocatorias.destroy') == 'convocatorias.destroy'

    $title = 'Convocatorias - Dashboard'
</script>

<AuthenticatedLayout>
    <div class="py-12">
        {#if isSuperAdmin}
            <div class="flex justify-center items-center flex-col">
                <p>
                    Si desea revisar, {#if canEditConvocatorias} editar {/if} la información de la convocatoria, de clic en el siguiente botón
                </p>
                <div>
                    <Button on:click={() => Inertia.visit(route('convocatorias.edit', [convocatoria.id]))} class="mt-8 mb-20" variant="raised">Ver detalles</Button>
                </div>
            </div>
        {/if}
        <h1 class="text-4xl text-center">
            ¡Bienvenido(a)
            <span class="capitalize">{$page.props.auth.user.nombre}</span>! Formule proyectos de I+D+i, Tecnoacademia-Tecnoparque y de Servicios Tecnológicos para la vigencia {convocatoria.year}
        </h1>
        <div class="grid grid-cols-3 gap-10 mt-24">
            {#if isSuperAdmin}
                <a use:inertia href={route('convocatorias.idi.index', convocatoria.id)} class="bg-white overflow-hidden shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col h-96">
                    <span>ICON</span>
                    IDi
                </a>
            {/if}
        </div>
    </div>
</AuthenticatedLayout>
