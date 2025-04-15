<?php

namespace App\HT\Models;

use WP_Post;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use App\HT\Models\Post;
class Product extends Post
{
    public const POST_TYPE = 'product';
    public const MPN_METAKEY_NAME = '_ht_mpn';
    public const BARCODE_METAKEY_NAME = '_ht_barcode';
    public const VIDEO_METAKEY_NAME = 'video';
    public const PACKAGING_GIFTWRAP_METAKEY_NAME = '_packaging_giftwrap';

    public static function find($id): ?Collection {
        $post = get_post($id);
        return ($post instanceof WP_Post) ? collect([wc_get_product($post)]) : null;
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
            'no_found_rows' => true,
            'update_post_term_cache' => false,
            'update_post_meta_cache' => false,
            'numberposts' => -1
        ]);

        if(!empty($posts)){ $posts = array_map(fn ($post) => wc_get_product($post), $posts); }
        return collect($posts);
    }
}
