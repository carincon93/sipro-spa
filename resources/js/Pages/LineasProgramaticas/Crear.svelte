<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Components/Input'
    import Label from '@/Components/Label'
    import LoadingButton from '@/Components/LoadingButton'
    import Select from '@/Components/Select'
    import Textarea from '@/Components/Textarea'

    export let errors
    export let categoriasProyectos

    $: $title = 'Líneas programáticas'

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

    let sending = false
    let form = useForm({
        nombre: '',
        descripcion: '',
        codigo: '',
        categoria_proyecto: null,
    })

    function submit() {
        if (canCreateLineasProgramaticas || isSuperAdmin) {
            $form.post(route('lineas-programaticas.store'), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
            })
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
                    {#if canIndexLineasProgramaticas || canCreateLineasProgramaticas || isSuperAdmin}
                        <a
                            use:inertia
                            href={route('lineas-programaticas.index')}
                            class="text-indigo-400 hover:text-indigo-600"
                        >
                            Líneas programáticas
                        </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    Crear
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset
                class="p-8"
                disabled={canCreateLineasProgramaticas || isSuperAdmin
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
                {#if canCreateLineasProgramaticas || isSuperAdmin}
                    <LoadingButton
                        loading={sending}
                        class="btn-indigo ml-auto"
                        type="submit"
                    >
                        Crear línea programática
                    </LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
