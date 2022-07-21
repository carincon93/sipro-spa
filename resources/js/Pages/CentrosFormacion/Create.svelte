<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Shared/Input'
    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import Select from '@/Shared/Select'

    export let errors
    export let regionales
    export let subdirectores
    export let dinamizadoresSennova

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
                    <Select id="regional_id" items={regionales} bind:selectedValue={$form.regional_id} error={errors.regional_id} autocomplete="off" placeholder="Seleccione una regional" required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="subdirector_id" value="Subdirector" />
                    <Select id="subdirector_id" items={subdirectores} bind:selectedValue={$form.subdirector_id} error={errors.subdirector_id} autocomplete="off" placeholder="Seleccione una subdirector" required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="dinamizador_sennova_id" value="Dinamizador SENNOVA" />
                    <Select id="dinamizador_sennova_id" items={dinamizadoresSennova} bind:selectedValue={$form.dinamizador_sennova_id} error={errors.dinamizador_sennova_id} autocomplete="off" placeholder="Seleccione una dinamizador SENNOVA" required />
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
