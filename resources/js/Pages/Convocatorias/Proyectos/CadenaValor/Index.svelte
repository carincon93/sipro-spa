<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import { page } from '@inertiajs/inertia-svelte'
    import { _ } from 'svelte-i18n'

    import Stepper from '@/Shared/Stepper'
    import Loading from '@/Shared/Loading'
    import { onDestroy, onMount } from 'svelte'

    export let convocatoria
    export let proyecto
    export let objetivos
    export let productos

    let loading = false

    $title = 'Cadena de valor'

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

    <div class="mt-10">
        <div id="orgchart_div" />
        <!-- <Loading {loading} bg="transparent" /> -->
    </div>
</AuthenticatedLayout>

<style>
    :global(#orgchart_div table) {
        border-collapse: unset;
    }
</style>
