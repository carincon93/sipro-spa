<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { Inertia } from '@inertiajs/inertia'

    import Button from '@/Shared/Button'
    import InfoMessage from '@/Shared/InfoMessage'

    $title = 'Panel de control'

    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])
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
                {:else if checkRole(authUser, [21])}
                    <p class="text-indigo-700 mt-4">Su rol principal es: Líder de grupo de investigación</p>
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
        <InfoMessage class="mb-10">
            Antes de añadir un nuevo rubro presupuestal a una convocatoria debe seguir los siguientes pasos:
            <ul>
                <li>1. Crear el un rubro en el primer grupo presupuestal</li>
                <li>2. Crear el un rubro en el segundo grupo presupuestal</li>
                <li>3. Crear el un rubro en el tercer grupo presupuestal</li>
                <li>4. Crear el uso presupuestal</li>
                <li>5. Relacionar los anteriores ítems creados en el módulo Rubros presupuestales SENNOVA</li>
                <li>6. Por último ir al módulo de <strong>Convocatorias</strong> > <strong>Editar</strong> > <strong>Configurar rubros presupuesto SENNOVA.</strong> Allí podrá asociar a la convocatoria el rubro presupuestal nuevo.</li>
            </ul>
        </InfoMessage>
        <div class="grid grid-cols-3 gap-10">
            {#if isSuperAdmin}
                <a use:inertia class="bg-white overflow-hidden shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col" href={route('primer-grupo-presupuestal.index')}>Primer grupo presupuestal</a>
            {/if}
            {#if isSuperAdmin}
                <a use:inertia class="bg-white overflow-hidden shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col" href={route('segundo-grupo-presupuestal.index')}>Segundo grupo presupuestal</a>
            {/if}
            {#if isSuperAdmin}
                <a use:inertia class="bg-white overflow-hidden shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col" href={route('tercer-grupo-presupuestal.index')}>Tercer grupo presupuestal</a>
            {/if}
            {#if isSuperAdmin}
                <a use:inertia class="bg-white overflow-hidden shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col" href={route('usos-presupuestales.index')}>Usos presupuestales</a>
            {/if}
            {#if isSuperAdmin}
                <a use:inertia class="bg-white overflow-hidden shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col" href={route('presupuesto-sennova.index')}>Rubros presupuestales SENNOVA</a>
            {/if}
        </div>
    </div>
</AuthenticatedLayout>
