<nav x-data="{mouseoverTriggered:false, itemHovered: null}" aria-label="{{ wp_get_nav_menu_name('primary_navigation') }}" 
x-on:mouseover="
    if(!mouseoverTriggered){
        document.dispatchEvent(new CustomEvent('closeAllModals', {detail: {}}));
        mouseoverTriggered = true;
        document.body.classList.add('body-no-scroll');
        document.getElementById('backdrop').classList.remove('hidden');
    }
"
@mouseout="
    if(mouseoverTriggered){
        mouseoverTriggered = false;
        document.body.classList.remove('body-no-scroll');
        document.getElementById('backdrop').classList.add('hidden');
    }
"
class="w-auto h-full {{ 'text-'.$header_color }} {{ 'bg-'.$header_bg }} hidden xl:block static transition-none opacity-100 transform-none">
    <div class="flex gap-12 h-full">
        <div class="relative flex flex-wrap justify-between items-center h-full">
            <div class="h-full flex">
                @if(!empty($megamenu))
                @foreach($megamenu as $key => $first_lvl_item) 
                    {{-- nav group --}}
                    <div class="group {{ 'text-'.$header_color }} {{ 'hover:text-'.$header_hover_color }} pr-5">
                        <div class="flex gap-1.5 w-full text-xs h-full">
                            @if(!empty($first_lvl_item->children))
                                <button type="button" class="flex items-center font-bold uppercase text-xs whitespace-nowrap">
                                    {!! __($first_lvl_item->label,'sage') !!}
                                </button>
                            @else
                                <a href="{{ $first_lvl_item->url }}" target="{!! $first_lvl_item->target !!}" title="{!! $first_lvl_item->label !!}" 
                                    class="{{ 'text-'.$header_color }} {{ 'hover:!text-'.$header_hover_color }} flex items-center font-bold uppercase text-xs whitespace-nowrap">
                                    {!! __($first_lvl_item->label,'sage') !!}
                                </a>
                            @endif
                        </div>
                        @if(!empty($first_lvl_item->children))
                            <div class="flex flex-wrap {{ 'bg-'.$header_bg }} z-[1020] absolute -left-3 xl:-left-4 top-full hidden group-hover:flex hover:flex w-screen  border-t border-black" style="min-height:300px;">
                                <ul class="ht-list-none w-3/4 flex flex-wrap gap-x-16 gap-y-16 {{ 'bg-'.$header_bg }} px-3 xl:px-4 py-6 h-full">
                                    @foreach ($first_lvl_item->children as $second_lvl_item)
                                        <li class="mb-0 flex flex-col gap-3">
                                            <a href="{{ $second_lvl_item->url }}" target="{!! $second_lvl_item->target !!}" title="{!! $second_lvl_item->label !!}" class="text-xs font-semibold uppercase transition {{ 'text-'.$header_color }} {{ 'hover:text-'.$header_hover_color }}">{!! $second_lvl_item->label !!}</a>
                                            @if(!empty($second_lvl_item->children))
                                                <ul class="ht-list-none flex flex-col gap-1">
                                                    @foreach ($second_lvl_item->children as $third_lvl_item)
                                                    <li x-on:mouseover="itemHovered = {{ $third_lvl_item->id }}">
                                                        <a href="{{ $third_lvl_item->url }}" target="{!! $third_lvl_item->target !!}" title="{!! $third_lvl_item->label !!}" class="text-xs font-medium transition {{ 'text-'.$header_color }} {{ 'hover:text-'.$header_hover_color }}">{!! $third_lvl_item->label !!}</a>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="relative flex overflow-hidden w-1/4">

                                    <div class="flex overflow-hidden absolute inset-0">
                                        <img src="{{ Vite::asset('resources/images/fallbacks/navigation-fallback.jpg') }}" alt="navigation-fallback-image" class="object-cover w-full"/>
                                    </div>
                                    
                                    @if(!empty($first_lvl_item->children))
                                    @foreach ($first_lvl_item->children as $second_lvl_item)
                                    @if(!empty($second_lvl_item->children))
                                    @foreach ($second_lvl_item->children as $third_lvl_item)
                                        @php 
                                            $f_img = ht_get_field('img', $third_lvl_item->id);
                                        @endphp
                                        @if(!empty($f_img))
                                            <div class="flex overflow-hidden absolute inset-0">
                                                <img x-cloak x-show="itemHovered == {{ $third_lvl_item->id }}" src="{{ $f_img['url'] }}" alt="{{ $third_lvl_item->label }}" class="object-cover w-full"/>
                                            </div>
                                        @endif
                                    @endforeach
                                    @endif
                                    @endforeach
                                    @endif
                                    
                                    
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>
</nav>
