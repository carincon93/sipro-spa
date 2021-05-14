<script context="module">
    import GuestLayout from '@/Layouts/Guest'
    export const layout = GuestLayout
</script>

<script>
    import { Inertia } from '@inertiajs/inertia'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import LoadingButton from '@/Components/LoadingButton'
    import Input from '@/Components/Input'
    import Label from '@/Components/Label'
    import InputError from '@/Components/InputError'

    export let status
    export let errors

    let sending = false

    let form = {
        email: '',
    }

    function handleSubmit() {
        Inertia.post(route('password.email'), form)
    }
</script>

<div class="mb-4 text-sm text-gray-600">
    {$_(
        'Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.',
    )}
</div>

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
            name="email"
            required
            autocomplete="email"
        />
        <InputError message={errors.email} />
    </div>

    <div class="flex items-center justify-end mt-4">
        <LoadingButton bind:loading={sending} class="btn-indigo" type="submit"
            >{$_('Email Password Reset Link')}</LoadingButton
        >
    </div>
</form>
