<script>
    import Dialog, { Title, Content, Actions } from '@smui/dialog'

    export let open = false
    export let id
    export let size = '850px'
    export let hexBgColor = '#fff'

    $: props = {
        ...$$restProps,
        class: `${$$restProps.class || ''}`,
    }
</script>

<Dialog surface$style="width: {size}; max-width: calc(100vw - 32px); background-color: {hexBgColor}" bind:open scrimClickAction="" {...props} escapeKeyAction="" aria-labelledby="mandatory-title" aria-describedby="mandatory-content" id="{id}-dialog">
    <Title id="mandatory-title">
        <slot name="title" />
    </Title>
    <Content id="mandatory-content">
        <slot name="content" />
    </Content>
    <Actions>
        <slot name="actions" />
    </Actions>
</Dialog>
