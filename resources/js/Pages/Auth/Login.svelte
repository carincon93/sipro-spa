<script context="module">
    import GuestLayout, { title } from '@/Layouts/Guest'
    export const layout = GuestLayout
</script>

<script>
    import { inertia, useForm } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import Input from '@/Shared/Input'
    import LoadingButton from '@/Shared/LoadingButton'
    import Checkbox from '@smui/checkbox'
    import FormField from '@smui/form-field'
    import Password from '@/Shared/Password'

    export let status
    export let errors

    $title = 'Iniciar sesión'

    let canResetPassword
    let selection = []
    let sending = false

    let form = useForm({
        email: '',
        password: '',
        remember: false,
    })

    function handleSubmit() {
        $form.post(route('login'), {
            onStart: () => (sending = true),
            onFinish: () => (sending = false),
        })
    }
</script>

<svelte:head>
    <title>Ingresar - SGPS-SIPRO</title>
</svelte:head>

{#if status}
    <div class="mb-4 font-medium text-sm text-green-600">
        {status}
    </div>
{/if}

<form on:submit|preventDefault={handleSubmit}>
    <div>
        <Input label={$_('Email')} id="email" type="email" class="mt-1" bind:value={$form.email} error={errors.email} required autocomplete="email" />
    </div>

    <div class="mt-4">
        <Password id="password" class="mt-1 w-full" bind:value={$form.password} error={errors.password} required autocomplete="current-password" />
    </div>

    <div class="block mt-4">
        <FormField>
            <Checkbox bind:checked={$form.remember} value={selection} />
            <span slot="label">{$_('Remember me')}</span>
        </FormField>
    </div>

    <div class="flex items-center justify-between mt-4">
        <a use:inertia href={route('password.request')} class="mr-4 underline text-sm text-gray-600 hover:text-gray-900">
            {$_('Forgot your password?')}
        </a>

        <LoadingButton bind:loading={sending} class="btn-indigo" type="submit">{$_('Login')}</LoadingButton>
    </div>
</form>

<p class="text-xs mt-10">Si aún no tiene cuenta puede crearla diligenciando el siguiente formulario</p>

<a use:inertia href={route('register')} class="bg-indigo-500 block mt-1 mx-auto p-2 rounded text-center text-white text-xs w-1/4">Registro</a>

<p class="text-xs mt-6 text-indigo-500">
    Problemas con la plataforma. Envíe un correo a la dirección <a class="underline" href="mailto:sgpssipro@sena.edu.co">sgpssipro@sena.edu.co</a> (Se debe enviar desde una cuenta @sena.edu.co)
</p>

<div class="flex mt-20">
    <figure>
        <img src={window.basePath + '/images/sennova-logo.png'} alt="Logo SENNOVA" />
    </figure>

    <figure class="ml-10">
        <img src={window.basePath + '/images/grindda.png'} alt="Logo del grupo de investigación GRINDDA" />
    </figure>
</div>
