<?php

namespace App\View\Composers\Posts;

use Roots\Acorn\View\Composer;

class PostCategory extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var string[]
     */
    protected static $views = [
        'category',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {

        return [
            'categories' => $this->getCategories(),
        ];
    }

    private function getCategories()
    {
        $currentCat = get_queried_object_id();

        $cats = get_categories([
            'parent' => $currentCat,
        ]);

        return $cats;
    }
}
