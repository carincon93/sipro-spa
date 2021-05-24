<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import axios from 'axios'
    import { onMount } from 'svelte'

    import Button from '@/Components/Button'
    import Input from '@/Components/Input'
    import InputError from '@/Components/InputError'
    import Label from '@/Components/Label'
    import LoadingButton from '@/Components/LoadingButton'
    import Stepper from '@/Components/Stepper'
    import DynamicList from '@/Dropdowns/DynamicList'
    import Textarea from '@/Components/Textarea'
    import InfoMessage from '@/Components/InfoMessage'
    import SelectMulti from '@/Components/SelectMulti'
    import Dialog from '@/Components/Dialog'
    import Tags from '@/Components/Tags'

    export let errors
    export let convocatoria
    export let tatp
    export let lineaTecnologicaRelacionada
    export let tecnoacademiaRelacionada
    export let proyectoMunicipios

    let dialog_open = errors.password != undefined ? true : false
    let sending = false

    let municipios = []
    let tecnoacademias = []
    let lineasTecnologicas = []

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0

    let form = useForm({
        centro_formacion_id: tatp.proyecto.centro_formacion_id,
        tipo_proyecto_id: tatp.proyecto.tipo_proyecto_id,
        titulo: tatp.titulo,
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
    })

    onMount(() => {
        if (tecnoacademiaRelacionada) {
            getLineasTecnologicas(tecnoacademiaRelacionada)
        }
        getMunicipios()
    })

    function submit() {
        if (isSuperAdmin) {
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
        if (isSuperAdmin) {
            $deleteForm.delete(route('convocatorias.tatp.destroy', [convocatoria.id, tatp.id]), {
                preserveScroll: true,
            })
        }
    }

    $: selectedCentroFormacion = $form.centro_formacion_id
    $: if (selectedCentroFormacion) {
        lineasTecnologicas = []
        getTecnoacademias(selectedCentroFormacion)
    }

    async function getTecnoacademias(centroFormacionRelacionado) {
        let res = await axios.get(route('web-api.centros-formacion.tecnoacademias', [centroFormacionRelacionado]))
        tecnoacademias = res.data
    }

    $: selectedTecnoacademia = $form.tecnoacademia_id
    $: if (selectedTecnoacademia) {
        getLineasTecnologicas(selectedTecnoacademia)
    }

    async function getLineasTecnologicas(tecnoacademiaRelacionada) {
        let res = await axios.get(route('web-api.tecnoacademias.lineas-tecnologicas', [tecnoacademiaRelacionada]))
        lineasTecnologicas = res.data
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
            <div class="mt-28">
                <Label required labelFor="titulo" class="font-medium inline-block mb-10 text-center text-gray-700 text-sm w-full" value="Descripción llamativa que orienta el enfoque del proyecto, indica el cómo y el para qué." />
                <Textarea rows="4" id="titulo" error={errors.titulo} bind:value={$form.titulo} classes="bg-transparent block border-0 {errors.titulo ? '' : 'outline-none-important'} mt-1 outline-none text-4xl text-center w-full" required />
            </div>

            <div class="mt-44">
                <p class="text-center">Fecha de ejecución</p>
                <small class="text-red-400 block text-center"> * Campo obligatorio </small>
                <div class="mt-4 flex items-start justify-around">
                    <div class="mt-4 flex {errors.fecha_inicio ? '' : 'items-center'}">
                        <Label labelFor="fecha_inicio" class={errors.fecha_inicio ? 'top-3.5 relative' : ''} value="Del" />
                        <div class="ml-4">
                            <Input id="fecha_inicio" type="date" class="mt-1 block w-full" error={errors.fecha_inicio} bind:value={$form.fecha_inicio} required />
                        </div>
                    </div>
                    <div class="mt-4 flex {errors.fecha_finalizacion ? '' : 'items-center'}">
                        <Label labelFor="fecha_finalizacion" class={errors.fecha_finalizacion ? 'top-3.5 relative' : ''} value="hasta" />
                        <div class="ml-4">
                            <Input id="fecha_finalizacion" type="date" class="mt-1 block w-full" error={errors.fecha_finalizacion} bind:value={$form.fecha_finalizacion} required />
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="tipo_proyecto_id" value="Tipo de proyecto" />
                </div>
                <div>
                    <DynamicList id="tipo_proyecto_id" bind:value={$form.tipo_proyecto_id} routeWebApi={route('web-api.tipos-proyecto', 1)} placeholder="Busque por el nombre del tipo de proyecto, línea programática" message={errors.tipo_proyecto_id} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="centro_formacion_id" value="Centro de formación" />
                    <small> Nota: El Centro de Formación relacionado es el ejecutor del proyecto </small>
                </div>
                <div>
                    <DynamicList id="centro_formacion_id" bind:value={$form.centro_formacion_id} routeWebApi={route('web-api.centros-formacion')} placeholder="Busque por el nombre del centro de formación" message={errors.centro_formacion_id} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="tecnoacademia_id" value="Tecnoacademia" />
                </div>
                <div>
                    <select id="tecnoacademia_id" disabled={!(tecnoacademias.length > 0)} class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 focus:border-indigo-200 focus:ring-indigo-200" bind:value={$form.tecnoacademia_id} error={errors.tecnoacademia_id} autocomplete="off" required>
                        <option value="">Seleccione una Tecnoacademia</option>
                        {#each tecnoacademias as { id, nombre }}
                            <option value={id}>{nombre}</option>
                        {/each}
                    </select>

                    <InputError message={errors.tecnoacademia_id} />

                    {#if tecnoacademias.length == 0}
                        <InfoMessage message="No hay tecnoacademias para este filtro, por favor realice un filtro diferente" />
                    {/if}
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="tecnoacademia_linea_tecnologica_id" value="Línea tecnológica" />
                </div>
                <div>
                    <select id="tecnoacademia_linea_tecnologica_id" class="w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 focus:border-indigo-200 focus:ring-indigo-200" bind:value={$form.tecnoacademia_linea_tecnologica_id} disabled={lineasTecnologicas.length > 0 ? undefined : true} required>
                        <option value="">Seleccione una línea tecnológica</option>
                        {#each lineasTecnologicas as { id, nombre }, i}
                            <option value={id}>{nombre}</option>
                        {/each}
                    </select>

                    <InputError message={errors.tecnoacademia_linea_tecnologica_id} />

                    {#if lineasTecnologicas.length == 0}
                        <InfoMessage message="No hay líneas tecnológicas para este filtro, por favor realice un filtro diferente" />
                    {/if}
                </div>
            </div>

            <div class="mt-40 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="resumen" value="Resumen del proyecto" />
                    <InfoMessage message="Información necesaria para darle al lector una idea precisa de la pertinencia y calidad proyecto. Explique en qué consiste el problema o necesidad, cómo cree que lo resolverá, cuáles son las razones que justifican su ejecución y las herramientas que se utilizarán en el desarrollo del proyecto." />
                </div>
                <div>
                    <Textarea rows="4" id="resumen" error={errors.resumen} bind:value={$form.resumen} required />
                </div>
            </div>

            <div class="mt-40 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="resumen_regional" value="Complemento Resumen ejecutivo Regional" />
                </div>
                <div>
                    <Textarea rows="4" id="resumen_regional" error={errors.resumen_regional} bind:value={$form.resumen_regional} required />
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
                    <Textarea rows="4" id="antecedentes" error={errors.antecedentes} bind:value={$form.antecedentes} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="antecedentes_regional" value="Complemento Antecedentes Regional" />
                </div>
                <div>
                    <Textarea rows="4" id="antecedentes_regional" error={errors.antecedentes_regional} bind:value={$form.antecedentes_regional} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="retos_oportunidades" value="Descripción de Retos y prioridades locales y regionales en los cuales la Tecnoacademia tiene impacto" />
                </div>
                <div>
                    <Textarea rows="4" id="retos_oportunidades" error={errors.retos_oportunidades} bind:value={$form.retos_oportunidades} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="pertinencia_territorio" value="Justificacion y pertinencia en el territorio" />
                </div>
                <div>
                    <Textarea rows="4" id="pertinencia_territorio" error={errors.pertinencia_territorio} bind:value={$form.pertinencia_territorio} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="marco_conceptual" value="Marco conceptual" />
                    <InfoMessage message="Descripción de los aspectos conceptuales y/o teóricos relacionados con el problema. Se hace la claridad que no es un listado de definiciones." />
                </div>
                <div>
                    <Textarea rows="4" id="marco_conceptual" error={errors.marco_conceptual} bind:value={$form.marco_conceptual} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="metodologia" value="Descripción de la metodología" />
                    <InfoMessage message="Describir la (s) metodología (s) a utilizar en el desarrollo del proyecto." />
                </div>
                <div>
                    <Textarea rows="4" id="metodologia" error={errors.metodologia} bind:value={$form.metodologia} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="metodologia_local" value="Descripcion de La Metodología aplicada a nivel local" />
                </div>
                <div>
                    <Textarea rows="4" id="metodologia_local" error={errors.metodologia_local} bind:value={$form.metodologia_local} required />
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
                    <Textarea rows="4" id="impacto_municipios" error={errors.impacto_municipios} bind:value={$form.impacto_municipios} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="nombre_instituciones" value="Instituciones donde se implementará el programa que tienen Articulación con la Media" />
                </div>
                <div>
                    <Tags id="nombre_instituciones" bind:tags={$form.nombre_instituciones} error={errors.nombre_instituciones} required />
                </div>
            </div>

            <div class="mt-40 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="propuesta_sostenibilidad" value="Propuesta de sostenibilidad" />
                    <InfoMessage message="Identificar los efectos que tiene el desarrollo del proyecto de ya sea positivos o negativos.  Se recomienda establecer las acciones pertinentes para mitigar los impactos negativos ambientales identificados y anexar el respectivo permiso ambiental cuando aplique. Tener en cuenta si aplica el decreto 1376 de 2013." />
                </div>
                <div>
                    <Textarea rows="4" id="propuesta_sostenibilidad" error={errors.propuesta_sostenibilidad} bind:value={$form.propuesta_sostenibilidad} required />
                </div>
            </div>

            <div class="mt-40 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="impacto_centro_formacion" value="Impacto en el centro de formación" />
                </div>
                <div>
                    <Textarea rows="4" id="impacto_centro_formacion" error={errors.impacto_centro_formacion} bind:value={$form.impacto_centro_formacion} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="bibliografia" value="Bibliografía" />
                    <InfoMessage message="Lista de las referencias utilizadas en cada apartado del proyecto. Utilizar normas APA- Sexta edición (http://biblioteca.sena.edu.co/images/PDF/InstructivoAPA.pdf)." />
                </div>
                <div>
                    <Textarea rows="4" id="bibliografia" error={errors.bibliografia} bind:value={$form.bibliografia} required />
                </div>
            </div>
        </fieldset>
        <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
            {#if isSuperAdmin}
                <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={(event) => (dialog_open = true)}> Eliminar </button>
            {/if}
            {#if isSuperAdmin}
                <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Guardar</LoadingButton>
            {/if}
        </div>
    </form>

    <Dialog bind:open={dialog_open}>
        <div slot="titulo" class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            Eliminar recurso
        </div>
        <div slot="content">
            <p>
                ¿Está seguro (a) que desea eliminar este proyecto?
                <br />
                Una vez eliminado el proyecto, todos sus recursos y datos se eliminarán de forma permanente.
            </p>

            <form on:submit|preventDefault={destroy} id="delete-tatp" class="mt-20 mb-28">
                <Label for="password" value="Ingrese su contraseña para confirmar que desea eliminar permanentemente este proyecto." />
                <Input id="password" type="password" class="mt-1 block w-full" error={errors.password} placeholder="Escriba su contraseña" bind:value={$deleteForm.password} required />
            </form>
        </div>
        <div slot="actions">
            <div class="p-4">
                <Button on:click={(event) => (dialog_open = false)} variant={null}>Cancelar</Button>
                <Button variant="raised" form="delete-tatp">Confirmar</Button>
            </div>
        </div>
    </Dialog>
</AuthenticatedLayout>
