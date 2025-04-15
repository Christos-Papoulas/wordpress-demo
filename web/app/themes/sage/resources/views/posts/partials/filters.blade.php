{{-- Archive Filters --}}
<div id="htech-posts-filters" class="noselect flex h-full flex-wrap">
    <div
        class="custom-scrollbar h-full w-full overflow-y-auto px-5 pt-5 lg:px-3 lg:pt-0 xl:h-auto xl:overflow-hidden xl:px-7"
    >
        <div class="block">
            <div class="sidebar-header flex items-center justify-between py-3">
                <h2 style="height: 56px" class="paragraph-50 mb-0 flex items-center uppercase">
                    {{ __('FILTERS', 'sage') }}
                </h2>
                <img
                    class="hidden lg:block"
                    src="{{ Vite::asset('resources/images/icons/settings.svg') }}"
                    alt="icon_settings"
                    width="22px"
                    height="22px"
                />
            </div>

            <button
                x-on:click="closeFaceteDrawer()"
                class="absolute top-1 right-1 flex items-center justify-center px-4 py-6 text-2xl text-gray-800 transition-opacity hover:opacity-60 focus:outline-none lg:hidden lg:px-6 xl:py-8"
                aria-label="close"
            >
                <svg
                    stroke="currentColor"
                    fill="currentColor"
                    stroke-width="0"
                    viewBox="0 0 512 512"
                    class="mt-1 text-black lg:mt-0.5"
                    height="1em"
                    width="1em"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M289.94 256l95-95A24 24 0 00351 127l-95 95-95-95a24 24 0 00-34 34l95 95-95 95a24 24 0 1034 34l95-95 95 95a24 24 0 0034-34z"
                    ></path>
                </svg>
            </button>
        </div>

        <div class="filterboxes h-[calc(100%_-_155px)] overflow-y-auto xl:h-full">
            @include('posts.partials.components.facetes')
        </div>
    </div>

    <div class="absolute bottom-0 w-full bg-white px-5 py-4 xl:hidden">
        @include('posts.partials.components.mobile-actions')
    </div>
</div>
