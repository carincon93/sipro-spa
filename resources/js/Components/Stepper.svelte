<script>
    import { inertia, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    export let call
    export let project
</script>

<!-- Steper -->
<div class="flex justify-around">
    <div class="w-10/12">
        <a
            use:inertia
            href={route('calls.rdi.edit', [call.id, project.id])}
            class="flex flex-col items-center inline-block"
        >
            <div
                class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2"
            >
                1
            </div>
            <p class="text-sm text-center">Generalidades</p>
        </a>
    </div>
    <div class="w-10/12">
        <a
            use:inertia
            href={route('calls.projects.problem-tree', [call.id, project.id])}
            class="flex flex-col items-center inline-block"
        >
            <div
                class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2"
            >
                2
            </div>
            <p class="text-sm text-center">Árbol de problemas</p>
        </a>
    </div>
    <div class="w-10/12">
        <a
            use:inertia
            href={route('calls.projects.objectives-tree', [
                call.id,
                project.id,
            ])}
            class="flex flex-col items-center inline-block"
        >
            <div
                class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2"
            >
                3
            </div>
            <p class="text-sm text-center">Árbol de objetivos</p>
        </a>
    </div>
    <div class="w-10/12">
        <a
            use:inertia
            href={route('calls.projects.project-sennova-roles.index', [
                call.id,
                project.id,
            ])}
            class="flex flex-col items-center inline-block"
        >
            <div
                class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2"
            >
                4
            </div>
            <p class="text-sm text-center">Roles</p>
        </a>
    </div>
    <div class="w-10/12">
        <a
            use:inertia
            href={route('calls.projects.project-sennova-budgets.index', [
                call.id,
                project.id,
            ])}
            class="flex flex-col items-center inline-block"
        >
            <div
                class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2"
            >
                5
            </div>
            <p class="text-sm text-center">Rubros</p>
        </a>
    </div>
    <div class="w-10/12">
        <a
            use:inertia
            href={route('calls.projects.participants', [call.id, project.id])}
            class="flex flex-col items-center inline-block"
        >
            <div
                class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2"
            >
                6
            </div>
            <p class="text-sm text-center">Participantes</p>
        </a>
    </div>
    <div class="w-10/12">
        <a
            use:inertia
            href={route('calls.projects.outputs.index', [call.id, project.id])}
            class="flex flex-col items-center inline-block"
        >
            <div
                class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2"
            >
                7
            </div>
            <p class="text-sm text-center">Productos</p>
        </a>
    </div>
    <div class="w-10/12">
        <a
            use:inertia
            href={route('calls.projects.activities.index', [
                call.id,
                project.id,
            ])}
            class="flex flex-col items-center inline-block"
        >
            <div
                class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2"
            >
                8
            </div>
            <p class="text-sm text-center">Actividades</p>
        </a>
    </div>
    <div class="w-10/12">
        <a
            use:inertia
            href={route('calls.projects.risk-analysis.index', [
                call.id,
                project.id,
            ])}
            class="flex flex-col items-center inline-block"
        >
            <div
                class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2"
            >
                9
            </div>
            <p class="text-sm text-center">Análisis de riesgos</p>
        </a>
    </div>
    {#if project.project_type.programmatic_line.code == 66 || project.project_type.programmatic_line.code == 82}
        <div class="w-10/12">
            <a
                use:inertia
                href={route('calls.rdi.partner-organizations.index', [
                    call.id,
                    project.id,
                ])}
                class="flex flex-col items-center inline-block"
            >
                <div
                    class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2"
                >
                    10
                </div>
                <p class="text-sm text-center">Entidades aliadas</p>
            </a>
        </div>
    {/if}
    <div class="w-10/12">
        <a
            use:inertia
            href={route('calls.projects.project-annexes.index', [
                call.id,
                project.id,
            ])}
            class="flex flex-col items-center inline-block"
        >
            <div
                class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2"
            >
                {#if project.project_type.programmatic_line.code == 66 || project.project_type.programmatic_line.code == 82}
                    11
                {:else}
                    10
                {/if}
            </div>
            <p class="text-sm text-center">Anexos</p>
        </a>
    </div>
    <div class="w-10/12">
        <a use:inertia href="#" class="flex flex-col items-center inline-block">
            <div
                class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2"
            >
                {#if project.project_type.programmatic_line.code == 66 || project.project_type.programmatic_line.code == 82}
                    12
                {:else}
                    11
                {/if}
            </div>
            <p class="text-sm text-center">Cadena de valor</p>
        </a>
    </div>
    <div class="w-10/12">
        <a href="#" class="flex flex-col items-center inline-block">
            <div
                class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2"
            >
                {#if project.project_type.programmatic_line.code == 66 || project.project_type.programmatic_line.code == 82}
                    13
                {:else}
                    12
                {/if}
            </div>
            <p class="text-sm text-center">Finalzar formulación</p>
        </a>
    </div>
</div>
