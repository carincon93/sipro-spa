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
    {$_(
        'This is a secure area of the application. Please confirm your password before continuing.',
    )}
</div>

<form on:submit|preventDefault={handleSubmit}>
    <div>
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

    <div class="flex justify-end mt-4">
        <LoadingButton bind:loading={sending} class="btn-indigo" type="submit"
            >Confirmar</LoadingButton
        >
    </div>
</form>
