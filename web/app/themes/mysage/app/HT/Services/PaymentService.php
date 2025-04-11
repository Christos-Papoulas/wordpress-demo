<?php

namespace App\HT\Services;

class PaymentService
{
    public const POS_INPUT_NAME = 'pos_for';

    public const POS_METAKEY_NAME = '_pos_for';

    /**
     * Maybe unset payment methods
     */
    public static function maybeUnsetPaymentMethods(array $available_gateways): array
    {
        if (empty(WC()->session)) {
            return $available_gateways;
        }

        $chosen_shipping_methods = WC()->session->get('chosen_shipping_methods');
        if (empty($chosen_shipping_methods)) {
            return $available_gateways;
        }

        foreach ($chosen_shipping_methods as $key => $method) {

            // Cod only available when shipping is local_pickup
            if (strpos($method, 'local_pickup') === 0) {
                unset($available_gateways['eurobank_gateway']);
                unset($available_gateways['cod']);
                unset($available_gateways['caod']);
            } elseif (strpos($method, 'free_shipping') === 0) {
                unset($available_gateways['colp']);
            }
        }

        return $available_gateways;
    }

    /**
     * Add custom payment gateways
     * WC_Gateway_COLP = Cash on local pick up , like COD native woocommerce gateway
     * WC_Gateway_CAOD = Card on delivery , like COD native woocommerce gateway
     *
     * @see wp-content/plugins/woocommerce/includes/gateways/cod/class-wc-gateway-cod.php
     */
    public static function addCustomPaymentGateways(array $gateways): array
    {
        $gateways[] = 'WC_Gateway_COLP';
        $gateways[] = 'WC_Gateway_CAOD';

        return $gateways;
    }

    /**
     * Saves pos field if caod is selected as payment method.
     *
     * @return void
     */
    public static function save_pos_field($order_id, $data)
    {
        $order = wc_get_order($order_id);

        if ($order) {
            $payment_method = $order->get_payment_method();

            if ($payment_method == 'caod' && isset($_POST[self::POS_INPUT_NAME])) {
                $order->update_meta_data(self::POS_METAKEY_NAME, sanitize_text_field($_POST[self::POS_INPUT_NAME]));
                $order->save();
            }
        }
    }

    /**
     * Show pos name if order payment method is caod
     *
     * @param  string  $title
     * @param  WC_Order  $order
     */
    public static function maybeShowPosName($title, $order): string
    {
        if ($order instanceof \WC_Order) {
            $payment_method = $order->get_payment_method();
            if ($payment_method === 'caod') {
                $bank = $order->get_meta('_pos_for');

                if ($bank) {
                    $title .= ' ('.$bank.')';
                }
            }
        }

        return $title;
    }
}
