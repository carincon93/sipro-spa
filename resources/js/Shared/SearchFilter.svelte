<script>
    import { Inertia } from '@inertiajs/inertia'
    import { page } from '@inertiajs/inertia-svelte'
    import { route } from '@/Utils'
    import pickBy from 'lodash/pickBy'
    import debounce from 'lodash/debounce'

    export let routeParams

    $: props = {
        ...$$restProps,
        class: `flex items-center ${$$restProps.class || ''}`,
    }

    let readyToSearch = false
    let form = {
        search: $page.props.filters.search,
    }

    $: search(form)

    function reset() {
        Object.keys(form).forEach((key) => (form[key] = null))
    }

    const search = debounce((form) => {
        if (!readyToSearch) {
            readyToSearch = true
            return
        }

        let query = pickBy(form)

        Inertia.get(route(route().current(), routeParams), Object.keys(query).length ? query : { remember: 'forget' }, { preserveState: true, preserveScroll: true })
    }, 150)
</script>

<div {...props}>
    <input class="relative w-full px-6 py-3 rounded focus:shadow-outline" autocomplete="off" type="text" name="search" placeholder="Buscar…" bind:value={form.search} />

    <button class="ml-3 text-sm text-gray-500 hover:text-gray-700 focus:text-indigo-500" type="button" on:click={reset}> Limpiar </button>
</div>
