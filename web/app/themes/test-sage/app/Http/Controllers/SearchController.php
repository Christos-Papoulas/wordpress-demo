<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use WP_Query;

class SearchController
{
    public function index(Request $request): array
    {
        $mainQuery = new WP_Query([
           'post_type' => ['post', 'page', 'professor', 'program', 'campus', 'event'],
           's' => sanitize_text_field($request->input('term')),
        ]);

        $results = [
            'post' => [],
            'page' => [],
            'professor' => [],
            'program' => [],
            'campus' => [],
            'event' => [],
        ];
        while ($mainQuery->have_posts()) {
            $mainQuery->the_post();
            $results[get_post_type()][] = [
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
            ];
        }

        return $results;
    }
}
