<script>
    import { route } from '@/Utils'

    import FileFormat from '@/Shared/FileFormat'

    export let hideFiles = false
    export let versiones
    export let convocatoria
    export let proyecto

    proyecto = proyecto.proyecto ? proyecto.proyecto : proyecto

    let allFiles = proyecto.proyecto ? proyecto.proyecto.all_files : proyecto.all_files
</script>

<div class="bg-cyan-500 mb-12 p-8 text-white shadow-inner transition delay-150 duration-300 ease-in-out {hideFiles ? 'hide-files' : 'min-h-screen max-h-screen overflow-y-scroll'}">
    <div class="bg-files {hideFiles ? 'hidden' : ''}">
        <div class="grid grid-cols-3">
            <div>
                <h1 class="font-black text-6xl uppercase sticky top-0">Lista de archivos del proyecto</h1>
            </div>
            <div>
                {#if versiones.length > 0}
                    <h1 class="font-black">Versiones - PDF</h1>
                    <ul class="flex flex-wrap">
                        {#each versiones as version, i}
                            <li class="mr-4 w-20 hover:opacity-95">
                                {#if version.estado == 1}
                                    <a target="_blank" href={route('convocatorias.proyectos.version', [convocatoria.id, proyecto.id, version.version])}>
                                        <FileFormat extension="pdf" />
                                        <small class="block leading-tight">Versión: {version.fecha_generacion_pdf}</small>
                                    </a>
                                {/if}
                            </li>
                        {/each}
                    </ul>
                {:else}
                    <h6>Versiones - PDF</h6>

                    <p class="text-xs">No se ha generado un PDF aún</p>
                {/if}

                {#if allFiles.length > 0}
                    <h1 class="font-black my-4">Archivos cargados</h1>

                    <ul class="flex flex-wrap">
                        {#each allFiles as file}
                            {#if file.empresa}
                                <li class="mr-4 w-20 hover:opacity-95">
                                    <a target="_blank" href={file.soporte}>
                                        <FileFormat extension={file.soporte.split('.').at(-1)} />
                                        <small class="block leading-tight capitalize">{file.empresa}</small>
                                    </a>
                                </li>
                            {:else if file.formato_estudio_mercado}
                                <li class="mr-4 w-20 hover:opacity-95">
                                    <a target="_blank" href={file.formato_estudio_mercado}>
                                        <FileFormat extension={file.formato_estudio_mercado.split('.').at(-1)} />
                                        <small class="block leading-tight">Estudio de mercado</small>
                                    </a>
                                </li>
                            {:else if file.carta_intencion}
                                <li class="mr-4 w-20 hover:opacity-95">
                                    <a target="_blank" href={file.carta_intencion}>
                                        <FileFormat extension={file.carta_intencion.split('.').at(-1)} />
                                        <small class="block leading-tight capitalize paragraph-ellipsis">{file.entidad_aliada}</small>
                                    </a>
                                </li>
                            {:else if file.carta_intencion}
                                <li class="mr-4 w-20 hover:opacity-95">
                                    <a target="_blank" href={file.carta_propiedad_intelectual}>
                                        <FileFormat extension={file.carta_propiedad_intelectual.split('.').at(-1)} />
                                        <small class="block leading-tight capitalize paragraph-ellipsis">{file.entidad_aliada}</small>
                                    </a>
                                </li>
                            {:else if file.soporte_convenio}
                                <li class="mr-4 w-20 hover:opacity-95">
                                    <a target="_blank" href={file.soporte_convenio}>
                                        <FileFormat extension={file.soporte_convenio.split('.').at(-1)} />
                                        <small class="block leading-tight capitalize paragraph-ellipsis">{file.entidad_aliada}</small>
                                    </a>
                                </li>
                            {:else if file.anexo}
                                <li class="mr-4 w-20 hover:opacity-95">
                                    <a target="_blank" href={file.archivo}>
                                        <FileFormat extension={file.archivo.split('.').at(-1)} />
                                        <small class="block leading-tight paragraph-ellipsis">{file.anexo}</small>
                                    </a>
                                </li>
                            {/if}
                        {/each}
                    </ul>
                {/if}
            </div>
        </div>
    </div>
</div>

<style>
    .bg-files {
        background-image: url(/images/files.png);
        background-position: 98%;
        background-repeat: no-repeat;
        background-size: 14%;
        background-attachment: fixed;
    }

    .hide-files {
        opacity: 0;
        padding: 0;
        height: 0;
    }
</style>
