<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import LoadingButton from '@/Shared/LoadingButton'
    import Textarea from '@/Shared/Textarea'
    import Select from '@/Shared/Select'

    import Header from '../Shared/Header'

    export let errors
    export let proyectoCapacidadInstalada
    export let resultados
    export let tipologiasMinciencias

    $: $title = 'Crear producto'

    /**
     * Validar si el usuario autenticado es SuperAdmin
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let form = useForm({
        descripcion: '',
        resultado_id: null,
        tipologia_minciencias: null,
    })

    function submit() {
        if (proyectoCapacidadInstalada.allowed.to_update) {
            $form.post(route('proyectos-capacidad-instalada.productos.store', [proyectoCapacidadInstalada.id]))
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
                    <Textarea label="Descripción del producto" maxlength="255" id="descripcion" error={errors.descripcion} bind:value={$form.descripcion} required />
                </div>
                <div class="mt-4">
                    <Select id="resultado_id" items={resultados} bind:selectedValue={$form.resultado_id} error={errors.resultado_id} autocomplete="off" placeholder="Seleccione un resultado" required />
                </div>
                <div class="mt-4">
                    <Select id="tipologia_minciencias" items={tipologiasMinciencias} bind:selectedValue={$form.tipologia_minciencias} error={errors.tipologia_minciencias} autocomplete="off" placeholder="Seleccione una tipología" required />
                </div>
            </fieldset>
            <div class="shadow-inner bg-violet-200 border-violet-400 bottom-0 flex items-center justify-between mt-14 px-8 py-4 sticky">
                {#if proyectoCapacidadInstalada.allowed.to_update}
                    <LoadingButton loading={$form.processing} class="ml-auto" type="submit">Crear producto</LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
