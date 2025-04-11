<?php

namespace App\HT\Services\Filter\Post;

use App\HT\Services\Filter\QueryService;
use BadMethodCallException;

class FilterService
{
    /**
     * Returns facetes for alpine shop.
     *
     * @param  $_POST['archive_for']
     * @return array
     */
    public static function getFacetesForPosts()
    {
        $post_type = $_POST['post_type'] ?? 'post';
        $facetes = config('wordpress.'.$post_type.'.facetes');

        if (empty($facetes)) {
            wp_send_json_success([
                'facetes' => [],
            ], 200);
        }

        // error_log(print_r($facetes,true));
        $archive_for = json_decode(stripslashes($_POST['archive_for']));
        $getSubCategories = $_POST['get_subcats'] ?? false;

        // Set a unique cache key for this query based on the current taxonomy and post IDs
        $cache_key = 'ht_posts_facetes_result'.md5(implode_recursive(',', $facetes).'_'.$_POST['archive_for']);

        // Check if the query results are already in the cache
        $cached_facetes = wp_cache_get($cache_key, 'ht_posts_facetes');
        // error_log(print_r($cached_facetes,true));
        if ($cached_facetes !== false) {
            wp_send_json_success([
                'facetes' => $cached_facetes['facetes'],
            ], 200);
        }

        // Get the visible posts of the page
        if (isset($archive_for)) {

            $query = [
                'post_type' => $post_type,
                'fields' => 'ids',
                'post_status' => 'publish',
                'ignore_sticky_posts' => 1,
                'posts_per_page' => -1,
                'tax_query' => [
                    [
                        'taxonomy' => $archive_for->taxonomy,
                        'field' => 'term_id',
                        'terms' => $archive_for->term->term_id,
                    ],
                ],
            ];

        } else {

            $query = [
                'post_type' => $post_type,
                'fields' => 'ids',
                'post_status' => 'publish',
                'ignore_sticky_posts' => 1,
                'posts_per_page' => -1,
            ];

        }
        $results = new \WP_Query($query);
        $post_ids = $results->posts;
        // error_log(print_r($post_ids,true));

        // Get facetes
        if (empty($post_ids)) {
            $facetes = [];
        } else {

            // Get facetes of visible posts
            foreach ($facetes as $key => $facete) {
                if ($facete['type'] != 'taxonomy') {
                    continue;
                }

                $tax = $facete['taxonomy'];

                $terms = [];

                if (isset($archive_for)) {
                    if ($tax == $archive_for->taxonomy) {
                        $terms = get_terms([
                            'taxonomy' => $tax,
                            'fields' => 'all',
                            'hide_empty' => true,
                            'object_ids' => $post_ids,
                            'parent' => $archive_for->term->term_id,
                            'orderby' => 'menu_order',
                            'order' => 'ASC',
                        ]);
                    } else {
                        $terms = get_terms([
                            'taxonomy' => $tax,
                            'fields' => 'all',
                            'hide_empty' => true,
                            'object_ids' => $post_ids,
                            'orderby' => 'menu_order',
                            'order' => 'ASC',
                        ]);
                    }
                } else {
                    $terms = get_terms([
                        'taxonomy' => $tax,
                        'fields' => 'all',
                        'hide_empty' => true,
                        'object_ids' => $post_ids,
                        'orderby' => 'menu_order',
                        'order' => 'ASC',
                    ]);
                }

                // Remove sanitized chars
                $terms = TransformService::stringReplaceTermNames($terms);
                // error_log(print_r($terms,true));

                $facetes[$key]['terms'] = $terms;

                // Transform terms . Get categories images etc.
                if ($getSubCategories) {

                    $terms = array_map(function ($term) use ($tax, $post_type) {
                        return TransformService::transformTerm($term, $tax, $post_type);
                    }, $terms);

                }

            }
        }

        // Save the query results to the cache for next time
        wp_cache_set($cache_key, ['facetes' => $facetes], 'ht_posts_facetes', 3600);

        wp_send_json_success([
            'facetes' => $facetes,
        ], 200);
    }

    /**
     * Returns posts for alpine
     *
     * @param  $_POST['archive_for']
     * @param  $_POST['active_filters']
     * @param  $_POST['page'];
     * @param  $_POST['orderBy']
     * @return array
     */
    public static function getPostsForAlpine()
    {
        $post_type = $_POST['post_type'] ?? 'post';
        $activeFilters = json_decode(stripslashes($_POST['active_filters']));

        $page = $_POST['page'];

        $per_page = config('wordpress.'.$post_type.'.posts-per-page', get_option('posts_per_page'));
        $query_args = [
            'post_type' => $post_type,
            'post_status' => 'publish',
            'ignore_sticky_posts' => config('wordpress.'.$post_type.'.posts-ignore-sticky-posts', 1),
            'posts_per_page' => $per_page,
            'paged' => $page,
        ];

        $archive_for = json_decode(stripslashes($_POST['archive_for']));
        // if we are in an archive page eg. product_cat
        if (isset($archive_for)) {
            $query_args['tax_query'][] = [
                'taxonomy' => $archive_for->taxonomy,
                'field' => 'id',
                'terms' => $archive_for->term->term_id,
                'operator' => 'IN', // Possible values are 'IN', 'NOT IN', 'AND'.
            ];
        }

        // meta query
        $query_args['meta_query'] = [];

        // from filters to query args
        foreach ($activeFilters as $key => $activeFilter) {
            $functionName = 'add_query_args_for_'.$key;
            if (method_exists(QueryService::class, $functionName)) {
                $query_args = call_user_func([QueryService::class, $functionName], $key, $activeFilter, $query_args);
            } else {
                $reflectionClass = new \ReflectionClass(QueryService::class);
                $namespace = $reflectionClass->getNamespaceName();
                throw new BadMethodCallException("Method [$functionName] does not exist on [".$namespace."\Filters] class.");
            }
        }

        $defaultOrder = config('wordpress.'.$post_type.'.default-order', [
            'orderby' => 'menu_order title',
            'order' => 'ASC',
        ]);
        $query_args = QueryService::addOrderBy($_POST['orderBy'], $defaultOrder, $query_args);

        // TODO: set a 30 minute transient for caching
        // $new_query_transient_key = md5(json_encode($query_args));
        // $results = get_transient($new_query_transient_key);
        // if (false === $results) {
        //     $results = new \WP_Query($query_args);
        //     set_transient($new_query_transient_key, $results, 300);
        // }
        $results = new \WP_Query($query_args);

        if ($results->have_posts()) {
            $posts = array_map(function ($post) use ($post_type) {
                return TransformService::transformPost($post, $post_type);
            }, $results->posts);
        } else {
            $posts = [];
        }

        wp_send_json_success([
            'current_page' => $page,
            'max_page' => (int) $results->max_num_pages,
            'total_posts' => $results->found_posts,
            'postsPerPage' => $per_page,
            'posts' => $posts,
        ], 200);
    }
}
