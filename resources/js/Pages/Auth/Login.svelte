<script context="module">
    import GuestLayout from '@/Layouts/Guest'
    export const layout = GuestLayout
</script>

<script>
    import { Inertia } from '@inertiajs/inertia'
    import { inertia } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import Input from '@/Components/Input'
    import LoadingButton from '@/Components/LoadingButton'
    import Checkbox from '@smui/checkbox'
    import FormField from '@smui/form-field'

    export let status
    export let errors

    let canResetPassword
    let selection = []
    let sending = false

    let form = {
        email: '',
        password: '',
        remember: false,
    }

    function handleSubmit() {
        const data = {
            email: form.email,
            password: form.password,
            remember: form.remember,
        }
        Inertia.post(route('login'), data, {
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
        <Input label={$_('Email')} id="email" type="email" class="mt-1 block w-full" bind:value={form.email} error={errors.email} required autocomplete="email" />
    </div>

    <div class="mt-4">
        <Input label={$_('Password')} id="password" type="password" class="mt-1 block w-full" bind:value={form.password} error={errors.password} required autocomplete="current-password" />
    </div>

    <div class="block mt-4">
        <FormField>
            <Checkbox bind:checked={form.remember} value={selection} />
            <span slot="label">{$_('Remember me')}</span>
        </FormField>
    </div>

    <div class="flex items-center justify-end mt-4">
        {#if canResetPassword}
            <a use:inertia href={route('password.request')} class="mr-4 underline text-sm text-gray-600 hover:text-gray-900">
                {$_('Forgot your password?')}
            </a>
        {/if}

        <LoadingButton bind:loading={sending} class="btn-indigo" type="submit">{$_('Login')}</LoadingButton>
    </div>
</form>

<p class="text-xs mt-6">
    Si aún no tiene cuenta puede crear una dilienciando el siguiente <a use:inertia href={route('register')} class="text-indigo-500">formulario</a>
</p>

<div class="flex mt-20">
    <figure>
        <img src={window.basePath + 'images/sennova-logo.png'} alt="Logo SENNOVA" />
    </figure>

    <figure class="ml-10">
        <img src={window.basePath + 'images/grindda.png'} alt="Logo del grupo de investigación GRINDDA" />
    </figure>
</div>
