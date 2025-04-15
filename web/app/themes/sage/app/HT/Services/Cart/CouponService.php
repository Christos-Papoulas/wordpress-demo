<?php

namespace App\HT\Services\Cart;

class CouponService
{
    /**
     * Apply coupon to cart.
     *
     * @since   1.0.0
     */
    public static function applyCoupon(): void
    {
        if (is_user_logged_in() && (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'security_nonce'))) {
            wp_send_json_error(['message' => 'Invalid nonce'], 403);
        }

        $notice['notice'] = '';
        $coupon_code = $_POST['coupon_code'];

        $applied = WC()->cart->add_discount($coupon_code);
        if (! $applied) {
            $notices = wc_get_notices('error');
            $notice = array_shift($notices);
            WC()->session->set('wc_notices', []);
        }

        if ($applied) {
            wp_send_json_success(null, 200);
        } else {
            wp_send_json_error(['message' => $notice['notice']], 200);
        }
    }

    /**
     * Remove coupon from cart.
     *
     * @since   1.0.0
     */
    public static function removeCoupon(): void
    {
        if (is_user_logged_in() && (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'security_nonce'))) {
            wp_send_json_error(['message' => 'Invalid nonce'], 403);
        }
        
        $coupon_slug = $_POST['couponSlug'];

        $applied_coupons = WC()->cart->get_applied_coupons(); // Get currently applied coupons

        if (in_array($coupon_slug, $applied_coupons)) {
            $coupon_key = array_search($coupon_slug, $applied_coupons); // Get the key of the coupon slug

            WC()->cart->remove_coupon($applied_coupons[$coupon_key]); // Remove the coupon from the cart
            WC()->cart->calculate_totals(); // Recalculate cart totals
        }

        wp_send_json_success(null, 200);
    }

    /**
     * Get Cart coupons
     *
     * @see woocommerce wc_cart_totals_coupon_label, wc_cart_totals_coupon_html()
     */
    public static function getCartCoupons(): array
    {
        $coupons = [];

        foreach (WC()->cart->get_coupons() as $code => $coupon) {
            if (is_string($coupon)) {
                $coupon = new \WC_Coupon($coupon);
            }

            $discount_amount_html = '';

            $amount = WC()->cart->get_coupon_discount_amount($coupon->get_code(), WC()->cart->display_cart_ex_tax);

            $discount_amount_html = '-'.wc_price($amount);

            if ($coupon->get_free_shipping() && empty($amount)) {
                $discount_amount_html = __('Free shipping coupon', 'woocommerce');
            }

            $coupons[] = [
                'label' => apply_filters('woocommerce_cart_totals_coupon_label', $coupon->get_code(), $coupon),
                'amount' => $amount,
                'formatted' => wc_price($amount),
            ];
        }

        return $coupons;
    }
}
