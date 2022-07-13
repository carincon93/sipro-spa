<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import LoadingButton from '@/Shared/LoadingButton'
    import Textarea from '@/Shared/Textarea'

    import Header from '../Shared/Header'

    export let errors
    export let proyectoCapacidadInstalada

    $: $title = 'Crear objetivo específico'

    /**
     * Validar si el usuario autenticado es SuperAdmin
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let form = useForm({
        descripcion: '',
        descripcion_resultado: '',
    })

    function submit() {
        if (proyectoCapacidadInstalada.allowed.to_update) {
            $form.post(route('proyectos-capacidad-instalada.objetivos-especificos.store', [proyectoCapacidadInstalada.id]))
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <Header {proyectoCapacidadInstalada} />
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={proyectoCapacidadInstalada.allowed.to_update ? undefined : true}>
                <div class="mt-8">
                    <Textarea label="Descripción del objetivo específico" maxlength="255" id="descripcion" error={errors.descripcion} bind:value={$form.descripcion} required />
                </div>
                <div class="mt-8">
                    <Textarea label="Descripción del resultado" maxlength="255" id="descripcion_resultado" error={errors.descripcion_resultado} bind:value={$form.descripcion_resultado} required />
                </div>
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if proyectoCapacidadInstalada.allowed.to_update}
                    <LoadingButton loading={$form.processing} class="ml-auto" type="submit">Crear objetivo específico</LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
