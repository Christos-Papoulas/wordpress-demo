<?php

namespace App\View\Composers\Posts;

use Roots\Acorn\View\Composer;

class Post extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'partials.content-single',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'latestPosts' => $this->getLatestPosts(),
        ];
    }

    /**
     * Returns latest posts, excluding itself
     *
     * @return array
     */
    private function getLatestPosts()
    {
        return wp_get_recent_posts(['suppress_filters' => false, 'numberposts' => 10, 'exclude' => [get_queried_object_id()]], 'OBJECT');
    }
}
