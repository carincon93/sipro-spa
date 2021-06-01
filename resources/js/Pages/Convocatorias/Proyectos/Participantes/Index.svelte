<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import Stepper from '@/Components/Stepper'
    import ProgramasFormacion from './ProgramasFormacion'
    import SemillerosInvestigacion from './SemillerosInvestigacion'
    import Participantes from './Participantes'

    export let errors
    export let convocatoria
    export let proyecto
    export let tiposDocumento
    export let tiposParticipacion
    export let roles

    $: $title = 'Participantes'
</script>

<AuthenticatedLayout>
    <Stepper {convocatoria} {proyecto} />

    <div class="py-12">
        <div class="mt-16">
            <Participantes {convocatoria} {proyecto} {errors} {tiposDocumento} {tiposParticipacion} {roles} />
        </div>
        <hr class="my-16" />
        {#if proyecto.codigo_linea_programatica == 66 || proyecto.codigo_linea_programatica == 82 || proyecto.codigo_linea_programatica == 69 || proyecto.codigo_linea_programatica == 70}
            <div>
                <SemillerosInvestigacion {convocatoria} {proyecto} {errors} />
            </div>
        {/if}
        {#if proyecto.codigo_linea_programatica == 66 || proyecto.codigo_linea_programatica == 82}
            <hr class="my-16" />
            <div>
                <ProgramasFormacion {convocatoria} {proyecto} {errors} />
            </div>
        {/if}
    </div>
</AuthenticatedLayout>
