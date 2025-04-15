<?php

namespace App\Options\Tabs;

use Illuminate\Support\Facades\Vite;

class ContactInfo
{
    public $address;

    public $city;

    public $address_number;

    public $zip_code;

    public $country;

    public $emails;

    public $contact_hours;

    public $phones;

    public $extra;

    public $contact_page_link;

    public $working_hours;

    public $socials;

    public $google_map_url;

    public function __construct()
    {
        $this->address = ht_get_field('address', 'options');
        $this->city = ht_get_field('city', 'options');
        $this->address_number = ht_get_field('address_number', 'options');
        $this->zip_code = ht_get_field('zip_code', 'options');
        $this->country = ht_get_field('country', 'options');
        $this->emails = ht_get_field('emails', 'options');
        $this->contact_hours = ht_get_field('contact_hours', 'options');
        $this->google_map_url = ht_get_field('google_map_url', 'options');
        $this->phones = ht_get_field('phones', 'options') ?? [];
        $this->extra = ht_get_field('extra_desc', 'options') ?? [];
        $this->contact_page_link = ht_get_field('contact_page_link', 'options') ?? [];
        $this->working_hours = ht_get_field('working_hours', 'options') ?? [];
        $this->socials = $this->setupSocials();
    }

    private function setupSocials()
    {
        $socials = ht_get_field('socials', 'options');
        if (empty($socials)) {
            return [];
        }

        foreach ($socials as $key => $row) {
            $icon = match ($row['platform']) {
                'facebook' => Vite::asset('resources/images/social/facebook.png'),
                'instagram' => Vite::asset('resources/images/social/instagram.png'),
                'google' => Vite::asset('resources/images/social/google.png'),
                'linkedin' => Vite::asset('resources/images/social/linkedin.png'),
                'twitter' => Vite::asset('resources/images/social/twitter.png'),
                'tiktok' => Vite::asset('resources/images/social/tiktok.png'),
                'youtube' => Vite::asset('resources/images/social/youtube.png'),
                default => ''
            };

            $inlineSvg = match ($row['platform']) {
                'facebook' => 'social/facebook',
                'instagram' => 'social/instagram',
                'google' => 'social/google',
                'linkedin' => 'social/linkedin',
                'twitter' => 'social/twitter',
                'tiktok' => 'social/tiktok',
                'youtube' => 'social/youtube',
                default => 'social/facebook'
            };
            $socials[$key]['icon'] = $icon;
            $socials[$key]['inline_svg'] = $inlineSvg;
        }

        return $socials;
    }
}
