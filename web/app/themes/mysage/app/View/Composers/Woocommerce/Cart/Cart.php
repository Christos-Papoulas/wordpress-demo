<?php

namespace App\View\Composers\Woocommerce\Cart;

use Roots\Acorn\View\Composer;

class Cart extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'woocommerce.cart.cart',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [];
    }
}
