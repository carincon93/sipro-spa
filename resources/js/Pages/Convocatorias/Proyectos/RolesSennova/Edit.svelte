<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Shared/Input'
    import Label from '@/Shared/Label'
    import Button from '@/Shared/Button'
    import LoadingButton from '@/Shared/LoadingButton'
    import InfoMessage from '@/Shared/InfoMessage'
    import Textarea from '@/Shared/Textarea'
    import DynamicList from '@/Shared/Dropdowns/DynamicList'
    import Dialog from '@/Shared/Dialog'

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
    let isSuperAdmin = checkRole(authUser, [1])

    let dialogOpen = false
    let sending = false
    let form = useForm({
        numero_meses: proyectoRolSennova.numero_meses,
        numero_roles: proyectoRolSennova.numero_roles,
        descripcion: proyectoRolSennova.descripcion,
        convocatoria_rol_sennova_id: proyectoRolSennova.convocatoria_rol_sennova_id,
    })

    function submit() {
        if (isSuperAdmin || (checkPermission(authUser, [3, 4, 6, 7, 9, 10, 12, 13, 18, 19]) && proyecto.modificable == true)) {
            $form.put(route('convocatorias.proyectos.proyecto-rol-sennova.update', [convocatoria.id, proyecto.id, proyectoRolSennova.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    function destroy() {
        if (isSuperAdmin || (checkPermission(authUser, [4, 7, 10, 13, 19]) && proyecto.modificable == true)) {
            $form.delete(route('convocatorias.proyectos.proyecto-rol-sennova.destroy', [convocatoria.id, proyecto.id, proyectoRolSennova.id]))
        }
    }

    let diff_meses = proyecto.diff_meses
    $: if ($form.convocatoria_rol_sennova_id) {
        if (proyecto.codigo_linea_programatica == 68) {
            $form.descripcion = infoRolSennova?.perfil == null ? $form.descripcion : infoRolSennova?.perfil
            if ($form.convocatoria_rol_sennova_id == 108) {
                $form.numero_meses = 6
            } else {
                $form.numero_meses = proyecto.max_meses_ejecucion
            }
        }
    }

    $: if (infoRolSennova?.perfil != null) {
        $form.descripcion = infoRolSennova.perfil
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1 class="overflow-ellipsis overflow-hidden w-breadcrumb-ellipsis whitespace-nowrap">
                    {#if isSuperAdmin || checkPermission(authUser, [3, 4, 6, 7, 9, 10, 12, 13, 18, 19, 21, 14, 16, 15, 20])}
                        <a use:inertia href={route('convocatorias.proyectos.proyecto-rol-sennova.index', [convocatoria.id, proyecto.id])} class="text-indigo-400 hover:text-indigo-600"> Roles SENNOVA </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    {rolSennova.nombre}
                </h1>
            </div>
        </div>
    </header>

    <div class="flex">
        <div class="bg-white rounded shadow max-w-3xl">
            <form on:submit|preventDefault={submit}>
                <fieldset class="p-8" disabled={isSuperAdmin || (checkPermission(authUser, [3, 4, 6, 7, 9, 10, 12, 13, 18, 19]) && proyecto.modificable == true) ? undefined : true}>
                    <div class="mt-4">
                        <Label required class="mb-4" labelFor="convocatoria_rol_sennova_id" value="Rol SENNOVA" />
                        <DynamicList id="convocatoria_rol_sennova_id" bind:value={$form.convocatoria_rol_sennova_id} routeWebApi={route('web-api.convocatorias.roles-sennova', [convocatoria.id, proyecto.id, lineaProgramatica])} bind:recurso={infoRolSennova} message={errors.convocatoria_rol_sennova_id} placeholder="Busque por el nombre del rol" required />
                    </div>

                    <div class="mt-4">
                        {#if infoRolSennova?.perfil}
                            <Textarea disabled label="Descripción" maxlength="40000" id="descripcion" error={errors.descripcion} value={infoRolSennova.perfil} required />
                        {:else}
                            <Textarea label="Descripción" maxlength="40000" id="descripcion" error={errors.descripcion} bind:value={$form.descripcion} required />
                        {/if}
                    </div>

                    {#if proyecto.codigo_linea_programatica != 68}
                        <div class="mt-4">
                            <Input label="Número de meses que requiere el apoyo." id="numero_meses" type="number" input$min="1" input$step="0.1" input$max={proyecto.diff_meses < 6 ? 6 : proyecto.diff_meses} class="mt-1" error={errors.numero_meses} bind:value={$form.numero_meses} required />
                        </div>
                    {/if}

                    <div class="mt-4">
                        <Input label="Número de personas requeridas" id="numero_roles" type="number" input$min="1" class="mt-1" error={errors.numero_roles} bind:value={$form.numero_roles} required />
                    </div>
                </fieldset>
                <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                    {#if isSuperAdmin || (checkPermission(authUser, [4, 7, 10, 13, 19]) && proyecto.modificable == true)}
                        <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={(event) => (dialogOpen = true)}> Eliminar rol SENNOVA </button>
                    {/if}
                    {#if isSuperAdmin || (checkPermission(authUser, [3, 4, 6, 7, 9, 10, 12, 13, 18, 19]) && proyecto.modificable == true)}
                        <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Editar rol SENNOVA</LoadingButton>
                    {/if}
                </div>
            </form>
        </div>
        <div class="ml-1.5">
            {#if proyecto.en_subsanacion}
                {#each proyectoRolSennova.proyecto_roles_evaluaciones as evaluacionRol, i}
                    <div class="bg-gray-200 p-4 rounded border-orangered border mb-5">
                        <div class="flex text-orangered-900 font-black">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                            Recomendación del {i == 0 ? 'primer' : i == 1 ? 'segundo' : ''} evaluador:
                        </div>
                        {#if evaluacionRol.incorrecto && evaluacionRol.evaluacion.habilitado}
                            <p class="whitespace-pre-line">{evaluacionRol.comentario ? evaluacionRol.comentario : 'Sin recomendación'}</p>
                        {:else}
                            Aprobado
                        {/if}
                    </div>
                {/each}
            {/if}
        </div>
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
