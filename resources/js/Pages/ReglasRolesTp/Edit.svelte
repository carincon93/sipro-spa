<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Shared/Input'
    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import Select from '@/Shared/Select'
    import Dialog from '@/Shared/Dialog'
    import Button from '@/Shared/Button'

    export let errors
    export let rolesTp
    export let nodosTecnoparque
    export let reglaRolTp

    $: $title = 'Editar regla de rol TP'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let dialogOpen = false
    let sending = false
    let form = useForm({
        maximo: reglaRolTp.maximo,
        convocatoria_rol_sennova_id: {
            value: reglaRolTp.convocatoria_rol_sennova_id,
            label: rolesTp.find((item) => item.value == reglaRolTp.convocatoria_rol_sennova_id)?.label,
        },
        nodo_tecnoparque_id: {
            value: reglaRolTp.nodo_tecnoparque_id,
            label: nodosTecnoparque.find((item) => item.value == reglaRolTp.nodo_tecnoparque_id)?.label,
        },
    })

    function submit() {
        if (isSuperAdmin) {
            $form.put(route('reglas-roles-tp.update', reglaRolTp.id), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    function destroy() {
        if (isSuperAdmin) {
            $form.delete(route('reglas-roles-tp.destroy', reglaRolTp.id))
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    {#if isSuperAdmin}
                        <a use:inertia href={route('reglas-roles-tp.index')} class="text-indigo-400 hover:text-indigo-600"> Reglas de roles TP</a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    Editar
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={isSuperAdmin ? undefined : true}>
                <div class="mt-4">
                    <Input label="Máximo de roles" id="maximo" type="number" input$min="0" class="mt-1" bind:value={$form.maximo} error={errors.maximo} required />
                </div>
                <div class="mt-4">
                    <Label required class="mb-4" labelFor="convocatoria_rol_sennova_id" value="Rol TP" />
                    <Select id="convocatoria_rol_sennova_id" items={rolesTp} bind:selectedValue={$form.convocatoria_rol_sennova_id} error={errors.convocatoria_rol_sennova_id} autocomplete="off" placeholder="Seleccione un rol" required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="nodo_tecnoparque_id" value="Nodo tecnoparque" />
                    <Select id="nodo_tecnoparque_id" items={nodosTecnoparque} bind:selectedValue={$form.nodo_tecnoparque_id} error={errors.nodo_tecnoparque_id} autocomplete="off" placeholder="Seleccione un nodo" required />
                </div>
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if isSuperAdmin}
                    <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={(event) => (dialogOpen = true)}> Eliminar regla </button>
                {/if}
                {#if isSuperAdmin}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Editar regla</LoadingButton>
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
