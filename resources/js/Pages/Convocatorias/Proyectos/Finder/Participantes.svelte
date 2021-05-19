<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import Stepper from '@/Components/Stepper'

    import AcademicProgramsFinder from './AcademicProgramsFinder'
    import ResearchTeamsFinder from './ResearchTeamsFinder'
    import UsersFinder from './UsersFinder'

    export let convocatoria
    export let proyecto

    $: $title = 'Participantes'
</script>

<AuthenticatedLayout>
    <Stepper {convocatoria} {proyecto} />

    <div class="mt-24">
        <UsersFinder />
    </div>
    <div class="mt-80">
        <ResearchTeamsFinder />
    </div>
    <div class="mt-80">
        <AcademicProgramsFinder />
    </div>
</AuthenticatedLayout>
