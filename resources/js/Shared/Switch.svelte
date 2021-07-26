<script>
    import Switch from '@smui/switch'
    import FormField from '@smui/form-field'

    export let checked = false
    export let disabled
</script>

<FormField>
    <Switch bind:checked {disabled} />
    <span slot="label">
        {#if checked} Si {:else} No {/if}
    </span>
</FormField>
