<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import LoadingButton from '@/Shared/LoadingButton'
    import Textarea from '@/Shared/Textarea'
    import Dialog from '@/Shared/Dialog'
    import Button from '@/Shared/Button'
    import Select from '@/Shared/Select'

    import Header from '../Shared/Header'

    export let errors
    export let proyectoCapacidadInstalada
    export let producto
    export let resultados
    export let tipologiasMinciencias

    $: $title = 'Editar producto'

    /**
     * Validar si el usuario autenticado es SuperAdmin
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let dialogOpen = false

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
        if (proyectoCapacidadInstalada.allowed.to_update) {
            $form.put(route('proyectos-capacidad-instalada.productos.update', [proyectoCapacidadInstalada.id, producto.id]), {
                preserveScroll: true,
            })
        }
    }

    function destroy() {
        if (proyectoCapacidadInstalada.allowed.to_update) {
            $form.delete(route('proyectos-capacidad-instalada.productos.destroy', [proyectoCapacidadInstalada.id, producto.id]))
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <Header {proyectoCapacidadInstalada} />
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={proyectoCapacidadInstalada.allowed.to_update ? undefined : true}>
                <div class="mt-8">
                    <Textarea label="Descripción" maxlength="255" id="descripcion" error={errors.descripcion} bind:value={$form.descripcion} required />
                </div>
                <div class="mt-4">
                    <Select id="resultado_id" items={resultados} bind:selectedValue={$form.resultado_id} error={errors.resultado_id} autocomplete="off" placeholder="Seleccione un resultado" required />
                </div>
                <div class="mt-4">
                    <Select id="tipologia_minciencias" items={tipologiasMinciencias} bind:selectedValue={$form.tipologia_minciencias} error={errors.tipologia_minciencias} autocomplete="off" placeholder="Seleccione una tipología" required />
                </div>
                <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center justify-between sticky bottom-0">
                    {#if proyectoCapacidadInstalada.allowed.to_update}
                        <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={() => (dialogOpen = true)}> Eliminar producto </button>
                        <LoadingButton loading={$form.processing} class="ml-auto" type="submit">Editar producto</LoadingButton>
                    {:else}
                        <span class="inline-block"> El proyecto no se puede modificar </span>
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
                <Button on:click={() => (dialogOpen = false)} variant={null}>Cancelar</Button>
                <Button variant="raised" on:click={destroy}>Confirmar</Button>
            </div>
        </div>
    </Dialog>
</AuthenticatedLayout>
