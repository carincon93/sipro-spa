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
    export let rolesTa
    export let tecnoacademias

    $: $title = 'Crear regla de rol TA'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let sending = false
    let form = useForm({
        maximo: '',
        conovocatoria_rol_sennova_id: null,
        tecnoacademia_id: null,
    })

    function submit() {
        if (isSuperAdmin) {
            $form.post(route('reglas-roles-ta.store'), {
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
                        <a use:inertia href={route('reglas-roles-ta.index')} class="text-indigo-400 hover:text-indigo-600"> Reglas de roles TA </a>
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
                    <Label required class="mb-4" labelFor="conovocatoria_rol_sennova_id" value="Rol TA" />
                    <Select id="conovocatoria_rol_sennova_id" items={rolesTa} bind:selectedValue={$form.conovocatoria_rol_sennova_id} error={errors.conovocatoria_rol_sennova_id} autocomplete="off" placeholder="Seleccione un rol" required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="tecnoacademia_id" value="Tecnoacademia" />
                    <Select id="tecnoacademia_id" items={tecnoacademias} bind:selectedValue={$form.tecnoacademia_id} error={errors.tecnoacademia_id} autocomplete="off" placeholder="Seleccione una tecnoacademia" required />
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
