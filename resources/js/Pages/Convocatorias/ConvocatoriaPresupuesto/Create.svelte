<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import Select from '@/Shared/Select'

    export let errors
    export let convocatoria
    export let presupuestosSennova

    $: $title = 'Asociar rubro presupuestal SENNOVA a la convocatoria'

    /**
     * Validar si el usuario autenticado es SuperAdmin
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let form = useForm({
        presupuesto_sennova_id: null,
    })

    function submit() {
        if (isSuperAdmin) {
            $form.post(route('convocatorias.convocatoria-presupuesto.store', convocatoria.id))
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    <a use:inertia href={route('convocatorias.convocatoria-presupuesto.index', convocatoria.id)} class="text-violet-400 hover:text-violet-600"> Rubros presupuestales SENNOVA de la convocatoria </a>
                    <span class="text-violet-400 font-medium">/</span>
                    Crear
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={isSuperAdmin ? undefined : true}>
                <div class="mt-4">
                    <Label required class="mb-4" labelFor="presupuesto_sennova_id" value="Rubros presupuestales SENNOVA" />
                    <Select id="presupuesto_sennova_id" items={presupuestosSennova} bind:selectedValue={$form.presupuesto_sennova_id} error={errors.presupuesto_sennova_id} autocomplete="off" placeholder="Seleccione un rubro presupuestal SENNOVA" required />
                </div>
            </fieldset>
            <div class="shadow-inner bg-violet-200 border-violet-400 bottom-0 flex items-center justify-between mt-14 px-8 py-4 sticky">
                {#if isSuperAdmin}
                    <LoadingButton loading={$form.processing} class="ml-auto" type="submit">Asociar rubro presupuestal SENNOVA</LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
