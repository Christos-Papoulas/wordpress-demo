<div x-cloak x-show="!filtersLoading" x-cloak style="display: none !important">
    <div id="alpine-posts-facetes" class="relative h-full w-full overflow-y-auto bg-white py-4" tabindex="-1">
        <div class="flex flex-1 flex-col justify-between">
            <div
                id="accordion-flush"
                data-accordion="collapse"
                data-active-classes="text-black"
                data-inactive-classes="text-black"
            >
                {{-- category tree --}}
                <div class="">
                    <div class="facete-wrapper">
                        <h2 class="facete-heading">
                            <button
                                type="button"
                                class="border-gray-350 flex w-full items-center justify-between border-b px-4 py-3 text-left text-sm font-medium text-gray-900"
                                aria-expanded="false"
                            >
                                <span>ΚΑΤΗΓΟΡΙΕΣ</span>
                                <svg
                                    aria-hidden="false"
                                    class="h-5 w-5 shrink-0 rotate-180"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        clip-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    ></path>
                                </svg>
                            </button>
                        </h2>
                        <div aria-labelledby="cat-tree-heading" class="facete-body">
                            <div class="border-gray-350 border-b px-2 py-5 font-light">
                                <ul
                                    class="custom-scrollbar mb-0 ml-0 list-none space-y-2 px-2 pb-2 xl:max-h-[500px] xl:overflow-auto"
                                >
                                    @foreach ($category_tree as $key => $row)
                                        <li class="w-full" x-data="{ show: false }">
                                            <div
                                                class="flex w-full justify-between text-gray-500"
                                                :class="show ? '!text-black' : ''"
                                            >
                                                <a
                                                    href="{{ get_term_link($row['term']->term_id) }}"
                                                    class="text-sm font-medium uppercase"
                                                >
                                                    {!! $row['term']->name !!}
                                                </a>
                                                @if (! empty($row['children']))
                                                    <div
                                                        class="cursor-pointer px-2 text-sm"
                                                        x-on:click="show = ! show"
                                                        x-html="show ? '-' : '+'"
                                                    ></div>
                                                @endif
                                            </div>
                                            <ul class="my-4 list-none" x-show="show" x-cloak>
                                                @foreach ($row['children'] as $key_lvl_2 => $row_lvl_2)
                                                    <li class="w-full" x-data="{ show: false }">
                                                        <div
                                                            class="flex w-full justify-between text-gray-500"
                                                            :class="show ? '!text-black' : ''"
                                                        >
                                                            <a
                                                                href="{{ get_term_link($row_lvl_2['term']->term_id) }}"
                                                                class="text-sm font-medium uppercase"
                                                            >
                                                                - {!! $row_lvl_2['term']->name !!}
                                                            </a>
                                                            @if (! empty($row_lvl_2['children']))
                                                                <div
                                                                    class="cursor-pointer px-2 text-sm"
                                                                    x-on:click="show = ! show"
                                                                    x-html="show ? '-' : '+'"
                                                                ></div>
                                                            @endif
                                                        </div>
                                                        <ul class="my-4 list-none" x-show="show" x-cloak>
                                                            @foreach ($row_lvl_2['children'] as $row_lvl_3)
                                                                <div class="flex w-full justify-between text-gray-500">
                                                                    <a
                                                                        href="{{ get_term_link($row_lvl_2['term']->term_id) }}"
                                                                        class="text-sm font-medium uppercase"
                                                                    >
                                                                        -- {!! $row_lvl_2['term']->name !!}
                                                                    </a>
                                                                </div>
                                                            @endforeach
                                                        </ul>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- location tree --}}
                @if (! empty($location_tree))
                    <div class="">
                        <div class="facete-wrapper">
                            <h2 class="facete-heading">
                                <button
                                    type="button"
                                    class="border-gray-350 flex w-full items-center justify-between border-b px-4 py-3 text-left text-sm font-medium text-gray-900"
                                    aria-expanded="false"
                                >
                                    <span>ΤΟΠΟΘΕΣΙΑ</span>
                                    <svg
                                        aria-hidden="false"
                                        class="h-5 w-5 shrink-0 rotate-180"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                        xmlns="http://www.w3.org/2000/svg"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            clip-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        ></path>
                                    </svg>
                                </button>
                            </h2>
                            <div aria-labelledby="cat-tree-heading" class="facete-body">
                                <div class="border-gray-350 border-b px-2 py-5 font-light">
                                    <ul
                                        class="custom-scrollbar mb-0 ml-0 list-none space-y-2 px-2 pb-2 xl:max-h-[500px] xl:overflow-auto"
                                    >
                                        @foreach ($location_tree as $key => $row)
                                            <li class="w-full" x-data="{ show: false }">
                                                <div
                                                    class="flex w-full justify-between text-gray-500"
                                                    :class="show ? '!text-black' : ''"
                                                >
                                                    <a
                                                        href="{{ get_term_link($row['term']->term_id) }}"
                                                        class="text-sm font-medium uppercase"
                                                    >
                                                        {!! $row['term']->name !!}
                                                    </a>
                                                    @if (! empty($row['children']) || ! empty($row['posts']))
                                                        <div
                                                            class="cursor-pointer px-2 text-sm"
                                                            x-on:click="show = ! show"
                                                            x-html="show ? '-' : '+'"
                                                        ></div>
                                                    @endif
                                                </div>
                                                <ul class="my-4 list-none" x-show="show" x-cloak>
                                                    {{-- posts --}}
                                                    @foreach ($row['posts'] as $project_post)
                                                        <li class="w-full" x-data="{ show: false }">
                                                            <a
                                                                href="{{ get_permalink($project_post) }}"
                                                                class="text-xs font-medium uppercase"
                                                            >
                                                                - {!! $project_post->post_title !!}
                                                            </a>
                                                        </li>
                                                    @endforeach

                                                    {{-- subccats --}}
                                                    @foreach ($row['children'] as $key_lvl_2 => $row_lvl_2)
                                                        <li class="w-full" x-data="{ show: false }">
                                                            <div
                                                                class="flex w-full justify-between text-gray-500"
                                                                :class="show ? '!text-black' : ''"
                                                            >
                                                                <a
                                                                    href="{{ get_term_link($row_lvl_2['term']->term_id) }}"
                                                                    class="text-sm font-medium uppercase"
                                                                >
                                                                    - {!! $row_lvl_2['term']->name !!}
                                                                </a>
                                                                @if (! empty($row_lvl_2['children']) || ! empty($row_lvl_2['posts']))
                                                                    <div
                                                                        class="cursor-pointer px-2 text-sm"
                                                                        x-on:click="show = ! show"
                                                                        x-html="show ? '-' : '+'"
                                                                    ></div>
                                                                @endif
                                                            </div>
                                                            <ul class="my-4 list-none" x-show="show" x-cloak>
                                                                {{-- posts --}}
                                                                @foreach ($row_lvl_2['posts'] as $project_post)
                                                                    <li class="w-full" x-data="{ show: false }">
                                                                        <a
                                                                            href="{{ get_permalink($project_post) }}"
                                                                            class="text-xs font-medium uppercase"
                                                                        >
                                                                            - {!! $project_post->post_title !!}
                                                                        </a>
                                                                    </li>
                                                                @endforeach

                                                                {{-- subccats --}}
                                                                @foreach ($row_lvl_2['children'] as $row_lvl_3)
                                                                    <div
                                                                        class="flex w-full justify-between text-gray-500"
                                                                    >
                                                                        <a
                                                                            href="{{ get_term_link($row_lvl_2['term']->term_id) }}"
                                                                            class="text-sm font-medium uppercase"
                                                                        >
                                                                            -- {!! $row_lvl_2['term']->name !!}
                                                                        </a>
                                                                    </div>
                                                                @endforeach
                                                            </ul>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- we show locations as tree above. Dont need location filters --}}
                {{--
                    <template x-for="facete in facetes">
                    
                    <div>
                    <template x-if="facete.terms && facete.terms.length">
                    
                    <div>
                    <template x-if="facete.type == 'taxonomy' && facete.template == 'list' && facete.taxonomy != 'project_cat'">
                    <div class="facete-wrapper">
                    <h2 :id="facete.taxonomy + '-heading'" class="facete-heading">
                    <button type="button"
                    class="flex items-center justify-between w-full px-4 py-3 text-sm font-medium text-left text-gray-900 border-b border-gray-350"
                    aria-expanded="false">
                    <span x-text="facete.label"></span>
                    <svg aria-hidden="false" class="w-5 h-5 shrink-0 rotate-180" fill="currentColor"
                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z">
                    </path>
                    </svg>
                    </button>
                    </h2>
                    <div :aria-labelledby="facete.taxonomy + '-heading'" class="facete-body">
                    <template x-if="facete.enable_search">
                    <div class="relative mx-4">
                    <div
                    class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg aria-hidden="false" class="w-5 h-5 text-black"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    </div>
                    <input type="search" :id="facete.taxonomy + '-search'"
                    class="block w-full !p-2 !pl-10 !h-auto text-sm text-body-main border border-gray-300 rounded bg-gray-50 focus:ring-gray-600 focus:border-gray-600"
                    :placeholder="'Search for '+facete.label">
                    </div>
                    </template>
                    <div class="px-2 py-5 font-light border-b border-gray-350">
                    <div class="px-2 space-y-2 pb-2 xl:max-h-[500px] xl:overflow-auto custom-scrollbar">
                    <template x-for="term in facete.terms">
                    <div class="flex items-center">
                    <input :id="facete.taxonomy+'-'+term.term_id" x-on:change.debounce="calculateTaxonomy(facete.taxonomy,term)"
                    :checked="activeFilters.hasOwnProperty('taxonomies') && activeFilters.taxonomies.hasOwnProperty(facete.taxonomy) && activeFilters.taxonomies[facete.taxonomy].includes(term)"
                    type="checkbox"
                    value=""
                    class="w-5 h-5 bg-gray-100 border-gray-300 rounded-full text-body-main focus:ring-none" />
                    <label :for="facete.taxonomy+'-'+term.term_id"
                    class="ml-2 text-sm font-medium text-gray-500" x-text="term.name">
                    </label>
                    </div>
                    </template>
                    </div>
                    </div>
                    </div>
                    </div>
                    </template>
                    </div>
                    
                    </template>
                    
                    </div>
                    
                    </template>
                --}}
            </div>
        </div>
    </div>
</div>
<div x-show="filtersLoading">
    <div id="alpine-posts-facetes-loader" class="mt-4">
        @for ($i = 0; $i < 10; $i++)
            <div class="px-2">
                <div class="mb-4 h-14 animate-pulse rounded bg-slate-200"></div>
            </div>
        @endfor
    </div>
</div>
