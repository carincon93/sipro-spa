<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Components/Input'
    import Label from '@/Components/Label'
    import LoadingButton from '@/Components/LoadingButton'
    import Select from '@/Components/Select'
    import DynamicList from '@/Dropdowns/DynamicList'

    export let errors
    export let categoriasMinciencias

    $: $title = 'Crear grupo de investigación'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(1)

    function checkRole(roleId) {
        return (
            authUser.roles.filter(function (role) {
                return role.id == roleId
            }).length > 0
        )
    }

    let sending = false
    let form = useForm({
        nombre: '',
        acronimo: '',
        email: '',
        enlace_gruplac: '',
        codigo_minciencias: '',
        categoria_minciencias: '',
        centro_formacion_id: null,
    })

    function submit() {
        if (isSuperAdmin) {
            $form.post(route('grupos-investigacion.store'), {
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
                    {#if isSuperAdmin}
                        <a use:inertia href={route('grupos-investigacion.index')} class="text-indigo-400 hover:text-indigo-600"> Grupos de investigación </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    Crear
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={isSuperAdmin ? undefined : true}>
                <div class="mt-4">
                    <Input label="Nombre" id="nombre" type="text" class="mt-1 block w-full" bind:value={$form.nombre} error={errors.nombre} required />
                </div>

                <div class="mt-4">
                    <Input label="Acrónimo" id="acronimo" type="text" class="mt-1 block w-full" bind:value={$form.acronimo} error={errors.acronimo} required />
                </div>

                <div class="mt-4">
                    <Input label="Correo electrónico" id="email" type="email" class="mt-1 block w-full" bind:value={$form.email} error={errors.email} required />
                </div>

                <div class="mt-4">
                    <Input label="Enlace GrupLAC" id="enlace_gruplac" type="url" class="mt-1 block w-full" bind:value={$form.enlace_gruplac} error={errors.enlace_gruplac} required />
                </div>

                <div class="mt-4">
                    <Input label="Código Minciencias" id="codigo_minciencias" type="text" class="mt-1 block w-full" bind:value={$form.codigo_minciencias} error={errors.codigo_minciencias} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="categoria_minciencias" value="Categoría Minciencias" />
                    <Select id="categoria_minciencias" items={categoriasMinciencias} bind:selectedValue={$form.categoria_minciencias} error={errors.categoria_minciencias} autocomplete="off" placeholder="Seleccione una categoría Minciencias" required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="centro_formacion_id" value="Centro de formación" />
                    <DynamicList id="centro_formacion_id" bind:value={$form.centro_formacion_id} routeWebApi={route('web-api.centros-formacion')} placeholder="Busque por el nombre del centro de formación" message={errors.centro_formacion_id} required />
                </div>
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if isSuperAdmin}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Crear grupo de investigación</LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
