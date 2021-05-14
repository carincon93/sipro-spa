<script>
    $: props = {
        ...$$restProps,
        class: `${$$restProps.class || ''}`,
    }
</script>

<div {...props}>
    <h1 class="mb-8 font-bold text-center text-3xl">
        <slot name="title" />
    </h1>
    <div class="mb-8">
        <slot name="caption" />
    </div>
    <div class="mb-6 flex justify-end items-center">
        <!-- <SearchFilter class="w-full max-w-md mr-4" bind:filters /> -->
        <slot name="actions" />
    </div>
    <div class="bg-white rounded shadow">
        <table class="w-full whitespace-no-wrap data-table">
            <slot name="thead" />
            <slot name="tbody" />
            <slot name="tfoot" />
        </table>
    </div>
</div>

<style>
    :global(.data-table td.border-t.td-actions:last-child) {
        text-align: center;
        width: 150px;
    }
</style>
