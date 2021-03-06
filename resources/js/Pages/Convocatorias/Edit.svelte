<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { Inertia } from '@inertiajs/inertia'
    import { route, checkRole } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Shared/Input'
    import Label from '@/Shared/Label'
    import InputError from '@/Shared/InputError'
    import Button from '@/Shared/Button'
    import InfoMessage from '@/Shared/InfoMessage'
    import Textarea from '@/Shared/Textarea'
    import Password from '@/Shared/Password'
    import Switch from '@/Shared/Switch'
    import Select from '@/Shared/Select'
    import Dialog from '@/Shared/Dialog'

    export let errors
    export let convocatoria
    export let fases

    $: $title = convocatoria ? 'Convocatoria ' + convocatoria.year : null

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let dialogOpen = false
    let sending = false

    let formFase = useForm({
        fase: {
            value: convocatoria.fase,
            label: fases.find((item) => item.value == convocatoria.fase)?.label,
        },
    })

    function submitFase() {
        if (isSuperAdmin) {
            $formFase.put(route('convocatorias.update-fase', convocatoria.id), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }
    let form = useForm({
        descripcion: convocatoria.descripcion,
        esta_activa: convocatoria.esta_activa,
        visible: convocatoria.visible,
        fecha_finalizacion_fase: convocatoria.fecha_finalizacion_fase,
        hora_finalizacion_fase: convocatoria.hora_finalizacion_fase,
        min_fecha_inicio_proyectos_idi: convocatoria.min_fecha_inicio_proyectos_idi,
        max_fecha_finalizacion_proyectos_idi: convocatoria.max_fecha_finalizacion_proyectos_idi,
        min_fecha_inicio_proyectos_cultura: convocatoria.min_fecha_inicio_proyectos_cultura,
        max_fecha_finalizacion_proyectos_cultura: convocatoria.max_fecha_finalizacion_proyectos_cultura,
        min_fecha_inicio_proyectos_st: convocatoria.min_fecha_inicio_proyectos_st,
        max_fecha_finalizacion_proyectos_st: convocatoria.max_fecha_finalizacion_proyectos_st,
        min_fecha_inicio_proyectos_ta: convocatoria.min_fecha_inicio_proyectos_ta,
        max_fecha_finalizacion_proyectos_ta: convocatoria.max_fecha_finalizacion_proyectos_ta,
        min_fecha_inicio_proyectos_tp: convocatoria.min_fecha_inicio_proyectos_tp,
        max_fecha_finalizacion_proyectos_tp: convocatoria.max_fecha_finalizacion_proyectos_tp,
        mostrar_recomendaciones: convocatoria.mostrar_recomendaciones,
    })

    function submitInfo() {
        if (isSuperAdmin) {
            $form.put(route('convocatorias.update', convocatoria.id), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
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
                        {convocatoria.descripcion}
                    </a>
                </h1>
            </div>
        </div>
    </header>

    {#if convocatoria.tipo_convocatoria == 1 || convocatoria.tipo_convocatoria == 3}
        <div class="bg-white rounded shadow max-w-3xl mt-20">
            <form on:submit|preventDefault={submitFase}>
                <fieldset class="p-8" disabled={isSuperAdmin ? undefined : true}>
                    <div class="grid grid-cols-2">
                        <div>
                            <Label required class="mb-4" labelFor="fase" value="Fase" />
                        </div>
                        <div>
                            <Select id="fase" items={fases} bind:selectedValue={$formFase.fase} error={errors.fase} autocomplete="off" placeholder="Seleccione una fase" required />
                        </div>
                    </div>

                    <InfoMessage class="mt-10">
                        {#if $formFase.fase?.value == 1}
                            <strong>Tenga en cuenta:</strong> La fase de {$formFase.fase?.label.toLowerCase()} permitirá a los formuladores crear, modificar y eliminar proyectos.
                        {:else if $formFase.fase?.value == 2}
                            <strong>Tenga en cuenta:</strong> La fase de {$formFase.fase?.label.toLowerCase()} bloqueará a los formuladores las acciones de crear, modificar y eliminar proyectos.
                        {:else if $formFase.fase?.value == 3}
                            <strong>Tenga en cuenta:</strong> La fase de {$formFase.fase?.label.toLowerCase()} permitirá a los formuladores modificar aquellos proyectos que pueden ser subsanados y a los evaluadores se le bloqueará la acción de modificar las evaluaciones.
                        {:else if $formFase.fase?.value == 4}
                            <strong>Tenga en cuenta:</strong> La fase de {$formFase.fase?.label.toLowerCase()} bloqueará a los formuladores la acción de modificar aquellos proyectos que pasaron a etapa de subsanación y a los evaluadores se le habilitarán aquellas evaluaciones de proyectos subsanados.
                        {:else if $formFase.fase?.value == 5}
                            <strong>Tenga en cuenta:</strong> La fase de {$formFase.fase?.label.toLowerCase()} bloqueará a los formuladores la modificación de proyectos y a los evaluadores la modificación de las evaluaciones.
                        {/if}
                    </InfoMessage>
                </fieldset>
                <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                    {#if isSuperAdmin}
                        <Button class="btn-indigo ml-auto" type="submit">Editar fase</Button>
                    {/if}
                </div>
            </form>
        </div>

        <hr class="mt-10 mb-10" />
    {/if}

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submitInfo}>
            <fieldset class="p-8" disabled={isSuperAdmin ? undefined : true}>
                {#if $formFase.fase?.label}
                    <div class="mt-4 mb-20">
                        <div class="mt-4 ">
                            <Label required labelFor="fecha_finalizacion_fase" value="Fecha de finalización de la fase: {$formFase.fase?.label.toLowerCase()}" />
                            <input id="fecha_finalizacion_fase" type="date" class="mt-1 p-2" bind:value={$form.fecha_finalizacion_fase} required />
                        </div>
                    </div>
                    {#if errors.fecha_finalizacion_fase}
                        <InputError message={errors.fecha_finalizacion_fase} />
                    {/if}
                {/if}

                <div class="mt-4 mb-20">
                    <div class="mt-4 ">
                        <Label required labelFor="hora_finalizacion_fase" value="Hora límite" />
                        <input id="hora_finalizacion_fase" type="time" step="1" class="mt-1 p-2 border rounded border-gray-300" bind:value={$form.hora_finalizacion_fase} required />
                        <InputError message={errors.hora_finalizacion_fase} />
                    </div>
                </div>

                <div class="mt-4">
                    <Textarea label="Descripción" maxlength="40000" id="descripcion" error={errors.descripcion} bind:value={$form.descripcion} required />
                </div>

                <div class="mt-10 mb-20">
                    <Label required labelFor="esta_activa" value="¿Desea activar esta convocatoria? (Si la opción está habilitada permite a los usuarios formular proyectos. Tenga en cuenta que solo puede activar una convocatoria por tipo --Proyectos de convocatoria - Proyectos de ejecicio DEMO)" class="inline-block mb-4" />
                    <br />
                    <Switch bind:checked={$form.esta_activa} />
                    <InputError message={errors.esta_activa} />
                </div>

                <div class="mt-10 mb-20">
                    <Label required labelFor="visible" value="Defina la visibilidad de la convocatoria. (Si la opción está habilitada permite a los usuarios visualizar la convocatoria)" class="inline-block mb-4" />
                    <br />
                    <Switch bind:checked={$form.visible} onMessage="Visible" offMessage="Oculta" />
                    <InputError message={errors.visible} />
                </div>

                {#if convocatoria.tipo_convocatoria == 1 || convocatoria.tipo_convocatoria == 3}
                    <div class="mt-4 mb-20">
                        <Label required labelFor="mostrar_recomendaciones" value="¿Desea que el formulador visualice las recomendaciones hechas por los evaluadores?" class="inline-block mb-4" />
                        <br />
                        <Switch bind:checked={$form.mostrar_recomendaciones} />
                        <InputError message={errors.mostrar_recomendaciones} />
                    </div>
                {/if}

                {#if convocatoria.tipo_convocatoria == 1}
                    <div class="mt-20">
                        <p class="text-center">Fechas máximas de ejecución de proyectos I+D+i</p>
                        <div class="mt-4 flex items-start justify-around">
                            <div class="mt-4 flex {errors.min_fecha_inicio_proyectos_idi ? '' : 'items-center'}">
                                <Label required labelFor="min_fecha_inicio_proyectos_idi" class={errors.min_fecha_inicio_proyectos_idi ? 'top-3.5 relative' : ''} value="Del" />
                                <div class="ml-4">
                                    <input id="min_fecha_inicio_proyectos_idi" type="date" class="mt-1 p-2" bind:value={$form.min_fecha_inicio_proyectos_idi} required />
                                </div>
                            </div>
                            <div class="mt-4 flex {errors.max_fecha_finalizacion_proyectos_idi ? '' : 'items-center'}">
                                <Label required labelFor="max_fecha_finalizacion_proyectos" class={errors.max_fecha_finalizacion_proyectos_idi ? 'top-3.5 relative' : ''} value="hasta" />
                                <div class="ml-4">
                                    <input id="max_fecha_finalizacion_proyectos" type="date" class="mt-1 p-2" bind:value={$form.max_fecha_finalizacion_proyectos_idi} required />
                                </div>
                            </div>
                        </div>
                    </div>
                    {#if errors.min_fecha_inicio_proyectos_idi || errors.max_fecha_finalizacion_proyectos_idi}
                        <InputError message={errors.min_fecha_inicio_proyectos_idi || errors.max_fecha_finalizacion_proyectos_idi} />
                    {/if}
                {/if}

                {#if convocatoria.tipo_convocatoria == 1}
                    <div class="mt-20">
                        <p class="text-center">Fechas máximas de ejecución de proyectos Cultura de la innovación</p>
                        <div class="mt-4 flex items-start justify-around">
                            <div class="mt-4 flex {errors.min_fecha_inicio_proyectos_cultura ? '' : 'items-center'}">
                                <Label required labelFor="min_fecha_inicio_proyectos_cultura" class={errors.min_fecha_inicio_proyectos_cultura ? 'top-3.5 relative' : ''} value="Del" />
                                <div class="ml-4">
                                    <input id="min_fecha_inicio_proyectos_cultura" type="date" class="mt-1 p-2" bind:value={$form.min_fecha_inicio_proyectos_cultura} required />
                                </div>
                            </div>
                            <div class="mt-4 flex {errors.max_fecha_finalizacion_proyectos_cultura ? '' : 'items-center'}">
                                <Label required labelFor="max_fecha_finalizacion_proyectos_cultura" class={errors.max_fecha_finalizacion_proyectos_cultura ? 'top-3.5 relative' : ''} value="hasta" />
                                <div class="ml-4">
                                    <input id="max_fecha_finalizacion_proyectos_cultura" type="date" class="mt-1 p-2" bind:value={$form.max_fecha_finalizacion_proyectos_cultura} required />
                                </div>
                            </div>
                        </div>
                    </div>
                    {#if errors.min_fecha_inicio_proyectos_cultura || errors.max_fecha_finalizacion_proyectos_cultura}
                        <InputError message={errors.min_fecha_inicio_proyectos_cultura || errors.max_fecha_finalizacion_proyectos_cultura} />
                    {/if}
                {/if}

                {#if convocatoria.tipo_convocatoria == 1 || convocatoria.tipo_convocatoria == 3}
                    <div class="mt-20">
                        <p class="text-center">Fechas máximas de ejecución de proyectos Tecnoacademia</p>
                        <div class="mt-4 flex items-start justify-around">
                            <div class="mt-4 flex {errors.min_fecha_inicio_proyectos_ta ? '' : 'items-center'}">
                                <Label required labelFor="min_fecha_inicio_proyectos_ta" class={errors.min_fecha_inicio_proyectos_ta ? 'top-3.5 relative' : ''} value="Del" />
                                <div class="ml-4">
                                    <input id="min_fecha_inicio_proyectos_ta" type="date" class="mt-1 p-2" bind:value={$form.min_fecha_inicio_proyectos_ta} required />
                                </div>
                            </div>
                            <div class="mt-4 flex {errors.max_fecha_finalizacion_proyectos_ta ? '' : 'items-center'}">
                                <Label required labelFor="max_fecha_finalizacion_proyectos_ta" class={errors.max_fecha_finalizacion_proyectos_ta ? 'top-3.5 relative' : ''} value="hasta" />
                                <div class="ml-4">
                                    <input id="max_fecha_finalizacion_proyectos_ta" type="date" class="mt-1 p-2" bind:value={$form.max_fecha_finalizacion_proyectos_ta} required />
                                </div>
                            </div>
                        </div>
                    </div>
                    {#if errors.min_fecha_inicio_proyectos_ta || errors.max_fecha_finalizacion_proyectos_ta}
                        <InputError message={errors.min_fecha_inicio_proyectos_ta || errors.max_fecha_finalizacion_proyectos_ta} />
                    {/if}
                {/if}

                {#if convocatoria.tipo_convocatoria == 1 || convocatoria.tipo_convocatoria == 3}
                    <div class="mt-20">
                        <p class="text-center">Fechas máximas de ejecución de proyectos Tecnoparque</p>
                        <div class="mt-4 flex items-start justify-around">
                            <div class="mt-4 flex {errors.min_fecha_inicio_proyectos_tp ? '' : 'items-center'}">
                                <Label required labelFor="min_fecha_inicio_proyectos_tp" class={errors.min_fecha_inicio_proyectos_tp ? 'top-3.5 relative' : ''} value="Del" />
                                <div class="ml-4">
                                    <input id="min_fecha_inicio_proyectos_tp" type="date" class="mt-1 p-2" bind:value={$form.min_fecha_inicio_proyectos_tp} required />
                                </div>
                            </div>
                            <div class="mt-4 flex {errors.max_fecha_finalizacion_proyectos_tp ? '' : 'items-center'}">
                                <Label required labelFor="max_fecha_finalizacion_proyectos_tp" class={errors.max_fecha_finalizacion_proyectos_tp ? 'top-3.5 relative' : ''} value="hasta" />
                                <div class="ml-4">
                                    <input id="max_fecha_finalizacion_proyectos_tp" type="date" class="mt-1 p-2" bind:value={$form.max_fecha_finalizacion_proyectos_tp} required />
                                </div>
                            </div>
                        </div>
                    </div>
                    {#if errors.min_fecha_inicio_proyectos_tp || errors.max_fecha_finalizacion_proyectos_tp}
                        <InputError message={errors.min_fecha_inicio_proyectos_tp || errors.max_fecha_finalizacion_proyectos_tp} />
                    {/if}
                {/if}

                {#if convocatoria.tipo_convocatoria == 1}
                    <div class="mt-20">
                        <p class="text-center">Fechas máximas de ejecución de proyectos Servicios tecnológicos</p>
                        <div class="mt-4 flex items-start justify-around">
                            <div class="mt-4 flex {errors.min_fecha_inicio_proyectos_st ? '' : 'items-center'}">
                                <Label required labelFor="min_fecha_inicio_proyectos_st" class={errors.min_fecha_inicio_proyectos_st ? 'top-3.5 relative' : ''} value="Del" />
                                <div class="ml-4">
                                    <input id="min_fecha_inicio_proyectos_st" type="date" class="mt-1 p-2" bind:value={$form.min_fecha_inicio_proyectos_st} required />
                                </div>
                            </div>
                            <div class="mt-4 flex {errors.max_fecha_finalizacion_proyectos_st ? '' : 'items-center'}">
                                <Label required labelFor="max_fecha_finalizacion_proyectos_st" class={errors.max_fecha_finalizacion_proyectos_st ? 'top-3.5 relative' : ''} value="hasta" />
                                <div class="ml-4">
                                    <input id="max_fecha_finalizacion_proyectos_st" type="date" class="mt-1 p-2" bind:value={$form.max_fecha_finalizacion_proyectos_st} required />
                                </div>
                            </div>
                        </div>
                    </div>
                    {#if errors.min_fecha_inicio_proyectos_st || errors.max_fecha_finalizacion_proyectos_st}
                        <InputError message={errors.min_fecha_inicio_proyectos_st || errors.max_fecha_finalizacion_proyectos_st} />
                    {/if}
                {/if}
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if isSuperAdmin}
                    <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={(event) => (dialogOpen = true)}> Eliminar convocatoria </button>
                {/if}
                {#if isSuperAdmin}
                    <Button class="btn-indigo ml-auto" type="submit">Editar convocatoria</Button>
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
