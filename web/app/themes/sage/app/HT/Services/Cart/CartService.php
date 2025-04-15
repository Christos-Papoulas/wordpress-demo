<?php

namespace App\HT\Services\Cart;

use App\HT\Services\ShippingService;

class CartService
{
    /**
     * Returns cart totals rows as html. Return in the same order as original file
     *
     * @since   1.0.0
     * @see plugins/woocommerce/templates/cart/cart-totals.php
     *
     * @param  bool $_POST['forMinicart']  Whether or not we need data for minicart. For minicart we dont need some data. Speed optimization
     * @param string $return_type  return json or array
     * @param  bool $returnHtmls whether to return htmls or not
     * 
     * @return void|array
     */
    public static function getCartTotals(string $return_type = 'json', bool $returnHtmls = true)
    {

        $subtotal = null;
        $coupons = null;
        $shipping = null;
        $shippingHtml = '';
        $feesHtml = '';
        $taxHtml = '';
        $discountHtml = '';
        if (isset($_POST['forMinicart']) && filter_var($_POST['forMinicart'], FILTER_VALIDATE_BOOLEAN)) {
            // for minicart we only need subtotal and total
        } else {
            // subtotal
            $subtotal = self::getCartSubtotal();

            // coupons
            $coupons = CouponService::getCartCoupons();

            // shipping
            $shipping = self::getCartShipping();

            if($returnHtmls){
                $shippingHtml = '';
                if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) {
                    ob_start();
                    wc_cart_totals_shipping_html();
                    $shippingHtml = ob_get_clean();
                } elseif (WC()->cart->needs_shipping() && get_option('woocommerce_enable_shipping_calc') === 'yes') {
                    ob_start();
                    echo '
                    <tr class="shipping">
                            <th class="hidden">';
                    // esc_html_e( 'Shipping', 'woocommerce' );
                    echo '</th>
                            <td data-title="';
                    echo esc_attr_e('Shipping', 'woocommerce');
                    echo '">';
                    woocommerce_shipping_calculator();
                    echo '</td>
                    </tr>';
                    $shippingHtml = ob_get_clean();
                }

                $feesHtml = '';
                // ob_start();
                // foreach ( WC()->cart->get_fees() as $fee ){
                //     echo '
                //         <tr class="fee flex w-full">
                //             <th class="w-1/2 py-4 border-b border-[#B6B6B6] flex items-center justify-between font-normal">PACKAGING</th>';
                //                 esc_html( $fee->name );
                //     echo    '<td data-title="'; esc_attr( $fee->name ); echo '" class="w-1/2 py-4 border-b border-[#B6B6B6] flex items-center justify-end font-medium">';
                //                 wc_cart_totals_fee_html( $fee );
                //     echo    '</td>
                //     </tr>
                //     ';
                // }
                // $feesHtml = ob_get_clean();
                
                // Tax should only be shown if prices are excluding tax.
                // If prices are including tax, we will show it as part of the total.
                $taxHtml = '';
                if ( wc_tax_enabled() && !WC()->cart->display_prices_including_tax()) {
                	$taxable_address = WC()->customer->get_taxable_address();
                	$estimated_text  = '';

                	if ( WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ) {
                		/* translators: %s location. */
                		$estimated_text = sprintf( ' <small>' . esc_html__( '(estimated for %s)', 'woocommerce' ) . '</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] );
                	}

                    ob_start();
                	if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) {
                		foreach ( WC()->cart->get_tax_totals() as $code => $tax ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
                            echo '
                			<tr class="flex justify-between w-full text-xs tax-rate tax-rate-'; esc_attr( sanitize_title( $code ) ); 
                            echo '">
                				    <th class="font-normal py-3 border-b border-[#e5e7eb] w-full flex">';
                                        echo esc_html( $tax->label ) . $estimated_text; 
                            echo    '</th>
                				    <td class="py-3 border-b border-[#e5e7eb] w-full flex justify-end" data-title="'; esc_attr( $tax->label ); echo '">';
                                        echo wp_kses_post( $tax->formatted_amount );
                            echo    '</td>
                			</tr>';
                		}
                	} else {
                		echo '
                		<tr class="tax-total">
                			    <th>';
                                    echo esc_html( WC()->countries->tax_or_vat() ) . $estimated_text;
                        echo    '</th>
                			    <td data-title="'; esc_attr( WC()->countries->tax_or_vat() ); echo '">';
                                    wc_cart_totals_taxes_total_html();
                        echo    '</td>
                		</tr>';
                	}
                    $taxHtml = ob_get_clean();
                }

                $discountHtml = '';
                ob_start();
                echo '
                    <tr class="discout-total">
                        <th>'
                    .__('Total Discount', 'sage').
                    '</th>
                        <td>'
                    .WC()->cart->get_total_discount().
                    '</td>
                    </tr>';
                $discountHtml .= ob_get_clean();
            }
        }

        $total = self::getCartTotal();
        $count = WC()->cart->get_cart_contents_count();

        $return = [
            'subtotal' => $subtotal,
            'coupons' => $coupons,
            'shipping' => $shipping,
            'shippingHtml' => $shippingHtml,
            'feesHtml' => $feesHtml,
            'taxHtml' => $taxHtml,
            'total' => $total,
            'count' => $count,
            'discountHtml' => $discountHtml,
        ];

        if ($return_type == 'array') {
            return $return;
        }

        wp_send_json($return, 200);
    }

    /**
     * Add product to cart
     *
     * @since  1.0.0
     */
    public static function AjaxAddToCart(): void
    {
        $product_id = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        $variation_id = $_POST['variation_id'];

        // if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id) && 'publish' === $product_status) {
        if (WC()->cart->add_to_cart($product_id, $quantity, $variation_id)) {
            wp_send_json_success(200);
        } else {
            $data = [
                'error' => true,
                'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id)];

            echo wp_send_json($data);
        }

        wp_die();
    }

    /**
     * Get user's cart.
     *
     * @since   1.0.0
     */
    public static function getCart(): void
    {
        $cart = collect(WC()->cart->get_cart());
        $cart = TransformService::transformItems($cart);

        wp_send_json($cart, 200);
    }

    /**
     * Get user's cart items and totals.
     *
     * @since   1.0.0
     *
     * @param  string  $return
     * @param  bool  $returnHtmls Whether to return htmls or not
     * @return void|array
     */
    public static function getCartAndTotals($return = 'json', $returnHtmls = true)
    {
        if (is_user_logged_in() && $return === 'json' && (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'security_nonce'))) {
            wp_send_json_error(['message' => 'Invalid nonce'], 403);
        }

        $cart = collect(WC()->cart->get_cart());
        $cart = TransformService::transformItems($cart);
        $totals = self::getCartTotals('array', $returnHtmls);

        if ($return == 'array') {
            return [
                'items' => $cart,
                'totals' => $totals,
            ];
        } else {
            wp_send_json_success([
                'items' => $cart,
                'totals' => $totals,
            ], 200);
        }
    }

    /**
     * Get additional cart informaton such as item count, total, discount coupons etc.
     *
     * @since   1.0.0
     */
    public static function getCartAdditionalInfo(): void
    {
        $count = WC()->cart->get_cart_contents_count();
        $total = WC()->cart->get_cart_total();
        $discount = WC()->cart->get_total_discount();
        $coupons = WC()->cart->get_applied_coupons();

        wp_send_json(['count' => $count, 'total' => $total, 'discount' => $discount, 'coupons' => $coupons], 200);
    }

    /**
     * Get Free Shipping data based on user zone. If no zone matches we use the defaul zone as woocommerce does.
     *
     * @since   1.0.0
     */
    public static function getFreeShippingAmount(): void
    {
        if (is_user_logged_in() && (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'security_nonce'))) {
            wp_send_json_error(['message' => 'Invalid nonce'], 403);
        }

        $amount_to_free_shipping = null;
        $packages = WC()->shipping->get_packages();

        $user_zone_id = null;

        if (isset($packages[0])) {
            $user_zone_id = \WC_Shipping_Zones::get_zone_matching_package($packages[0])->get_id() ?? null;
        }
        $zones = \WC_Shipping_Zones::get_zones();

        if (! empty($zones)) {
            foreach ($zones as $zone) {
                if ($zone['id'] == $user_zone_id) {
                    foreach ($zone['shipping_methods'] as $shipping_method) {
                        if (get_class($shipping_method) === 'WC_Shipping_Free_Shipping') {
                            $amount_to_free_shipping = $shipping_method->min_amount ?? null;
                        }
                    }
                }
            }
        }

        // Default Zone - Rest of the World zone
        $zone = new \WC_Shipping_Zone(0);
        $id = 0; // 0 is the default zone id by woocommerce
        $zones[$id] = $zone->get_data();
        $zones[$id]['formatted_zone_location'] = $zone->get_formatted_location();
        $zones[$id]['shipping_methods'] = $zone->get_shipping_methods();
        foreach ($zones[0]['shipping_methods'] as $shipping_method) {
            if (get_class($shipping_method) === 'WC_Shipping_Free_Shipping') {
                $amount_to_free_shipping = $shipping_method->min_amount ?? null;
            }
        }

        wp_send_json([
            '$user_zone_id' => $user_zone_id,
            'amount_to_free_shipping' => $amount_to_free_shipping,
        ], 200);
    }

    /**
     * Add item to cart.
     *
     * @since   1.0.0
     */
    public static function addItemToCart(): void
    {
        $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
        $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount($_POST['quantity']);
        $variation_id = absint($_POST['variation_id']);
        $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
        $product_status = get_post_status($product_id);
        $product = wc_get_product($product_id);

        if ($product->get_type() == 'variable' && ! $variation_id) {
            wp_send_json_error(['message' => __('Please Select A Variation', 'sage')], 200);
        }

        if ($passed_validation && $product_status === 'publish') {
            $key = WC()->cart->add_to_cart($product_id, $quantity, $variation_id);
            do_action('woocommerce_ajax_added_to_cart', $product_id);

            if (get_option('woocommerce_cart_redirect_after_add') === 'yes') {
                wc_add_to_cart_message([$product_id => $quantity], true);
            }

            wp_send_json_success([], 200);
        } else {
            wp_send_json_error(['message' => __('Something went wrong.', 'sage')], 200);
        }

        wp_die();
    }

    /**
     * Update Cart Item Quantity.
     *
     * @since   1.0.0
     */
    public static function updateCartItemQuantity(): void
    {
        if (is_user_logged_in() && (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'security_nonce'))) {
            wp_send_json_error(['message' => 'Invalid nonce'], 403);
        }

        $cart_item_key = $_POST['cart_item_key'];

        $cart_item = WC()->cart->get_cart_item($cart_item_key);

        // Get the quantity of the item in the cart
        $cart_item_quantity = apply_filters('woocommerce_stock_amount_cart_item', apply_filters('woocommerce_stock_amount', preg_replace("/[^0-9\.]/", '', filter_var($_POST['quantity'], FILTER_SANITIZE_NUMBER_INT))), $cart_item_key);

        // Update cart validation
        $passed_validation = apply_filters('woocommerce_update_cart_validation', true, $cart_item_key, $cart_item, $cart_item_quantity);

        // Update the quantity of the item in the cart
        if ($passed_validation) {
            WC()->cart->set_quantity($cart_item_key, $cart_item_quantity, true);

            wp_send_json_success([], 200);
        } else {
            wp_send_json_error(['message' => __('An error occured', 'sage')], 200);
        }

        wp_die();
    }

    /**
     * Remove Item from user's cart.
     *
     * @since   1.0.0
     */
    public static function removeItemFromCart(): void
    {
        if (is_user_logged_in() && (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'security_nonce'))) {
            wp_send_json_error(['message' => 'Invalid nonce'], 403);
        }

        $cart_item_key = $_POST['cart_item_key'];

        $cart = WC()->cart;

        $cart_contents = $cart->get_cart();
        $removed_quantity = isset($cart_contents[$cart_item_key]) ? $cart_contents[$cart_item_key]['quantity'] : 0;

        $cart->remove_cart_item($cart_item_key);
    
        wp_send_json_success([
            'removed_quantity' => $removed_quantity,
        ], 200);
    }

    /**
     * Clear user's cart.
     *
     * @since   1.0.0
     */
    public static function clearCart(): void
    {
        if (is_user_logged_in() && (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'security_nonce'))) {
            wp_send_json_error(['message' => 'Invalid nonce'], 403);
        }

        WC()->cart->empty_cart();
        wp_send_json_success(null, 200);
    }

    /**
     * Return an object with the stock status, and maybe the error msg for a cart item.
     *
     * @param  array  $values
     *
     * @see woocommerce/includes/class-wc-cart.php, line:757, function:check_cart_item_stock()
     *
     * @return object
     */
    public static function getCartItemStockStatus($values)
    {
        $arr = [
            'status' => 'instock',
            'msg' => '',
        ];

        global $woocommerce;

        // Get the current cart object
        $cart = $woocommerce->cart;

        $product_qty_in_cart = $cart->get_cart_item_quantities();
        $current_session_order_id = isset(WC()->session->order_awaiting_payment) ? absint(WC()->session->order_awaiting_payment) : 0;

        $product = $values['data'];

        // Check stock based on stock-status.
        if (! $product->is_in_stock()) {
            /* translators: %s: product name */
            $arr['status'] = 'outofstock';
            $arr['msg'] = sprintf(__('Sorry, "%s" is not in stock. Please edit your cart and try again. We apologize for any inconvenience caused.', 'woocommerce'), $product->get_name());

            return (object) $arr;
        }

        // We only need to check products managing stock, with a limited stock qty.
        if ($product->managing_stock() && ! $product->backorders_allowed()) {
            // Check stock based on all items in the cart and consider any held stock within pending orders.
            $held_stock = wc_get_held_stock_quantity($product, $current_session_order_id);
            $required_stock = $product_qty_in_cart[$product->get_stock_managed_by_id()];

            // Allows filter if product have enough stock to get added to the cart.
            if (apply_filters('woocommerce_cart_item_required_stock_is_not_enough', $product->get_stock_quantity() < ($held_stock + $required_stock), $product, $values)) {
                /* translators: 1: product name 2: quantity in stock */
                $arr['status'] = 'outofstock';
                $arr['msg'] = sprintf(__('Sorry, we do not have enough "%1$s" in stock to fulfill your order (%2$s available). We apologize for any inconvenience caused.', 'woocommerce'), $product->get_name(), wc_format_stock_quantity_for_display($product->get_stock_quantity() - $held_stock, $product));

                return (object) $arr;
            }
        }

        return (object) $arr;
    }

    /**
     * Get Cart subtotal
     *
     * @param  bool  $compound
     *
     * @see woocommerce get_cart_subtotal()
     */
    public static function getCartSubtotal($compound = false): array
    {
        $cart_subtotal_vat_suffix = null;

        /**
         * If the cart has compound tax, we want to show the subtotal as cart + shipping + non-compound taxes (after discount).
         */
        if ($compound) {
            $cart_subtotal = (WC()->cart->get_cart_contents_total() + WC()->cart->get_shipping_total() + WC()->cart->get_taxes_total(false, false));
        } elseif (WC()->cart->display_prices_including_tax()) {
            $cart_subtotal = WC()->cart->get_subtotal() + WC()->cart->get_subtotal_tax();

            if (WC()->cart->get_subtotal_tax() > 0 && ! wc_prices_include_tax()) {
                $cart_subtotal_vat_suffix = WC()->countries->inc_tax_or_vat();
            }
        } else {
            $cart_subtotal = WC()->cart->get_subtotal();

            if (WC()->cart->get_subtotal_tax() > 0 && wc_prices_include_tax()) {
                $cart_subtotal_vat_suffix = WC()->countries->ex_tax_or_vat();
            }
        }
        $cart_subtotal = apply_filters('woocommerce_cart_subtotal', $cart_subtotal, $compound, WC()->cart);

        return [
            'amount' => $cart_subtotal,
            'formatted' => wc_price($cart_subtotal),
            'vat_suffix' => $cart_subtotal_vat_suffix,
        ];
    }

    /**
     * Get Cart total
     *
     * @see woocommerce wc_cart_totals_order_total_html()
     */
    public static function getCartTotal(): array
    {
        $amount = WC()->cart->get_total('NOT VIEW');
        $vat_suffix = null;

        // If prices are tax inclusive, show taxes here.
        if (wc_tax_enabled() && WC()->cart->display_prices_including_tax()) {
            $tax_string_array = [];
            $cart_tax_totals = WC()->cart->get_tax_totals();

            if (get_option('woocommerce_tax_total_display') === 'itemized') {
                foreach ($cart_tax_totals as $code => $tax) {
                    $tax_string_array[] = sprintf('%s %s', $tax->formatted_amount, $tax->label);
                }
            } elseif (! empty($cart_tax_totals)) {
                $tax_string_array[] = sprintf('%s %s', wc_price(WC()->cart->get_taxes_total(true, true)), WC()->countries->tax_or_vat());
            }

            if (! empty($tax_string_array)) {
                $taxable_address = WC()->customer->get_taxable_address();
                if (WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping()) {
                    $country = WC()->countries->estimated_for_prefix($taxable_address[0]).WC()->countries->countries[$taxable_address[0]];
                    /* translators: 1: tax amount 2: country name */
                    $tax_text = wp_kses_post(sprintf(__('(includes %1$s estimated for %2$s)', 'woocommerce'), implode(', ', $tax_string_array), $country));
                } else {
                    /* translators: %s: tax amount */
                    $tax_text = wp_kses_post(sprintf(__('(includes %s)', 'woocommerce'), implode(', ', $tax_string_array)));
                }

                $vat_suffix = '<small class="includes_tax">'.$tax_text.'</small>';
            }
        }

        return [
            'amount' => $amount,
            'formatted' => wc_price($amount),
            'vat_suffix' => $vat_suffix,
        ];
    }

    /**
     * Get Cart shipping
     *     *
     * @see woocommerce
     *
     * @return array
     */
    public static function getCartShipping()
    {
        $shipping_total = WC()->cart->get_shipping_total($context = 'NOT VIEW') + WC()->cart->get_shipping_tax($context = 'NOT VIEW');
        $shipping = [
            'show_shipping_methods' => false,
            'show_calculator' => false,
            'shipping_zones' => [],
            'shipping_total' => $shipping_total,
            'shipping_total_formatted' => wc_price($shipping_total),
        ];

        if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) {
            $shipping['show_shipping_methods'] = true;

            // WC()->shipping()->get_packages() returns an empty array if the shipping is not calculated for the products added to cart.
            // So you might have to calculate shipping for packages by WC()->cart->calculate_shipping()
            WC()->cart->calculate_shipping();

            $packages = WC()->shipping()->get_packages();
            $first = true;

            foreach ($packages as $i => $package) {
                $chosen_method = isset(WC()->session->chosen_shipping_methods[$i]) ? WC()->session->chosen_shipping_methods[$i] : '';
                $product_names = [];

                if (count($packages) > 1) {
                    foreach ($package['contents'] as $item_id => $values) {
                        $product_names[$item_id] = $values['data']->get_name().' &times;'.$values['quantity'];
                    }
                    $product_names = apply_filters('woocommerce_shipping_package_details_array', $product_names, $package);
                }

                $shipping['shipping_zones'][] = [
                    'package' => $package,
                    'available_methods' => $package['rates'],
                    'show_package_details' => count($packages) > 1,
                    'show_shipping_calculator' => is_cart() && apply_filters('woocommerce_shipping_show_shipping_calculator', $first, $i, $package),
                    'package_details' => implode(', ', $product_names),
                    'package_name' => apply_filters('woocommerce_shipping_package_name', (($i + 1) > 1) ? sprintf(_x('Shipping %d', 'shipping packages', 'woocommerce'), ($i + 1)) : _x('Shipping', 'shipping packages', 'woocommerce'), $i, $package),
                    'index' => $i,
                    'chosen_method' => $chosen_method,
                    'formatted_destination' => WC()->countries->get_formatted_address($package['destination'], ', '),
                    'has_calculated_shipping' => WC()->customer->has_calculated_shipping(),
                ];

                $first = false;
            }
        } elseif (WC()->cart->needs_shipping() && get_option('woocommerce_enable_shipping_calc') === 'yes') {
            $shipping['show_calculator'] = true;
        }

        return $shipping;
    }

    /**
     * Check if product or variation exists in cart and return its qty
     *
     * @return mixed
     */
    public static function checkIfProductExistsInCart()
    {
        $product_id = absint($_POST['product_id']);
        $variation_id = absint($_POST['variation_id']);

        $found = false;
        $qty = 0;
        foreach (WC()->cart->get_cart() as $cart_item) {
            if ($cart_item['product_id'] === $product_id && $cart_item['variation_id'] === $variation_id) {
                $found = true;
                $qty = $cart_item['quantity'];
                break;
            }
        }

        if ($found) {
            wp_send_json_success([
                'qty' => $qty,
            ], 200);
        } else {
            wp_send_json_error(['message' => 'Product not in cart'], 404);
        }

    }

    /**
     * Decrease qty for a product in cart
     *
     * @return mixed
     */
    public static function decreaseProductQtyFromCart()
    {
        $product_id = absint($_POST['product_id']);
        $variation_id = absint($_POST['variation_id']);

        $found = false;
        foreach (WC()->cart->get_cart() as $key => $cart_item) {
            if ($cart_item['product_id'] === $product_id && $cart_item['variation_id'] === $variation_id) {
                $newQty = $cart_item['quantity'] - 1;
                $found = true;
                WC()->cart->set_quantity($key, $newQty, true);
                break;
            }
        }

        if ($found) {
            wp_send_json_success([
                'qty' => $newQty,
            ], 200);
        } else {
            wp_send_json_error(['message' => 'Product not in cart'], 404);
        }

    }

    /**
     * Modify shipping method label
     *
     * @param  string  $label
     * @param  object  $method
     * @return string
     */
    public static function woocommerceCartShippingMethodFullLabel($label, $method)
    {
        if ($method->get_cost() <= 0) {
            $label .= '<span class="text-[#229E44] pr-5 ">Free</span>';
        }

        return $label;
    }

    /**
     * Create WC cart from local storage data and maybe update the customer shipping data
     *
     * @param  $_POST['data']  json string
     * @param  $data->items  array of objects
     * @param  $data->shippingFormData  string
     * @return mixed
     */
    public static function createWCcartFromLocalStorage()
    {
        if (is_user_logged_in() && (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'security_nonce'))) {
            wp_send_json_error(['message' => 'Invalid nonce'], 403);
        }
        
        $data = json_decode(stripslashes($_POST['data']));
        WC()->cart->empty_cart();
        $errors = [];
        $total_not_added = 0;

        foreach ($data->items as $row) {
            if ($row->product_id) {

                $product = $row->variation_id == 0 ? wc_get_product($row->product_id) : wc_get_product($row->variation_id);

                if (! $product) {
                    $errors[$row->product_id.'_'.$row->variation_id] = [
                        'quantity_added' => 0,
                        'quantity_not_added' => $row->quantity,
                    ];
                    $total_not_added += $row->quantity;
                }

                // Get available stock quantity
                $stock_quantity = $product->get_stock_quantity();
                $final_quantity = $row->quantity;

                // if product is on backorder, allow adding
                if (! $product->is_on_backorder()) {
                    // If requested quantity is more than stock, set it to available stock
                    if ($stock_quantity && $final_quantity > $stock_quantity) {
                        $final_quantity = $stock_quantity;

                        $not_added = $row->quantity - $final_quantity;
                        $errors[$row->product_id.'_'.$row->variation_id] = [
                            'quantity_added' => $final_quantity,
                            'quantity_not_added' => $not_added,
                        ];
                        $total_not_added += $not_added;
                    }
                }

                WC()->cart->add_to_cart($row->product_id, $final_quantity, $row->variation_id, (array)$row->variation, []);
            }
        }

        $return = self::getCartAndTotals('array');

        if ($total_not_added > 0) {
            $return['error_title'] = __('Sorry, we could not add all products to the cart', 'sage');
            $return['message'] = $total_not_added.' '.__('Product/s removed from your cart.', 'sage');
            $return['errors'] = $errors;
            $return['total_not_added'] = $total_not_added;
            wp_send_json_error($return, 200);
        }
        wp_send_json_success($return, 200);
    }

    /**
     * Update session Billings and Shipping postcode
     * We have to set the billing postcode also, because we dont know if
     * the customer want to ship to a different address yet.
     *
     * @param  string  $_POST['postcode']
     */
    public static function updateSessionBillingAndShippingPostcode(): mixed
    {
        if (is_user_logged_in() && (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'security_nonce'))) {
            wp_send_json_error(['message' => 'Invalid nonce'], 403);
        }

        WC()->customer->set_billing_postcode($_POST['postcode']);
        WC()->customer->set_shipping_postcode($_POST['postcode']);

        wp_send_json_success([
            'session_shipping_postcode' => WC()->customer->get_shipping_postcode(),
        ], 200);
    }

    /**
     * Validate cart before checkout. Check for frozen products
     * Same check in App\HT\Services\CheckoutService\checkForFrozenProducts;
     */
    public static function validateCartBeforeCheckout(): mixed
    {
        if (is_user_logged_in() && (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'security_nonce'))) {
            wp_send_json_error(['message' => 'Invalid nonce'], 403);
        }
        
        WC()->session->set('chosen_store', $_POST['chosen_store'] ?? null);

        $restricted_category_slug = 'katepsygmena';
        $restricted_category = get_term_by('slug', $restricted_category_slug, 'product_cat'); // WPML works
        if (! $restricted_category) {
            wp_send_json_success([], 200);
        }

        $must_be_removed_products = [];

        $chosen_shipping_methods = WC()->session->get('chosen_shipping_methods', []);

        $checkForFrozen = false;
        foreach ($chosen_shipping_methods as $key => $method) {
            if (strpos($method, 'local_pickup') === 0) {

                $store_available_for_frozen = ht_get_field('store_custom_fields_frozen_products_enabled', WC()->session->get('chosen_store'));
                if ($store_available_for_frozen !== 'yes') {
                    $checkForFrozen = true;
                }

            } elseif (strpos($method, 'free_shipping') === 0) {

                if (! ShippingService::isPostCodeInAthens(WC()->customer->get_shipping_postcode())) {
                    $checkForFrozen = true;
                }
            }
        }

        if ($checkForFrozen) {
            $wc_cart = WC()->cart->get_cart();

            foreach ($wc_cart as $key => $cart_item) {
                $product_id = $cart_item['product_id'];
                $product_categories = wp_get_post_terms($product_id, 'product_cat', ['fields' => 'ids']);

                // Check if product is in the restricted cat.
                if (has_term($restricted_category->slug, 'product_cat', $product_id)) {
                    $must_be_removed_products[] = $cart_item['data']->get_id();

                    continue;
                }

                // Check if the product is in a child category of the restricted cat.
                foreach ($product_categories as $category_id) {
                    // Get all parent categories of this category
                    $ancestors = get_ancestors($category_id, 'product_cat');
                    // Check if the restricted category is in the ancestors
                    if (in_array($restricted_category->term_id, $ancestors)) {
                        $must_be_removed_products[] = $cart_item['data']->get_id();
                        break;
                    }
                }
            }

            if (! empty($must_be_removed_products)) {
                $cart = collect($wc_cart);
                $cart = TransformService::transformItems($cart);

                $cart->transform(function ($item) use ($must_be_removed_products) {
                    if (in_array($item->product_id, $must_be_removed_products)) {
                        $item->must_be_removed = true;
                    } else {
                        $item->must_be_removed = false;
                    }

                    return $item;
                });
                wp_send_json_error([
                    'items' => $cart,
                    'message' => __('Your cart contains frozen products. Υou will need to remove these products in order to proceed to checkout for pick up from an M&S Store outside of Attica.', 'sage'),
                ], 400);
            }
        }

        wp_send_json_success([], 200);

    }
}
