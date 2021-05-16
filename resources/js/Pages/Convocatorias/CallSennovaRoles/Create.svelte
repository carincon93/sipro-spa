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
    export let rolesSennova

    $: $title = 'Crear rol SENNOVA de convocatoria'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    let canIndexConvocatoriaRolSennova = authUser.can.find((element) => element == 'convocatoria-rol-sennova.index') == 'convocatoria-rol-sennova.index'
    let canShowConvocatoriaRolSennova = authUser.can.find((element) => element == 'convocatoria-rol-sennova.show') == 'convocatoria-rol-sennova.show'
    let canCreateConvocatoriaRolSennova = authUser.can.find((element) => element == 'convocatoria-rol-sennova.create') == 'convocatoria-rol-sennova.create'
    let canEditConvocatoriaRolSennova = authUser.can.find((element) => element == 'convocatoria-rol-sennova.edit') == 'convocatoria-rol-sennova.edit'
    let canDestroyConvocatoriaRolSennova = authUser.can.find((element) => element == 'convocatoria-rol-sennova.destroy') == 'convocatoria-rol-sennova.destroy'

    let sending = false
    let form = useForm({
        asignacion_mensual: '',
        numero_meses: '',
        numero_roles: '',
        rol_sennova: '',
    })

    function submit() {
        if (canCreateConvocatoriaRolSennova || isSuperAdmin) {
            $form.post(route('convocatorias.convocatoria-rol-sennova.store', convocatoria.id), {
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
                        <a use:inertia href={route('convocatorias.convocatoria-rol-sennova.index', convocatoria.id)} class="text-indigo-400 hover:text-indigo-600"> Roles SENNOVA de convocatoria </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    Crear
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={canCreateConvocatoriaRolSennova || isSuperAdmin ? undefined : true}>
                <div class="mt-4">
                    <Label required class="mb-4" labelFor="rol_sennova" value="Rol SENNOVA" />
                    <Select id="rol_sennova" items={rolesSennova} bind:selectedValue={$form.rol_sennova} error={errors.rol_sennova} autocomplete="off" placeholder="Seleccione un rol SENNOVA" required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="asignacion_mensual" value="Asignación mensual" />
                    <Input id="asignacion_mensual" type="number" min="0" class="mt-1 block w-full" bind:value={$form.asignacion_mensual} error={errors.asignacion_mensual} required />
                </div>

                <div class="mt-4">
                    <Label class="mb-4" labelFor="numero_meses" value="Número de meses que requiere el apoyo" />
                    <Input id="numero_meses" type="number" min="0" class="mt-1 block w-full" bind:value={$form.numero_meses} error={errors.numero_meses} />
                </div>

                <div class="mt-4">
                    <Label class="mb-4" labelFor="numero_roles" value="Número de personas requeridas" />
                    <Input id="numero_roles" type="number" min="0" class="mt-1 block w-full" bind:value={$form.numero_roles} error={errors.numero_roles} />
                </div>
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if isSuperAdmin}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Crear rol SENNOVA convocatoria</LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
