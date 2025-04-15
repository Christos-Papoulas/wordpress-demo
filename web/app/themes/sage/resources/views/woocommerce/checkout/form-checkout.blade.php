<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 *
 * @version 3.5.0
 */
if (! defined('ABSPATH')) {
    exit;
}

// If checkout registration is disabled and not logged in, the user cannot checkout.
if (! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in()) {
    echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')));

    return;
}

use App\HT\Services\Cart\CartService;
$cartAndTotals = CartService::getCartAndTotals('array', false);
?>

<div 
x-data="checkout({ 
    items :  JSON.parse(
                atob('{{ base64_encode(json_encode($cartAndTotals['items'])) }}')
            ),
    total : JSON.parse(
                atob('{{ base64_encode(json_encode($cartAndTotals['totals']['total']['amount'])) }}')
            )
    })" 

    x-ref="checkoutContainer" class="ht-container-large font-roboto mb-6 pb-6">
    <?php do_action('woocommerce_before_checkout_form', $checkout); ?>

    <div class="">
        <form
            name="checkout"
            method="post"
            class="checkout woocommerce-checkout @if(!is_user_logged_in()) pt-6 @endif"
            action="<?php echo esc_url(wc_get_checkout_url()); ?>"
            enctype="multipart/form-data"
        >
            <p class="form-row text-body mb-6 flex items-center gap-5">
                <label for="billing_enable_invoice" class="text-xs text-body font-bold uppercase">
                    {{ __('INVOICE?', 'sage') }}
                </label>
                <button id="billing_enable_invoice" x-on:click="toggleInvoice()" type="button">
                    <div
                        :class="invoice.enabled && 'checked'"
                        class="ht-switch-input-wrapper relative mx-auto h-6 w-11 rounded-full"
                    >
                        <div
                            class="ht-switch-input-inner absolute top-1/2 left-0 h-5 w-5 -translate-y-[50%] translate-x-0.5 transform cursor-pointer rounded-full bg-white transition-transform duration-300 ease-in-out"
                        ></div>
                    </div>
                </button>
            </p>

            <div class="grid grid-cols-1 gap-x-8 lg:grid-cols-5">
                {{-- left col --}}
                <div class="checkout-col lg:col-span-3">
                    <div class="">
                        <?php if ( $checkout->get_checkout_fields() ) : ?>

                        <?php do_action('woocommerce_checkout_before_customer_details'); ?>

                        <div class="col2-set" id="customer_details">
                            {{-- billing fields --}}
                            <div class="">
                                <?php do_action('woocommerce_checkout_billing'); ?>
                            </div>

                            {{-- Custom Newsletter --}}

                            <?php //do_action('ht_checkout_newsletter'); ?>
                
                            {{-- Shipping select --}}
                            <?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

                            <div>
                                <h2
                                    class="mt-2 mb-3 border-b-[1.5px] border-[#B6B6B6] pb-2 text-xs text-body font-bold uppercase"
                                >
                                    {{ __('Choose shipping method :', 'sage') }}
                                </h2>

                                <div class="relative">
                                    <div id="ht-checkout-shipping-methods" class="my-3">
                                        <?php do_action('woocommerce_review_order_before_shipping'); ?>

                                        <?php wc_cart_totals_shipping_html(); ?>

                                        <?php do_action('woocommerce_review_order_after_shipping'); ?>
                                    </div>
                                    <div
                                        x-ref="shippingMethodsLoader"
                                        x-cloak
                                        x-show="shippingMethodsLoading"
                                        class="absolute top-0 left-0 z-[1000] h-full w-full bg-white opacity-60"
                                    ></div>
                                </div>
                            </div>

                            <?php endif; ?>

                            {{-- shipping fields --}}
                            <div class="">
                                <?php do_action('woocommerce_checkout_shipping'); ?>
                            </div>
                        </div>

                        <?php do_action('woocommerce_checkout_after_customer_details'); ?>

                        <?php endif; ?>
                    </div>
                </div>

                {{-- right col --}}
                <div class="checkout-col z-[1020] md:sticky md:top-0 lg:z-auto lg:col-span-2">
                    <?php do_action('woocommerce_checkout_before_order_review_heading'); ?>

                    <h3
                        id="order_review_heading"
                        class="mb-0 hidden border-b-[1.5px] border-[#B6B6B6] pb-2 text-xs text-body font-bold uppercase lg:block"
                    >
                        {{ __('Your order', 'woocommerce') }}
                    </h3>

                    <?php do_action('woocommerce_checkout_before_order_review'); ?>

                    <div id="order_review" class="woocommerce-checkout-review-order">
                        <?php do_action('woocommerce_checkout_order_review'); ?>
                    </div>

                    <?php do_action('woocommerce_checkout_after_order_review'); ?>
                </div>
            </div>
        </form>
    </div>

    <?php do_action('woocommerce_after_checkout_form', $checkout); ?>
</div>
