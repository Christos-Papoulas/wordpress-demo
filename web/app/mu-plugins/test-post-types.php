<?php

add_action('init', function () {
    register_post_type('event', [
        'rewrite' => [
            'slug' => 'events'
        ],
        'has_archive' => true,
        'public' => true,
        'show_in_rest' => true,
        'labels' => [
            'name' => 'Events',
            'add_new_item' => 'Add new Event',
            'edit_item' => 'Edit Event',
            'all_items' => 'All Events',
            'singular_name' => 'Event',
        ],
        'menu_icon' => 'dashicons-calendar',
        'supports' => [
            'title', 'editor', 'excerpt',
        ],

        'show_in_rest' => true,
    ]);

    register_post_type('program', [
        'rewrite' => [
            'slug' => 'programs'
        ],
        'has_archive' => true,
        'public' => true,
        'labels' => [
            'name' => 'Programs',
            'add_new_item' => 'Add new Program',
            'edit_item' => 'Edit Program',
            'all_items' => 'All Programs',
            'singular_name' => 'Program',
        ],
        'menu_icon' => 'dashicons-awards',
        'supports' => [
            'title', 'editor',
        ],
        'show_in_rest' => true,
    ]);

    register_post_type('professor', [
        'public' => true,
        'labels' => [
            'name' => 'Professors',
            'add_new_item' => 'Add new Professor',
            'edit_item' => 'Edit Professor',
            'all_items' => 'All Professors',
            'singular_name' => 'Professor',
        ],
        'menu_icon' => 'dashicons-welcome-learn-more',
        'supports' => [
            'title', 'editor', 'thumbnail',
        ],
        'show_in_rest' => true,
    ]);

    register_post_type('campus', [
        'rewrite' => [
            'slug' => 'campuses'
        ],
        'has_archive' => true,
        'public' => true,
        'labels' => [
            'name' => 'Campuses',
            'add_new_item' => 'Add new Campus',
            'edit_item' => 'Edit Campus',
            'all_items' => 'All Campuses',
            'singular_name' => 'Campus',
        ],
        'menu_icon' => 'dashicons-location-alt',
        'supports' => [
            'title', 'editor', 'excerpt',
        ],

        'show_in_rest' => true,
    ]);
});
