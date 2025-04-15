<?php

namespace App\HT\Services\Cart;

use Illuminate\Support\Collection;
use App\HT\Services\Product\ProductService;

class TransformService
{
    /**
     * Transform items.
     *
     * @since   1.0.0
     */
    public static function transformItems($collection): Collection
    {
        return $collection->transform(function ($item) {
            $item_arr = $item;
            $item = (object) $item;
            $item->permalink = $item->data->get_permalink();
            $item->title = $item->data->get_title();
            $item->sku = $item->data->get_sku();
            $item->image = $item->data->get_image();

            $img_src = null;
            $thumb_id = get_post_thumbnail_id($item->data->get_ID(), 'woocommerce_thumbnail');
            if ($thumb_id) {
                $img_src = wp_get_attachment_image_src(get_post_thumbnail_id($item->data->get_ID()), 'woocommerce_thumbnail')[0];
            }
            
            if ($img_src === null && $item->data->get_type() == 'variation') {
                $img_src = wp_get_attachment_image_src(get_post_thumbnail_id($item->data->get_parent_id()), 'woocommerce_thumbnail')[0];
            }
            $item->image_src = $img_src ?? wc_placeholder_img_src('woocommerce_thumbnail');

            $item->price = $item->data->get_price();
            $item->price_html = wc_price($item->price);
            $item->regular_price = $item->data->get_regular_price();
            $item->line_total_with_tax = wc_price($item_arr['line_total'] + $item_arr['line_tax']);

            $item->stock_status = $item->data->get_stock_status();
            $item->max_qty = $item->data->get_max_purchase_quantity();
            $item->min_qty = $item->data->get_min_purchase_quantity();
            $item->stock_status = CartService::getCartItemStockStatus($item_arr);

            if($item->data->get_type() == 'variation'){
              
                $variation_labels = array_map(function ($term_slug, $attribute) {
                    $term = get_term_by('slug', $term_slug, str_replace('attribute_', '', $attribute));
                    return $term ? $term->name : ucfirst(str_replace(['attribute_pa_', '_'], ['', ' '], $attribute));
                }, $item->variation, array_keys($item->variation));
                
                $item->variation_attr = implode(', ', $variation_labels);
            }else{
                $item->variation_attr = '';
            }

            $item->backorder_note = '';
            if ($item->data->backorders_require_notification() && $item->data->is_on_backorder($item_arr['quantity'])) {
                $item->backorder_note = wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">'.esc_html__('Available on backorder', 'woocommerce').'</p>', $item->data->get_ID()));
            }

            $messages = [
                'incrementDisabled' => null,
                'decrementDisabled' => null,
            ];
            $item->messages = (object) $messages;

            $item->new_in_badge = $item->data->get_meta('_new_in_dates_to') ? true : false;
            // gtm4 required properties
            $item->brand = $item->data->get_attribute('pa_brand');
            $item->categories = ProductService::getProductCategories($item->data->get_ID());
            
            return $item;
        });
    }
}
