<?php

return [

    // keys of filters must be the post type slug

    // Post Filters Start
    'post' => [
        'post-type' => 'post',
        'posts-per-page' => 10, // Comment this out for config value of wordpress (get_option('posts_per_page'))
        'posts-ignore-sticky-posts' => 1,
        'default-order' => [
            'orderby' => 'date',
            'order' => 'DESC',
        ],
        'facetes' => [
            [
                'type' => 'taxonomy',
                'taxonomy' => 'category',
                'template' => 'list',
                'label' => 'ΚΑΤΗΓΟΡΙΕΣ',
                'enable_search' => false,
                'limit' => 10,
                'show_more' => true,
                'extra_term_properties' => [
                    // // add call user functions
                    // 'call_user_fn' => [
                    // ],
                    // // add advance custom fields keys to get the values
                    // 'acf' => [
                    //     'acf_key'
                    // ],
                ],
            ],
        ],
        'extra_post_properties' => [
            // add call user functions
            'call_user_fn' => [
                'categories',
                'date', // dont remove date, used at post schema
                'excerpt', // dont remve excerpt, used at post schema
            ],
            // add advance custom fields keys to get the values
            // 'acf' => [
            //     ''
            // ],
        ],
    ],
    // Post Filters End
    // Store Post Type Start
    'store' => [
        'post-type' => 'store',
        'posts-per-page' => -1, // Comment this out for config value of wordpress (get_option('posts_per_page'))
        'posts-ignore-sticky-posts' => 1,
        'default-order' => [
            'orderby' => 'menu_order title',
            'order' => 'ASC',
        ],
        'facetes' => [
            // [
            //     'type' => 'taxonomy',
            //     'taxonomy' => 'category',
            //     'template' => 'list',
            //     'label' => 'ΚΑΤΗΓΟΡΙΕΣ',
            //     'enable_search' => false,
            //     'limit' => 10,
            //     'show_more' => true,
            // ]
        ],
        'extra_post_properties' => [
            // add call user functions
            // 'call_user_fn' => [
            //     'excerpt',
            // ],
            // add advance Store fields keys to get the values
            'acf' => [
                'store_custom_fields_address',
                'store_custom_fields_phones',
                'store_custom_fields_emails',
                'store_custom_fields_opening-hours',
                'store_custom_fields_google_map_field',
            ],
        ],
    ],
    // Store Post Type End
    // Custom Post Type Start
    'custom-post-type' => [
        'post-type' => 'custom-post-type',
        'posts-per-page' => 12, // Comment this out for config value of wordpress (get_option('posts_per_page'))
        'posts-ignore-sticky-posts' => 1,
        'default-order' => [
            'orderby' => 'menu_order title',
            'order' => 'ASC',
        ],
        'facetes' => [
            // [
            //     'type' => 'taxonomy',
            //     'taxonomy' => 'category',
            //     'template' => 'list',
            //     'label' => 'ΚΑΤΗΓΟΡΙΕΣ',
            //     'enable_search' => false,
            //     'limit' => 10,
            //     'show_more' => true,
            // ]
        ],
        'extra_post_properties' => [
            // add call user functions
            'call_user_fn' => [
                'excerpt',
            ],
            // add advance custom fields keys to get the values
            // 'acf' => [
            //     'free_shipping'
            // ],
        ],
    ],
    // Custom Post Type End
];
