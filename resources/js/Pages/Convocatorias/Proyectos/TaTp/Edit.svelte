<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission, monthDiff } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import axios from 'axios'
    import { onMount } from 'svelte'

    import Button from '@/Shared/Button'
    import InputError from '@/Shared/InputError'
    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import Stepper from '@/Shared/Stepper'
    import DynamicList from '@/Shared/Dropdowns/DynamicList'
    import Textarea from '@/Shared/Textarea'
    import InfoMessage from '@/Shared/InfoMessage'
    import Password from '@/Shared/Password'
    import SelectMulti from '@/Shared/SelectMulti'
    import Dialog from '@/Shared/Dialog'
    import Tags from '@/Shared/Tags'
    import Select from 'svelte-select'
    import { Inertia } from '@inertiajs/inertia'

    export let errors
    export let convocatoria
    export let tatp
    export let regionales
    export let lineaTecnologicaRelacionada
    export let tecnoacademiaRelacionada
    export let proyectoMunicipios

    $: $title = tatp ? tatp.titulo : null

    let sending = false
    let dialogOpen = errors.password != undefined ? true : false
    let proyectoDialogOpen = true

    let municipios = []
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
        linea_programatica_id: tatp.proyecto.linea_programatica_id,
        fecha_inicio: tatp.fecha_inicio,
        fecha_finalizacion: tatp.fecha_finalizacion,
        max_meses_ejecucion: tatp.max_meses_ejecucion,
        resumen: tatp.resumen,
        resumen_regional: tatp.resumen_regional,
        antecedentes: tatp.antecedentes,
        antecedentes_regional: tatp.antecedentes_regional,
        retos_oportunidades: tatp.retos_oportunidades,
        pertinencia_territorio: tatp.pertinencia_territorio,
        marco_conceptual: tatp.marco_conceptual,
        municipios: proyectoMunicipios.length > 0 ? proyectoMunicipios : null,
        impacto_municipios: tatp.impacto_municipios,
        nombre_instituciones: tatp.nombre_instituciones,
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
        if (isSuperAdmin || (checkPermission(authUser, [9, 10]) && tatp.proyecto.modificable == true)) {
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
        if (isSuperAdmin || (checkPermission(authUser, [10]) && tatp.proyecto.modificable == true)) {
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

    $: if ($form.fecha_inicio && $form.fecha_finalizacion) {
        $form.max_meses_ejecucion = monthDiff($form.fecha_inicio, $form.fecha_finalizacion)
    }
</script>

<AuthenticatedLayout>
    <Stepper {convocatoria} proyecto={tatp} />

    <form on:submit|preventDefault={submit}>
        <fieldset class="p-8" disabled={isSuperAdmin || (checkPermission(authUser, [9, 10]) && tatp.proyecto.modificable == true) ? undefined : true}>
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
                {#if errors.fecha_inicio || errors.fecha_finalizacion || errors.max_meses_ejecucion}
                    <div class="mb-20 flex justify-center mt-4">
                        <InputError classes="text-center" message={errors.fecha_inicio} />
                        <InputError classes="text-center" message={errors.fecha_finalizacion} />
                        <InputError classes="text-center" message={errors.max_meses_ejecucion} />
                    </div>
                {/if}
            </div>

            <fieldset disabled>
                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="linea_programatica_id" value="Código dependencia presupuestal (SIIF)" />
                    </div>
                    <div>
                        <DynamicList id="linea_programatica_id" bind:value={$form.linea_programatica_id} bind:recurso={codigoLineaProgramatica} routeWebApi={route('web-api.tipos-proyecto', 1)} placeholder="Busque por el nombre de la línea programática" message={errors.linea_programatica_id} required />
                    </div>
                </div>
            </fieldset>

            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="centro_formacion_id" value="Centro de formación" />
                    <small> Nota: El Centro de Formación relacionado es el ejecutor del proyecto </small>
                </div>
                <div>
                    {tatp.proyecto.centro_formacion.nombre}
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
                    <Label required class="mb-4" labelFor="pertinencia_territorio" value="Justificación y pertinencia en el territorio" />
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
                    <Label required class="mb-4" labelFor="impacto_centro_formacion" value="Impacto en el centro de formación" />
                </div>
                <div>
                    <Textarea maxlength="40000" id="impacto_centro_formacion" error={errors.impacto_centro_formacion} bind:value={$form.impacto_centro_formacion} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="bibliografia" value="Bibliografía" />
                    <InfoMessage message="Lista de las referencias utilizadas en cada apartado del proyecto. Utilizar normas APA- Séptima edición (http://biblioteca.sena.edu.co/images/PDF/InstructivoAPA.pdf)." />
                </div>
                <div>
                    <Textarea maxlength="40000" id="bibliografia" error={errors.bibliografia} bind:value={$form.bibliografia} required />
                </div>
            </div>
        </fieldset>
        <div class="px-8 py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
            {#if isSuperAdmin || (checkPermission(authUser, [10]) && tatp.proyecto.modificable == true)}
                <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={(event) => (dialogOpen = true)}> Eliminar </button>
            {/if}
            {#if isSuperAdmin || (checkPermission(authUser, [9, 10]) && tatp.proyecto.modificable == true)}
                <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Guardar</LoadingButton>
            {/if}
        </div>
    </form>

    <Dialog bind:open={proyectoDialogOpen} id="informacion">
        <div slot="title" class="flex items-center flex-col mt-4">
            <figure>
                <img src={window.basePath + '/images/proyecto.png'} alt="Proyecto" class="h-32 mb-6" />
            </figure>
            Código del proyecto: {tatp.proyecto.codigo}
        </div>
        <div slot="content">
            <div>
                <h1 class="text-center mt-4 mb-4">Para terminar el numeral de <strong>Generalidades</strong> por favor continue diligenciando los siguientes campos:</h1>
                <p class="text-center mb-4">Si ya están completos omita esta información.</p>
                <ul class="list-disc">
                    <li>Resumen</li>
                    <li>Complemento - Resumen</li>
                    <li>Antecedentes</li>
                    <li>Complemento - Antecedentes regional</li>
                    <li>Retos y prioridades locales</li>
                    <li>Justificación y pertinencia en el territorio</li>
                    <li>Marco conceptual</li>
                    <li>Bibliografía</li>
                    <li>Número de aprendices beneficiados</li>
                    <li>Nombre de los municipios beneficiados</li>
                    <li>Articulación con la media</li>
                    <li>Impacto en el centro de formación</li>
                </ul>
            </div>
        </div>
        <div slot="actions">
            <div class="p-4">
                <Button on:click={(event) => (proyectoDialogOpen = false)} variant={null}>Omitir</Button>
                <Button variant="raised" on:click={(event) => (proyectoDialogOpen = false)} on:click={() => Inertia.visit('#tecnoacademia_linea_tecnologica_id')}>Continuar diligenciando</Button>
            </div>
        </div>
    </Dialog>

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
                <Label labelFor="password" value="Ingrese su contraseña para confirmar que desea eliminar permanentemente este proyecto" class="mb-4" />
                <Password id="password" class="w-full" bind:value={$deleteForm.password} error={errors.password} required autocomplete="current-password" />
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
