<script>
    import { onMount } from 'svelte'
    import axios from 'axios'
    import InputError from '@/Components/InputError'
    import Label from '@/Components/Label'
    import InfoMessage from '@/Components/InfoMessage'

    export let classes = ''
    export let message
    export let presupuestoSennova
    export let selectedUsoPresupuestal
    export let required
    export let showQtyInput
    export let convocatoria
    export let lineaProgramatica
    export let codigoUsoPresupuestal

    let selectedSegundoGrupoPresupuestalId
    let selectedTercerGrupoPresupuestalId
    let mensajeUsoPresupuestal

    let segundoGrupoPresupuestal = []
    let tercerGrupoPresupuestal = []
    let usosPresupuestales = []

    onMount(() => {
        getSegundoGrupoPresupuestal()
        presupuestoSennova?.convocatoria_presupuesto?.presupuesto_sennova?.segundo_grupo_presupuestal_id ? getTercerGrupoPresupuestal(presupuestoSennova?.convocatoria_presupuesto?.presupuesto_sennova?.segundo_grupo_presupuestal_id) : null
        presupuestoSennova?.convocatoria_presupuesto?.presupuesto_sennova?.tercer_grupo_presupuestal_id ? getUsosPresupuestales(presupuestoSennova?.convocatoria_presupuesto?.presupuesto_sennova?.segundo_grupo_presupuestal_id, presupuestoSennova?.convocatoria_presupuesto?.presupuesto_sennova?.tercer_grupo_presupuestal_id) : null
        presupuestoSennova?.convocatoria_presupuesto?.id ? getUsosPresupuestalesData(presupuestoSennova?.convocatoria_presupuesto?.id) : null
    })

    async function getSegundoGrupoPresupuestal() {
        let res = await axios.get(route('web-api.segundo-grupo-presupuestal'))
        segundoGrupoPresupuestal = res.data
    }

    function handleSegundoGrupoPresupuestal(e) {
        selectedSegundoGrupoPresupuestalId = e.target.value
        mensajeUsoPresupuestal = null
        showQtyInput = null
        if (selectedSegundoGrupoPresupuestalId != '') getTercerGrupoPresupuestal(selectedSegundoGrupoPresupuestalId)
    }

    async function getTercerGrupoPresupuestal(segundoGrupoPresupuestalId) {
        tercerGrupoPresupuestal = []
        usosPresupuestales = []
        let res = await axios.get(route('web-api.tercer-grupo-presupuestal', [segundoGrupoPresupuestalId]))
        tercerGrupoPresupuestal = res.data
    }

    function handleTercerGrupoPresupuestal(e) {
        mensajeUsoPresupuestal = null
        showQtyInput = null
        selectedTercerGrupoPresupuestalId = e.target.value

        if (selectedSegundoGrupoPresupuestalId != '' && selectedTercerGrupoPresupuestalId != '') getUsosPresupuestales(selectedSegundoGrupoPresupuestalId, selectedTercerGrupoPresupuestalId)
    }

    async function getUsosPresupuestales(segundoGrupoPresupuestalId, tercerGrupoPresupuestalId) {
        let res = await axios.get(route('web-api.usos-presupuestales', [convocatoria, lineaProgramatica, segundoGrupoPresupuestalId, tercerGrupoPresupuestalId]))
        usosPresupuestales = res.data
    }

    function handlepresupuestoSennova(e) {
        if (e.target.value != '') getUsosPresupuestalesData(e.target.value)
    }

    function getUsosPresupuestalesData(value) {
        mensajeUsoPresupuestal = usosPresupuestales.find((item) => item.value == value)?.mensaje

        codigoUsoPresupuestal = usosPresupuestales.find((item) => item.value == value)?.codigo

        showQtyInput = usosPresupuestales.find((item) => item.value == value)?.requiere_estudio_mercado
    }
</script>

<div class="mt-4">
    <Label id="segundo_grupo_presupuestal_id" value="Concepto interno SENA" {required} />
    <!-- svelte-ignore a11y-no-onchange -->
    <select id="segundo_grupo_presupuestal_id" class="presupuesto-info w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 focus:border-indigo-200 focus:ring-indigo-200 {classes}" on:change={(e) => handleSegundoGrupoPresupuestal(e)}>
        <option value="">Seleccione el concepto interno SENA</option>
        {#each segundoGrupoPresupuestal as { value, label }}
            <option {value} selected={presupuestoSennova?.convocatoria_presupuesto?.presupuesto_sennova?.segundo_grupo_presupuestal_id == value ? true : false}>{label}</option>
        {/each}
    </select>
</div>

<div class="mt-4">
    <Label id="tercer_grupo_presupuestal_id" value="Rubro" {required} />
    <!-- svelte-ignore a11y-no-onchange -->
    <select id="tercer_grupo_presupuestal_id" disabled={!(tercerGrupoPresupuestal.length > 0)} class="presupuesto-info w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 focus:border-indigo-200 focus:ring-indigo-200 {classes}" on:change={(e) => handleTercerGrupoPresupuestal(e)}>
        <option value="">Seleccione el rubro</option>
        {#each tercerGrupoPresupuestal as { value, label }}
            <option class="shadow p-8 hover:bg-gray-100" {value} selected={presupuestoSennova?.convocatoria_presupuesto?.presupuesto_sennova?.tercer_grupo_presupuestal_id == value ? true : false}>{label}</option>
        {/each}
    </select>
</div>

<div class="mt-4">
    <Label id="uso_presupuestal_id" value="Uso presupuestal" {required} />
    <!-- svelte-ignore a11y-no-onchange -->
    <select id="uso_presupuestal_id" disabled={!(usosPresupuestales.length > 0)} class="presupuesto-info w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 focus:border-indigo-200 focus:ring-indigo-200 {classes}" bind:value={selectedUsoPresupuestal} on:change={(e) => handlepresupuestoSennova(e)}>
        <option value="">Seleccione el uso presupuestal</option>
        {#each usosPresupuestales as { value, label }}
            <option class="shadow p-8 hover:bg-gray-100" {value} selected={presupuestoSennova?.convocatoria_presupuesto?.id == value ? true : false}>{label}</option>
        {/each}
    </select>
    <InputError {message} />
</div>

{#if mensajeUsoPresupuestal}
    <InfoMessage message={mensajeUsoPresupuestal} />
{/if}

<style>
    :global(.presupuesto-info .listItem) {
        border-bottom: 1px solid #ccc;
    }

    :global(.presupuesto-info .item) {
        height: auto !important;
        line-height: 1.6 !important;
        text-overflow: initial !important;
        overflow: initial !important;
        white-space: break-spaces !important;
        padding-top: 10px;
        padding-bottom: 10px;
    }
</style>
