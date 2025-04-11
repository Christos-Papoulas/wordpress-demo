<?php

namespace App\View\Composers\Templates;

use App\HT\Services\Page\PageService;
use Roots\Acorn\View\Composer;

class FaqTemplate extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'template-faq',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        $pageService = new PageService;
        $accordion = ht_get_field('accordion');

        return [
            'showBreadcrumbs' => $pageService->breadcrumbs_options['enabled'] ?? true,
            'breadcrumbsContainer' => $pageService->breadcrumbs_options['container'] ?? 'ht-container-no-max-width',
            'showPageTitle' => $pageService->showPageTitle ?? true,
            'pageTitleContainer' => $pageService->pageTitleOptions['container'] ?? 'ht-container-no-max-width',
            'accordion' => $accordion,
            'activeQuestion' => $this->setActiveQuestion($accordion),
        ];
    }

    /**
     * Creates an array with questions and their keys
     * Returns the active question data.
     */
    private function setActiveQuestion($accordion)
    {
        $number = $_GET['q'] ?? false;
        if (! $number) {
            return null;
        }

        $array = [];
        foreach ($accordion['accordion'] as $groupKey => $row) {
            foreach ($row['group']['group_faq'] as $key => $group_faq) {
                $array[] = [
                    'group_key' => $groupKey,
                    'question_key' => $key,
                ];
            }
        }

        return $array[$number - 1];
    }
}
