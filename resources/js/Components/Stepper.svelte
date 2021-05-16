<script>
    import { inertia, page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    export let convocatoria
    export let proyecto
</script>

<!-- Steper -->
<div class="flex justify-around">
    <div class="w-10/12">
        <a use:inertia href={route('convocatorias.idi.edit', [convocatoria.id, proyecto.id])} class="flex flex-col items-center inline-block">
            <div class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2">1</div>
            <p class="text-sm text-center">Generalidades</p>
        </a>
    </div>
    <div class="w-10/12">
        <a use:inertia href={route('convocatorias.proyectos.arbol-problemas', [convocatoria.id, proyecto.id])} class="flex flex-col items-center inline-block">
            <div class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2">2</div>
            <p class="text-sm text-center">Árbol de problemas</p>
        </a>
    </div>
    <div class="w-10/12">
        <a use:inertia href={route('convocatorias.proyectos.arbol-objetivos', [convocatoria.id, proyecto.id])} class="flex flex-col items-center inline-block">
            <div class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2">3</div>
            <p class="text-sm text-center">Árbol de objetivos</p>
        </a>
    </div>
    <div class="w-10/12">
        <a use:inertia href={route('convocatorias.proyectos.proyecto-rol-sennova.index', [convocatoria.id, proyecto.id])} class="flex flex-col items-center inline-block">
            <div class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2">4</div>
            <p class="text-sm text-center">Roles</p>
        </a>
    </div>
    <div class="w-10/12">
        <a use:inertia href={route('convocatorias.proyectos.proyecto-presupuesto-sennova.index', [convocatoria.id, proyecto.id])} class="flex flex-col items-center inline-block">
            <div class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2">5</div>
            <p class="text-sm text-center">Rubros</p>
        </a>
    </div>
    <div class="w-10/12">
        <a use:inertia href={route('convocatorias.proyectos.participants', [convocatoria.id, proyecto.id])} class="flex flex-col items-center inline-block">
            <div class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2">6</div>
            <p class="text-sm text-center">Participantes</p>
        </a>
    </div>
    <div class="w-10/12">
        <a use:inertia href={route('convocatorias.proyectos.productos.index', [convocatoria.id, proyecto.id])} class="flex flex-col items-center inline-block">
            <div class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2">7</div>
            <p class="text-sm text-center">Productos</p>
        </a>
    </div>
    <div class="w-10/12">
        <a use:inertia href={route('convocatorias.proyectos.actividades.index', [convocatoria.id, proyecto.id])} class="flex flex-col items-center inline-block">
            <div class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2">8</div>
            <p class="text-sm text-center">Actividades</p>
        </a>
    </div>
    <div class="w-10/12">
        <a use:inertia href={route('convocatorias.proyectos.analisis-riesgos.index', [convocatoria.id, proyecto.id])} class="flex flex-col items-center inline-block">
            <div class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2">9</div>
            <p class="text-sm text-center">Análisis de riesgos</p>
        </a>
    </div>
    {#if proyecto.codigo_linea_programatica == 66 || proyecto.codigo_linea_programatica == 82}
        <div class="w-10/12">
            <a use:inertia href={route('convocatorias.idi.entidades-aliadas.index', [convocatoria.id, proyecto.id])} class="flex flex-col items-center inline-block">
                <div class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2">10</div>
                <p class="text-sm text-center">Entidades aliadas</p>
            </a>
        </div>
    {/if}
    <div class="w-10/12">
        <a use:inertia href={route('convocatorias.proyectos.proyecto-anexos.index', [convocatoria.id, proyecto.id])} class="flex flex-col items-center inline-block">
            <div class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2">
                {#if proyecto.codigo_linea_programatica == 66 || proyecto.codigo_linea_programatica == 82}
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
            <div class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2">
                {#if proyecto.codigo_linea_programatica == 66 || proyecto.codigo_linea_programatica == 82}
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
            <div class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2">
                {#if proyecto.codigo_linea_programatica == 66 || proyecto.codigo_linea_programatica == 82}
                    13
                {:else}
                    12
                {/if}
            </div>
            <p class="text-sm text-center">Finalzar formulación</p>
        </a>
    </div>
</div>
