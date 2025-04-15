<section @if(!empty($block->block->anchor)) id="{{ $block->block->anchor }}" @endif class="pb-12 xl:pb-20 @if(isset($block->classes)){{ $block->classes }}@endif">
    <div class="ht-container-no-max-width">

        @if(!empty($section['title']))
            <div class="text-xs font-bold lg:text-3xl lg:font-normal mb-5 uppercase paragraph-no-margin" data-aos="fade-right" data-aos-duration="1000">
                {!! strip_tags($section['title'], ['<br>','<div>','<strong>','<p>','<span>','<em>']) !!}          
            </div>
        @endif

        <div class="w-full grid grid-cols-1 lg:grid-cols-2 gap-1.5">

            @if($section['img_as_bg'])
                <div class="h-[200px] lg:h-full flex w-full overflow-hidden bg-center bg-cover bg-no-repeat @if(!$section['img_left']) order-2 @endif"
                    style="background-image:url('{{ $section['img']['url'] }}')" data-aos="fade-in" data-aos-duration="2500">
                </div>
            @else
                <div class="h-[200px] lg:h-full flex w-full overflow-hidden @if(!$section['img_left']) order-2 @endif">
                    <img src="{{ $section['img']['url'] }}" alt="{{ $section['img']['title'] }}" class="object-cover w-full h-full" data-aos="fade-in" data-aos-duration="2500">
                </div>
            @endif
            <div class="lg:h-full flex flex-col justify-center px-8 py-16 xl:px-16 xl:py-24 bg-primary @if(!$section['img_left']) order-1 @endif">
                <div class="text-sm text-white" data-aos="fade-up" data-aos-duration="1000">
                    {!! strip_tags($section['content'], ['<h1>','<h2>','<h3>','<h4>','<br>','<div>','<strong>','<p>','<span>','<em>']) !!}          
                </div>

                @if(!empty($section['button']['link']['url']))
                    <div class="flex">
                        <a href="{{ $section['button']['link']['url'] }}" title="{{ $section['button']['link']['title'] }}" target="{{ $section['button']['link']['target'] }}" class="{{ $section['button']['size'] . ' ' . $section['button']['style'] }}"  data-aos="fade-up" data-aos-delay="200" data-aos-duration="1000">
                            {{ $section['button']['text'] }}
                        </a>
                    </div>
                @endif

            </div>
        </div>

    </div>
</section>
