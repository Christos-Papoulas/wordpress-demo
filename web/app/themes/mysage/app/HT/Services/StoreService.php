<?php

namespace App\HT\Services;

use App\HT\Models\Store;

class StoreService
{

    public static function initCustomPostType()
    {
        // Stores
        $store_labels = [
            'name' => _x('Stores', 'Post type general name', 'sage'),
            'singular_name' => _x('Store', 'Post type singular name', 'sage'),
            'menu_name' => _x('Stores', 'Admin Menu text', 'sage'),
            'name_admin_bar' => _x('Store', 'Add New on Toolbar', 'sage'),
            'add_new' => __('Add New', 'sage'),
            'add_new_item' => __('Add New Store', 'sage'),
            'new_item' => __('New Store', 'sage'),
            'edit_item' => __('Edit Store', 'sage'),
            'view_item' => __('View Store', 'sage'),
            'all_items' => __('All Stores', 'sage'),
            'search_items' => __('Search Stores', 'sage'),
            'parent_item_colon' => __('Parent Stores:', 'sage'),
            'not_found' => __('No Stores found.', 'sage'),
            'not_found_in_trash' => __('No Stores found in Trash.', 'sage'),
            'featured_image' => _x('Featured Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'sage'),
            'set_featured_image' => _x('Set featured image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'sage'),
            'remove_featured_image' => _x('Remove featured image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'sage'),
            'use_featured_image' => _x('Use as featured image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'sage'),
            'archives' => _x('Store archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'sage'),
            'insert_into_item' => _x('Insert into store', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'sage'),
            'uploaded_to_this_item' => _x('Uploaded to this store', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'sage'),
            'filter_items_list' => _x('Filter Stores list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'sage'),
            'items_list_navigation' => _x('Stores list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'sage'),
            'items_list' => _x('Stores list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'sage'),
        ];
        $store_args = [
            'labels' => $store_labels,
            'public' => true,
            'publicly_queryable' => true,
            'with_front' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'menu_position' => 27,
            'menu_icon' => 'dashicons-store',
            'query_var' => true,
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => false,
            'can_export' => true,
            'show_in_rest' => true, // also enables gutenberg
            'supports' => ['title', 'editor', 'thumbnail', 'page-attributes', 'custom-fields'],
        ];
        register_post_type('store', $store_args);

        $labels = [
            'name' => esc_html__('Categories', 'sage'),
            'singular_name' => esc_html__('Category', 'sage'),
        ];
        $args = [
            'label' => esc_html__('Category', 'sage'),
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'hierarchical' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'store_categories', 'with_front' => true],
            'show_admin_column' => false,
            'show_in_rest' => true,
            'show_tagcloud' => false,
            'rest_base' => 'store_category',
            'rest_controller_class' => 'WP_REST_Terms_Controller',
            'rest_namespace' => 'wp/v2',
            'show_in_quick_edit' => false,
            'sort' => false,
            'show_in_graphql' => true,
            'graphql_single_name' => 'StoreCategory',
            'graphql_plural_name' => 'StoreCategories',
        ];
        register_taxonomy('store_category', ['store'], $args);

        $labels = [
            'name' => esc_html__('Cities', 'sage'),
            'singular_name' => esc_html__('City', 'sage'),
        ];

        $args = [
            'label' => esc_html__('Cities', 'sage'),
            'labels' => $labels,
            'public' => true,
            'publicly_queryable' => true,
            'hierarchical' => false,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_nav_menus' => true,
            'query_var' => true,
            'rewrite' => ['slug' => 'cities', 'with_front' => true],
            'show_admin_column' => false,
            'show_in_rest' => true,
            'show_tagcloud' => false,
            'rest_base' => 'city',
            'rest_controller_class' => 'WP_REST_Terms_Controller',
            'rest_namespace' => 'wp/v2',
            'show_in_quick_edit' => false,
            'sort' => false,
            'show_in_graphql' => true,
            'graphql_single_name' => 'City',
            'graphql_plural_name' => 'Cities',
        ];
        register_taxonomy('city', ['store'], $args);
    }

    /**
     * get stores
     *
     * @param  array  $query_params  Query params for WP_Query
     * @return mixed
     */
    public static function getStores(array $query_params = []): mixed
    {
        $defaults = [
            'post_type' => 'store',
            'post_status' => 'publish',
            'ignore_sticky_posts' => 1,
            'update_post_term_cache' => false,
            'update_post_meta_cache' => false,
            'no_found_rows' => true,
            'posts_per_page' => -1,
            'orderby' => 'name',
            'order' => 'ASC',
        ];

        $query_args = array_merge($defaults, $query_params);

        $results = new \WP_Query($query_args);

        return $results->posts;
    }

    /**
     * Show store name if order shipping method is localpickup
     *
     * @param  string  $shipping_to_display
     * @param  WC_Order  $order
     * @return string
     */
    public static function maybeShowStoreName(string $shipping_to_display, \WC_Order $order): string
    {

        $shipping_items = $order->get_items('shipping');
        if (is_array($shipping_items) && ! empty($shipping_items)) {
            foreach ($shipping_items as $item_id => $shipping_item) {

                if ($shipping_item->get_method_id() == 'local_pickup') {
                    $stores = self::getStores(['post__in' => [$order->get_meta(Store::METAKEY_NAME)], 'posts_per_page' => 1]);
                    if (empty($stores)) {
                        return $shipping_to_display;
                    }

                    return $shipping_to_display.' '.$stores[0]->post_title;
                }
                break;
            }
        }

        return $shipping_to_display;
    }

    /**
     * Saves the pick up store if local pickup is selected as shipping method.
     *
     * @param  int  $order_id
     * @param  array  $data
     * @return void
     */
    public static function save_store_field(int $order_id, array $data): void
    {
        $order = wc_get_order($order_id);

        if ($order) {
            // dd($_POST[Store::INPUT_NAME]);
            $shipping_items = $order->get_items('shipping');
            if (is_array($shipping_items) && ! empty($shipping_items)) {
                foreach ($shipping_items as $item_id => $shipping_item) {
                    if ($shipping_item->get_method_id() == 'local_pickup' && isset($_POST[Store::INPUT_NAME])) {
                        $order->update_meta_data(Store::METAKEY_NAME, sanitize_text_field($_POST[Store::INPUT_NAME]));
                        $order->save();
                    }
                    break;
                }
            }
        }
    }

    /**
     * Adds 'Store' column header to 'Orders' page.
     *
     *
     * @since 1.0.0
     *
     * @param  array  $columns
     * @return array
     */
    public static function addStoreColumnHeader(array $columns): array
    {

        $new_columns = [];

        foreach ($columns as $column_name => $column_info) {

            $new_columns[$column_name] = $column_info;

            if ($column_name === 'shipping_address') {

                $new_columns['store'] = __('Store', 'sage');
            }
        }

        return $new_columns;
    }

    /**
     * Adds Store column content to 'Orders' page.
     *
     * @internal
     *
     * @since 1.0.0
     *
     * @param  string  $column  name of column being displayed
     * @return void
     */
    public static function addStoreColumnContent(string $column): void
    {
        global $post;

        $output = '&ndash;';

        if ($column === 'store') {

            $order = wc_get_order($post->ID);

            if (($order instanceof \WC_Order || $order instanceof \WC_Order_Refund) && ! $order instanceof \WC_Subscription) {
                $store_id = $order->get_meta(Store::METAKEY_NAME);
                if (! empty($store_id)) {
                    // WPML
                    if (defined('ICL_SITEPRESS_VERSION')) {
                        $store_id = apply_filters('wpml_object_id', $store_id, 'store');
                    }
                    $stores = self::getStores(['post__in' => [(int) $store_id], 'posts_per_page' => 1]);
                    $output = $stores[0]->post_title;
                }
            }

            echo $output;
        }
    }

    /**
     * Adds Store column content to 'Orders' page.
     *
     *
     * @since 1.0.0
     *
     * @param  string  $column  name of column being displayed
     * @param  WC_Order  $order
     */
    public static function addStoreColumnContentHpos(string $column, \WC_Order $order)
    {
        global $post;

        $output = '&ndash;';

        if ($column === 'store') {

            if (($order instanceof \WC_Order || $order instanceof \WC_Order_Refund) && ! $order instanceof \WC_Subscription) {
                $store_id = $order->get_meta(Store::METAKEY_NAME);
                if (! empty($store_id)) {
                    // WPML
                    if (defined('ICL_SITEPRESS_VERSION')) {
                        $store_id = apply_filters('wpml_object_id', $store_id, 'store');
                    }
                    $stores = self::getStores(['post__in' => [(int) $store_id], 'posts_per_page' => 1]);
                    $output = $stores[0]->post_title;
                }
            }

            echo $output;
        }
    }

    /**
     * Adds CSS to style the Store column.
     *
     *
     * @since 1.0.0
     * @return void
     */
    public static function storeColumnStyles(): void
    {
        $screen = get_current_screen();

        if ($screen && $screen->id === 'edit-shop_order') {

            ?>
			<style type="text/css">
				.widefat .column-store {
					width: 11%;
				}
			</style>
			<?php

        }
    }

    /**
     * Get a special composite field for handling order shipping item pickup data.
     *
     * @since 1.0.0
     *
     * @param  int  $item_id  order shipping item ID
     * @param  array  $item  order shipping item array
     */
    public static function output_order_shipping_item_pickup_data_field(int $item_id, array $item)
    {
        global $post, $theorder;

        if (WoocommerceService::isHposEnabled()) {
            $order = $theorder;
        } else {
            $order = wc_get_order($post);
        }

        if (empty($order) && ! empty($_POST['order_id']) && wp_doing_ajax()) {
            $order = wc_get_order($_POST['order_id']);
        }

        $shipping_method = $item['method_id'] ?? null;

        if ($order instanceof \WC_Order && ! $order instanceof \WC_Subscription && ($shipping_method === 'local_pickup')) {
            ?>
			<div>
				<table class="display_meta">
                    <tbody>

						<tr>
							<th>
                                <label>
                                    <?php echo __('Store', 'sage') . ':'; ?>
                                </label>
                            </th>
							<td>
								<?php
                                    $store_id = $order->get_meta(Store::METAKEY_NAME);
            if (! empty($store_id)) {
                // WPML
                if (defined('ICL_SITEPRESS_VERSION')) {
                    $store_id = apply_filters('wpml_object_id', $store_id, 'store');
                }
                $stores = self::getStores(['post__in' => [(int) $store_id], 'posts_per_page' => 1]);
                echo $stores[0]->post_title;
            }
            ?>
							</td>
						</tr>

					</tbody>
				</table>
			</div>
			<?php
        }
    }
}
