<?php

namespace App\View\Composers\Partials;

use App\HT\Services\Page\PageService;
use Roots\Acorn\View\Composer;

class PageHeader extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'partials.page-header',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        $pageService = new PageService;

        return [
            'showBreadcrumbs' => $pageService->breadcrumbs_options['enabled'] ?? true,
            'breadcrumbsContainer' => $pageService->breadcrumbs_options['container'] ?? 'ht-container-no-max-width',
            'showPageTitle' => $pageService->showPageTitle ?? true,
            'pageTitleContainer' => $pageService->pageTitleOptions['container'] ?? 'ht-container-no-max-width',
        ];
    }
}
