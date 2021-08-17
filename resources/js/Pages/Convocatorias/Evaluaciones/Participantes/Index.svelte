<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import EvaluationStepper from '@/Shared/EvaluationStepper'
    import SemillerosInvestigacion from './SemillerosInvestigacion'
    import Participantes from './Participantes'

    export let errors
    export let convocatoria
    export let evaluacion
    export let proyecto
    export let tiposDocumento
    export let tiposVinculacion
    export let roles

    $: $title = 'Participantes'
</script>

<AuthenticatedLayout>
    <EvaluationStepper {convocatoria} {evaluacion} {proyecto} />

    <div class="py-12">
        <div class="mt-16">
            <Participantes {convocatoria} {proyecto} {errors} {tiposDocumento} {tiposVinculacion} {roles} />
        </div>
        <hr class="my-16" />
        {#if proyecto.codigo_linea_programatica == 66 || proyecto.codigo_linea_programatica == 82 || proyecto.codigo_linea_programatica == 69 || proyecto.codigo_linea_programatica == 70 || proyecto.codigo_linea_programatica == 65}
            <div>
                <SemillerosInvestigacion {convocatoria} {proyecto} {errors} />
            </div>
        {/if}
    </div>
</AuthenticatedLayout>
