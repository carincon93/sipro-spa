<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Shared/Input'
    import LoadingButton from '@/Shared/LoadingButton'
    import Label from '@/Shared/Label'
    import Select from '@/Shared/Select'
    import InputError from '@/Shared/InputError'
    import Checkbox from '@smui/checkbox'
    import FormField from '@smui/form-field'

    export let errors
    export let user
    export let tiposDocumento
    export let tiposVinculacion
    export let roles
    export let rolesRelacionados

    $: $title = 'Perfil'

    /**
     * Validar si el usuario autenticado es SuperAdmin
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let sending = false
    let formChangePassword = useForm({
        old_password: '',
        password: '',
        password_confirmation: '',
    })

    function submitChangePassword() {
        $formChangePassword.put(route('users.change-password'), {
            onStart: () => (sending = true),
            onFinish: () => (sending = false),
        })
    }

    let form = useForm({
        nombre: user.nombre,
        email: user.email,
        tipo_documento: {
            value: user.tipo_documento,
            label: tiposDocumento.find((item) => item.value == user.tipo_documento)?.label,
        },
        numero_documento: user.numero_documento,
        numero_celular: user.numero_celular,
        tipo_vinculacion: {
            value: user.tipo_vinculacion,
            label: tiposVinculacion.find((item) => item.value == user.tipo_vinculacion)?.label,
        },
        role_id: rolesRelacionados,
    })

    function submitChangeUserProfile() {
        $form.put(route('users.change-user-profile'), {
            onStart: () => (sending = true),
            onFinish: () => (sending = false),
        })
    }
</script>

<AuthenticatedLayout>
    <div class="grid grid-cols-3">
        <div>
            <h1 class="font-black text-4xl sticky top-0 uppercase">Datos personales</h1>
        </div>
        <form on:submit|preventDefault={submitChangeUserProfile} class="bg-white rounded shadow col-span-2">
            <fieldset class="p-8">
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
                    <Label required class="mb-4" labelFor="tipo_vinculacion" value="Tipo de vinculación" />
                    <Select id="tipo_vinculacion" items={tiposVinculacion} bind:selectedValue={$form.tipo_vinculacion} error={errors.tipo_vinculacion} autocomplete="off" placeholder="Seleccione el tipo de vinculación" required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="role_id" value="Seleccione algún rol" />
                    <InputError message={errors.role_id} />
                </div>
                <div class="grid grid-cols-2">
                    {#each roles as { id, name }, i}
                        <div class="pt-8 pb-8 border-t">
                            <FormField>
                                <Checkbox bind:group={$form.role_id} value={id} />
                                <span slot="label">{name}</span>
                            </FormField>
                        </div>
                    {/each}
                </div>
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center">
                <LoadingButton bind:loading={sending} type="submit">Guardar cambios</LoadingButton>
            </div>
        </form>
    </div>

    <hr class="w-full my-8" />

    <div class="grid grid-cols-3">
        <div>
            <h1 class="font-black text-4xl sticky top-0 uppercase">Cambiar la contraseña</h1>
        </div>
        <form on:submit|preventDefault={submitChangePassword} class="bg-white rounded shadow col-span-2">
            <fieldset class="p-8">
                <div class="mt-4">
                    <Input label="Contraseña actual" id="old_password" type="password" class="mt-1" bind:value={$formChangePassword.old_password} error={errors.old_password} required />
                </div>
                <div class="mt-4">
                    <Input label={'Nueva ' + $_('Password').toLowerCase()} id="password" type="password" class="mt-1" bind:value={$formChangePassword.password} error={errors.password} required autocomplete="new-password" />
                </div>

                <div class="mt-4">
                    <Input label={$_('Confirm Password')} id="password_confirmation" type="password" class="mt-1" bind:value={$formChangePassword.password_confirmation} error={errors.password_confirmation} required autocomplete="new-password" />
                </div>
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center">
                <LoadingButton bind:loading={sending} type="submit" bind:disabled={$formChangePassword.autorizacion_datos}>Cambiar contraseña</LoadingButton>
            </div>
        </form>
    </div>
</AuthenticatedLayout>
