<?php

namespace App\View\Composers\Woocommerce\Checkout;

use Roots\Acorn\View\Composer;

class Checkout extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'woocommerce.checkout.form-checkout',
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
