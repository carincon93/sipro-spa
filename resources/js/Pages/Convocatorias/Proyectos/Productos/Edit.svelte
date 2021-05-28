<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Components/Input'
    import Label from '@/Components/Label'
    import InputError from '@/Components/InputError'
    import Button from '@/Components/Button'
    import LoadingButton from '@/Components/LoadingButton'
    import Select from '@/Components/Select'
    import DynamicList from '@/Dropdowns/DynamicList'
    import Textarea from '@/Components/Textarea'
    import Dialog from '@/Components/Dialog'
    import InfoMessage from '@/Components/InfoMessage'

    export let errors
    export let convocatoria
    export let proyecto
    export let producto
    export let resultados

    $: $title = producto ? producto.nombre : null

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0

    let dialogOpen = false
    let sending = false
    let form = useForm({
        nombre: producto.nombre,
        resultado_id: {
            value: producto.resultado_id,
            label: resultados.find((item) => item.value == producto.resultado_id)?.label,
        },
        fecha_inicio: producto.fecha_inicio,
        fecha_finalizacion: producto.fecha_finalizacion,
        indicador: producto.indicador,
        trl: producto.idi_producto?.trl,
        subtipologia_minciencias_id: producto.idi_producto?.subtipologia_minciencias_id,
        valor_proyectado: producto.ta_tp_producto?.valor_proyectado,
        idi: proyecto.idi ? true : false,
    })

    function submit() {
        if (isSuperAdmin) {
            $form.put(route('convocatorias.proyectos.productos.update', [convocatoria.id, proyecto.id, producto.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    function destroy() {
        if (isSuperAdmin) {
            $form.delete(route('convocatorias.proyectos.productos.destroy', [convocatoria.id, proyecto.id, producto.id]))
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    {#if isSuperAdmin}
                        <a use:inertia href={route('convocatorias.proyectos.productos.index', [convocatoria.id, proyecto.id])} class="text-indigo-400 hover:text-indigo-600"> Productos </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    {producto.nombre}
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={isSuperAdmin ? undefined : true}>
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

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="nombre" value="Nombre" />
                    <Textarea rows="4" id="nombre" error={errors.nombre} bind:value={$form.nombre} required />
                </div>
                <div class="mt-4">
                    <Label required class="mb-4" labelFor="resultado_id" value="Resultado" />
                    <Select id="resultado_id" items={resultados} bind:selectedValue={$form.resultado_id} error={errors.resultado_id} autocomplete="off" placeholder="Seleccione un resultado" required />
                </div>
                <div class="mt-4">
                    <Label required labelFor="indicador" value="Indicador" />
                    <InfoMessage message="Especifique los medios de verificación para validar los logros del proyecto." />

                    <Textarea rows="4" id="indicador" error={errors.indicador} bind:value={$form.indicador} required />
                </div>

                {#if producto.idi_producto}
                    <div class="mt-4">
                        <Label required class="mb-4" labelFor="subtipologia_minciencias_id" value="Subtipología Minciencias" />
                        <DynamicList id="subtipologia_minciencias_id" bind:value={$form.subtipologia_minciencias_id} routeWebApi={route('web-api.subtipologias-minciencias')} placeholder="Busque por el nombre de la subtipología Minciencias" message={errors.subtipologia_minciencias_id} required />
                    </div>

                    <div class="mt-4">
                        <Label required labelFor="trl" value="TRL" />
                        <Input id="trl" type="number" max="9" min="1" class="block w-full" error={errors.trl} bind:value={$form.trl} required />
                    </div>
                {:else if proyecto.ta_tp}
                    <div class="mt-4">
                        <Label required labelFor="valor_proyectado" value="Valor proyectado" />
                        <Input id="valor_proyectado" type="number" min="0" max="100" class="mt-1 block w-full" bind:value={$form.valor_proyectado} required />
                    </div>
                {/if}
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if isSuperAdmin}
                    <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={(event) => (dialogOpen = true)}> Eliminar producto </button>
                {/if}
                {#if isSuperAdmin}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Editar producto</LoadingButton>
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
