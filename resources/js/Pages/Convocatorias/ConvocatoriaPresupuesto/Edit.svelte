<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Label from '@/Shared/Label'
    import Button from '@/Shared/Button'
    import LoadingButton from '@/Shared/LoadingButton'
    import Select from '@/Shared/Select'
    import Dialog from '@/Shared/Dialog'

    export let errors
    export let convocatoria
    export let convocatoriaPresupuesto
    export let presupuestosSennova

    $: $title = convocatoriaPresupuesto ? convocatoriaPresupuesto.presupuesto_sennova.codigo : null

    /**
     * Validar si el usuario autenticado es SuperAdmin
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let dialogOpen = false
    let sending = false
    let form = useForm({
        presupuesto_sennova_id: {
            value: convocatoriaPresupuesto.presupuesto_sennova_id,
            label: presupuestosSennova.find((item) => item.value == convocatoriaPresupuesto.presupuesto_sennova_id)?.label,
        },
    })

    function submit() {
        if (isSuperAdmin) {
            $form.put(route('convocatorias.convocatoria-presupuesto.update', [convocatoria.id, convocatoriaPresupuesto.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    function destroy() {
        if (isSuperAdmin) {
            $form.delete(route('convocatorias.convocatoria-presupuesto.destroy', [convocatoria.id, convocatoriaPresupuesto.id]))
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1 class="overflow-ellipsis overflow-hidden w-breadcrumb-ellipsis whitespace-nowrap">
                    {#if isSuperAdmin}
                        <a use:inertia href={route('convocatorias.convocatoria-presupuesto.index', convocatoria.id)} class="text-cyan-400 hover:text-cyan-600"> Rubros presupuestales SENNOVA de convocatoria</a>
                    {/if}
                    <span class="text-cyan-400 font-medium">/</span>
                    {convocatoriaPresupuesto.presupuesto_sennova.codigo}
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={isSuperAdmin ? undefined : true}>
                <div class="mt-4">
                    <Label required class="mb-4" labelFor="presupuesto_sennova_id" value="Rubros presupuestales SENNOVA" />
                    <Select id="presupuesto_sennova_id" items={presupuestosSennova} bind:selectedValue={$form.presupuesto_sennova_id} error={errors.presupuesto_sennova_id} autocomplete="off" placeholder="Seleccione un rubro presupuestal SENNOVA" required />
                </div>
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if isSuperAdmin}
                    <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={(event) => (dialogOpen = true)}> Eliminar rubro presupuestal SENNOVA </button>
                {/if}
                {#if isSuperAdmin}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Editar rubro presupuestal SENNOVA</LoadingButton>
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
