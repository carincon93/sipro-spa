<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Components/Input'
    import Label from '@/Components/Label'
    import InputError from '@/Components/InputError'
    import LoadingButton from '@/Components/LoadingButton'
    import Textarea from '@/Components/Textarea'
    import Switch from '@/Components/Switch'

    export let errors

    $: $title = $_('Create') + ' ' + $_('Calls.singular').toLowerCase()

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    let canIndexCalls =
        authUser.can.find((element) => element == 'calls.index') ==
        'calls.index'
    let canShowCalls =
        authUser.can.find((element) => element == 'calls.show') == 'calls.show'
    let canCreateCalls =
        authUser.can.find((element) => element == 'calls.create') ==
        'calls.create'
    let canEditCalls =
        authUser.can.find((element) => element == 'calls.edit') == 'calls.edit'
    let canDestroyCalls =
        authUser.can.find((element) => element == 'calls.destroy') ==
        'calls.delete'

    let sending = false
    let form = useForm({
        description: '',
        fecha_finalizacion: '',
        fecha_incio: '',
        active: false,
        project_fecha_incio: '',
        project_fecha_finalizacion: '',
    })

    function submit() {
        if (canCreateCalls || isSuperAdmin) {
            $form.post(route('calls.store'), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
            })
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div
            class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6"
        >
            <div>
                <h1>
                    {#if canIndexCalls || canCreateCalls || isSuperAdmin}
                        <a
                            use:inertia
                            href={route('calls.index')}
                            class="text-indigo-400 hover:text-indigo-600"
                        >
                            {$_('Calls.plural')}
                        </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    Crear
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset
                class="p-8"
                disabled={canCreateCalls || isSuperAdmin ? undefined : true}
            >
                <div class="mt-4 mb-20">
                    <p class="text-center">Fecha de la conovicatoria</p>
                    <div class="mt-4 flex items-start justify-around">
                        <div
                            class="mt-4 flex {errors.fecha_incio
                                ? ''
                                : 'items-center'}"
                        >
                            <Label
                                required
                                labelFor="fecha_incio"
                                class={errors.fecha_incio
                                    ? 'top-3.5 relative'
                                    : ''}
                                value="Del"
                            />
                            <div class="ml-4">
                                <Input
                                    id="fecha_incio"
                                    type="date"
                                    class="mt-1 block w-full"
                                    bind:value={$form.fecha_incio}
                                    required
                                />
                            </div>
                        </div>
                        <div
                            class="mt-4 flex {errors.fecha_finalizacion
                                ? ''
                                : 'items-center'}"
                        >
                            <Label
                                required
                                labelFor="fecha_finalizacion"
                                class={errors.fecha_finalizacion
                                    ? 'top-3.5 relative'
                                    : ''}
                                value="hasta"
                            />
                            <div class="ml-4">
                                <Input
                                    id="fecha_finalizacion"
                                    type="date"
                                    class="mt-1 block w-full"
                                    bind:value={$form.fecha_finalizacion}
                                    required
                                />
                            </div>
                        </div>
                    </div>
                </div>
                {#if errors.fecha_incio || errors.fecha_finalizacion}
                    <InputError
                        message={errors.fecha_incio ||
                            errors.fecha_finalizacion}
                    />
                {/if}

                <hr />

                <div class="mt-4">
                    <Label
                        required
                        class="mb-4"
                        labelFor="description"
                        value="Descripción"
                    />
                    <Textarea
                        rows="4"
                        id="description"
                        error={errors.description}
                        bind:value={$form.description}
                        required
                    />
                </div>

                <div class="mt-4 mb-20">
                    <Label
                        required
                        labelFor="active"
                        value="Desea activar está convocatoria?"
                        class="inline-block mb-4"
                    />
                    <br />
                    <Switch bind:checked={$form.active} />
                    <InputError message={errors.active} />
                </div>

                <hr />

                <div class="mt-4">
                    <p class="text-center">Fecha de ejecución de proyectos</p>
                    <div class="mt-4 flex items-start justify-around">
                        <div
                            class="mt-4 flex {errors.project_fecha_incio
                                ? ''
                                : 'items-center'}"
                        >
                            <Label
                                required
                                labelFor="project_fecha_incio"
                                class={errors.project_fecha_incio
                                    ? 'top-3.5 relative'
                                    : ''}
                                value="Del"
                            />
                            <div class="ml-4">
                                <Input
                                    id="project_fecha_incio"
                                    type="date"
                                    class="mt-1 block w-full"
                                    bind:value={$form.project_fecha_incio}
                                    required
                                />
                            </div>
                        </div>
                        <div
                            class="mt-4 flex {errors.project_fecha_finalizacion
                                ? ''
                                : 'items-center'}"
                        >
                            <Label
                                required
                                labelFor="project_fecha_finalizacion"
                                class={errors.project_fecha_finalizacion
                                    ? 'top-3.5 relative'
                                    : ''}
                                value="hasta"
                            />
                            <div class="ml-4">
                                <Input
                                    id="project_fecha_finalizacion"
                                    type="date"
                                    class="mt-1 block w-full"
                                    bind:value={$form.project_fecha_finalizacion}
                                    required
                                />
                            </div>
                        </div>
                    </div>
                </div>
                {#if errors.project_fecha_incio || errors.project_fecha_finalizacion}
                    <InputError
                        message={errors.project_fecha_incio ||
                            errors.project_fecha_finalizacion}
                    />
                {/if}
            </fieldset>
            <div
                class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0"
            >
                {#if canCreateCalls || isSuperAdmin}
                    <LoadingButton
                        loading={sending}
                        class="btn-indigo ml-auto"
                        type="submit"
                    >
                        Crear
                        {$_('Calls.singular')}
                    </LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
