<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Label from '@/Shared/Label'
    import Button from '@/Shared/Button'
    import LoadingButton from '@/Shared/LoadingButton'
    import Checkbox from '@smui/checkbox'
    import FormField from '@smui/form-field'
    import Textarea from '@/Shared/Textarea'
    import InputError from '@/Shared/InputError'
    import Dialog from '@/Shared/Dialog'

    export let errors
    export let convocatoria
    export let proyecto
    export let actividad
    export let productos
    export let proyectoPresupuesto
    export let productosRelacionados
    export let proyectoPresupuestoRelacionado

    $: $title = actividad ? actividad.nombre : null

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let dialogOpen = false
    let sending = false
    let form = useForm({
        descripcion: actividad.descripcion,
        fecha_inicio: actividad.fecha_inicio,
        fecha_finalizacion: actividad.fecha_finalizacion,
        producto_id: productosRelacionados,
        proyecto_presupuesto_id: proyectoPresupuestoRelacionado,
    })

    function submit() {
        if (isSuperAdmin || checkPermission(authUser, [3, 4, 6, 7, 9, 10])) {
            $form.put(route('convocatorias.proyectos.actividades.update', [convocatoria.id, proyecto.id, actividad.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    function destroy() {
        if (isSuperAdmin || checkPermission(authUser, [4, 7, 10])) {
            $form.delete(route('convocatorias.proyectos.actividades.destroy', [convocatoria.id, proyecto.id, actividad.id]))
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    {#if isSuperAdmin || checkPermission(authUser, [3, 4, 6, 7, 9, 10])}
                        <a use:inertia href={route('convocatorias.proyectos.actividades.index', [convocatoria.id, proyecto.id])} class="text-indigo-400 hover:text-indigo-600"> Actividades </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    {actividad.descripcion}
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={isSuperAdmin || checkPermission(authUser, [3, 4, 6, 7, 9, 10]) ? undefined : true}>
                <div class="mt-4">
                    <p class="text-center">Fecha de ejecución</p>
                    <div class="mt-4 flex items-start justify-around">
                        <div class="mt-4 flex {errors.fecha_inicio ? '' : 'items-center'}">
                            <Label required labelFor="fecha_inicio" class={errors.fecha_inicio ? 'top-3.5 relative' : ''} value="Del" />
                            <div class="ml-4">
                                <input id="fecha_inicio" type="date" class="mt-1 block w-full p-4" min={convocatoria.min_fecha_inicio_proyectos} max={convocatoria.max_fecha_finalizacion_proyectos} bind:value={$form.fecha_inicio} required />
                            </div>
                        </div>
                        <div class="mt-4 flex {errors.fecha_finalizacion ? '' : 'items-center'}">
                            <Label required labelFor="fecha_finalizacion" class={errors.fecha_finalizacion ? 'top-3.5 relative' : ''} value="hasta" />
                            <div class="ml-4">
                                <input id="fecha_finalizacion" type="date" class="mt-1 block w-full p-4" min={convocatoria.min_fecha_inicio_proyectos} max={convocatoria.max_fecha_finalizacion_proyectos} bind:value={$form.fecha_finalizacion} required />
                            </div>
                        </div>
                    </div>
                    {#if errors.fecha_inicio || errors.fecha_finalizacion}
                        <InputError message={errors.fecha_inicio || errors.fecha_finalizacion} />
                    {/if}
                </div>

                <div class="mt-20">
                    <Textarea label="Descripción" maxlength="40000" id="descripcion" error={errors.descripcion} bind:value={$form.descripcion} required />
                </div>

                <h6 class="mt-20 mb-12 text-2xl">Productos</h6>
                <div class="bg-white rounded shadow overflow-hidden">
                    <div class="p-4">
                        <Label required class="mb-4" labelFor="producto_id" value="Relacione algún producto" />
                        <InputError message={errors.producto_id} />
                    </div>
                    <div class="grid grid-cols-2">
                        {#each productos as { id, nombre }, i}
                            <FormField>
                                <Checkbox bind:group={$form.producto_id} value={id} />
                                <span slot="label">{nombre}</span>
                            </FormField>
                        {/each}
                        {#if productos.length == 0}
                            <p class="p-4">Sin información registrada</p>
                        {/if}
                    </div>
                </div>

                <h6 class="mt-20 mb-12 text-2xl">Rubros presupuestales</h6>
                <div class="bg-white rounded shadow overflow-hidden">
                    <div class="p-4">
                        <Label required class="mb-4" labelFor="proyecto_presupuesto_id" value="Relacione algún rubro" />
                        <InputError message={errors.proyecto_presupuesto_id} />
                    </div>
                    <div class="grid grid-cols-2">
                        {#each proyectoPresupuesto as presupuesto, i}
                            <FormField class="border-b border-l">
                                <Checkbox bind:group={$form.proyecto_presupuesto_id} value={presupuesto.id} />
                                <span slot="label">
                                    <div class="mb-8 mt-4">
                                        <small class="block">Concepto interno SENA</small>
                                        {presupuesto.convocatoria_presupuesto?.presupuesto_sennova?.segundo_grupo_presupuestal.nombre}
                                    </div>
                                    <div class="mb-8">
                                        <small class="block">Rubro</small>
                                        {presupuesto.convocatoria_presupuesto?.presupuesto_sennova?.tercer_grupo_presupuestal.nombre}
                                    </div>
                                    <div class="mb-8">
                                        <small class="block">Uso presupuestal</small>
                                        {presupuesto.convocatoria_presupuesto?.presupuesto_sennova?.uso_presupuestal.descripcion}
                                    </div>
                                    <div class="mb-8">
                                        <small class="block">Descripción</small>
                                        {presupuesto.descripcion}
                                    </div>
                                </span>
                            </FormField>
                        {/each}
                        {#if proyectoPresupuesto.length == 0}
                            <p class="p-4">Sin información registrada</p>
                        {/if}
                    </div>
                </div>
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if isSuperAdmin || checkPermission(authUser, [4, 7, 10])}
                    <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={(event) => (dialogOpen = true)}> Eliminar actividad </button>
                {/if}
                {#if isSuperAdmin || checkPermission(authUser, [3, 4, 6, 7, 9, 10])}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Editar actividad</LoadingButton>
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
