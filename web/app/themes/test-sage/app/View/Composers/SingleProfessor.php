<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;
use WP_Query;

class SingleProfessor extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'partials.content-single-professor',
    ];

    public function with(): array
    {
        $likeId =$this->getUserLikeId();

        return [
            'programs' => $this->relatedPrograms(),
            'likeCounts' => $this->likeCounts(),
            'isLiked' => $likeId > 0,
            'likeId' => $likeId
        ];
    }

    public function relatedPrograms()
    {
        return get_field('related-programs') ?: null;
    }

    public function likeCounts() {
        $likeCounts = new WP_Query([
            'post_type' => 'like',
            'meta_query' => [
                [
                    'key' => 'liked_professor_id',
                    'compare' => '=',
                    'value' => get_the_ID(),
                ],
            ],
        ]);

        return $likeCounts->found_posts;
    }

    public function getUserLikeId(): bool|int {
        if (!is_user_logged_in()) {
            return false;
        }

        $query = new WP_Query([
            'author' => get_current_user_id(),
            'post_type' => 'like',
            'meta_query' => [
                [
                    'key' => 'liked_professor_id',
                    'compare' => '=',
                    'value' => get_the_ID(),
                ],
            ],
        ]);

        if ($query->found_posts > 0) {
            return $query->posts[0]->ID;
        }

        return false;
    }
}
