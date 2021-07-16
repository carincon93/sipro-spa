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
    export let centrosFormacion
    export let reglaRolCultura

    $: $title = 'Editar regla de rol Cultura de la innovación'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let dialogOpen = false
    let sending = false
    let form = useForm({
        centro_formacion_id: {
            value: reglaRolCultura.centro_formacion_id,
            label: centrosFormacion.find((item) => item.value == reglaRolCultura.centro_formacion_id)?.label,
        },
        auxiliar_editorial: reglaRolCultura.auxiliar_editorial,
        gestor_editorial: reglaRolCultura.gestor_editorial,
        experto_tematico: reglaRolCultura.experto_tematico,
    })

    function submit() {
        if (isSuperAdmin) {
            $form.put(route('reglas-roles-cultura.update', reglaRolCultura.id), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    function destroy() {
        if (isSuperAdmin) {
            $form.delete(route('reglas-roles-cultura.destroy', reglaRolCultura.id))
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    {#if isSuperAdmin}
                        <a use:inertia href={route('reglas-roles-cultura.index')} class="text-indigo-400 hover:text-indigo-600"> Reglas de roles Cultura de la innovación </a>
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
                    <Label required class="mb-4" labelFor="centro_formacion_id" value="Centro de formación" />
                    <Select id="centro_formacion_id" items={centrosFormacion} bind:selectedValue={$form.centro_formacion_id} error={errors.centro_formacion_id} autocomplete="off" placeholder="Seleccione un centro de formación" required />
                </div>

                <div class="mt-4">
                    <Input label="Auxiliar editorial" id="auxiliar_editorial" type="number" input$min="0" class="mt-1" bind:value={$form.auxiliar_editorial} error={errors.auxiliar_editorial} required />
                </div>

                <div class="mt-4">
                    <Input label="Gestor editorial" id="gestor_editorial" type="number" input$min="0" class="mt-1" bind:value={$form.gestor_editorial} error={errors.gestor_editorial} required />
                </div>

                <div class="mt-4">
                    <Input label="Experto temático" id="experto_tematico" type="number" input$min="0" class="mt-1" bind:value={$form.experto_tematico} error={errors.experto_tematico} required />
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
