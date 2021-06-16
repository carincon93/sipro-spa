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
    })

    function submit() {
        if (isSuperAdmin || (checkPermission(authUser, [3, 4, 6, 7, 9, 10, 12, 13]) && proyecto.modificable == true)) {
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
        data.addRows([[{ v: 'Tercer objetivo específico', f: '<strong>Tercer objetivo específico</strong><div>' + objetivos['Tercer objetivo específico'] + '</div>' }, 'Objetivo general', 'Tercer objetivo específico']])
        data.addRows([[{ v: 'Cuarto objetivo específico', f: '<strong>Cuarto objetivo específico</strong><div>' + objetivos['Cuarto objetivo específico'] + '</div>' }, 'Objetivo general', 'Cuarto objetivo específico']])

        productos.map((producto) => {
            data.addRows([[{ v: producto.v, f: '<strong>Producto</strong><div>' + producto.f + '</div>' }, producto.fkey, producto.tooltip]])
            producto.actividades.map((actividad) => {
                data.addRows([[{ v: 'act' + producto.v + actividad.id, f: '<strong>Actividad</strong><div>' + actividad.descripcion + '</div>' }, producto.v, actividad.descripcion]])

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

    <h1 class="text-3xl m-24 text-center">Cadena de valor</h1>

    <form on:submit|preventDefault={submit}>
        <fieldset disabled={isSuperAdmin || (checkPermission(authUser, [3, 4, 6, 7, 9, 10, 12, 13]) && proyecto.modificable == true) ? undefined : true}>
            <div class="mt-4">
                <Label required class="mb-4" labelFor="propuesta_sostenibilidad" value="Propuesta de sostenibilidad" />
                <InfoMessage class="mb-2" message="Identificar los efectos que tiene el desarrollo del proyecto de investigación ya sea positivos o negativos. Se recomienda establecer las acciones pertinentes para mitigar los impactos negativos ambientales identificados y anexar el respectivo permiso ambiental cuando aplique. Tener en cuenta si aplica el decreto 1376 de 2013." />
                <Textarea label="Propuesta de sostenibilidad" maxlength="40000" id="propuesta_sostenibilidad" error={errors.propuesta_sostenibilidad} bind:value={$form.propuesta_sostenibilidad} required />
            </div>
        </fieldset>
        <div class="mt-4 bg-gray-100 border-t border-gray-200 flex items-center">
            {#if isSuperAdmin || (checkPermission(authUser, [3, 4, 6, 7, 9, 10, 12, 13]) && proyecto.modificable == true)}
                <LoadingButton loading={sending} class="btn-indigo ml-auto" type="submit">Guardar</LoadingButton>
            {/if}
        </div>
    </form>

    <hr class="mb-20 mt-20" />

    {#if productos.length == 0}
        <InfoMessage message="No ha generado productos por lo tanto tiene la cadena de valor incompleta.<br />Por favor realice los siguientes pasos:<div>1. Diríjase a <strong>Productos</strong> y genere los productos correspondientes</div><div>2. Luego diríjase a <strong>Actividades</strong> y asocie los productos y rubros correspondientes. De esta manera completa la cadena de valor.</div>" />
    {/if}

    <div class="mt-10">
        <div id="orgchart_div" />
    </div>
</AuthenticatedLayout>

<style>
    :global(#orgchart_div table) {
        border-collapse: unset;
    }
</style>
