<?php

namespace App\View\Composers\Sections;

use App\HT\Services\MenuService;
use Roots\Acorn\View\Composer;

class BottomFooter extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'sections.footer.bottombar',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'copyright_text' => ht_get_field('footer_copyright_text', 'options'),
            'bottom_footer_nav' => MenuService::getBottomFooter(),
        ];
    }
}
