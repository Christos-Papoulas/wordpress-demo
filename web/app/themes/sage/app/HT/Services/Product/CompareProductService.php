<?php

namespace App\HT\Services\Product;

class CompareProductService
{
    /**
     * Store product to session
     *
     * @param  int  $post_id
     */
    public static function insertProduct(): mixed
    {
        $post_id = $_POST['post_id'];

        if (! WC()->session->has_session()) {
            WC()->session->set_customer_session_cookie(true);
        }

        $arr = WC()->session->get('compare_product_list');

        if (! is_array($arr)) {
            $arr = [];
        }

        if (! in_array($post_id, $arr)) {
            $arr[] = $post_id;
        }

        WC()->session->set('compare_product_list', $arr);

        wp_send_json_success();
    }

    /**
     * Get compare product list
     */
    public static function getCompareProductList(): array
    {
        $arr = WC()->session->get('compare_product_list');
        if (! is_array($arr)) {
            $arr = [];
        }

        if (! empty($arr)) {
            $arr = array_map(fn ($post_id) => wc_get_product($post_id), $arr);
            $arr = array_filter($arr);
        }

        return $arr;
    }

    /**
     * Group compare list by categories
     *
     * @param  array  $myCompareList  array of product objects
     */
    public static function groupByCategories($myCompareList): array
    {
        $grouped_products = [];
        $attributes = [];

        foreach ($myCompareList as $product) {
            $first_category = $product->get_category_ids()[0];
            if (! isset($grouped_products[$first_category])) {
                $grouped_products[$first_category] = [];
            }
            $grouped_products[$first_category][] = $product;
        }

        $selected_list = $_GET['ht_list'] ?? array_key_first($grouped_products);

        foreach ($grouped_products[$selected_list] as $product) {
            $atts = $product->get_attributes();
            if (! empty($atts)) {
                foreach ($atts as $key => $att) {
                    $attributes[] = $key;
                }
            }
        }
        $unique_attributes = array_unique($attributes);

        return [
            $selected_list,
            $unique_attributes,
            $grouped_products,
        ];
    }

    /**
     * Remove products from compare list by categories
     *
     * @param  int  $cat_id  category id
     */
    public static function removeByCategory(): mixed
    {
        $cat_id = $_POST['cat_id'];
        $arr = WC()->session->get('compare_product_list');

        $remove = [];

        if (! is_array($arr)) {
            $arr = [];
        }

        foreach ($arr as $post_id) {
            $product = wc_get_product($post_id);
            if ($product) {
                $first_category = $product->get_category_ids()[0];
                if ($cat_id == $first_category) {
                    $remove[] = $product->get_ID();
                }
            }
        }

        $filteredArr = array_diff($arr, $remove);
        $filteredArr = array_values($filteredArr);
        WC()->session->set('compare_product_list', $filteredArr);

        wp_send_json_success();
    }

    /**
     * Remove all products from compare list
     */
    public static function removeAllProducts(): mixed
    {
        WC()->session->set('compare_product_list', []);
        wp_send_json_success();
    }

    /**
     * Remove product from compare list
     *
     * @param  int  $post_id  product id
     */
    public static function removeProduct($post_id): mixed
    {
        $post_id = $_POST['post_id'];
        $arr = WC()->session->get('compare_product_list');

        $remove = [$post_id];

        if (! is_array($arr)) {
            $arr = [];
        }

        $filteredArr = array_diff($arr, $remove);
        $filteredArr = array_values($filteredArr);
        WC()->session->set('compare_product_list', $filteredArr);

        wp_send_json_success();
    }
}
