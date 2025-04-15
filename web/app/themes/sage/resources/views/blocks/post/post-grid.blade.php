<section class="@if(isset($block->classes)){{ $block->classes }}@endif pb-12 xl:pb-20">
    <div class="ht-container-no-max-width grid w-full grid-cols-1 gap-1.5 lg:grid-cols-2">
        <div class="grid aspect-square grid-cols-3 gap-1.5 overflow-hidden">
            <a
                href="{{ get_permalink(get_option('page_for_posts')) }}"
                class="group hover:text-secondary relative col-span-2 flex items-center text-7xl text-black transition md:text-[120px] lg:text-[100px] xl:text-[120px]"
            >
                <span class="transition duration-300 xl:group-hover:opacity-0">{{ __('NEWS', 'sage') }}</span>
                <div
                    class="3xl:text-[120px] absolute bottom-0 left-0 z-10 flex w-full items-center p-3 text-sm text-black uppercase mix-blend-difference invert transition duration-300 md:text-xs md:font-bold xl:top-0 xl:bottom-auto xl:h-full xl:justify-center xl:bg-[rgb(0_0_0_/_60%)] xl:text-[50px] xl:font-normal xl:text-white xl:opacity-0 xl:mix-blend-normal xl:invert-0 xl:group-hover:opacity-100 2xl:text-[70px]"
                >
                    {{ __('READ MORE', 'sage') }}
                </div>
            </a>
            @foreach ($grid['posts'] as $post)
                @php
                    $img = get_the_post_thumbnail_url($post->ID, 'full');
                    if (! $img || $img == null) {
                        $img = wc_placeholder_img_src('full');
                    }
                    $permalink = get_permalink($post->ID);
                @endphp

                <a
                    href="{{ $permalink }}"
                    title="{{ $post->post_title }}"
                    class="group relative block aspect-square overflow-hidden"
                >
                    <div class="flex flex-col">
                        <time class="dt-published hidden" datetime="{{ get_post_time('c', true, $post->ID) }}">
                            {{ get_the_date('', $post->ID) }}
                        </time>
                        <div class="flex aspect-square w-full shrink-0 overflow-hidden">
                            <img
                                src="{{ $img }}"
                                alt="{{ $post->post_title.' featured image' }}"
                                class="w-full object-cover"
                            />
                        </div>

                        <div
                            class="absolute bottom-0 left-0 z-10 flex w-full items-center p-3 text-sm text-black uppercase mix-blend-difference invert transition duration-300 md:text-xs md:font-bold xl:top-0 xl:bottom-auto xl:h-full xl:bg-[rgb(0_0_0_/_60%)] xl:text-white xl:opacity-0 xl:mix-blend-normal xl:invert-0 xl:group-hover:opacity-100"
                        >
                            <div class="w-2/3">{!! $post->post_title !!}</div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        <a
            href="{{ $grid['col_2']['link']['url'] }}"
            title="{{ $grid['col_2']['link']['title'] }}"
            target="{{ $grid['col_2']['link']['target'] }}"
            class="group relative block aspect-square overflow-hidden"
        >
            <div class="flex h-full w-full overflow-hidden">
                <img
                    src="{{ $grid['col_2']['img']['url'] }}"
                    alt="{{ $grid['col_2']['img']['title'] }}"
                    class="w-full object-cover"
                />
            </div>
            <div
                class="3xl:text-[120px] absolute bottom-0 left-0 z-10 flex w-full items-center p-3 text-sm text-black uppercase mix-blend-difference invert transition duration-300 md:text-xs md:font-bold xl:top-0 xl:bottom-auto xl:h-full xl:justify-center xl:bg-[rgb(0_0_0_/_60%)] xl:text-[80px] xl:font-normal xl:text-white xl:opacity-0 xl:mix-blend-normal xl:invert-0 xl:group-hover:opacity-100 2xl:text-[100px]"
            >
                {{ __('OUR STORES', 'sage') }}
            </div>
        </a>
    </div>
</section>
