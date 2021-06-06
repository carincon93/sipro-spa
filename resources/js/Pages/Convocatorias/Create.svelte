<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Shared/Input'
    import Label from '@/Shared/Label'
    import InputError from '@/Shared/InputError'
    import LoadingButton from '@/Shared/LoadingButton'
    import Textarea from '@/Shared/Textarea'
    import Switch from '@/Shared/Switch'

    export let errors

    $: $title = 'Crear convocatoria'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

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
        if (isSuperAdmin) {
            $form.post(route('convocatorias.store'), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
            })
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    {#if isSuperAdmin}
                        <a use:inertia href={route('convocatorias.index')} class="text-indigo-400 hover:text-indigo-600"> Convocatorias </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    Crear
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={isSuperAdmin ? undefined : true}>
                <div class="mt-4 mb-20">
                    <p class="text-center">Fecha de la conovicatoria</p>
                    <div class="mt-4 flex items-start justify-around">
                        <div class="mt-4 flex {errors.fecha_inicio ? '' : 'items-center'}">
                            <Label required labelFor="fecha_inicio" class={errors.fecha_inicio ? 'top-3.5 relative' : ''} value="Del" />
                            <div class="ml-4">
                                <Input id="fecha_inicio" type="date" class="mt-1" bind:value={$form.fecha_inicio} required />
                            </div>
                        </div>
                        <div class="mt-4 flex {errors.fecha_finalizacion ? '' : 'items-center'}">
                            <Label required labelFor="fecha_finalizacion" class={errors.fecha_finalizacion ? 'top-3.5 relative' : ''} value="hasta" />
                            <div class="ml-4">
                                <Input id="fecha_finalizacion" type="date" class="mt-1" bind:value={$form.fecha_finalizacion} required />
                            </div>
                        </div>
                    </div>
                </div>
                {#if errors.fecha_inicio || errors.fecha_finalizacion}
                    <InputError message={errors.fecha_inicio || errors.fecha_finalizacion} />
                {/if}

                <hr />

                <div class="mt-4">
                    <Textarea label="Descripción" maxlength="40000" id="descripcion" error={errors.descripcion} bind:value={$form.descripcion} required />
                </div>

                <div class="mt-4 mb-20">
                    <Label required labelFor="esta_activa" value="Desea activar está convocatoria?" class="inline-block mb-4" />
                    <br />
                    <Switch bind:checked={$form.esta_activa} />
                    <InputError message={errors.esta_activa} />
                </div>

                <hr />

                <div class="mt-4">
                    <p class="text-center">Rango de fechas de ejecución de proyectos</p>
                    <div class="mt-4 flex items-start justify-around">
                        <div class="mt-4 flex {errors.min_fecha_inicio_proyectos ? '' : 'items-center'}">
                            <Label required labelFor="min_fecha_inicio_proyectos" class={errors.min_fecha_inicio_proyectos ? 'top-3.5 relative' : ''} value="Del" />
                            <div class="ml-4">
                                <input id="min_fecha_inicio_proyectos" type="date" class="mt-1" bind:value={$form.min_fecha_inicio_proyectos} required />
                            </div>
                        </div>
                        <div class="mt-4 flex {errors.max_fecha_finalizacion_proyectos ? '' : 'items-center'}">
                            <Label required labelFor="max_fecha_finalizacion_proyectos" class={errors.max_fecha_finalizacion_proyectos ? 'top-3.5 relative' : ''} value="hasta" />
                            <div class="ml-4">
                                <input id="max_fecha_finalizacion_proyectos" type="date" class="mt-1" bind:value={$form.max_fecha_finalizacion_proyectos} required />
                            </div>
                        </div>
                    </div>
                </div>
                {#if errors.min_fecha_inicio_proyectos || errors.max_fecha_finalizacion_proyectos}
                    <InputError message={errors.min_fecha_inicio_proyectos || errors.max_fecha_finalizacion_proyectos} />
                {/if}
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if isSuperAdmin}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Crear convocatoria</LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
