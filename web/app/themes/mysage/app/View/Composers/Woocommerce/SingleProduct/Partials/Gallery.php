<?php

namespace App\View\Composers\Woocommerce\SingleProduct\Partials;

use App\HT\Services\Product\MediaService;
use Roots\Acorn\View\Composer;
use App\HT\Models\Product;

class Gallery extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'woocommerce.single-product.partials.gallery',
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
            'productGalleryUrls' => MediaService::getProductGalleryForZoom($product, 'full', 'woocommerce_single', 'woocommerce_gallery_thumbnail'),
            'video' => ht_get_field(Product::VIDEO_METAKEY_NAME, $product->get_id()),
        ];
    }
}
