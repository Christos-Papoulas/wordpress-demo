<section class="pb-12 xl:pb-20 @if(isset($block->classes)){{ $block->classes }}@endif">
	<div class="grid grid-cols-3 gap-1">
		<div class="group w-full col-span-3 xl:col-span-2 bg-primary">
            @if($hero['blocks'][0]['block_type'] == 'img')
                <a href="{{ $hero['blocks'][0]['link']['url'] }}" 
                    class="flex w-full aspect-video xl:aspect-auto xl:h-full bg-cover bg-center bg-no-repeat relative"
                    style="background-image:url('{{ $hero['blocks'][0]['img']['url'] }}');"
                >
                    <div class="z-10 flex items-center absolute left-0 top-0 w-full h-full opacity-0 group-hover:opacity-100 bg-[rgb(0_0_0_/_60%)] transition duration-300"></div>
                    <div class="z-20 !text-black group-hover:!text-white text-xs mix-blend-difference group-hover:!mix-blend-normal invert group-hover:!invert-0 font-bold absolute bottom-0 left-0 p-3 xl:p-4 uppercase">{{ $hero['blocks'][0]['title'] }}</div>
                </a>
            @elseif($hero['blocks'][0]['block_type'] == 'video')
                <div class="flex w-full xl:w-3/5 h-full overflow-hidden relative">
                    <video class="absolute left-0 top-0 w-full h-full object-cover" preload="metadata" playsinline="playsinline" loop="loop" muted="muted" autoplay="">
                        <source src="{{ $hero['blocks'][0]['video'] }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            @endif
		</div>
		<div class="w-full hidden xl:flex xl:flex-col gap-1 col-span-3 xl:col-span-1">

            @foreach ($hero['blocks'] as $block)
                @if($loop->index < 1 || $loop->index > 3)
                    @continue
                @endif
                <a class="bg-primary flex flex-col-reverse xl:flex-row flex-nowrap w-full aspect-video overflow-hidden" href="{{ $block['link']['url'] }}">
                    <div class="w-full xl:w-2/5 flex items-center xl:relative xl:top-8 text-white text-[10px]/3 md:text-xs/4 font-bold p-3 xl:p-4 uppercase">
                            {{ $block['title'] }}
                    </div>
                    @if($block['block_type'] == 'img')
                        <div class="flex w-full xl:w-3/5 h-full overflow-hidden">
                            <img class="object-cover w-full h-full" alt="{{ $block['title'] }}'}}" src="{{ $block['img']['url'] }}">
                        </div>
                    @elseif($block['block_type'] == 'video')
                        <div class="flex w-full xl:w-3/5 h-full overflow-hidden relative">
                            <video class="absolute left-0 top-0 w-full h-full object-cover" preload="metadata" playsinline="playsinline" loop="loop" muted="muted" autoplay="">
                                <source src="{{ $block['video'] }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    @endif
                </a>
            @endforeach

		</div>
	</div>
	<div class="w-full grid grid-cols-2 md:grid-cols-3 gap-1 mt-1">

        @foreach ($hero['blocks'] as $block)
            @if($loop->index < 1)
                @continue
            @endif
            <a href="{{ $block['link']['url'] }}" class="@if($loop->index < 4) xl:hidden @endif flex flex-col">
                @if($block['block_type'] == 'img')
                <div class="flex w-full aspect-video overflow-hidden">
                    <img class="object-cover w-full h-full" alt="{{ $block['title'] }}" src="{{ $block['img']['url'] }}">
                </div>
                @elseif($block['block_type'] == 'video')
                    <div class="flex w-full aspect-video overflow-hidden relative">
                        <video class="absolute left-0 top-0 w-full h-full object-cover" preload="metadata" playsinline="playsinline" loop="loop" muted="muted" autoplay="">
                            <source src="{{ $block['video'] }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    </div>
                @endif
                <div class="bg-primary flex items-center text-white text-[10px]/3 md:text-xs/4 font-bold p-3 xl:p-4 uppercase">
                    {{ $block['title'] }}
                </div>
            </a>
        @endforeach

	</div>
</section>
