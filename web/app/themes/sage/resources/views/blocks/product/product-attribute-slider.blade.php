<section class="slider-products-attributes @if(isset($block->classes)){{ $block->classes }}@endif pb-12 xl:pb-20">
    <div class="ht-container w-full">
        @if (! empty($slider['title']))
            <div class="text-body text-2xl leading-9 font-semibold" data-aos="fade-right" data-aos-duration="1000">
                {!! strip_tags($slider['title'], ['<br>', '<div>', '<strong>', '<p>', '<span>', '<em>']) !!}
            </div>
        @endif

        @if (is_array($slider['attributes']) && ! empty($slider['attributes']))
            <swiper-container init="false">
                @foreach ($slider['attributes'] as $term_id)
                    <swiper-slide class="hidden h-auto">
                        <x-brand-card :termId="$term_id" />
                    </swiper-slide>
                @endforeach
            </swiper-container>
        @endif
    </div>
</section>
