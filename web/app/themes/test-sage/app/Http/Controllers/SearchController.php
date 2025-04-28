<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use WP_Query;

class SearchController
{
    public function index(Request $request)
    {
        $professors = new WP_Query([
           'post_type' => 'professor',
           's' => sanitize_text_field($request->input('term')),
        ]);

        $results = [];
        while ($professors->have_posts()) {
            $professors->the_post();
            $results[] = [
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
            ];
        }
        return $results;
    }
}
