<?php

namespace App\Providers;

use App\HT\Interfaces\ConsentInterface;
use App\HT\Services\Consent\IubendaService;
use App\HT\Services\Wishlist;
use Illuminate\Contracts\Foundation\Application;
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
        $this->app->singleton(Wishlist::class, function (Application $app) {
            return new Wishlist;
        });

        $this->app->bind(ConsentInterface::class, function (Application $app) {
            if (config('theme.consentApiProvider', 'iubenda') == 'iubenda') {
                return new IubendaService;
            }
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
