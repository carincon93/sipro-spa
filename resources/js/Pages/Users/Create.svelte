<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Shared/Input'
    import Label from '@/Shared/Label'
    import InputError from '@/Shared/InputError'
    import LoadingButton from '@/Shared/LoadingButton'
    import Switch from '@/Shared/Switch'
    import Select from '@/Shared/Select'
    import InfoMessage from '@/Shared/InfoMessage'
    import Checkbox from '@smui/checkbox'
    import FormField from '@smui/form-field'
    import DynamicList from '@/Shared/Dropdowns/DynamicList'

    export let errors
    export let tiposDocumento
    export let tiposVinculacion
    export let roles

    $: $title = 'Crear usuario'

    /**
     * Validar si el usuario autenticado es SuperAdmin
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let form = useForm({
        nombre: '',
        email: '',
        tipo_documento: '',
        numero_documento: '',
        numero_celular: '',
        habilitado: true,
        tipo_vinculacion: '',
        centro_formacion_id: isSuperAdmin ? null : checkRole(authUser, [4, 21]) ? authUser.centro_formacion_id : null,
        role_id: [],
        permission_id: [],
        autorizacion_datos: false,
    })

    function submit() {
        if (isSuperAdmin || checkRole(authUser, [4, 21, 17, 18, 20, 19, 5])) {
            $form.post(route('users.store'))
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                        <a use:inertia href={route('users.index')} class="text-violet-400 hover:text-violet-600"> Usuarios </a>
                    <span class="text-violet-400 font-medium">/</span>
                    Crear
                </h1>
            </div>
        </div>
    </header>

    <form on:submit|preventDefault={submit}>
        <div class="bg-white rounded shadow max-w-3xl">
            <div class="p-8">
                <div class="mt-4">
                    <Input label="Nombre completo" id="nombre" type="text" class="mt-1" bind:value={$form.nombre} error={errors.nombre} required />
                </div>

                <div class="mt-4">
                    <Input label="Correo electrónico institucional" id="email" type="email" class="mt-1" bind:value={$form.email} error={errors.email} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="tipo_documento" value="Tipo de documento" />
                    <Select id="tipo_documento" items={tiposDocumento} bind:selectedValue={$form.tipo_documento} error={errors.tipo_documento} autocomplete="off" placeholder="Seleccione un tipo de documento" required />
                </div>

                <div class="mt-4">
                    <Input label="Número de documento" id="numero_documento" type="number" input$min="55555" input$max="9999999999999" class="mt-1" bind:value={$form.numero_documento} error={errors.numero_documento} required />
                </div>

                <div class="mt-4">
                    <Input label="Número de celular" id="numero_celular" type="number" input$min="3000000000" input$max="9999999999" class="mt-1" bind:value={$form.numero_celular} error={errors.numero_celular} required />
                </div>
                <div class="mt-4">
                    <Label required labelFor="habilitado" value="¿El usuario está habilitado para ingresar al sistema?" class="inline-block mb-4" />
                    <br />
                    <Switch bind:checked={$form.habilitado} />
                    <InputError message={errors.habilitado} />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="tipo_vinculacion" value="Tipo de vinculación" />
                    <Select id="tipo_vinculacion" items={tiposVinculacion} bind:selectedValue={$form.tipo_vinculacion} error={errors.tipo_vinculacion} autocomplete="off" placeholder="Seleccione el tipo de vinculación" required />
                </div>

                {#if isSuperAdmin}
                    <div class="mt-4">
                        <Label required class="mb-4" labelFor="centro_formacion_id" value="Centro de formación" />
                        <DynamicList id="centro_formacion_id" bind:value={$form.centro_formacion_id} routeWebApi={route('web-api.centros-formacion')} placeholder="Busque por el nombre del centro de formación" message={errors.centro_formacion_id} required />
                    </div>
                {/if}

                <div class="mt-4">
                    <InfoMessage message="Los datos proporcionados serán tratados de acuerdo con la política de tratamiento de datos personales del SENA y a la ley 1581 de 2012 (Acuerdo No. 0009 del 2016)" />
                    <FormField>
                        <Checkbox bind:checked={$form.autorizacion_datos} />
                        <span slot="label">¿La persona autoriza el tratamiento de datos personales?. <a href="https://www.sena.edu.co/es-co/transparencia/Documents/proteccion_datos_personales_sena_2016.pdf" target="_blank" class="text-violet-500">Leer acuerdo No. 0009 del 2016</a></span>
                    </FormField>
                </div>

                <div class="mt-4">
                    {#if $form.numero_documento}
                        <InfoMessage message="La contraseña de este usuario es: sena{$form.numero_documento}*" />
                    {/if}
                </div>
            </div>
        </div>

        <div class="bg-white rounded shadow overflow-hidden mt-20">
            <div class="p-4">
                <Label required class="mb-4" labelFor="role_id" value="Seleccione algún rol" />
                <InputError message={errors.role_id} />
            </div>
            <div class="grid grid-cols-2">
                {#each roles as { id, name }, i}
                    {#if (checkRole(authUser, [4, 21, 17, 18, 20, 19, 5]) && name == 'proponente cultura de la innovación') || (checkRole(authUser, [4, 21, 17, 18, 20, 19, 5]) && name == 'proponente i+d+i') || (checkRole(authUser, [4, 21, 17, 18, 20, 19, 5]) && name == 'proponente servicios tecnológicos') || (checkRole(authUser, [4, 21, 17, 18, 20, 19, 5]) && name == 'proponente tecnoacademia') || (checkRole(authUser, [4, 21, 17, 18, 20, 19, 5]) && name == 'proponente tecnoparque')}
                        <div class="pt-8 pb-8 border-t">
                            <FormField>
                                <Checkbox bind:group={$form.role_id} value={id} />
                                <span slot="label">{name}</span>
                            </FormField>
                        </div>
                    {:else if isSuperAdmin}
                        <div class="pt-8 pb-8 border-t">
                            <FormField>
                                <Checkbox bind:group={$form.role_id} value={id} />
                                <span slot="label">{name}</span>
                            </FormField>
                        </div>
                    {/if}
                {/each}
            </div>
        </div>

        <div class="bg-white rounded shadow overflow-hidden mt-20">
            <fieldset class="p-8" disabled={isSuperAdmin || checkRole(authUser, [17, 18, 20, 19, 5]) ? undefined : true}>
                <div class="p-4">
                    <Label class="mb-4" labelFor="role_id" value="Seleccione algún permiso especial para el usuario (Aplica cuando la convocatoria ha finalizado)" />
                    <InputError message={errors.permission_id} />
                </div>
                <div class="grid grid-cols-2">
                    {#if isSuperAdmin}
                        <div class="pt-8 pb-8 border-t">
                            <FormField>
                                <Checkbox bind:group={$form.permission_id} value={1} />
                                <span slot="label">Crear/modificar proyecto de I+D+i</span>
                            </FormField>
                        </div>

                        <div class="pt-8 pb-8 border-t">
                            <FormField>
                                <Checkbox bind:group={$form.permission_id} value={8} />
                                <span slot="label">Crear/modificar proyecto de TA</span>
                            </FormField>
                        </div>

                        <div class="pt-8 pb-8 border-t">
                            <FormField>
                                <Checkbox bind:group={$form.permission_id} value={17} />
                                <span slot="label">Crear/modificar proyecto de TP</span>
                            </FormField>
                        </div>

                        <div class="pt-8 pb-8 border-t">
                            <FormField>
                                <Checkbox bind:group={$form.permission_id} value={11} />
                                <span slot="label">Crear/modificar proyecto de Cultura de la Innovación</span>
                            </FormField>
                        </div>

                        <div class="pt-8 pb-8 border-t">
                            <FormField>
                                <Checkbox bind:group={$form.permission_id} value={5} />
                                <span slot="label">Crear proyecto de Servicios Tecnológicos</span>
                            </FormField>
                        </div>
                    {/if}
                </div>
            </fieldset>
        </div>

        {#if $form.role_id.find((item) => item == 4) == 4 && isSuperAdmin}
            <InfoMessage alertMsg={true} class="mt-10">
                <strong>Importante:</strong>
                <br />
                El/la usuario(a) tiene rol Dinamizador(a) SENNOVA, al dar clic en 'Editar usuario' se configurará como dinamizador(a) del centro seleccionado
            </InfoMessage>
        {/if}
        <div class="shadow-inner bg-violet-200 border-violet-400 bottom-0 flex items-center justify-between mt-14 px-8 py-4 sticky">
            {#if isSuperAdmin || checkRole(authUser, [4, 21, 17, 18, 20, 19, 5])}
                <LoadingButton loading={$form.processing} type="submit" bind:disabled={$form.autorizacion_datos}>Crear usuario</LoadingButton>
            {/if}
        </div>
    </form>
</AuthenticatedLayout>
