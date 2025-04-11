<?php

namespace App\View\Composers\Posts;

use Roots\Acorn\View\Composer;

class PostArchive extends Composer
{
    protected static $views = [
        'home',
        'category',
    ];

    public function with()
    {

        return [
            'postType' => get_post_type(),
            'taxonomyTerm' => $this->getTaxonomyTerm(),
            'termDisplayType' => 'posts',
            'categories' => get_categories(),
        ];

    }

    private function getTaxonomyTerm()
    {
        $term = null;
        if (is_category()) {
            $term = get_queried_object();
        }

        return $term;
    }
}
