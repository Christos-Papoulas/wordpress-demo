<?php

namespace App\HT\Services;

class UserService
{
    /**
     * Authenticates and logs a user in with ‘remember’ capability.
     *
     * @since 1.0.0
     * @see https://developer.wordpress.org/reference/functions/wp_signon/
     *
     * @param  array  $credentials  array with username, pass, remember.
     * @param  string  $secure_cookie  Whether to use secure cookie.
     * @return void WP_User on success, WP_Error on failure.
     */
    public static function loginUser(): void
    {
        $credentials = json_decode(stripslashes($_POST['credentials'] ?? []), true);
        $secure_cookie = json_decode(stripslashes($_POST['secure_cookie'] ?? ''), true);

        $result = wp_signon($credentials, $secure_cookie);

        if (is_wp_error($result)) {
            wp_send_json_error([
                'wp_error' => $result,
            ], 200);
        } else {
            wp_set_current_user($result->data->ID);
            wp_set_auth_cookie($result->data->ID);
            wp_send_json_success([
                'user' => [
                    'ID' => $result->data->ID,
                    'nicename' => $result->data->user_nicename,
                    'email' => $result->data->user_email,
                    'registered' => $result->data->user_registered,
                    'status' => $result->data->user_status,
                    'display_name' => $result->data->display_name,
                ],
            ], 200);
        }
    }

    /**
     * Check if an email is a registered user in WooCommerce.
     *
     * @param  string  $email  The email address to check.
     * @return bool True if the email is registered, false otherwise.
     */
    public static function checkIfEmailExists(): bool
    {
        $email = $_POST['email'];
        if (! is_email($email)) {
            wp_send_json_error([
                'wp_error' => 'This doesnt seem like an email.',
            ], 400);
        }
        $user = get_user_by('email', $email);
        if ($user) {
            wp_send_json_success();
        }
        wp_send_json_error([
            'wp_error' => 'Email not found.',
        ], 404);
    }
}
