<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Shared/Input'
    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import InfoMessage from '@/Shared/InfoMessage'
    import Textarea from '@/Shared/Textarea'
    import DynamicList from '@/Shared/Dropdowns/DynamicList'
    import Switch from '@/Shared/Switch'

    export let errors
    export let convocatoria
    export let evaluacion
    export let proyectoRolEvaluacion
    export let proyecto
    export let lineaProgramatica
    export let rolSennova
    export let proyectoRolSennova

    let infoRolSennova

    $: $title = rolSennova.nombre

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let rolSennovaInfo = {
        numero_meses: proyectoRolSennova.numero_meses,
        numero_roles: proyectoRolSennova.numero_roles,
        descripcion: proyectoRolSennova.descripcion,
        convocatoria_rol_sennova_id: proyectoRolSennova.convocatoria_rol_sennova_id,
    }

    let sending = false
    let form = useForm({
        comentario: proyectoRolEvaluacion ? proyectoRolEvaluacion.comentario : '',
        correcto: proyectoRolEvaluacion?.correcto,
    })
    function submit() {
        if (isSuperAdmin || (checkRole(authUser, [11]) && proyecto.finalizado == true)) {
            $form.put(route('convocatorias.evaluaciones.proyecto-rol-sennova.update', [convocatoria.id, evaluacion.id, proyectoRolSennova.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    let diff_meses = proyecto.diff_meses
    $: if (rolSennovaInfo.convocatoria_rol_sennova_id) {
        if (proyecto.codigo_linea_programatica == 68) {
            rolSennovaInfo.descripcion = infoRolSennova?.perfil == null ? 'Sin descripción' : infoRolSennova?.perfil
            rolSennovaInfo.numero_roles = 1
            if (rolSennovaInfo.convocatoria_rol_sennova_id == 108) {
                rolSennovaInfo.numero_meses = 6
            } else {
                rolSennovaInfo.numero_meses = proyecto.max_meses_ejecucion
            }
        }

        if (proyecto.codigo_linea_programatica == 70) {
            if ((rolSennovaInfo.convocatoria_rol_sennova_id == 51 && proyecto.diff_meses >= 11) || (rolSennovaInfo.convocatoria_rol_sennova_id == 52 && proyecto.diff_meses >= 11) || (rolSennovaInfo.convocatoria_rol_sennova_id == 53 && proyecto.diff_meses >= 11)) {
                diff_meses = 11
            } else {
                diff_meses = proyecto.diff_meses
            }
        }
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto px-4 py-6 sm:px-6">
            <div>
                <h1 class="overflow-ellipsis overflow-hidden w-breadcrumb-ellipsis whitespace-nowrap">
                    {#if isSuperAdmin || checkRole(authUser, [11])}
                        <a use:inertia href={route('convocatorias.evaluaciones.proyecto-rol-sennova.index', [convocatoria.id, evaluacion.id])} class="text-indigo-400 hover:text-indigo-600"> Roles SENNOVA </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    {rolSennova.nombre}
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <fieldset class="p-8" disabled={isSuperAdmin || (checkRole(authUser, [11]) && proyecto.finalizado == true) ? undefined : true}>
                <div class="mt-4">
                    <Label class="mb-4" labelFor="convocatoria_rol_sennova_id" value="Rol SENNOVA" />
                    <DynamicList disabled={true} id="convocatoria_rol_sennova_id" value={rolSennovaInfo.convocatoria_rol_sennova_id} routeWebApi={route('web-api.convocatorias.roles-sennova', [convocatoria.id, proyecto, lineaProgramatica])} recurso={infoRolSennova} placeholder="Busque por el nombre del rol" />
                </div>

                {#if infoRolSennova?.experiencia}
                    <div class="mt-4">
                        <p class="block font-medium text-sm text-gray-700 ">
                            Experiencia (meses)
                            <span class="block border-gray-300 p-4 rounded-md shadow-sm">
                                {infoRolSennova.experiencia}
                            </span>
                        </p>
                    </div>
                {/if}

                {#if proyecto.codigo_linea_programatica != 68}
                    <div class="mt-4">
                        <Textarea disabled label="Descripción" maxlength="40000" id="descripcion" value={rolSennovaInfo.descripcion} />
                    </div>

                    <div class="mt-4">
                        <Input disabled label="Número de meses que requiere el apoyo. (Máximo {proyecto.diff_meses})" id="numero_meses" type="number" input$min="1" input$step="0.1" input$max={proyecto.diff_meses < 6 ? 6 : proyecto.diff_meses} class="mt-1" value={rolSennovaInfo.numero_meses} />
                        <InfoMessage>Este proyecto será ejecutado en {proyecto.diff_meses} meses.</InfoMessage>
                    </div>

                    <div class="mt-4">
                        <Input disabled label="Número de personas requeridas" id="numero_roles" type="number" input$min="1" class="mt-1" value={rolSennovaInfo.numero_roles} />
                    </div>
                {/if}
            </fieldset>

            <InfoMessage>
                <div class="mt-4">
                    <p>¿El rol requiere de una recomendación?</p>
                    <Switch disabled={evaluacion.finalizado && evaluacion.habilitado ? true : undefined} bind:checked={$form.correcto} />
                    {#if $form.correcto}
                        <Textarea disabled={evaluacion.finalizado && evaluacion.habilitado ? true : undefined} label="Comentario" class="mt-4" maxlength="40000" id="comentario" bind:value={$form.comentario} error={errors.comentario} required />
                    {/if}
                </div>
            </InfoMessage>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if isSuperAdmin || (checkRole(authUser, [11]) && proyecto.finalizado == true && evaluacion.finalizado == false)}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Evaluar</LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
