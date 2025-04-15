<?php

namespace App\View\Composers\Menu;

use App\HT\Services\MenuService;
use Roots\Acorn\View\Composer;

class BottomBar extends Composer
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
            'bottom_footer' => MenuService::getBottomFooter(),
        ];
    }
}
