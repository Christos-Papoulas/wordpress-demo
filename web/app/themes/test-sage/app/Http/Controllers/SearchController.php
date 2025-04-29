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
            $postType = get_post_type();

            $customFields = [];
            if ($postType === 'professor') {
                $customFields['image'] = get_the_post_thumbnail_url(0, 'professorLandscape');
            } else if ($postType === 'event') {
                $customFields['month'] = get_the_time('M');
                $customFields['day'] = get_the_time('d');
                $customFields['excerpt'] = has_excerpt() ?
                    get_the_excerpt()
                    : wp_trim_words(get_the_content(), 18);
            }

            $results[$postType][] = [
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'authorName' => get_the_author(),
                ...$customFields
            ];
        }

        if (count($results['program']) === 0) {
            return $results;
        }

        $programsMetaQuery = ['relation' => 'OR'];

        foreach ($results['program'] as $program) {
            $programsMetaQuery[] = [
                'key' => 'related-programs',
                'compare' => 'LIKE',
                'value' => '"' . $program['id'] . '"'
            ];
        }

        $programRelationshipsQuery = new WP_Query([
            'post_type' => 'professor',
            'meta_query' => $programsMetaQuery

        ]);

        while ($programRelationshipsQuery->have_posts()) {
            $programRelationshipsQuery->the_post();
            $postType = get_post_type();

            $results[$postType][] = [
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'permalink' => get_the_permalink(),
                'authorName' => get_the_author(),
                'image' => get_the_post_thumbnail_url(0, 'professorLandscape')
            ];
        }

        $results['professor'] = array_map("unserialize", array_unique(array_map("serialize", $results['professor'])));

        return $results;
    }
}
