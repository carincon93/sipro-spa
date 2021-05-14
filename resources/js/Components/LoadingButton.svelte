<script>
    import Button, { Label } from '@smui/button'

    export let loading = false
    export let variant = 'raised'

    $: props = {
        ...$$restProps,
        class: `inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150 ${
            $$restProps.class || ''
        }`,
    }
</script>

<Button {...props} disabled={loading} action={null} {variant}>
    {#if loading}
        <div class="btn-spinner mr-2" />
    {/if}

    <Label>
        <slot />
    </Label>
</Button>

<style>
    :global(.mdc-button) {
        height: auto;
        min-height: 36px;
    }
</style>
