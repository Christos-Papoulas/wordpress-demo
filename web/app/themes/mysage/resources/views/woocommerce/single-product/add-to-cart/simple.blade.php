{{--
    /**
    * Simple product add to cart
    *
    * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
    *
    * HOWEVER, on occasion WooCommerce will need to update template files and you
    * (the theme developer) will need to copy the new files to your theme to
    * maintain compatibility. We try to do this as little as possible, but it does
    * happen. When this occurs the version of the template file will be bumped and
    * the readme will list any important changes.
    *
    * @see https://docs.woocommerce.com/document/template-structure/
    * @package WooCommerce\Templates
    * @version 3.4.0
    */
--}}
@php
    defined('ABSPATH') || exit;
    global $product;

    if (! $product->is_purchasable()) {
        return;
    }
@endphp


@php
    do_action('woocommerce_before_add_to_cart_form');
@endphp

<form
    x-data="buttonAddToCartSinglePage({
                productCardData: JSON.parse(
                    atob('{{ base64_encode(json_encode($productCardData)) }}'),
                ),
            })"
    x-ref="form"
    class="cart w-full"
    action="@php
    echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink()));@endphp




"
    method="post"
    enctype="multipart/form-data"
>
    @php
        do_action('woocommerce_before_add_to_cart_quantity');
    @endphp

    <div class="mb-4 hidden items-center justify-between">
        <div class="text-body flex items-center text-base">
            @php
                woocommerce_quantity_input(
                    [
                        'min_value' => apply_filters('woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product),
                        'max_value' => apply_filters('woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product),
                        'input_value' => isset($_POST['quantity']) ? wc_stock_amount(wp_unslash($_POST['quantity'])) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
                        'classes' => 'text-center w-10',
                    ]
                );
            @endphp
        </div>
    </div>
    @php
        do_action('woocommerce_after_add_to_cart_quantity');
    @endphp

    <div class="w-full">
        @php
            do_action('woocommerce_before_add_to_cart_button');
        @endphp

        <button
            x-ref="button"
            x-on:click.prevent.stop="addOneToCart()"
            type="submit"
            name="add-to-cart"
            value="{!! esc_attr($product->get_id()) !!}"
            :class="showButtons ? 'cursor-default':'cursor-pointer'"
            class="btn-single-add-to-cart single_add_to_cart_button relative !w-full overflow-hidden bg-black py-3 text-xs font-bold text-white"
        >
            <div class="flex items-center justify-center">
                <span x-cloak x-show="!loading && !showButtons">
                    @if ($product->is_in_stock())
                        {{ __('ADD TO CART', 'sage') }}
                    @else
                        {{ __('OUT OF STOCK', 'sage') }}
                    @endif
                </span>
                <span x-cloak x-show="!loading && showButtons" x-text="qty"></span>
                <svg
                    x-show="loading"
                    class="pointer-events-none h-4 w-4 animate-spin text-white"
                    fill="none"
                    viewBox="0 0 24 24"
                >
                    <circle
                        class="opacity-25"
                        cx="12"
                        cy="12"
                        r="10"
                        stroke="currentColor"
                        stroke-width="4"
                    ></circle>
                    <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                    ></path>
                </svg>
            </div>
            <div
                x-on:click.prevent.stop="decreaseQty()"
                x-cloak
                x-show="!loading && showButtons"
                x-ref="qty_decrease"
                :class="decreaseDisabled && '!cursor-not-allowed'"
                class="cursor-pointer absolute top-1.5 left-1.5 z-10 flex h-[calc(100%_-_.75rem)] w-10 items-center bg-[rgba(255,255,255,0.25)] text-white transition hover:bg-[rgba(255,255,255,0.35)]"
            >
                <svg viewBox="0 0 448 512" class="mx-auto h-8 w-2 lg:h-3 lg:w-3">
                    <path
                        d="M432 256c0 17.7-14.3 32-32 32L48 288c-17.7 0-32-14.3-32-32s14.3-32 32-32l352 0c17.7 0 32 14.3 32 32z"
                        fill="currentColor"
                    ></path>
                </svg>
            </div>
            <div
                x-on:click.prevent.stop="increaseQty()"
                x-cloak
                x-show="!loading && showButtons"
                x-ref="qty_increase"
                :class="increaseDisabled && '!cursor-not-allowed'"
                class="cursor-pointer absolute top-1.5 right-1.5 z-10 flex h-[calc(100%_-_.75rem)] w-10 items-center bg-[rgba(255,255,255,0.25)] text-white transition hover:bg-[rgba(255,255,255,0.35)]"
            >
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="mx-auto h-8 w-2 lg:h-3 lg:w-3">
                    <path
                        d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z"
                        fill="currentColor"
                    ></path>
                </svg>
            </div>
        </button>

        @php
            do_action('woocommerce_after_add_to_cart_button');
        @endphp
    </div>
</form>

@php
    do_action('woocommerce_after_add_to_cart_form');
@endphp

{{-- back in stock notification --}}
{{--
    <div x-data="buttonBackInStockNotify">
    <button x-on:click="openModal()" type="button" class="relative !w-full overflow-hidden bg-black py-5 h-[60px] rounded-md text-white text-xs font-bold uppercase">
    {{ __('Notify me','sage') }}
    </button>
    
    <div
    :class="open && '!translate-x-0 !translate-y-0'"
    class="transform translate-y-full xl:translate-y-0 xl:translate-x-[calc(100%_+_24px)] transition w-full xl:max-w-[530px] fixed bottom-0 left-0 xl:bottom-auto xl:left-auto xl:top-40 xl:right-4 z-[1010] px-3 py-4 xl:px-5 xl:py-5 overflow-y-auto bg-white shadow-md max-h-[50dvh]">
    
    <div x-cloak x-show="open" class="w-full">
    <template x-if="!jobdone">
    <div class="text-center text-base items-center text-body pt-5 pb-7">
    {{ __('Enter your email and we will notify you when the product is available','sage') . ' ' }}<span class="font-semibold">{{ strip_tags($product->name) }}</span>{{ ' ' . __('is back in stock.','sage') }}
    </div>
    </template>
    <template x-if="jobdone">
    <div class="text-center text-base items-center text-body pt-5 pb-7">
    {{ __("Thanks, we'll let you know.",'sage') }}
    </div>
    </template>
    <template x-if="!jobdone">
    <p class="form-row ht-custom">
    <input x-model="userEmail" type="email" value="" placeholder="name@email.com" :class="error && 'border-red-600'"/>
    </p>
    </template>
    <template x-if="error">
    <div class="text-xs text-red-600 lg:text-sm mb-5" x-text="error"></div>
    </template>
    <button x-on:click="closeModal()"
    class="btn-md btn-solid-secondary w-full mb-1">
    {{ __('CONTINUE SHOPPING','sage') }}
    </button>
    <template x-if="!jobdone">
    <button x-on:click.debounce="addRecord({{$product->get_id()}})" class="btn-md btn-solid-primary !bg-primary !text-white w-full">
    <span x-show="!loading"> {{ __('NOTIFY ME','sage') }}</span>
    <svg x-cloak x-show="loading" class="w-4 h-4 text-white pointer-events-none animate-spin" fill="none" viewBox="0 0 24 24">
    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
    </svg>
    </button>
    </template>
    </div>
    
    </div>
    </div>
--}}

