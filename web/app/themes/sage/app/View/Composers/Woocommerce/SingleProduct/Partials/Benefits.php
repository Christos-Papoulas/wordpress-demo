<?php

namespace App\View\Composers\Woocommerce\SingleProduct\Partials;

use Roots\Acorn\View\Composer;

class Benefits extends Composer
{
    private $product;

    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'woocommerce.single-product.partials.benefits',

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
            'benefits' => $this->getBenefits(),
        ];
    }

    private function getBenefits()
    {
        return ht_get_field('benefits','options')['benefits'] ?? [];
    }
}
