<script>
    import Dialog, { Title, Content, Actions } from '@smui/dialog'

    export let open = false
    export let id
    export let size = 'mandatory'

    $: props = {
        ...$$restProps,
        class: `${$$restProps.class || ''}`,
    }
</script>

<Dialog bind:open scrimClickAction="" {...props} escapeKeyAction="" aria-labelledby="{size}-title" aria-describedby="{size}-content" id="{id}-dialog">
    <Title id="{size}-title">
        <slot name="title" />
    </Title>
    <Content id="{size}-content">
        <slot name="content" />
    </Content>
    <Actions>
        <slot name="actions" />
    </Actions>
</Dialog>
