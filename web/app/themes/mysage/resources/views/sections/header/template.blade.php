{{-- topbar --}}
@include('sections.header.topbar')

<header 
id="app-header"
x-data="{openMobileMenu:false, openSearch:false, firstLvlOpen: null, secondLvlOpen: null}" 
x-init="
document.addEventListener('closeAllModals', (event) => {
    openMobileMenu=false;
    openSearch=false;
});
"
class="overflow-visible z-[1020] sticky top-0"
style="box-shadow: 0 2px 2px -2px rgba(0,0,0,.2);"
>
    <section class="relative w-full {{ 'bg-'.$header_bg }} {{ 'text-'.$header_color }}">
        <div class="ht-container-no-max-width relative">
            <div class="flex justify-between">

                {{-- Navigation --}}
                <div class="flex-1 hidden xl:block">
                    @if (has_nav_menu('primary_navigation'))
                        @include('sections.header.navigation')
                    @endif
                </div>

                <div class="flex items-center xl:justify-center flex-1 py-3 xl:py-4">
                    <div class="">
                        <a class="block shrink-0 w-full transition-all duration-300 overflow-hidden xl:!h-auto" href="{{ home_url('/') }}" title="{{ $siteName }}">
                            <img src="{{ $header_logo }}" alt="{{ $siteName }}" class="h-[26px] hidden xl:block">
                            <img src="{{ $header_sticky_logo }}" alt="{{ $siteName }}" class="h-[26px] w-auto xl:hidden">
                        </a>
                    </div>
                </div>

                <div class="flex items-center justify-end flex-1 py-3 xl:py-4">

                    <div class="flex gap-4 items-center">


                        {{-- search --}} 
                        <button x-data="{}" x-on:click="
                            document.dispatchEvent(new CustomEvent('toggleSearch', {detail: {}}));
                        "
                        type="button" class="hidden xl:block {{ 'text-'.$header_color }}">
                            <svg class="w-4 h-4" viewBox="0 0 15.85 15.85"><defs><style>.search-icon{stroke-miterlimit:10;}</style></defs>
                            <circle fill="none" stroke="currentColor" cx="6.21" cy="6.21" r="5.71"/>
                            <line fill="none" stroke="currentColor" x1="10.27" y1="10.27" x2="15.5" y2="15.5"/>
                            </svg> 
                        </button>

                        
                        {{-- search mobile --}} 
                        <div 
                            x-cloak 
                            x-show="openMobileMenu" 
                            class="h-11 fixed xl:absolute top-[60px] xl:top-full left-0 w-full flex items-center px-3 z-[1020] xl:shadow-md xl:border-t xl:border-t-[#e5e7eb] {{ 'text-'.$header_color }} {{ 'bg-'.$header_bg }}"
                            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-full" x-transition:enter-end="translate-y-0 opacity-100 " 
                            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-full">
                                <button x-on:click="
                                    document.dispatchEvent(new CustomEvent('toggleSearch', {detail: {}}));
                                 "
                                    type="button" class="w-full h-full">
                                    <svg class="w-4 h-4" viewBox="0 0 15.85 15.85">
                                    <circle fill="none" stroke="currentColor" stroke-miterlimit="10" cx="6.21" cy="6.21" r="5.71"/>
                                    <line fill="none" stroke="currentColor" stroke-miterlimit="10" x1="10.27" y1="10.27" x2="15.5" y2="15.5"/>
                                    </svg>   
                                </button>                                         
                        </div>

                        {{-- wishlist --}}
                        <a href="{{ $wishlist_url }}" class="hidden md:block text-xs {{ 'hover:text-'.$header_hover_color }} {{ 'text-'.$header_color }}">
                            <svg class="" xmlns="http://www.w3.org/2000/svg" width="16" height="14.19" viewBox="0 0 16 14.19">
                                <path d="M14.37 1.63a3.88 3.88 0 00-5.48 0L8 2.52l-.89-.89a3.87 3.87 0 00-5.48 5.48l.89.89L8 13.48 13.48 8l.89-.89a3.88 3.88 0 000-5.48z" class="group-hover:fill-current" fill="none" stroke="currentColor" stroke-miterlimit="10"></path>
                            </svg>
                        </a>

                        {{-- lang switcher --}}
                        <div class="header-language-switcher {{ 'bg-'.$header_bg }} text-xs {{ 'text-'.$header_color }} flex items-center">
                            @include('sections.header.lang-switcher')
                        </div>

                        <div class="md:px-4 md:border-x md:border-x-black">
                            {{-- login - register --}}
                            <a href="{{ $my_account_url }}" class="flex items-center text-xs {{ 'hover:text-'.$header_hover_color }} {{ 'text-'.$header_color }}">
                                @if($current_user)
                                    <div class="text-xs mr-2 hidden xl:block">{{ $current_user->display_name }}</div>
                                @else
                                    <span class="text-xs mr-2 hidden xl:block">{{ __('LOGIN/REGISTER','sage') }}</span>
                                @endif
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                                    <path d="M3.9745 18.7713C6.42265 17.3876 9.18784 16.6624 12 16.6667C14.9167 16.6667 17.6548 17.4308 20.0255 18.7713M15.5 9.66667C15.5 10.5949 15.1313 11.4852 14.4749 12.1415C13.8185 12.7979 12.9283 13.1667 12 13.1667C11.0717 13.1667 10.1815 12.7979 9.52513 12.1415C8.86875 11.4852 8.5 10.5949 8.5 9.66667C8.5 8.73841 8.86875 7.84817 9.52513 7.19179C10.1815 6.53541 11.0717 6.16667 12 6.16667C12.9283 6.16667 13.8185 6.53541 14.4749 7.19179C15.1313 7.84817 15.5 8.73841 15.5 9.66667ZM22.5 12C22.5 13.3789 22.2284 14.7443 21.7007 16.0182C21.1731 17.2921 20.3996 18.4496 19.4246 19.4246C18.4496 20.3996 17.2921 21.1731 16.0182 21.7007C14.7443 22.2284 13.3789 22.5 12 22.5C10.6211 22.5 9.25574 22.2284 7.98182 21.7007C6.70791 21.1731 5.55039 20.3996 4.57538 19.4246C3.60036 18.4496 2.82694 17.2921 2.29926 16.0182C1.77159 14.7443 1.5 13.3789 1.5 12C1.5 9.21523 2.60625 6.54451 4.57538 4.57538C6.54451 2.60625 9.21523 1.5 12 1.5C14.7848 1.5 17.4555 2.60625 19.4246 4.57538C21.3938 6.54451 22.5 9.21523 22.5 12Z" stroke="currentColor" stroke-width="1" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </a>
                        </div>

                        {{-- cart button --}}
                        <button 
                            x-data="cartButton"
                            @if(!is_cart())
                                x-on:click="
                                @if(is_checkout())
                                    window.location.href = '{{ wc_get_cart_url() }}';
                                @else
                                    if (window.innerWidth < 1280) {
                                        window.location.href = '{{ wc_get_cart_url() }}';
                                    }else{
                                        document.dispatchEvent(new CustomEvent('toggleMiniCart', {detail: {}}));
                                    }
                                @endif
                                "
                            @endif
                            type="button" class="flex">
                            <svg class="w-4 h-4" viewBox="0 0 16 16"><defs><style>.bag-icon{stroke-miterlimit:10;}</style></defs>
                                <rect fill="none" stroke="currentColor" x="0.5" y="4.71" width="15" height="10.79"/>
                                <path fill="none" stroke="currentColor" d="M8,.5a3,3,0,0,0-3,3v1.2h6V3.51A3,3,0,0,0,8,.5Z"/>
                            </svg>
                            <span class="ml-1 {{ 'text-'.$header_color }} flex justify-center items-center rounded-full text-xs relative top-0.5" x-html="itemsCount > 0 ? itemsCount : ''"></span>
                        </button>
    
                        {{-- toggler --}}
                        <div class="items-center flex xl:hidden">
                            <button 
                                x-on:click="
                                if(!openMobileMenu){
                                    document.dispatchEvent(new CustomEvent('closeAllModals', {detail: {}}));
                                    openMobileMenu = true;
                                }else{
                                    openMobileMenu = false;
                                }
                                "
                                class="flex items-center space-x-2 focus:outline-none">
                                <div class="w-7 h-9 flex items-center justify-center relative">
                                    <span class="transform transition w-full h-0.5 bg-current absolute rounded-full -translate-y-2" :class="openMobileMenu && '!translate-y-0 !rotate-45'"></span>
                                    <span class="transform transition w-full h-0.5 bg-current absolute rounded-full opacity-100" :class="openMobileMenu && '!opacity-0'" ></span>
                                    <span class="transform transition w-full h-0.5 bg-current absolute rounded-full translate-y-2" :class="openMobileMenu && '!translate-y-0 !-rotate-45'"></span>
                                </div>
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- mobile Menu --}}
        <div class="flex-1 xl:hidden">
            @if (has_nav_menu('primary_navigation'))
                @include('sections.header.mobile-menu')
            @endif
        </div>

        @if(is_product())
            @include('sections.header.product-sticky-add-to-cart')
        @endif

    </section>
</header>

{{-- fixed search modal --}}
@if(!is_cart() && !is_checkout())
    <div id="typesense-secondary-search"
        x-data="{openSearch:false}"
        x-init="
            document.addEventListener('toggleSearch', (event) => {
                openSearch=!openSearch;
            });
        "
        x-cloak 
        x-show="openSearch" 
        class="fixed overflow-auto ht-container-no-max-width top-0 left-0 w-screen h-dvh flex items-center px-3 z-[1020] {{ 'text-'.$header_color }} {{ 'bg-'.$header_bg }}"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100 " 
        x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="relative flex flex-col justify-center-center w-full h-full pt-3">
            <div class="flex items-center absolute top-4 xl:top-6 right-0 z-10">
                <button type="button"  x-on:click="openSearch = false">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" x="0" y="0" width="15.74" height="15.74" viewBox="0 0 15.74 15.74" xml:space="preserve">
                    <path fill="none" stroke="currentColor" stroke-width="2" stroke-miterlimit="10" d="M.35.35l15.03 15.03M15.38.35L.35 15.38"></path>
                    </svg>
                </button>
            </div>
            <div class="flex justify-between">
                <?php echo do_shortcode('[cm_tsfwc_search
                            cat_filter="show"
                            routing="disable"
                            price_filter="show"
                            rating_filter="hide"
                            attribute_filter="show"
                            pagination="show"
                            show_more_text="show"
                            sortby="show"
                            placeholder="' .  __( 'Search products', 'typesense-search-for-woocommerce' ) . '"
                            show_featured_first="yes"
                            unique_id="ts_woo_secondary_search"
                        ]'); 
                ?>
            </div>
        </div>
    </div>
@endif
