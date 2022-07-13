<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Header from '../../Shared/Header'
    import Form from './Form'

    export let errors
    export let proyectoCapacidadInstalada
    export let entidadAliada

    $: $title = 'Editar entidad aliada'

    /**
     * Validar si el usuario autenticado es SuperAdmin
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let form = useForm({
        _method: 'put',
        nombre: entidadAliada.nombre,
        nit: entidadAliada.nit,
        documento: null,
    })

    function submit() {
        if (proyectoCapacidadInstalada.allowed.to_update) {
            $form.post(route('proyectos-capacidad-instalada.entidades-aliadas.update', [proyectoCapacidadInstalada.id, entidadAliada.id]), {
                preserveScroll: true,
            })
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <Header {proyectoCapacidadInstalada} />
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <Form {errors} {proyectoCapacidadInstalada} {entidadAliada} {form} {submit} />
    </div>
</AuthenticatedLayout>
