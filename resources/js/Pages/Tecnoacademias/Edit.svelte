<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Shared/Input'
    import Label from '@/Shared/Label'
    import Button from '@/Shared/Button'
    import LoadingButton from '@/Shared/LoadingButton'
    import Dialog from '@/Shared/Dialog'
    import Checkbox from '@smui/checkbox'
    import FormField from '@smui/form-field'
    import InputError from '@/Shared/InputError'
    import Select from '@/Shared/Select'
    import Textarea from '@/Shared/Textarea'

    export let errors
    export let tecnoacademia
    export let lineasTecnoacademia
    export let modalidades
    export let lineasTecnoacademiaRelacionadas
    export let centrosFormacion

    $: $title = tecnoacademia ? tecnoacademia.nombre : null

    /**
     * Validar si el usuario autenticado es SuperAdmin
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let dialogOpen = false

    let form = useForm({
        nombre: tecnoacademia.nombre,
        modalidad: tecnoacademia.modalidad,
        centro_formacion_id: tecnoacademia.centro_formacion_id,
        fecha_creacion: tecnoacademia.fecha_creacion,
        foco: tecnoacademia.foco,
        linea_tecnoacademia_id: lineasTecnoacademiaRelacionadas,
        max_valor_viaticos_interior: tecnoacademia.max_valor_viaticos_interior,
        max_valor_edt: tecnoacademia.max_valor_edt,
        max_valor_mantenimiento_equipos: tecnoacademia.max_valor_mantenimiento_equipos,
        max_valor_roles: tecnoacademia.max_valor_roles,
        max_valor_presupuesto: tecnoacademia.max_valor_presupuesto,
        max_valor_materiales_formacion: tecnoacademia.max_valor_materiales_formacion,
        max_valor_bienestar_alumnos: tecnoacademia.max_valor_bienestar_alumnos,
    })

    function submit() {
        if (isSuperAdmin || checkRole(authUser, [5])) {
            $form.put(route('tecnoacademias.update', tecnoacademia.id), {
                preserveScroll: true,
            })
        }
    }

    function destroy() {
        if (isSuperAdmin || checkRole(authUser, [5])) {
            $form.delete(route('tecnoacademias.destroy', tecnoacademia.id))
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1 class="overflow-ellipsis overflow-hidden w-breadcrumb-ellipsis whitespace-nowrap">
                    <a use:inertia href={route('tecnoacademias.index')} class="text-violet-400 hover:text-violet-600"> Tecnoacademias </a>
                    <span class="text-violet-400 font-medium">/</span>
                    {tecnoacademia.nombre}
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={isSuperAdmin || checkRole(authUser, [5]) ? undefined : true}>
                <div class="mt-4">
                    <Input label="Nombre" id="nombre" type="text" class="mt-1" bind:value={$form.nombre} error={errors.nombre} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="centro_formacion_id" value="Centro de formación" />
                    <Select id="centro_formacion_id" items={centrosFormacion} bind:selectedValue={$form.centro_formacion_id} error={errors.centro_formacion_id} autocomplete="off" placeholder="Busque por el nombre del centro de formación" required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="modalidad" value="Modalidad" />
                    <Select id="modalidad" items={modalidades} bind:selectedValue={$form.modalidad} error={errors.modalidad} autocomplete="off" placeholder="Seleccione una modalidad" required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="fecha_creacion" value="Fecha de creación" />
                    <input label="Fecha de creación" id="fecha_creacion" type="date" class="mt-1 p-4" bind:value={$form.fecha_creacion} required />
                </div>

                <div class="mt-4">
                    <Textarea label="Foco de la TecnoAcademia" maxlength="40000" id="foco" bind:value={$form.foco} error={errors.foco} required />
                </div>

                <div class="mt-4">
                    <Input label="Valor máximo: Material de enseñanza" id="max_valor_materiales_formacion" type="number" input$step="0.1" input$min="0" class="mt-1" bind:value={$form.max_valor_materiales_formacion} error={errors.max_valor_materiales_formacion} required />
                </div>

                <div class="mt-4">
                    <Input label="Valor máximo: Bienestar alumnos" id="max_valor_bienestar_alumnos" type="number" input$step="0.1" input$min="0" class="mt-1" bind:value={$form.max_valor_bienestar_alumnos} error={errors.max_valor_bienestar_alumnos} required />
                </div>

                <div class="mt-4">
                    <Input label="Valor máximo: Viáticos interior formación profesional" id="max_valor_viaticos_interior" type="number" input$step="0.1" input$min="0" class="mt-1" bind:value={$form.max_valor_viaticos_interior} error={errors.max_valor_viaticos_interior} required />
                </div>

                <div class="mt-4">
                    <Input label="Valor máximo: EDT" id="max_valor_edt" type="number" input$step="0.1" input$min="0" class="mt-1" bind:value={$form.max_valor_edt} error={errors.max_valor_edt} required />
                </div>

                <div class="mt-4">
                    <Input label="Valor máximo: Mantenimiento de equipos" id="max_valor_mantenimiento_equipos" type="number" input$step="0.1" input$min="0" class="mt-1" bind:value={$form.max_valor_mantenimiento_equipos} error={errors.max_valor_mantenimiento_equipos} required />
                </div>

                <div class="mt-4">
                    <Input label="Valor máximo: Roles" id="max_valor_roles" type="number" input$step="0.1" input$min="0" class="mt-1" bind:value={$form.max_valor_roles} error={errors.max_valor_roles} required />
                </div>

                <div class="mt-4">
                    <Input label="Valor máximo: Presupuesto total" id="max_valor_presupuesto" type="number" input$step="0.1" input$min="0" class="mt-1" bind:value={$form.max_valor_presupuesto} error={errors.max_valor_presupuesto} required />
                </div>

                <div class="mt-10">
                    <Label required class="mb-4" labelFor="linea_tecnoacademia_id" value="Líneas de TecnoAcademia" />
                    <div class="mt-10 grid grid-cols-2">
                        {#each lineasTecnoacademia as { id, nombre }, i}
                            <FormField>
                                <Checkbox bind:group={$form.linea_tecnoacademia_id} value={id} />
                                <span slot="label">{nombre}</span>
                            </FormField>
                        {/each}
                    </div>
                    <InputError message={errors.linea_tecnoacademia_id} />
                </div>
            </fieldset>
            <div class="shadow-inner bg-violet-200 border-violet-400 bottom-0 flex items-center justify-between mt-14 px-8 py-4 sticky">
                {#if isSuperAdmin || checkRole(authUser, [5])}
                    <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={() => (dialogOpen = true)}> Eliminar tecnoacademia </button>
                {/if}
                {#if isSuperAdmin || checkRole(authUser, [5])}
                    <LoadingButton loading={$form.processing} class="ml-auto" type="submit">Editar tecnoacademia</LoadingButton>
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
                ¿Está seguro(a) que desea eliminar este recurso?
                <br />
                Todos los datos se eliminarán de forma permanente.
                <br />
                Está acción no se puede deshacer.
            </p>
        </div>
        <div slot="actions">
            <div class="p-4">
                <Button on:click={() => (dialogOpen = false)} variant={null}>Cancelar</Button>
                <Button variant="raised" on:click={destroy}>Confirmar</Button>
            </div>
        </div>
    </Dialog>
</AuthenticatedLayout>
