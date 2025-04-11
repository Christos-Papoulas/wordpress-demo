<?php

namespace App\HT\Services\Product;

class ProductNewInService
{
    protected $todayTimestamp;

    /**
     * Finds product with _new_in_dates_to as today.
     * Updates their meta and fire typesense reindex.
     * Timezone its important. Use always the wordpress timezone as woocommerce does.
     * Timezone must be used the same in the importer reciever plugin.
     */
    public function run(): bool
    {
        // Get WordPress timezone
        $timezone = new \DateTimeZone(get_option('timezone_string') ?: 'UTC');

        // Get today's midnight timestamp in WordPress's timezone
        $datetime = new \DateTime('today', $timezone);
        $this->todayTimestamp = $datetime->getTimestamp();

        $this->findAndReindexProducts();

        return true;
    }

    /**
     * Finds product with _new_in_dates_to as today.
     * Updates their meta and fire typesense reindex.
     */
    private function findAndReindexProducts(): bool
    {
        $this->newInBadgeEnds();

        return true;
    }

    /**
     * Removes sale from product if the sale timestamp is today
     * Post meta are removed and _price updates to _regular_price
     */
    private function newInBadgeEnds(): bool
    {
        global $wpdb;

        // Query to get product IDs where sale price ends today
        $query = "
            SELECT DISTINCT pm.post_id 
            FROM {$wpdb->postmeta} AS pm
            WHERE pm.meta_key IN ('_new_in_dates_to')
            AND pm.meta_value = %d
        ";

        $product_ids = $wpdb->get_col($wpdb->prepare($query, $this->todayTimestamp));

        if (! empty($product_ids)) {
            foreach ($product_ids as $key => $product_id) {
                $product = wc_get_product($product_id);
                if (! $product) {
                    continue;
                }

                $product->delete_meta_data('_new_in_dates_to');
                $product->save();

                // Clear cache to ensure changes reflect immediately
                wc_delete_product_transients($product_id);

                // typesense reindex hook
                do_action('wp_after_insert_post', $product_id, get_post($product_id), true, null);
            }
        }

        return true;
    }
}
