<template x-for="(activeFilterValue, activeFilterKey) in activeFilters">
    <div class="flex flex-wrap py-5 lg:py-0">
        <template x-if="activeFilterKey == 'taxonomies'">
            <template x-for="(taxonomy, taxonomyKey) in activeFilters[activeFilterKey]">
                <template x-for="term in taxonomy">
                    <div
                        x-on:click="removeActiveFilterTaxonomyTerm(taxonomyKey, term)"
                        class="group border-gray-350 hover:border-heading mx-1.5 my-1.5 flex flex-shrink-0 cursor-pointer items-center border bg-white px-4 py-3 text-sm text-black uppercase transition duration-200 ease-in-out hover:border-gray-600"
                    >
                        <span x-text="term.name"></span>
                        <svg
                            stroke="currentColor"
                            fill="currentColor"
                            stroke-width="0"
                            viewBox="0 0 512 512"
                            class="ml-2 flex-shrink-0 text-sm transition duration-200 ease-in-out group-hover:text-black"
                            height="1em"
                            width="1em"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M289.94 256l95-95A24 24 0 00351 127l-95 95-95-95a24 24 0 00-34 34l95 95-95 95a24 24 0 1034 34l95-95 95 95a24 24 0 0034-34z"
                            ></path>
                        </svg>
                    </div>
                </template>
            </template>
        </template>
    </div>
</template>
