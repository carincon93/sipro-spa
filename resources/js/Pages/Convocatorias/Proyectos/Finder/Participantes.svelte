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

    <div class="py-12">
        <div class="mt-16">
            <UsersFinder />
        </div>
        <div class="mt-80">
            <ResearchTeamsFinder />
        </div>
        <div class="mt-80">
            <AcademicProgramsFinder />
        </div>
    </div>
</AuthenticatedLayout>
