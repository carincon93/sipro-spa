<script>
    import { inertia, page } from '@inertiajs/inertia-svelte'
    import { route, checkRole } from '@/Utils'
    import { _ } from 'svelte-i18n'
    import { onMount } from 'svelte'

    import FileList from '@/Shared/FileList'
    import Tooltip from '@/Shared/Tooltip'

    export let convocatoria
    export let proyecto

    let versiones
    if (proyecto.proyecto && proyecto.proyecto.PdfVersiones) {
        versiones = proyecto.proyecto.PdfVersiones
    } else if (proyecto && proyecto.PdfVersiones) {
        versiones = proyecto.PdfVersiones
    } else if (proyecto.proyecto && proyecto.proyecto.pdf_versiones) {
        versiones = proyecto.proyecto.pdf_versiones
    } else if (proyecto && proyecto.pdf_versiones) {
        versiones = proyecto.pdf_versiones
    }

    let container
    let activeProyecto = route().current('convocatorias.ta.edit') || route().current('convocatorias.tp.edit') || route().current('convocatorias.idi.edit') || route().current('convocatorias.servicios-tecnologicos.edit') || route().current('convocatorias.cultura-innovacion.edit')

    onMount(() => {
        let steps = container.getElementsByClassName('step-number')
        for (let i = 0; i < steps.length; i++) {
            steps[i].textContent = i + 1
        }
    })

    /**
     * Validar si el usuario autenticado es SuperAdmin
     */
    let authUser = $page.props.auth.user
    let isSuperAdmin = checkRole(authUser, [1])

    let hideFiles = true
</script>

<!-- Stepper -->
<div class="flex justify-around my-8" id="stepper" bind:this={container}>
    <div class="w-10/12 step">
        <a use:inertia active={activeProyecto} href={route('convocatorias.proyectos.edit', [convocatoria.id, proyecto.id])} class="flex flex-col items-center">
            <div class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2 step-number" />
            <p class="text-sm text-center">Generalidades</p>
        </a>
    </div>
    {#if proyecto.codigo_linea_programatica != 70}
        <div class="w-10/12 step">
            <a use:inertia active={route().current('convocatorias.proyectos.participantes')} href={route('convocatorias.proyectos.participantes', [convocatoria.id, proyecto.id])} class="flex flex-col items-center">
                <div class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2 step-number" />
                <p class="text-sm text-center">{proyecto.codigo_linea_programatica == 68 ? 'Formulador del proyecto' : 'Participantes'}</p>
            </a>
        </div>
    {:else}
        <div class="w-10/12 step">
            <a use:inertia active={route().current('convocatorias.proyectos.articulacion-sennova')} href={route('convocatorias.proyectos.articulacion-sennova', [convocatoria.id, proyecto.id])} class="flex flex-col items-center">
                <div class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2 step-number" />
                <p class="text-sm text-center">Articulación SENNOVA</p>
            </a>
        </div>
    {/if}
    <div class="w-10/12 step">
        <a use:inertia active={route().current('convocatorias.proyectos.arbol-problemas')} href={route('convocatorias.proyectos.arbol-problemas', [convocatoria.id, proyecto.id])} class="flex flex-col items-center">
            <div class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2 step-number" />
            <p class="text-sm text-center">Árbol de problemas</p>
        </a>
    </div>
    <div class="w-10/12 step">
        <a use:inertia active={route().current('convocatorias.proyectos.arbol-objetivos')} href={route('convocatorias.proyectos.arbol-objetivos', [convocatoria.id, proyecto.id])} class="flex flex-col items-center">
            <div class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2 step-number" />
            <p class="text-sm text-center">Árbol de objetivos</p>
        </a>
    </div>
    <div class="flex justify-around relative w-10/12 px-1.5 presupuesto-container">
        {#if proyecto.codigo_linea_programatica != 23}
            <div class="w-10/12 step">
                <a use:inertia active={route().current('convocatorias.proyectos.proyecto-rol-sennova.index')} href={route('convocatorias.proyectos.proyecto-rol-sennova.index', [convocatoria.id, proyecto.id])} class="flex flex-col items-center">
                    <div class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2 step-number" />
                    <p class="text-sm text-center">Roles</p>
                </a>
            </div>
        {/if}
        <div class="w-10/12 step{proyecto.codigo_linea_programatica != 23 ? ' ml-5' : ''}">
            <a use:inertia active={route().current('convocatorias.proyectos.presupuesto.index')} href={route('convocatorias.proyectos.presupuesto.index', [convocatoria.id, proyecto.id])} class="flex flex-col items-center">
                <div class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2 step-number" />
                <p class="text-sm text-center">Presupuesto</p>
            </a>
        </div>
        {#if proyecto.codigo_linea_programatica == 70}
            <div class="w-10/12 step ml-5">
                <a use:inertia active={route().current('convocatorias.proyectos.edt.index')} href={route('convocatorias.proyectos.edt.index', [convocatoria.id, proyecto.id])} class="flex flex-col items-center">
                    <div class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2 step-number" />
                    <p class="text-sm text-center">EDT</p>
                </a>
            </div>
        {/if}
        <small class="absolute bg-cyan-500 text-white px-2 py-1 rounded-full w-max text-center total">$ {new Intl.NumberFormat('de-DE').format(!isNaN(proyecto.precio_proyecto) ? proyecto.precio_proyecto : 0)} COP</small>
    </div>
    <div class="w-10/12 step">
        <a use:inertia active={route().current('convocatorias.proyectos.actividades.index')} href={route('convocatorias.proyectos.actividades.index', [convocatoria.id, proyecto.id])} class="flex flex-col items-center">
            <div class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2 step-number" />
            <p class="text-sm text-center">Metodología y actividades</p>
        </a>
    </div>
    <div class="w-10/12 step">
        <a use:inertia active={route().current('convocatorias.proyectos.productos.index')} href={route('convocatorias.proyectos.productos.index', [convocatoria.id, proyecto.id])} class="flex flex-col items-center">
            <div class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2 step-number" />
            <p class="text-sm text-center">Productos</p>
        </a>
    </div>
    <div class="w-10/12 step">
        <a use:inertia active={route().current('convocatorias.proyectos.analisis-riesgos.index')} href={route('convocatorias.proyectos.analisis-riesgos.index', [convocatoria.id, proyecto.id])} class="flex flex-col items-center">
            <div class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2 step-number" />
            <p class="text-sm text-center">Análisis de riesgos</p>
        </a>
    </div>
    {#if proyecto.codigo_linea_programatica == 66 || proyecto.codigo_linea_programatica == 82 || proyecto.codigo_linea_programatica == 70}
        <div class="w-10/12 step">
            <a use:inertia active={route().current('convocatorias.proyectos.entidades-aliadas.index')} href={route('convocatorias.proyectos.entidades-aliadas.index', [convocatoria.id, proyecto.id])} class="flex flex-col items-center">
                <div class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2 step-number" />
                <p class="text-sm text-center">Entidades aliadas</p>
            </a>
        </div>
    {/if}
    <div class="w-10/12 step">
        <a use:inertia active={route().current('convocatorias.proyectos.proyecto-anexos.index')} href={route('convocatorias.proyectos.proyecto-anexos.index', [convocatoria.id, proyecto.id])} class="flex flex-col items-center">
            <div class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2 step-number" />
            <p class="text-sm text-center">Anexos</p>
        </a>
    </div>
    {#if proyecto.codigo_linea_programatica == 68}
        <div class="w-10/12 step">
            <a use:inertia active={route().current('convocatorias.proyectos.inventario-equipos.index')} href={route('convocatorias.proyectos.inventario-equipos.index', [convocatoria.id, proyecto.id])} class="flex flex-col items-center">
                <div class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2 step-number" />
                <p class="text-sm text-center">Inventario de equipos</p>
            </a>
        </div>
    {/if}
    <div class="w-10/12 step">
        <a use:inertia active={route().current('convocatorias.proyectos.cadena-valor')} href={route('convocatorias.proyectos.cadena-valor', [convocatoria.id, proyecto.id])} class="flex flex-col items-center">
            <div class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2 step-number" />
            <p class="text-sm text-center">Cadena de valor</p>
        </a>
    </div>

    {#if (isSuperAdmin && convocatoria.tipo_convocatoria == 1) || (proyecto.mostrar_recomendaciones && convocatoria.tipo_convocatoria == 1)}
        <div class="w-10/12 step">
            <a use:inertia active={route().current('convocatorias.proyectos.comentarios-generales-form')} href={route('convocatorias.proyectos.comentarios-generales-form', [convocatoria.id, proyecto.id])} class="flex flex-col items-center">
                <div class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2 step-number" />
                <p class="text-sm text-center">Comentarios generales</p>
            </a>
        </div>
    {/if}

    <div class="w-10/12 step">
        <a use:inertia active={route().current('convocatorias.proyectos.summary')} href={route('convocatorias.proyectos.summary', [convocatoria.id, proyecto.id])} class="flex flex-col items-center">
            <div class="rounded-full bg-white w-11 h-11 text-center flex items-center justify-center shadow mb-2 step-number" />
            <p class="text-sm text-center">Finalizar proyecto</p>
        </a>
    </div>
</div>

<FileList {versiones} {hideFiles} {convocatoria} {proyecto} />
<div class="flex items-center justify-center mb-14 relative">
    <div>
        <button on:click={() => (hideFiles = !hideFiles)} class="flex items-center flex-col border p-1 rounded-full hover:bg-gray-100 mb-2">
            <svg id="Capa_2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 497.08" class="w-6 h-6">
                <g id="Object">
                    <g>
                        <path d="M300,32.35v-8.21c0-13.33-10.81-24.14-24.14-24.14H24.14C10.81,0,0,10.81,0,24.14V459.45H600V56.49c0-13.33-10.81-24.14-24.14-24.14H300Z" style="fill:#f9df5c;" />
                        <rect x="26.64" y="67.14" width="552.34" height="396.73" style="fill:#fff;" />
                        <path d="M291.44,116.74l-8.91,24.69H24.14c-13.33,0-24.14,10.81-24.14,24.14V472.94c0,13.33,10.81,24.14,24.14,24.14H575.86c13.33,0,24.14-10.81,24.14-24.14V124.93c0-13.33-10.81-24.14-24.14-24.14H314.15c-10.17,0-19.26,6.38-22.71,15.95Z" style="fill:#f3cc30;" />
                        <path d="M600,472.94v-200.39C433.34,434.65,144.27,478.03,6.71,489.61c4.39,4.59,10.57,7.47,17.43,7.47H575.86c13.33,0,24.14-10.81,24.14-24.14Z" style="fill:#edbd31;" />
                        <path d="M243.65,160.87H39.63c-10.77,0-19.49,8.73-19.49,19.49v43.18c34.32-23.51,111.76-53.13,223.52-62.67Z" style="fill:#f6d738;" />
                    </g>
                </g>
            </svg>
        </button>
        <Tooltip placement="bottom">
            <small class="text-center block leading-tight">Clic en el icono para {hideFiles ? 'mostrar' : 'ocultar'} archivos</small>
        </Tooltip>
    </div>
</div>

<style>
    #stepper a[active='true'] .rounded-full {
        background: #58badd;
        color: #fff;
    }

    .presupuesto-container {
        border: 1px solid #6366f12b;
    }

    .total {
        bottom: -18px;
        box-shadow: -1px -9px 17px 0px rgba(99, 163, 241, 0.25);
    }
</style>
