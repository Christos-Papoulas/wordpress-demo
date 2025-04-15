<?php

namespace App\View\Composers\Woocommerce\SingleProduct;

use App\HT\Services\Product\ProductService;
use App\HT\Services\Product\RecentlyViewProductsService;
use Roots\Acorn\View\Composer;

class ContentSingleProduct extends Composer
{
    private $product;

    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'woocommerce.content-single-product',

    ];

    public function __construct()
    {
        global $product;
        $this->product = $product;
        RecentlyViewProductsService::insertProduct($this->product->get_id());
    }

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        [$price, $regular_price, $sale_price, $discount_percentage] = ProductService::getPrices($this->product);

        return [
            'product' => $this->product,
            'product_title' => $this->product->get_title(),
            'product_price' => $price,
            'product_regular_price' => $regular_price,
            'product_sale_price' => $sale_price,
            'product_on_sale' => $this->product->is_on_sale(),
            'discount_percentage' => $this->getDiscountPercentage($regular_price, $sale_price),
            // 'product_reviews' => $this->getProductReviews($this->product->get_ID()),
            'cross_sells_and_upsells' => $this->getCrossSells_UpsellsOrSameCatProducts(),
        ];
    }

    /**
     * Proper way to return product price. It accepts product, price, quantity, tax and optionally returns html
     *
     * @param  mixed  $price  Price amount
     * @param  mixed  $sale_price  Sale Price amount
     * @return mixed
     */
    private function getDiscountPercentage($regular_price, $sale_price)
    {
        if ($this->product->is_on_sale() && $regular_price && $sale_price) {
            $discount_percentage = round(100 - ($sale_price / $regular_price * 100));

            return $discount_percentage;
        }

        return false;
    }

    private function getProductReviews($product_id)
    {
        return get_comments([
            'post_id' => $product_id,
            'number' => 10,
            'status' => 'approve',
            'post_status' => 'publish',
            'post_type' => 'product',
        ]);
    }

    private function getCrossSells_UpsellsOrSameCatProducts()
    {

        // filter array to remove false values ( bad database )
        $cross_sells = array_filter(array_map(fn ($post_id) => wc_get_product($post_id), $this->product->get_cross_sell_ids()));
        $up_sells = array_filter(array_map(fn ($post_id) => wc_get_product($post_id), $this->product->get_upsell_ids()));

        $cross_sells = array_filter($cross_sells, function ($product) {
            return $product && $product !== null && $product->get_catalog_visibility() !== 'hidden';
        });
        $up_sells = array_filter($up_sells, function ($product) {
            return $product && $product !== null && $product->get_catalog_visibility() !== 'hidden';
        });

        if (empty($cross_sells) || empty($up_sells)) {

            $primary_cat_id = get_post_meta($this->product->get_ID(), '_yoast_wpseo_primary_product_cat', true);
            if ($primary_cat_id) {
                $terms = [$primary_cat_id];
            } else {
                // always returns array with length at least 1.
                $terms = $this->product->get_category_ids();
            }

            $args = [
                'posts_per_page' => 12,
                'no_found_rows' => 1,
                'orderby' => 'rand',
                'order' => 'DESC',
                'tax_query' => [
                    [
                        'taxonomy' => 'product_cat',
                        'field' => 'term_id',
                        'terms' => $terms,
                        'include_children' => false,
                    ],
                    [
                        'taxonomy' => 'product_visibility',
                        'field' => 'slug',
                        'terms' => ['exclude-from-catalog'],
                        'operator' => 'NOT IN',
                    ],
                ],
            ];
            $same_cat_products = array_map(fn ($post) => wc_get_product($post), ProductService::getProducts($args));
            if ($same_cat_products === false) {
                $same_cat_products = [];
            }
        }

        return [
            empty($cross_sells) ? $same_cat_products : $cross_sells,
            empty($up_sells) ? $same_cat_products : $up_sells,
        ];

    }
}
