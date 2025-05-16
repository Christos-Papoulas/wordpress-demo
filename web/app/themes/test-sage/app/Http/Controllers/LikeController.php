<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use WP_Query;

class LikeController
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'professor' => 'required|integer',
        ]);

        $query = new WP_Query([
            'author' => get_current_user_id(),
            'post_type' => 'like',
            'meta_query' => [
                [
                    'key' => 'liked_professor_id',
                    'compare' => '=',
                    'value' => $data['professor'],
                ],
            ],
        ]);

        if ($query->found_posts > 0) {
            return response(['success' => false, 'message' => 'You have already liked this professor'], 400);
        }

        if (get_post_type($data['professor']) !== 'professor') {
            return response(['success' => false, 'message' => 'You can only like professors'], 400);
        }

        $result = wp_insert_post([
            'post_type' => 'like',
            'post_status' => 'publish',
            'post_title' => 'Like',
            'post_author' => get_current_user_id(),
            'meta_input' => [
                'liked_professor_id' => $data['professor'],
            ],
        ]);

        return response(['id' => $result, 'professor' => $data['professor']]);
    }

    public function destroy(string $id)
    {
        if (get_post_type($id) !== 'like') {
            return response(['success' => false, 'message' => 'The is not a like'], 400);
        }

        if (get_current_user_id() != get_post_field('post_author', $id)) {
            return response(['success' => false, 'message' => 'You can only delete your own like'], 400);
        }

        $result = wp_delete_post($id, true);

        if (empty($result)) {
            return response(['success' => false], 400);
        }

        return response(['success' => true, 'result' => $result]);
    }
}
