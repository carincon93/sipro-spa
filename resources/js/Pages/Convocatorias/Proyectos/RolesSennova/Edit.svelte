<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Components/Input'
    import Label from '@/Components/Label'
    import Button from '@/Components/Button'
    import LoadingButton from '@/Components/LoadingButton'
    import Textarea from '@/Components/Textarea'
    import DynamicList from '@/Dropdowns/DynamicList'
    import Dialog from '@/Components/Dialog'

    export let convocatoria
    export let proyecto
    export let lineaProgramatica
    export let rolSennova
    export let proyectoRolSennova
    export let errors

    let infoRolSennova

    $: $title = rolSennova.nombre

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

    let dialogOpen = false
    let sending = false
    let form = useForm({
        numero_meses: proyectoRolSennova.numero_meses,
        numero_roles: proyectoRolSennova.numero_roles,
        descripcion: proyectoRolSennova.descripcion,
        convocatoria_rol_sennova_id: proyectoRolSennova.convocatoria_rol_sennova_id,
    })

    function submit() {
        if (isSuperAdmin || checkRole(74)) {
            $form.put(route('convocatorias.proyectos.proyecto-rol-sennova.update', [convocatoria.id, proyecto.id, proyectoRolSennova.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    function destroy() {
        if (isSuperAdmin || checkRole(74)) {
            $form.delete(route('convocatorias.proyectos.proyecto-rol-sennova.destroy', [convocatoria.id, proyecto.id, proyectoRolSennova.id]))
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    {#if isSuperAdmin || checkRole(74)}
                        <a use:inertia href={route('convocatorias.proyectos.proyecto-rol-sennova.index', [convocatoria.id, proyecto.id])} class="text-indigo-400 hover:text-indigo-600"> Roles SENNOVA </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    {rolSennova.nombre}
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={isSuperAdmin || checkRole(74) ? undefined : true}>
                <div class="mt-4">
                    <Label required class="mb-4" labelFor="convocatoria_rol_sennova_id" value="Rol SENNOVA" />
                    <DynamicList id="convocatoria_rol_sennova_id" bind:value={$form.convocatoria_rol_sennova_id} routeWebApi={route('web-api.convocatorias.roles-sennova', [convocatoria.id, lineaProgramatica])} message={errors.convocatoria_rol_sennova_id} placeholder="Busque por el nombre del rol" required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="descripcion" value="Descripción" />
                    <Textarea rows="4" id="descripcion" error={errors.descripcion} bind:value={$form.descripcion} required />
                </div>

                {#if infoRolSennova?.experiencia}
                    <div class="mt-4">
                        <p class="block font-medium text-sm text-gray-700 ">
                            Experiencia (meses)
                            <span class="block border-gray-300 p-4 rounded-md shadow-sm">
                                {infoRolSennova.experiencia}
                            </span>
                        </p>
                    </div>
                {/if}

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="numero_meses" value="Número de meses que requiere el apoyo" />
                    <Input id="numero_meses" type="number" min="1" max={proyecto.diff_meses} class="mt-1 block w-full" error={errors.numero_meses} bind:value={$form.numero_meses} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="numero_roles" value="Número de personas requeridas" />
                    <Input id="numero_roles" type="number" min="1" class="mt-1 block w-full" error={errors.numero_roles} bind:value={$form.numero_roles} required />
                </div>
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if isSuperAdmin || checkRole(74)}
                    <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={(event) => (dialogOpen = true)}> Eliminar rol SENNOVA </button>
                {/if}
                {#if isSuperAdmin || checkRole(74)}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Editar rol SENNOVA</LoadingButton>
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
                <Button on:click={(event) => (dialogOpen = false)} variant={null}>Cancelar</Button>
                <Button variant="raised" on:click={destroy}>Confirmar</Button>
            </div>
        </div>
    </Dialog>
</AuthenticatedLayout>
