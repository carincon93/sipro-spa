<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { useForm, page } from '@inertiajs/inertia-svelte'
    import { Inertia } from '@inertiajs/inertia'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Dialog from '@/Shared/Dialog'
    import Button from '@/Shared/Button'
    import Label from '@/Shared/Label'
    import Textarea from '@/Shared/Textarea'
    import SelectMulti from '@/Shared/SelectMulti'
    import InfoMessage from '@/Shared/InfoMessage'

    import Header from './Shared/Header'
    import Form from './Form'

    export let errors
    export let centrosFormacion
    export let proyectoCapacidadInstalada
    export let listaBeneficiados
    export let programasFormacion
    export let lineasInvestigacion
    export let semillerosInvestigacion
    export let areasConocimiento
    export let subareasConocimiento
    export let disciplinasSubareaConocimiento
    export let redesConocimiento
    export let actividadesEconomicas
    export let tiposProyectoCapacidadInstalada
    export let subtiposProyectoCapacidadInstalada
    export let programasFormacionRegistroAsociados
    export let programasFormacionSinRegistroAsociados
    export let roles
    export let autorPrincipal

    $: $title = 'Editar proyecto de capacidad instalada'

    /**
     * Validar si el usuario autenticado es SuperAdmin
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let proyectoDialogOpen = true
    let dialogGuardar = false

    let programasFormacionConRegistro = []
    let programasFormacionSinRegistro = []

    let oldCentroFormacionValue = null
    $: if ($form.centro_formacion_id) {
        if (oldCentroFormacionValue != $form.centro_formacion_id?.value) {
            programasFormacionConRegistro = programasFormacion.filter(function (obj) {
                oldCentroFormacionValue = $form.centro_formacion_id?.value
                return obj.registro_calificado == true && obj.centro_formacion_id == $form.centro_formacion_id?.value
            })
        }
    }
    programasFormacionSinRegistro = programasFormacion.filter((obj) => obj.registro_calificado == false)

    let formPlanteamientoProblema = useForm({
        planteamiento_problema: proyectoCapacidadInstalada.planteamiento_problema,
    })

    let formJustificacion = useForm({
        justificacion: proyectoCapacidadInstalada.justificacion,
    })

    let formObjetivoGeneral = useForm({
        objetivo_general: proyectoCapacidadInstalada.objetivo_general,
    })

    let formMetodologia = useForm({
        metodologia: proyectoCapacidadInstalada.metodologia,
    })

    let formInfraestructuraDesarrolloProyecto = useForm({
        infraestructura_desarrollo_proyecto: proyectoCapacidadInstalada.infraestructura_desarrollo_proyecto,
    })

    let formMaterialesFormacion = useForm({
        materiales_formacion_a_usar: proyectoCapacidadInstalada.materiales_formacion_a_usar,
    })

    let formConclusiones = useForm({
        conclusiones: proyectoCapacidadInstalada.conclusiones,
    })

    let formBibliografia = useForm({
        bibliografia: proyectoCapacidadInstalada.bibliografia,
    })

    let form = useForm({
        centro_formacion_id: {
            value: centrosFormacion.find((item) => item.value == proyectoCapacidadInstalada.semillero_investigacion.linea_investigacion.grupo_investigacion.centro_formacion_id)?.value,
            label: centrosFormacion.find((item) => item.value == proyectoCapacidadInstalada.semillero_investigacion.linea_investigacion.grupo_investigacion.centro_formacion_id)?.label,
        },
        linea_investigacion_id: {
            value: proyectoCapacidadInstalada.semillero_investigacion.linea_investigacion_id,
            label: lineasInvestigacion.find((item) => item.value == proyectoCapacidadInstalada.semillero_investigacion.linea_investigacion_id)?.label,
        },
        semillero_investigacion_id: {
            value: proyectoCapacidadInstalada.semillero_investigacion_id,
            label: semillerosInvestigacion.find((item) => item.value == proyectoCapacidadInstalada.semillero_investigacion_id)?.label,
        },
        area_conocimiento_id: {
            value: proyectoCapacidadInstalada.disciplina_subarea_conocimiento.subarea_conocimiento.area_conocimiento_id,
            label: areasConocimiento.find((item) => item.value == proyectoCapacidadInstalada.disciplina_subarea_conocimiento.subarea_conocimiento.area_conocimiento_id)?.label,
        },
        subarea_conocimiento_id: {
            value: proyectoCapacidadInstalada.disciplina_subarea_conocimiento.subarea_conocimiento_id,
            label: subareasConocimiento.find((item) => item.value == proyectoCapacidadInstalada.disciplina_subarea_conocimiento.subarea_conocimiento_id)?.label,
        },
        disciplina_subarea_conocimiento_id: {
            value: proyectoCapacidadInstalada.disciplina_subarea_conocimiento_id,
            label: disciplinasSubareaConocimiento.find((item) => item.value == proyectoCapacidadInstalada.disciplina_subarea_conocimiento_id)?.label,
        },
        red_conocimiento_id: {
            value: proyectoCapacidadInstalada.red_conocimiento_id,
            label: redesConocimiento.find((item) => item.value == proyectoCapacidadInstalada.red_conocimiento_id)?.label,
        },
        actividad_economica_id: {
            value: proyectoCapacidadInstalada.actividad_economica_id,
            label: actividadesEconomicas.find((item) => item.value == proyectoCapacidadInstalada.actividad_economica_id)?.label,
        },
        tipo_proyecto_capacidad_instalada_id: {
            value: proyectoCapacidadInstalada.subtipo_proyecto_capacidad_instalada.tipo_proyecto_capacidad_instalada_id,
            label: tiposProyectoCapacidadInstalada.find((item) => item.value == proyectoCapacidadInstalada.subtipo_proyecto_capacidad_instalada.tipo_proyecto_capacidad_instalada_id)?.label,
        },
        subtipo_proyecto_capacidad_instalada_id: {
            value: proyectoCapacidadInstalada.subtipo_proyecto_capacidad_instalada_id,
            label: subtiposProyectoCapacidadInstalada.find((item) => item.value == proyectoCapacidadInstalada.subtipo_proyecto_capacidad_instalada_id)?.label,
        },
        titulo: proyectoCapacidadInstalada.titulo,
        fecha_inicio: proyectoCapacidadInstalada.fecha_inicio,
        fecha_finalizacion: proyectoCapacidadInstalada.fecha_finalizacion,
        beneficia_a: {
            value: listaBeneficiados.find((item) => item.value == proyectoCapacidadInstalada.beneficia_a)?.value,
            label: listaBeneficiados.find((item) => item.value == proyectoCapacidadInstalada.beneficia_a)?.label,
        },
        programas_formacion_registro_calificado: programasFormacionRegistroAsociados.length > 0 ? programasFormacionRegistroAsociados : null,
        programas_formacion_sin_registro_calificado: programasFormacionSinRegistroAsociados.length > 0 ? programasFormacionSinRegistroAsociados : null,
        rol_sennova: {
            value: autorPrincipal ? roles.find((item) => item.value == autorPrincipal.pivot.rol_sennova)?.value : null,
            label: autorPrincipal ? roles.find((item) => item.value == autorPrincipal.pivot.rol_sennova)?.label : null,
        },
        cantidad_meses: autorPrincipal ? autorPrincipal.pivot.cantidad_meses : null,
        cantidad_horas: autorPrincipal ? autorPrincipal.pivot.cantidad_horas : null,
    })

    function submit() {
        if (proyectoCapacidadInstalada.allowed.to_update) {
            $form.put(route('proyectos-capacidad-instalada.update', proyectoCapacidadInstalada.id))
        }
    }

    async function syncColumnLong(column, form) {
        return new Promise((resolve) => {
            if (typeof column !== 'undefined' && typeof form !== 'undefined') {
                Inertia.put(
                    route('proyectos-capacidad-instalada.updateLongColumn', [proyectoCapacidadInstalada.id, column]),
                    { [column]: form[column] },
                    {
                        onError: (resp) => resolve(resp),
                        onFinish: () => resolve({}),
                        preserveScroll: true,
                    },
                )
            } else {
                resolve({})
            }
        })
    }
</script>

<AuthenticatedLayout>
    <header class="shadow bg-white" slot="header">
        <Header {proyectoCapacidadInstalada} active={true} />
    </header>

    <Form
        id="capacidad-instalada-form"
        {submit}
        {proyectoCapacidadInstalada}
        {errors}
        {form}
        {formPlanteamientoProblema}
        {formJustificacion}
        {formObjetivoGeneral}
        {formMetodologia}
        {formMaterialesFormacion}
        {formConclusiones}
        {formBibliografia}
        {centrosFormacion}
        {lineasInvestigacion}
        {semillerosInvestigacion}
        {redesConocimiento}
        {areasConocimiento}
        {subareasConocimiento}
        {disciplinasSubareaConocimiento}
        {actividadesEconomicas}
        {tiposProyectoCapacidadInstalada}
        {subtiposProyectoCapacidadInstalada}
        {listaBeneficiados}
        {roles}
        {programasFormacion}
        {dialogGuardar}
    >
        <div class="mt-44 grid grid-cols-1">
            <div>
                <Label required class="mb-4" labelFor="planteamiento_problema" value="Planteamiento del problema" />
            </div>
            <div>
                <Textarea maxlength="40000" id="planteamiento_problema" error={errors.planteamiento_problema} bind:value={$formPlanteamientoProblema.planteamiento_problema} on:input={() => syncColumnLong('planteamiento_problema', $formPlanteamientoProblema)} required />
            </div>
        </div>

        <div class="mt-44 grid grid-cols-1">
            <div>
                <Label required class="mb-4" labelFor="justificacion" value="Justificación" />
            </div>
            <div>
                <Textarea maxlength="40000" id="justificacion" error={errors.justificacion} bind:value={$formJustificacion.justificacion} on:input={() => syncColumnLong('justificacion', $formJustificacion)} required />
            </div>
        </div>

        <div class="mt-44 grid grid-cols-1">
            <div>
                <Label required class="mb-4" labelFor="objetivo_general" value="Objetivo general" />
            </div>
            <div>
                <Textarea maxlength="40000" id="objetivo_general" error={errors.objetivo_general} bind:value={$formObjetivoGeneral.objetivo_general} on:input={() => syncColumnLong('objetivo_general', $formObjetivoGeneral)} required />
            </div>
        </div>

        <div class="mt-44 grid grid-cols-1">
            <div>
                <Label required class="mb-4" labelFor="metodologia" value="Metodología" />
            </div>
            <div>
                <Textarea maxlength="40000" id="metodologia" error={errors.metodologia} bind:value={$formMetodologia.metodologia} on:input={() => syncColumnLong('metodologia', $formMetodologia)} required />
            </div>
        </div>

        <div class="mt-44 grid grid-cols-1">
            <div>
                <Label required class="mb-4" labelFor="infraestructura_desarrollo_proyecto" value="Infraestructura para el desarrollo del proyecto" />
            </div>
            <div>
                <Textarea
                    label="Describr los ambientes de formación y equipos que se van a usar"
                    maxlength="40000"
                    id="infraestructura_desarrollo_proyecto"
                    error={errors.infraestructura_desarrollo_proyecto}
                    bind:value={$formInfraestructuraDesarrolloProyecto.infraestructura_desarrollo_proyecto}
                    on:input={() => syncColumnLong('infraestructura_desarrollo_proyecto', $formInfraestructuraDesarrolloProyecto)}
                    required
                />
            </div>
        </div>

        <div class="mt-44 grid grid-cols-1">
            <div>
                <Label required class="mb-4" labelFor="materiales_formacion_a_usar" value="Materiales de formación a utilizar" />
            </div>
            <div>
                <Textarea label="Describir los materiales de formación que se van a utilizar" maxlength="40000" id="materiales_formacion_a_usar" error={errors.materiales_formacion_a_usar} bind:value={$formMaterialesFormacion.materiales_formacion_a_usar} on:input={() => syncColumnLong('materiales_formacion_a_usar', $formMaterialesFormacion)} required />
            </div>
        </div>

        <div class="mt-44 grid grid-cols-2">
            <div>
                <Label required class="mb-4" labelFor="programas_formacion_registro_calificado" value="Nombre de los programas de formación con registro calificado" />
            </div>
            <div>
                <SelectMulti id="programas_formacion_registro_calificado" bind:selectedValue={$form.programas_formacion_registro_calificado} items={programasFormacionConRegistro} isMulti={true} error={errors.programas_formacion_registro_calificado} placeholder="Buscar por el nombre del programa de formación" required />
            </div>
        </div>

        <div class="mt-44 grid grid-cols-2">
            <div>
                <Label required class="mb-4" labelFor="programas_formacion_sin_registro_calificado" value="Nombre de los programas de formación a los cuales está asociado el proyecto " />
            </div>
            <div>
                <SelectMulti id="programas_formacion_sin_registro_calificado" bind:selectedValue={$form.programas_formacion_sin_registro_calificado} items={programasFormacionSinRegistro} isMulti={true} error={errors.programas_formacion_sin_registro_calificado} placeholder="Buscar por el nombre del programa de formación" required />
            </div>
        </div>

        <div class="mt-44 grid grid-cols-1">
            <div>
                <Label required class="mb-4" labelFor="conclusiones" value="Conclusiones" />
            </div>
            <div>
                <Textarea maxlength="40000" id="conclusiones" error={errors.conclusiones} bind:value={$formConclusiones.conclusiones} on:input={() => syncColumnLong('conclusiones', $formConclusiones)} required />
            </div>
        </div>

        <div class="mt-44 grid grid-cols-1">
            <div>
                <Label required class="mb-4" labelFor="bibliografia" value="Bibliografía" />
                <InfoMessage class="mb-2" message="Lista de las referencias utilizadas en cada apartado del proyecto. Utilizar normas APA- Última edición (http://biblioteca.sena.edu.co/images/PDF/InstructivoAPA.pdf)." />
            </div>
            <div>
                <Textarea maxlength="40000" id="bibliografia" error={errors.bibliografia} bind:value={$formBibliografia.bibliografia} on:input={() => syncColumnLong('bibliografia', $formBibliografia)} required />
            </div>
        </div>

        <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center justify-between sticky bottom-0" slot="buttons">
            <small class="block leading-tight">{proyectoCapacidadInstalada.updated_at}</small>

            {#if proyectoCapacidadInstalada.allowed.to_update}
                <Button on:click={() => (dialogGuardar = true)} loading={$form.processing} class="ml-auto" type="button">¿Desea guardar la información?</Button>
            {:else}
                <span class="inline-block"> El proyecto no se puede modificar </span>
            {/if}
        </div>
    </Form>
    <Dialog bind:open={proyectoDialogOpen} id="informacion">
        <div slot="title" class="flex items-center flex-col mt-4">
            <figure>
                <img src={window.basePath + '/images/proyecto.png'} alt="Proyecto" class="h-32 mb-6" />
            </figure>
            Código del proyecto: {proyectoCapacidadInstalada.codigo}
        </div>
        <div slot="content">
            <div>
                <h1 class="text-center mt-4 mb-4">Para terminar el numeral de <strong>Información básica</strong> por favor continue diligenciando los siguientes campos:</h1>
                <p class="text-center mb-4">Si ya están completos omita esta información.</p>
                <ul class="list-disc">
                    <li>Justificación</li>
                    <li>Plan tecnológico</li>
                    <li>Objetivo general</li>
                    <li>Metodología</li>
                    <li>Infraestructura para el desarrollo del proyecto</li>
                    <li>Materiales de formación a utilizar</li>
                    <li>Planteamiento del problema</li>
                    <li>Conclusiones</li>
                    <li>Bibliografía</li>
                </ul>
            </div>
        </div>
        <div slot="actions">
            <div class="p-4">
                <Button on:click={() => (proyectoDialogOpen = false)} variant={null}>Omitir</Button>
                {#if proyectoCapacidadInstalada.allowed.to_update}
                    <Button variant="raised" on:click={() => (proyectoDialogOpen = false)} on:click={() => Inertia.visit('#beneficia_a')}>Continuar diligenciando</Button>
                {/if}
            </div>
        </div>
    </Dialog>
</AuthenticatedLayout>
