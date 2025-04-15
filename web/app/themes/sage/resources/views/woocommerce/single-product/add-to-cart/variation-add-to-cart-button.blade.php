{{--
    /**
    * Single variation cart button
    *
    * @see https://docs.woocommerce.com/document/template-structure/
    * @package WooCommerce\Templates
    * @version 3.4.0
    */
--}}
@php
    defined('ABSPATH') || exit;
    global $product;
@endphp

<div class="woocommerce-variation-add-to-cart variations_button cart w-full">
    @php
        do_action('woocommerce_before_add_to_cart_button');
    @endphp

    @php
        do_action('woocommerce_before_add_to_cart_quantity');
    @endphp

    <div style="display: none !important">
        @php
            woocommerce_quantity_input(
                [
                    'min_value' => apply_filters('woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product),
                    'max_value' => apply_filters('woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product),
                    'input_value' => isset($_POST['quantity']) ? wc_stock_amount(wp_unslash($_POST['quantity'])) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
                ]
            );
        @endphp
    </div>
    <div class="flex w-full space-x-4 md:flex-col md:space-y-4 md:space-x-0 lg:flex-row lg:space-y-0 lg:space-x-4">
        <div class="w-full overflow-hidden">
            <button
                x-ref="button"
                x-on:click.prevent.stop="addOneToCart()"
                data-variant="slim"
                type="submit"
                :class="stockStatus === 'outofstock' && '!cursor-not-allowed'"
                class="btn-single-add-to-cart single_add_to_cart_button relative !w-full overflow-hidden bg-black py-3 text-xs font-bold text-white"
            >
                <div class="flex items-center justify-center">
                    <span
                        x-cloak
                        x-show="!loading && !showButtons"
                        x-text="addToCartText"
                    >
                        {{ __('CHOOSE A VARIATION FIRST', 'sage') }}
                    </span>
                    <span x-cloak x-show="!loading && showButtons" x-text="qty"></span>
                    <svg
                        x-show="loading"
                        class="pointer-events-none ml-2 h-4 w-4 animate-spin text-white"
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

            <input
                type="hidden"
                name="add-to-cart"
                value="@php
                echo absint($product->get_id());@endphp




"
            />
            <input
                type="hidden"
                name="product_id"
                value="@php
                echo absint($product->get_id());@endphp




"
            />
            <input type="hidden" name="variation_id" class="variation_id" value="0" />
        </div>
    </div>
</div>
