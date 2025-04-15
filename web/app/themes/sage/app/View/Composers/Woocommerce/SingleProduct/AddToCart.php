<?php

namespace App\View\Composers\Woocommerce\SingleProduct;

use App\HT\Services\Product\ProductService;
use Roots\Acorn\View\Composer;

class AddToCart extends Composer
{
    public $productCardData;

    /**
     * List of views served by this composer.
     *
     * @var string[]
     */
    protected static $views = [
        'woocommerce.single-product.add-to-cart.simple',
        'woocommerce.single-product.add-to-cart.partials.variable-theme',
    ];

    public function __construct()
    {
        global $product;

        $this->productCardData = ProductService::createProductCardData($product, true);
    }

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'productCardData' => $this->productCardData,
        ];
    }
}
