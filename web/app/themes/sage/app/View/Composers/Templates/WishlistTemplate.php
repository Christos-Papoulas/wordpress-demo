<?php

namespace App\View\Composers\Templates;

use Roots\Acorn\View\Composer;
use App\HT\Services\Wishlist;

class WishlistTemplate extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'template-wishlist'
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        $wishlist = app(Wishlist::class);

        return [
            'wishlist' => $wishlist,
        ];
    }
}
