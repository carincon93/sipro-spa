<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Shared/Input'
    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import Select from '@/Shared/Select'

    export let errors
    export let rolesTp
    export let nodosTecnoparque

    $: $title = 'Crear regla de rol TP'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let sending = false
    let form = useForm({
        maximo: '',
        convocatoria_rol_sennova_id: null,
        nodo_tecnoparque_id: null,
    })

    function submit() {
        if (isSuperAdmin) {
            $form.post(route('reglas-roles-tp.store'), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
            })
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    {#if isSuperAdmin}
                        <a use:inertia href={route('reglas-roles-tp.index')} class="text-indigo-400 hover:text-indigo-600"> Reglas de roles TP </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    Crear
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
                    <Label required class="mb-4" labelFor="convocatoria_rol_sennova_id" value="Rol TA" />
                    <Select id="convocatoria_rol_sennova_id" items={rolesTp} bind:selectedValue={$form.convocatoria_rol_sennova_id} error={errors.convocatoria_rol_sennova_id} autocomplete="off" placeholder="Seleccione un rol" required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="nodo_tecnoparque_id" value="Nodo tecnoparque" />
                    <Select id="nodo_tecnoparque_id" items={nodosTecnoparque} bind:selectedValue={$form.nodo_tecnoparque_id} error={errors.nodo_tecnoparque_id} autocomplete="off" placeholder="Seleccione un nodo" required />
                </div>
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if isSuperAdmin}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Crear regla</LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
