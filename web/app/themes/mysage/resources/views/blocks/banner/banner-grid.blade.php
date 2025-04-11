<section class="pb-12 xl:pb-20 @if(isset($block->classes)){{ $block->classes }}@endif">
    <div class="w-full grid grid-cols-1 @if(count($grid['banners']) == 3) ht-container-large lg:grid-cols-3 @elseif(count($grid['banners']) == 2) ht-container-large lg:grid-cols-2 @else ht-container-no-max-width @endif gap-1.5">
        @foreach($grid['banners'] as $banner)
             <a href="{{ $banner['link']['url'] }}" title="{{ $banner['link']['title'] }}" target="{{ $banner['link']['target'] }}" class="aspect-[800/1000] overflow-hidden flex w-full shrink-0 group relative @if(count($grid['banners']) == 1)) lg:max-w-[35%] mx-auto @endif">
                @if($banner['banner_type'] == 'video')
                    <video class="absolute left-0 top-0 w-full h-full object-cover" preload="metadata" playsinline="playsinline" loop="loop" muted="muted" autoplay>
                        <source src="{{ $banner['video'] }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                @else
                    <img src="{{ $banner['img']['url'] }}" alt="{{ $banner['img']['title'] }}" class="object-cover w-full">
                @endif

                <div class="z-10 flex items-center absolute left-0 top-0 w-full h-full opacity-0 group-hover:opacity-100 bg-[rgb(0_0_0_/_60%)] transition duration-300"></div>
                <div class="z-20 !text-black group-hover:!text-white text-xs mix-blend-difference group-hover:!mix-blend-normal invert group-hover:!invert-0 font-bold absolute bottom-0 left-0 p-3 xl:p-4 uppercase">{{ $banner['link']['title'] }}</div>
            </a>
        @endforeach
    </div>
</section>
