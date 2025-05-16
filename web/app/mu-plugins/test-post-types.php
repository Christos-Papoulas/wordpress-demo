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

        'capability_type' => 'event',
        'map_meta_cap' => true,

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
		'has_archive' => true,
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

        'capability_type' => 'campus',
        'map_meta_cap' => true,

        'show_in_rest' => true,
    ]);

    register_post_type('note', [
        'public' => false,
        'show_ui' => true,
        'capability_type' => 'note',
        'map_meta_cap' => true,
        'labels' => [
            'name' => 'Notes',
            'add_new_item' => 'Add new Note',
            'edit_item' => 'Edit Note',
            'all_items' => 'All Notes',
            'singular_name' => 'Note',
        ],
        'menu_icon' => 'dashicons-welcome-write-blog',
        'supports' => [
            'title', 'editor',
        ],
        'show_in_rest' => true,
    ]);

    register_post_type('like', [
        'public' => false,
        'show_ui' => true,
        'labels' => [
            'name' => 'Likes',
            'add_new_item' => 'Add new Like',
            'edit_item' => 'Edit Like',
            'all_items' => 'All Likes',
            'singular_name' => 'Like',
        ],
        'menu_icon' => 'dashicons-heart',
        'supports' => [
            'title'
        ],
    ]);
});
