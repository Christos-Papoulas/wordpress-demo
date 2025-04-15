<template x-if="! postsLoading && posts.length > 0">
    <div id="alpine-posts-container" class="flex min-w-96 flex-col overflow-y-auto lg:h-[600px]">
        <template x-for="post in posts" :key="post.id">
            <div class="mb-4 flex flex-col text-xs">
                <p class="mb-2 font-semibold" x-html="post.name"></p>
                <div x-html="post.content" class="paragraph-no-margin"></div>
                <template
                    x-if="
                        post.store_custom_fields_google_map_field?.place_id !== undefined &&
                            post.store_custom_fields_google_map_field?.place_id !== null
                    "
                >
                    <a
                        :href="'https://www.google.com/maps/place/?q=place_id:'+post.store_custom_fields_google_map_field.place_id"
                        target="_blank"
                        :title="post.name"
                    >
                        {{ __('See in map', 'sage') }}
                    </a>
                </template>
            </div>
        </template>
    </div>
</template>

<div x-html="schema"></div>

<template x-if="! postsLoading && ! posts.length > 0">
    <div id="alpine-no-posts" class="flex items-center justify-center text-xl">
        {{ __('No posts found', 'sage') }}
    </div>
</template>
<template x-if="postsLoading">
    <div id="alpine-posts-loader" class="flex min-w-96 flex-col overflow-y-auto lg:h-[600px]">
        @for ($i = 0; $i < config('wordpress.' . $postType . '.posts-per-page',12); $i++)
            <div class="flex flex-col gap-y-2 bg-slate-200 text-xs">
                <p class="invisible mb-0 bg-slate-200 font-semibold">ΝΕΑ ΦΙΛΑΔΕΛΦΕΙΑ</p>
                <p class="invisible mb-0 bg-slate-200">Placeholder</p>
                <p class="invisible mb-0 bg-slate-200">Placeholder</p>
                <p class="invisible mb-0 bg-slate-200">Placeholder</p>
                <p class="invisible mb-0 bg-slate-200">Placeholder</p>
            </div>
        @endfor
    </div>
</template>
