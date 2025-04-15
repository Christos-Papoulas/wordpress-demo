<div x-data="miniCart" 
:class="showMinicart && '!translate-x-0 !translate-y-0'"
class="transform translate-y-full xl:translate-y-0 xl:translate-x-[calc(100%_+_24px)] transition w-full xl:max-w-[530px] fixed bottom-0 left-0 xl:bottom-auto xl:left-auto xl:top-28 xl:right-4 z-[1020] px-3 py-4 xl:px-5 xl:py-5 overflow-y-auto bg-white shadow-md max-h-[500px] 2xl:max-h-[50dvh]">
    
    <div class="hidden xl:flex flex-col items-center overflow-auto" style="max-height:calc(50dvh - 190px )">
        <div class="w-full flex items-center justify-between mb-6">
            <h5 class="text-body flex items-center text-lg mb-0">{{ __( 'SHOPPING BAG', 'sage' ) }}</h5>
            <button x-on:click="toggleMinicart()" type="button" 
                class="hover:text-red-600 text-body text-sm w-8 h-8 inline-flex items-center justify-center transition" >
                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
                <span class="sr-only">{{ __('Close Shopping Bag','sage') }}</span>
            </button>
        </div>
        <div class="w-full flex items-center justify-between">
            <span class="text-xs text-body font-bold"><span x-text="count"></span> {{ __('PRODUCTS','sage') }}</span>
            <template x-if="count > 0">
            <button x-on:click="clearCart" type="button" class="text-[10px] leading-3 text-body hover:text-red-600 transition underline">{{ __('CLEAR','sage') }}</button>
            </template>
        </div>

        <div class="py-5 w-full" style="max-height:436px;">
            <div x-cloak x-show="!loading && count == 0" class="w-full"> 
                @php
                /*
                * @hooked wc_empty_cart_message - 10
                */
                do_action( 'woocommerce_cart_is_empty' );
                @endphp
            </div>
            <template x-for="item in items">
                <div class="w-full flex flex-col text-body items-center pt-3 pb-6">
                    
                    <div class="w-full flex justify-between items-center">
                        <div class="w-full gap-3 flex flex-col justify-center">
                            <div class="w-full flex gap-3 items-center">
                                <a :href="item.permalink" class="bg-[#f4f2ee] relative flex w-full max-w-[58px] aspect-[800/1000]">
                                    <img class="maybeAddMixBlend object-contain" :src="item.image_src" :alt="item.title">
                                    <span class="top-0 right-0 transform translate-x-[50%] -translate-y-[50%] text-xs absolute bg-secondary text-white w-5 h-5 rounded-full flex items-center justify-center">
                                        <strong class="product-quantity" x-html="item.quantity"></strong>
                                    </span>
                                </a>
                                <div>
                                    <a :href="item.permalink" class="flex flex-wrap">
                                        <div class="w-full text-xs text-body max-w-xs font-bold uppercase" x-html="item.title"></div>
                                        <template x-if="item.hasOwnProperty('variation_attr')"><div class="w-full text-xs text-body max-w-xs font-bold uppercase" x-html="item.variation_attr"></div></template>
                                        <div class="w-full text-xs text-body" x-html="`{{ __('SKU:','sage') }} ${item.sku}`"></div>
                                        <template x-if="item.backorder_note != ''"><div x-html="item.backorder_note" class="paragraph-no-margin text-xs text-body"></div></template>
                                    </a>
                                    <div class="flex">
                                        <div class="flex items-center my-3">
                                            {{-- minus btn --}}
                                            <button class="hover:text-red-500 transition"
                                            x-on:click.prevent.debounce="if(item.quantity>1)decrement(item.key)">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="w-2 h-8 lg:w-3 lg:h-3">
                                                    <path
                                                        d="M432 256c0 17.7-14.3 32-32 32L48 288c-17.7 0-32-14.3-32-32s14.3-32 32-32l352 0c17.7 0 32 14.3 32 32z"
                                                        fill="currentColor">
                                                    </path>
                                                </svg>
                                            </button>
                                            {{-- input --}}
                                            <div x-text="item.quantity" class="text-center mb-0 text-xs px-2.5"></div>
                                            {{-- plus btn --}}
                                            <button class="hover:text-black transition"
                                            x-on:click.prevent.debounce="increment(item.key)">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="w-2 h-8 lg:w-3 lg:h-3">
                                                    <path
                                                        d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z"
                                                        fill="currentColor">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                        <button class="flex items-center ml-7" x-on:click.prevent.debounce="removeItemFromCart(item.key)">
                                            <svg
                                            class="text-red-600 transition-all ease-in-out duration-150 w-2 h-8 lg:w-3 lg:h-3"
                                            xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                            viewBox="0 0 14.121 14.121">
                                            <g id="Group_16004" data-name="Group 16004"
                                                transform="translate(1.061 1.061)">
                                                <line id="Line_385" data-name="Line 385" x2="12" y2="12"
                                                    transform="translate(0)" fill="none" stroke="currentColor"
                                                    stroke-width="3"/>
                                                <line id="Line_386" data-name="Line 386" x1="12" y2="12" fill="none"
                                                    stroke="currentColor" stroke-width="3"/>
                                            </g>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <template x-if="item.backorder_note != ''"><div class="w-full paragraph-no-margin" x-html="item.backorder_note"></div></template>
                        </div>
                        <div class="w-[100px] md:w-1/6 text-xs text-right" x-html="item.price_html"></div>
                    </div>

                    <div class="w-full mt-1 text-xs text-red-600 text-center" x-show="item.messages.incrementDisabled" x-html="item.messages.incrementDisabled" x-cloak style="display: none !important;"></div>
                    <div class="w-full mt-1 text-xs text-red-600 text-center" x-show="item.messages.decrementDisabled" x-html="item.messages.decrementDisabled" x-cloak style="display: none !important;"></div>
                </div>
            </template>
        </div>
    </div>


    <div class="mb-4 pt-2 border-t border-black">
        @include('woocommerce/cart/partials/freeshipping')
    </div>

    <div x-cloak x-show="!loading && count > 0" class="w-full">
        <div class="hidden xl:flex justify-between items-center text-xs text-body pt-5 pb-7 border-t border-black">
            <span>{{ __('PRODUCTS:','sage') }}</span>
            <span class="font-bold" x-html="totalFormatted"></span>
        </div>
        <a href="{{ wc_get_cart_url() }}"
            class="btn-md btn-outlined-primary mb-1">
            {{ __('GO TO SHOPPING BAG','sage') }}
        </a>
        <button type="button" x-on:click="toggleMinicart"
            class="btn-md btn-solid-primary w-full">
            {{ __('CONTINUE SHOPPING','sage') }}
        </button>
    </div>

    <div x-cloak x-show="!loading && count == 0"  class="">
        @php if ( wc_get_page_id( 'shop' ) > 0 ) : @endphp
            <a class="btn-md btn-solid-primary" href="@php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); @endphp">
                @php
                    /**
                    * Filter "Return To Shop" text.
                    *
                    * @since 4.6.0
                    * @param string $default_text Default text.
                    */
                    echo esc_html( apply_filters( 'woocommerce_return_to_shop_text', __( 'BACK TO STORE', 'sage' ) ) );
                @endphp
            </a>
        @php endif; @endphp
    </div>

</div>
