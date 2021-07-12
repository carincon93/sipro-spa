<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Shared/Input'
    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import Checkbox from '@smui/checkbox'
    import FormField from '@smui/form-field'
    import InputError from '@/Shared/InputError'
    import Select from '@/Shared/Select'
    import Textarea from '@/Shared/Textarea'
    import DynamicList from '@/Shared/Dropdowns/DynamicList'

    export let errors
    export let lineasTecnologicas
    export let modalidades

    $: $title = 'Crear tecnoacademia'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let sending = false
    let form = useForm({
        nombre: '',
        foco: '',
        fecha_creacion: '',
        modalidad: null,
        centro_formacion_id: null,
        linea_tecnologica_id: [],
    })

    function submit() {
        if (isSuperAdmin) {
            $form.post(route('tecnoacademias.store'), {
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
                        <a use:inertia href={route('tecnoacademias.index')} class="text-indigo-400 hover:text-indigo-600"> Tecnoacademias </a>
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
                    <Input label="Nombre" id="nombre" type="text" class="mt-1" bind:value={$form.nombre} error={errors.nombre} required />
                </div>
                <div class="mt-4">
                    <Label required class="mb-4" labelFor="centro_formacion_id" value="Centro de formación" />
                    <DynamicList id="centro_formacion_id" bind:value={$form.centro_formacion_id} routeWebApi={route('web-api.centros-formacion')} placeholder="Busque por el nombre del centro de formación" message={errors.centro_formacion_id} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="modalidad" value="Modalidad" />
                    <Select id="modalidad" items={modalidades} bind:selectedValue={$form.modalidad} error={errors.modalidad} autocomplete="off" placeholder="Seleccione una modalidad" required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="fecha_creacion" value="Fecha de creación" />
                    <input label="Fecha de creación" id="fecha_creacion" type="date" class="mt-1 p-4" bind:value={$form.fecha_creacion} required />
                </div>

                <div class="mt-4">
                    <Textarea label="Foco de la TecnoAcademia" maxlength="40000" id="foco" bind:value={$form.foco} error={errors.foco} required />
                </div>

                <div class="mt-10">
                    <Label required class="mb-4" labelFor="linea_tecnologica_id" value="Líneas de TecnoAcademia" />
                    <div class="mt-10 grid grid-cols-2">
                        {#each lineasTecnologicas as { id, nombre }, i}
                            <FormField>
                                <Checkbox bind:group={$form.linea_tecnologica_id} value={id} />
                                <span slot="label">{nombre}</span>
                            </FormField>
                        {/each}
                    </div>
                    <InputError message={errors.linea_tecnologica_id} />
                </div>
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if isSuperAdmin}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Crear tecnoacademia</LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
