<?php

namespace App\View\Composers\Posts\CPT;

use Roots\Acorn\View\Composer;

class StoreArchive extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var string[]
     */
    protected static $views = [
        'archive-store',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'postType' => get_post_type(),
            'taxonomyTerm' => $this->getTaxonomyTerm(),
        ];
    }

    private function getTaxonomyTerm()
    {
        $term = null;
        if (is_tax()) {
            $term = get_queried_object();
        }

        return $term;
    }
}
