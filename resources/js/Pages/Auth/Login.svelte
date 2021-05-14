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
    import Label from '@/Components/Label'
    import InputError from '@/Components/InputError'
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
        <Label required class="mb-4" labelFor="email" value={$_('Email')} />
        <Input
            id="email"
            type="email"
            class="mt-1 block w-full"
            bind:value={form.email}
            required
            autocomplete="email"
        />
        <InputError message={errors.email} />
    </div>

    <div class="mt-4">
        <Label
            required
            class="mb-4"
            labelFor="password"
            value={$_('Password')}
        />
        <Input
            id="password"
            type="password"
            class="mt-1 block w-full"
            bind:value={form.password}
            required
            autocomplete="current-password"
        />
        <InputError message={errors.password} />
    </div>

    <div class="block mt-4">
        <FormField>
            <Checkbox bind:checked={form.remember} value={selection} />
            <span slot="label">{$_('Remember me')}</span>
        </FormField>
    </div>

    <div class="flex items-center justify-end mt-4">
        {#if canResetPassword}
            <a
                use:inertia
                href={route('password.request')}
                class="mr-4 underline text-sm text-gray-600 hover:text-gray-900"
            >
                {$_('Forgot your password?')}
            </a>
        {/if}

        <LoadingButton bind:loading={sending} class="btn-indigo" type="submit"
            >{$_('Login')}</LoadingButton
        >
    </div>
</form>
