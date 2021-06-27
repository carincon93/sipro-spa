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
        esta_activa: false,
        min_fecha_inicio_proyectos_idi: '',
        max_fecha_finalizacion_proyectos_idi: '',
        fecha_inicio_convocatoria_idi: '',
        fecha_finalizacion_convocatoria_idi: '',
        min_fecha_inicio_proyectos_cultura: '',
        max_fecha_finalizacion_proyectos_cultura: '',
        min_fecha_inicio_proyectos_st: '',
        max_fecha_finalizacion_proyectos_st: '',
        min_fecha_inicio_proyectos_ta: '',
        min_fecha_inicio_proyectos_tp: '',
        max_fecha_finalizacion_proyectos_ta: '',
        max_fecha_finalizacion_proyectos_tp: '',
        fecha_inicio_convocatoria_cultura: '',
        fecha_finalizacion_convocatoria_cultura: '',
        fecha_inicio_convocatoria_st: '',
        fecha_finalizacion_convocatoria_st: '',
        fecha_inicio_convocatoria_ta: '',
        fecha_inicio_convocatoria_tp: '',
        fecha_finalizacion_convocatoria_ta: '',
        fecha_finalizacion_convocatoria_tp: '',
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
                    <p class="text-center">Fecha de la conovocatoria I+D+i</p>
                    <div class="mt-4 flex items-start justify-around">
                        <div class="mt-4 flex {errors.fecha_inicio_convocatoria_idi ? '' : 'items-center'}">
                            <Label required labelFor="fecha_inicio_convocatoria_idi" class={errors.fecha_inicio_convocatoria_idi ? 'top-3.5 relative' : ''} value="Del" />
                            <div class="ml-4">
                                <Input id="fecha_inicio_convocatoria_idi" type="date" class="mt-1" bind:value={$form.fecha_inicio_convocatoria_idi} required />
                            </div>
                        </div>
                        <div class="mt-4 flex {errors.fecha_finalizacion_convocatoria_idi ? '' : 'items-center'}">
                            <Label required labelFor="fecha_finalizacion_convocatoria_idi" class={errors.fecha_finalizacion_convocatoria_idi ? 'top-3.5 relative' : ''} value="hasta" />
                            <div class="ml-4">
                                <Input id="fecha_finalizacion_convocatoria_idi" type="date" class="mt-1" bind:value={$form.fecha_finalizacion_convocatoria_idi} required />
                            </div>
                        </div>
                    </div>
                </div>
                {#if errors.fecha_inicio_convocatoria_idi || errors.fecha_finalizacion_convocatoria_idi}
                    <InputError message={errors.fecha_inicio_convocatoria_idi || errors.fecha_finalizacion_convocatoria_idi} />
                {/if}

                <hr />

                <div class="mt-4 mb-20">
                    <p class="text-center">Fecha de la conovocatoria Cultura de la innovación</p>
                    <div class="mt-4 flex items-start justify-around">
                        <div class="mt-4 flex {errors.fecha_inicio_convocatoria_cultura ? '' : 'items-center'}">
                            <Label required labelFor="fecha_inicio_convocatoria_cultura" class={errors.fecha_inicio_convocatoria_cultura ? 'top-3.5 relative' : ''} value="Del" />
                            <div class="ml-4">
                                <Input id="fecha_inicio_convocatoria_cultura" type="date" class="mt-1" bind:value={$form.fecha_inicio_convocatoria_cultura} required />
                            </div>
                        </div>
                        <div class="mt-4 flex {errors.fecha_finalizacion_convocatoria_cultura ? '' : 'items-center'}">
                            <Label required labelFor="fecha_finalizacion_convocatoria_cultura" class={errors.fecha_finalizacion_convocatoria_cultura ? 'top-3.5 relative' : ''} value="hasta" />
                            <div class="ml-4">
                                <Input id="fecha_finalizacion_convocatoria_cultura" type="date" class="mt-1" bind:value={$form.fecha_finalizacion_convocatoria_cultura} required />
                            </div>
                        </div>
                    </div>
                </div>
                {#if errors.fecha_inicio_convocatoria_cultura || errors.fecha_finalizacion_convocatoria_cultura}
                    <InputError message={errors.fecha_inicio_convocatoria_cultura || errors.fecha_finalizacion_convocatoria_cultura} />
                {/if}

                <hr />

                <div class="mt-4 mb-20">
                    <p class="text-center">Fecha de la conovocatoria Tecnoacademia</p>
                    <div class="mt-4 flex items-start justify-around">
                        <div class="mt-4 flex {errors.fecha_inicio_convocatoria_ta ? '' : 'items-center'}">
                            <Label required labelFor="fecha_inicio_convocatoria_ta" class={errors.fecha_inicio_convocatoria_ta ? 'top-3.5 relative' : ''} value="Del" />
                            <div class="ml-4">
                                <Input id="fecha_inicio_convocatoria_ta" type="date" class="mt-1" bind:value={$form.fecha_inicio_convocatoria_ta} required />
                            </div>
                        </div>
                        <div class="mt-4 flex {errors.fecha_finalizacion_convocatoria_ta ? '' : 'items-center'}">
                            <Label required labelFor="fecha_finalizacion_convocatoria_ta" class={errors.fecha_finalizacion_convocatoria_ta ? 'top-3.5 relative' : ''} value="hasta" />
                            <div class="ml-4">
                                <Input id="fecha_finalizacion_convocatoria_ta" type="date" class="mt-1" bind:value={$form.fecha_finalizacion_convocatoria_ta} required />
                            </div>
                        </div>
                    </div>
                </div>
                {#if errors.fecha_inicio_convocatoria_ta || errors.fecha_finalizacion_convocatoria_ta}
                    <InputError message={errors.fecha_inicio_convocatoria_ta || errors.fecha_finalizacion_convocatoria_ta} />
                {/if}

                <hr />

                <div class="mt-4 mb-20">
                    <p class="text-center">Fecha de la conovocatoria Tecnoparque</p>
                    <div class="mt-4 flex items-start justify-around">
                        <div class="mt-4 flex {errors.fecha_inicio_convocatoria_tp ? '' : 'items-center'}">
                            <Label required labelFor="fecha_inicio_convocatoria_tp" class={errors.fecha_inicio_convocatoria_tp ? 'top-3.5 relative' : ''} value="Del" />
                            <div class="ml-4">
                                <Input id="fecha_inicio_convocatoria_tp" type="date" class="mt-1" bind:value={$form.fecha_inicio_convocatoria_tp} required />
                            </div>
                        </div>
                        <div class="mt-4 flex {errors.fecha_finalizacion_convocatoria_tp ? '' : 'items-center'}">
                            <Label required labelFor="fecha_finalizacion_convocatoria_tp" class={errors.fecha_finalizacion_convocatoria_tp ? 'top-3.5 relative' : ''} value="hasta" />
                            <div class="ml-4">
                                <Input id="fecha_finalizacion_convocatoria_tp" type="date" class="mt-1" bind:value={$form.fecha_finalizacion_convocatoria_tp} required />
                            </div>
                        </div>
                    </div>
                </div>
                {#if errors.fecha_inicio_convocatoria_tp || errors.fecha_finalizacion_convocatoria_tp}
                    <InputError message={errors.fecha_inicio_convocatoria_tp || errors.fecha_finalizacion_convocatoria_tp} />
                {/if}

                <hr />

                <div class="mt-4 mb-20">
                    <p class="text-center">Fecha de la conovocatoria Servicios tecnológicos</p>
                    <div class="mt-4 flex items-start justify-around">
                        <div class="mt-4 flex {errors.fecha_inicio_convocatoria_st ? '' : 'items-center'}">
                            <Label required labelFor="fecha_inicio_convocatoria_st" class={errors.fecha_inicio_convocatoria_st ? 'top-3.5 relative' : ''} value="Del" />
                            <div class="ml-4">
                                <Input id="fecha_inicio_convocatoria_st" type="date" class="mt-1" bind:value={$form.fecha_inicio_convocatoria_st} required />
                            </div>
                        </div>
                        <div class="mt-4 flex {errors.fecha_finalizacion_convocatoria_st ? '' : 'items-center'}">
                            <Label required labelFor="fecha_finalizacion_convocatoria_st" class={errors.fecha_finalizacion_convocatoria_st ? 'top-3.5 relative' : ''} value="hasta" />
                            <div class="ml-4">
                                <Input id="fecha_finalizacion_convocatoria_st" type="date" class="mt-1" bind:value={$form.fecha_finalizacion_convocatoria_st} required />
                            </div>
                        </div>
                    </div>
                </div>
                {#if errors.fecha_inicio_convocatoria_st || errors.fecha_finalizacion_convocatoria_st}
                    <InputError message={errors.fecha_inicio_convocatoria_st || errors.fecha_finalizacion_convocatoria_st} />
                {/if}

                <hr />

                <div class="mt-4">
                    <Textarea label="Descripción" maxlength="40000" id="descripcion" error={errors.descripcion} bind:value={$form.descripcion} required />
                </div>

                <div class="mt-4 mb-20">
                    <Label required labelFor="esta_activa" value="¿Desea activar está convocatoria?" class="inline-block mb-4" />
                    <br />
                    <Switch bind:checked={$form.esta_activa} />
                    <InputError message={errors.esta_activa} />
                </div>

                <hr />

                <div class="mt-20">
                    <p class="text-center">Fechas máximas de ejecución de proyectos I+D+i</p>
                    <div class="mt-4 flex items-start justify-around">
                        <div class="mt-4 flex {errors.min_fecha_inicio_proyectos_idi ? '' : 'items-center'}">
                            <Label required labelFor="min_fecha_inicio_proyectos_idi" class={errors.min_fecha_inicio_proyectos_idi ? 'top-3.5 relative' : ''} value="Del" />
                            <div class="ml-4">
                                <Input id="min_fecha_inicio_proyectos_idi" type="date" class="mt-1" bind:value={$form.min_fecha_inicio_proyectos_idi} required />
                            </div>
                        </div>
                        <div class="mt-4 flex {errors.max_fecha_finalizacion_proyectos_idi ? '' : 'items-center'}">
                            <Label required labelFor="max_fecha_finalizacion_proyectos" class={errors.max_fecha_finalizacion_proyectos_idi ? 'top-3.5 relative' : ''} value="hasta" />
                            <div class="ml-4">
                                <Input id="max_fecha_finalizacion_proyectos" type="date" class="mt-1" bind:value={$form.max_fecha_finalizacion_proyectos_idi} required />
                            </div>
                        </div>
                    </div>
                </div>
                {#if errors.min_fecha_inicio_proyectos_idi || errors.max_fecha_finalizacion_proyectos_idi}
                    <InputError message={errors.min_fecha_inicio_proyectos_idi || errors.max_fecha_finalizacion_proyectos_idi} />
                {/if}

                <div class="mt-20">
                    <p class="text-center">Fechas máximas de ejecución de proyectos Cultura de la innovación</p>
                    <div class="mt-4 flex items-start justify-around">
                        <div class="mt-4 flex {errors.min_fecha_inicio_proyectos_cultura ? '' : 'items-center'}">
                            <Label required labelFor="min_fecha_inicio_proyectos_cultura" class={errors.min_fecha_inicio_proyectos_cultura ? 'top-3.5 relative' : ''} value="Del" />
                            <div class="ml-4">
                                <Input id="min_fecha_inicio_proyectos_cultura" type="date" class="mt-1" bind:value={$form.min_fecha_inicio_proyectos_cultura} required />
                            </div>
                        </div>
                        <div class="mt-4 flex {errors.max_fecha_finalizacion_proyectos_cultura ? '' : 'items-center'}">
                            <Label required labelFor="max_fecha_finalizacion_proyectos_cultura" class={errors.max_fecha_finalizacion_proyectos_cultura ? 'top-3.5 relative' : ''} value="hasta" />
                            <div class="ml-4">
                                <Input id="max_fecha_finalizacion_proyectos_cultura" type="date" class="mt-1" bind:value={$form.max_fecha_finalizacion_proyectos_cultura} required />
                            </div>
                        </div>
                    </div>
                </div>
                {#if errors.min_fecha_inicio_proyectos_cultura || errors.max_fecha_finalizacion_proyectos_cultura}
                    <InputError message={errors.min_fecha_inicio_proyectos_cultura || errors.max_fecha_finalizacion_proyectos_cultura} />
                {/if}

                <div class="mt-20">
                    <p class="text-center">Fechas máximas de ejecución de proyectos Tecnoacademia</p>
                    <div class="mt-4 flex items-start justify-around">
                        <div class="mt-4 flex {errors.min_fecha_inicio_proyectos_ta ? '' : 'items-center'}">
                            <Label required labelFor="min_fecha_inicio_proyectos_ta" class={errors.min_fecha_inicio_proyectos_ta ? 'top-3.5 relative' : ''} value="Del" />
                            <div class="ml-4">
                                <Input id="min_fecha_inicio_proyectos_ta" type="date" class="mt-1" bind:value={$form.min_fecha_inicio_proyectos_ta} required />
                            </div>
                        </div>
                        <div class="mt-4 flex {errors.max_fecha_finalizacion_proyectos_ta ? '' : 'items-center'}">
                            <Label required labelFor="max_fecha_finalizacion_proyectos_ta" class={errors.max_fecha_finalizacion_proyectos_ta ? 'top-3.5 relative' : ''} value="hasta" />
                            <div class="ml-4">
                                <Input id="max_fecha_finalizacion_proyectos_ta" type="date" class="mt-1" bind:value={$form.max_fecha_finalizacion_proyectos_ta} required />
                            </div>
                        </div>
                    </div>
                </div>
                {#if errors.min_fecha_inicio_proyectos_ta || errors.max_fecha_finalizacion_proyectos_ta}
                    <InputError message={errors.min_fecha_inicio_proyectos_ta || errors.max_fecha_finalizacion_proyectos_ta} />
                {/if}

                <div class="mt-20">
                    <p class="text-center">Fechas máximas de ejecución de proyectos Tecnoparque</p>
                    <div class="mt-4 flex items-start justify-around">
                        <div class="mt-4 flex {errors.min_fecha_inicio_proyectos_tp ? '' : 'items-center'}">
                            <Label required labelFor="min_fecha_inicio_proyectos_tp" class={errors.min_fecha_inicio_proyectos_tp ? 'top-3.5 relative' : ''} value="Del" />
                            <div class="ml-4">
                                <Input id="min_fecha_inicio_proyectos_tp" type="date" class="mt-1" bind:value={$form.min_fecha_inicio_proyectos_tp} required />
                            </div>
                        </div>
                        <div class="mt-4 flex {errors.max_fecha_finalizacion_proyectos_tp ? '' : 'items-center'}">
                            <Label required labelFor="max_fecha_finalizacion_proyectos_tp" class={errors.max_fecha_finalizacion_proyectos_tp ? 'top-3.5 relative' : ''} value="hasta" />
                            <div class="ml-4">
                                <Input id="max_fecha_finalizacion_proyectos_tp" type="date" class="mt-1" bind:value={$form.max_fecha_finalizacion_proyectos_tp} required />
                            </div>
                        </div>
                    </div>
                </div>
                {#if errors.min_fecha_inicio_proyectos_tp || errors.max_fecha_finalizacion_proyectos_tp}
                    <InputError message={errors.min_fecha_inicio_proyectos_tp || errors.max_fecha_finalizacion_proyectos_tp} />
                {/if}

                <div class="mt-20">
                    <p class="text-center">Fechas máximas de ejecución de proyectos Servicios tecnológicos</p>
                    <div class="mt-4 flex items-start justify-around">
                        <div class="mt-4 flex {errors.min_fecha_inicio_proyectos_st ? '' : 'items-center'}">
                            <Label required labelFor="min_fecha_inicio_proyectos_st" class={errors.min_fecha_inicio_proyectos_st ? 'top-3.5 relative' : ''} value="Del" />
                            <div class="ml-4">
                                <Input id="min_fecha_inicio_proyectos_st" type="date" class="mt-1" bind:value={$form.min_fecha_inicio_proyectos_st} required />
                            </div>
                        </div>
                        <div class="mt-4 flex {errors.max_fecha_finalizacion_proyectos_st ? '' : 'items-center'}">
                            <Label required labelFor="max_fecha_finalizacion_proyectos_st" class={errors.max_fecha_finalizacion_proyectos_st ? 'top-3.5 relative' : ''} value="hasta" />
                            <div class="ml-4">
                                <Input id="max_fecha_finalizacion_proyectos_st" type="date" class="mt-1" bind:value={$form.max_fecha_finalizacion_proyectos_st} required />
                            </div>
                        </div>
                    </div>
                </div>
                {#if errors.min_fecha_inicio_proyectos_st || errors.max_fecha_finalizacion_proyectos_st}
                    <InputError message={errors.min_fecha_inicio_proyectos_st || errors.max_fecha_finalizacion_proyectos_st} />
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
