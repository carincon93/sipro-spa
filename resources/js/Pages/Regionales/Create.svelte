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

    $: $title = 'Crear regionales'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0

    let canIndexRegionales = authUser.can.find((element) => element == 'regionales.index') == 'regionales.index'

    let canShowRegionales = authUser.can.find((element) => element == 'regionales.show') == 'regionales.show'

    let canCreateRegionales = authUser.can.find((element) => element == 'regionales.create') == 'regionales.create'

    let canEditRegionales = authUser.can.find((element) => element == 'regionales.edit') == 'regionales.edit'

    let canDestroyRegionales = authUser.can.find((element) => element == 'regionales.destroy') == 'regionales.destroy'

    let sending = false
    let form = useForm({
        nombre: '',
        codigo: '',
        region_id: null,
        director_regional_id: null,
    })

    function submit() {
        if (canCreateRegionales || isSuperAdmin) {
            $form.post(route('regionales.store'), {
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
                    {#if canIndexRegionales || canCreateRegionales || isSuperAdmin}
                        <a use:inertia href={route('regionales.index')} class="text-indigo-400 hover:text-indigo-600"> Regionales </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    Crear
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={canCreateRegionales || isSuperAdmin ? undefined : true}>
                <div class="mt-4">
                    <Label required class="mb-4" labelFor="nombre" value="Nombre" />
                    <Input id="nombre" type="text" class="mt-1 block w-full" bind:value={$form.nombre} error={errors.nombre} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="codigo" value="Código" />
                    <Input id="codigo" type="number" min="0" max="999" class="mt-1 block w-full" bind:value={$form.codigo} error={errors.codigo} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="region_id" value="Región" />
                    <DynamicList id="region_id" bind:value={$form.region_id} routeWebApi={route('web-api.regiones')} placeholder="Busque por el nombre de la región" message={errors.region_id} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="director_regional_id" value="Director(a) Regional" />
                    <DynamicList id="director_regional_id" bind:value={$form.director_regional_id} routeWebApi={route('web-api.directores-regional')} placeholder="Busque por el nombre del director" message={errors.director_regional_id} required />
                </div>
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if canCreateRegionales || isSuperAdmin}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Crear regional</LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
