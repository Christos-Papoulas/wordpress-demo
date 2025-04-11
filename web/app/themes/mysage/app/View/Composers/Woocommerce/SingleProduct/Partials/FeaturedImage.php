<?php

namespace App\View\Composers\Woocommerce\SingleProduct\Partials;

use App\HT\Services\Product\MediaService;
use Roots\Acorn\View\Composer;

class FeaturedImage extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'woocommerce.single-product.partials.product-featured-image',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        global $product;

        return [
            'productFeaturedImagesUrl' => MediaService::getProductMainImageForZoom($product, 'full', 'woocommerce_single'),
        ];
    }
}
