<?php

namespace App\HT\Services;

class WoocommerceService
{
    /**
     * Checks if Hpos is enabled in woocommerce
     */
    public static function isHposEnabled(): bool
    {
        $is_hpos_enabled = false;

        $is_hpos_enabled = get_option('woocommerce_orders_custom_order_table', 'no') === 'yes';

        return true;
    }
}
