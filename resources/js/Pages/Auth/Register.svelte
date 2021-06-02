<script context="module">
    import GuestLayout from '@/Layouts/Guest'
    export const layout = GuestLayout
</script>

<script>
    import { Inertia } from '@inertiajs/inertia'
    import { inertia } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import LoadingButton from '@/Components/LoadingButton'
    import Input from '@/Components/Input'
    import Label from '@/Components/Label'
    import InputError from '@/Components/InputError'

    export let errors

    let sending = false

    let form = {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        terms: false,
    }

    function handleSubmit() {
        Inertia.post(route('register'), form)
    }
</script>

<form on:submit|preventDefault={handleSubmit}>
    <div>
        <Input label={$_('Name')} id="name" type="text" class="mt-1 block w-full" bind:value={form.name} required autocomplete="name" />
        <InputError message={errors.name} />
    </div>

    <div class="mt-4">
        <Input label={$_('Email')} id="email" type="email" class="mt-1 block w-full" bind:value={form.email} required autocomplete="username" />
        <InputError message={errors.email} />
    </div>

    <div class="mt-4">
        <Input label={$_('Password')} id="password" type="password" class="mt-1 block w-full" bind:value={form.password} required autocomplete="new-password" />
        <InputError message={errors.password} />
    </div>

    <div class="mt-4">
        <Input label={$_('Confirm Password')} id="password_confirmation" type="password" class="mt-1 block w-full" bind:value={form.password_confirmation} required autocomplete="new-password" />
        <InputError message={errors.password_confirmation} />
    </div>

    <div class="flex items-center justify-end mt-4">
        <a use:inertia href={route('login')} class="mr-4 underline text-sm text-gray-600 hover:text-gray-900">
            {$_('Already registered?')}
        </a>

        <LoadingButton bind:loading={sending} class="btn-indigo" type="submit">{$_('Register')}</LoadingButton>
    </div>
</form>
