<script>
    import { useForm, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    import LoadingButton from '@/Components/LoadingButton'
    import File from '@/Components/File'
    import PercentageProgress from '@/Components/PercentageProgress.svelte'

    export let errors
    export let call
    export let project
    export let annexe
    export let projectAnnexes
    export let sending = false

    /**
     * Permisos
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin =
        authUser.roles.filter(function (role) {
            return role.id == 1
        }).length > 0
    let canIndexProjectAnnexes =
        authUser.can.find((element) => element == 'project-annexes.index') ==
        'project-annexes.index'
    let canShowProjectAnnexes =
        authUser.can.find((element) => element == 'project-annexes.show') ==
        'project-annexes.show'
    let canCreateProjectAnnexes =
        authUser.can.find((element) => element == 'project-annexes.create') ==
        'project-annexes.create'
    let canEditProjectAnnexes =
        authUser.can.find((element) => element == 'project-annexes.edit') ==
        'project-annexes.edit'
    let canDestroyProjectAnnexes =
        authUser.can.find((element) => element == 'project-annexes.destroy') ==
        'project-annexes.delete'

    let form = useForm({
        file: null,
        annexe_id: annexe.id,
    })

    function submit() {
        if (
            (canCreateProjectAnnexes && !sending) ||
            (canEditProjectAnnexes && !sending) ||
            (isSuperAdmin && !sending)
        ) {
            $form.post(
                route('calls.projects.project-annexes.store', [
                    call.id,
                    project.id,
                ]),
                {
                    onStart: () => (sending = true),
                    onFinish: () => (sending = false),
                },
            )
        }
    }
</script>

<form on:submit|preventDefault={submit} class="mt-4 p-4">
    {#if projectAnnexes.data.filter(function (i) {
        return i.annexe_id == annexe.id
    }).length > 0}
        <a
            target="_blank"
            class="text-indigo-400 underline inline-block mb-4"
            download
            href={route('calls.projects.project-annexes.download', [
                call.id,
                project.id,
                annexe.id,
            ])}
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                />
            </svg>
            {annexe.name}
        </a>
    {/if}
    <fieldset
        disabled={(canCreateProjectAnnexes && !sending) ||
        (canEditProjectAnnexes && !sending) ||
        (isSuperAdmin && !sending)
            ? undefined
            : true}
    >
        <div>
            <File
                id="file"
                type="file"
                accept="application/pdf"
                class="mt-1 block w-full"
                bind:value={$form.file}
                error={errors?.file}
                required
            />
        </div>
        <div>
            <LoadingButton loading={sending} class="w-full mt-4" type="submit">
                {$_('Upload')}
                {annexe.name}
            </LoadingButton>
        </div>
        {#if $form.progress}
            <PercentageProgress percentage={$form.progress.percentage} />
        {/if}
    </fieldset>
</form>
