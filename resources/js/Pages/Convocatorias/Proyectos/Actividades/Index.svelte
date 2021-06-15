<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page } from '@inertiajs/inertia-svelte'
    import { checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import Stepper from '@/Shared/Stepper'
    import Gantt from '@/Shared/Gantt'
    import InfoMessage from '@/Shared/InfoMessage'

    export let convocatoria
    export let proyecto
    export let actividades

    $title = 'Actividades'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])
</script>

<AuthenticatedLayout>
    <Stepper {convocatoria} {proyecto} />

    <h1 class="text-3xl m-24 text-center">Actividades</h1>

    <InfoMessage message="Debe generar las actividades en el 'Árbol de objetivos'. <br /><strong>Importante</strong> Una vez creadas las actividades, edite cada una haciendo clic en los tres puntos, a continuación, enlace los productos y rubros correspondientes, de esta manera se completa la cadena de valor." />

    <Gantt
        items={actividades.data}
        request={isSuperAdmin || checkPermission(authUser, [3, 4, 6, 7, 9, 10, 12, 13])
            ? {
                  uri: 'convocatorias.proyectos.actividades.edit',
                  params: [convocatoria.id, proyecto.id],
              }
            : null}
    />
</AuthenticatedLayout>
