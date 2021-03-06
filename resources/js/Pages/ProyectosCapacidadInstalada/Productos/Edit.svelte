<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import LoadingButton from '@/Shared/LoadingButton'
    import Textarea from '@/Shared/Textarea'
    import Dialog from '@/Shared/Dialog'
    import Button from '@/Shared/Button'
    import Select from '@/Shared/Select'

    export let errors
    export let proyectoCapacidadInstalada
    export let producto
    export let resultados
    export let tipologiasMinciencias
    export let autorPrincipal

    $: $title = 'Editar producto'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let dialogOpen = false
    let sending = false
    let form = useForm({
        descripcion: producto.descripcion,
        resultado_id: {
            value: resultados.find((item) => item.value == producto.proyecto_capacidad_resultado_id)?.value,
            label: resultados.find((item) => item.value == producto.proyecto_capacidad_resultado_id)?.label,
        },
        tipologia_minciencias: {
            value: tipologiasMinciencias.find((item) => item.value == producto.tipologia_minciencias)?.value,
            label: tipologiasMinciencias.find((item) => item.value == producto.tipologia_minciencias)?.label,
        },
    })

    function submit() {
        if (isSuperAdmin || (proyectoCapacidadInstalada.estado_proyecto != 'Finalizado' && checkRole(authUser, [4]) && proyectoCapacidadInstalada.semillero_investigacion.linea_investigacion.grupo_investigacion.centro_formacion.id && authUser.centro_formacion_id) || (proyectoCapacidadInstalada.estado_proyecto != 'Finalizado' && checkRole(authUser, [6]) && authUser.id == autorPrincipal.id)) {
            $form.put(route('proyectos-capacidad-instalada.productos.update', [proyectoCapacidadInstalada.id, producto.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    function destroy() {
        if (isSuperAdmin || (proyectoCapacidadInstalada.estado_proyecto != 'Finalizado' && checkRole(authUser, [4]) && proyectoCapacidadInstalada.semillero_investigacion.linea_investigacion.grupo_investigacion.centro_formacion.id && authUser.centro_formacion_id) || (proyectoCapacidadInstalada.estado_proyecto != 'Finalizado' && checkRole(authUser, [6]) && authUser.id == autorPrincipal.id)) {
            $form.delete(route('proyectos-capacidad-instalada.productos.destroy', [proyectoCapacidadInstalada.id, producto.id]))
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    <a use:inertia href={route('proyectos-capacidad-instalada.index')} class="text-indigo-400 hover:text-indigo-600"> Proyectos de capacidad instalada </a>
                    <span class="text-indigo-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-capacidad-instalada.edit', proyectoCapacidadInstalada.id)} class="text-indigo-400 hover:text-indigo-600">Información básica</a>
                    <span class="text-indigo-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-capacidad-instalada.integrantes.index', proyectoCapacidadInstalada.id)} class="text-indigo-400 hover:text-indigo-600">Integrantes</a>
                    <span class="text-indigo-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-capacidad-instalada.objetivos-especificos.index', proyectoCapacidadInstalada.id)} class="text-indigo-400 hover:text-indigo-600">Objetivos específicos y resultados</a>
                    <span class="text-indigo-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-capacidad-instalada.productos.index', proyectoCapacidadInstalada.id)} class="text-indigo-400 hover:text-indigo-600 font-extrabold underline">Productos</a>
                    <span class="text-indigo-400 font-medium">/</span>
                    <a use:inertia href={route('proyectos-capacidad-instalada.finalizar', proyectoCapacidadInstalada.id)} class="text-indigo-400 hover:text-indigo-600">Finalizar</a>
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset
                class="p-8"
                disabled={isSuperAdmin ||
                (proyectoCapacidadInstalada.estado_proyecto != 'Finalizado' && checkRole(authUser, [4]) && proyectoCapacidadInstalada.semillero_investigacion.linea_investigacion.grupo_investigacion.centro_formacion.id && authUser.centro_formacion_id) ||
                (proyectoCapacidadInstalada.estado_proyecto != 'Finalizado' && checkRole(authUser, [6]) && authUser.id == autorPrincipal.id)
                    ? undefined
                    : true}
            >
                <div class="mt-8">
                    <Textarea label="Descripción" maxlength="255" id="descripcion" error={errors.descripcion} bind:value={$form.descripcion} required />
                </div>
                <div class="mt-4">
                    <Select id="resultado_id" items={resultados} bind:selectedValue={$form.resultado_id} error={errors.resultado_id} autocomplete="off" placeholder="Seleccione un resultado" required />
                </div>
                <div class="mt-4">
                    <Select id="tipologia_minciencias" items={tipologiasMinciencias} bind:selectedValue={$form.tipologia_minciencias} error={errors.tipologia_minciencias} autocomplete="off" placeholder="Seleccione una tipología" required />
                </div>
                <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                    {#if isSuperAdmin || (proyectoCapacidadInstalada.estado_proyecto != 'Finalizado' && checkRole( authUser, [4], ) && proyectoCapacidadInstalada.semillero_investigacion.linea_investigacion.grupo_investigacion.centro_formacion.id && authUser.centro_formacion_id) || (proyectoCapacidadInstalada.estado_proyecto != 'Finalizado' && checkRole( authUser, [6], ) && authUser.id == autorPrincipal.id)}
                        <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={(event) => (dialogOpen = true)}> Eliminar producto </button>
                    {/if}
                    {#if isSuperAdmin || (proyectoCapacidadInstalada.estado_proyecto != 'Finalizado' && checkRole( authUser, [4], ) && proyectoCapacidadInstalada.semillero_investigacion.linea_investigacion.grupo_investigacion.centro_formacion.id && authUser.centro_formacion_id) || (proyectoCapacidadInstalada.estado_proyecto != 'Finalizado' && checkRole( authUser, [6], ) && authUser.id == autorPrincipal.id)}
                        <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Editar producto</LoadingButton>
                    {/if}
                </div>
            </fieldset>
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
