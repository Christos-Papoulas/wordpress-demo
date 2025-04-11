<div class="mobile-filters-actions grid grid-cols-2 gap-x-5">
    <button
        x-on:click="
            clearFilters()
            closeFaceteDrawer()
        "
        class="border-red-main inline w-full cursor-pointer items-center justify-center rounded-md border bg-gray-600 px-5 py-3 text-center text-[12px] leading-4 font-semibold text-white placeholder-white transition duration-300 ease-in-out focus:outline-none focus-visible:outline-none lg:px-6 lg:text-sm xl:hidden xl:px-8"
    >
        {{ __('Clear All', 'sage') }}
    </button>
    <button
        x-on:click="closeFaceteDrawer()"
        class="inline w-full cursor-pointer items-center justify-center rounded-md border-0 border-transparent bg-gray-600 px-5 py-3 text-center text-[12px] leading-4 font-semibold text-white placeholder-white transition duration-300 ease-in-out focus:outline-none focus-visible:outline-none lg:px-6 lg:text-sm xl:hidden xl:px-8"
    >
        {{ __('OK', 'sage') }}
    </button>
</div>
