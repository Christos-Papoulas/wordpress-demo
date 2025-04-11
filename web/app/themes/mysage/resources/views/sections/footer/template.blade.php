<footer class="w-full bg-{{ $footer_bg }} text-{{ $footer_color }}">
    <section class="w-full {{ 'text-'.$footer_color }} {{ 'bg-'.$footer_bg }}">
        <div class="ht-container-no-max-width">
            
            <div class="py-3.5 flex gap-20 md:items-center text-[10px]/3 md:text-xs/4 border-b border-black">
            
                <div class="flex md:items-center bg-{{ $footer_bg }} {{ 'text-'.$footer_color }}">
                    <div class="font-bold">{{ __('LANGUAGE:','sage') }}</div>
                    @include('sections.header.lang-switcher')
                </div>
                <div class="flex justify-between items-center">
                    <div class="flex flex-wrap gap-y-3 items-center overflow-hidden">
                        @if(!empty($contact->socials))
                            <div class="font-bold mr-2">{{ __('FOLLOW US:','sage') }}</div>
                            <ul class="ht-list-none flex flex-wrap gap-x-5 items-center">
                                @foreach ($contact->socials as $item)
                                    <li class="w-4.5 h-auto mb-0">
                                        <a href="{{ $item['url'] }}" title="{{ $item['platform'] }}" rel="noopener noreferrer" target="_blank" class="flex items-center transition {{ 'text-'.$footer_color }} {{ 'hover:text-'.$footer_hover_color }}">
                                            @svg('resources/images/' . $item['inline_svg'], 'w-4.5 h-auto text-' . $footer_color)
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

            </div>

            <div class="flex flex-wrap w-full py-8 sm:py-10 md:mb-8 gap-y-4 md:gap-y-10 xl:gap-y-20">

                <div class="w-full flex flex-col md:flex-row md:flex-wrap gap-y-4 gap-x-10 md:gap-y-10 xl:gap-y-20">
                    
                    @if(is_active_sidebar('sidebar-footer-1'))
                        <div class="flex flex-col gap-5 text-sm {{ 'text-'.$footer_color }}">
                            @php(dynamic_sidebar('sidebar-footer-1'))
                        </div>
                    @endif

                    @if(is_active_sidebar('sidebar-footer-2'))
                        <div class="flex flex-col gap-5 text-sm {{ 'text-'.$footer_color }}">
                            @php(dynamic_sidebar('sidebar-footer-2'))
                        </div>
                    @endif

                    @if(is_active_sidebar('sidebar-footer-3'))
                        <div class="flex flex-col gap-5 text-sm {{ 'text-'.$footer_color }}">
                            @php(dynamic_sidebar('sidebar-footer-3'))
                        </div>
                    @endif

                    @if(is_active_sidebar('sidebar-footer-4'))
                        <div class="flex flex-col gap-5 text-sm {{ 'text-'.$footer_color }}">
                            @php(dynamic_sidebar('sidebar-footer-4'))
                        </div>
                    @endif

                    @if(is_active_sidebar('sidebar-footer-5'))
                        <div class="flex flex-col gap-5 text-sm xl:pr-20 {{ 'text-'.$footer_color }}">
                            @php(dynamic_sidebar('sidebar-footer-5'))
                        </div>
                    @endif

                </div>

            </div>

        </div>
    </section>
{{-- bottombar --}}
@include('sections.footer.bottombar')
</footer>
    