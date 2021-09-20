<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page, useForm } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import EvaluationStepper from '@/Shared/EvaluationStepper'
    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import Select from '@/Shared/Select'
    import SelectMulti from '@/Shared/SelectMulti'
    import Textarea from '@/Shared/Textarea'
    import Tags from '@/Shared/Tags'
    import InfoMessage from '@/Shared/InfoMessage'
    import Switch from '@/Shared/Switch'

    export let errors
    export let convocatoria
    export let evaluacion
    export let proyecto
    export let lineasInvestigacion
    export let gruposInvestigacion
    export let semillerosInvestigacion
    export let gruposInvestigacionRelacionados
    export let lineasInvestigacionRelacionadas
    export let semillerosInvestigacionRelacionados

    $title = 'Articulación SENNOVA'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let opcionesSiNo = [
        { value: 1, label: 'Si' },
        { value: 2, label: 'No' },
    ]
    let sending = false
    let articulacionSennovaInfo = {
        lineas_investigacion: lineasInvestigacionRelacionadas.length > 0 ? lineasInvestigacionRelacionadas : null,
        grupos_investigacion: gruposInvestigacionRelacionados.length > 0 ? gruposInvestigacionRelacionados : null,
        semilleros_investigacion: semillerosInvestigacionRelacionados.length > 0 ? semillerosInvestigacionRelacionados : null,
        proyectos_ejecucion: proyecto.proyectos_ejecucion,
        semilleros_en_formalizacion: proyecto.semilleros_en_formalizacion,
        articulacion_semillero: {
            value: proyecto.articulacion_semillero,
            label: opcionesSiNo.find((item) => item.value == proyecto.articulacion_semillero)?.label,
        },
    }

    let formTaEvaluacion = useForm({
        articulacion_sennova_comentario: evaluacion.ta_evaluacion.articulacion_sennova_comentario,
        articulacion_sennova_requiere_comentario: evaluacion.ta_evaluacion.articulacion_sennova_comentario == null ? true : false,
    })
    function submitTaEvaluacion() {
        if (isSuperAdmin || (checkRole(authUser, [11]) && proyecto.finalizado == true && evaluacion.finalizado == false && evaluacion.habilitado == true && evaluacion.modificable == true)) {
            $formTaEvaluacion.put(route('convocatorias.evaluaciones.articulacion-sennova.guardar-evaluacion', [convocatoria.id, evaluacion.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }
</script>

<AuthenticatedLayout>
    <EvaluationStepper {convocatoria} {evaluacion} {proyecto} />

    <h1 class="text-3xl mt-24 mb-8 text-center">Articulación SENNOVA</h1>

    <p class="text-center mb-8">A continuación, registre la información relacionada con la articulación de la línea de TecnoAcademia con las otras líneas de SENNOVA en el centro y la regional:</p>

    <form on:submit|preventDefault={submitTaEvaluacion}>
        <fieldset disabled={isSuperAdmin || (checkPermission(authUser, [11]) && proyecto.finalizado == true) ? undefined : true}>
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label class="mb-4" for="lineas_investigacion" value="Líneas de Investigación en las cuales se están ejecutando iniciativas o proyectos de la TecnoAcademia" />
                </div>
                <div>
                    <SelectMulti classes="evaluacion-select-multi" disabled={true} id="lineas_investigacion" bind:selectedValue={articulacionSennovaInfo.lineas_investigacion} items={lineasInvestigacion} isMulti={true} error={errors.lineas_investigacion} placeholder="Buscar por el nombre de la línea de investigación" />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label class="mb-4" for="grupos_investigacion" value="Grupos de investigación en los cuales está vinculada la TecnoAcademia" />
                </div>
                <div>
                    <SelectMulti classes="evaluacion-select-multi" disabled={true} id="grupos_investigacion" bind:selectedValue={articulacionSennovaInfo.grupos_investigacion} items={gruposInvestigacion} isMulti={true} error={errors.grupos_investigacion} placeholder="Buscar por el nombre del grupo de investigación" />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label class="mb-4" labelFor="articulacion_semillero" value="¿Está la TecnoAcademia articulada con un semillero?" />
                </div>
                <div>
                    <Select disabled={true} items={opcionesSiNo} id="articulacion_semillero" bind:selectedValue={articulacionSennovaInfo.articulacion_semillero} error={errors.articulacion_semillero} autocomplete="off" placeholder="Seleccione una opción" />
                </div>
            </div>

            {#if articulacionSennovaInfo.articulacion_semillero?.value == 1}
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label class="mb-4" for="semilleros_investigacion" value="Semillero(s) de investigación de la TecnoAcademia" />
                    </div>
                    <div>
                        <SelectMulti classes="evaluacion-select-multi" disabled={true} id="semilleros_investigacion" bind:selectedValue={articulacionSennovaInfo.semilleros_investigacion} items={semillerosInvestigacion} isMulti={true} error={errors.semilleros_investigacion} placeholder="Buscar por el nombre del semillero de investigación" />
                    </div>
                </div>
            {/if}
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label class="mb-4" for="proyectos_ejecucion" value="Proyectos o iniciativas en ejecución en el año {convocatoria.year - 1}" />
                </div>
                <div>
                    <Textarea disabled label="Proyectos / Iniciativas" maxlength="40000" id="proyectos_ejecucion" error={errors.proyectos_ejecucion} bind:value={articulacionSennovaInfo.proyectos_ejecucion} />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label class="mb-4" labelFor="semilleros_en_formalizacion" value="Semilleros en proceso de formalización (Separados por coma)" />
                </div>
                <div>
                    <Tags id="semilleros_en_formalizacion" class="mt-4" enforceWhitelist={false} bind:tags={articulacionSennovaInfo.semilleros_en_formalizacion} placeholder="Nombre del semillero" error={errors.semilleros_en_formalizacion} />
                </div>
            </div>

            {#if proyecto.codigo_linea_programatica == 70}
                <hr class="mt-10 mb-10" />

                <h1 class="text-3xl mt-24 mb-8 text-center" id="evaluacion">Evaluación</h1>

                <div class="mt-16">
                    <InfoMessage>
                        <div class="mt-4">
                            <p>¿La articulación SENNOVA está definida correctamente? Por favor seleccione si Cumple o No cumple.</p>
                            <Switch onMessage="Cumple" offMessage="No cumple" disabled={isSuperAdmin ? undefined : evaluacion.finalizado == true || evaluacion.habilitado == false || evaluacion.modificable == false ? true : undefined} bind:checked={$formTaEvaluacion.articulacion_sennova_requiere_comentario} />
                            {#if $formTaEvaluacion.articulacion_sennova_requiere_comentario == false}
                                <Textarea
                                    disabled={isSuperAdmin ? undefined : evaluacion.finalizado == true || evaluacion.habilitado == false || evaluacion.modificable == false ? true : undefined}
                                    label="Comentario"
                                    class="mt-4"
                                    maxlength="40000"
                                    id="articulacion_sennova_comentario"
                                    bind:value={$formTaEvaluacion.articulacion_sennova_comentario}
                                    error={errors.articulacion_sennova_comentario}
                                    required
                                />
                            {/if}
                        </div>
                    </InfoMessage>
                </div>
            {/if}
        </fieldset>
        <div class="py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
            {#if isSuperAdmin || (checkRole(authUser, [11]) && proyecto.finalizado == true && evaluacion.finalizado == false && evaluacion.habilitado == true && evaluacion.modificable == true)}
                <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Guardar</LoadingButton>
            {/if}
        </div>
    </form>
</AuthenticatedLayout>
