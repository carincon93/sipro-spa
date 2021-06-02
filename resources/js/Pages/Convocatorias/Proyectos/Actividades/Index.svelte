<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page } from '@inertiajs/inertia-svelte'
    import { _ } from 'svelte-i18n'
    import Stepper from '@/Components/Stepper'
    import Gantt from '@/Components/Gantt'
    import InfoMessage from '@/Components/InfoMessage'

    export let convocatoria
    export let proyecto
    export let actividades

    $title = 'Actividades'

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

    let filters = {}
</script>

<AuthenticatedLayout>
    <Stepper {convocatoria} {proyecto} />

    <h1 class="text-3xl m-24 text-center">Actividades</h1>

    <InfoMessage message="Si desea añadir actividades, por favor diríjase al 'Arbol de objetivos'" />

    <Gantt
        items={actividades.data}
        request={isSuperAdmin || checkRole(74)
            ? {
                  uri: 'convocatorias.proyectos.actividades.edit',
                  params: [convocatoria.id, proyecto.id],
              }
            : null}
    />
</AuthenticatedLayout>
