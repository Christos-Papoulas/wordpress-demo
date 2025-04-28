<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;
use WP_Query;

class SingleCampus extends Composer
{
    protected static $views = [
        'partials.content-single-campus',
    ];


    public function with(): array
    {
        $programs = $this->relatedPrograms();

        return [
            'programs' => $programs,
        ];
    }

    public function relatedPrograms(): WP_Query
    {
        return new WP_Query([
            'posts_per_page' => -1,
            'post_type' => 'program',
            'meta_query' => [
                [
                    'key' => 'related_campus',
                    'compare' => 'LIKE',
                    'value' => '"' . get_the_ID() .   '"',
                ],
            ],
            'orderby' => 'title',
            'order' => 'ASC',
        ]);
    }

}
