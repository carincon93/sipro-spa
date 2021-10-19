<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { inertia, useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Input from '@/Shared/Input'
    import LoadingButton from '@/Shared/LoadingButton'
    import InfoMessage from '@/Shared/InfoMessage'
    import Textarea from '@/Shared/Textarea'
    import Switch from '@/Shared/Switch'

    export let errors
    export let convocatoria
    export let evaluacion
    export let proyectoRolEvaluacion
    export let proyecto
    export let proyectoRolSennova

    $: $title = proyectoRolSennova.convocatoria_rol_sennova.rol_sennova.nombre

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
        correcto: proyectoRolEvaluacion?.correcto == undefined || proyectoRolEvaluacion?.correcto == true ? true : false,
    })
    function submit() {
        if (isSuperAdmin || (checkRole(authUser, [11]) && evaluacion.finalizado == false && evaluacion.habilitado == true && evaluacion.modificable == true)) {
            $form.put(route('convocatorias.evaluaciones.proyecto-rol-sennova.update', [convocatoria.id, evaluacion.id, proyectoRolSennova.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
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
                    {#if isSuperAdmin || checkRole(authUser, [11])}
                        <a use:inertia href={route('convocatorias.evaluaciones.proyecto-rol-sennova.index', [convocatoria.id, evaluacion.id])} class="text-indigo-400 hover:text-indigo-600"> Roles SENNOVA </a>
                    {/if}
                    <span class="text-indigo-400 font-medium">/</span>
                    {proyectoRolSennova.convocatoria_rol_sennova.rol_sennova.nombre}
                </h1>
            </div>
        </div>
    </header>

    <div class="bg-white rounded shadow max-w-3xl">
        <form on:submit|preventDefault={submit}>
            <div class="p-8">
                <div class="mt-4 mb-10">
                    <p class="mb-10">
                        {proyectoRolSennova.convocatoria_rol_sennova.rol_sennova.nombre}
                    </p>
                    <p class="mb-10 whitespace-pre-line">
                        Experiencia: {proyectoRolSennova.convocatoria_rol_sennova.experiencia}
                    </p>
                    <p class="mb-10">
                        Asignación mensual: {proyectoRolSennova.convocatoria_rol_sennova.asignacion_mensual}
                    </p>
                </div>

                <div class="mt-4">
                    {#if proyectoRolSennova.convocatoria_rol_sennova?.perfil && proyecto.codigo_linea_programatica != 68}
                        <Textarea disabled={proyecto.codigo_linea_programatica != 68} label="Descripción" maxlength="40000" id="descripcion" bind:value={proyectoRolSennova.convocatoria_rol_sennova.perfil} />
                    {:else}
                        <Textarea disabled label="Descripción" maxlength="40000" id="descripcion" bind:value={rolSennovaInfo.descripcion} />
                    {/if}
                </div>

                {#if proyecto.codigo_linea_programatica != 68}
                    <div class="mt-4">
                        <Input disabled label="Número de meses que requiere el apoyo." id="numero_meses" type="number" input$min="1" input$step="0.1" class="mt-1" bind:value={rolSennovaInfo.numero_meses} />
                    </div>
                {/if}

                <div class="mt-4">
                    <Input disabled label="Número de personas requeridas" id="numero_roles" type="number" input$min="1" class="mt-1" bind:value={rolSennovaInfo.numero_roles} />
                </div>

                {#if proyectoRolSennova.actividades?.length > 0}
                    <h6 class="mt-20 mb-12 text-2xl">Actividades</h6>
                    <div class="bg-white rounded shadow overflow-hidden">
                        <div class="p-4" />

                        <div class="p-2">
                            <ul class="list-disc p-4">
                                {#each proyectoRolSennova.actividades as { id, descripcion }, i}
                                    <li class="first-letter-uppercase mb-4">{descripcion}</li>
                                {/each}
                            </ul>
                        </div>
                    </div>
                {/if}
            </div>

            <InfoMessage>
                <div class="mt-4">
                    <p>¿El rol es correcto? Por favor seleccione si Cumple o No cumple.</p>
                    <Switch onMessage="Cumple" offMessage="No cumple" disabled={isSuperAdmin ? undefined : evaluacion.finalizado == true || evaluacion.habilitado == false || evaluacion.modificable == false ? true : undefined} bind:checked={$form.correcto} />
                    {#if $form.correcto == false}
                        <Textarea disabled={isSuperAdmin ? undefined : evaluacion.finalizado == true || evaluacion.habilitado == false || evaluacion.modificable == false ? true : undefined} label="Comentario" class="mt-4" maxlength="40000" id="comentario" bind:value={$form.comentario} error={errors.comentario} required />
                    {/if}
                </div>
            </InfoMessage>
            <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
                {#if isSuperAdmin || (checkRole(authUser, [11]) && evaluacion.finalizado == false && evaluacion.habilitado == true && evaluacion.modificable == true)}
                    <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Evaluar</LoadingButton>
                {/if}
            </div>
        </form>
    </div>
</AuthenticatedLayout>
