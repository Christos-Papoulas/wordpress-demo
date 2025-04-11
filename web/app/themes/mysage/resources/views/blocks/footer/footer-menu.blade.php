<div x-data="{isOpen:false}" class="flex flex-col ">
    <button x-on:click="isOpen = !isOpen" type="button" class="uppercase pb-2 sm:pb-0 border-b border-black sm:border-0 sm:cursor-default w-full flex justify-between h-5 mb-3 text-[10px]/3 md:text-xs/4 font-bold">
        @if(!empty($footerMenu['title'])) {{ __($footerMenu['title'],'sage') }} @endif
        <div 
            :class="isOpen && 'transform rotate-180'"
            class="sm:hidden ml-2 flex items-center justify-between"
            aria-expanded="false">
            
            <svg x-show="isOpen"  width="12" height="3" viewBox="0 0 12 3" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M11 1.75391H1" stroke="#707072" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>

            <svg x-show="!isOpen"  width="14" height="15" viewBox="0 0 14 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M6.99935 1.9209V13.5876M1.16602 7.75423H12.8327" stroke="#707072" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>

        </div>
    </button>
    @if(!empty($footerMenu['menu']))
        <ul :class="isOpen && 'is-open'" :style="isOpen && {height: $el.scrollHeight+`px`}" class="h-0 sm:!h-auto sm:!opacity-100 sm:!pointer-events-auto overflow-hidden facete-collapsible ht-list-none ml-0 flex-col mb-5 text-[10px] leading-3 sm:flex gap-y-2 sm:mb-0 flex mt-3 md:mt-0">
            @foreach($footerMenu['menu'] as $item)
                <li class="uppercase !mb-3 md:!mb-1 last:pb-3 a-no-underline"><a href="{{  $item->url }}" target="{{ $item->target }}" title="{{ $item->label }}" class="transition {{ 'text-'.$footer_color }} {{ 'hover:text-'.$footer_hover_color }}">{!! $item->label !!}</a></li>
            @endforeach
        </ul>
        <div></div>
    @endif
</div>
