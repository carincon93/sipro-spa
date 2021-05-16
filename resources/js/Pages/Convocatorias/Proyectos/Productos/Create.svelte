<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Components/Input'
    import Label from '@/Components/Label'
    import InputError from '@/Components/InputError'
    import LoadingButton from '@/Components/LoadingButton'
    import Select from '@/Components/Select'
    import DynamicList from '@/Dropdowns/DynamicList'
    import Textarea from '@/Components/Textarea'

    export let errors
    export let convocatoria
    export let proyecto
    export let resultados

    $: $title = 'Crear producto'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    let canIndexProductos = authUser.can.find((element) => element == 'productos.index') == 'productos.index'
    let canShowProductos = authUser.can.find((element) => element == 'productos.show') == 'productos.show'
    let canCreateProductos = authUser.can.find((element) => element == 'productos.create') == 'productos.create'
    let canEditProductos = authUser.can.find((element) => element == 'productos.edit') == 'productos.edit'
    let canDestroyProductos = authUser.can.find((element) => element == 'productos.destroy') == 'productos.destroy'

    let sending = false
    let form = useForm({
        nombre: '',
        resultado_id: null,
        subtipologia_minciencias_id: null,
        fecha_inicio: '',
        fecha_finalizacion: '',
    })

    function submit() {
        if (canCreateProductos || isSuperAdmin) {
            $form.post(route('convocatorias.proyectos.productos.store', [convocatoria.id, proyecto.id]), {
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
                    {#if canIndexProductos || canCreateProductos || isSuperAdmin}
                        <a use:inertia href={route('convocatorias.proyectos.productos.index', [convocatoria.id, proyecto.id])} class="text-indigo-400 hover:text-indigo-600"> Productos </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    Crear
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={canCreateProductos || isSuperAdmin ? undefined : true}>
                <div class="mt-8 mb-8">
                    <p class="text-center">Fecha de ejecución</p>
                    <div class="mt-4 flex items-start justify-around">
                        <div class="mt-4 flex">
                            <Label required labelFor="fecha_inicio" value="Del" />
                            <div class="ml-4">
                                <Input id="fecha_inicio" type="date" class="mt-1 block w-full" bind:value={$form.fecha_inicio} required />
                            </div>
                        </div>
                        <div class="mt-4 flex">
                            <Label required labelFor="fecha_finalizacion" value="hasta" />
                            <div class="ml-4">
                                <Input id="fecha_finalizacion" type="date" class="mt-1 block w-full" bind:value={$form.fecha_finalizacion} required />
                            </div>
                        </div>
                    </div>
                    {#if errors.fecha_inicio || errors.fecha_finalizacion}
                        <InputError message={errors.fecha_inicio || errors.fecha_finalizacion} />
                    {/if}
                </div>

                <hr />

                <div class="mt-8">
                    <Label required class="mb-4" labelFor="nombre" value="Nombre" />
                    <Textarea rows="4" id="nombre" error={errors.nombre} bind:value={$form.nombre} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="resultado_id" value="Resultado" />
                    <Select id="resultado_id" items={resultados} bind:selectedValue={$form.resultado_id} error={errors.resultado_id} autocomplete="off" placeholder="Seleccione un resultado" required />
                </div>

                {#if proyecto.idi}
                    <div class="mt-4">
                        <Label required class="mb-4" labelFor="subtipologia_minciencias_id" value="Subtipología Minciencias" />
                        <DynamicList id="subtipologia_minciencias_id" bind:value={$form.subtipologia_minciencias_id} routeWebApi={route('web-api.subtipologias-minciencias')} placeholder="Busque por el nombre de la subtipología Minciencias" message={errors.subtipologia_minciencias_id} required />
                    </div>
                {/if}
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if canCreateProductos || isSuperAdmin}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Crear producto</LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
