<script>
    import { monthDiff } from '@/Utils'

    import InputError from '@/Shared/InputError'
    import Label from '@/Shared/Label'
    import Textarea from '@/Shared/Textarea'
    import Select from '@/Shared/Select'
    import InfoMessage from '@/Shared/InfoMessage'
    import Input from '@/Shared/Input'
    import Dialog from '@/Shared/Dialog'
    import Button from '@/Shared/Button'
    import LoadingButton from '@/Shared/LoadingButton'
    import Export2Word from '@/Shared/Export2Word'

    export let id
    export let submit
    export let proyectoCapacidadInstalada
    export let errors
    export let form
    export let formPlanteamientoProblema
    export let formJustificacion
    export let formObjetivoGeneral
    export let formMetodologia
    export let formMaterialesFormacion
    export let formConclusiones
    export let formBibliografia
    export let centrosFormacion
    export let lineasInvestigacion
    export let semillerosInvestigacion = []
    export let redesConocimiento
    export let areasConocimiento
    export let subareasConocimiento = []
    export let disciplinasSubareaConocimiento = []
    export let actividadesEconomicas
    export let tiposProyectoCapacidadInstalada
    export let subtiposProyectoCapacidadInstalada = []
    export let listaBeneficiados
    export let roles
    export let dialogGuardar = false
    export let allowedToCreate
    export let method = null

    let exportComponent

    let oldLineaInvestigacionIdValue = null
    let arraySemillerosInvestigacion = semillerosInvestigacion
    $: if ($form.linea_investigacion_id) {
        if (oldLineaInvestigacionIdValue != $form.linea_investigacion_id?.value) {
            arraySemillerosInvestigacion = semillerosInvestigacion.filter(function (obj) {
                oldLineaInvestigacionIdValue = $form.linea_investigacion_id?.value
                return obj.linea_investigacion_id == $form.linea_investigacion_id?.value
            })
        }
    }

    let oldAreaConocimientoIdValue = null
    let arraySubareasConocimiento = []
    $: if ($form.area_conocimiento_id) {
        if (oldAreaConocimientoIdValue != $form.area_conocimiento_id?.value) {
            arraySubareasConocimiento = subareasConocimiento.filter(function (obj) {
                oldAreaConocimientoIdValue = $form.area_conocimiento_id?.value
                return obj.area_conocimiento_id == $form.area_conocimiento_id?.value
            })
        }
    }

    let oldSubareaConocimientoIdValue = null
    let arrayDisciplinasSubareaConocimiento = []
    $: if ($form.subarea_conocimiento_id) {
        if (oldSubareaConocimientoIdValue != $form.subarea_conocimiento_id?.value) {
            arrayDisciplinasSubareaConocimiento = disciplinasSubareaConocimiento.filter(function (obj) {
                oldSubareaConocimientoIdValue = $form.subarea_conocimiento_id?.value
                return obj.subarea_conocimiento_id == $form.subarea_conocimiento_id?.value
            })
        }
    }

    let oldTipoProyectoIdValue = null
    let arraySubtiposProyectoCapacidadInstalada = []
    $: if ($form.tipo_proyecto_capacidad_instalada_id) {
        if (oldTipoProyectoIdValue != $form.tipo_proyecto_capacidad_instalada_id?.value) {
            arraySubtiposProyectoCapacidadInstalada = subtiposProyectoCapacidadInstalada.filter(function (obj) {
                oldTipoProyectoIdValue = $form.tipo_proyecto_capacidad_instalada_id?.value
                return obj.tipo_proyecto_capacidad_instalada_id == $form.tipo_proyecto_capacidad_instalada_id?.value
            })
        }
    }

    let arrayLineasInvestigacion = lineasInvestigacion
    let oldCentroFormacionValue = null
    $: if ($form.centro_formacion_id) {
        if (oldCentroFormacionValue != $form.centro_formacion_id?.value) {
            arrayLineasInvestigacion = lineasInvestigacion.filter(function (obj) {
                oldCentroFormacionValue = $form.centro_formacion_id?.value
                return obj.centro_formacion_id == $form.centro_formacion_id?.value
            })
        }
    }
</script>

<form {id} on:submit|preventDefault={submit}>
    <fieldset class="p-8" disabled={proyectoCapacidadInstalada?.allowed.to_update || allowedToCreate ? undefined : true}>
        <div class="mt-28">
            <Label required labelFor="titulo" class="font-medium inline-block mb-10 text-center text-gray-700 text-sm w-full" value="Descripción llamativa que orienta el enfoque del proyecto, indica el cómo y el para qué. (Máximo 20 palabras)" />
            <Textarea label="Título" id="titulo" sinContador={true} error={errors.titulo} bind:value={$form.titulo} classes="bg-transparent block border-0 {errors.titulo ? '' : 'outline-none-important'} mt-1 outline-none text-4xl text-center w-full" required />
        </div>

        <div class="mt-44">
            <p class="text-center">Fecha de ejecución</p>
            {#if proyectoCapacidadInstalada?.allowed.to_update || allowedToCreate}
                <small class="text-red-400 block text-center"> * Campo obligatorio </small>
            {/if}
            <div class="mt-4 flex items-start justify-around">
                <div class="mt-4 flex {errors.fecha_inicio ? '' : 'items-center'}">
                    <Label labelFor="fecha_inicio" class={errors.fecha_inicio ? 'top-3.5 relative' : ''} value="Del" />
                    <div class="ml-4">
                        <input id="fecha_inicio" type="date" class="mt-1 block w-full p-4" error={errors.fecha_inicio} bind:value={$form.fecha_inicio} required />
                    </div>
                </div>
                <div class="mt-4 flex {errors.fecha_finalizacion ? '' : 'items-center'}">
                    <Label labelFor="fecha_finalizacion" class={errors.fecha_finalizacion ? 'top-3.5 relative' : ''} value="hasta" />
                    <div class="ml-4">
                        <input id="fecha_finalizacion" type="date" class="mt-1 block w-full p-4" error={errors.fecha_finalizacion} bind:value={$form.fecha_finalizacion} required />
                    </div>
                </div>
            </div>
            {#if errors.fecha_inicio || errors.fecha_finalizacion || errors.max_meses_ejecucion}
                <div class="mb-20 flex justify-center mt-4">
                    <InputError classes="text-center" message={errors.fecha_inicio} />
                    <InputError classes="text-center" message={errors.fecha_finalizacion} />
                </div>
            {/if}
        </div>
        <div class="mt-44 grid grid-cols-2">
            <div>
                <Label required class="mb-4" labelFor="centro_formacion_id" value="Centro de formación" />
            </div>
            <div>
                <Select id="centro_formacion_id" items={centrosFormacion} bind:selectedValue={$form.centro_formacion_id} error={errors.centro_formacion_id} autocomplete="off" placeholder="Busque por el nombre del centro de formación" required />
            </div>
        </div>

        {#if $form.centro_formacion_id?.value}
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="linea_investigacion_id" value="Línea de investigación" />
                </div>
                <div>
                    <Select id="linea_investigacion_id" items={arrayLineasInvestigacion} bind:selectedValue={$form.linea_investigacion_id} error={errors.linea_investigacion_id} autocomplete="off" placeholder="Busque por el nombre de la línea de investigación, centro de formación, grupo de investigación o regional" required />
                </div>
            </div>
        {/if}
        {#if $form.linea_investigacion_id}
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="semillero_investigacion_id" value="Semillero de investigación" />
                </div>
                <div>
                    <Select id="semillero_investigacion_id" items={arraySemillerosInvestigacion} bind:selectedValue={$form.semillero_investigacion_id} error={errors.semillero_investigacion_id} autocomplete="off" placeholder="Busque por el nombre del semillero de investigación" required />
                </div>
            </div>
        {/if}
        <div class="mt-44 grid grid-cols-2">
            <div>
                <Label required class="mb-4" labelFor="red_conocimiento_id" value="Red de conocimiento sectorial" />
            </div>
            <div>
                <Select id="red_conocimiento_id" items={redesConocimiento} bind:selectedValue={$form.red_conocimiento_id} error={errors.red_conocimiento_id} autocomplete="off" placeholder="Busque por el nombre de la red de conocimiento sectorial" required />
            </div>
        </div>
        <div class="mt-44 grid grid-cols-2">
            <div>
                <Label required class="mb-4" labelFor="area_conocimiento_id" value="Área de conocimiento" />
            </div>
            <div>
                <Select id="area_conocimiento_id" items={areasConocimiento} bind:selectedValue={$form.area_conocimiento_id} error={errors.area_conocimiento_id} autocomplete="off" placeholder="Busque por el nombre de la área de conocimiento" required />
            </div>
        </div>
        {#if $form.area_conocimiento_id}
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="subarea_conocimiento_id" value="Subárea de conocimiento" />
                </div>
                <div>
                    <Select id="subarea_conocimiento_id" items={arraySubareasConocimiento} bind:selectedValue={$form.subarea_conocimiento_id} error={errors.subarea_conocimiento_id} autocomplete="off" placeholder="Busque por el nombre de la subárea de conocimiento" required />
                </div>
            </div>
        {/if}
        {#if $form.subarea_conocimiento_id}
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="disciplina_subarea_conocimiento_id" value="Disciplina de la subárea de conocimiento" />
                </div>
                <div>
                    <Select id="disciplina_subarea_conocimiento_id" items={arrayDisciplinasSubareaConocimiento} bind:selectedValue={$form.disciplina_subarea_conocimiento_id} error={errors.disciplina_subarea_conocimiento_id} autocomplete="off" placeholder="Busque por el nombre de la disciplina de subáreas de conocimiento" required />
                </div>
            </div>
        {/if}
        <div class="mt-44 grid grid-cols-2">
            <div>
                <Label required class="mb-4" labelFor="actividad_economica_id" value="¿En cuál de estas actividades económicas se puede aplicar el proyecto?" />
            </div>
            <div>
                <Select id="actividad_economica_id" items={actividadesEconomicas} bind:selectedValue={$form.actividad_economica_id} error={errors.actividad_economica_id} autocomplete="off" placeholder="Busque por el nombre de la actividad económica" required />
            </div>
        </div>

        <div class="mt-44 grid grid-cols-2">
            <div>
                <Label required class="mb-4" labelFor="tipo_proyecto_capacidad_instalada_id" value="Tipo de proyecto" />
            </div>
            <div>
                <Select id="tipo_proyecto_capacidad_instalada_id" items={tiposProyectoCapacidadInstalada} bind:selectedValue={$form.tipo_proyecto_capacidad_instalada_id} error={errors.tipo_proyecto_capacidad_instalada_id} autocomplete="off" placeholder="Busque por el nombre del tipo de proyecto" required />
            </div>
        </div>
        {#if $form.tipo_proyecto_capacidad_instalada_id}
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="subtipo_proyecto_capacidad_instalada_id" value="Subtipo de proyecto" />
                </div>
                <div>
                    <Select id="subtipo_proyecto_capacidad_instalada_id" items={arraySubtiposProyectoCapacidadInstalada} bind:selectedValue={$form.subtipo_proyecto_capacidad_instalada_id} error={errors.subtipo_proyecto_capacidad_instalada_id} autocomplete="off" placeholder="Busque por el nombre del subtipo de proyecto" required />
                </div>
            </div>
        {/if}

        <div class="mt-44 grid grid-cols-2">
            <div>
                <Label required class="mb-4" labelFor="beneficia_a" value="El proyecto beneficiará a:" />
            </div>
            <div>
                <Select id="beneficia_a" items={listaBeneficiados} bind:selectedValue={$form.beneficia_a} error={errors.centro_formacion_id} autocomplete="off" placeholder="Seleccione una opción" required />
            </div>
        </div>

        <slot />

        <hr class="mt-32 mb-32" />

        <h1 class="text-2xl text-center" id="estructura-proyecto">Participación del autor principal</h1>

        <div class="mt-44 grid grid-cols-2">
            <div>
                <Label required class="mb-4" labelFor="rol_sennova" value="Rol SENNOVA" />
            </div>

            <div>
                <Select id="rol_sennova" items={roles} bind:selectedValue={$form.rol_sennova} error={errors.rol_sennova} autocomplete="off" placeholder="Seleccione un rol SENNOVA" required />
            </div>
        </div>

        <div class="mt-44 grid grid-cols-2">
            <div>
                <Label required class="mb-4" labelFor="cantidad_meses" value="Número de meses de vinculación al proyecto" />
            </div>
            <div>
                <Input label="Número de meses de vinculación" id="cantidad_meses" type="number" input$step="0.1" input$min="1" input$max={monthDiff($form.fecha_inicio, $form.fecha_finalizacion)} class="mt-1" bind:value={$form.cantidad_meses} placeholder="Número de meses de vinculación" autocomplete="off" required />
            </div>
        </div>

        <div class="mt-44 grid grid-cols-2">
            <div>
                <Label required class="mb-4" labelFor="cantidad_horas" value="Número de horas semanales dedicadas para el desarrollo del proyecto" />
            </div>
            <div>
                <Input label="Número de horas semanales dedicadas para el desarrollo del proyecto" id="cantidad_horas" type="number" input$step="1" input$min="1" input$max={$form.rol_sennova?.maxHoras} class="mt-1" bind:value={$form.cantidad_horas} placeholder="Número de horas semanales dedicadas para el desarrollo del proyecto" autocomplete="off" required />
                {#if $form.rol_sennova?.maxHoras}
                    <InfoMessage>Horas máximas permitidas para este rol: {$form.rol_sennova?.maxHoras}.</InfoMessage>
                {/if}
            </div>
        </div>
    </fieldset>

    <slot name="buttons" />
</form>

<Dialog bind:open={dialogGuardar}>
    <div slot="title">
        <div class="relative bg-violet-100 text-violet-600 p-5 h-36 w-1/3 m-auto my-10" style="border-radius: 41% 59% 70% 30% / 32% 40% 60% 68% ;">
            <figure>
                <img src="/images/megaphone.png" alt="" class="m-auto" />
            </figure>
        </div>
    </div>
    <div slot="header-info" class="ml-4">
        <InfoMessage>
            Se recomienda que antes de dar clic en el botón <strong>Guardar</strong> descargue el borrador en archivo Word. De esta manera si ocurre un error al guardar puede recuperar la información registrada. Luego de descargar el borrador de clic en el botón <strong>Guardar</strong>. Revise que se muestra un mensaje en verde que dice '<strong>
                El recurso se ha modificado correctamente</strong
            >'. Si después de unos segundos no se muestra el mensaje y al recargar el aplicativo observa que la información no se ha guardado por favor envie un correo a <a href="mailto:sgpssipro@sena.edu.co" class="underline">sgpssipro@sena.edu.co</a>
            desde una cuenta <strong>@sena.edu.co</strong> y describa detalladamente lo ocurrido (Importante adjuntar el borrador e indicar el código del proyecto).
        </InfoMessage>
    </div>
    <div slot="content">
        <Export2Word id="borrador" showButton={false} bind:this={exportComponent}>
            <h1 class="font-black text-center my-10">Información del proyecto</h1>
            <p style="white-space: pre-line; margin-bottom: 4rem">
                <strong>Centro de formación:</strong>
                {$form.centro_formacion_id?.label}
            </p>
            <p style="white-space: pre-line; margin-bottom: 4rem">
                <strong>Línea de investigación:</strong>
                {$form.linea_investigacion_id?.label}
            </p>
            <p style="white-space: pre-line; margin-bottom: 4rem;">
                <strong>Semillero de investigación:</strong>
                {$form.semillero_investigacion_id?.label}
            </p>
            <p style="white-space: pre-line; margin-bottom: 4rem;">
                <strong>Área de conocimiento:</strong>
                {$form.area_conocimiento_id?.label}
            </p>
            <p style="white-space: pre-line; margin-bottom: 4rem;">
                <strong>Subárea de conocimiento:</strong>
                {$form.subarea_conocimiento_id?.label}
            </p>
            <p style="white-space: pre-line; margin-bottom: 4rem;">
                <strong>Disciplina de la subárea de conocimiento:</strong>
                {$form.disciplina_subarea_conocimiento_id?.label}
            </p>
            <p style="white-space: pre-line; margin-bottom: 4rem;">
                <strong>Red de conocimiento:</strong>
                {$form.red_conocimiento_id?.label}
            </p>
            <p style="white-space: pre-line; margin-bottom: 4rem;">
                <strong>Actividad económica:</strong>
                {$form.actividad_economica_id?.label}
            </p>
            <p style="white-space: pre-line; margin-bottom: 4rem;">
                <strong>Tipo de proyecto:</strong>
                {$form.tipo_proyecto_capacidad_instalada_id?.label}
            </p>
            <p style="white-space: pre-line; margin-bottom: 4rem;">
                <strong>Subtipo de proyecto:</strong>
                {$form.subtipo_proyecto_capacidad_instalada_id?.label}
            </p>
            <p style="white-space: pre-line; margin-bottom: 4rem;">
                <strong>Descripción llamativa que orienta el enfoque del proyecto, indica el cómo y el para qué:</strong>
                {$form.titulo}
            </p>
            <p style="white-space: pre-line; margin-bottom: 4rem">
                <strong>Fecha de ejecución:</strong>
                Del {$form.fecha_inicio + ' hasta ' + $form.fecha_finalizacion}
            </p>
            <p style="white-space: pre-line; margin-bottom: 4rem">
                <strong>El proyecto beneficiará a:</strong>
                <br />
                {$form.beneficia_a?.label}
            </p>
            <p style="white-space: pre-line; margin-bottom: 4rem">
                <strong>Planteamiento del problema</strong>
                <br />
                {$formPlanteamientoProblema.planteamiento_problema}
            </p>
            <p style="white-space: pre-line; margin-bottom: 4rem">
                <strong>Justificación</strong>
                <br />
                {$formJustificacion.justificacion}
            </p>
            <p style="white-space: pre-line; margin-bottom: 4rem">
                <strong>Objetivo general</strong>
                <br />
                {$formObjetivoGeneral.objetivo_general}
            </p>
            <p style="white-space: pre-line; margin-bottom: 4rem">
                <strong>Metodología</strong>
                <br />
                {$formMetodologia.metodologia}
            </p>
            <p style="white-space: pre-line; margin-bottom: 4rem">
                <strong>Materiales de formación a utilizar</strong>
                <br />
                {$formMaterialesFormacion.materiales_formacion_a_usar}
            </p>
            <p style="white-space: pre-line; margin-bottom: 4rem">
                <strong>Nombre de los programas de formación con registro calificado:</strong>
                <br />
                {#if $form.programas_formacion_registro_calificado}
                    {#each $form.programas_formacion_registro_calificado as programa_formacion}
                        <br />
                        {programa_formacion.label}
                    {/each}
                {:else}
                    Sin información registrada
                {/if}
            </p>
            <p style="white-space: pre-line; margin-bottom: 4rem">
                <strong>Nombre de los programas de formación a los cuales está asociado el proyecto:</strong>
                <br />
                {#if $form.programas_formacion_sin_registro_calificado}
                    {#each $form.programas_formacion_sin_registro_calificado as programa_formacion}
                        <br />
                        {programa_formacion.label}
                    {/each}
                {:else}
                    Sin información registrada
                {/if}
            </p>
            <p style="white-space: pre-line; margin-bottom: 4rem">
                <strong>Conclusiones</strong>
                <br />
                {$formConclusiones.conclusiones}
            </p>
            <p style="white-space: pre-line; margin-bottom: 4rem">
                <strong>Bibliografía</strong>
                <br />
                {$formBibliografia.bibliografia}
            </p>
        </Export2Word>
    </div>
    <div slot="actions">
        <div class="p-4">
            <Button on:click={() => (dialogGuardar = false)} variant={null}>Cancelar</Button>
            <Button variant="raised" type="button" on:click={() => exportComponent.export2Word(proyectoCapacidadInstalada.codigo)}>Descargar borrador en Word</Button>
            {#if proyectoCapacidadInstalada?.allowed.to_update || allowedToCreate}
                <LoadingButton loading={$form.processing} form="capacidad-instalada-form">Guardar</LoadingButton>
            {/if}
        </div>
    </div>
</Dialog>
