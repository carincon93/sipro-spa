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
    import InputError from '@/Components/InputError'

    export let email
    export let token
    export let errors

    let sending = false

    let form = {
        token: token,
        email: email,
        password: '',
        password_confirmation: '',
    }

    function handleSubmit() {
        Inertia.post(route('password.update', form))
    }
</script>

<form on:submit|preventDefault={handleSubmit}>
    <div>
        <Input label={$_('Email')} id="email" type="email" class="mt-1 block w-full" bind:value={form.email} name="email" error={errors.email} required autocomplete="email" />
    </div>

    <div class="mt-4">
        <Input label={$_('Password')} id="password" type="password" class="mt-1 block w-full" bind:value={form.password} name="password" error={errors.password} required autocomplete="new-password" />
    </div>

    <div class="mt-4">
        <Input label={$_('Confirm Password')} id="password_confirmation" type="password" class="mt-1 block w-full" bind:value={form.password_confirmation} name="password_confirmation" required autocomplete="new-password" />
        <InputError message={errors.password_confirmation} />
    </div>

    <div class="flex items-center justify-end mt-4">
        <LoadingButton bind:loading={sending} class="btn-indigo" type="submit">{$_('Reset Password')}</LoadingButton>
    </div>
</form>
