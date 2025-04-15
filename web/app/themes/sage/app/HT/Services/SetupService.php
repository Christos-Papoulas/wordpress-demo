<?php

namespace App\HT\Services;

class SetupService
{
    /**
     * Add support for svg
     *
     * @since  1.0.0
     */
    public static function extendFiletypes($data, $file, $filename, $mimes): array
    {
        global $wp_version;
        if ($wp_version !== '4.7.1') {
            return $data;
        }

        $filetype = wp_check_filetype($filename, $mimes);

        return [
            'ext' => $filetype['ext'],
            'type' => $filetype['type'],
            'proper_filename' => $data['proper_filename'],
        ];
    }

    /**
     * Add support for svg
     *
     * @since  1.0.0
     */
    public static function cc_mime_types(array $mimes): array
    {
        $mimes['svg'] = 'image/svg+xml';

        return $mimes;
    }

    /**
     * Returns login url
     *
     * @since  1.0.0
     */
    public static function get_login_url(): string
    {
        return home_url();
    }

    /**
     * Returns login title
     *
     * @since  1.0.0
     */
    public static function get_login_title(): string
    {
        return get_option('blogname');
    }

    public static function remove_jquery_migrate($scripts)
    {
        if (! is_admin() && isset($scripts->registered['jquery'])) {
            $script = $scripts->registered['jquery'];

            if ($script->deps) {
                $script->deps = array_diff($script->deps, ['jquery-migrate']);
            }
        }
    }

    /**
     * Register category for HT Gutenberg Blocks
     *
     * @since  1.0.0
     */
    public static function addCustomCategoriesForGutenbergBlocks(array $categories): array
    {
        $categories[] = [
            'slug' => 'ht-category',
            'title' => 'HT BLOCKS',
        ];
        $categories[] = [
            'slug' => 'ht-category-footer',
            'title' => 'HT FOOTER BLOCKS',
        ];

        return $categories;
    }

    /**
     * Remove author name and author url from share data
     *
     * @since  1.0.0
     */
    public static function removeAuthorName(array $data): array
    {
        unset($data['author_url']);
        unset($data['author_name']);

        return $data;
    }

    /**
     * Remove tools from custom toolbars
     *
     * @since  1.0.0
     */
    public static function removeFromHtToolbar(array $field): array
    {
        if ($field['type'] == 'wysiwyg' && ($field['toolbar'] == 'ht' || $field['toolbar'] == 'ht_title')) {
            // $field['tabs'] = 'visual';
            $field['media_upload'] = 0;
        }

        return $field;
    }

    /**
     * Add custom toolabars for wysiwyg editor
     *
     * @since  1.0.0
     */
    public static function addToolbars(array $toolbars): array
    {
        $toolbars['HT'] = [];
        $toolbars['HT'][1] = ['formatselect', 'bold', 'italic', 'underline', 'link', 'unlink', 'bullist', 'numlist', 'subscript', 'superscript', 'blockquote', 'charmap', 'removeformat', 'spellchecker', 'fullscreen', 'wp_help', 'forecolor'];

        $toolbars['HT_TITLE'] = [];
        $toolbars['HT_TITLE'][1] = ['formatselect', 'bold', 'italic', 'underline', 'subscript', 'charmap', 'removeformat', 'superscript', 'forecolor', 'fullscreen', 'wp_help'];

        return $toolbars;
    }

    /**
     * Add Custom Colors to the TinyMCE Editor
     *
     * @since  1.0.0
     */
    public static function addAppColorsToTinyMceEditor($init): array
    {
        $custom_colours = '
            "000000", "Black",
            "993300", "Burnt orange",
            "333300", "Dark olive",
            "003300", "Dark green",
            "003366", "Dark azure",
            "000080", "Navy Blue",
            "333399", "Indigo",
            "333333", "Very dark gray",
            "800000", "Maroon",
            "FF6600", "Orange",
            "808000", "Olive",
            "008000", "Green",
            "008080", "Teal",
            "0000FF", "Blue",
            "666699", "Grayish blue",
            "808080", "Gray",
            "FF0000", "Red",
            "FF9900", "Amber",
            "99CC00", "Yellow green",
            "339966", "Sea green",
            "33CCCC", "Turquoise",
            "3366FF", "Royal blue",
            "800080", "Purple",
            "999999", "Medium gray",
            "FF00FF", "Magenta",
            "FFCC00", "Gold",
            "FFFF00", "Yellow",
            "00FF00", "Lime",
            "00FFFF", "Aqua",
            "00CCFF", "Sky blue",
            "993366", "Red violet",
            "FFFFFF", "White",
            "FF99CC", "Pink",
            "FFCC99", "Peach",
            "FFFF99", "Light yellow",
            "CCFFCC", "Pale green",
            "CCFFFF", "Pale cyan",
            "99CCFF", "Light sky blue",
            "CC99FF", "Plum",
        ';

        // build colour grid default+custom colors
        $init['textcolor_map'] = '['.$custom_colours.']';

        // change the number of rows in the grid if the number of colors changes
        // 8 swatches per row
        $init['textcolor_rows'] = 10;

        return $init;
    }

    /**
     * Add "… Continued" to the excerpt.
     *
     * @since  1.0.0
     */
    public static function addContinueToExcerpt(): string
    {
        return sprintf(' &hellip; <a href="%s">%s</a>', get_permalink(), __('Continued', 'sage'));
    }

    /**
     * Enable show thankyou page action in wp-admin
     *
     * @since  1.0.0
     *
     * @param  WC_Order  $order
     */
    public static function ht_show_thank_you_page_order_admin_actions(array $actions, $order): array
    {
        if ($order->has_status(wc_get_is_paid_statuses())) {
            $actions['view_thankyou'] = 'Display thank you page';
        }

        return $actions;
    }

    /**
     * Redirect to thankyou page
     *
     * @since  1.0.0
     */
    public static function ht_redirect_thank_you_page_order_admin_actions($order): void
    {
        $url = $order->get_checkout_order_received_url();
        error_log($url);
        add_filter('redirect_post_location', function () use ($url) {
            return $url;
        });
    }

    /**
     * Hide shipping rates when free shipping is available.
     * Updated to support WooCommerce 2.6 Shipping Zones.
     *
     * @since  1.0.0
     *
     * @param  array  $rates  Array of rates found for the package.
     * @return array
     */
    public static function shipping_when_free_is_available($rates)
    {
        $free = [];
        foreach ($rates as $rate_id => $rate) {
            if ($rate->method_id === 'free_shipping') {
                $free[$rate_id] = $rate;
                break;
            }
        }

        return ! empty($free) ? $free : $rates;
    }

    /**
     * Add custom email classes to the list of email classes that WooCommerce loads
     *
     * @since  1.0.0
     */
    public static function addCustomWooCommerceEmailClasses($email_classes): array
    {
        // include our custom email class
        require __DIR__.'/../EmailClass/class-wc-email-customer-back-instock-product.php';

        // For Customer
        $email_classes['WC_Email_Customer_Back_Instock_Product'] = new \WC_Email_Customer_Back_Instock_Product;

        // For Admin
        // $email_classes['WC_Email_Timeout_Order'] = new \WC_Email_Timeout_Order();

        return $email_classes;
    }
}
