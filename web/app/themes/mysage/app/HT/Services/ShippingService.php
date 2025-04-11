<?php

namespace App\HT\Services;

class ShippingService
{
    /**
     * Maybe unset shipping methods
     */
    public static function maybeUnsetShippingMethods(array $rates, array $package): array
    {
        $shipping_postcode = $package['destination']['postcode'];

        // Remove free shipping if the postcode is not in Athens
        if (! self::isPostCodeInAthens($shipping_postcode)) {
            foreach ($rates as $key => $rate) {
                if ($rate->get_method_id() == 'free_shipping') {
                    unset($rates[$key]);
                    break;
                }
            }
        }

        return $rates;
    }

    /**
     * Check if the postcode is in Athens
     */
    public static function isPostCodeInAthens(string $postcode): bool
    {
        global $wpdb;

        $table_name = $wpdb->prefix.PostCodesService::TABLE_NAME;

        $in_athens = $wpdb->get_var(
            $wpdb->prepare("SELECT in_athens FROM $table_name WHERE postcode = %s", $postcode)
        );

        if ($in_athens) {
            return true;
        }

        return false;
    }
}
