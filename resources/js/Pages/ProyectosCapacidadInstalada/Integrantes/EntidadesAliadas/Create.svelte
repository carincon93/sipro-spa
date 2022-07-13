<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Form from './Form'
    import Header from '../../Shared/Header'

    export let errors
    export let proyectoCapacidadInstalada

    $: $title = 'Crear entidad aliada'

    /**
     * Validar si el usuario autenticado es SuperAdmin
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let form = useForm({
        nombre: '',
        nit: '',
        documento: null,
    })

    function submit() {
        if (proyectoCapacidadInstalada.allowed.to_update) {
            $form.post(route('proyectos-capacidad-instalada.entidades-aliadas.store', [proyectoCapacidadInstalada.id]))
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <Header {proyectoCapacidadInstalada} />
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <Form {errors} {proyectoCapacidadInstalada} {form} {submit} allowToCreate={proyectoCapacidadInstalada.allowed.to_update} showInput={true} />
    </div>
</AuthenticatedLayout>
