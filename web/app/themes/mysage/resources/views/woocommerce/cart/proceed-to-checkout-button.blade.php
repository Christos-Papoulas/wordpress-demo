<?php
/**
 * Proceed to checkout button
 *
 * Contains the markup for the proceed to checkout button on the cart.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/proceed-to-checkout-button.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 *
 * @version 2.4.0
 */
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
?>

<a
    href="<?php echo esc_url(wc_get_checkout_url()); ?>"
    class="checkout-button button alt wc-forward flex h-12 !w-full items-center justify-center !rounded-md !bg-[#229E44] text-center !text-base text-white transition duration-300 ease-in-out"
>
    <?php esc_html_e('Checkout', 'sage'); ?>
</a>
