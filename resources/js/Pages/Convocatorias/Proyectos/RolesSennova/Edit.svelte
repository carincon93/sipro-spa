<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Shared/Input'
    import Label from '@/Shared/Label'
    import Button from '@/Shared/Button'
    import LoadingButton from '@/Shared/LoadingButton'
    import Textarea from '@/Shared/Textarea'
    import Select from '@/Shared/Select'
    import Dialog from '@/Shared/Dialog'
    import RecomendacionEvaluador from '@/Shared/RecomendacionEvaluador'

    export let errors
    export let convocatoria
    export let proyecto
    export let rolSennova
    export let proyectoRolSennova
    export let convocatoriaRolesSennova

    let infoRolSennova

    $: $title = rolSennova.nombre

    /**
     * Validar si el usuario autenticado es SuperAdmin
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let dialogOpen = false

    let form = useForm({
        numero_meses: proyectoRolSennova.numero_meses,
        numero_roles: proyectoRolSennova.numero_roles,
        descripcion: proyectoRolSennova.descripcion,
        convocatoria_rol_sennova_id: proyectoRolSennova.convocatoria_rol_sennova_id,
    })

    function submit() {
        if (proyecto.allowed_to_update) {
            $form.put(route('convocatorias.proyectos.proyecto-rol-sennova.update', [convocatoria.id, proyecto.id, proyectoRolSennova.id]), {
                preserveScroll: true,
            })
        }
    }

    function destroy() {
        if (proyecto.allowed.to_update) {
            $form.delete(route('convocatorias.proyectos.proyecto-rol-sennova.destroy', [convocatoria.id, proyecto.id, proyectoRolSennova.id]))
        }
    }

    $: if ($form.convocatoria_rol_sennova_id) {
        if (proyecto.codigo_linea_programatica == 68) {
            if ($form.convocatoria_rol_sennova_id == 108) {
                $form.numero_meses = 6
            } else {
                $form.numero_meses = proyecto.max_meses_ejecucion
            }
        }
    }

    $: if (infoRolSennova?.perfil != null) {
        if (infoRolSennova?.value == proyectoRolSennova.convocatoria_rol_sennova_id) {
            $form.descripcion = proyectoRolSennova.descripcion
        } else {
            $form.descripcion = infoRolSennova.perfil
        }
    } else {
        $form.descripcion = proyectoRolSennova.descripcion
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1 class="overflow-ellipsis overflow-hidden w-breadcrumb-ellipsis whitespace-nowrap">
                    <a use:inertia href={route('convocatorias.proyectos.proyecto-rol-sennova.index', [convocatoria.id, proyecto.id])} class="text-violet-400 hover:text-violet-600"> Roles SENNOVA </a>
                    <span class="text-violet-400 font-medium">/</span>
                    {rolSennova.nombre}
                </h1>
            </div>
        </div>
    </header>

    <div class="grid grid-cols-3">
        <div>
            <h1 class="font-black text-4xl sticky top-0 uppercase">Roles SENNOVA</h1>
        </div>
        <div class="col-span-2">
            {#if isSuperAdmin || proyecto.mostrar_recomendaciones}
                <RecomendacionEvaluador class="w-1/3">
                    {#each proyectoRolSennova.proyecto_roles_evaluaciones as evaluacionRol, i}
                        <div class="bg-zinc-900 p-4 rounded shadow text-white my-2">
                            <p class="text-xs">Evaluador COD-{evaluacionRol.evaluacion.id}:</p>
                            {#if evaluacionRol.correcto == false && evaluacionRol.evaluacion.habilitado}
                                <p class="whitespace-pre-line">{evaluacionRol.comentario ? evaluacionRol.comentario : 'Sin recomendación'}</p>
                            {:else}
                                Aprobado
                            {/if}
                        </div>
                    {/each}
                </RecomendacionEvaluador>
            {/if}
            <form on:submit|preventDefault={submit} class="bg-white rounded shadow">
                <fieldset class="p-8" disabled={proyecto.allowed_to_update ? undefined : true}>
                    <div class="mt-4">
                        <Label required class="mb-4" labelFor="convocatoria_rol_sennova_id" value="Rol SENNOVA" />
                        <Select id="convocatoria_rol_sennova_id" items={convocatoriaRolesSennova} bind:selectedValue={$form.convocatoria_rol_sennova_id} error={errors.convocatoria_rol_sennova_id} autocomplete="off" placeholder="Busque por el nombre del rol" required />
                    </div>

                    <div class="mt-4">
                        {#if infoRolSennova?.perfil}
                            <Textarea disabled={proyecto.codigo_linea_programatica != 68} label="Descripción" maxlength="40000" id="descripcion" error={errors.descripcion} bind:value={$form.descripcion} required />
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
                <div class="shadow-inner bg-violet-200 border-violet-400 bottom-0 flex items-center justify-between mt-14 px-8 py-4 sticky">
                    <small class="flex items-center text-violet-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {proyectoRolSennova.updated_at}
                    </small>
                    {#if proyecto.allowed.to_update}
                        <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={() => (dialogOpen = true)}> Eliminar rol SENNOVA </button>
                        <LoadingButton loading={$form.processing} class="ml-auto" type="submit">Guardar</LoadingButton>
                    {:else}
                        <span class="inline-block ml-1.5"> El proyecto no se puede modificar </span>
                    {/if}
                </div>
            </form>
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
                <Button on:click={() => (dialogOpen = false)} variant={null}>Cancelar</Button>
                <Button variant="raised" on:click={destroy}>Confirmar</Button>
            </div>
        </div>
    </Dialog>
</AuthenticatedLayout>
