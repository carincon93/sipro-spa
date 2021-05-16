<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Components/Input'
    import Label from '@/Components/Label'
    import Select from '@/Components/Select'
    import LoadingButton from '@/Components/LoadingButton'
    import Textarea from '@/Components/Textarea'
    import DropdownLineaProgramatica from '@/Dropdowns/DropdownLineaProgramatica'

    export let errors
    export let nivelesAcademicos

    $: $title = 'Crear rol SENNOVA'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0

    let sending = false
    let form = useForm({
        nombre: '',
        descripcion: '',
        nivel_academico: '',
        linea_programatica_id: null,
    })

    function submit() {
        if (isSuperAdmin) {
            $form.post(route('sennova-roles.store'), {
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
                        <a use:inertia href={route('sennova-roles.index')} class="text-indigo-400 hover:text-indigo-600"> Roles SENNOVA </a>
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
                    <Label required class="mb-4" labelFor="nombre" value="Nombre" />
                    <Input id="nombre" type="text" class="mt-1 block w-full" bind:value={$form.nombre} error={errors.nombre} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="descripcion" value="Descripción" />
                    <Textarea rows="4" id="descripcion" bind:value={$form.descripcion} error={errors.descripcion} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="linea_programatica_id" value="Línea programática" />
                    <DropdownLineaProgramatica id="linea_programatica_id" bind:formLineaProgramatica={$form.linea_programatica_id} message={errors.linea_programatica_id} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="nivel_academico" value="Nivel académico" />
                    <Select id="nivel_academico" items={nivelesAcademicos} bind:selectedValue={$form.nivel_academico} error={errors.nivel_academico} autocomplete="off" placeholder="Seleccione un nivel académico" required />
                </div>
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if isSuperAdmin}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Crear rol SENNOVA</LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
