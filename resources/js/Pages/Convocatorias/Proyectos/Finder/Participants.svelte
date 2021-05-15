<script>
    import AuthenticatedLayout, { title } from '@/Layouts/Authenticated'
    import Stepper from '@/Components/Stepper.svelte'

    import AcademicProgramsFinder from './AcademicProgramsFinder.svelte'
    import ResearchTeamsFinder from './ResearchTeamsFinder.svelte'
    import UsersFinder from './UsersFinder.svelte'

    export let call
    export let project

    $: $title = 'Participantes'
</script>

<AuthenticatedLayout>
    <Stepper {call} {project} />

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
