<script>
    import Label from '@/Shared/Label'
    import Input from '@/Shared/Input'
    import Textarea from '@/Shared/Textarea'
    import SelectMulti from '@/Shared/SelectMulti'
    import Select from '@/Shared/Select'
    import InfoMessage from '@/Shared/InfoMessage'
    import LoadingButton from '@/Shared/LoadingButton'
    import Button from '@/Shared/Button'
    import Dialog from '@/Shared/Dialog'
    import Export2Word from '@/Shared/Export2Word'

    export let semilleroInvestigacion
    export let form
    export let errors
    export let submit
    export let opcionesSiNo
    export let lineasInvestigacion
    export let programasFormacion
    export let programasFormacionSemilleroInvestigacion
    export let redesConocimiento
    export let allowedToCreate

    let dialogGuardar = false
    let exportComponent

    let oldLineaInvestigacionIdValue = null
    let arrayProgramasFormacion = programasFormacion
    $: if ($form.linea_investigacion) {
        if (oldLineaInvestigacionIdValue != $form.linea_investigacion?.value) {
            arrayProgramasFormacion = programasFormacion.filter(function (obj) {
                oldLineaInvestigacionIdValue = $form.linea_investigacion?.value
                return obj.linea_investigacion_id == $form.linea_investigacion?.value
            })
        }
    }
</script>

<form on:submit|preventDefault={submit} class="bg-white rounded shadow" id="semillero-investigacion-form">
    <fieldset class="p-8">
        {#if semilleroInvestigacion}
            <div class="mt-4">
                <Label labelFor="nombre" value="Código" />
                <Input disabled id="codigo" type="text" class="mt-1" bind:value={semilleroInvestigacion.codigo} error={errors.codigo} required />
            </div>
        {/if}

        <div class="mt-8">
            <Label required class="mb-4" labelFor="es_semillero_tecnoacademia" value="¿Es semillero de TecnoAcademia?" />
            <Select items={opcionesSiNo} id="es_semillero_tecnoacademia" bind:selectedValue={$form.es_semillero_tecnoacademia} error={errors.es_semillero_tecnoacademia} autocomplete="off" placeholder="Seleccione una opción" required />
        </div>

        <div class="mt-8">
            <Label required class="mb-4" labelFor="linea_investigacion_id" value="Línea de investigación principal" />
            <Select id="linea_investigacion_id" items={lineasInvestigacion} bind:selectedValue={$form.linea_investigacion_id} error={errors.linea_investigacion_id} autocomplete="off" placeholder="Seleccione una línea de investigación" required />
        </div>

        <div class="mt-8">
            <Label required class="mb-4" labelFor="lineas_investigacion" value="Articulación con líneas de investigación" />
            <SelectMulti id="lineas_investigacion" bind:selectedValue={$form.lineas_investigacion} items={lineasInvestigacion} isMulti={true} error={errors.lineas_investigacion} placeholder="Buscar por el nombre de la línea de investigación" required />
        </div>

        <div class="mt-4">
            <Label required labelFor="nombre" value="Nombre del semillero" />
            <Input id="nombre" type="text" class="mt-1" bind:value={$form.nombre} error={errors.nombre} required />
        </div>

        <div class="mt-4 ">
            <Label required labelFor="fecha_creacion_semillero" value="Fecha creación del semillero" />
            <input id="fecha_creacion_semillero" type="date" class="mt-1 p-2" bind:value={$form.fecha_creacion_semillero} required />
        </div>

        <div class="mt-4">
            <Label required labelFor="nombre_lider_semillero" value="Nombre del líder del semillero" />
            <Input id="nombre_lider_semillero" type="text" class="mt-1" bind:value={$form.nombre_lider_semillero} error={errors.nombre_lider_semillero} required />
        </div>

        <div class="mt-4">
            <Label required labelFor="email_contacto" value="Email de contacto" />
            <Input id="email_contacto" type="email" class="mt-1" bind:value={$form.email_contacto} error={errors.email_contacto} required />
        </div>

        <div class="mt-4">
            <Label required labelFor="reconocimientos_semillero_investigacion" value="Reconocimientos semillero de investigación" />
            <Textarea maxlength="40000" id="reconocimientos_semillero_investigacion" bind:value={$form.reconocimientos_semillero_investigacion} error={errors.reconocimientos_semillero_investigacion} required />
        </div>

        <div class="mt-4">
            <Label required labelFor="vision" value="Visión" />
            <Textarea maxlength="40000" id="vision" bind:value={$form.vision} error={errors.vision} required />
        </div>

        <div class="mt-4">
            <Label required labelFor="mision" value="Misión" />
            <Textarea maxlength="40000" id="mision" bind:value={$form.mision} error={errors.mision} required />
        </div>

        <div class="mt-4">
            <Label required labelFor="objetivo_general" value="Objetivo general" />
            <Textarea maxlength="40000" id="objetivo_general" bind:value={$form.objetivo_general} error={errors.objetivo_general} required />
        </div>

        <div class="mt-4">
            <Label required labelFor="objetivos_especificos" value="Objetivos específicos " />
            <Textarea maxlength="40000" id="objetivos_especificos" bind:value={$form.objetivos_especificos} error={errors.objetivos_especificos} required />
        </div>

        <div class="mt-4">
            <Label labelFor="link_semillero" value="Link del semillero" />
            <Input id="link_semillero" type="url" class="mt-1" bind:value={$form.link_semillero} error={errors.link_semillero} />
        </div>

        <div class="mt-4">
            <Label required class="mb-4" labelFor="redes_conocimiento" value="Red o redes de conocimiento afines al Semillero de Investigación" />
            <SelectMulti id="redes_conocimiento" bind:selectedValue={$form.redes_conocimiento} items={redesConocimiento} isMulti={true} error={errors.redes_conocimiento} placeholder="Buscar redes de conocimiento" required />
        </div>

        <hr class="mt-10 mb-10" />

        <div class="mt-4">
            <Label required={$form.programas_formacion?.length > 0 ? undefined : 'required'} class="mb-4" labelFor="linea_investigacion" value="Seleccione una línea de investigación y posteriormente asocie los programas de formación" />
            <Select id="linea_investigacion" items={lineasInvestigacion} bind:selectedValue={$form.linea_investigacion} error={errors.linea_investigacion} autocomplete="off" placeholder="Seleccione una línea de investigación" required={$form.programas_formacion?.length > 0 ? undefined : 'required'} />
        </div>

        {#if ($form.linea_investigacion?.value && programasFormacion.length > 0) || programasFormacionSemilleroInvestigacion?.length > 0}
            <div class="mt-4">
                <Label required class="mb-4" labelFor="programas_formacion" value="Programa(s) de formación" />
                <SelectMulti id="programas_formacion" bind:selectedValue={$form.programas_formacion} items={arrayProgramasFormacion} isMulti={true} error={errors.programas_formacion} placeholder="Programa(s) de formación" required />
            </div>
        {:else if ($form.linea_investigacion?.value && programasFormacion.length == 0) || programasFormacionSemilleroInvestigacion?.length == 0}
            <InfoMessage alertMsg={true} class="mt-10">
                <p class="mt-3 py-4 text-justify">La línea de investigación seleccionada no tiene programas de formación asociados, por favor antes de crear/modificar semilleros de investigación debe actualizar las líneas de investigación.</p>
            </InfoMessage>
        {/if}
    </fieldset>

    <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center justify-between sticky bottom-0">
        {#if semilleroInvestigacion}
            <small class="block leading-tight">{semilleroInvestigacion.updated_at}</small>
        {/if}

        {#if semilleroInvestigacion?.allowed.to_update || allowedToCreate}
            <Button on:click={() => (dialogGuardar = true)} loading={$form.processing} class="ml-auto" type="button">¿Desea guardar la información?</Button>
        {:else}
            <span class="inline-block"> El semillero no se puede modificar </span>
        {/if}
    </div>
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
            desde una cuenta <strong>@sena.edu.co</strong> y describa detalladamente lo ocurrido (Importante adjuntar el borrador e indicar el código del semillero).
        </InfoMessage>
    </div>
    <div slot="content">
        <Export2Word id="borrador" showButton={false} bind:this={exportComponent}>
            <h1 class="font-black text-center my-10">Información del semillero de investigación</h1>
            <p style="white-space: pre-line; margin-bottom: 4rem">
                <strong>Nombre del semillero:</strong>
                {$form.nombre}
            </p>
            <p style="white-space: pre-line; margin-bottom: 4rem">
                <strong>Fecha creación del semillero:</strong>
                {$form.fecha_creacion_semillero}
            </p>
            <p style="white-space: pre-line; margin-bottom: 4rem">
                <strong>Línea de investigación principal:</strong>
                {$form.linea_investigacion_id?.label}
            </p>
            <p style="white-space: pre-line; margin-bottom: 4rem">
                <strong>Nombre del líder del semillero:</strong>
                {$form.nombre_lider_semillero}
            </p>
            <p style="white-space: pre-line; margin-bottom: 4rem">
                <strong>Correo electrónico de contacto:</strong>
                {$form.email_contacto}
            </p>
            <p style="white-space: pre-line; margin-bottom: 4rem">
                <strong>Reconocimientos semillero de investigación:</strong>
                {$form.reconocimientos_semillero_investigacion}
            </p>
            <p style="white-space: pre-line; margin-bottom: 4rem">
                <strong>Visión:</strong>
                {$form.vision}
            </p>
            <p style="white-space: pre-line; margin-bottom: 4rem">
                <strong>Misión:</strong>
                {$form.mision}
            </p>
            <p style="white-space: pre-line; margin-bottom: 4rem">
                <strong>Objetivo general:</strong>
                {$form.objetivo_general}
            </p>
            <p style="white-space: pre-line; margin-bottom: 4rem">
                <strong>Objetivos específicos:</strong>
                {$form.objetivos_especificos}
            </p>
            <p style="white-space: pre-line; margin-bottom: 4rem">
                <strong>Link del semillero:</strong>
                {$form.link_semillero}
            </p>

            <p style="white-space: pre-line; margin-bottom: 4rem">
                <strong>¿Es semillero de TecnoAcademia?</strong>
                <br />
                {$form.es_semillero_tecnoacademia?.label}
            </p>

            <p style="white-space: pre-line; margin-bottom: 4rem">
                <strong>Red o redes de conocimiento afines al Semillero de Investigación:</strong>
                <br />
                {#if $form.redes_conocimiento}
                    {#each $form.redes_conocimiento as redConocimiento}
                        <br />
                        {redConocimiento.label}
                    {/each}
                {:else}
                    Sin información registrada
                {/if}
            </p>

            <p style="white-space: pre-line; margin-bottom: 4rem">
                <strong>Programas de formación:</strong>
                <br />
                {#if $form.programas_formacion}
                    {#each $form.programas_formacion as programaFormacion}
                        <br />
                        {programaFormacion.label}
                    {/each}
                {:else}
                    Sin información registrada
                {/if}
            </p>

            <p style="white-space: pre-line; margin-bottom: 4rem">
                <strong>Articulación con líneas de investigación:</strong>
                <br />
                {#if $form.lineas_investigacion}
                    {#each $form.lineas_investigacion as lineaInvestigacion}
                        <br />
                        {lineaInvestigacion.label}
                    {/each}
                {:else}
                    Sin información registrada
                {/if}
            </p>
        </Export2Word>
    </div>
    <div slot="actions">
        <div class="p-4">
            <Button on:click={() => (dialogGuardar = false)} variant={null}>Cancelar</Button>
            <Button variant="raised" type="button" on:click={() => exportComponent.export2Word(semilleroInvestigacion.codigo)}>Descargar borrador en Word</Button>
            {#if semilleroInvestigacion?.allowed.to_update || allowedToCreate}
                <LoadingButton loading={$form.processing} form="semillero-investigacion-form">Guardar</LoadingButton>
            {/if}
        </div>
    </div>
</Dialog>
