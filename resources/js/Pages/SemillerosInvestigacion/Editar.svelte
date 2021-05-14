<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import Dialog from '@/Components/Dialog'

    import Input from '@/Components/Input'
    import Label from '@/Components/Label'
    import Button from '@/Components/Button'
    import LoadingButton from '@/Components/LoadingButton'
    import DynamicList from '@/Dropdowns/DynamicList'

    export let errors
    export let semilleroInvestigacion

    $: $title = semilleroInvestigacion ? semilleroInvestigacion.nombre : null

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    // prettier-ignore
    let canIndexSemillerosInvestigacion = authUser.can.find((element) => element == 'semilleros-investigacion.index') == 'semilleros-investigacion.index'
    // prettier-ignore
    let canShowSemillerosInvestigacion = authUser.can.find((element) => element == 'semilleros-investigacion.show') == 'semilleros-investigacion.show'
    // prettier-ignore
    let canCreateSemillerosInvestigacion = authUser.can.find((element) => element == 'semilleros-investigacion.create') == 'semilleros-investigacion.create'
    // prettier-ignore
    let canEditSemillerosInvestigacion = authUser.can.find((element) => element == 'semilleros-investigacion.edit') == 'semilleros-investigacion.edit'
    // prettier-ignore
    let canDestroySemillerosInvestigacion = authUser.can.find((element) => element == 'semilleros-investigacion.destroy') == 'semilleros-investigacion.destroy'

    let dialog_open = false
    let sending = false
    let form = useForm({
        nombre: semilleroInvestigacion.nombre,
        linea_investigacion_id: semilleroInvestigacion.linea_investigacion_id,
    })

    function submit() {
        if (canEditSemillerosInvestigacion || isSuperAdmin) {
            $form.put(
                route(
                    'semilleros-investigacion.update',
                    semilleroInvestigacion.id,
                ),
                {
                    onStart: () => (sending = true),
                    onFinish: () => (sending = false),
                    preserveScroll: true,
                },
            )
        }
    }

    function destroy() {
        if (canDestroySemillerosInvestigacion || isSuperAdmin) {
            $form.delete(
                route(
                    'semilleros-investigacion.destroy',
                    semilleroInvestigacion.id,
                ),
            )
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div
            class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6"
        >
            <div>
                <h1>
                    {#if canIndexSemillerosInvestigacion || canShowSemillerosInvestigacion || canEditSemillerosInvestigacion || canDestroySemillerosInvestigacion || isSuperAdmin}
                        <a
                            use:inertia
                            href={route('semilleros-investigacion.index')}
                            class="text-indigo-400 hover:text-indigo-600"
                        >
                            Semilleros de investigación
                        </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    {semilleroInvestigacion.nombre}
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset
                class="p-8"
                disabled={canEditSemillerosInvestigacion || isSuperAdmin
                    ? undefined
                    : true}
            >
                <div class="mt-4">
                    <Label
                        required
                        class="mb-4"
                        labelFor="nombre"
                        value="Nombre"
                    />
                    <Input
                        id="nombre"
                        type="text"
                        class="mt-1 block w-full"
                        bind:value={$form.nombre}
                        error={errors.nombre}
                        required
                    />
                </div>

                <div class="mt-4">
                    <Label
                        required
                        class="mb-4"
                        labelFor="linea_investigacion_id"
                        value="Línea de investigación"
                    />
                    <DynamicList
                        id="linea_investigacion_id"
                        bind:value={$form.linea_investigacion_id}
                        routeWebApi={route('web-api.lineas-investigacion')}
                        placeholder="Busque por el nombre de la línea de investigación, centro de formación, grupo de investigación o regional"
                        message={errors.linea_investigacion_id}
                        required
                    />
                </div>
            </fieldset>
            <div
                class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0"
            >
                {#if canDestroySemillerosInvestigacion || isSuperAdmin}
                    <button
                        class="text-red-600 hover:underline text-left"
                        tabindex="-1"
                        type="button"
                        on:click={(event) => (dialog_open = true)}
                    >
                        Eliminar semillero de investigación
                    </button>
                {/if}
                {#if canEditSemillerosInvestigacion || isSuperAdmin}
                    <LoadingButton
                        loading={sending}
                        class="btn-indigo ml-auto"
                        type="submit"
                    >
                        Editar semillero de investigación
                    </LoadingButton>
                {/if}
            </div>
        </form>
    </div>
    <Dialog bind:open={dialog_open}>
        <div slot="title" class="flex items-center">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6 mr-2 text-red-500"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                />
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
                <Button
                    on:click={(event) => (dialog_open = false)}
                    variant={null}>Cancelar</Button
                >
                <Button variant="raised" on:click={destroy}>Confirmar</Button>
            </div>
        </div>
    </Dialog>
</AuthenticatedLayout>
