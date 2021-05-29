<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { Inertia } from '@inertiajs/inertia'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import Dialog from '@/Components/Dialog'

    import Input from '@/Components/Input'
    import Label from '@/Components/Label'
    import InputError from '@/Components/InputError'
    import Button from '@/Components/Button'
    import LoadingButton from '@/Components/LoadingButton'
    import Textarea from '@/Components/Textarea'
    import Switch from '@/Components/Switch'

    export let errors
    export let convocatoria

    $: $title = convocatoria ? 'Convocatoria ' + convocatoria.year : null

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0

    let dialogOpen = false
    let sending = false
    let form = useForm({
        descripcion: convocatoria.descripcion,
        esta_activa: convocatoria.esta_activa,
        fecha_inicio: convocatoria.fecha_inicio,
        fecha_finalizacion: convocatoria.fecha_finalizacion,
        min_fecha_inicio_proyectos: convocatoria.min_fecha_inicio_proyectos,
        max_fecha_finalizacion_proyectos: convocatoria.max_fecha_finalizacion_proyectos,
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
                <h1>
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
                    <p class="text-center">Fecha de la conovicatoria</p>
                    <div class="mt-4 flex items-start justify-around">
                        <div class="mt-4 flex {errors.fecha_inicio ? '' : 'items-center'}">
                            <Label required labelFor="fecha_inicio" class={errors.fecha_inicio ? 'top-3.5 relative' : ''} value="Del" />
                            <div class="ml-4">
                                <Input id="fecha_inicio" type="date" class="mt-1 block w-full" bind:value={$form.fecha_inicio} required />
                            </div>
                        </div>
                        <div class="mt-4 flex {errors.fecha_finalizacion ? '' : 'items-center'}">
                            <Label required labelFor="fecha_finalizacion" class={errors.fecha_finalizacion ? 'top-3.5 relative' : ''} value="hasta" />
                            <div class="ml-4">
                                <Input id="fecha_finalizacion" type="date" class="mt-1 block w-full" bind:value={$form.fecha_finalizacion} required />
                            </div>
                        </div>
                    </div>
                </div>
                {#if errors.fecha_inicio || errors.fecha_finalizacion}
                    <InputError message={errors.fecha_inicio || errors.fecha_finalizacion} />
                {/if}

                <hr />

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="descripcion" value="Descripción" />
                    <Textarea rows="4" id="descripcion" error={errors.descripcion} bind:value={$form.descripcion} required />
                </div>

                <div class="mt-4 mb-20">
                    <Label required labelFor="esta_activa" value="Desea activar está convocatoria?" class="inline-block mb-4" />
                    <br />
                    <Switch bind:checked={$form.esta_activa} />
                    <InputError message={errors.esta_activa} />
                </div>

                <hr />

                <div class="mt-4">
                    <p class="text-center">Fecha de ejecución de proyectos</p>
                    <div class="mt-4 flex items-start justify-around">
                        <div class="mt-4 flex {errors.min_fecha_inicio_proyectos ? '' : 'items-center'}">
                            <Label required labelFor="min_fecha_inicio_proyectos" class={errors.min_fecha_inicio_proyectos ? 'top-3.5 relative' : ''} value="Del" />
                            <div class="ml-4">
                                <Input id="min_fecha_inicio_proyectos" type="date" class="mt-1 block w-full" bind:value={$form.min_fecha_inicio_proyectos} required />
                            </div>
                        </div>
                        <div class="mt-4 flex {errors.max_fecha_finalizacio_proyectos ? '' : 'items-center'}">
                            <Label required labelFor="max_fecha_finalizacion_proyectos" class={errors.max_fecha_finalizacio_proyectos ? 'top-3.5 relative' : ''} value="hasta" />
                            <div class="ml-4">
                                <Input id="max_fecha_finalizacion_proyectos" type="date" class="mt-1 block w-full" bind:value={$form.max_fecha_finalizacion_proyectos} required />
                            </div>
                        </div>
                    </div>
                </div>
                {#if errors.min_fecha_inicio || errors.max_fecha_finalizacion_proyectos_proyectos}
                    <InputError message={errors.min_fecha_inicio_proyectos || errors.max_fecha_finalizacion_proyectos} />
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
            Eliminar recurso
        </div>
        <div slot="content">
            <p>
                ¿Está seguro (a) que desea eliminar esta convocatoria?
                <br />
                Una vez eliminada la convocatoria, todos los proyectos y datos asociados se eliminarán de forma permanente.
            </p>

            <form on:submit|preventDefault={destroy} id="delete-rdi" class="mt-20 mb-28">
                <Label labelFor="password" value="Ingrese su contraseña para confirmar que desea eliminar permanentemente esta convocatoria." />
                <Input id="password" type="password" class="mt-1 block w-full" error={errors.password} placeholder="Escriba su contraseña" bind:value={$deleteForm.password} required />
            </form>
        </div>
        <div slot="actions">
            <div class="p-4">
                <Button on:click={(event) => (dialogOpen = false)} variant={null}>Cancelar</Button>
                <Button variant="raised" form="delete-rdi">Confirmar</Button>
            </div>
        </div>
    </Dialog>
</AuthenticatedLayout>
