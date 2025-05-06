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
    }
}
