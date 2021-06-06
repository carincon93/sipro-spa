<script context="module">
    import GuestLayout from '@/Layouts/Guest'
    export const layout = GuestLayout
</script>

<script>
    import { Inertia } from '@inertiajs/inertia'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import LoadingButton from '@/Shared/LoadingButton'
    import Input from '@/Shared/Input'

    export let errors

    let sending = false

    let form = {
        password: '',
    }

    function handleSubmit() {
        Inertia.post(route('password.confirm', form))
    }
</script>

<div class="mb-4 text-sm text-gray-600">
    {$_('This is a secure area of the application. Please confirm your password before continuing.')}
</div>

<form on:submit|preventDefault={handleSubmit}>
    <div>
        <Input label={$_('Password')} id="password" type="password" class="mt-1" bind:value={form.password} error={errors.password} required autocomplete="current-password" />
    </div>

    <div class="flex justify-end mt-4">
        <LoadingButton bind:loading={sending} class="btn-indigo" type="submit">Confirmar</LoadingButton>
    </div>
</form>
