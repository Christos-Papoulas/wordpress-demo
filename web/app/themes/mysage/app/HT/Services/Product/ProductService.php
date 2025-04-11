<?php

namespace App\HT\Services\Product;

use App\HT\Models\Product;
use InvalidArgumentException;

class ProductService
{
    public const FORMAT_HTML = 'html';

    public const FORMAT_OBJECT = 'object';

    public const FORMAT_VALUE = 'value';

    /**
     * Adds custom fields to product
     *
     * @since  1.0.0
     */
    public static function addCustomOptionsToProducts(): void
    {
        woocommerce_wp_text_input([
            'id' => Product::MPN_METAKEY_NAME,
            'label' => __('MPN', 'woocommerce'),
            'placeholder' => '',
            'desc_tip' => true,
            'description' => __('MPN code of product.', 'woocommerce'),
        ]);

        woocommerce_wp_text_input([
            'id' => Product::BARCODE_METAKEY_NAME,
            'label' => __('BARCODE', 'woocommerce'),
            'placeholder' => '',
            'desc_tip' => true,
            'description' => __('BARCODE of product.', 'woocommerce'),
        ]);
    }

    /**
     * Adds custom shipping fields to product
     *
     * @since  1.0.0
     */
    // public static function addCustomShippingOptionsToProducts():void
    // {
    //     echo '<div class="options_group" style="display:flex;">';

    //         woocommerce_wp_checkbox(
    //             array(
    //                 'id'          => Product::PACKAGING_GIFTWRAP_METAKEY_NAME,
    //                 'label'       => __( 'Enable Giftwrap', 'woocommerce' ),
    //                 'desc_tip'    => true,
    //                 'description' => __( 'Check this box to allow customers to order giftwrap for the product).', 'woocommerce' ),
    //             )
    //         );

    //         woocommerce_wp_text_input(
    //             array(
    //                 'id'        => Product::PACKAGING_GIFTWRAP_METAKEY_NAME . '_price',
    //                 'label'     => __( 'Giftwrap price', 'woocommerce' ) . ' (' . get_woocommerce_currency_symbol() . ')',
    //                 'data_type' => 'price',
    //                 'desc_tip'    => true,
    //             )
    //         );

    //     echo '</div>';
    // }

    /**
     * Saves custom fields data
     *
     * @since  1.0.0
     */
    public static function saveProductCustomOptions(int $post_id): void
    {
        update_post_meta($post_id, Product::MPN_METAKEY_NAME, esc_attr($_POST[Product::MPN_METAKEY_NAME]));
        update_post_meta($post_id, Product::BARCODE_METAKEY_NAME, esc_attr($_POST[Product::BARCODE_METAKEY_NAME]));
        // update_post_meta($post_id, Product::PACKAGING_GIFTWRAP_METAKEY_NAME, esc_attr($_POST[Product::PACKAGING_GIFTWRAP_METAKEY_NAME]));
        // update_post_meta($post_id, Product::PACKAGING_GIFTWRAP_METAKEY_NAME . '_price', esc_attr($_POST[Product::PACKAGING_GIFTWRAP_METAKEY_NAME . '_price']));
    }

    /**
     * Adds custom fields to product variations
     *
     * @since  1.0.0
     *
     * @param  array  $variation_data
     * @param  WP_Post  $variation
     */
    public static function addCustomOptionsToVariations(int $loop, $variation_data, $variation): void
    {
        woocommerce_wp_text_input([
            'id' => Product::MPN_METAKEY_NAME.$loop,
            'wrapper_class' => 'form-row',
            'label' => __('MPN', 'woocommerce'),
            'placeholder' => '',
            'desc_tip' => true,
            'description' => __('MPN code of variation.', 'woocommerce'),
            'value' => get_post_meta($variation->ID, Product::MPN_METAKEY_NAME, true),
        ]);

        woocommerce_wp_text_input([
            'id' => Product::BARCODE_METAKEY_NAME.$loop,
            'wrapper_class' => 'form-row',
            'label' => __('BARCODE', 'woocommerce'),
            'placeholder' => '',
            'desc_tip' => true,
            'description' => __('BARCODE of variation.', 'woocommerce'),
            'value' => get_post_meta($variation->ID, Product::BARCODE_METAKEY_NAME, true),
        ]);
    }

    /**
     * Saves custom fields data
     *
     * @since  1.0.0
     */
    public static function saveVariationsCustomOptions(int $variation_id, int $i): void
    {
        update_post_meta($variation_id, Product::MPN_METAKEY_NAME, esc_attr($_POST[Product::MPN_METAKEY_NAME.$i]));
        update_post_meta($variation_id, Product::BARCODE_METAKEY_NAME, esc_attr($_POST[Product::BARCODE_METAKEY_NAME.$i]));
    }

    /**
     * Add field to the product attribute form
     *
     * @since  1.0.0
     */
    public static function edit_wc_attribute_display_type(): void
    {
        $id = isset($_GET['edit']) ? absint($_GET['edit']) : 0;
        $value = $id ? get_option("wc_attribute_display_type-$id") : 'default';
        ?>
            <tr class="form-field">
                <th scope="row" valign="top">
                    <label for="display-type">Display Type</label>
                </th>
                <td>
                    <select name="display_type" id="display-type">
                        <option value="default" <?php if ($value == 'default') {
                            echo 'selected';
                        } ?>>Default</option>
                        <option value="color" <?php if ($value == 'color') {
                            echo 'selected';
                        } ?>>Color</option>
                        <option value="size" <?php if ($value == 'size') {
                            echo 'selected';
                        } ?>>Size</option>
                    </select>
                    <p class="description">
                        <?php echo __('Determines the appearance of terms on the website.', 'sage'); ?>
                    </p>
                </td>
            </tr>
        <?php
    }

    /**
     * Set/save the custom field for display type, in the options table
     *
     * @since  1.0.0
     */
    public static function save_wc_attribute_display_type(int $id): void
    {
        if (is_admin() && isset($_POST['display_type'])) {
            $option = "wc_attribute_display_type-$id";
            update_option($option, sanitize_text_field($_POST['display_type']));
        }
    }

    /**
     * Delete the custom option for product attribute display type, when the attribute is deleted.
     *
     * @since  1.0.0
     */
    public static function delete_wc_attribute_display_type(int $id): void
    {
        delete_option("wc_attribute_display_type-$id");
    }

    /**
     * Wrapper function to get products
     *
     * @since  1.0.0
     *
     * @param  array  $query_params  Query params for WP_Query
     * @param  string  $format  The returning format
     * @return mixed
     */
    public static function getProducts(
        array $query_params = [],
        string $format = 'object',
    ) {
        $defaults = [
            'post_type' => 'product',
            'post_status' => 'publish',
            'ignore_sticky_posts' => 1,
            'update_post_term_cache' => false,
            'update_post_meta_cache' => false,
            'no_found_rows' => true,
            'posts_per_page' => config('woocommerce.shop-products-per-page', get_option('posts_per_page')),
        ];

        // meta query
        $defaults['meta_query'] = [];
        // tax query
        $defaults['tax_query'] = [
            [
                'taxonomy' => 'product_visibility',
                'field' => 'slug',
                'terms' => ['exclude-from-catalog'],
                'operator' => 'NOT IN',
            ],
        ];

        // hide products with no price
        if (config('woocommerce.shop-hide-products-with-empty-price', true)) {
            $defaults['meta_query'] = [
                'key' => '_price',
                'value' => '',
                'compare' => '!=',
            ];
        }

        // hide out of stock products
        if (get_option('woocommerce_hide_out_of_stock_items') == 'yes') {
            $defaults['meta_query'][] = [
                'key' => '_stock_status',
                'value' => 'outofstock',
                'compare' => '!=',
            ];
        }

        $query_args = array_merge($defaults, $query_params);

        $results = new \WP_Query($query_args);

        return match ($format) {
            self::FORMAT_OBJECT => array_map(fn ($post) => wc_get_product($post), $results->posts),
            default => throw new InvalidArgumentException('Format '.$format.' not supported')
        };
    }

    /**
     * Return all product prices based on type
     *
     * @since  1.0.0
     *
     * @param  WC_Product  $product
     */
    public static function getPrices(\WC_Product $product): array
    {
        switch ($product->get_type()) {
            case 'simple':
                $sale_price = $product->get_sale_price();
                $regular_price = $product->get_regular_price();
                $regular_price_max = $regular_price;
                $price = $product->get_price();
                break;
            case 'variable':
                $sale_price = $product->get_variation_sale_price('min');
                $regular_price = $product->get_variation_regular_price('min');
                $regular_price_max = $product->get_variation_regular_price('max');
                $price = $product->get_variation_price('min');
                break;
            default:
                $sale_price = $product->get_sale_price();
                $regular_price = $product->get_regular_price();
                $regular_price_max = $regular_price;
                $price = $product->get_price();
        }

        $discount_percentage = 0;
        if ($product->is_on_sale() && $regular_price_max && $sale_price) {
            $discount_percentage = round(100 - ($sale_price / $regular_price_max * 100));
        }

        return [
            $price,
            $regular_price,
            $sale_price,
            $discount_percentage,
        ];
    }

    /**
     * Return if the product is almost out of stock
     *
     * @since  1.0.0
     *
     * @param  WC_Product  $product
     */
    public static function lastUnitsForProduct(\WC_Product $product): mixed
    {
        $last_units = false;
        if ($product->get_manage_stock() && ($product->get_stock_quantity() <= $product->get_low_stock_amount()) && ! $product->backorders_allowed()) {
            $last_units = true;
        }

        return $last_units;
    }

    /**
     * Get store stock status by product id.
     *
     * @since   1.0.0
     */
    public static function getStoreStockStatus(): void
    {
        $product_id = $_POST['product_id'];

        $storages = [
            // [
            //     'name' => __('ΝΕΑ ΦΙΛΑΔΕΛΦΕΙΑ - ΒΥΤΙΝΗΣ','sage'),
            //     'stock' => (int)ht_get_field('_ht_storage1_stock', $product_id)
            // ],
            //         [
            //     'name' => __('ΝΕΑ ΦΙΛΑΔΕΛΦΙΑ - ΘΕΣΣΑΛΟΝΙΚΗΣ','sage'),
            //     'stock' => (int)ht_get_field('_ht_storage2_stock', $product_id)
            // ],
            //         [
            //     'name' => __('ΘΗΒΩΝ','sage'),
            //     'stock' => (int)ht_get_field('_ht_storage3_stock', $product_id)
            // ],
            //         [
            //     'name' => __('ΑΘΗΝΑ','sage'),
            //     'stock' => (int)ht_get_field('_ht_storage4_stock', $product_id)
            // ],
            //         [
            //     'name' => __('ΜΕΤΑΜΟΡΦΩΣΗ','sage'),
            //     'stock' => (int)ht_get_field('_ht_storage5_stock', $product_id)
            // ],
            //         [
            //     'name' => __('ΠΕΙΡΑΙΑΣ','sage'),
            //     'stock' => (int)ht_get_field('_ht_storage6_stock', $product_id)
            // ],
            //         [
            //     'name' => __('ΝΕΑ ΕΡΥΘΡΑΙΑ','sage'),
            //     'stock' => (int)ht_get_field('_ht_storage7_stock', $product_id)
            // ],
            //         [
            //     'name' => __('ΒΟΛΟΣ','sage'),
            //     'stock' => (int)ht_get_field('_ht_storage8_stock', $product_id)
            // ],
            //         [
            //     'name' => __('ΑΡΤΕΜΙΔΑ','sage'),
            //     'stock' => (int)ht_get_field('_ht_storage9_stock', $product_id)
            // ],
        ];

        wp_send_json_success([
            'storages' => $storages,
        ], 200);
    }

    /**
     * Get variation sku.
     *
     * @since   1.0.0
     *
     * @return void
     */
    public static function getVariationSku()
    {
        $variation_id = $_POST['variation_id'];

        $variation = wc_get_product($variation_id);
        $sku = $variation->get_sku();
        if ($variation) {
            wp_send_json_success(
                [
                    'variation_sku' => $sku,
                ],
                200
            );
        } else {
            wp_send_json_error(['message' => 'Variation not found'], 200);
        }
    }

    /**
     * Returns product colors count and the first color
     *
     * @since   1.0.0
     */
    public static function getProductColorsBadge(\WC_Product $product): array
    {
        $attributes = $product->get_attributes();

        $count = 0;
        $first_color_label = [];
        if (is_array($attributes) && ! empty($attributes)) {
            foreach ($attributes as $att) {
                $d_type = get_option('wc_attribute_display_type-'.$att->get_id());
                if ($d_type && $d_type == 'color') {
                    $terms = $att->get_options();
                    if (! empty($terms)) {
                        $count = $count + count($terms);

                        if (empty($first_color_label)) {
                            // uncomment if you want to show the first color value
                            // $term = get_term_by('term_id',$terms[0],$att->get_name());
                            // $term_display_color_style = 'hex';
                            // $term_display_color_style = get_term_meta($term->term_id, 'display_color_style', true);

                            $background = 'radial-gradient(circle at 0 0, rgba(255, 0, 0, .7), rgba(255, 0, 0, 0) 70.71%), radial-gradient(circle at 93.3% 0, #fe0, rgba(255, 238, 0, 0) 70.71%), radial-gradient(circle at 0 80%, blue, rgba(0, 0, 255, 0) 70.71%), radial-gradient(circle at 93.3% 80%, rgba(0, 251, 255, .8), rgba(0, 251, 255, 0) 70.71%)';

                            // if( $term_display_color_style == 'img'){
                            //     $img_id = get_term_meta($term->term_id, 'attr_img', true);
                            //     if ($img_id && !empty($img_id)) {
                            //         $background = 'url(' . wp_get_attachment_url($img_id) . ')';
                            //     }
                            // }else{
                            //     $hex = get_term_meta($term->term_id, 'hexcolor', true);
                            //     if ($hex && !empty($hex)) {
                            //         $background = $hex;
                            //     }
                            // }

                        }

                    }

                }
            }

            return [
                'colors_count' => $count,
                'first_color' => [
                    'display_type' => $term_display_color_style ?? 'hex',
                    'value' => $background ?? '',
                ],
            ];
        }

        return [
            'colors_count' => 0,
            'first_color' => [
                'display_type' => 'hex',
                'value' => '',
            ],
        ];
    }

    /**
     * get all brands
     *
     * @since 1.0.0
     */
    public static function getAllBrands(): void
    {
        $terms = get_terms([
            'taxonomy' => 'pa_brand',
            'hide_empty' => false,
        ]);

        $brands = array_map(function ($term) {
            $term->permalink = get_term_link($term->term_id, $term->taxonomy);

            return $term;
        }, $terms);

        wp_send_json_success([
            'brands' => $brands,
        ], 200);
    }

    /**
     * Creates the required data for product card. Includes data required for local storage add to cart.
     *
     * @since   1.0.0
     *      
     * @param WC_Product $product
     * @param $single_product_page optionally param for single product page.
     * In single product page we dont need the attribute data, because we load them for the inputs in variable-theme.blade.php
     * 
     * @return array
     */
    public static function createProductCardData(\WC_Product $product, ?bool $single_product_page = false):array
    {
        $localization = [];
        if (defined('ICL_SITEPRESS_VERSION')) {
            $post_id = $product->get_id();
            $type = apply_filters('wpml_element_type', get_post_type($post_id));
            $trid = apply_filters('wpml_element_trid', false, $post_id, $type);
            $translations = apply_filters('wpml_get_element_translations', [], $trid, $type);
            foreach ($translations as $key => $tr) {
                $localization[$key] = (object) ['post_title' => $tr->post_title];
            }
        }
        if ($product->backorders_require_notification() && $product->is_on_backorder(1)) {
            $backorder_note = wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">'.esc_html__('Available on backorder', 'woocommerce').'</p>', $product->get_ID()));
        }

        [$price, $regular_price, $sale_price, $discount_percentage] = ProductService::getPrices($product);

        return [
            'product_id' => $product->get_id(),
            'type' => $product->get_type(),
            "stock_status" => $product->get_stock_status(),
            'max_qty' => $product->get_max_purchase_quantity(),
            'min_qty' => $product->get_min_purchase_quantity(),
            'permalink' => $product->get_permalink(),
            'title' => $product->get_title(),
            'localization' => $localization,
            'sku' => $product->get_sku(),
            'image_src' => [
                'woocommerce_single' => MediaService::getProductMainImage($product, 'woocommerce_single'),
                'woocommerce_thumbnail' => MediaService::getProductMainImage($product, 'woocommerce_thumbnail'),
            ],
            "image_src_2" => [
                'woocommerce_single' => MediaService::getASecondImgForProduct($product,'woocommerce_single', false),
                'woocommerce_thumbnail' => MediaService::getASecondImgForProduct($product,'woocommerce_thumbnail', false)
            ],
            'video' => MediaService::getProductVideo($product->get_id()),
            'price' => $price,
            'regular_price' => $regular_price,
            'sale_price' => ! empty($sale_price) ? $sale_price : null,
            'price_html' => wc_price($price),
            'regular_price_html' => wc_price($regular_price),
            'sale_price_html' => ! empty($sale_price) ? wc_price($sale_price) : null,
            'is_on_sale' => $product->is_on_sale(),
            'discount_percentage' => $discount_percentage,
            'backorder_note' => $backorder_note ?? '',
            'attributes_and_options' => $single_product_page ? [] : self::getAttributesAndOptions($product),
            'variations' => self::getVariationsArray($product),
            'category_list' => wc_get_product_category_list( $product->get_id(), ' / ', '', '' ),

            // badges
            'last_units' => self::lastUnitsForProduct($product),
            'cross_sells' => $product->get_cross_sells(),
            'new_in_badge' => $product->get_meta('_new_in_dates_to') ? true : false,
        
            // gtm4 required properties
            'brand' => $product->get_attribute('pa_brand'),
            'categories' => self::getProductCategories($product->get_id()),

            // 'taxes' => WC_Tax::get_rates( $product->get_tax_class() ) TODO: open this when we implement taxes with local storage
        ];
    }

        /**
     * Creates variations array for product card data
     * 
     * @since   1.0.0
     *      
     * @param WC_Product $product
     * 
     * @return array
     */
    private static function getVariationsArray(\WC_Product $product): array
    {
        if($product->get_type() !== 'variable'){
            return [];
        }

        $variation_ids  = $product->get_children();
        $all_variations = [];
        if ( is_callable( '_prime_post_caches' ) ) {
            _prime_post_caches( $variation_ids );
        }
        $thumbnail_size = apply_filters( 'woocommerce_thumbnail_size', 'woocommerce_thumbnail' );

        foreach ( $variation_ids as $variation_id ) {
            $variation = wc_get_product( $variation_id );
            // Filter 'woocommerce_hide_invisible_variations' to optionally hide invisible variations (disabled variations and variations with empty price).
            if ( apply_filters( 'woocommerce_hide_invisible_variations', true, $product->get_id(), $variation ) && ! $variation->variation_is_visible() ) {
                continue;
            }
        
            $localization = [];
            if (defined('ICL_SITEPRESS_VERSION')) {
                $post_id = $variation->get_id();
                $type = apply_filters('wpml_element_type', get_post_type($post_id));
                $trid = apply_filters('wpml_element_trid', false, $post_id, $type);
                $translations = apply_filters('wpml_get_element_translations', [], $trid, $type);
                foreach ($translations as $key => $tr) {
                    $localization[$key] = (object) ['post_title' => $tr->post_title];
                }
            }
            if ( $variation->backorders_require_notification() && $variation->is_on_backorder( 1 ) ) {
                $backorder_note = wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $variation->get_ID() ) );
            }
        
            $all_variations[] = [
                'id' => $variation_id,
                'attributes' => $variation->get_variation_attributes(),
                "stock_status" => $variation->get_stock_status(),
                "max_qty" => $variation->get_max_purchase_quantity(),
                "min_qty" => $variation->get_min_purchase_quantity(),
                "permalink" => $variation->get_permalink(),
                "title" =>  $variation->get_title(),
                "localization" => $localization,
                "sku" => $variation->get_sku(),
                "image_src" => [
                    'woocommerce_single' => MediaService::getProductMainImage($variation,'woocommerce_single'),
                    'woocommerce_thumbnail' => MediaService::getProductMainImage($variation,'woocommerce_thumbnail')
                ],
                "price" => $variation->get_price(),
                "regular_price" => $variation->get_regular_price(),
                "sale_price" => $variation->get_sale_price(),
                "price_html" => wc_price($variation->get_price()),
                "regular_price_html" => wc_price($variation->get_regular_price()),
                "sale_price_html" => wc_price($variation->get_sale_price()),
                "is_on_sale" => $variation->is_on_sale(),
                "variation_attr" => $variation->get_attribute_summary(),
                "backorder_note" => $backorder_note ?? '',
            ];
        }
        
        // this code get all variation data needed for swatches
        $all_variations = array_values( array_filter( $all_variations ) );

        return $all_variations;
    }

    /**
     * Creates array of variable product attributes options
     * 
     * @since   1.0.0
     *      
     * @param WC_Product $product
     * 
     * @return array
     */
    private static function getAttributesAndOptions(\WC_Product $product): array
    {
        if ( !$product || !$product->is_type( 'variable' ) ) {
            return [];
        }

        $attributes = $product->get_attributes();

        $attributes_and_options = [];
       
        foreach ( $attributes as $attribute_name => $attribute ) {
            // Check if it's a variation attribute (used for variations)
            if ( $attribute->get_variation() ) {
                // For taxonomy-based attributes (like pa_color, pa_size)
                if ( $attribute->is_taxonomy() ) {
                    $terms = wc_get_product_terms( $product->get_id(), $attribute_name, array( 'fields' => 'all' ) );
               
                    $attribute_id = $attribute->get_id();
                    $displayType = $attribute_id ? get_option( "wc_attribute_display_type-$attribute_id" ) : 'default';
                    if($displayType === 'color'){
                        foreach($terms as $key => $term){
                            $terms[$key]->background =  self::buildBackgroundForColorInput($term);
                        }
                    }

                    $attributes_and_options[] = [
                        'id' => $attribute_id,
                        'name' => $attribute_name,
                        'label' => wc_attribute_label($attribute_name),
                        'display_type' => $displayType,
                        'options' => $terms
                    ];

                } else {
                    // TODO: For custom product attributes
                    // $options = $attribute->get_options();
                }
            }
        }

        return $attributes_and_options;
    }

    /**
     * Build background for color input
     * 
     * @param WP_Term $term
     * 
     * @return string
     */
    public static function buildBackgroundForColorInput(\WP_Term $term): string
    {
        $background = 'radial-gradient(circle at 0 0, rgba(255, 0, 0, .7), rgba(255, 0, 0, 0) 70.71%), radial-gradient(circle at 93.3% 0, #fe0, rgba(255, 238, 0, 0) 70.71%), radial-gradient(circle at 0 80%, blue, rgba(0, 0, 255, 0) 70.71%), radial-gradient(circle at 93.3% 80%, rgba(0, 251, 255, .8), rgba(0, 251, 255, 0) 70.71%)';

        // for product images
        // foreach ($all_variations as $variation) {
        //     foreach ($variation['attributes'] as $key => $attr) {
        //         if ($input_name == $key && $value == $attr) {
        //             $background = 'url('.$variation['image']['thumb_src'].')';
        //             break 2;
        //         }
        //     }
        // }

        // for hexcolors
        if(true){
            $term_display_color_style = 'hex';
            $term_display_color_style = get_term_meta($term->term_id, 'display_color_style', true);
            
            if( $term_display_color_style == 'img'){
                $img_id = get_term_meta($term->term_id, 'attr_img', true);
                if ($img_id && !empty($img_id)) {
                    $background = 'url(' . wp_get_attachment_url($img_id) . ')';
                }
            }else{
                $hex = get_term_meta($term->term_id, 'hexcolor', true);
                if ($hex && !empty($hex)) {
                    $background = $hex;
                }
            }
        }

        return $background;
    }

    /**
     * Get product categories
     * 
     * @since   1.0.0
     * @param int product_id
     * @return array
     */
    public static function getProductCategories(int $product_id): array
    {
        $terms = get_the_terms( $product_id, 'product_cat' );

        if ( is_wp_error( $terms ) ) {
            return [];
        }

        if ( empty( $terms ) ) {
            return [];
        }

        $arr = [];

        foreach ( $terms as $term ) {
            $arr[] = $term->name;
        }

        return $arr;
    }
}
