<?php

namespace App\View\Composers\Woocommerce\SingleProduct\AddToCart;

use Roots\Acorn\View\Composer;

class Variable extends Composer
{
    private $product;

    /**
     * List of views served by this composer.
     *
     * @var string[]
     */
    protected static $views = [
        'woocommerce.single-product.add-to-cart.variable',
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
            'product' => $this->product,
        ];
    }
}
