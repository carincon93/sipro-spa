<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { Inertia } from '@inertiajs/inertia'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Textarea from '@/Shared/Textarea'
    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import Checkbox from '@smui/checkbox'
    import FormField from '@smui/form-field'
    import InputError from '@/Shared/InputError'

    export let errors
    export let lineasProgramaticas

    $: $title = 'Crear anexo'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let sending = false
    let form = useForm({
        nombre: '',
        descripcion: '',
        linea_programatica_id: [],
    })

    function submit() {
        if (isSuperAdmin) {
            $form.post(route('anexos.store'), {
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
                        <a use:inertia href={route('anexos.index')} class="text-indigo-400 hover:text-indigo-600"> Anexos </a>
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
                    <Textarea label="Nombre del anexo" maxlength="40000" id="nombre" error={errors.nombre} bind:value={$form.nombre} required />
                </div>

                <div class="mt-4">
                    <Textarea label="Descripción" maxlength="40000" id="descripcion" error={errors.descripcion} bind:value={$form.descripcion} required />
                </div>

                <div class="bg-white rounded shadow overflow-hidden mt-20">
                    <div class="p-4">
                        <Label required class="mb-4" labelFor="linea_programatica_id" value="Seleccione alguna línea programática" />
                        <InputError message={errors.linea_programatica_id} />
                    </div>
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
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Crear anexo</LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
