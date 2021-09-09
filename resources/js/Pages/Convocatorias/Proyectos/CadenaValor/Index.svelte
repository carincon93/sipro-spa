<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page, useForm } from '@inertiajs/inertia-svelte'
    import { onMount } from 'svelte'
    import { _ } from 'svelte-i18n'
    import { checkRole, checkPermission } from '@/Utils'

    import Stepper from '@/Shared/Stepper'
    import InfoMessage from '@/Shared/InfoMessage'
    import Label from '@/Shared/Label'
    import Textarea from '@/Shared/Textarea'
    import LoadingButton from '@/Shared/LoadingButton'

    export let errors
    export let convocatoria
    export let proyecto
    export let objetivos
    export let productos

    $title = 'Cadena de valor'

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let sending = false

    let form = useForm({
        propuesta_sostenibilidad: proyecto.propuesta_sostenibilidad,
        propuesta_sostenibilidad_social: proyecto.propuesta_sostenibilidad_social,
        propuesta_sostenibilidad_ambiental: proyecto.propuesta_sostenibilidad_ambiental,
        propuesta_sostenibilidad_financiera: proyecto.propuesta_sostenibilidad_financiera,
    })

    function submit() {
        if (isSuperAdmin || (checkPermission(authUser, [3, 4, 6, 7, 9, 10, 12, 13, 18, 19]) && proyecto.modificable == true)) {
            $form.put(route('convocatorias.proyectos.propuesta-sostenibilidad', [convocatoria.id, proyecto.id]), {
                onStart: () => (sending = true),
                onFinish: () => (sending = false),
                preserveScroll: true,
            })
        }
    }

    onMount(() => {
        google.charts.setOnLoadCallback(drawChart)
    })

    function drawChart() {
        var data = new google.visualization.DataTable()
        data.addColumn('string', 'Name')
        data.addColumn('string', 'Manager')
        data.addColumn('string', 'ToolTip')

        var options = {
            nodeClass: 'bg-indigo-500 text-white shadow',
            selectedNodeClass: 'bg-indigo-700',
            allowHtml: true,
            size: 'small',
        }

        // For each orgchart box, provide the name, manager, and tooltip to show.

        // [[{ v: 'Key', f: 'Key<div>{Descritpion}</div>' }, 'Foreign key', 'Tooltip']]

        data.addRows([[{ v: 'Objetivo general', f: '<strong>Objetivo general</strong><div>' + objetivos['Objetivo general'] + '</div>' }, '', 'Objetivo general']])
        data.addRows([[{ v: 'Primer objetivo específico', f: '<strong>Primer objetivo específico</strong><div>' + objetivos['Primer objetivo específico'] + '</div>' }, 'Objetivo general', 'Primer objetivo específico']])
        data.addRows([[{ v: 'Segundo objetivo específico', f: '<strong>Segundo objetivo específico</strong><div>' + objetivos['Segundo objetivo específico'] + '</div>' }, 'Objetivo general', 'Segundo objetivo específico']])
        if (objetivos['Tercer objetivo específico']) {
            data.addRows([[{ v: 'Tercer objetivo específico', f: '<strong>Tercer objetivo específico</strong><div>' + objetivos['Tercer objetivo específico'] + '</div>' }, 'Objetivo general', 'Tercer objetivo específico']])
        }
        if (objetivos['Cuarto objetivo específico']) {
            data.addRows([[{ v: 'Cuarto objetivo específico', f: '<strong>Cuarto objetivo específico</strong><div>' + objetivos['Cuarto objetivo específico'] + '</div>' }, 'Objetivo general', 'Cuarto objetivo específico']])
        }
        if (objetivos['Quinto objetivo específico']) {
            data.addRows([[{ v: 'Quinto objetivo específico', f: '<strong>Quinto objetivo específico</strong><div>' + objetivos['Quinto objetivo específico'] + '</div>' }, 'Objetivo general', 'Quinto objetivo específico']])
        }
        if (objetivos['Sexto objetivo específico']) {
            data.addRows([[{ v: 'Sexto objetivo específico', f: '<strong>Sexto objetivo específico</strong><div>' + objetivos['Sexto objetivo específico'] + '</div>' }, 'Objetivo general', 'Sexto objetivo específico']])
        }
        productos.map((producto) => {
            data.addRows([[{ v: producto.v, f: '<strong>Producto</strong><div>' + producto.f + '</div>' }, producto.fkey, producto.tooltip]])
            producto.actividades.map((actividad) => {
                data.addRows([
                    [
                        {
                            v: 'act' + producto.v + actividad.id,
                            f: '<strong>Actividad</strong><div>' + actividad.descripcion + '</div><div><strong>Roles:</strong><ul class="list-inside">' + actividad.proyecto_roles_sennova.map((proyectoRol) => '<li>' + proyectoRol.convocatoria_rol_sennova.rol_sennova.nombre + '</li>') + '</ul></div>',
                        },
                        producto.v,
                        actividad.descripcion,
                    ],
                ])

                data.addRows([[{ v: 'cost' + producto.v + actividad.id, f: '<strong>Costo</strong><div>$ ' + new Intl.NumberFormat('de-DE').format(!isNaN(actividad.costo_actividad) ? actividad.costo_actividad : 0) + ' COP</div>' }, 'act' + producto.v + actividad.id, new Intl.NumberFormat('de-DE').format(!isNaN(actividad.costo_actividad) ? actividad.costo_actividad : 0)]])
            })
        })

        // Create the chart.
        var chart = new google.visualization.OrgChart(document.getElementById('orgchart_div'))
        if (typeof chart.draw === 'function') {
            // Draw the chart, setting the allowHtml option to true for the tooltips.
            chart.draw(data, options)
        }
    }
</script>

<AuthenticatedLayout>
    <Stepper {convocatoria} {proyecto} />

    <h1 class="text-3xl mt-24 mb-10 text-center">Propuesta de sostenibilidad</h1>

    {#if proyecto.codigo_linea_programatica == 70}
        <p class="text-center mb-24">A continuación, plantee las acciones concretas que contribuirán a la sostenibilidad financiera de la TecnoAcademia y su aporte a la sostenibilidad ambiental y social del territorio.</p>
    {/if}

    <form on:submit|preventDefault={submit}>
        <fieldset disabled={isSuperAdmin || (checkPermission(authUser, [3, 4, 6, 7, 9, 10, 12, 13, 18, 19]) && proyecto.modificable == true) ? undefined : true}>
            {#if proyecto.codigo_linea_programatica != 70}
                <div class="mt-4">
                    <Label required class="mb-4" labelFor="propuesta_sostenibilidad" value="Propuesta de sostenibilidad" />
                    {#if proyecto.codigo_linea_programatica == 68}
                        <InfoMessage class="mb-2">
                            Se deben mencionar aquellos factores que pueden comprometer la viabilidad, desarrollo de los objetivos y resultados del proyecto a través del tiempo.
                            <br />
                            Para definir la propuesta de sostenibilidad se deben tener en cuenta los impactos definidos en el árbol de objetivos (ambiental, social - en el centro de formación, social - en el sector productivo, tecnológico)
                        </InfoMessage>
                    {:else}
                        <InfoMessage class="mb-2" message="Identificar los efectos que tiene el desarrollo del proyecto de investigación ya sea positivos o negativos. Se recomienda establecer las acciones pertinentes para mitigar los impactos negativos ambientales identificados y anexar el respectivo permiso ambiental cuando aplique. Tener en cuenta si aplica el decreto 1376 de 2013." />
                    {/if}
                    <Textarea label="Propuesta de sostenibilidad" maxlength="40000" id="propuesta_sostenibilidad" error={errors.propuesta_sostenibilidad} bind:value={$form.propuesta_sostenibilidad} required />
                </div>
            {:else if proyecto.codigo_linea_programatica == 70}
                <div class="mt-4">
                    <Textarea label="Propuesta de sostenibilidad social" maxlength="40000" id="propuesta_sostenibilidad_social" error={errors.propuesta_sostenibilidad_social} bind:value={$form.propuesta_sostenibilidad_social} required />
                </div>
                <div class="mt-4">
                    <Textarea label="Propuesta de sostenibilidad ambiental" maxlength="40000" id="propuesta_sostenibilidad_ambiental" error={errors.propuesta_sostenibilidad_ambiental} bind:value={$form.propuesta_sostenibilidad_ambiental} required />
                </div>
                <div class="mt-4">
                    <Textarea label="Propuesta de sostenibilidad financiera" maxlength="40000" id="propuesta_sostenibilidad_financiera" error={errors.propuesta_sostenibilidad_financiera} bind:value={$form.propuesta_sostenibilidad_financiera} required />
                </div>
            {/if}
        </fieldset>
        <div class="py-4 bg-gray-100 border-t border-gray-200 flex items-center sticky bottom-0">
            {#if isSuperAdmin || (checkPermission(authUser, [3, 4, 6, 7, 9, 10, 12, 13, 18, 19]) && proyecto.modificable == true)}
                <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Guardar propuesta de sostenibilidad</LoadingButton>
            {/if}
        </div>
    </form>

    <hr class="mb-20 mt-20" />

    <h1 class="text-3xl m-24 text-center">Cadena de valor</h1>

    {#if isSuperAdmin || convocatoria.mostrar_recomendaciones}
        {#each proyecto.evaluaciones as evaluacion, i}
            {#if isSuperAdmin || (evaluacion.finalizado && evaluacion.habilitado)}
                <div class="bg-gray-200 p-4 rounded border-orangered border mb-5">
                    <div class="flex text-orangered-900 font-black">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        Recomendación del evaluador COD-{evaluacion.id}:
                    </div>
                    {#if evaluacion.idi_evaluacion}
                        <p class="whitespace-pre-line">{evaluacion.idi_evaluacion?.cadena_valor_comentario ? evaluacion.idi_evaluacion.cadena_valor_comentario : 'Sin recomendación'}</p>
                    {:else if evaluacion.cultura_innovacion_evaluacion}
                        <p class="whitespace-pre-line">{evaluacion.cultura_innovacion_evaluacion?.cadena_valor_comentario ? evaluacion.cultura_innovacion_evaluacion.cadena_valor_comentario : 'Sin recomendación'}</p>
                    {:else if evaluacion.ta_evaluacion}
                        <p class="whitespace-pre-line">{evaluacion.ta_evaluacion?.cadena_valor_comentario ? evaluacion.ta_evaluacion.cadena_valor_comentario : 'Sin recomendación'}</p>
                    {:else if evaluacion.tp_evaluacion}
                        <p class="whitespace-pre-line">{evaluacion.tp_evaluacion?.cadena_valor_comentario ? evaluacion.tp_evaluacion.cadena_valor_comentario : 'Sin recomendación'}</p>
                    {:else if evaluacion.servicio_tecnologico_evaluacion}
                        <hr class="mt-10 mb-10 border-black-200" />
                        <h1 class="font-black">Propuesta de sostenibilidad</h1>

                        <p class="whitespace-pre-line mb-10">{evaluacion.servicio_tecnologico_evaluacion?.propuesta_sostenibilidad_comentario ? 'Recomendación: ' + evaluacion.servicio_tecnologico_evaluacion.propuesta_sostenibilidad_comentario : 'Sin recomendación'}</p>

                        <hr class="mt-10 mb-10 border-black-200" />
                        <h1 class="font-black">Impactos</h1>

                        <ul class="list-disc pl-4">
                            <li class="whitespace-pre-line mb-10">{evaluacion.servicio_tecnologico_evaluacion?.impacto_ambiental_comentario ? 'Recomendación impacto ambiental: ' + evaluacion.servicio_tecnologico_evaluacion.impacto_ambiental_comentario : 'Sin recomendación'}</li>
                            <li class="whitespace-pre-line mb-10">{evaluacion.servicio_tecnologico_evaluacion?.impacto_social_centro_comentario ? 'Recomendación impacto social en el centro de formación: ' + evaluacion.servicio_tecnologico_evaluacion.impacto_social_centro_comentario : 'Sin recomendación'}</li>
                            <li class="whitespace-pre-line mb-10">{evaluacion.servicio_tecnologico_evaluacion?.impacto_social_productivo_comentario ? 'Recomendación impacto social en el sector productivo: ' + evaluacion.servicio_tecnologico_evaluacion.impacto_social_productivo_comentario : 'Sin recomendación'}</li>
                            <li class="whitespace-pre-line mb-10">{evaluacion.servicio_tecnologico_evaluacion?.impacto_tecnologico_comentario ? 'Recomendación impacto tecnológico: ' + evaluacion.servicio_tecnologico_evaluacion.impacto_tecnologico_comentario : 'Sin recomendación'}</li>
                        </ul>
                    {/if}
                </div>
            {/if}
        {/each}
    {/if}

    {#if productos.length == 0}
        <InfoMessage message="No ha generado productos por lo tanto tiene la cadena de valor incompleta.<br />Por favor realice los siguientes pasos:<div>1. Diríjase a <strong>Productos</strong> y genere los productos correspondientes</div><div>2. Luego diríjase a <strong>Actividades</strong> y asocie los productos y rubros correspondientes. De esta manera completa la cadena de valor.</div>" />
    {/if}

    <div class="mt-10">
        <div id="orgchart_div" class="overflow-x-scroll" style="margin: 0 -100px;" />
    </div>
</AuthenticatedLayout>

<style>
    :global(#orgchart_div table) {
        border-collapse: unset;
    }

    :global(#orgchart_div table td.google-visualization-orgchart-node-small > div) {
        margin: auto;
        width: 150px;
    }
</style>
