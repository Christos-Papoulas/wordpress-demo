<?php

add_action('init', function () {
    register_post_type('event', [
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
    ]);
});
 