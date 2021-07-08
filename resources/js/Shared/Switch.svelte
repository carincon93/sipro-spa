<script>
    import Switch from '@smui/switch'
    import FormField from '@smui/form-field'

    export let checked = false
    checked
</script>

<FormField>
    <Switch bind:checked />
    <span slot="label"
        >{#if checked} Si {:else} No {/if}</span
    >
</FormField>
