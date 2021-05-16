<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import Dialog from '@/Components/Dialog'

    import Textarea from '@/Components/Textarea'
    import Label from '@/Components/Label'
    import Button from '@/Components/Button'
    import LoadingButton from '@/Components/LoadingButton'
    import Checkbox from '@smui/checkbox'
    import FormField from '@smui/form-field'

    export let errors
    export let anexo
    export let lineasProgramaticas
    export let anexoLineasProgramaticas

    $: $title = anexo ? anexo.nombre : null

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0

    let canIndexAnexos = authUser.can.find((element) => element == 'anexos.index') == 'anexos.index'

    let canShowAnexos = authUser.can.find((element) => element == 'anexos.show') == 'anexos.show'

    let canCreateAnexos = authUser.can.find((element) => element == 'anexos.create') == 'anexos.create'

    let canEditAnexos = authUser.can.find((element) => element == 'anexos.edit') == 'anexos.edit'

    let canDestroyAnexos = authUser.can.find((element) => element == 'anexos.destroy') == 'anexos.destroy'

    let dialog_open = false
    let sending = false
    let form = useForm({
        nombre: anexo.nombre,
        descripcion: anexo.descripcion,
        linea_programatica_id: anexoLineasProgramaticas,
    })

    function submit() {
        if (canEditAnexos || isSuperAdmin) {
            $form.put(route('anexos.update', anexo.id), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    function destroy() {
        if (canDestroyAnexos || isSuperAdmin) {
            $form.delete(route('anexos.destroy', anexo.id))
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    {#if isSuperAdmin}
                        <a use:inertia href={route('anexos.index')} class="text-indigo-400 hover:text-indigo-600"> Anexos </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    {anexo.nombre}
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={canEditAnexos || isSuperAdmin ? undefined : true}>
                <div class="mt-4">
                    <Label required class="mb-4" labelFor="nombre" value="Nombre del anexo" />
                    <Textarea rows="4" id="nombre" error={errors.nombre} bind:value={$form.nombre} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="descripcion" value="Descripción" />
                    <Textarea rows="4" id="descripcion" error={errors.descripcion} bind:value={$form.descripcion} required />
                </div>

                <div class="bg-white rounded shadow overflow-hidden mt-20">
                    <div class="grid grid-cols-2">
                        {#each lineasProgramaticas as { id, nombre, codigo }, i}
                            <FormField>
                                <Checkbox bind:group={$form.linea_programatica_id} value={id} />
                                <span slot="label">{nombre} ({codigo})</span>
                            </FormField>
                        {/each}
                    </div>
                </div>
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if isSuperAdmin}
                    <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={(event) => (dialog_open = true)}> Eliminar anexo </button>
                {/if}
                {#if isSuperAdmin}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Editar anexo</LoadingButton>
                {/if}
            </div>
        </form>

        <Dialog bind:open={dialog_open}>
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
                    <Button on:click={(event) => (dialog_open = false)} variant={null}>Cancelar</Button>
                    <Button variant="raised" on:click={destroy}>Confirmar</Button>
                </div>
            </div>
        </Dialog>
    </div>
</AuthenticatedLayout>
