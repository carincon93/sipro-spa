<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page, useForm } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Stepper from '@/Shared/Stepper'
    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import Select from '@/Shared/Select'
    import SelectMulti from '@/Shared/SelectMulti'
    import Textarea from '@/Shared/Textarea'
    import Tags from '@/Shared/Tags'

    export let errors
    export let convocatoria
    export let proyecto
    export let areasConocimiento
    export let subareasConocimiento
    export let disciplinasSubareaConocimiento
    export let lineasInvestigacion
    export let gruposInvestigacion
    export let semillerosInvestigacion
    export let redesConocimiento
    export let tematicasEstrategicas
    export let actividadesEconomicas
    export let gruposInvestigacionRelacionados
    export let lineasInvestigacionRelacionadas
    export let semillerosInvestigacionRelacionados
    export let disciplinasSubareaConocimientoRelacionadas
    export let redesConocimientoRelacionadas
    export let actividadesEconomicasRelacionadas
    export let tematicasEstrategicasRelacionadas

    $title = 'Articulación SENNOVA'

    /**
     * Validar si el usuario autenticado es SuperAdmin
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let opcionesSiNo = [
        { value: 1, label: 'Si' },
        { value: 2, label: 'No' },
    ]

    let arraySubareasConocimiento = subareasConocimiento.filter(function (obj) {
        return obj.area_conocimiento_id == $form.area_conocimiento_id?.value
    })
    function selectAreaConocimiento(event) {
        arraySubareasConocimiento = subareasConocimiento.filter(function (obj) {
            return obj.area_conocimiento_id == event.detail?.value
        })
    }

    let arrayDisciplinasSubareaConocimiento = []
    function selectSubreaConocimiento(event) {
        arrayDisciplinasSubareaConocimiento = disciplinasSubareaConocimiento.filter(function (obj) {
            return obj.subarea_conocimiento_id == event.detail?.value
        })
    }

    let form = useForm({
        area_conocimiento_id: null,
        subarea_conocimiento_id: null,
        lineas_investigacion: lineasInvestigacionRelacionadas.length > 0 ? lineasInvestigacionRelacionadas : null,
        grupos_investigacion: gruposInvestigacionRelacionados.length > 0 ? gruposInvestigacionRelacionados : null,
        semilleros_investigacion: semillerosInvestigacionRelacionados.length > 0 ? semillerosInvestigacionRelacionados : null,
        disciplinas_subarea_conocimiento: disciplinasSubareaConocimientoRelacionadas.length > 0 ? disciplinasSubareaConocimientoRelacionadas : null,
        redes_conocimiento: redesConocimientoRelacionadas.length > 0 ? redesConocimientoRelacionadas : null,
        actividades_economicas: actividadesEconomicasRelacionadas.length > 0 ? actividadesEconomicasRelacionadas : null,
        tematicas_estrategicas: tematicasEstrategicasRelacionadas.length > 0 ? tematicasEstrategicasRelacionadas : null,
        proyectos_ejecucion: proyecto.proyectos_ejecucion,
        semilleros_en_formalizacion: proyecto.semilleros_en_formalizacion,
        articulacion_semillero: proyecto.articulacion_semillero,
    })

    function submit() {
        if (isSuperAdmin || (checkPermission(authUser, [8, 9, 10]) && proyecto.modificable == true)) {
            $form.post(route('convocatorias.proyectos.articulacion-sennova.store', [convocatoria.id, proyecto.id]), {
                preserveScroll: true,
            })
        }
    }
</script>

<AuthenticatedLayout>
    <header slot="header">
        <Stepper {convocatoria} {proyecto} />
    </header>

    <h1 class="text-3xl mt-24 mb-8 text-center">Articulación SENNOVA</h1>

    <p class="text-center mb-8">A continuación, registre la información relacionada con la articulación de la línea de TecnoAcademia con las otras líneas de SENNOVA en el centro y la regional:</p>

    <form on:submit|preventDefault={submit}>
        <fieldset disabled={isSuperAdmin || (checkPermission(authUser, [8, 9, 10]) && proyecto.modificable == true) ? undefined : true}>
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="lineas_investigacion" value="Líneas de Investigación en las cuales se están ejecutando iniciativas o proyectos de la TecnoAcademia" />
                </div>
                <div>
                    <SelectMulti id="lineas_investigacion" bind:selectedValue={$form.lineas_investigacion} items={lineasInvestigacion} isMulti={true} error={errors.lineas_investigacion} placeholder="Buscar por el nombre de la línea de investigación" required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="grupos_investigacion" value="Grupos de investigación en los cuales está vinculada la TecnoAcademia" />
                </div>
                <div>
                    <SelectMulti id="grupos_investigacion" bind:selectedValue={$form.grupos_investigacion} items={gruposInvestigacion} isMulti={true} error={errors.grupos_investigacion} placeholder="Buscar por el nombre del grupo de investigación" required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="articulacion_semillero" value="¿Está la TecnoAcademia articulada con un semillero?" />
                </div>
                <div>
                    <Select items={opcionesSiNo} id="articulacion_semillero" bind:selectedValue={$form.articulacion_semillero} error={errors.articulacion_semillero} autocomplete="off" placeholder="Seleccione una opción" required />
                </div>
            </div>

            {#if $form.articulacion_semillero?.value == 1}
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="semilleros_investigacion" value="Semillero(s) de investigación de la TecnoAcademia" />
                    </div>
                    <div>
                        <SelectMulti id="semilleros_investigacion" bind:selectedValue={$form.semilleros_investigacion} items={semillerosInvestigacion} isMulti={true} error={errors.semilleros_investigacion} placeholder="Buscar por el nombre del semillero de investigación" required />
                    </div>
                </div>
            {/if}
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="proyectos_ejecucion" value="Proyectos o iniciativas en ejecución en el año {convocatoria.year - 1}" />
                </div>
                <div>
                    <Textarea label="Proyectos / Iniciativas" maxlength="40000" id="proyectos_ejecucion" error={errors.proyectos_ejecucion} bind:value={$form.proyectos_ejecucion} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label class="mb-4" labelFor="semilleros_en_formalizacion" value="Semilleros en proceso de formalización (Separados por coma)" />
                </div>
                <div>
                    <Tags id="semilleros_en_formalizacion" class="mt-4" enforceWhitelist={false} bind:tags={$form.semilleros_en_formalizacion} placeholder="Nombre del semillero" error={errors.semilleros_en_formalizacion} />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="area_conocimiento_id" value="Área de conocimiento" />
                </div>
                <div>
                    <Select id="area_conocimiento_id" items={areasConocimiento} bind:selectedValue={$form.area_conocimiento_id} selectFunctions={[(event) => selectAreaConocimiento(event)]} error={errors.area_conocimiento_id} autocomplete="off" placeholder="Busque por el nombre de la área de conocimiento" required />
                </div>
            </div>
            {#if $form.area_conocimiento_id}
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="subarea_conocimiento_id" value="Subárea de conocimiento" />
                    </div>
                    <div>
                        <Select id="subarea_conocimiento_id" items={arraySubareasConocimiento} bind:selectedValue={$form.subarea_conocimiento_id} selectFunctions={[(event) => selectSubreaConocimiento(event)]} error={errors.subarea_conocimiento_id} autocomplete="off" placeholder="Busque por el nombre de la subárea de conocimiento" required />
                    </div>
                </div>
            {/if}
            {#if $form.subarea_conocimiento_id || disciplinasSubareaConocimientoRelacionadas.length > 0}
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="disciplina_subarea_conocimiento_id" value="Disciplina de la subárea de conocimiento" />
                    </div>
                    <div>
                        <SelectMulti id="disciplinas_subarea_conocimiento_id" bind:selectedValue={$form.disciplinas_subarea_conocimiento} items={arrayDisciplinasSubareaConocimiento} isMulti={true} error={errors.disciplinas_subarea_conocimiento} placeholder="Disciplinas subárea de concimiento" required />
                    </div>
                </div>
            {/if}

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="redes_conocimiento" value="Red de conocimiento sectorial" />
                </div>
                <div>
                    <SelectMulti id="redes_conocimiento" bind:selectedValue={$form.redes_conocimiento} items={redesConocimiento} isMulti={true} error={errors.redes_conocimiento} placeholder="Buscar por el nombre del grupo de investigación" required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="actividades_economicas" value="¿En cuál de estas actividades económicas se puede aplicar el proyecto?" />
                </div>
                <div>
                    <SelectMulti id="actividades_economicas" bind:selectedValue={$form.actividades_economicas} items={actividadesEconomicas} isMulti={true} error={errors.actividades_economicas} placeholder="Buscar por el nombre del grupo de investigación" required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="tematicas_estrategicas" value="Temática estratégica SENA" />
                </div>
                <div>
                    <SelectMulti id="tematicas_estrategicas" bind:selectedValue={$form.tematicas_estrategicas} items={tematicasEstrategicas} isMulti={true} error={errors.tematicas_estrategicas} placeholder="Buscar por el nombre del grupo de investigación" required />
                </div>
            </div>
        </fieldset>
        <div class="shadow-inner bg-violet-200 border-violet-400 bottom-0 flex items-center justify-between mt-14 px-8 py-4 sticky">
            {#if isSuperAdmin || (checkPermission(authUser, [8, 9, 10]) && proyecto.modificable == true)}
                <LoadingButton loading={$form.processing} class="ml-auto" type="submit">Guardar</LoadingButton>
            {/if}
        </div>
    </form>
</AuthenticatedLayout>
