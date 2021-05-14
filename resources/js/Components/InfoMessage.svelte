<script>
    export let message
    export let classes
</script>

<div
    class="relative bg-indigo-100 p-5 text-indigo-600{classes
        ? ' ' + classes
        : ''}"
>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        stroke="currentColor"
        class="w-5"
        style="transform: translateX(-44px); position: absolute;"
    >
        <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
        />
    </svg>
    <p>{@html message}</p>
</div>
