<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Components/Input'
    import Label from '@/Components/Label'
    import LoadingButton from '@/Components/LoadingButton'
    import DynamicList from '@/Dropdowns/DynamicList'

    export let errors

    $: $title = $_('Create') + ' ' + $_('Research teams.singular').toLowerCase()

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0

    let canIndexSemillerosInvestigacion = authUser.can.find((element) => element == 'semilleros-investigacion.index') == 'semilleros-investigacion.index'

    let canShowSemillerosInvestigacion = authUser.can.find((element) => element == 'semilleros-investigacion.show') == 'semilleros-investigacion.show'

    let canCreateSemillerosInvestigacion = authUser.can.find((element) => element == 'semilleros-investigacion.create') == 'semilleros-investigacion.create'

    let canEditSemillerosInvestigacion = authUser.can.find((element) => element == 'semilleros-investigacion.edit') == 'semilleros-investigacion.edit'

    let canDestroySemillerosInvestigacion = authUser.can.find((element) => element == 'semilleros-investigacion.destroy') == 'semilleros-investigacion.destroy'

    let sending = false
    let form = useForm({
        nombre: '',
        linea_investigacion_id: null,
    })

    function submit() {
        if (canCreateSemillerosInvestigacion || isSuperAdmin) {
            $form.post(route('semilleros-investigacion.store'), {
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
                    {#if canIndexSemillerosInvestigacion || canCreateSemillerosInvestigacion || isSuperAdmin}
                        <a use:inertia href={route('semilleros-investigacion.index')} class="text-indigo-400 hover:text-indigo-600"> Semilleros de investigación </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    Crear
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={canCreateSemillerosInvestigacion || isSuperAdmin ? undefined : true}>
                <div class="mt-4">
                    <Label required class="mb-4" labelFor="nombre" value="Nombre" />
                    <Input id="nombre" type="text" class="mt-1 block w-full" bind:value={$form.nombre} error={errors.nombre} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="linea_investigacion_id" value="Línea de investigación" />
                    <DynamicList id="linea_investigacion_id" bind:value={$form.linea_investigacion_id} routeWebApi={route('web-api.lineas-investigacion')} placeholder="Busque por el nombre de la línea de investigación, centro de formación, grupo de investigación o regional" message={errors.linea_investigacion_id} required />
                </div>
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if canCreateSemillerosInvestigacion || isSuperAdmin}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Crear semillero de investigación</LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
