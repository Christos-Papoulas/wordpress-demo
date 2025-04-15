<?php

/*
|--------------------------------------------------------------------------
| Theme Setup
|--------------------------------------------------------------------------
|
|  Setup our theme features
|
*/

namespace App;

use App\HT\Services\InvoiceService;
use App\HT\Services\PostCodesService;
use App\HT\Services\Product\MediaService;
use App\HT\Services\Product\ProductBackInStockService;
use App\HT\Services\Product\ProductService;
use App\HT\Services\SetupService;
use App\HT\Services\ShortcodeBlockService;
use App\HT\Services\StoreService;
use App\HT\Services\WoocommerceService;
use Illuminate\Support\Facades\Vite;


/*
|--------------------------------------------------------------------------
| Load text domain
|--------------------------------------------------------------------------
|
*/
load_theme_textdomain('sage', get_theme_file_path('resources/lang'));
add_action('wp_enqueue_scripts', function () {

    $theme = wp_get_theme();
    $public_asset_path = get_theme_file_uri('public/build/assets/');

    // Styles
    wp_enqueue_style('magiczoomplus', $public_asset_path . 'magiczoomplus.css', [], $theme->get('Version'));
    wp_enqueue_style('mobiscroll', $public_asset_path . 'mobiscroll.javascript.min.css', [], $theme->get('Version'));

    // Scripts
    wp_enqueue_script('wp-i18n');
    wp_enqueue_script('magiczoomplus', $public_asset_path . 'magiczoomplus.js', [], $theme->get('Version'), true);
    wp_enqueue_script('mobiscroll', $public_asset_path . 'mobiscroll.javascript.min.js', [], $theme->get('Version'), true);
});

/*
|--------------------------------------------------------------------------
| Greek zip codes custom table
|--------------------------------------------------------------------------
|
*/
add_action('after_switch_theme', [PostCodesService::class, 'createTable']); // Fires on the next WP load after the theme has been switched.

/*
|--------------------------------------------------------------------------
| External Product Images Feature for woocommerce
|--------------------------------------------------------------------------
|
*/
add_action('wp_head', [MediaService::class, 'createLcpImage']);
if (config('theme.products.imagesFetchingMethod', 'native') == 'internal') {
    add_action('after_switch_theme', [MediaService::class, 'createUploadsFolder']);
}
if (config('theme.products.imagesFetchingMethod', 'native') != 'native') {
    add_filter('woocommerce_product_variation_get_image_id', [MediaService::class, 'woocommerce_product_variation_get_image_id'], 10, 2);
    add_filter('woocommerce_product_get_image_id', [MediaService::class, 'woocommerce_product_get_image_id'], 10, 2);
    add_filter('post_thumbnail_id', [MediaService::class, 'post_thumbnail_id'], 10, 2);
    add_filter('wp_get_attachment_image_src', [MediaService::class, 'wp_get_attachment_image_src'], 10, 4);

    // Yoast SEO plugin
    add_filter( 'wpseo_twitter_image', function( $image ) {
        if ( is_product() ) {
            global $product;
            $image = MediaService::wp_get_attachment_image_src($image, $product->get_id(), 'full', false)[0] ?? '';
        }
        return $image;
    });
    add_filter( 'wpseo_add_opengraph_images', function( $image_container ) {
        if ( is_product() ) {
            global $product;

            $url = MediaService::wp_get_attachment_image_src( '', $product->get_id(), 'full', false)[0] ?? '';
            $image_container->add_image_by_url( $url );
        }
        return $image_container;
    });
}

/*
|--------------------------------------------------------------------------
| Add Custom Email Classes
|--------------------------------------------------------------------------
|
*/
add_filter('woocommerce_email_classes', [SetupService::class, 'addCustomWooCommerceEmailClasses']);

/*
|--------------------------------------------------------------------------
| Back in stock feature for woocoomerce
|--------------------------------------------------------------------------
|
*/
// add_action('after_switch_theme', [ProductBackInStockService::class,'createTable']); // Fires on the next WP load after the theme has been switched.
// add_action( 'woocommerce_product_set_stock_status', [ProductBackInStockService::class,'action_based_on_stock_status'], 999, 3 );
// add_action( 'woocommerce_variation_set_stock_status', [ProductBackInStockService::class,'action_based_on_stock_status'], 999, 3 );

/*
|--------------------------------------------------------------------------
| Stores feature for woocoomerce
|--------------------------------------------------------------------------
|
*/
add_action('init', [StoreService::class, 'initCustomPostType']);
add_filter('woocommerce_order_shipping_to_display', [StoreService::class, 'maybeShowStoreName'], 10, 2);
add_action('woocommerce_checkout_update_order_meta', [StoreService::class, 'save_store_field'], 10, 2);

if (WoocommerceService::isHposEnabled()) {
    // add a Stores column to the orders edit screen
    add_filter('woocommerce_shop_order_list_table_columns', [StoreService::class, 'addStoreColumnHeader'], 20);
    add_action('manage_woocommerce_page_wc-orders_custom_column', [StoreService::class, 'addStoreColumnContentHpos'], 10, 2);

    // filter orders by pickup locations
    // add_action( 'woocommerce_order_list_table_restrict_manage_orders',        [ $this, 'add_pickup_locations_filter' ], 20 );
    // add_filter( 'woocommerce_shop_order_list_table_prepare_items_query_args', [ $this, 'filter_orders_by_locations' ], 999 );
} else {
    // add a Stores column to the orders edit screen
    add_filter('manage_edit-shop_order_columns', [StoreService::class, 'addStoreColumnHeader'], 20);
    add_action('manage_shop_order_posts_custom_column', [StoreService::class, 'addStoreColumnContent']);

    // filter orders by pickup locations
    // add_action( 'restrict_manage_posts', [ $this, 'add_pickup_locations_filter' ], 20 );
    // add_filter( 'request',               [ $this, 'filter_orders_by_locations' ], 999 );
}
add_action('admin_head', [StoreService::class, 'storeColumnStyles']);
// add a Pickup Location field for each shipping item to edit the Pickup Location ID
add_action('woocommerce_before_order_itemmeta', [StoreService::class, 'output_order_shipping_item_pickup_data_field'], 1, 2);

/*
|--------------------------------------------------------------------------
| Invoice feature for woocommerce
|--------------------------------------------------------------------------
|
*/
add_filter('woocommerce_checkout_fields', [InvoiceService::class, 'add_checkout_fields']);
add_action('woocommerce_after_checkout_validation', [InvoiceService::class, 'validate_invoice_fields'], 10, 2);
add_action('woocommerce_checkout_update_order_meta', [InvoiceService::class, 'save_invoice_fields'], 10, 2);
add_filter('woocommerce_order_formatted_billing_address', [InvoiceService::class, 'add_invoice_fields_to_formatted_address'], 10, 2);
add_filter('woocommerce_formatted_address_replacements', [InvoiceService::class, 'add_invoice_fields_to_formatted_address_replacements'], 10, 2);
add_filter('woocommerce_localisation_address_formats', [InvoiceService::class, 'add_invoice_add_localization_address_format'], 10, 1);
add_action('woocommerce_admin_order_data_after_order_details', [InvoiceService::class, 'woocommerce_admin_order_data_after_order_details']);
add_action('woocommerce_admin_order_actions_end', [InvoiceService::class, 'add_invoice_meta'], 10, 1);

/*
|--------------------------------------------------------------------------
| Custom Gutenberg blocks feature
|--------------------------------------------------------------------------
|
*/
add_action('init', [ShortcodeBlockService::class, 'initCustomPostType']);
add_filter('manage_shortcodeblock_posts_columns', [ShortcodeBlockService::class, 'manageColumns']);
add_action('manage_shortcodeblock_posts_custom_column', [ShortcodeBlockService::class, 'customColumn'], 10, 2);
add_filter('manage_edit-shortcodeblock_sortable_columns', [ShortcodeBlockService::class, 'sortableColumns']);
add_shortcode('shortcodeblock', [ShortcodeBlockService::class, 'createShortcode']);

/*
|--------------------------------------------------------------------------
| Advanced Custom Fields Plugin
|--------------------------------------------------------------------------
|
*/
add_filter('acf/settings/show_admin', '__return_false');
add_action('acf/init', function () {
    acf_update_setting('google_api_key', config('theme.googleMapsApiKey', ''));
});
add_filter('acf/fields/wysiwyg/toolbars', [SetupService::class, 'addToolbars']);
add_filter('acf/get_valid_field', [SetupService::class, 'removeFromHtToolbar']);

/*
|--------------------------------------------------------------------------
| General Theme preferences
|--------------------------------------------------------------------------
|
*/
add_filter('woocommerce_enqueue_styles', '__return_empty_array');
add_filter('wp_check_filetype_and_ext', [SetupService::class, 'extendFiletypes'], 10, 4);
add_filter('upload_mimes', [SetupService::class, 'cc_mime_types']);
add_filter('login_headertext', [SetupService::class, 'get_login_title']);
add_filter('login_headerurl', [SetupService::class, 'get_login_url']);
add_filter('wp_default_scripts', [SetupService::class, 'remove_jquery_migrate']);
remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
remove_action('wp_body_open', 'wp_global_styles_render_svg_filters');
add_filter('block_categories_all', [SetupService::class, 'addCustomCategoriesForGutenbergBlocks']);
add_filter('oembed_response_data', [SetupService::class, 'removeAuthorName']);
add_filter('tiny_mce_before_init', [SetupService::class, 'addAppColorsToTinyMceEditor']);
add_filter('excerpt_more', [SetupService::class, 'addContinueToExcerpt']);
add_filter('woocommerce_order_actions', [SetupService::class, 'ht_show_thank_you_page_order_admin_actions'], 9999, 2);
add_action('woocommerce_order_action_view_thankyou', [SetupService::class, 'ht_redirect_thank_you_page_order_admin_actions']);

/*
|--------------------------------------------------------------------------
| Add custom fields to attributes
|--------------------------------------------------------------------------
|
*/
add_action('woocommerce_after_add_attribute_fields', [ProductService::class, 'edit_wc_attribute_display_type']);
add_action('woocommerce_after_edit_attribute_fields', [ProductService::class, 'edit_wc_attribute_display_type']);
add_action('woocommerce_attribute_added', [ProductService::class, 'save_wc_attribute_display_type']);
add_action('woocommerce_attribute_updated', [ProductService::class, 'save_wc_attribute_display_type']);
add_action('woocommerce_attribute_deleted', [ProductService::class, 'delete_wc_attribute_display_type']);

/*
|--------------------------------------------------------------------------
| Add custom fields to products
|--------------------------------------------------------------------------
|
*/
add_action('woocommerce_product_options_sku', [ProductService::class, 'addCustomOptionsToProducts']);
add_action('woocommerce_process_product_meta', [ProductService::class, 'saveProductCustomOptions']);
add_action('woocommerce_variation_options', [ProductService::class, 'addCustomOptionsToVariations'], 10, 3);
add_action('woocommerce_save_product_variation', [ProductService::class, 'saveVariationsCustomOptions'], 10, 2);
// add_action('woocommerce_product_options_shipping_product_data', [ProductService::class,'addCustomShippingOptionsToProducts']);

/**
 * Redirect after user registration
 *
 * @return string
 */
add_action('woocommerce_registration_redirect', function () {
    return get_permalink(get_option('woocommerce_myaccount_page_id'));
}, 2);

/**
 * Change how many variations can be loaded. For example the available variations in single product page.
 *
 * @param  int  $count
 * @return int
 */
add_filter('woocommerce_ajax_variation_threshold', function ($count) {
    return 100;
}, 10, 1);

/**
 * hide out of stock products from all wc queries
 *
 * @param  array  $meta_query
 * @return array
 */
add_filter('woocommerce_product_query_meta_query', function ($meta_query) {
    if (get_option('woocommerce_hide_out_of_stock_items') === 'yes') {
        $meta_query[] = [
            'relation' => 'AND',
            [
                'key' => '_stock_status',
                'value' => 'outofstock',
                'compare' => '!=',
            ],
        ];
    }

    return $meta_query;
}, 10, 1);

/**
 * Enable search with skus for acf fields with key_name = products
 *
 * @param  array  $args
 * @param  string  $field
 * @param  int  $post_id
 *
 * @see https://www.advancedcustomfields.com/resources/acf-fields-post_object-query/
 */
add_filter('acf/fields/post_object/query/name=products', function ($args, $field, $post_id) {
    if ($args['s'] == '') {
        // nothing passed in, so just return $args as it stands and get out of here.
        return $args;
    }
    // check for posts using $args
    $result = new \WP_Query($args);
    if ($result->found_posts == 0) {

        // no posts found for the query, so it might be a sku... take a look there?
        $args['meta_query'] = [
            [
                'key' => '_sku',
                'value' => $args['s'],
                'compare' => 'like',
            ],
        ];
        $args['posts_per_page'] = -1;
        $args['s'] = '';

    }

    return $args;
}, 10, 3);

/**
 * This filter fixes the bug where price_html is empty if product variables are exactly the same price
 * At our theme this can be an issue at single product page. Discounts for each variation will not show.
 *
 * @see https://github.com/woocommerce/woocommerce/issues/11827
 *
 * @return string
 */
add_filter('woocommerce_show_variation_price', '__return_true');

/**
 * Inject styles into the block editor.
 *
 * @return array
 */
add_filter('block_editor_settings_all', function ($settings) {
    $style = Vite::asset('resources/css/editor.css');

    $settings['styles'][] = [
        'css' => "@import url('{$style}')",
    ];

    return $settings;
});

/**
 * Inject scripts into the block editor.
 *
 * @return void
 */
add_filter('admin_head', function () {
    if (! get_current_screen()?->is_block_editor()) {
        return;
    }

    $dependencies = json_decode(Vite::content('editor.deps.json'));

    foreach ($dependencies as $dependency) {
        if (! wp_script_is($dependency)) {
            wp_enqueue_script($dependency);
        }
    }

    echo Vite::withEntryPoints([
        'resources/js/editor.js',
    ])->toHtml();
});

/**
 * Use the generated theme.json file.
 *
 * @return string
 */
add_filter('theme_file_path', function ($path, $file) {
    return $file === 'theme.json'
        ? public_path('build/assets/theme.json')
        : $path;
}, 10, 2);

/**
 * Register the initial theme setup.
 *
 * @return void
 */
add_action('after_setup_theme', function () {

    /**
     * Add Woocommerce Support
     */
    add_theme_support('woocommerce');

    /* filter added to prevent automatic regeneration of thumbnail images and causing server hikes */
    // add_filter('woocommerce_background_image_regeneration', '__return_false');
    remove_image_size('2048x2048');
    remove_image_size('1536x1536');
    remove_image_size('large');
    remove_image_size('medium_large');
    remove_image_size('medium');

    /* Shop Thumbnails */
    // add_filter('woocommerce_get_image_size_thumbnail', function ($size) {
    //     return [
    //         'width' => 800,
    //         'height' => 1000,
    //         'crop' => 0,
    //     ];
    // });

    // /* Shop Single Product Image Size */
    // add_filter('woocommerce_get_image_size_single', function ($size) {
    //     return [
    //         'width' => 800,
    //         'height' => 1000,
    //         'crop' => 0,
    //     ];
    // });

    // /* Single Product Gallery Thunmbnails */
    // add_filter('woocommerce_get_image_size_gallery_thumbnail', function ($size) {
    //     return [
    //         'width' => 800,
    //         'height' => 1000,
    //         'crop' => 0,
    //     ];
    // });

    /**
     * Disable full-site editing support.
     *
     * @link https://wptavern.com/gutenberg-10-5-embeds-pdfs-adds-verse-block-color-options-and-introduces-new-patterns
     */
    remove_theme_support('block-templates');

    /**
     * Enable block styles support.
     *
     * @link https://wptavern.com/gutenberg-10-5-embeds-pdfs-adds-verse-block-color-options-and-introduces-new-patterns
     */
    add_theme_support('wp-block-styles');

    /**
     * Reads theme.json and generates the necessary CSS for colors and bg colors.
     */
    add_action('wp_head', function () {
        $theme_json_path = get_theme_file_path('/theme.json');

        if (file_exists($theme_json_path)) {
            $theme_json = json_decode(file_get_contents($theme_json_path), true);
            $colors = $theme_json['settings']['color']['palette'] ?? [];

            if (! empty($colors)) {
                echo '<style>';
                foreach ($colors as $color) {
                    $slug = sanitize_title($color['slug']); // Ensure it's a safe class name
                    $hex = $color['color'];

                    // Generate CSS classes for text and background colors
                    echo ".has-{$slug}-color { color: {$hex}; }";
                    echo ".has-{$slug}-background-color { background-color: {$hex}; }";
                }
                echo '</style>';
            }
        }
    });

    /**
     * Register the navigation menus.
     *
     * @link https://developer.wordpress.org/reference/functions/register_nav_menus/
     */
    register_nav_menus([
        'topbar_navigation' => __('Top Bar Navigation', 'sage'),
        'primary_navigation' => __('Primary Navigation', 'sage'),
        'bottom_footer_navigation' => __('Bottom Footer Navigation', 'sage'),
    ]);

    /**
     * Disable the default block patterns.
     *
     * @link https://developer.wordpress.org/block-editor/developers/themes/theme-support/#disabling-the-default-block-patterns
     */
    remove_theme_support('core-block-patterns');

    /**
     * Enable plugins to manage the document title.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#title-tag
     */
    add_theme_support('title-tag');

    /**
     * Enable post thumbnail support.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support('post-thumbnails');

    /**
     * Enable responsive embed support.
     *
     * @link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#responsive-embedded-content
     */
    add_theme_support('responsive-embeds');

    /**
     * Enable HTML5 markup support.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#html5
     */
    add_theme_support('html5', [
        'caption',
        'comment-form',
        'comment-list',
        'gallery',
        'search-form',
        'script',
        'style',
    ]);

    /**
     * Enable selective refresh for widgets in customizer.
     *
     * @link https://developer.wordpress.org/reference/functions/add_theme_support/#customize-selective-refresh-widgets
     */
    add_theme_support('customize-selective-refresh-widgets');
}, 20);

/**
 * Register the theme sidebars.
 *
 * @return void
 */
add_action('widgets_init', function () {
    $config = [
        'before_widget' => '<section class="widget %1$s %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3>',
        'after_title' => '</h3>',
    ];

    register_sidebar([
        'name' => __('Footer Col 1', 'sage'),
        'id' => 'sidebar-footer-1',
    ] + $config);
    register_sidebar([
        'name' => __('Footer Col 2', 'sage'),
        'id' => 'sidebar-footer-2',
    ] + $config);
    register_sidebar([
        'name' => __('Footer Col 3', 'sage'),
        'id' => 'sidebar-footer-3',
    ] + $config);
    register_sidebar([
        'name' => __('Footer Col 4', 'sage'),
        'id' => 'sidebar-footer-4',
    ] + $config);
    register_sidebar([
        'name' => __('Footer Col 5', 'sage'),
        'id' => 'sidebar-footer-5',
    ] + $config);
    register_sidebar([
        'name' => __('Footer Col 6', 'sage'),
        'id' => 'sidebar-footer-6',
    ] + $config);
});
