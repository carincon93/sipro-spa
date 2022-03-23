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
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let sending = false
    let form = useForm({
        presupuesto_sennova_id: null,
    })

    function submit() {
        if (isSuperAdmin) {
            $form.post(route('convocatorias.convocatoria-presupuesto.store', convocatoria.id), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
            })
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    {#if isSuperAdmin}
                        <a use:inertia href={route('convocatorias.convocatoria-presupuesto.index', convocatoria.id)} class="text-indigo-400 hover:text-indigo-600"> Rubros presupuestales SENNOVA de la convocatoria </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
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
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if isSuperAdmin}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Asociar rubro presupuestal SENNOVA</LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
