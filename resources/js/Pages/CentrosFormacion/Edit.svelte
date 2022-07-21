<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Shared/Input'
    import Label from '@/Shared/Label'
    import Button from '@/Shared/Button'
    import LoadingButton from '@/Shared/LoadingButton'
    import Select from '@/Shared/Select'
    import Dialog from '@/Shared/Dialog'

    export let errors
    export let centroFormacion
    export let regionales
    export let subdirectores
    export let dinamizadoresSennova

    $: $title = centroFormacion ? centroFormacion.nombre : null

    /**
     * Validar si el usuario autenticado es SuperAdmin
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let dialogOpen = false
    let form = useForm({
        nombre: centroFormacion.nombre,
        codigo: centroFormacion.codigo,
        regional_id: centroFormacion.regional_id,
        subdirector_id: centroFormacion.subdirector_id,
        dinamizador_sennova_id: centroFormacion.dinamizador_sennova_id,
    })

    function submit() {
        if (isSuperAdmin) {
            $form.put(route('centros-formacion.update', centroFormacion.id), {
                preserveScroll: true,
            })
        }
    }

    function destroy() {
        if (isSuperAdmin) {
            $form.delete(route('centros-formacion.destroy', centroFormacion.id))
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1 class="overflow-ellipsis overflow-hidden w-breadcrumb-ellipsis whitespace-nowrap">
                    <a use:inertia href={route('centros-formacion.index')} class="text-violet-400 hover:text-violet-600"> Centros de formación </a>
                    <span class="text-violet-400 font-medium">/</span>
                    {centroFormacion.nombre}
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
                    <Label required class="mb-4" labelFor="regional" value="Regional" />
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
                    <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={() => (dialogOpen = true)}> Eliminar centro de formación </button>
                {/if}
                {#if isSuperAdmin}
                    <LoadingButton loading={$form.processing} class="ml-auto" type="submit">Editar centro de formación</LoadingButton>
                {/if}
            </div>
        </form>
    </div>

    <Dialog bind:open={dialogOpen}>
        <div slot="title" class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            Eliminar recurso
        </div>
        <div slot="content">
            <p>
                ¿Está seguro(a) que desea eliminar este recurso?
                <br />
                Todos los datos se eliminarán de forma permanente.
                <br />
                Está acción no se puede deshacer.
            </p>
        </div>
        <div slot="actions">
            <div class="p-4">
                <Button on:click={() => (dialogOpen = false)} variant={null}>Cancelar</Button>
                <Button variant="raised" on:click={destroy}>Confirmar</Button>
            </div>
        </div>
    </Dialog>
</AuthenticatedLayout>
