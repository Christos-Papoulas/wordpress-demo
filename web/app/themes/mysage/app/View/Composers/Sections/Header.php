<?php

namespace App\View\Composers\Sections;

use Roots\Acorn\View\Composer;

class Header extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'sections.header.template',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        $wishlist = ht_get_field('wishlist_page', 'option');

        return [
            'wishlist_url' => empty($wishlist) ? '' : esc_url( get_permalink( $wishlist->ID ) ),
            'topbar_bg' => ht_get_field('topbar_bg', 'options') ?? 'primary',
            'topbar_color' => ht_get_field('topbar_color', 'options') ?? 'white',
            'topbar_hover_color' => ht_get_field('topbar_hover_color', 'options') ?? 'white',

            'header_logo' => ht_get_field('header_logo', 'options')['url'] ?? '',
            'header_sticky_logo' => ht_get_field('header_sticky_logo', 'options')['url'] ?? '',
            'header_bg' => ht_get_field('header_bg', 'options') ?? 'primary',
            'header_color' => ht_get_field('header_color', 'options') ?? 'white',
            'header_hover_color' => ht_get_field('header_hover_color', 'options') ?? 'white',
            'header_messages' => ht_get_field('header_messages', 'options') ?? '',
        ];
    }
}
