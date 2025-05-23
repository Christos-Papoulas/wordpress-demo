<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;
use WP_Query;

class SingleProgram extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'partials.content-single-program',
    ];

    public function with(): array
    {
        return [
            'events' => $this->relatedEvents(),
            'professors' => $this->relatedProfessors(),
            'campuses' => $this->relatedCampuses(),
        ];
    }

    public function relatedEvents()
    {
        $today = date('Ymd');

        return new WP_Query([
            'posts_per_page' => 3,
            'post_type' => 'event',
            'meta_key' => 'event-date',
            'meta_query' => [
                [
                    'key' => 'event-date',
                    'compare' => '>=',
                    'value' => $today,
                    'type' => 'numeric'
                ],
                [
                    'key' => 'related-programs',
                    'compare' => 'LIKE',
                    'value' => '"' . get_the_ID() .   '"',
                ],
            ],
            'orderby' => 'meta_value_num',
            'order' => 'ASC',
        ]);
    }

    public function relatedProfessors()
    {
        $today = date('Ymd');

        return new WP_Query([
            'posts_per_page' => -1,
            'post_type' => 'professor',
            'meta_query' => [
                [
                    'key' => 'related-programs',
                    'compare' => 'LIKE',
                    'value' => '"' . get_the_ID() .   '"',
                ],
            ],
            'orderby' => 'title',
            'order' => 'ASC',
        ]);
    }

    public function relatedCampuses()
    {
        return get_field('related_campus') ?: null;
    }
}
