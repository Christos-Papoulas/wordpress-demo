<template x-if="! postsLoading && posts.length > 0">
    <div id="alpine-posts-container" class="grid grid-cols-1 gap-x-5 gap-y-5 md:grid-cols-2 lg:grid-cols-3">
        <template x-for="post in posts" :key="post.id">
            <a
                :href="post.url"
                class="group relative flex aspect-[684/382] w-full transition-all duration-200 ease-linear"
            >
                <img :src="post.image" :alt="post.name" class="w-full object-cover" />
                <div
                    class="absolute top-8 left-8 h-[calc(100%_-_64px)] w-[calc(100%_-_64px)] bg-black opacity-0 bg-blend-multiply transition-opacity duration-400 ease-linear group-hover:opacity-50"
                ></div>
                <div
                    class="absolute top-8 left-8 flex h-[calc(100%_-_64px)] h-full w-[calc(100%_-_64px)] items-center justify-center opacity-0 transition-opacity duration-400 ease-linear group-hover:opacity-100"
                >
                    <h2
                        x-text="post.name"
                        class="mb-0 p-4 text-center text-base leading-[140%] text-white lg:text-[30px]"
                    ></h2>
                </div>
            </a>
        </template>
    </div>
</template>
<template x-if="! postsLoading && ! posts.length > 0">
    <div id="alpine-no-posts" class="flex items-center justify-center text-xl">No Posts Found</div>
</template>
<template x-if="postsLoading">
    <div id="alpine-posts-loader" class="grid grid-cols-1 gap-x-5 gap-y-5 md:grid-cols-2 lg:grid-cols-3">
        @for ($i = 0; $i < config('wordpress.' . $postType . '.posts-per-page',12); $i++)
            <div class="px-2 py-4">
                <div class="mb-4 aspect-[684/382] animate-pulse bg-slate-200"></div>
            </div>
        @endfor
    </div>
</template>
