<?php

namespace App\View\Composers\Sections;

use Roots\Acorn\View\Composer;

class Footer extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'sections.footer.template',
        'blocks.footer.*',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'footer_logo' => ht_get_field('footer_logo','options')['url'] ?? '',
            'footer_bg' => ht_get_field('footer_bg', 'options') ?? '#f4f3ef',
            'footer_color' => ht_get_field('footer_color', 'options') ?? '#212121',
            'footer_hover_color' => ht_get_field('footer_hover_color', 'options') ?? '#212121',
        ];
    }
}
