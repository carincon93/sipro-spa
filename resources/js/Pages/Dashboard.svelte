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
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div
            class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6"
        >
            <div>
                <h1 class="font-bold text-5xl">SENNOVA</h1>
                <p class="text-2xl mt-4">
                    Revisa las convocatorias y formula proyectos.
                </p>

                {#if authUser.can.find((element) => element == 'calls.index') == 'calls.index' || authUser.can.find((element) => element == 'calls.show') == 'calls.show' || authUser.can.find((element) => element == 'calls.create') == 'calls.create' || authUser.can.find((element) => element == 'calls.edit') == 'calls.edit' || authUser.can.find((element) => element == 'calls.destroy') == 'calls.delete' || isSuperAdmin}
                    <Button
                        variant="raised"
                        on:click={() => Inertia.visit(route('calls.index'))}
                        class="mt-4 inline-block"
                    >
                        {$_('Calls.plural')}
                    </Button>
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
        <div class="grid grid-cols-3 gap-10">
            {#each links as link}
                {#if authUser.can.find((element) => element == link.route + '.index') == link.route + '.index' || authUser.can.find((element) => element == link.route + '.show') == link.route + '.show' || authUser.can.find((element) => element == link.route + '.create') == link.route + '.create' || authUser.can.find((element) => element == link.route + '.edit') == link.route + '.edit' || authUser.can.find((element) => element == link.route + '.destroy') == link.route + '.delete' || isSuperAdmin}
                    <a
                        use:inertia
                        href={route(link.route + '.index')}
                        class="bg-white overflow-hidden shadow-sm sm:rounded-lg block px-6 py-2 hover:bg-indigo-500 hover:text-white h-52 flex justify-around items-center flex-col"
                    >
                        <span>ICON</span>
                        {link.name}
                    </a>
                {/if}
            {/each}
        </div>
    </div>
</AuthenticatedLayout>
