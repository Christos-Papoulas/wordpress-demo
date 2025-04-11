<button
    x-show="Object.keys(activeFilters).length > 0"
    x-cloak
    style="display"
    x-on:click="clearFilters()"
    id="alpine-posts-clear-filters"
    class="mb-5 h-auto cursor-pointer border-none bg-transparent px-0 text-xs text-xs text-black hover:bg-transparent hover:text-red-600 focus:bg-transparent focus:outline-none lg:mb-0 lg:inline"
    aria-label="Clear All"
>
    {{ __('Clear All', 'sage') }}
</button>
