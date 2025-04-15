<?php

namespace App\HT\Models;

use WP_Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class Post {

    public const POST_TYPE = 'post';

    public static function find($id): ?Collection {
        $post = get_post($id);
        return ($post instanceof WP_Post) ? collect([$post]) : null;
    }

    public static function findOrFail($id): Collection {
        $post = self::find($id);
        
        if (!$post) {
            throw new ModelNotFoundException("Post with ID {$id} not found.");
        }

        return $post;
    }

    public static function all(): Collection {

        $posts = get_posts([
            'post_type'   => static::POST_TYPE,
            'ignore_sticky_posts' => 1,
            'no_found_rows' => true,
            'update_post_term_cache' => false,
            'update_post_meta_cache' => false,
            'numberposts' => -1
        ]);

        return collect($posts);
    }
}
