<?php

namespace App\View\Composers\Woocommerce\SingleProduct\Partials;

use Roots\Acorn\View\Composer;

class Categories extends Composer
{
    private $product;

    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'woocommerce.single-product.partials.categories',

    ];

    public function __construct()
    {
        global $product;
        $this->product = $product;
    }

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'productCategories' => $this->getProductCategories(),
        ];
    }

    private function getProductCategories(): array
    {
        $cats = $this->product->get_category_ids();
        if (! empty($cats)) {
            $cats = array_map(function ($cat_id) {
                return get_term($cat_id, 'product_cat');
            }, $cats);
        }

        return $cats;
    }
}
