<?php

namespace App\HT\Services\Product;

class ProductSalePriceService
{
    protected $todayTimestamp;

    /**
     * Finds product with _sale_price_dates_from or _sale_price_dates_to as today.
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
     * Finds product with _sale_price_dates_from or _sale_price_dates_to as today.
     * Updates their meta and fire typesense reindex.
     */
    private function findAndReindexProducts(): bool
    {
        $this->saleEnds();
        $this->saleStarts();

        return true;
    }

    /**
     * Updates product to be on sale if date from timestamp is today
     * Post meta are updated and _price updates to _sale_price
     */
    private function saleStarts(): bool
    {
        global $wpdb;

        // Query to get product IDs where sale price start is today
        $query = "
            SELECT DISTINCT pm.post_id 
            FROM {$wpdb->postmeta} AS pm
            WHERE pm.meta_key IN ('_sale_price_dates_from')
            AND pm.meta_value = %d
        ";

        $product_ids = $wpdb->get_col($wpdb->prepare($query, $this->todayTimestamp));

        if (! empty($product_ids)) {
            foreach ($product_ids as $key => $product_id) {
                $product = wc_get_product($product_id);
                if (! $product) {
                    continue;
                }

                $sale_price = $product->get_sale_price();
                $product->set_price($sale_price);
                $product->save();

                // Manually update the `_price` meta field. I dont know why it doesnt recalculate like saleEnds does.
                update_post_meta($product_id, '_price', $sale_price);

                // Clear cache to ensure changes reflect immediately
                wc_delete_product_transients($product_id);

                // typesense reindex hook
                do_action('wp_after_insert_post', $product_id, get_post($product_id), true, null);
            }
        }

        return true;
    }

    /**
     * Removes sale from product if the sale timestamp is today
     * Post meta are removed and _price updates to _regular_price
     */
    private function saleEnds(): bool
    {
        global $wpdb;

        // Query to get product IDs where sale price ends today
        $query = "
            SELECT DISTINCT pm.post_id 
            FROM {$wpdb->postmeta} AS pm
            WHERE pm.meta_key IN ('_sale_price_dates_to')
            AND pm.meta_value = %d
        ";

        $product_ids = $wpdb->get_col($wpdb->prepare($query, $this->todayTimestamp));

        if (! empty($product_ids)) {
            foreach ($product_ids as $key => $product_id) {
                $product = wc_get_product($product_id);
                if (! $product) {
                    continue;
                }

                $regular_price = $product->get_regular_price();
                $product->set_sale_price(null);
                $product->set_date_on_sale_from(null);
                $product->set_date_on_sale_to(null);
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

// // get all translations, includes original product
// $trid = apply_filters( 'wpml_element_trid', null, $product_id, 'post_product');
// $translations = apply_filters( 'wpml_get_element_translations', null, $trid, 'post_product');
// foreach ($translations as $lang => $translation) {
// }
