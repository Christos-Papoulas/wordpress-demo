<?php

namespace App\Providers;

use Roots\Acorn\Sage\SageServiceProvider;

class ThemeServiceProvider extends SageServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        add_action('wp_enqueue_scripts', function () {
            wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
            wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');

            wp_enqueue_script(
                'sage-main', // handle
                get_template_directory_uri() . '/dist/js/main.js', // adjust to your built asset path
                [], // deps
                false, // version
                true // load in footer
            );

            wp_localize_script('sage-main', 'testSage', [
                'nonce' => wp_create_nonce('wp_rest'),
            ]);
        });

        add_action('login_enqueue_scripts', function () {
            wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
            wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
        });

        add_action('admin_init', function () {
            $roles = wp_get_current_user()->roles;

            if (count($roles) === 1 && $roles[0] === 'subscriber') {
                wp_redirect(home_url());
                exit;
            }
        });

        add_action('wp_loaded', function () {
            $roles = wp_get_current_user()->roles;

            if (count($roles) === 1 && $roles[0] === 'subscriber') {
                show_admin_bar(false);
            }
        });

        add_filter('login_headerurl', fn () => home_url('/'));

        add_filter('login_headertitle', fn () => get_bloginfo('name'));

        add_filter('wp_insert_post_data', function ($data, $postArr) {
            if ($data['post_type'] === 'note') {
                if (count_user_posts(get_current_user_id(), $data['post_type']) >= 5 && empty($postArr['ID'])) {
                    http_response_code(403);
                    die('You have reached your note limit.');
                }

                $data['post_content'] = sanitize_textarea_field($data['post_content']);
                $data['post_title'] = sanitize_text_field($data['post_title']);
            }
            // Force note posts to be private
            if ($data['post_type'] === 'note' && $data['post_status'] !== 'trash') {
                $data['post_status'] = 'private';
            }

            return $data;
        }, accepted_args: 2);

        // remove "Private: " from titles
        add_filter('the_title', fn ($title) => str_replace('Private: ', '', $title));
    }
}
