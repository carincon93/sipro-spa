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
    export let centrosFormacion

    $: $title = 'Crear regla de rol Cultura de la innovación'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let sending = false
    let form = useForm({
        centro_formacion_id: null,
        auxiliar_editorial: 0,
        gestor_editorial: 0,
        experto_tematico: 0,
    })

    function submit() {
        if (isSuperAdmin) {
            $form.post(route('reglas-roles-cultura.store'), {
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
                        <a use:inertia href={route('reglas-roles-cultura.index')} class="text-indigo-400 hover:text-indigo-600"> Reglas de roles Cultura de la innovación </a>
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
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Crear regla</LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
