<?php

namespace App\View\Composers\Menu;

use App\HT\Services\MenuService;
use Roots\Acorn\View\Composer;

class TopBar extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'sections.header.topbar',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'topBarMenu' => MenuService::getTopBar(),
        ];
    }
}
