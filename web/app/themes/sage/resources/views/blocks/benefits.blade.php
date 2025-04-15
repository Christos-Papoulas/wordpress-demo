<section class="pb-12 xl:pb-20 @if(isset($block->classes)){{ $block->classes }}@endif">
    <div class="ht-container-large">
        <div class="flex flex-wrap gap-10 justify-center gap-y-5 items-baseline">
            @foreach($benefits['benefits'] as $key => $benefit)
                <div class="flex flex-col items-center justify-center gap-3 w-full lg:max-w-[300px]">
                    <img width="70" height="48" src="{{ $benefit['img']['url'] }}" alt="{{ $benefit['img']['title'] }}"/>
                    @if(!empty($benefit['link']['url']))
                        <a href="{{ $benefit['link']['url'] }}" title="{{ $benefit['link']['title'] }}" target="{{ $benefit['link']['target'] }}" 
                            class="a-no-underline !text-body flex justify-center @if($key !== array_key_last($benefits['benefits'])) border-b @endif lg:border-none lg:items-center flex-wrap mx-auto flex-col pb-5 lg:pb-0">
                        <div class="mx-auto text-center text-xl px-5">{{ $benefit['title'] }}</div>
                        <span class="mx-auto text-center text-base px-5">{{ $benefit['content'] }}</span>
                        </a>
                    @else
                        <div
                            class="a-no-underline !text-body flex justify-center @if($key !== array_key_last($benefits['benefits'])) border-b @endif lg:border-none lg:items-center flex-wrap mx-auto flex-col pb-5 lg:pb-0">
                            <div class="mx-auto text-center text-xl px-5">{{ $benefit['title'] }}</div>
                            <span class="mx-auto text-center text-base px-5">{{ $benefit['content'] }}</span>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
