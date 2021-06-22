<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { useForm, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole, checkPermission, monthDiff } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import Button from '@/Shared/Button'
    import InputError from '@/Shared/InputError'
    import Label from '@/Shared/Label'
    import LoadingButton from '@/Shared/LoadingButton'
    import Stepper from '@/Shared/Stepper'
    import DynamicList from '@/Shared/Dropdowns/DynamicList'
    import Textarea from '@/Shared/Textarea'
    import InfoMessage from '@/Shared/InfoMessage'
    import Password from '@/Shared/Password'
    import Select from '@/Shared/Select'
    import Dialog from '@/Shared/Dialog'
    import { Inertia } from '@inertiajs/inertia'

    export let errors
    export let convocatoria
    export let servicioTecnologico
    export let mesasTecnicas
    export let tipologiasSt
    export let tiposProyectoST

    $: $title = servicioTecnologico ? servicioTecnologico.titulo : null

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let dialogOpen = errors.password != undefined ? true : false
    let proyectoDialogOpen = true
    let sending = false

    let form = useForm({
        centro_formacion_id: servicioTecnologico.proyecto?.centro_formacion_id,
        linea_programatica_id: servicioTecnologico.proyecto?.linea_programatica_id,
        titulo: servicioTecnologico.titulo,
        fecha_inicio: servicioTecnologico.fecha_inicio,
        fecha_finalizacion: servicioTecnologico.fecha_finalizacion,
        max_meses_ejecucion: servicioTecnologico.max_meses_ejecucion,
        resumen: servicioTecnologico.resumen,
        antecedentes: servicioTecnologico.antecedentes,
        bibliografia: servicioTecnologico.bibliografia,
        especificaciones_area: servicioTecnologico.especificaciones_area,
        mesa_tecnica_id: {
            value: servicioTecnologico?.mesa_tecnica_sector_productivo?.mesa_tecnica_id,
            label: mesasTecnicas.find((item) => item.value == servicioTecnologico?.mesa_tecnica_sector_productivo?.mesa_tecnica_id)?.label,
        },
        mesa_tecnica_sector_productivo_id: servicioTecnologico.mesa_tecnica_sector_productivo_id,
        tipologia_st: {
            value: servicioTecnologico?.subclasificacion_tipologia_st?.tipologia_st_id,
            label: tipologiasSt.find((item) => item.value == servicioTecnologico?.subclasificacion_tipologia_st?.tipologia_st_id)?.label,
        },
        subclasificacion_tipologia_st_id: servicioTecnologico?.subclasificacion_tipologia_st?.tipologia_st_id,
        tipo_proyecto_st: {
            value: servicioTecnologico?.estado_sistema_gestion?.tipo_proyecto_st_id,
            label: tiposProyectoST.find((item) => item.value == servicioTecnologico?.estado_sistema_gestion?.tipo_proyecto_st_id)?.label,
        },
        estado_sistema_gestion_id: servicioTecnologico?.estado_sistema_gestion?.id,
        identificacion_problema: servicioTecnologico.identificacion_problema,
        pregunta_formulacion_problema: servicioTecnologico.pregunta_formulacion_problema,
        justificacion_problema: servicioTecnologico.justificacion_problema,
    })

    function submit() {
        if (isSuperAdmin || (checkPermission(authUser, [6, 7]) && servicioTecnologico.proyecto.modificable == true)) {
            $form.put(route('convocatorias.servicios-tecnologicos.update', [convocatoria.id, servicioTecnologico.id]), {
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
        if (isSuperAdmin || (checkPermission(authUser, [7]) && servicioTecnologico.proyecto.modificable == true)) {
            $deleteForm.delete(route('convocatorias.servicios-tecnologicos.destroy', [convocatoria.id, servicioTecnologico.id]), {
                preserveScroll: true,
            })
        }
    }

    $: if ($form.fecha_inicio && $form.fecha_finalizacion) {
        $form.max_meses_ejecucion = monthDiff($form.fecha_inicio, $form.fecha_finalizacion)
    }
</script>

<AuthenticatedLayout>
    <Stepper {convocatoria} proyecto={servicioTecnologico} />

    <form on:submit|preventDefault={submit}>
        <fieldset class="p-8" disabled={isSuperAdmin || (checkPermission(authUser, [6, 7]) && servicioTecnologico.proyecto.modificable == true) ? undefined : true}>
            <div class="mt-28">
                <Label
                    required
                    labelFor="titulo"
                    class="font-medium inline-block mb-10 text-center text-gray-700 text-sm w-full"
                    value="Debe corresponder al contenido del proyecto y responder a los siguientes interrogantes: ¿Qué se va a hacer?, ¿Sobre qué o quiénes se hará?, ¿Cómo?, ¿Dónde se llevará a cabo? Tiene que estar escrito de manera breve y concisa. Un buen título describe con exactitud y usando el menor número posible de palabras el tema central del proyecto. Nota: las respuestas a las preguntas anteriormente formuladas no necesariamente deben responderse en mismo orden en el que aparecen."
                />
                <Textarea label="Título" maxlength="40000" id="titulo" error={errors.titulo} bind:value={$form.titulo} classes="bg-transparent block border-0 {errors.titulo ? '' : 'outline-none-important'} mt-1 outline-none text-4xl text-center w-full" required />
            </div>

            <div class="mt-44">
                <p class="text-center">Fecha de ejecución</p>
                <div class="mt-4 flex items-start justify-around">
                    <div class="mt-4 flex {errors.fecha_inicio ? '' : 'items-center'}">
                        <Label required labelFor="fecha_inicio" class={errors.fecha_inicio ? 'top-3.5 relative' : ''} value="Del" />
                        <div class="ml-4">
                            <input id="fecha_inicio" type="date" class="mt-1 block w-full p-4" min={convocatoria.min_fecha_inicio_proyectos_st} max={convocatoria.max_fecha_finalizacion_proyectos_st} bind:value={$form.fecha_inicio} required />
                        </div>
                    </div>
                    <div class="mt-4 flex {errors.fecha_finalizacion ? '' : 'items-center'}">
                        <Label required labelFor="fecha_finalizacion" class={errors.fecha_finalizacion ? 'top-3.5 relative' : ''} value="hasta" />
                        <div class="ml-4">
                            <input id="fecha_finalizacion" type="date" class="mt-1 block w-full p-4" min={convocatoria.min_fecha_inicio_proyectos_st} max={convocatoria.max_fecha_finalizacion_proyectos_st} bind:value={$form.fecha_finalizacion} required />
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
                        <Label required class="mb-4" labelFor="centro_formacion_id" value="Centro de formación" />
                        <small> Nota: El Centro de Formación relacionado es el ejecutor del proyecto </small>
                    </div>
                    <div>
                        {servicioTecnologico.proyecto.centro_formacion.nombre}
                    </div>
                </div>

                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="linea_programatica_id" value="Código dependencia presupuestal (SIIF)" />
                    </div>
                    <div>
                        <DynamicList id="linea_programatica_id" bind:value={$form.linea_programatica_id} routeWebApi={route('web-api.lineas-programaticas', 3)} classes="min-h" placeholder="Busque por el nombre de la línea programática" message={errors.linea_programatica_id} required />
                    </div>
                </div>

                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="tipologia_st" value="Tipología ST" />
                    </div>
                    <div>
                        <Select id="tipologia_st" items={tipologiasSt} bind:selectedValue={$form.tipologia_st} error={errors.tipologia_st} autocomplete="off" placeholder="Seleccione una tipología de ST" required />
                    </div>
                </div>

                {#if $form.tipologia_st?.value}
                    <div class="mt-44 grid grid-cols-2">
                        <div>
                            <Label required class="mb-4" labelFor="subclasificacion_tipologia_st_id" value="Subclasificación de tipología ST" />
                        </div>
                        <div>
                            <DynamicList id="subclasificacion_tipologia_st_id" bind:value={$form.subclasificacion_tipologia_st_id} routeWebApi={route('web-api.subclasificacion-tipologia-st', $form.tipologia_st?.value)} classes="min-h" placeholder="Busque por el de la subclasificación de tipología ST" message={errors.subclasificacion_tipologia_st_id} required />
                        </div>
                    </div>
                {/if}

                <div class="mt-44 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="tipo_proyecto_st" value="Tipo de proyecto" />
                    </div>
                    <div>
                        <Select id="tipo_proyecto_st" items={tiposProyectoST} bind:selectedValue={$form.tipo_proyecto_st} error={errors.tipo_proyecto_st} autocomplete="off" placeholder="Seleccione un tipo de proyecto ST" required />
                    </div>
                </div>

                {#if $form.tipo_proyecto_st?.value}
                    <div class="mt-44 grid grid-cols-2">
                        <div>
                            <Label required class="mb-4" labelFor="estado_sistema_gestion_id" value="Estado del sistema de gestión" />
                        </div>
                        <div>
                            <DynamicList id="estado_sistema_gestion_id" bind:value={$form.estado_sistema_gestion_id} routeWebApi={route('web-api.estados-sistema-gestion', $form.tipo_proyecto_st?.value)} classes="min-h" placeholder="Busque por el nombre del estado de sistema de gestión" message={errors.estado_sistema_gestion_id} required />
                        </div>
                    </div>
                {/if}
            </fieldset>

            <hr class="mt-32" />

            <div class="mt-10">
                <h1 class="text-2xl text-center">Mesa técnica a la que pertenece el proyecto</h1>
            </div>
            <div class="mt-44 grid grid-cols-2">
                <div>
                    <Label required class="mb-4" labelFor="mesa_tecnica_id" value="Mesa técnica ST" />
                </div>
                <div>
                    <Select id="mesa_tecnica_id" items={mesasTecnicas} bind:selectedValue={$form.mesa_tecnica_id} error={errors.mesa_tecnica_id} autocomplete="off" placeholder="Seleccione una mesa técnica" required />
                </div>
            </div>

            {#if $form.mesa_tecnica_id?.value}
                <div class="mt-10 grid grid-cols-2">
                    <div>
                        <Label required class="mb-4" labelFor="mesa_tecnica_sector_productivo_id" value="Sectores priorizados de Colombia productiva" />
                    </div>
                    <div>
                        <DynamicList id="mesa_tecnica_sector_productivo_id" bind:value={$form.mesa_tecnica_sector_productivo_id} routeWebApi={route('web-api.sectores-productivos', [$form.mesa_tecnica_id?.value])} classes="min-h" placeholder="Busque por el nombre del sector productivo" message={errors.mesa_tecnica_sector_productivo_id} required />
                    </div>
                </div>
            {/if}

            <hr class="mt-32 mb-32" />

            <h1 class="text-2xl text-center" id="estructura-proyecto">Estructura del proyecto</h1>

            <div class="mt-40 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="resumen" value="Resumen ejecutivo" />
                    <InfoMessage class="mb-2">
                        <p>
                            Información necesaria para darle al lector una idea precisa de la pertinencia y calidad proyecto. Explique en qué consiste el problema o necesidad, cómo cree que lo resolverá, cuáles son las razones que justifican su ejecución y las herramientas que se utilizarán en el desarrollo del proyecto.
                            <br />
                            <strong>Nota:</strong> El resumen por lo general se construye al final de la contextualización con el fin de tener claros todos los puntos que intervinieron en la misma y poder dar a conocer de forma más pertinente los por menores del proyecto. (Máximo 1000 caracteres).
                        </p>
                    </InfoMessage>
                </div>
                <div>
                    <Textarea maxlength="1000" id="resumen" error={errors.resumen} bind:value={$form.resumen} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="antecedentes" value="Antecedentes" />
                    <InfoMessage class="mb-2">
                        <p>
                            Se debe evidenciar la identificación y caracterización del mercado potencial/objetivo, nicho de mercado al cual se busca atender o la necesidad que se busca satisfacer tomando como referencia el estudio del sector, identificando si existen el(los) mismo(s) alcance(s) o similar(es) en la empresa privada o pública u otros centros de formación de tal forma que el proyecto
                            no se convierta en una competencia frente a un servicio/producto ofertado. Se debe registrar el análisis de las tendencias del mercado, en relación con clientes potenciales, competidores y proveedores. En este ítem es necesario valorar las necesidades de los clientes actuales o potenciales y precisar la segmentación del mercado, las tendencias de los precios y las
                            gestiones comerciales a realizadas.
                            <br />
                            <strong>Nota:</strong> La información debe ser de fuentes primarias, ejemplo: Secretarías, DANE, Artículos científicos, entre otros y citarla utilizando normas APA séptima edición. (Máximo 10000 caracteres).
                        </p>
                    </InfoMessage>
                </div>
                <div>
                    <Textarea maxlength="10000" id="antecedentes" error={errors.antecedentes} bind:value={$form.antecedentes} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="identificacion_problema" value="Identificación y descripción del problema" />
                    <InfoMessage
                        class="mb-2"
                        message="1. Descripción de la necesidad, problema u oportunidad identificada del plan tecnológico y/o agendas departamentales de innovación y competitividad.<br>2. Descripción del problema que se atiende con el proyecto, sustentado en el contexto, la caracterización, los datos, las estadísticas, de la regional, entre otros, citar toda la información consignada utilizando normas APA sexta edición. La información debe ser de fuentes primarias de información, ejemplo: Secretarías, DANE, Artículos científicos, entre otros."
                    />
                </div>

                <div>
                    <Textarea label="Identificación y descripción del problema" maxlength="5000" id="identificacion_problema" error={errors.identificacion_problema} bind:value={$form.identificacion_problema} required />
                </div>
            </div>

            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="pregunta_formulacion_problema" value="Pregunta de formulación del problema" />
                    <InfoMessage class="mb-2">
                        <p>Se debe verificar que la pregunta del problema defina con exactitud ¿cuál es el problema para resolver, investigar o intervenir?</p>
                        La pregunta debe cumplir las siguientes condiciones:
                        <ul>
                            <li>• Guardar estrecha correspondencia con el título del proyecto.</li>
                            <li>• Evitar adjetivos que impliquen juicios de valor tales como: bueno, malo, mejor, peor.</li>
                            <li>• No debe dar origen a respuestas tales como si o no.</li>
                        </ul>
                        <br />
                        <strong>Nota:</strong> Se sugiere convertir el problema principal (tronco) identificado en el árbol de problemas en forma pregunta.
                    </InfoMessage>
                </div>
                <div>
                    <Textarea label="Pregunta formulación del problema" maxlength="5000" id="pregunta_formulacion_problema" error={errors.pregunta_formulacion_problema} bind:value={$form.pregunta_formulacion_problema} required />
                </div>
            </div>
            <div class="mt-44 grid grid-cols-1">
                <div>
                    <Label required class="mb-4" labelFor="justificacion_problema" value="Justificación" />
                    <InfoMessage class="mb-2">
                        <p>La justificación debe describir la solución del problema y debe responder a las siguientes preguntas:</p>
                        <ul>
                            <li>• ¿Cómo se relaciona el proyecto con las prioridades de la región y del país?</li>
                            <li>• ¿Qué resultados se lograrán?</li>
                            <li>• ¿Cuál es la finalidad con los resultados esperados?</li>
                            <li>• ¿Cómo se utilizarán los resultados y quiénes serán los beneficiarios?</li>
                        </ul>
                        <strong>Nota:</strong>La justificación debe brindar un argumento convincente de los resultados del proyecto generado y de su aplicabilidad."
                    </InfoMessage>
                </div>
                <div>
                    <Textarea label="Justificación" maxlength="5000" id="justificacion_problema" error={errors.justificacion_problema} bind:value={$form.justificacion_problema} required />
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
            {#if isSuperAdmin || (checkPermission(authUser, [7]) && servicioTecnologico.proyecto.modificable == true)}
                <button class="text-red-600 hover:underline text-left" tabindex="-1" type="button" on:click={(event) => (dialogOpen = true)}> Eliminar </button>
            {/if}
            {#if isSuperAdmin || (checkPermission(authUser, [6, 7]) && servicioTecnologico.proyecto.modificable == true)}
                <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Guardar</LoadingButton>
            {/if}
        </div>
    </form>

    <Dialog bind:open={proyectoDialogOpen} id="informacion">
        <div slot="title" class="flex items-center flex-col mt-4">
            <figure>
                <img src={window.basePath + '/images/proyecto.png'} alt="Proyecto" class="h-32 mb-6" />
            </figure>
            Código del proyecto: {servicioTecnologico.proyecto.codigo}
        </div>
        <div slot="content">
            <div>
                <h1 class="text-center mt-4 mb-4">Para terminar el numeral de <strong>Generalidades</strong> por favor continue diligenciando los siguientes campos:</h1>
                <p class="text-center mb-4">Si ya están completos omita esta información.</p>
                <ul class="list-disc">
                    <li>Resumen</li>
                    <li>Antecedentes</li>
                    <li>Identificación y descripción del problema</li>
                    <li>Pregunta de formulación del problema</li>
                    <li>Justificación</li>
                    <li>Bibliografía</li>
                </ul>
            </div>
        </div>
        <div slot="actions">
            <div class="p-4">
                <Button on:click={(event) => (proyectoDialogOpen = false)} variant={null}>Omitir</Button>
                <Button variant="raised" on:click={(event) => (proyectoDialogOpen = false)} on:click={() => Inertia.visit('#estructura-proyecto')}>Continuar diligenciando</Button>
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
