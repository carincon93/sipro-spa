<script>
    import { inertia } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import { _ } from 'svelte-i18n'

    export let convocatoria
    export let proyecto

    let activeProyecto = route().current('convocatorias.tatp.edit') || route().current('convocatorias.idi.edit') || route().current('convocatorias.servicios-tecnologicos.edit') || route().current('convocatorias.cultura-innovacion.edit')
</script>

<!-- Steper -->
<div class="flex justify-around" id="stepper">
    <div class="w-10/12">
        <a use:inertia active={activeProyecto} href={route('convocatorias.proyectos.edit', [convocatoria.id, proyecto.id])} class="flex flex-col items-center inline-block">
            <div class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2">1</div>
            <p class="text-sm text-center">Generalidades</p>
        </a>
    </div>
    <div class="w-10/12">
        <a use:inertia active={route().current('convocatorias.proyectos.arbol-problemas')} href={route('convocatorias.proyectos.arbol-problemas', [convocatoria.id, proyecto.id])} class="flex flex-col items-center inline-block">
            <div class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2">2</div>
            <p class="text-sm text-center">Árbol de problemas</p>
        </a>
    </div>
    <div class="w-10/12">
        <a use:inertia active={route().current('convocatorias.proyectos.arbol-objetivos')} href={route('convocatorias.proyectos.arbol-objetivos', [convocatoria.id, proyecto.id])} class="flex flex-col items-center inline-block">
            <div class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2">3</div>
            <p class="text-sm text-center">Árbol de objetivos</p>
        </a>
    </div>
    <div class="flex justify-around relative w-10/12 px-1.5 presupuesto-container">
        {#if proyecto.codigo_linea_programatica != 23}
            <div class="w-10/12">
                <a use:inertia active={route().current('convocatorias.proyectos.proyecto-rol-sennova.index')} href={route('convocatorias.proyectos.proyecto-rol-sennova.index', [convocatoria.id, proyecto.id])} class="flex flex-col items-center inline-block">
                    <div class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2">4</div>
                    <p class="text-sm text-center">Roles</p>
                </a>
            </div>
        {/if}
        <div class="w-10/12{proyecto.codigo_linea_programatica != 23 ? ' ml-5' : ''}">
            <a use:inertia active={route().current('convocatorias.proyectos.presupuesto.index')} href={route('convocatorias.proyectos.presupuesto.index', [convocatoria.id, proyecto.id])} class="flex flex-col items-center inline-block">
                <div class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2">{proyecto.codigo_linea_programatica != 23 ? 5 : 4}</div>
                <p class="text-sm text-center">Presupuesto</p>
            </a>
        </div>
        <small class="absolute bg-indigo-500 text-white px-2 py-1 rounded-full w-max text-center total">$ {new Intl.NumberFormat('de-DE').format(!isNaN(proyecto.precio_proyecto) ? proyecto.precio_proyecto : 0)} COP</small>
    </div>
    <div class="w-10/12">
        <a use:inertia active={route().current('convocatorias.proyectos.participantes')} href={route('convocatorias.proyectos.participantes', [convocatoria.id, proyecto.id])} class="flex flex-col items-center inline-block">
            <div class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2">{proyecto.codigo_linea_programatica != 23 ? 6 : 5}</div>
            <p class="text-sm text-center">Participantes</p>
        </a>
    </div>
    <div class="w-10/12">
        <a use:inertia active={route().current('convocatorias.proyectos.productos.index')} href={route('convocatorias.proyectos.productos.index', [convocatoria.id, proyecto.id])} class="flex flex-col items-center inline-block">
            <div class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2">{proyecto.codigo_linea_programatica != 23 ? 7 : 6}</div>
            <p class="text-sm text-center">Productos</p>
        </a>
    </div>
    <div class="w-10/12">
        <a use:inertia active={route().current('convocatorias.proyectos.actividades.index')} href={route('convocatorias.proyectos.actividades.index', [convocatoria.id, proyecto.id])} class="flex flex-col items-center inline-block">
            <div class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2">{proyecto.codigo_linea_programatica != 23 ? 8 : 7}</div>
            <p class="text-sm text-center">Actividades</p>
        </a>
    </div>
    <div class="w-10/12">
        <a use:inertia active={route().current('convocatorias.proyectos.analisis-riesgos.index')} href={route('convocatorias.proyectos.analisis-riesgos.index', [convocatoria.id, proyecto.id])} class="flex flex-col items-center inline-block">
            <div class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2">{proyecto.codigo_linea_programatica != 23 ? 9 : 8}</div>
            <p class="text-sm text-center">Análisis de riesgos</p>
        </a>
    </div>
    {#if proyecto.codigo_linea_programatica == 66 || proyecto.codigo_linea_programatica == 82 || proyecto.codigo_linea_programatica == 70}
        <div class="w-10/12">
            <a use:inertia active={route().current('convocatorias.proyectos.entidades-aliadas.index')} href={route('convocatorias.proyectos.entidades-aliadas.index', [convocatoria.id, proyecto.id])} class="flex flex-col items-center inline-block">
                <div class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2">{proyecto.codigo_linea_programatica != 23 && proyecto.codigo_linea_programatica != 65 ? 10 : 9}</div>
                <p class="text-sm text-center">Entidades aliadas</p>
            </a>
        </div>
    {/if}
    <div class="w-10/12">
        <a use:inertia active={route().current('convocatorias.proyectos.proyecto-anexos.index')} href={route('convocatorias.proyectos.proyecto-anexos.index', [convocatoria.id, proyecto.id])} class="flex flex-col items-center inline-block">
            <div class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2">
                {#if proyecto.codigo_linea_programatica == 66 || proyecto.codigo_linea_programatica == 82 || proyecto.codigo_linea_programatica == 70}
                    11
                {:else if proyecto.codigo_linea_programatica == 23}
                    9
                {:else}
                    10
                {/if}
            </div>
            <p class="text-sm text-center">Anexos</p>
        </a>
    </div>
    <div class="w-10/12">
        <a use:inertia active={route().current('convocatorias.proyectos.cadena-valor')} href={route('convocatorias.proyectos.cadena-valor', [convocatoria.id, proyecto.id])} class="flex flex-col items-center inline-block">
            <div class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2">
                {#if proyecto.codigo_linea_programatica == 66 || proyecto.codigo_linea_programatica == 82 || proyecto.codigo_linea_programatica == 70}
                    12
                {:else if proyecto.codigo_linea_programatica == 23}
                    10
                {:else}
                    11
                {/if}
            </div>
            <p class="text-sm text-center">Cadena de valor</p>
        </a>
    </div>
    <div class="w-10/12">
        <a use:inertia active={route().current('convocatorias.proyectos.summary')} href={route('convocatorias.proyectos.summary', [convocatoria.id, proyecto.id])} class="flex flex-col items-center inline-block">
            <div class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2">
                {#if proyecto.codigo_linea_programatica == 66 || proyecto.codigo_linea_programatica == 82 || proyecto.codigo_linea_programatica == 70}
                    13
                {:else if proyecto.codigo_linea_programatica == 23}
                    11
                {:else}
                    12
                {/if}
            </div>
            <p class="text-sm text-center">Finalizar formulación</p>
        </a>
    </div>
</div>

<style>
    #stepper a[active='true'] .rounded-full {
        background: #6366f1;
        color: #fff;
    }

    .presupuesto-container {
        border: 1px solid #6366f12b;
    }

    .total {
        bottom: -18px;
        box-shadow: -1px -9px 17px 0px rgb(99 102 241 / 25%);
    }
</style>
