<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import Pagination from '@/Components/Pagination'
    import Button from '@/Components/Button'
    import { Inertia } from '@inertiajs/inertia'

    import Stepper from '@/Components/Stepper'
    import Gantt from '@/Components/Gantt'

    export let call
    export let project
    export let outputs

    $title = $_('Outputs.plural')

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    let canIndexOutputs =
        authUser.can.find((element) => element == 'outputs.index') ==
        'outputs.index'
    let canShowOutputs =
        authUser.can.find((element) => element == 'outputs.show') ==
        'outputs.show'
    let canCreateOutputs =
        authUser.can.find((element) => element == 'outputs.create') ==
        'outputs.create'
    let canEditOutputs =
        authUser.can.find((element) => element == 'outputs.edit') ==
        'outputs.edit'
    let canDestroyOutputs =
        authUser.can.find((element) => element == 'outputs.destroy') ==
        'outputs.delete'

    let filters = {}
</script>

<AuthenticatedLayout>
    <Stepper {call} {project} />

    <h1 class="font-bold text-3xl m-24 text-center">{$_('Outputs.plural')}</h1>
    <div class="mb-6 flex justify-end items-center">
        <!-- <SearchFilter class="w-full max-w-md mr-4" bind:filters /> -->
        <div>
            {#if canCreateOutputs || isSuperAdmin}
                <Button
                    on:click={() =>
                        Inertia.visit(
                            route('calls.projects.outputs.create', [
                                call.id,
                                project.id,
                            ]),
                        )}
                >
                    <div>
                        <span>Crear</span>
                        <span class="hidden md:inline"
                            >{$_('Outputs.singular')}</span
                        >
                    </div>
                </Button>
            {/if}
        </div>
    </div>

    <Gantt
        items={outputs.data}
        request={canShowOutputs ||
        canEditOutputs ||
        canDestroyOutputs ||
        isSuperAdmin
            ? {
                  uri: 'calls.projects.outputs.edit',
                  params: [call.id, project.id],
              }
            : null}
    />

    <Pagination links={outputs.links} />
</AuthenticatedLayout>
