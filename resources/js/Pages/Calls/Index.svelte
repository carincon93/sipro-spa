<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import Button from '@/Components/Button'
    import { Inertia } from '@inertiajs/inertia'

    export let calls
    export let activeCall

    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    let canIndexCalls =
        authUser.can.find((element) => element == 'calls.index') ==
        'calls.index'
    let canShowCalls =
        authUser.can.find((element) => element == 'calls.show') == 'calls.show'
    let canCreateCalls =
        authUser.can.find((element) => element == 'calls.create') ==
        'calls.create'
    let canEditCalls =
        authUser.can.find((element) => element == 'calls.edit') == 'calls.edit'
    let canDestroyCalls =
        authUser.can.find((element) => element == 'calls.destroy') ==
        'calls.delete'

    $title = $_('Calls.plural')
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div
            class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6"
        >
            <div>
                {#if activeCall}
                    <h1 class="font-bold text-5xl">
                        Convocatoria {activeCall.year}
                    </h1>
                    <p class="text-2xl mt-4">
                        La convocatoria empezó el {activeCall.fecha_incio_for_humans}
                        y finaliza el {activeCall.fecha_finalizacion_for_humans}.
                    </p>
                    {#if canIndexCalls || isSuperAdmin}
                        <Button
                            on:click={() =>
                                Inertia.visit(
                                    route('calls.dashboard', activeCall.id),
                                )}
                            variant="raised"
                            class="mt-4 inline-block"
                        >
                            {$_('Calls.singular')}
                            {activeCall.year}
                        </Button>
                    {/if}
                {:else}
                    <h1 class="font-bold text-5xl">
                        Aún no hay una convocatoria activa.
                    </h1>
                    <a
                        use:inertia
                        href={route('dashboard')}
                        class="bg-indigo-600 hover:bg-indigo-500 inline-block mt-4 overflow-hidden px-6 py-2 shadow-sm sm:rounded-lg text-white transition-colors"
                    >
                        {$_('Dashboard')}
                    </a>
                    {#if canCreateCalls || isSuperAdmin}
                        <a
                            use:inertia
                            href={route('calls.create')}
                            class="bg-indigo-600 hover:bg-indigo-500 inline-block mt-4 overflow-hidden px-6 py-2 shadow-sm sm:rounded-lg text-white transition-colors"
                        >
                            <span>Crear</span>
                            <span class="hidden md:inline"
                                >{$_('Calls.singular').toLowerCase()}</span
                            >
                        </a>
                    {/if}
                {/if}
            </div>
            <div>
                <figure>
                    <img src="/storage/images/dashboard.png" alt="" />
                </figure>
            </div>
        </div>
    </header>
    <div class="py-12">
        {#if canCreateCalls || isSuperAdmin}
            <div class="flex justify-center items-center flex-col">
                <p>
                    A continuación, se listan todas las convocatorias, si desea
                    crear una nueva de clic en el siguiente botón.
                </p>
                <div>
                    <Button
                        on:click={() => Inertia.visit(route('calls.create'))}
                        class="mt-8 mb-20"
                        variant="raised"
                    >
                        Crear
                        {$_('Calls.singular')}
                    </Button>
                </div>
            </div>
        {/if}
        <div class="grid grid-cols-3 gap-10">
            {#if canIndexCalls || isSuperAdmin}
                {#each calls.data as call (call.id)}
                    <a
                        use:inertia
                        href={route('calls.dashboard', call.id)}
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col"
                    >
                        <span>ICON</span>
                        {$_('Calls.singular')}
                        {call.year}
                        {#if call.active}
                            <small>Convocatoria activa</small>
                        {/if}
                    </a>
                {/each}
            {/if}
        </div>
    </div>
</AuthenticatedLayout>
