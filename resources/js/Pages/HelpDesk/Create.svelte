<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Shared/Input'
    import File from '@/Shared/File'
    import Textarea from '@/Shared/Textarea'
    import LoadingButton from '@/Shared/LoadingButton'
    import InfoMessage from '@/Shared/InfoMessage'

    export let errors

    $: $title = 'Soporte'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let sending = false
    let form = useForm({
        email: '',
        descripcion: '',
        archivo: null,
    })

    function submit() {
        $form.post(route('reportar-problemas.report'), {
            onStart: () => (sending = true),
            onFinish: () => (sending = false),
        })
    }
</script>

<AuthenticatedLayout>
    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8">
                <div class="mt-4">
                    <Input label="Correo electrónico institucional" id="email" type="email" class="mt-1" bind:value={$form.email} error={errors.email} required />
                    <InfoMessage message="Este atento al correo electrónico, allí se le enviará la respuesta en el menor tiempo posible." />
                </div>
                <div class="mt-4">
                    <Textarea label="Descripción" maxlength="40000" id="descripcion" error={errors.descripcion} bind:value={$form.descripcion} required />
                </div>
                <div class="mt-4">
                    <p>Por favor cargue una captura de pantalla (si son varias capturas de pantalla adjunte un pdf). Se recomienda usar la herramienta de recortes de Windows y por favor asegúrese que las capturas sean de buena calidad.</p>
                    <File type="file" accept="image/*,application/pdf" maxSize="5000" class="mt-1" bind:value={$form.archivo} error={errors?.archivo} required />
                    {#if $form.progress}
                        <progress value={$form.progress.percentage} max="100" class="mt-4">
                            {$form.progress.percentage}%
                        </progress>
                    {/if}
                </div>
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                <LoadingButton bind:loading={sending} class="btn-indigo" type="submit" bind:disabled={$form.autorizacion_datos}>Notificar a la mesa de ayuda</LoadingButton>
            </div>
        </form>
    </div>
</AuthenticatedLayout>
