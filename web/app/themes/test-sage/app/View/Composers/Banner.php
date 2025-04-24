<?php

namespace App\View\Composers;

use Illuminate\Support\Facades\Vite;
use Roots\Acorn\View\Composer;

class Banner extends Composer
{
    protected static $views = [
        'components.banner',
    ];

    public function banner(): string
    {
        return get_field('page-banner-background-image')['sizes']['pageBanner'] ?? Vite::asset('resources/images/library-hero.jpg');
    }
}
