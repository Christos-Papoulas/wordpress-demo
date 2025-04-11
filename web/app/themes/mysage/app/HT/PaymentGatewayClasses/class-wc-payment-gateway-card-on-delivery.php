<?php

if (! defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

/**
 * A custom payment gateway class for Credit or Debit card on delivery
 *
 * @since 1.0.0
 *
 * @extends \WC_Payment_Gateway
 */
class WC_Gateway_CAOD extends WC_Payment_Gateway
{
    public function __construct()
    {
        $this->id = 'caod';
        $this->method_title = __('Credit or Debit card on delivery', 'sage');
        $this->method_description = __('Let your shoppers pay upon delivery — by credit or debit card.', 'sage');

        $this->has_fields = false; // Set to true if you need additional input fields

        // Load settings
        $this->init_form_fields();
        $this->init_settings();

        $this->title = $this->get_option('title');
        $this->description = $this->get_option('description');
        $this->enabled = $this->get_option('enabled');

        // Save settings
        add_action('woocommerce_update_options_payment_gateways_'.$this->id, [$this, 'process_admin_options']);
    }

    // Define the settings fields in WooCommerce admin
    public function init_form_fields()
    {
        $this->form_fields = [
            'enabled' => [
                'title' => __('Enable/Disable', 'woocommerce'),
                'type' => 'checkbox',
                'label' => __('Enable Custom Payment', 'woocommerce'),
                'default' => 'yes',
            ],
            'title' => [
                'title' => __('Title', 'woocommerce'),
                'type' => 'safe_text',
                'description' => __('Payment method description that the customer will see on your checkout.', 'woocommerce'),
                'default' => __('Credit or Debit card on delivery', 'woocommerce'),
                'desc_tip' => true,
            ],
            'description' => [
                'title' => __('Description', 'woocommerce'),
                'type' => 'textarea',
                'description' => __('Payment method description that the customer will see on your website.', 'woocommerce'),
                'default' => __('Pay with credit or debit card upon delivery.', 'woocommerce'),
                'desc_tip' => true,
            ],
        ];
    }

    // Process the payment
    public function process_payment($order_id)
    {
        $order = wc_get_order($order_id);

        // Mark order
        $order->update_status('processing', __('Awaiting custom payment confirmation.', 'woocommerce'));

        // Reduce stock levels
        wc_reduce_stock_levels($order_id);

        // Empty cart
        WC()->cart->empty_cart();

        // Redirect user after payment
        return [
            'result' => 'success',
            'redirect' => $this->get_return_url($order),
        ];
    }
}
