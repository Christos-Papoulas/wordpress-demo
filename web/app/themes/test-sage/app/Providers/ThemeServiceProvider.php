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
    }
}
