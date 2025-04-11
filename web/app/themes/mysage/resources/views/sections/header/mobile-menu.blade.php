<nav aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}" 
x-cloak 
x-show="openMobileMenu" 
class="fixed top-[104px] left-0 w-full h-[calc(100dvh_-_104px)] z-[1020] {{ 'text-'.$header_color }} {{ 'bg-'.$header_bg }} overflow-auto border-t border-black" 
x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-x-full" x-transition:enter-end="translate-x-0 opacity-100 " 
x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 -translate-x-full">
    <div class="py-6 px-3 relative">
        <button type="button" 
        x-on:click="openMobileMenu = false"
        class="absolute right-4 md:right-12 top-4 flex items-center"
        >
            <span class="text-xs text-body mr-2">{{ __('CLOSE','sage') }}</span>
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" x="0" y="0" width="12" height="12" viewBox="0 0 15.74 15.74" xml:space="preserve">
            <path fill="none" stroke="currentColor" stroke-miterlimit="10" d="M.35.35l15.03 15.03M15.38.35L.35 15.38"></path>
            </svg>       
        </button>
        <div class="grid grid-cols-1 gap-12 justify-center">
            <div class="relative flex flex-col flex-wrap justify-between">
                <div class="overflow-y-auto overflow-x-hidden h-full pb-16 flex flex-col gap-3">
                    @if(!empty($megamenu))
                    @foreach($megamenu as $key => $col) 
                        {{-- nav group --}}
                        <div class="">
                            <div class="flex gap-3 w-full text-xs">
                                @if(!empty($col->children))
                                    <button 
                                    x-on:click="
                                        if(firstLvlOpen == {{ $key }}){
                                            firstLvlOpen = null;
                                        }else{
                                            firstLvlOpen = {{ $key }};
                                        }"
                                    type="button" class="flex items-center text-xs whitespace-nowrap">
                                        {!! __($col->label,'sage') !!}
                                        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="ml-2 w-4 pr-1 rotate-180"><path d="M233.4 105.4c12.5-12.5 32.8-12.5 45.3 0l192 192c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L256 173.3 86.6 342.6c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3l192-192z"></path></svg>
                                    </button>
                                @else
                                    <a href="{{ $col->url }}" target="{!! $col->target !!}" title="{!! $col->label !!}" class="text-xs transition whitespace-nowrap {{ 'text-'.$header_color }} {{ 'hover:text-'.$header_hover_color }}">{!! __($col->label,'sage') !!}</a>
                                @endif
                            </div>
                            @if(!empty($col->children))
                                <div class="w-full flex justify-center">
                                    <ul x-cloak x-show="firstLvlOpen == {{ $key }}" 
                                    class="ml-4 my-4 relative list-none  text-xs flex flex-col gap-3 z-[1020] w-full {{ 'bg-'.$header_bg }}">
                                        @foreach ($col->children as $second_lvl_key => $second_lvl_item)
                                            <li class="flex flex-col justify-between gap-12">
                                                @if(!empty($second_lvl_item->children))
                                                    <button 
                                                    x-on:click="
                                                        if(secondLvlOpen == {{ $second_lvl_key }}){
                                                            secondLvlOpen = null;
                                                        }else{
                                                            secondLvlOpen = {{ $second_lvl_key }};
                                                        }"
                                                    type="button" class="flex items-center text-xs whitespace-nowrap">
                                                        {!! __($second_lvl_item->label,'sage') !!}
                                                        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="ml-2 w-4 pr-1 rotate-180"><path d="M233.4 105.4c12.5-12.5 32.8-12.5 45.3 0l192 192c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L256 173.3 86.6 342.6c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3l192-192z"></path></svg>
                                                    </button>
                                                @else
                                                    <a href="{{ $second_lvl_item->url }}" target="{!! $second_lvl_item->target !!}" title="{!! $second_lvl_item->label !!}" class="text-xs transition whitespace-nowrap {{ 'text-'.$header_color }} {{ 'hover:text-'.$header_hover_color }}">{!! __($second_lvl_item->label,'sage') !!}</a>
                                                @endif
                                            </li>
                                            @if(!empty($col->children))
                                                <ul x-cloak x-show="secondLvlOpen == {{ $second_lvl_key }}" 
                                                    class="ml-4 relative list-none text-xs flex flex-col gap-3 z-[1020] w-full {{ 'bg-'.$header_bg }}">
                                                    @foreach ($second_lvl_item->children as $third_lvl_item)
                                                        <li class="flex flex-col justify-between gap-12">
                                                            <a href="{{ $third_lvl_item->url }}" target="{!! $third_lvl_item->target !!}" title="{!! $third_lvl_item->label !!}" class="text-xs transition whitespace-nowrap {{ 'text-'.$header_color }} {{ 'hover:text-'.$header_hover_color }}">{!! __($third_lvl_item->label,'sage') !!}</a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    @endforeach
                    @endif

                </div>
            </div>
        </div>
    </div>
</nav>
