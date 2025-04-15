<?php

namespace App\HT\Services;

use PH7\Eu\Vat\Provider\Europa;
use PH7\Eu\Vat\Validator;

class InvoiceService
{
    public const INVOICE_ENABLED_INPUT_NAME = 'ht_invoice_enabled';

    public const VAT_METAKEY_NAME = '_billing_vat';

    public const VAT_INPUT_NAME = 'ht_invoice_plugin_vat';

    public const TAX_OFFICE_METAKEY_NAME = '_billing_tax_office';

    public const TAX_OFFICE_INPUT_NAME = 'ht_invoice_plugin_tax_office';

    public const COMPANY_NAME_METAKEY_NAME = '_billing_company_name';

    public const COMPANY_NAME_INPUT_NAME = 'ht_invoice_plugin_company_name';

    public const COMPANY_ACTIVITY_METAKEY_NAME = '_billing_company_activity';

    public const COMPANY_ACTIVITY_INPUT_NAME = 'ht_invoice_plugin_company_activity';

    public static function add_checkout_fields($fields)
    {
        $fields['billing'][self::INVOICE_ENABLED_INPUT_NAME] = [
            'type' => 'checkbox',
            'label' => __('Need Invoice?', 'sage'),
            'class' => ['hidden'],
            'clear' => true,
            'priority' => 0,
        ];

        $fields['billing'][self::VAT_INPUT_NAME] = [
            'label' => __('VAT', 'sage'),
            'required' => true,
            'placeholder' => 'EL123456789',
            'class' => ['ht_invoice_plugin_input md:col-span-2', 'hidden'],
            'clear' => true,
            'priority' => 1,
        ];

        $fields['billing'][self::COMPANY_NAME_INPUT_NAME] = [
            'label' => __('Company Name', 'sage'),
            'required' => true,
            'class' => ['ht_invoice_plugin_input md:col-span-2', 'hidden'],
            'priority' => 2,
            'clear' => true,
        ];

        $fields['billing'][self::TAX_OFFICE_INPUT_NAME] = [
            'label' => __('Tax Office', 'sage'),
            'required' => true,
            'class' => ['ht_invoice_plugin_input md:col-span-2', 'hidden'],
            'priority' => 3,
            'clear' => true,
        ];

        $fields['billing'][self::COMPANY_ACTIVITY_INPUT_NAME] = [
            'label' => __('Company Activity', 'sage'),
            'required' => true,
            'class' => ['ht_invoice_plugin_input md:col-span-2', 'hidden'],
            'clear' => true,
            'priority' => 4,
        ];

        return $fields;
    }

    public static function validateVatNumber()
    {
        if (is_user_logged_in() && (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'security_nonce'))) {
            wp_send_json_error(['message' => 'Invalid nonce'], 403);
        }
        
        $vat_id = $_POST['vat'];

        $type = ht_get_field('invoice_validation_type', 'options') ?? 'vies';

        if ($type === 'aade') {
            self::validate_greek_afm($vat_id);
        }

        if ($type === 'vies') {
            self::validate_vies_afm($vat_id);
        }

        wp_send_json_error([
            'title' => __('Invalid Request.', 'sage'),
            'message' => __('Did you forget a field?', 'sage'),
        ]);
    }

    public static function validate_vies_afm($vat_id)
    {
        $country_code = substr($vat_id, 0, 2);
        $vat = substr($vat_id, 2);

        try {
            $vies = new Validator(new Europa, $vat, $country_code);
        } catch (\Exception $e) {
            wp_send_json_error([
                'title' => __('We could not validate your vat number.', 'sage'),
                'message' => __('VIES service is not available at the moment or vat number is wrong. Please try again later.', 'sage'),
            ]);
        }

        if ($vies->check()) {
            $response = [
                'vat_id' => $vies->getVatNumber(),
                'name' => $vies->getName(),
                'tax_office' => '',
                'activity' => '',
            ];
            wp_send_json_success($response);
        } else {
            wp_send_json_error([
                'title' => __('We could not validate your vat number.', 'sage'),
                'message' => __('VIES service is not available at the moment or vat number is wrong. Please try again later.', 'sage'),
            ]);
        }
    }

    public static function validate_greek_afm($vat_id)
    {
        $iapr_vat_id = ht_get_field('invoice_vat_id', 'options');
        $iapr_username = ht_get_field('invoice_iapr_username', 'options');
        $iapr_password = ht_get_field('invoice_iapr_password', 'options');

        if (is_null($iapr_vat_id) || is_null($iapr_username) || is_null($iapr_password)) {
            wp_send_json_error([
                'title' => __('We could not validate your vat number.', 'sage'),
                'message' => __('AADE requires username and password. Did you saved the details in the options page?',
                    'sage'),
            ]);
        }

        if (! AadeService::isAlive()) {
            wp_send_json_error([
                'title' => __('We could not validate your vat number.', 'sage'),
                'message' => __('AADE service is not available at the moment. Please try again later.', 'sage'),
            ]);
        }

        $response = AadeService::validate_afm($vat_id, $iapr_username, $iapr_password);

        if ($response === false) {
            wp_send_json_error([
                'title' => __('We could not validate your vat number.', 'sage'),
                'message' => __('There was an error communicating with aadde. Please try again later', 'sage'),
            ]);
        }

        wp_send_json_success($response);
    }

    public static function validate_invoice_fields($fields, $errors)
    {
        // Disable vat validation if checkbox is not checked
        if (isset($fields[self::INVOICE_ENABLED_INPUT_NAME]) && $fields[self::INVOICE_ENABLED_INPUT_NAME] != 1) {
            unset($fields[self::VAT_INPUT_NAME]);
            unset($fields[self::TAX_OFFICE_INPUT_NAME]);
            unset($fields[self::COMPANY_NAME_INPUT_NAME]);
            unset($fields[self::COMPANY_ACTIVITY_INPUT_NAME]);

            unset($errors->errors[self::VAT_INPUT_NAME.'_required']);
            unset($errors->errors[self::TAX_OFFICE_INPUT_NAME.'_required']);
            unset($errors->errors[self::COMPANY_NAME_INPUT_NAME.'_required']);
            unset($errors->errors[self::COMPANY_ACTIVITY_INPUT_NAME.'_required']);
            unset($errors->error_data[self::VAT_INPUT_NAME.'_required']);
            unset($errors->error_data[self::TAX_OFFICE_INPUT_NAME.'_required']);
            unset($errors->error_data[self::COMPANY_NAME_INPUT_NAME.'_required']);
            unset($errors->error_data[self::COMPANY_ACTIVITY_INPUT_NAME.'_required']);
        }

        // if it is a european vat then remove the tax office and company activity fields. Most countries does not
        // require/have tax office or main activity fields
        if (isset($fields[self::INVOICE_ENABLED_INPUT_NAME]) && $fields[self::INVOICE_ENABLED_INPUT_NAME] == 1) {
            $vat_id = sanitize_text_field($fields[self::VAT_INPUT_NAME]);
            $pattern = '/^((AT)?U[0-9]{8}|(BE)?0[0-9]{9}|(BG)?[0-9]{9,10}|(CY)?[0-9]{8}L|(CZ)?[0-9]{8,10}|(DE)?[0-9]{9}|(DK)?[0-9]{8}|(EE)?[0-9]{9}|(EL|GR)?[0-9]{9}|(ES)?[0-9A-Z][0-9]{7}[0-9A-Z]|(FI)?[0-9]{8}|(FR)?[0-9A-Z]{2}[0-9]{9}|(GB)?([0-9]{9}([0-9]{3})?|[A-Z]{2}[0-9]{3})|(HU)?[0-9]{8}|(IE)?[0-9]S[0-9]{5}L|(IT)?[0-9]{11}|(LT)?([0-9]{9}|[0-9]{12})|(LU)?[0-9]{8}|(LV)?[0-9]{11}|(MT)?[0-9]{8}|(NL)?[0-9]{9}B[0-9]{2}|(PL)?[0-9]{10}|(PT)?[0-9]{9}|(RO)?[0-9]{2,10}|(SE)?[0-9]{12}|(SI)?[0-9]{8}|(SK)?[0-9]{10})$/';
            if (preg_match($pattern, $vat_id) && preg_match('/[a-zA-Z]{2}/', $vat_id)) {
                unset($fields[self::TAX_OFFICE_INPUT_NAME]);
                unset($fields[self::COMPANY_ACTIVITY_INPUT_NAME]);
                unset($errors->errors[self::TAX_OFFICE_INPUT_NAME.'_required']);
                unset($errors->error_data[self::TAX_OFFICE_INPUT_NAME.'_required']);
                unset($errors->errors[self::COMPANY_ACTIVITY_INPUT_NAME.'_required']);
                unset($errors->error_data[self::COMPANY_ACTIVITY_INPUT_NAME.'_required']);
            }
        }

        return $fields;
    }

    public static function save_invoice_fields($order_id, $data)
    {
        $order = wc_get_order($order_id);
        if ($order) {
            if (isset($data[self::INVOICE_ENABLED_INPUT_NAME]) && $data[self::INVOICE_ENABLED_INPUT_NAME] == 1) {

                $invoice_meta[self::VAT_METAKEY_NAME] = sanitize_text_field($data[self::VAT_INPUT_NAME]);
                $invoice_meta[self::TAX_OFFICE_METAKEY_NAME] = sanitize_text_field($data[self::TAX_OFFICE_INPUT_NAME]);
                $invoice_meta[self::COMPANY_NAME_METAKEY_NAME] = sanitize_text_field($data[self::COMPANY_NAME_INPUT_NAME]);
                $invoice_meta[self::COMPANY_ACTIVITY_METAKEY_NAME] = sanitize_text_field($data[self::COMPANY_ACTIVITY_INPUT_NAME]);

                foreach ($invoice_meta as $key_name => $key_value) {
                    $order->update_meta_data($key_name, $key_value);
                }
                $order->save();
            }
        }
    }

    public static function add_invoice_fields_to_formatted_address($address, $order)
    {

        $address['vat'] = $order->get_meta(self::VAT_METAKEY_NAME);
        $address['tax_office'] = $order->get_meta(self::TAX_OFFICE_METAKEY_NAME);
        $address['company_name'] = $order->get_meta(self::COMPANY_NAME_METAKEY_NAME);
        $address['company_activity'] = $order->get_meta(self::COMPANY_ACTIVITY_METAKEY_NAME);

        return $address;
    }

    public static function add_invoice_fields_to_formatted_address_replacements($mapped, $args)
    {
        $invoice = [];
        $invoice['{company_name}'] = (! empty($args['company_name'])) ? __('Company', 'sage').': '.$args['company_name'] : '';
        $invoice['{company_activity}'] = (! empty($args['company_activity'])) ? __('Activity', 'sage').': '.$args['company_activity'] : '';
        $invoice['{vat}'] = (! empty($args['vat'])) ? __('Vat Number', 'sage').': '.$args['vat'] : '';
        $invoice['{tax_office}'] = (! empty($args['tax_office'])) ? __('Tax Office', 'sage').': '.$args['tax_office'] : '';

        return $invoice + $mapped;
    }

    public static function add_invoice_add_localization_address_format($format)
    {
        $format['default'] = "{company_name}\n{company_activity}\n{vat}\n{tax_office}\n{address_1}\n{address_2}\n{city}\n{state}\n{postcode}\n{country}";

        return $format;
    }

    public static function woocommerce_admin_order_data_after_order_details($order)
    {
        if (! empty($order->get_meta(self::VAT_METAKEY_NAME))) {
            $html = '<script>
                        jQuery(document).ready(function(){
                            var heading = jQuery("h2.woocommerce-order-data__heading");
                            var label = "<span style=\'font-size:18px;background-color:#cf90ee;color:white;padding:5px 8px;display:inline-block;text-shadow:none;\'>'.__('INVOICE', 'sage').'</span>";
                            heading.append(label)
                        });
                    </script>';
            echo $html;
        }
    }

    public static function add_invoice_meta($order)
    {
        if (! empty($order->get_meta(self::VAT_METAKEY_NAME))) {
            $html = '<div class="ht-invoice-plugin-meta" style="height: 28px; width: auto">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" style="height: 28px;width:auto" stroke="#000" stroke-width="3" viewBox="0 0 64 64">
                  <path stroke-linecap="round" d="M52.35 57.08h-40.7v-50a.11.11 0 0 1 .16-.08l4.11 3.85a.11.11 0 0 0 .13 0L19.35 7a.09.09 0 0 1 .12 0l3.72 3.89a.11.11 0 0 0 .13 0L26.61 7a.09.09 0 0 1 .13 0l2.86 3.87a.1.1 0 0 0 .14 0L33 7a.09.09 0 0 1 .13 0l2.69 3.85a.1.1 0 0 0 .14 0L38.86 7A.1.1 0 0 1 39 7l2.85 3.85a.1.1 0 0 0 .14 0L44.7 7a.09.09 0 0 1 .15 0l2.25 3.84a.09.09 0 0 0 .13 0L52.2 7a.1.1 0 0 1 .15.09ZM19.42 43.04h26.6M19.42 49.29h26.6"/>
                  <path d="M40.21 34.51a9 9 0 1 1-5.48-16.15 8.86 8.86 0 0 1 3.78.83M21.22 25.18h14.99M21.22 29.76H34.4"/>
                </svg>
               </div>';

            echo $html;
        }
    }
}
