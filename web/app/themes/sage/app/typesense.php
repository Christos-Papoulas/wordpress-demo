<?php

namespace App;

use App\HT\Models\Product;
use App\HT\Services\Product\ProductService;

/*
|--------------------------------------------------------------------------
| Typesense hooks
|--------------------------------------------------------------------------
|
| Here you can place hooks for typesense
|
*/

/*
|--------------------------------------------------------------------------
| Add product data to collection
|--------------------------------------------------------------------------
|
| Add product data to collection. These data are available to the instant-search-hits.php template
|
*/
add_filter('cm_tsfwc_data_before_entry', function ($formatted_data, $raw_data, $object_id, $schema_name) {
    $product = $raw_data;

    $formatted_data['productCardData'] = ProductService::createProductCardData($product);

    // extra properties only for typesense
    $barcodes = [];
    if ($productCardData['type'] == 'variable') {
        $variation_ids = $product->get_children();
        foreach ($variation_ids as $variation_id) {
            $barcodes[] = get_post_meta($variation_id, Product::BARCODE_METAKEY_NAME, true);
        }
    } else {
        $barcodes = [get_post_meta($product->get_ID(), Product::BARCODE_METAKEY_NAME, true)];
    }
    $formatted_data['barcodes'] = implode(',', $barcodes);
    $formatted_data['is_purchasable'] = $product->is_purchasable();

    // custom group filter
    $formatted_data['custom_group'] = [];
    $product_language = apply_filters('wpml_element_language_code', null, [
        'element_id' => $product->get_ID(),
        'element_type' => 'post_product',
    ]);
    if ($product->is_on_sale()) {
        $formatted_data['custom_group'][] = apply_filters('wpml_translate_single_string', 'Προσφορές', 'sage', 'Προσφορές', $product_language);
    }
    $new_in_dates_to = $product->get_meta('_new_in_dates_to');
    if ($new_in_dates_to && $new_in_dates_to !== '') {
        $formatted_data['new_in_badge'] = true;
        $formatted_data['custom_group'][] = apply_filters('wpml_translate_single_string', 'Νέες Αφίξεις', 'sage', 'Νέες Αφίξεις', $product_language);
    }
    // custom group filter

    // error_log('$schema_name = ' .$schema_name);
    // error_log(print_r($formatted_data,true));
    return $formatted_data;
}, 10, 4);

/*
|--------------------------------------------------------------------------
| Add a custom prefix
|--------------------------------------------------------------------------
|
| Add a custom prefix to fix the issue with routing feature ( official solution by codemanas)
| Also the prefix seperates the product collections if project uses the same typesense server as other projects.
|
*/
add_filter('cm_typesense_schema', function ($schema) {

    $prefix = '';
    if (WP_ENV == 'local' || WP_ENV == 'staging' || WP_ENV == 'development') {
        $prefix = WP_ENV.'_';
    }

    if ($schema['name'] == 'product') {
        $schema['name'] = config('theme.typesense.schemaPrefix', 'sage_').$schema['name'];
    }
    $schema['name'] = $prefix.$schema['name'];

    return $schema;
}, 20);

/*
|--------------------------------------------------------------------------
| Add sku and barcodes to products schema
|--------------------------------------------------------------------------
|
| You can search products by sku or barcodes
|
*/
add_filter('cm_tsfwc_product_fields', function ($fields) {
    $fields[] = [
        'name' => 'sku',
        'type' => 'string',
        'optional' => true,
        'facet' => false,
    ];
    $fields[] = [
        'name' => 'barcodes',
        'type' => 'string',
        'optional' => true,
        'facet' => false,
    ];
    // $fields[] = [
    //     'name' => 'stock_status',
    //     'type' => 'string',
    //     'facet' => true
    // ];
    $fields[] = [
        'name' => 'custom_group',
        'type' => 'string[]',
        'facet' => true,
    ];

    return $fields;
});

/*
|--------------------------------------------------------------------------
| WPML integration
|--------------------------------------------------------------------------
|
| Add language to filters
|
*/
if (defined('ICL_SITEPRESS_VERSION')) {
    add_action('cm_tsfwc_custom_attributes', function () {
        ?>
            <div
                    class="cmtsfwc-Filter-customAttributes lang_attribute_filter"
                    data-facet_name="lang_attribute_filter"
                    data-title="' . __( 'Filter by Language', 'typesense-search-for-woocommerce' ) . '"
                    style="display:none"></div>
            <?php
    });
    add_filter('tsfwc_current_lang', function () {
        return apply_filters('wpml_current_language', null);
    });
}

/*
|--------------------------------------------------------------------------
| Update Product
|--------------------------------------------------------------------------
|
| this hook is needed by typesense to update it's schema when products are imported via REST API.
|
*/
add_action('woocommerce_rest_insert_product_object', function ($product, $request, $creating) {
    // error_log('woocommerce_rest_insert_product_object - post id = ' . $product->get_ID() . ', creating = ' . ($creating ? 'true' : 'false'));
    do_action('wp_after_insert_post', $product->get_ID(), get_post($product->get_ID()), ! $creating, null);
}, 10, 3);

/*
|--------------------------------------------------------------------------
| Custom Filters for typesense woocommerce
|--------------------------------------------------------------------------
|
*/
// add_action( 'cm_tsfwc_custom_attributes', function() {
//     echo '<div
//     data-facet_name="stock_status"
//     data-attr_facet_name="stock_status"
//     data-title ="' . __( "Filter by Stock", 'sage' ) . '"
//     data-attr_label ="' . __( "Stock", 'sage' ) . '"
//     class="cm-tsfwc-shortcode-tags-attribute-filters"
//     data-filter_type="refinementList"
//     data-settings="' . _wp_specialchars( json_encode( [ "searchable" => false ] ), ENT_QUOTES, "UTF-8", true ) . '"
//     ></div>';
// } );
add_action('cm_tsfwc_custom_attributes', function () {
    echo '<div 
        data-facet_name="custom_group"  
        data-attr_facet_name="custom_group"
        data-title ="'.__('Options', 'sage').'" 
        data-attr_label ="'.__('Options', 'sage').'"
        class="cm-tsfwc-shortcode-tags-attribute-filters" 
        data-filter_type="refinementList"
        data-settings="'._wp_specialchars(json_encode(['searchable' => false]), ENT_QUOTES, 'UTF-8', true).'"
        ></div>';
});

/*
|--------------------------------------------------------------------------
| maybe force typesense to remove from collection out of stock products.
| typesense will also need products to have $product->get_catalog_visibility() == hidden
| to delete them from collection.
|--------------------------------------------------------------------------
|
*/
add_filter('cm_typesense_force_remove_post_on_update', function ($remove, $post) {

    if (config('woocommerce.shop-hide-products-with-empty-price', true) || get_option('woocommerce_hide_out_of_stock_items') == 'yes') {
        $product = wc_get_product($post->ID);

        if (! $product) {
            return $remove;
        }
        if ($product->get_type() === 'simple' && (! $product->is_purchasable() || ! $product->is_in_stock() || $product->get_catalog_visibility() == 'hidden')) {
            // error_log('typesense force remove = ' . $post->ID);
            return true;
        }
    }

    return $remove;
}, 99, 2);
add_filter('cm_typesense_bulk_import_skip_post', function ($remove, $post) {
    if (config('woocommerce.shop-hide-products-with-empty-price', true) || get_option('woocommerce_hide_out_of_stock_items') == 'yes') {
        $product = wc_get_product($post->ID);

        if (! $product) {
            return $remove;
        }
        if ($product->get_type() === 'simple' && (! $product->is_purchasable() || ! $product->is_in_stock() || $product->get_catalog_visibility() == 'hidden')) {
            // error_log('typesense bulk force remove = ' . $post->ID);
            return true;
        }
    }

    return $remove;
}, 99, 2);

/*
|--------------------------------------------------------------------------
| typesense forces upsert (post) of the product after wpml sync hook
| without checking product catalog visibility or providing a filter
| to not post the product to the collection. So we have use this hook to
| maybe delete it from the collection.
|
|--------------------------------------------------------------------------
|
*/
add_action('wcml_after_sync_product_data', function ($original_product_id, $tr_product_id) {
    // error_log('wcml_after_sync_product_data');
    if (config('woocommerce.shop-hide-products-with-empty-price', true) || get_option('woocommerce_hide_out_of_stock_items') == 'yes') {
        $product = wc_get_product($tr_product_id);

        if (! $product) {
            return;
        }
        if ($product->get_type() === 'simple' && (! $product->is_purchasable() || ! $product->is_in_stock() || $product->get_catalog_visibility() == 'hidden')) {
            do_action('wp_after_insert_post', $tr_product_id, get_post($tr_product_id), true, null);
        }
    }
}, PHP_INT_MAX, 2);

/*
|--------------------------------------------------------------------------
| typesense forces upsert (post) of the product after order stock reduced
| without checking product catalog visibility or providing a filter
| to not post the product to the collection. So we have use this hook to
| maybe delete it from the collection.
|
|--------------------------------------------------------------------------
|
*/
add_action('woocommerce_reduce_order_stock', function (\WC_Order $order) {
    // error_log('woocommerce_reduce_order_stock');
    $items = $order->get_items();
    if (count($items) > 0) {
        foreach ($items as $item) {
            $item_data = $item->get_data();
            if (isset($item_data['product_id'])) {
                $product_id = $item_data['product_id'];

                if (defined('ICL_SITEPRESS_VERSION')) {
                    // get all translations, includes original product
                    $trid = apply_filters('wpml_element_trid', null, $product_id, 'post_product');
                    $translations = apply_filters('wpml_get_element_translations', null, $trid, 'post_product');
                    foreach ($translations as $lang => $translation) {
                        do_action('wp_after_insert_post', $translation->element_id, get_post($translation->element_id), true, null);
                    }
                } else {
                    do_action('wp_after_insert_post', $product_id, get_post($product_id), true, null);
                }
            }
        }
    }
}, PHP_INT_MAX, 1);

/*
|--------------------------------------------------------------------------
| redirection for single brand page
|--------------------------------------------------------------------------
|
*/
// add_action('template_redirect', function() {

//     $t = $_GET['taxonomy'] ?? null;
//     if ($t !== null && $t == 'pa_brand' && $_GET['term'] !== null) {

//         $term = get_term_by('slug', $_GET['term'], 'pa_brand');
//         if(!$term){ return; }
//         // Build the new URL with the required query parameters
//         $new_url = home_url('/shop/?staging_marks_product%5BrefinementList%5D%5Bpa_brand_attribute_filter%5D%5B0%5D=' . urlencode($term->name));

//         // Redirect to the new URL
//         wp_redirect($new_url, 301);
//         exit;
//     }
// });
