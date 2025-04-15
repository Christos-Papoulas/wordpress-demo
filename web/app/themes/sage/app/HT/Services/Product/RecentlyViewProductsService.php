<?php

namespace App\HT\Services\Product;

class RecentlyViewProductsService
{
    /**
     * Store product to session
     *
     * @param  int  $post_id
     * @return void
     */
    public static function insertProduct($post_id)
    {
        $arr = WC()->session->get('recently_view_products');

        if (! WC()->session->has_session()) {
            WC()->session->set_customer_session_cookie(true);
        }

        if (! is_array($arr)) {
            $arr = [];
        }

        if (! in_array($post_id, $arr)) {
            if (count($arr) >= 10) {
                array_shift($arr);
            }
            $arr[] = $post_id;
        }

        WC()->session->set('recently_view_products', $arr);
    }

    /**
     * Get recently viewed products
     */
    public static function get(): array
    {
        $arr = [];
        if (WC()->session !== null) {
            $arr = WC()->session->get('recently_view_products');
        }
        if (! is_array($arr)) {
            $arr = [];
        }

        if (! empty($arr)) {
            // WPML
            if (defined('ICL_SITEPRESS_VERSION')) {
                $arr = array_map(fn ($post_id) => apply_filters('wpml_object_id', $post_id, 'post'), $arr);
            }
            $arr = array_map(fn ($post_id) => wc_get_product($post_id), $arr);
            $arr = array_filter($arr);
        }

        return $arr;
    }
}
