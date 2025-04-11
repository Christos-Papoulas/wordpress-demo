<?php

namespace App\View\Composers;

use App\Options\Tabs\ContactInfo;
use Roots\Acorn\View\Composer;

class Layouts extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'layouts.app',
        'layouts.shop',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with()
    {
        return [
            'siteName' => $this->siteName(),
            'contact' => new ContactInfo,
            'current_user' => $this->getCurrentUser(),
            'cart_contents_count' => $this->getCartContentsCount(),
            'my_account_url' => $this->getMyAccountURL(),
            'wishlist_url' => $this->getWishlistUrl()

        ];
    }

    /**
     * Returns wishlist url.
     *
     * @return string
     */
    private function getWishlistUrl(): string
    {
        $wishlist = ht_get_field('wishlist_page', 'option');
        if(empty($wishlist)){
            return '#';
        }
        return esc_url(get_permalink($wishlist->ID));
    }

    /**
     * Returns the site name.
     *
     * @return string
     */
    private function siteName()
    {
        return get_bloginfo('name', 'display');
    }

    /**
     * Returns current logged in user.
     *
     * @return false|WP_User
     */
    private function getCurrentUser()
    {
        if (! is_user_logged_in()) {
            return false;
        }
        $currentuser = wp_get_current_user();

        return $currentuser;
    }

    /**
     * Returns my account url.
     *
     * @return string
     */
    private function getMyAccountURL(): string
    {
        return wc_get_page_permalink('myaccount');
    }

    /**
     * Returns cart contents count.
     *
     * @return int
     */
    private function getCartContentsCount(): int
    {
        return WC()->cart->get_cart_contents_count();
    }
}
