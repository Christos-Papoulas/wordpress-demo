<?php

namespace App\HT\Services;

use App\HT\Models\Store;

class CheckoutService
{
    /**
     * Get shipping methods for checkout
     *
     * @return void
     */
    public static function getShippingMethodsForCheckout()
    {
        if (is_user_logged_in() && (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'security_nonce'))) {
            wp_send_json_error(['message' => 'Invalid nonce'], 403);
        }
        
        WC()->cart->calculate_totals();
        ob_start();
        do_action('woocommerce_review_order_before_shipping');
        wc_cart_totals_shipping_html();
        do_action('woocommerce_review_order_after_shipping');
        $shippingHtml = ob_get_clean();

        wp_send_json_success([
            'html' => $shippingHtml,
        ], 200);
    }

    /**
     * Check for frozen products. Frozen products can't be shipped outside Athens.
     * Same check in App\HT\Services\Cart\CartService/validateCartBeforeCheckout;
     */
    public static function checkForFrozenProducts(): void
    {
        $restricted_category_slug = 'katepsygmena';
        $restricted_category = get_term_by('slug', $restricted_category_slug, 'product_cat'); // WPML works
        if (! $restricted_category) {
            return;
        }

        $must_be_removed_products = [];

        $chosen_shipping_methods = WC()->session->get('chosen_shipping_methods', []);

        $checkForFrozen = false;
        foreach ($chosen_shipping_methods as $key => $method) {
            if (strpos($method, 'local_pickup') === 0) {
                if (! isset($_POST[Store::INPUT_NAME])) {
                    wc_add_notice('Something went wrong. Please refresh checkout.', 'error');
                }

                $store_available_for_frozen = ht_get_field('store_custom_fields_frozen_products_enabled', $_POST[Store::INPUT_NAME]);
                if ($store_available_for_frozen !== 'yes') {
                    $checkForFrozen = true;
                }

            } elseif (strpos($method, 'free_shipping') === 0) {

                if (! isset($_POST['shipping_postcode'])) {
                    wc_add_notice('Something went wrong. Please refresh checkout.', 'error');
                }
                if (! ShippingService::isPostCodeInAthens($_POST['shipping_postcode'])) {
                    $checkForFrozen = true;
                }
            }
        }

        if ($checkForFrozen) {

            foreach (WC()->cart->get_cart() as $cart_item) {
                $product_id = $cart_item['product_id'];
                $product_categories = wp_get_post_terms($product_id, 'product_cat', ['fields' => 'ids']);

                // Check if product is in the restricted cat.
                if (has_term($restricted_category->slug, 'product_cat', $product_id)) {
                    $must_be_removed_products[] = $cart_item['data']->get_name();

                    continue;
                }

                // Check if the product is in a child category of the restricted cat.
                foreach ($product_categories as $category_id) {
                    // Get all parent categories of this category
                    $ancestors = get_ancestors($category_id, 'product_cat');
                    // Check if the restricted category is in the ancestors
                    if (in_array($restricted_category->term_id, $ancestors)) {
                        $must_be_removed_products[] = $cart_item['data']->get_name();
                        break;
                    }
                }
            }

            if (! empty($must_be_removed_products)) {
                wc_add_notice(__('The below products are frozen and can\' t be shipped to the chosen store. Please remove them or select a store in Athens', 'sage').'<br>'.implode('<br>', $must_be_removed_products), 'error');
            }
        }
    }
}
