<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Shared/Input'
    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import DynamicList from '@/Shared/Dropdowns/DynamicList'

    export let errors

    $: $title = 'Crear centro de formación'

    /**
     * Validar si el usuario autenticado es SuperAdmin
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let form = useForm({
        nombre: '',
        codigo: '',
        regional_id: null,
        subdirector_id: null,
        dinamizador_sennova_id: null,
    })

    function submit() {
        if (isSuperAdmin) {
            $form.post(route('centros-formacion.store'))
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    <a use:inertia href={route('centros-formacion.index')} class="text-violet-400 hover:text-violet-600"> Centros de formación </a>
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
                    <Input label="Nombre" id="nombre" type="text" class="mt-1" bind:value={$form.nombre} error={errors.nombre} required />
                </div>

                <div class="mt-4">
                    <Input label="Código" id="codigo" type="number" input$min="0" input$max="2147483647" class="mt-1" bind:value={$form.codigo} error={errors.codigo} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="regional_id" value="Regional" />
                    <DynamicList id="regional_id" bind:value={$form.regional_id} routeWebApi={route('web-api.regionales')} placeholder="Busque por el nombre de la regional" message={errors.regional_id} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="subdirector_id" value="Subdirector" />
                    <DynamicList id="subdirector_id" bind:value={$form.subdirector_id} routeWebApi={route('web-api.users', 'subdirector')} placeholder="Busque por el nombre de subdirector" message={errors.subdirector_id} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="dinamizador_sennova_id" value="Dinamizador SENNOVA" />
                    <DynamicList id="dinamizador_sennova_id" bind:value={$form.dinamizador_sennova_id} routeWebApi={route('web-api.users', 'dinamizador')} placeholder="Busque por el nombre de subdirector" message={errors.dinamizador_sennova_id} required />
                </div>
            </fieldset>
            <div class="shadow-inner bg-violet-200 border-violet-400 bottom-0 flex items-center justify-between mt-14 px-8 py-4 sticky">
                {#if isSuperAdmin}
                    <LoadingButton loading={$form.processing} class="ml-auto" type="submit">Crear centro de formación</LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
