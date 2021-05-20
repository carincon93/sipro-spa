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

    let selectedSegundoGrupoPresupuestalId = presupuestoSennova?.convocatoria_presupuesto?.presupuesto_sennova?.segundo_grupo_presupuestal_id
    let selectedTercerGrupoPresupuestalId = presupuestoSennova?.convocatoria_presupuesto?.presupuesto_sennova?.tercer_grupo_presupuestal_id
    let seletcedConvocatoriaPresupuestoId = presupuestoSennova?.convocatoria_presupuesto?.id
    let mensajeUsoPresupuestal

    let segundoGrupoPresupuestal = []
    let tercerGrupoPresupuestal = []
    $: usosPresupuestales = []
    onMount(() => {
        getSegundoGrupoPresupuestal()
        selectedSegundoGrupoPresupuestalId ? getTercerGrupoPresupuestal(selectedSegundoGrupoPresupuestalId) : null
        selectedTercerGrupoPresupuestalId ? getUsosPresupuestales(selectedSegundoGrupoPresupuestalId, selectedTercerGrupoPresupuestalId) : null
        // seletcedConvocatoriaPresupuestoId ? getUsosPresupuestalesData(seletcedConvocatoriaPresupuestoId) : null
    })

    function handleSegundoGrupoPresupuestal(e) {
        selectedTercerGrupoPresupuestalId = undefined
        selectedSegundoGrupoPresupuestalId = e.target.value
        mensajeUsoPresupuestal = null
        showQtyInput = null
        if (selectedSegundoGrupoPresupuestalId != '') getTercerGrupoPresupuestal(selectedSegundoGrupoPresupuestalId)
    }

    async function getSegundoGrupoPresupuestal() {
        let res = await axios.get(route('web-api.segundo-grupo-presupuestal'))
        segundoGrupoPresupuestal = res.data
    }

    function handleTercerGrupoPresupuestal(e) {
        mensajeUsoPresupuestal = null
        showQtyInput = null
        selectedTercerGrupoPresupuestalId = e.target.value

        if (selectedSegundoGrupoPresupuestalId != '' && selectedTercerGrupoPresupuestalId != '') getUsosPresupuestales(selectedSegundoGrupoPresupuestalId, selectedTercerGrupoPresupuestalId)
    }

    async function getTercerGrupoPresupuestal(segundoGrupoPresupuestalId) {
        tercerGrupoPresupuestal = []
        usosPresupuestales = []
        let res = await axios.get(route('web-api.tercer-grupo-presupuestal', [segundoGrupoPresupuestalId]))
        tercerGrupoPresupuestal = res.data
    }

    async function getUsosPresupuestales(segundoGrupoPresupuestalId, tercerGrupoPresupuestalId) {
        let res = await axios.get(route('web-api.usos-presupuestales', [convocatoria, lineaProgramatica, segundoGrupoPresupuestalId, tercerGrupoPresupuestalId]))
        usosPresupuestales = res.data
    }

    function handlePresupuestoSennova(e) {
        if (e.target.value != '') seletcedConvocatoriaPresupuestoId = e.target.value
    }

    let sinUsosPresupuestales = false
    $: if (usosPresupuestales) {
        setTimeout(() => {
            if (selectedTercerGrupoPresupuestalId != undefined && usosPresupuestales.length == 0) {
                sinUsosPresupuestales = true
                document.getElementById('uso_presupuestal_id').removeAttribute('disabled')
                document.getElementById('uso_presupuestal_id').setCustomValidity('No hay usos presupuestales para este filtro, por favor realice un filtro diferente')
            } else {
                sinUsosPresupuestales = false
                document.getElementById('uso_presupuestal_id').setCustomValidity('')
            }
        }, 500)
        mensajeUsoPresupuestal = usosPresupuestales.find((item) => item.value == seletcedConvocatoriaPresupuestoId)?.mensaje
        codigoUsoPresupuestal = usosPresupuestales.find((item) => item.value == seletcedConvocatoriaPresupuestoId)?.codigo
        showQtyInput = usosPresupuestales.find((item) => item.value == seletcedConvocatoriaPresupuestoId)?.requiere_estudio_mercado
    }
</script>

<div class="mt-4">
    <Label labelFor="segundo_grupo_presupuestal_id" value="Concepto interno SENA" {required} />
    <!-- svelte-ignore a11y-no-onchange -->
    <select id="segundo_grupo_presupuestal_id" class="presupuesto-info w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 focus:border-indigo-200 focus:ring-indigo-200 {classes}" on:change={(e) => handleSegundoGrupoPresupuestal(e)} required>
        <option value="">Seleccione el concepto interno SENA</option>
        {#each segundoGrupoPresupuestal as { value, label }}
            <option {value} selected={selectedSegundoGrupoPresupuestalId == value ? true : false}>{label}</option>
        {/each}
    </select>
</div>

<div class="mt-4">
    <Label labelFor="tercer_grupo_presupuestal_id" value="Rubro" {required} />
    <!-- svelte-ignore a11y-no-onchange -->
    <select id="tercer_grupo_presupuestal_id" disabled={!(tercerGrupoPresupuestal.length > 0)} class="presupuesto-info w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 focus:border-indigo-200 focus:ring-indigo-200 {classes}" on:change={(e) => handleTercerGrupoPresupuestal(e)} required>
        <option value="">Seleccione el rubro</option>
        {#each tercerGrupoPresupuestal as { value, label }}
            <option class="shadow p-8 hover:bg-gray-100" {value} selected={selectedTercerGrupoPresupuestalId == value ? true : false}>{label}</option>
        {/each}
    </select>
</div>

<div class="mt-4">
    <Label labelFor="uso_presupuestal_id" value="Uso presupuestal" {required} />
    <!-- svelte-ignore a11y-no-onchange -->
    <select id="uso_presupuestal_id" disabled={!(usosPresupuestales.length > 0)} class="presupuesto-info w-full border-gray-300 rounded-md shadow-sm focus:ring focus:ring-opacity-50 focus:border-indigo-200 focus:ring-indigo-200 {classes}" bind:value={selectedUsoPresupuestal} on:change={(e) => handlePresupuestoSennova(e)} required>
        <option value="">Seleccione el uso presupuestal</option>
        {#each usosPresupuestales as { value, label }}
            <option class="shadow p-8 hover:bg-gray-100" {value} selected={seletcedConvocatoriaPresupuestoId == value ? true : false}>{label}</option>
        {/each}
    </select>
    <InputError {message} />
    {#if sinUsosPresupuestales}
        <InfoMessage message="No hay usos presupuestales para este filtro, por favor realice un filtro diferente" />
    {/if}
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
