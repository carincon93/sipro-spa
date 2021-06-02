<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Components/Input'
    import Label from '@/Components/Label'
    import LoadingButton from '@/Components/LoadingButton'
    import Select from '@/Components/Select'

    export let errors
    export let convocatoria
    export let proyecto
    export let entidadAliada
    export let tiposDocumento

    $: $title = 'Crear miembro de entidad aliada'

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
        email: '',
        tipo_documento: '',
        numero_documento: '',
        numero_celular: '',
    })

    function submit() {
        if (isSuperAdmin || checkRole(74)) {
            $form.post(route('convocatorias.proyectos.entidades-aliadas.miembros-entidad-aliada.store', [convocatoria.id, proyecto.id, entidadAliada.id]), {
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
                    {#if isSuperAdmin || checkRole(74)}
                        <a use:inertia href={route('convocatorias.proyectos.entidades-aliadas.miembros-entidad-aliada.index', [convocatoria.id, proyecto.id, entidadAliada.id])} class="text-indigo-400 hover:text-indigo-600">Miembros de la entidad aliada</a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    Crear
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={isSuperAdmin || checkRole(74) ? undefined : true}>
                <div class="mt-4">
                    <Label required class="mb-4" labelFor="nombre" value="Nombre completo" />
                    <Input id="nombre" type="text" class="mt-1 block w-full" bind:value={$form.nombre} error={errors.nombre} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="email" value="Correo electrónico" />
                    <Input id="email" type="email" class="mt-1 block w-full" bind:value={$form.email} error={errors.email} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="tipo_documento" value="Tipo de documento" />
                    <Select id="tipo_documento" items={tiposDocumento} bind:selectedValue={$form.tipo_documento} error={errors.tipo_documento} autocomplete="off" placeholder="Seleccione un tipo de documento" required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="numero_documento" value="Número de documento" />
                    <Input id="numero_documento" type="number" min="0" class="mt-1 block w-full" bind:value={$form.numero_documento} error={errors.numero_documento} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="numero_celular" value="Número de celular" />
                    <Input id="numero_celular" type="number" min="0" class="mt-1 block w-full" bind:value={$form.numero_celular} error={errors.numero_celular} required />
                </div>
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if isSuperAdmin || checkRole(74)}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Crear miembro de la entidad aliada</LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
