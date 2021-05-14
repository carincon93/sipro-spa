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
    import Textarea from '@/Components/Textarea'
    import Dialog from '@/Components/Dialog'

    export let errors
    export let lineaProgramatica
    export let categoriasProyectos

    $: $title = lineaProgramatica ? lineaProgramatica.nombre : null

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    // prettier-ignore
    let canIndexLineasProgramaticas = authUser.can.find((element) => element == 'lineas-programaticas.index') == 'lineas-programaticas.index'
    // prettier-ignore
    let canShowLineasProgramaticas = authUser.can.find((element) => element == 'lineas-programaticas.show') == 'lineas-programaticas.show'
    // prettier-ignore
    let canCreateLineasProgramaticas = authUser.can.find((element) => element == 'lineas-programaticas.create') == 'lineas-programaticas.create'
    // prettier-ignore
    let canEditLineasProgramaticas = authUser.can.find((element) => element == 'lineas-programaticas.edit') == 'lineas-programaticas.edit'
    // prettier-ignore
    let canDestroyLineasProgramaticas = authUser.can.find((element) => element == 'lineas-programaticas.destroy') == 'lineas-programaticas.destroy'

    let dialog_open = false
    let sending = false
    let form = useForm({
        nombre: lineaProgramatica.nombre,
        codigo: lineaProgramatica.codigo,
        descripcion: lineaProgramatica.descripcion,
        categoria_proyecto: {
            value: lineaProgramatica.categoria_proyecto,
            label: categoriasProyectos.find(
                (item) => item.value == lineaProgramatica.categoria_proyecto,
            )?.label,
        },
    })

    function submit() {
        if (canEditLineasProgramaticas || isSuperAdmin) {
            $form.put(
                route('lineas-programaticas.update', lineaProgramatica.id),
                {
                    onStart: () => (sending = true),
                    onFinish: () => (sending = false),
                    preserveScroll: true,
                },
            )
        }
    }

    function destroy() {
        if (canDestroyLineasProgramaticas || isSuperAdmin) {
            $form.delete(
                route('lineas-programaticas.destroy', lineaProgramatica.id),
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
                    {#if canIndexLineasProgramaticas || canShowLineasProgramaticas || canEditLineasProgramaticas || canDestroyLineasProgramaticas || isSuperAdmin}
                        <a
                            use:inertia
                            href={route('lineas-programaticas.index')}
                            class="text-indigo-400 hover:text-indigo-600"
                        >
                            Líneas programáticas
                        </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    {lineaProgramatica.nombre}
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset
                class="p-8"
                disabled={canEditLineasProgramaticas || isSuperAdmin
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
                        labelFor="codigo"
                        value="Código"
                    />
                    <Input
                        id="codigo"
                        type="number"
                        min="0"
                        max="999"
                        class="mt-1 block w-full"
                        bind:value={$form.codigo}
                        error={errors.codigo}
                        required
                    />
                </div>

                <div class="mt-4">
                    <Label
                        required
                        class="mb-4"
                        labelFor="categoria_proyecto"
                        value="Categoría"
                    />
                    <Select
                        id="categoria_proyecto"
                        items={categoriasProyectos}
                        bind:selectedValue={$form.categoria_proyecto}
                        error={errors.categoria_proyecto}
                        autocomplete="off"
                        placeholder="Seleccione una categoría"
                        required
                    />
                </div>

                <div class="mt-4">
                    <Label
                        required
                        class="mb-4"
                        labelFor="descripcion"
                        value="Descripción"
                    />
                    <Textarea
                        rows="4"
                        id="descripcion"
                        error={errors.descripcion}
                        bind:value={$form.descripcion}
                        required
                    />
                </div>
            </fieldset>
            <div
                class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0"
            >
                {#if canDestroyLineasProgramaticas || isSuperAdmin}
                    <button
                        class="text-red-600 hover:underline text-left"
                        tabindex="-1"
                        type="button"
                        on:click={(event) => (dialog_open = true)}
                    >
                        Eliminar línea programática
                    </button>
                {/if}
                {#if canEditLineasProgramaticas || isSuperAdmin}
                    <LoadingButton
                        loading={sending}
                        class="btn-indigo ml-auto"
                        type="submit"
                    >
                        Editar línea programática
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
