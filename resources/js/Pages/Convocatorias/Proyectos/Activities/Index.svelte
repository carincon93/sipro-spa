<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page } from '@inertiajs/inertia-svelte'
    import { _ } from 'svelte-i18n'
    import Stepper from '@/Components/Stepper'
    import Gantt from '@/Components/Gantt'
    import InfoMessage from '@/Components/InfoMessage'

    export let call
    export let project
    export let activities

    $title = $_('Activities.plural')

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    let canIndexActivities = authUser.can.find((element) => element == 'activities.index') == 'activities.index'
    let canShowActivities = authUser.can.find((element) => element == 'activities.show') == 'activities.show'
    let canCreateActivities = authUser.can.find((element) => element == 'activities.create') == 'activities.create'
    let canEditActivities = authUser.can.find((element) => element == 'activities.edit') == 'activities.edit'
    let canDestroyActivities = authUser.can.find((element) => element == 'activities.destroy') == 'activities.delete'

    let filters = {}
</script>

<AuthenticatedLayout>
    <Stepper {call} {project} />

    <h1 class="font-bold text-3xl m-24 text-center">
        {$_('Activities.plural')}
    </h1>

    <InfoMessage message="Si desea añadir actividades, por favor diríjase al 'Arbol de objetivos'" />

    <Gantt
        items={activities.data}
        request={canShowActivities || canEditActivities || canDestroyActivities || isSuperAdmin
            ? {
                  uri: 'calls.projects.activities.edit',
                  params: [call.id, project.id],
              }
            : null}
    />
</AuthenticatedLayout>
