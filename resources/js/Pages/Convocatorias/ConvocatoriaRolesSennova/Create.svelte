<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Shared/Input'
    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import Select from '@/Shared/Select'
    import Textarea from '@/Shared/Textarea'
    import DropdownLineaProgramatica from '@/Shared/Dropdowns/DropdownLineaProgramatica'

    export let errors
    export let convocatoria
    export let rolesSennova
    export let nivelesAcademicos

    $: $title = 'Crear rol SENNOVA'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let sending = false
    let form = useForm({
        rol_sennova_id: null,
        linea_programatica_id: null,
        asignacion_mensual: '',
        experiencia: '',
        nivel_academico: '',
        mensaje: '',
    })

    function submit() {
        if (isSuperAdmin) {
            $form.post(route('convocatorias.convocatoria-rol-sennova.store', convocatoria.id), {
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
                        <a use:inertia href={route('convocatorias.convocatoria-rol-sennova.index', convocatoria.id)} class="text-indigo-400 hover:text-indigo-600"> Roles SENNOVA de convocatoria </a>
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
                <div class="mt-4">
                    <Label required class="mb-4" labelFor="rol_sennova_id" value="Rol SENNOVA" />
                    <Select id="rol_sennova_id" items={rolesSennova} bind:selectedValue={$form.rol_sennova_id} error={errors.rol_sennova_id} autocomplete="off" placeholder="Seleccione un rol SENNOVA" required />
                </div>

                <div class="mt-4">
                    <Input label="Asignación mensual" id="asignacion_mensual" type="number" min="0" class="mt-1" bind:value={$form.asignacion_mensual} error={errors.asignacion_mensual} required />
                </div>

                <div class="mt-4">
                    <Input label="Número de meses de experiencia requerida" id="experiencia" type="number" min="0" class="mt-1" bind:value={$form.experiencia} error={errors.experiencia} />
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
                    <Textarea label="Mensaje (Regla de negocio)" maxlength="40000" id="mensaje" bind:value={$form.mensaje} error={errors.mensaje} />
                </div>
            </fieldset>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if isSuperAdmin}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Crear rol SENNOVA convocatoria</LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
