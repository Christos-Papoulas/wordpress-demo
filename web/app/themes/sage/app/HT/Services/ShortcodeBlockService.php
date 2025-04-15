<?php

namespace App\HT\Services;

class ShortcodeBlockService
{
    public static function initCustomPostType()
    {
        // Shortcode Blocks
        $labels = [
            'name' => _x('Shortcode Blocks', 'Post type general name', 'sage'),
            'singular_name' => _x('Shortcode Block', 'Post type singular name', 'sage'),
            'menu_name' => _x('Shortcode Blocks', 'Admin Menu text', 'sage'),
            'name_admin_bar' => _x('Shortcode Block', 'Add New on Toolbar', 'sage'),
            'add_new' => __('Add New', 'sage'),
            'add_new_item' => __('Add New Shortcode Block', 'sage'),
            'new_item' => __('New Shortcode Block', 'sage'),
            'edit_item' => __('Edit Shortcode Block', 'sage'),
            'view_item' => __('View Shortcode Block', 'sage'),
            'all_items' => __('All Shortcode Blocks', 'sage'),
            'search_items' => __('Search Shortcode Blocks', 'sage'),
            'parent_item_colon' => __('Parent Shortcode Blocks:', 'sage'),
            'not_found' => __('No Shortcode Blocks found.', 'sage'),
            'not_found_in_trash' => __('No Shortcode Blocks found in Trash.', 'sage'),
            'featured_image' => _x('Featured Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'sage'),
            'set_featured_image' => _x('Set featured image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'sage'),
            'remove_featured_image' => _x('Remove featured image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'sage'),
            'use_featured_image' => _x('Use as featured image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'sage'),
            'archives' => _x('Shortcode Block archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'sage'),
            'insert_into_item' => _x('Insert into shortcode block', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'sage'),
            'uploaded_to_this_item' => _x('Uploaded to this shortcode block', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'sage'),
            'filter_items_list' => _x('Filter Shortcode Blocks list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'sage'),
            'items_list_navigation' => _x('Shortcode Blocks list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'sage'),
            'items_list' => _x('Shortcode Blocks list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'sage'),
        ];
        $args = [
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => false, // keep it false, we dont want single post page.
            'with_front' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'menu_position' => 27,
            'menu_icon' => 'dashicons-welcome-widgets-menus',
            'query_var' => true,
            'capability_type' => 'post',
            'has_archive' => false,
            'hierarchical' => false,
            'can_export' => true,
            'show_in_rest' => true, // also enables gutenberg
            'supports' => ['title', 'editor', 'custom-fields'],
        ];
        register_post_type('shortcodeblock', $args);
    }

    /**
     * Add columns to admin page for the custom post type
     *
     * @param  array  $columns
     */
    public static function manageColumns($columns): array
    {
        $columns['post_id'] = __('Post ID');
        $columns['shortcode'] = __('Shortcode');

        return $columns;
    }

    /**
     * Add columns to admin page for the custom post type
     *
     * @param  array  $columns
     * @param  int  $post_id
     * @return void
     */
    public static function customColumn($column, $post_id)
    {
        if ($column === 'post_id') {
            echo $post_id;
        }
        if ($column === 'shortcode') {
            echo '[shortcodeblock id='.$post_id.']';
        }
    }

    /**
     * Make custom columns stortable
     *
     * @param  array  $columns
     */
    public static function sortableColumns($columns): array
    {
        $columns['post_id'] = 'post_id';
        $columns['shortcode'] = 'shortcode';

        return $columns;
    }

    /**
     * Create shortcode
     *
     * @param  array  $atts
     */
    public static function createShortcode($atts): string
    {
        $default = [
            'id' => null,
        ];
        $a = shortcode_atts($default, $atts);

        if (empty($a['id'])) {
            return '';
        }

        $content = get_the_content(null, false, $a['id']);
        $content = apply_filters('the_content', $content);
        $content = str_replace(']]>', ']]&gt;', $content);

        return $content;
    }
}
