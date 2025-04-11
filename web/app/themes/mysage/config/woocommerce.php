<?php

return [

    // Single Product Page
    'attribute_inputs' => 'theme', // Options are: native,theme. Changes the attribute inputs ui and logic from native wordpress to htsage theme.

    // Alpine Shop Start
    'shop-pagination-style' => 'load-more', // load-more, pagination
    'shop-products-per-page' => 12, // products per page in shop
    'shop-hide-products-with-empty-price' => false, // hide products from shop where price == ''.
    'shop-facetes-limit' => 10, // limit for facete terms
    'shop-products-ignore-sticky-posts' => 1, // for shop products query
    'shop-facetes-subcats-before-loop' => false, // show subcategories before shop loop
    'shop-default-order' => [
        'orderby' => 'menu_order title',
        'order' => 'ASC',
    ],
    'facetes' => [
        // dont remove product_cat from facetes. Used when archive page shows subcategories only.
        // its hidden from the front end. Replace by categories tree navigation
        [
            'type' => 'taxonomy',
            'taxonomy' => 'product_cat',
            'template' => 'list',
            'label' => 'Categories',
            'enable_search' => false,
            'terms_as_boolean' => false,
            'limit' => 10,
            'show_more' => true,
        ],
        [
            'type' => 'taxonomy',
            'taxonomy' => 'pa_brand',
            'template' => 'list',
            'label' => 'Brand',
            'orderby' => 'name', // name, term_order
            'enable_search' => false,
            'terms_as_boolean' => false,
            'limit' => 10,
            'show_more' => true,
        ],
        [
            'type' => 'taxonomy',
            'taxonomy' => 'pa_color',
            'template' => 'color-list',
            'label' => 'Color',
            'orderby' => 'name', // name, term_order
            'enable_search' => false,
            'terms_as_boolean' => false,
            'limit' => 10,
            'show_more' => true,
            'term_display_color_style_metakey' => 'display_color_style',
            'term_hex_metakey' => 'hexcolor',
            'term_img_metakey' => 'attr_img',
        ],
        [
            'type' => 'taxonomy',
            'taxonomy' => 'product_tag',
            'template' => 'list',
            'label' => 'Tags',
            'orderby' => 'name', // name, term_order
            'enable_search' => false,
            'terms_as_boolean' => false,
            'limit' => 10,
            'show_more' => true,
        ],
        // [
        //     'type' => 'price_range',
        //     'template' => 'price_range',
        //     'label' => 'Price',
        //     'enable_search' => false,
        //      'terms_as_boolean' => false,
        //     'limit' => 10,
        //     'show_more' => true,
        // ],
        [
            'type' => 'on_sale',
            'template' => 'switch',
            'label' => 'On Sale',
            'enable_search' => false,
            'terms_as_boolean' => false,
            'limit' => 10,
            'show_more' => true,
        ],
    ],
    'extra_product_properties' => [
        // add call user functions
        // 'call_user_fn' => [
        //     'gallery',
        //     'full_description'
        // ],
        // add product attribute slugs to get the terms
        'product_attributes' => [
            'pa_brand',
        ],
        // add advance custom fields keys to get the values
        // 'acf' => [
        //     'free_shipping'
        // ],
    ],
];
