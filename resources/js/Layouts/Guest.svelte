<script context="module">
    import { writable } from 'svelte/store'
    export const title = writable(null)
</script>

<script>
    import { inertia, page } from '@inertiajs/inertia-svelte'
    import ApplicationLogo from '@/Components/ApplicationLogo'
</script>

<svelte:head>
    <title>{$title ? `${$title} - SIPRO-SPA` : 'SIPRO-SPA'}</title>
</svelte:head>

<div
    class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100"
>
    <div>
        <a use:inertia href="/">
            <ApplicationLogo class="w-20 h-20 fill-current text-gray-500" />
        </a>
    </div>

    <div
        class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"
    >
        <slot />
    </div>
</div>
