<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import axios from 'axios'
    import { onMount } from 'svelte'

    import Button from '@/Shared/Button'
    import Input from '@/Shared/Input'
    import InputError from '@/Shared/InputError'
    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import Stepper from '@/Shared/Stepper'
    import DynamicList from '@/Shared/Dropdowns/DynamicList'
    import Textarea from '@/Shared/Textarea'
    import InfoMessage from '@/Shared/InfoMessage'
    import SelectMulti from '@/Shared/SelectMulti'
    import Dialog from '@/Shared/Dialog'
    import Tags from '@/Shared/Tags'
    import Select from 'svelte-select'

    export let errors
    export let convocatoria
    export let tatp
    export let regionales
    export let lineaTecnologicaRelacionada
    export let tecnoacademiaRelacionada
    export let proyectoMunicipios

    let dialogOpen = errors.password != undefined ? true : false
    let sending = false

    let municipios = []
    let lineasTecnologicas = []
    let codigoLineaProgramatica

    $: if (codigoLineaProgramatica) {
        $form.codigo_linea_programatica = codigoLineaProgramatica.codigo
    } else {
        $form.codigo_linea_programatica = tatp.codigo_linea_programatica
    }

    const groupBy = (item) => item.group

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let form = useForm({
        centro_formacion_id: tatp.proyecto.centro_formacion_id,
        tipo_proyecto_id: tatp.proyecto.tipo_proyecto_id,
        fecha_inicio: tatp.fecha_inicio,
        fecha_finalizacion: tatp.fecha_finalizacion,
        resumen: tatp.resumen,
        resumen_regional: tatp.resumen_regional,
        antecedentes: tatp.antecedentes,
        antecedentes_regional: tatp.antecedentes_regional,
        retos_oportunidades: tatp.retos_oportunidades,
        pertinencia_territorio: tatp.pertinencia_territorio,
        marco_conceptual: tatp.marco_conceptual,
        metodologia: tatp.metodologia,
        metodologia_local: tatp.metodologia_local,
        municipios: proyectoMunicipios.length > 0 ? proyectoMunicipios : null,
        impacto_municipios: tatp.impacto_municipios,
        nombre_instituciones: tatp.nombre_instituciones,
        propuesta_sostenibilidad: tatp.propuesta_sostenibilidad,
        impacto_centro_formacion: tatp.impacto_centro_formacion,
        bibliografia: tatp.bibliografia,
        tecnoacademia_id: tecnoacademiaRelacionada,
        tecnoacademia_linea_tecnologica_id: lineaTecnologicaRelacionada,
        codigo_linea_programatica: null,
        nodo_tecnoparque_id: tatp.nodo_tecnoparque_id,
    })

    let regional
    $: whitelistInstitucionesEducativas = []
    $: if (regional) {
        axios
            .get('https://www.datos.gov.co/resource/cfw5-qzt5.json?cod_dane_departamento=' + regional?.codigo)
            .then(function (response) {
                // handle success
                response.data.map((item) => {
                    whitelistInstitucionesEducativas.push(item.nombre_establecimiento)
                })
            })
            .catch(function (error) {
                // handle error
                console.log(error)
            })
            .then(function () {
                // always executed
            })
    }

    onMount(() => {
        getMunicipios()
    })

    function submit() {
        if (isSuperAdmin || checkPermission(authUser, [6, 7])) {
            $form.put(route('convocatorias.tatp.update', [convocatoria.id, tatp.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    let deleteForm = useForm({
        password: '',
    })

    function destroy() {
        if (isSuperAdmin || checkPermission(authUser, [7])) {
            $deleteForm.delete(route('convocatorias.tatp.destroy', [convocatoria.id, tatp.id]), {
                preserveScroll: true,
            })
        }
    }

    async function getMunicipios() {
        let res = await axios.get(route('web-api.municipios'))
        if (res.status == '200') {
            municipios = res.data
        }
    }

    $: $title = tatp ? tatp.titulo : null
</script>

<AuthenticatedLayout>
    <Stepper {convocatoria} proyecto={tatp} />

    <form on:submit|preventDefault={submit}>
        <fieldset class="p-8" disabled={isSuperAdmin ? undefined : true}>
            <div class="mt-44">
                <p class="text-center">Fecha de ejecución</p>
                <small class="text-red-400 block text-center"> * Campo obligatorio </small>
                <div class="mt-4 flex items-start justify-around">
                    <div class="mt-4 flex {errors.fecha_inicio ? '' : 'items-center'}">
                        <Label labelFor="fecha_inicio" class={errors.fecha_inicio ? 'top-3.5 relative' : ''} value="Del" />
                        <div class="ml-4">
                            <input id="fecha_inicio" type="date" class="mt-1 block w-full p-4" min={convocatoria.min_fecha_inicio_proyectos} max={convocatoria.max_fecha_finalizacion_proyectos} error={errors.fecha_inicio} bind:value={$form.fecha_inicio} required />
                        </div>
                    </div>
                    <div class="mt-4 flex {errors.fecha_finalizacion ? '' : 'items-center'}">
                        <Label labelFor="fecha_finalizacion" class={errors.fecha_finalizacion ? 'top-3.5 relative' : ''} value="hasta" />
                        <div class="ml-4">
                            <input id="fecha_finalizacion" type="date" class="mt-1 block w-full p-4" min={convocatoria.min_fecha_inicio_proyectos} max={convocatoria.max_fecha_finalizacion_proyectos} error={errors.fecha_finalizacion} bind:value={$form.fecha_finalizacion} required />
                        </div>
                    </div>
                </div>
                {#if errors.fecha_inicio || errors.fecha_finalizacion}
                    <div class="mb-20 flex">
                        <InputError classes="text-center" message={errors.fecha_inicio} />
                        <InputError classes="text-center" message={errors.fecha_finalizacion} />
                    </div>
                {/if}
            </div>

            <fieldset disabled>
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="tipo_proyecto_id" value="Tipo de proyecto" />
                    </div>
                    <div>
                        <DynamicList id="tipo_proyecto_id" bind:value={$form.tipo_proyecto_id} bind:recurso={codigoLineaProgramatica} routeWebApi={route('web-api.tipos-proyecto', 1)} placeholder="Busque por el nombre del tipo de proyecto, línea programática" message={errors.tipo_proyecto_id} required />
                    </div>
                </div>
            </fieldset>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="centro_formacion_id" value="Centro de formación" />
                    <small> Nota: El Centro de Formación relacionado es el ejecutor del proyecto </small>
                </div>
                <div>
                    <DynamicList id="centro_formacion_id" bind:value={$form.centro_formacion_id} routeWebApi={route('web-api.centros-formacion')} placeholder="Busque por el nombre del centro de formación" message={errors.centro_formacion_id} required />
                </div>
            </div>

            {#if $form.codigo_linea_programatica == 70 && $form.centro_formacion_id}
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="tecnoacademia_id" value="Tecnoacademia" />
                    </div>
                    <div>
                        <DynamicList id="tecnoacademia_id" bind:value={$form.tecnoacademia_id} routeWebApi={route('web-api.centros-formacion.tecnoacademias', [$form.centro_formacion_id])} placeholder="Busque por el nombre de la Tecnoacademia" message={errors.tecnoacademia_id} required />
                    </div>
                </div>

                {#if $form.tecnoacademia_id}
                    <div class="mt-44 grid grid-cols-2">
                        <div>
                            <Label required class="mb-4" labelFor="tecnoacademia_linea_tecnologica_id" value="Línea tecnológica" />
                        </div>
                        <div>
                            <DynamicList id="tecnoacademia_linea_tecnologica_id" bind:value={$form.tecnoacademia_linea_tecnologica_id} routeWebApi={route('web-api.tecnoacademias.lineas-tecnologicas', [$form.tecnoacademia_id])} placeholder="Busque por el nombre de la línea tecnológica" message={errors.tecnoacademia_linea_tecnologica_id} required />
                        </div>
                    </div>
                {/if}
            {:else if $form.codigo_linea_programatica == 69 && $form.centro_formacion_id}
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="nodo_tecnoparque_id" value="Nodo Tecnoparque" />
                    </div>
                    <div>
                        <DynamicList id="nodo_tecnoparque_id" bind:value={$form.nodo_tecnoparque_id} placeholder="Seleccione un nodo Tecnoparque" routeWebApi={route('web-api.nodos-tecnoparque', $form.centro_formacion_id)} message={errors.nodo_tecnoparque_id} required />
                    </div>
                </div>
            {/if}

            <div class="mt-40 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="resumen" value="Resumen del proyecto" />
                    <InfoMessage message="Información necesaria para darle al lector una idea precisa de la pertinencia y calidad proyecto. Explique en qué consiste el problema o necesidad, cómo cree que lo resolverá, cuáles son las razones que justifican su ejecución y las herramientas que se utilizarán en el desarrollo del proyecto." />
                </div>
                <div>
                    <Textarea maxlength="40000" id="resumen" error={errors.resumen} bind:value={$form.resumen} required />
                </div>
            </div>

            <div class="mt-40 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="resumen_regional" value="Complemento - Resumen ejecutivo regional" />
                </div>
                <div>
                    <Textarea maxlength="40000" id="resumen_regional" error={errors.resumen_regional} bind:value={$form.resumen_regional} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="antecedentes" value="Antecedentes" />
                    <InfoMessage
                        message="Presenta las investigaciones, innovaciones o desarrollos tecnológicos que se han realizado a nivel internacional, nacional, departamental o municipal en el marco de la temática de la propuesta del proyecto; que muestran la pertinencia del proyecto, citar toda la información consignada utilizando normas APA sexta edición. De igual forma, relacionar los proyectos ejecutados en vigencias anteriores (incluir códigos SGPS), si el proyecto corresponde a la continuidad de proyectos SENNOVA."
                    />
                </div>
                <div>
                    <Textarea maxlength="40000" id="antecedentes" error={errors.antecedentes} bind:value={$form.antecedentes} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="antecedentes_regional" value="Complemento - Antecedentes regional" />
                </div>
                <div>
                    <Textarea maxlength="40000" id="antecedentes_regional" error={errors.antecedentes_regional} bind:value={$form.antecedentes_regional} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="retos_oportunidades" value="Descripción de retos y prioridades locales y regionales en los cuales la Tecnoacademia tiene impacto" />
                </div>
                <div>
                    <Textarea maxlength="40000" id="retos_oportunidades" error={errors.retos_oportunidades} bind:value={$form.retos_oportunidades} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="pertinencia_territorio" value="Justificacion y pertinencia en el territorio" />
                </div>
                <div>
                    <Textarea maxlength="40000" id="pertinencia_territorio" error={errors.pertinencia_territorio} bind:value={$form.pertinencia_territorio} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="marco_conceptual" value="Marco conceptual" />
                    <InfoMessage message="Descripción de los aspectos conceptuales y/o teóricos relacionados con el problema. Se hace la claridad que no es un listado de definiciones." />
                </div>
                <div>
                    <Textarea maxlength="40000" id="marco_conceptual" error={errors.marco_conceptual} bind:value={$form.marco_conceptual} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="metodologia" value="Descripción de la metodología" />
                    <InfoMessage message="Describir la (s) metodología (s) a utilizar en el desarrollo del proyecto." />
                </div>
                <div>
                    <Textarea maxlength="40000" id="metodologia" error={errors.metodologia} bind:value={$form.metodologia} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="metodologia_local" value="Descripcion de la metodología aplicada a nivel local" />
                </div>
                <div>
                    <Textarea maxlength="40000" id="metodologia_local" error={errors.metodologia_local} bind:value={$form.metodologia_local} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" for="municipios" value="Nombre de los municipios beneficiados" />
                </div>
                <div>
                    <SelectMulti id="municipios" bind:selectedValue={$form.municipios} items={municipios} isMulti={true} error={errors.municipios} placeholder="Buscar municipios" required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="impacto_municipios" value="Descripción del beneficio en los municipios" />
                </div>
                <div>
                    <Textarea maxlength="40000" id="impacto_municipios" error={errors.impacto_municipios} bind:value={$form.impacto_municipios} required />
                </div>
            </div>

            {#if $form.codigo_linea_programatica == 70}
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="nombre_instituciones" value="Instituciones donde se implementará el programa que tienen Articulación con la Media" />
                    </div>
                    <div>
                        <Select bind:selectedValue={regional} items={regionales} {groupBy} placeholder="Seleccione un departamento" />

                        <Tags id="nombre_instituciones" class="mt-4" whitelist={whitelistInstitucionesEducativas} bind:tags={$form.nombre_instituciones} placeholder="Nombre de la IE" error={errors.nombre_instituciones} required />
                    </div>
                </div>
            {/if}

            <div class="mt-40 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="propuesta_sostenibilidad" value="Propuesta de sostenibilidad" />
                    <InfoMessage message="Identificar los efectos que tiene el desarrollo del proyecto de ya sea positivos o negativos.  Se recomienda establecer las acciones pertinentes para mitigar los impactos negativos ambientales identificados y anexar el respectivo permiso ambiental cuando aplique. Tener en cuenta si aplica el decreto 1376 de 2013." />
                </div>
                <div>
                    <Textarea maxlength="40000" id="propuesta_sostenibilidad" error={errors.propuesta_sostenibilidad} bind:value={$form.propuesta_sostenibilidad} required />
                </div>
            </div>

            <div class="mt-40 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="impacto_centro_formacion" value="Impacto en el centro de formación" />
                </div>
                <div>
                    <Textarea maxlength="40000" id="impacto_centro_formacion" error={errors.impacto_centro_formacion} bind:value={$form.impacto_centro_formacion} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="bibliografia" value="Bibliografía" />
                    <InfoMessage message="Lista de las referencias utilizadas en cada apartado del proyecto. Utilizar normas APA- Sexta edición (http://biblioteca.sena.edu.co/images/PDF/InstructivoAPA.pdf)." />
                </div>
                <div>
                    <Textarea maxlength="40000" id="bibliografia" error={errors.bibliografia} bind:value={$form.bibliografia} required />
                </div>
            </div>
        </fieldset>
        <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
            {#if isSuperAdmin || checkPermission(authUser, [7])}
                <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={(event) => (dialogOpen = true)}> Eliminar </button>
            {/if}
            {#if isSuperAdmin || checkPermission(authUser, [6, 7])}
                <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Guardar</LoadingButton>
            {/if}
        </div>
    </form>

    <Dialog bind:open={dialogOpen}>
        <div slot="titulo" class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            Eliminar recurso
        </div>
        <div slot="content">
            <InfoMessage message="¿Está seguro (a) que desea eliminar este proyecto?<br />Una vez eliminado el proyecto, todos sus recursos y datos se eliminarán de forma permanente." />

            <form on:submit|preventDefault={destroy} id="delete-tatp" class="mt-10 mb-28">
                <Label labelFor="password" value="Ingrese su contraseña para confirmar que desea eliminar permanentemente este proyecto" />
                <Input label="Ingrese su contraseña" id="password" type="password" class="mt-4" error={errors.password} bind:value={$deleteForm.password} required />
            </form>
        </div>
        <div slot="actions">
            <div class="p-4">
                <Button on:click={(event) => (dialogOpen = false)} variant={null}>Cancelar</Button>
                <Button variant="raised" form="delete-tatp">Confirmar</Button>
            </div>
        </div>
    </Dialog>
</AuthenticatedLayout>
