@if(!empty($slider['products']))
<section class="slider-products pb-12 xl:pb-20 @if(isset($block->classes)){{ $block->classes }}@endif">
    <div class="w-full ht-container-no-max-width">

        @if(!empty($slider['title']))
            <div class="text-xs font-bold lg:text-3xl lg:font-normal mb-5 uppercase paragraph-no-margin" data-aos="fade-right" data-aos-duration="1000">
            {!! strip_tags($slider['title'], ['<br>','<div>','<strong>','<p>','<span>','<em>']) !!}          
            </div>
        @endif

        <swiper-container init="false">
            @foreach ($slider['products'] as $key => $product)
                <swiper-slide class="h-auto hidden" data-aos="fade-left" data-aos-delay="{{ 0 + (($key+1)*50) }}" data-aos-duration="1000">
                    <x-product-card :product="$product" />
                </swiper-slide>
            @endforeach
        </swiper-container>

        <div class="flex justify-end gap-1 md:gap-3 md:px-5">
            <button type="button" class="flex swiper-btn-prev group text-primary hover:text-secondary shrink-0 grow-0 w-4 h-4 md:w-9 md:h-9 justify-center items-center">
            <svg width="36" height="37" viewBox="0 0 36 37" fill="none">
                <circle cx="18" cy="18.5" r="18" fill="currentColor"/>
                <g>
                <path d="M20.6719 11.5L12.9979 18.5793L20.6719 25.7729" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                </g>
            </svg>          
            </button>
            <button type="button" class="flex swiper-btn-next group text-primary hover:text-secondary w-4 h-4 md:w-9 md:h-9 shrink-0 grow-0 justify-center items-center">
                <svg width="36" height="37" viewBox="0 0 36 37" fill="none">
                    <circle cx="18" cy="18.5" r="18" fill="currentColor"/>
                    <g>
                    <path d="M15 11.5L22.674 18.5793L15 25.7729" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                    </g>
                </svg>          
            </button>
        </div>
        
    </div>
</section>
@endif
