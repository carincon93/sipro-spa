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

    $: $title = 'Crear convocatoria'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    // prettier-ignore
    let canIndexConvocatorias = authUser.can.find((element) => element == 'convocatorias.index') == 'convocatorias.index'
    // prettier-ignore
    let canShowConvocatorias = authUser.can.find((element) => element == 'convocatorias.show') == 'convocatorias.show'
    // prettier-ignore
    let canCreateConvocatorias = authUser.can.find((element) => element == 'convocatorias.create') == 'convocatorias.create'
    // prettier-ignore
    let canEditConvocatorias = authUser.can.find((element) => element == 'convocatorias.edit') == 'convocatorias.edit'
    // prettier-ignore
    let canDestroyConvocatorias = authUser.can.find((element) => element == 'convocatorias.destroy') == 'convocatorias.destroy'

    let sending = false
    let form = useForm({
        descripcion: '',
        fecha_finalizacion: '',
        fecha_inicio: '',
        esta_activa: false,
        min_fecha_inicio_proyectos: '',
        max_fecha_finalizacion_proyectos: '',
    })

    function submit() {
        if (canCreateConvocatorias || isSuperAdmin) {
            $form.post(route('convocatorias.store'), {
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
                    {#if canIndexConvocatorias || canCreateConvocatorias || isSuperAdmin}
                        <a
                            use:inertia
                            href={route('convocatorias.index')}
                            class="text-indigo-400 hover:text-indigo-600"
                        >
                            Convocatorias
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
                disabled={canCreateConvocatorias || isSuperAdmin
                    ? undefined
                    : true}
            >
                <div class="mt-4 mb-20">
                    <p class="text-center">Fecha de la conovicatoria</p>
                    <div class="mt-4 flex items-start justify-around">
                        <div
                            class="mt-4 flex {errors.fecha_inicio
                                ? ''
                                : 'items-center'}"
                        >
                            <Label
                                required
                                labelFor="fecha_inicio"
                                class={errors.fecha_inicio
                                    ? 'top-3.5 relative'
                                    : ''}
                                value="Del"
                            />
                            <div class="ml-4">
                                <Input
                                    id="fecha_inicio"
                                    type="date"
                                    class="mt-1 block w-full"
                                    bind:value={$form.fecha_inicio}
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
                {#if errors.fecha_inicio || errors.fecha_finalizacion}
                    <InputError
                        message={errors.fecha_inicio ||
                            errors.fecha_finalizacion}
                    />
                {/if}

                <hr />

                <div class="mt-4">
                    <Label
                        required
                        class="mb-4"
                        labelFor="descripcion"
                        value="Descripción"
                    />
                    <Textarea
                        rows="4"
                        id="descripcion"
                        error={errors.descripcion}
                        bind:value={$form.descripcion}
                        required
                    />
                </div>

                <div class="mt-4 mb-20">
                    <Label
                        required
                        labelFor="esta_activa"
                        value="Desea activar está convocatoria?"
                        class="inline-block mb-4"
                    />
                    <br />
                    <Switch bind:checked={$form.esta_activa} />
                    <InputError message={errors.esta_activa} />
                </div>

                <hr />

                <div class="mt-4">
                    <p class="text-center">
                        Rango de fechas de ejecución de proyectos
                    </p>
                    <div class="mt-4 flex items-start justify-around">
                        <div
                            class="mt-4 flex {errors.min_fecha_inicio_proyectos
                                ? ''
                                : 'items-center'}"
                        >
                            <Label
                                required
                                labelFor="min_fecha_inicio_proyectos"
                                class={errors.min_fecha_inicio_proyectos
                                    ? 'top-3.5 relative'
                                    : ''}
                                value="Del"
                            />
                            <div class="ml-4">
                                <Input
                                    id="min_fecha_inicio_proyectos"
                                    type="date"
                                    class="mt-1 block w-full"
                                    bind:value={$form.min_fecha_inicio_proyectos}
                                    required
                                />
                            </div>
                        </div>
                        <div
                            class="mt-4 flex {errors.max_fecha_finalizacion_proyectos
                                ? ''
                                : 'items-center'}"
                        >
                            <Label
                                required
                                labelFor="max_fecha_finalizacion_proyectos"
                                class={errors.max_fecha_finalizacion_proyectos
                                    ? 'top-3.5 relative'
                                    : ''}
                                value="hasta"
                            />
                            <div class="ml-4">
                                <Input
                                    id="max_fecha_finalizacion_proyectos"
                                    type="date"
                                    class="mt-1 block w-full"
                                    bind:value={$form.max_fecha_finalizacion_proyectos}
                                    required
                                />
                            </div>
                        </div>
                    </div>
                </div>
                {#if errors.min_fecha_inicio_proyectos || errors.max_fecha_finalizacion_proyectos}
                    <InputError
                        message={errors.min_fecha_inicio_proyectos ||
                            errors.max_fecha_finalizacion_proyectos}
                    />
                {/if}
            </fieldset>
            <div
                class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0"
            >
                {#if canCreateConvocatorias || isSuperAdmin}
                    <LoadingButton
                        loading={sending}
                        class="btn-indigo ml-auto"
                        type="submit"
                    >
                        Crear convocatoria
                    </LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
