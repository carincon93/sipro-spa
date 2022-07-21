<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Shared/Input'
    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import Textarea from '@/Shared/Textarea'
    import Select from '@/Shared/Select'

    export let errors
    export let convocatoria
    export let proyecto
    export let convocatoriaRolesSennova

    let infoRolSennova

    $: $title = 'Crear rol SENNOVA'

    /**
     * Validar si el usuario autenticado es SuperAdmin
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let form = useForm({
        numero_meses: '',
        numero_roles: '',
        descripcion: '',
        convocatoria_rol_sennova_id: null,
    })

    function submit() {
        if (proyecto.allowed.to_update) {
            $form.post(route('convocatorias.proyectos.proyecto-rol-sennova.store', [convocatoria.id, proyecto.id]))
        }
    }

    let diff_meses = proyecto.diff_meses
    $: if ($form.convocatoria_rol_sennova_id) {
        if (proyecto.codigo_linea_programatica == 68) {
            if ($form.convocatoria_rol_sennova_id == 108) {
                $form.numero_meses = 6
            } else {
                $form.numero_meses = proyecto.max_meses_ejecucion
            }
        }
    }

    $: if (infoRolSennova?.perfil != null) {
        $form.descripcion = infoRolSennova.perfil
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1>
                    <a use:inertia href={route('convocatorias.proyectos.proyecto-rol-sennova.index', [convocatoria.id, proyecto.id])} class="text-violet-400 hover:text-violet-600"> Roles SENNOVA </a>
                    <span class="text-violet-400 font-medium">/</span>
                    Crear
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={proyecto.allowed.to_update ? undefined : true}>
                <div class="mt-4">
                    <Label required class="mb-4" labelFor="convocatoria_rol_sennova_id" value="Rol SENNOVA" />
                    <Select id="convocatoria_rol_sennova_id" items={convocatoriaRolesSennova} bind:selectedValue={$form.convocatoria_rol_sennova_id} error={errors.convocatoria_rol_sennova_id} autocomplete="off" placeholder="Busque por el nombre del rol" required />
                </div>

                <div class="mt-4">
                    {#if infoRolSennova?.perfil}
                        <Textarea disabled={proyecto.codigo_linea_programatica != 68} label="Descripción" maxlength="40000" id="descripcion" error={errors.descripcion} bind:value={$form.descripcion} required />
                    {:else}
                        <Textarea label="Descripción" maxlength="40000" id="descripcion" error={errors.descripcion} bind:value={$form.descripcion} required />
                    {/if}
                </div>
                {#if proyecto.codigo_linea_programatica != 68}
                    <div class="mt-4">
                        <Input label="Número de meses que requiere el apoyo." id="numero_meses" type="number" input$min="1" input$step="0.1" input$max={diff_meses < 6 ? 6 : diff_meses} class="mt-1" error={errors.numero_meses} bind:value={$form.numero_meses} required />
                    </div>
                {/if}

                <div class="mt-4">
                    <Input label="Número de personas requeridas" id="numero_roles" type="number" input$min="1" class="mt-1" error={errors.numero_roles} bind:value={$form.numero_roles} required />
                </div>
            </fieldset>
            <div class="shadow-inner bg-violet-200 border-violet-400 bottom-0 flex items-center justify-between mt-14 px-8 py-4 sticky">
                {#if proyecto.allowed.to_update}
                    <LoadingButton loading={$form.processing} class="ml-auto" type="submit">Crear rol SENNOVA</LoadingButton>
                {:else}
                    <span class="inline-block ml-1.5"> El proyecto no se puede modificar </span>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
