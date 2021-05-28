<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Components/Input'
    import Label from '@/Components/Label'
    import Button from '@/Components/Button'
    import LoadingButton from '@/Components/LoadingButton'
    import Select from '@/Components/Select'
    import Textarea from '@/Components/Textarea'
    import Dialog from '@/Components/Dialog'
    import DropdownLineaProgramatica from '@/Dropdowns/DropdownLineaProgramatica'

    export let errors
    export let convocatoria
    export let convocatoriaRolSennova
    export let rolesSennova
    export let nivelesAcademicos

    $: $title = convocatoriaRolSennova ? convocatoriaRolSennova.nombre : null

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
        asignacion_mensual: convocatoriaRolSennova.asignacion_mensual,
        experiencia: convocatoriaRolSennova.experiencia,
        nivel_academico: {
            value: convocatoriaRolSennova.nivel_academico,
            label: nivelesAcademicos.find((item) => item.value == convocatoriaRolSennova.nivel_academico)?.label,
        },
        linea_programatica_id: convocatoriaRolSennova.linea_programatica_id,
        rol_sennova_id: {
            value: convocatoriaRolSennova.rol_sennova_id,
            label: rolesSennova.find((item) => item.value == convocatoriaRolSennova.rol_sennova_id)?.label,
        },
        mensaje: convocatoriaRolSennova.mensaje,
    })

    function submit() {
        if (isSuperAdmin) {
            $form.put(route('convocatorias.convocatoria-rol-sennova.update', [convocatoria.id, convocatoriaRolSennova.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    function destroy() {
        if (isSuperAdmin) {
            $form.delete(route('convocatorias.convocatoria-rol-sennova.destroy', [convocatoria.id, convocatoriaRolSennova.id]))
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    {#if isSuperAdmin}
                        <a use:inertia href={route('convocatorias.convocatoria-rol-sennova.index', convocatoria.id)} class="text-indigo-400 hover:text-indigo-600"> Roles SENNOVA </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    {convocatoriaRolSennova.rol_sennova.nombre}
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={isSuperAdmin ? undefined : true}>
                <div class="mt-4">
                    <Label required class="mb-4" labelFor="rol_sennova_id" value="Rol SENNOVA" />
                    <Select id="rol_sennova_id" items={rolesSennova} bind:selectedValue={$form.rol_sennova_id} error={errors.rol_sennova_id} autocomplete="off" placeholder="Seleccione un rol SENNOVA" required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="asignacion_mensual" value="Asignación mensual" />
                    <Input id="asignacion_mensual" type="number" min="0" class="mt-1 block w-full" bind:value={$form.asignacion_mensual} error={errors.asignacion_mensual} required />
                </div>

                <div class="mt-4">
                    <Label class="mb-4" labelFor="experiencia" value="Número de meses de experiencia requerida" />
                    <Input id="experiencia" type="number" min="0" class="mt-1 block w-full" bind:value={$form.experiencia} error={errors.experiencia} />
                </div>
                <div class="mt-4">
                    <Label required class="mb-4" labelFor="linea_programatica_id" value="Línea programática" />
                    <DropdownLineaProgramatica id="linea_programatica_id" bind:formLineaProgramatica={$form.linea_programatica_id} message={errors.linea_programatica_id} required />
                </div>

                <div class="mt-4">
                    <Label required class="mb-4" labelFor="nivel_academico" value="Nivel académico" />
                    <Select id="nivel_academico" items={nivelesAcademicos} bind:selectedValue={$form.nivel_academico} error={errors.nivel_academico} autocomplete="off" placeholder="Seleccione un nivel académico" required />
                </div>

                <div class="mt-4">
                    <Label class="mb-4" labelFor="mensaje" value="Mensaje (Regla de negocio)" />
                    <Textarea rows="4" id="mensaje" bind:value={$form.mensaje} error={errors.mensaje} />
                </div>
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if isSuperAdmin}
                    <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={(event) => (dialogOpen = true)}> Eliminar rol SENNOVA </button>
                {/if}
                {#if isSuperAdmin}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Editar rol SENNOVA</LoadingButton>
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
                <Button on:click={(event) => (dialogOpen = false)} variant={null}>Cancelar</Button>
                <Button variant="raised" on:click={destroy}>Confirmar</Button>
            </div>
        </div>
    </Dialog>
</AuthenticatedLayout>
