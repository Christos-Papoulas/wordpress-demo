<div class="fixed bottom-20 left-0 z-10 flex w-full items-center justify-evenly">
    <button
        x-on:click="toggleFacetesDrawer()"
        class="noselect mouse flex h-6 w-24 items-center justify-center rounded-full bg-gray-600 px-2 py-5 text-sm text-white shadow transition duration-200 ease-in focus:outline-none active:shadow-lg xl:hidden"
    >
        <svg
            xmlns="http://www.w3.org/2000/svg"
            class="h-4 w-4"
            fill="none"
            viewBox="0 0 24 24"
            stroke="currentColor"
            stroke-width="2"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"
            />
        </svg>
        <span>{{ __('Filters', 'sage') }}</span>
    </button>
</div>
