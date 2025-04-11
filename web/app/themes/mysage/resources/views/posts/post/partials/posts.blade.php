<template x-if="! postsLoading && posts.length > 0">
    <div id="alpine-posts-container" class="flex w-full flex-col gap-1.5">
        <template x-for="(chunk, chunkIndex) in chunkedPosts(posts, 10)" :key="chunkIndex">
            <div class="grid w-full grid-cols-1 gap-1.5 lg:grid-cols-6 lg:grid-rows-3">
                <template x-for="(post, index) in chunk" :key="post.id">
                    <a
                        :href="post.url"
                        :title="post.name"
                        :class="[ chunkIndex % 2 === 0 && index == 3 && 'lg:col-span-3 lg:row-span-3', (chunkIndex % 2 !== 0 || chunkIndex % 2 === 1) && index == 0 && 'lg:col-span-3 lg:row-span-3']"
                        class="group relative block aspect-square overflow-hidden"
                    >
                        <div class="flex flex-col">
                            <time
                                class="dt-published hidden"
                                :datetime="post.datePublished"
                                x-html="post.date"
                            ></time>
                            <div class="flex aspect-square w-full shrink-0 overflow-hidden">
                                <img
                                    :src="post.image"
                                    :alt="`${post.name} featured image`"
                                    class="w-full object-cover"
                                />
                            </div>

                            <div
                                class="absolute bottom-0 left-0 z-10 flex w-full items-center p-3 text-sm text-black uppercase mix-blend-difference invert transition duration-300 md:text-xs md:font-bold xl:top-0 xl:bottom-auto xl:h-full xl:bg-[rgb(0_0_0_/_60%)] xl:text-white xl:opacity-0 xl:mix-blend-normal xl:invert-0 xl:group-hover:opacity-100"
                            >
                                <div class="w-2/3" x-html="post.name"></div>
                            </div>
                        </div>
                    </a>
                </template>
            </div>
        </template>
    </div>
</template>

<div x-html="schema"></div>

<template x-if="! postsLoading && ! posts.length > 0">
    <div id="alpine-no-posts" class="flex items-center justify-center text-xl">
        {{ __('No Posts Found', 'sage') }}
    </div>
</template>

<template x-if="postsLoading">
    <div id="alpine-posts-loader" class="grid w-full grid-cols-1 gap-1.5 lg:grid-cols-6 lg:grid-rows-3">
        @for ($i = 0; $i < config('wordpress.' . $postType . '.posts-per-page',12); $i++)
            <div
                class="@if($i == 3) lg:col-span-3 lg:row-span-3 @endif shrink-0 animate-pulse overflow-hidden bg-slate-200"
            >
                <div class="flex aspect-video"></div>
                <div class="flex h-full flex-col justify-between p-5">
                    <div class="">
                        <div class="invisible text-xs">Placeholder</div>
                        <div class="py-5 sm:py-7">
                            <h3 class="text-body invisible mb-6 text-[22px] font-bold">Placeholder</h3>
                        </div>
                    </div>
                    <a href="#" class="btn-md btn-solid-primary invisible">Read more</a>
                </div>
            </div>
        @endfor
    </div>
</template>
