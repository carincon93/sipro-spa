<script>
    import { Jumper } from 'svelte-loading-spinners'

    export let loading = true
</script>

{#if !loading}
    <div class="bottom-0 fixed flex h-full items-center justify-center left-0 right-0 top-0 w-full">
        <Jumper size="60" color="#3b82f680" unit="px" duration="1s" />
    </div>
{/if}
