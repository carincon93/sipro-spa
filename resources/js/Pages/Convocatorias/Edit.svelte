<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { Inertia } from '@inertiajs/inertia'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import Dialog from '@/Shared/Dialog'

    import Input from '@/Shared/Input'
    import Label from '@/Shared/Label'
    import InputError from '@/Shared/InputError'
    import Button from '@/Shared/Button'
    import LoadingButton from '@/Shared/LoadingButton'
    import Textarea from '@/Shared/Textarea'
    import Password from '@/Shared/Password'
    import Switch from '@/Shared/Switch'

    export let errors
    export let convocatoria

    $: $title = convocatoria ? 'Convocatoria ' + convocatoria.year : null

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let dialogOpen = false
    let sending = false
    let form = useForm({
        descripcion: convocatoria.descripcion,
        esta_activa: convocatoria.esta_activa,
        min_fecha_inicio_proyectos_idi: convocatoria.min_fecha_inicio_proyectos_idi,
        max_fecha_finalizacion_proyectos_idi: convocatoria.max_fecha_finalizacion_proyectos_idi,
        fecha_inicio_convocatoria_idi: convocatoria.fecha_inicio_convocatoria_idi,
        fecha_finalizacion_convocatoria_idi: convocatoria.fecha_finalizacion_convocatoria_idi,
        min_fecha_inicio_proyectos_cultura: convocatoria.min_fecha_inicio_proyectos_cultura,
        max_fecha_finalizacion_proyectos_cultura: convocatoria.max_fecha_finalizacion_proyectos_cultura,
        min_fecha_inicio_proyectos_st: convocatoria.min_fecha_inicio_proyectos_st,
        max_fecha_finalizacion_proyectos_st: convocatoria.max_fecha_finalizacion_proyectos_st,
        min_fecha_inicio_proyectos_tatp: convocatoria.min_fecha_inicio_proyectos_tatp,
        max_fecha_finalizacion_proyectos_tatp: convocatoria.max_fecha_finalizacion_proyectos_tatp,
        fecha_inicio_convocatoria_cultura: convocatoria.fecha_inicio_convocatoria_cultura,
        fecha_finalizacion_convocatoria_cultura: convocatoria.fecha_finalizacion_convocatoria_cultura,
        fecha_inicio_convocatoria_st: convocatoria.fecha_inicio_convocatoria_st,
        fecha_finalizacion_convocatoria_st: convocatoria.fecha_finalizacion_convocatoria_st,
        fecha_inicio_convocatoria_tatp: convocatoria.fecha_inicio_convocatoria_tatp,
        fecha_finalizacion_convocatoria_tatp: convocatoria.fecha_finalizacion_convocatoria_tatp,
    })

    function submit() {
        if (isSuperAdmin) {
            $form.put(route('convocatorias.update', convocatoria.id), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
            })
        }
    }

    let deleteForm = useForm({
        password: '',
    })

    function destroy() {
        if (isSuperAdmin) {
            $deleteForm.delete(route('convocatorias.destroy', [convocatoria.id]), {
                preserveScroll: true,
            })
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1 class="overflow-ellipsis overflow-hidden w-breadcrumb-ellipsis whitespace-nowrap">
                    {#if isSuperAdmin}
                        <a use:inertia href={route('convocatorias.index')} class="text-indigo-400 hover:text-indigo-600"> Convocatorias </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    <a use:inertia href={route('convocatorias.dashboard', convocatoria.id)} class="text-indigo-400 hover:text-indigo-600">
                        Convocatoria
                        {convocatoria.year}
                    </a>
                </h1>
            </div>
        </div>
    </header>

    {#if isSuperAdmin}
        <Button variant="raised" on:click={() => Inertia.visit(route('convocatorias.convocatoria-rol-sennova.index', convocatoria.id))} class="mb-4">Roles SENNOVA</Button>
    {/if}
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
                    <p class="text-center">Fecha de la conovocatoria Tecnoacademia-Tecnoparque</p>
                    <div class="mt-4 flex items-start justify-around">
                        <div class="mt-4 flex {errors.fecha_inicio_convocatoria_tatp ? '' : 'items-center'}">
                            <Label required labelFor="fecha_inicio_convocatoria_tatp" class={errors.fecha_inicio_convocatoria_tatp ? 'top-3.5 relative' : ''} value="Del" />
                            <div class="ml-4">
                                <Input id="fecha_inicio_convocatoria_tatp" type="date" class="mt-1" bind:value={$form.fecha_inicio_convocatoria_tatp} required />
                            </div>
                        </div>
                        <div class="mt-4 flex {errors.fecha_finalizacion_convocatoria_tatp ? '' : 'items-center'}">
                            <Label required labelFor="fecha_finalizacion_convocatoria_tatp" class={errors.fecha_finalizacion_convocatoria_tatp ? 'top-3.5 relative' : ''} value="hasta" />
                            <div class="ml-4">
                                <Input id="fecha_finalizacion_convocatoria_tatp" type="date" class="mt-1" bind:value={$form.fecha_finalizacion_convocatoria_tatp} required />
                            </div>
                        </div>
                    </div>
                </div>
                {#if errors.fecha_inicio_convocatoria_tatp || errors.fecha_finalizacion_convocatoria_tatp}
                    <InputError message={errors.fecha_inicio_convocatoria_tatp || errors.fecha_finalizacion_convocatoria_tatp} />
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
                    <p class="text-center">Fechas máximas de ejecución de proyectos Tecnoacademia-Tecnoparque</p>
                    <div class="mt-4 flex items-start justify-around">
                        <div class="mt-4 flex {errors.min_fecha_inicio_proyectos_tatp ? '' : 'items-center'}">
                            <Label required labelFor="min_fecha_inicio_proyectos_tatp" class={errors.min_fecha_inicio_proyectos_tatp ? 'top-3.5 relative' : ''} value="Del" />
                            <div class="ml-4">
                                <Input id="min_fecha_inicio_proyectos_tatp" type="date" class="mt-1" bind:value={$form.min_fecha_inicio_proyectos_tatp} required />
                            </div>
                        </div>
                        <div class="mt-4 flex {errors.max_fecha_finalizacion_proyectos_tatp ? '' : 'items-center'}">
                            <Label required labelFor="max_fecha_finalizacion_proyectos_tatp" class={errors.max_fecha_finalizacion_proyectos_tatp ? 'top-3.5 relative' : ''} value="hasta" />
                            <div class="ml-4">
                                <Input id="max_fecha_finalizacion_proyectos_tatp" type="date" class="mt-1" bind:value={$form.max_fecha_finalizacion_proyectos_tatp} required />
                            </div>
                        </div>
                    </div>
                </div>
                {#if errors.min_fecha_inicio_proyectos_tatp || errors.max_fecha_finalizacion_proyectos_tatp}
                    <InputError message={errors.min_fecha_inicio_proyectos_tatp || errors.max_fecha_finalizacion_proyectos_tatp} />
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
                    <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={(event) => (dialogOpen = true)}> Eliminar convocatoria </button>
                {/if}
                {#if isSuperAdmin}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Editar convocatoria</LoadingButton>
                {/if}
            </div>
        </form>
    </div>
    <Dialog bind:open={dialogOpen}>
        <div slot="title" class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            Eliminar convocatoria
        </div>
        <div slot="content">
            <p>
                ¿Está seguro (a) que desea eliminar esta convocatoria?
                <br />
                Una vez eliminada la convocatoria, todos los proyectos y datos asociados se eliminarán de forma permanente.
            </p>

            <form on:submit|preventDefault={destroy} id="delete-convocatoria" class="mt-20 mb-28">
                <Label labelFor="password" value="Ingrese su contraseña para confirmar que desea eliminar permanentemente esta convocatoria" class="mb-4" />
                <Password id="password" class="w-full" bind:value={$deleteForm.password} error={errors.password} required autocomplete="current-password" />
            </form>
        </div>
        <div slot="actions">
            <div class="p-4">
                <Button on:click={(event) => (dialogOpen = false)} variant={null}>Cancelar</Button>
                <Button variant="raised" form="delete-convocatoria">Confirmar</Button>
            </div>
        </div>
    </Dialog>
</AuthenticatedLayout>
