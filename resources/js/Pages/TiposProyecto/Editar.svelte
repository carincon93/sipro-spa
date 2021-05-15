<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Components/Input'
    import Label from '@/Components/Label'
    import Button from '@/Components/Button'
    import LoadingButton from '@/Components/LoadingButton'
    import Select from '@/Components/Select'
    import Dialog from '@/Components/Dialog'

    export let errors
    export let tipoProyecto
    export let lineasProgramaticas

    $: $title = tipoProyecto ? tipoProyecto.nombre : null

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    // prettier-ignore
    let canIndexTiposProyecto = authUser.can.find((element) => element == 'tipos-proyecto.index') == 'tipos-proyecto.index'
    // prettier-ignore
    let canShowTiposProyecto = authUser.can.find((element) => element == 'tipos-proyecto.show') == 'tipos-proyecto.show'
    // prettier-ignore
    let canCreateTiposProyecto = authUser.can.find((element) => element == 'tipos-proyecto.create') == 'tipos-proyecto.create'
    // prettier-ignore
    let canEditTiposProyecto = authUser.can.find((element) => element == 'tipos-proyecto.edit') == 'tipos-proyecto.edit'
    // prettier-ignore
    let canDestroyTiposProyecto = authUser.can.find((element) => element == 'tipos-proyecto.destroy') == 'tipos-proyecto.destroy'

    let dialog_open = false
    let sending = false
    let form = useForm({
        nombre: tipoProyecto.nombre,
        linea_programatica_id: {
            value: tipoProyecto.linea_programatica_id,
            label: lineasProgramaticas.find(
                (item) => item.value == tipoProyecto.linea_programatica_id,
            )?.label,
        },
    })

    function submit() {
        if (canEditTiposProyecto || isSuperAdmin) {
            $form.put(route('tipos-proyecto.update', tipoProyecto.id), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    function destroy() {
        if (canDestroyTiposProyecto || isSuperAdmin) {
            $form.delete(route('tipos-proyecto.destroy', tipoProyecto.id))
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
                    {#if canIndexTiposProyecto || canShowTiposProyecto || canEditTiposProyecto || canDestroyTiposProyecto || isSuperAdmin}
                        <a
                            use:inertia
                            href={route('tipos-proyecto.index')}
                            class="text-indigo-400 hover:text-indigo-600"
                        >
                            Tipos de proyecto
                        </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    {tipoProyecto.nombre}
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset
                class="p-8"
                disabled={canEditTiposProyecto || isSuperAdmin
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
                        labelFor="linea_programatica_id"
                        value="Línea programática"
                    />
                    <Select
                        id="linea_programatica_id"
                        items={lineasProgramaticas}
                        bind:selectedValue={$form.linea_programatica_id}
                        error={errors.linea_programatica_id}
                        autocomplete="off"
                        placeholder="Seleccione una línea programática"
                        required
                    />
                </div>
            </fieldset>
            <div
                class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0"
            >
                {#if canDestroyTiposProyecto || isSuperAdmin}
                    <button
                        class="text-red-600 hover:underline text-left"
                        tabindex="-1"
                        type="button"
                        on:click={(event) => (dialog_open = true)}
                    >
                        Eliminar tipo de proyecto
                    </button>
                {/if}
                {#if canEditTiposProyecto || isSuperAdmin}
                    <LoadingButton
                        loading={sending}
                        class="btn-indigo ml-auto"
                        type="submit"
                    >
                        Editar tipo de proyecto
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
