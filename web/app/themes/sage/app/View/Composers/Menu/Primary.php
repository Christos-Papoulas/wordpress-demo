<?php

namespace App\View\Composers\Menu;

use App\HT\Services\MenuService;
use Roots\Acorn\View\Composer;

class Primary extends Composer
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
        return [
            'megamenu' => MenuService::getPrimary(),
        ];
    }
}
