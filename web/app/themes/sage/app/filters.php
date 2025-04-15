<?php

/*
|--------------------------------------------------------------------------
| Theme filters
|--------------------------------------------------------------------------
|
| Add here actions and filters for the specific project.
| Theme Features should not be here. Use setup.php
|
*/

namespace App;

use App\HT\Interfaces\ConsentInterface;
// use App\HT\Services\Cart\CartService;
// use App\HT\Services\CheckoutService;
// use App\HT\Services\PaymentService;
// use App\HT\Services\ShippingService;

/*
|--------------------------------------------------------------------------
| Woocommerce breadcrumbs
|--------------------------------------------------------------------------
|
*/
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
add_action('htech_breadcrumbs', 'woocommerce_breadcrumb', 20);

/*
|--------------------------------------------------------------------------
| Single Product Page
|--------------------------------------------------------------------------
|
*/
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
add_action('ht_woocommerce_single_product_rating', 'woocommerce_template_single_rating', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
// remove_action('woocommerce_single_product_summary','woocommerce_template_single_add_to_cart',30);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);

remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);

/*
|--------------------------------------------------------------------------
| Content Product
|--------------------------------------------------------------------------
|
*/
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10);
remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5);
remove_action('woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5);
remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);

/*
|--------------------------------------------------------------------------
| Archive
|--------------------------------------------------------------------------
|
*/

/*
|--------------------------------------------------------------------------
|  Cart Page
|--------------------------------------------------------------------------
|
*/
remove_action('woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');
remove_action('woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10);

/*
|--------------------------------------------------------------------------
|  Checkout Page
|--------------------------------------------------------------------------
|
*/
remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);
// add_filter( 'woocommerce_cart_shipping_method_full_label', [CartService::class, 'woocommerceCartShippingMethodFullLabel'],10, 2);
add_filter('woocommerce_get_terms_and_conditions_checkbox_text', function ($text) {
    $cookies = '<a href="'.esc_url(get_permalink(1312)).'" class="" target="_blank">'.__('Cookie policy', 'sage').'</a>';
    $text = $text.', '.$cookies.' '.__('and', 'sage').' [privacy_policy]*';

    return $text;
}, 10, 1);

/*
|--------------------------------------------------------------------------
|  My Account Page
|--------------------------------------------------------------------------
|
|  Remove default privacy policy text from register form
|  Add privacy policy checkbox to register form, with the same privacy policy text
|  Make the checkbox required
|
*/
remove_action('woocommerce_register_form', 'wc_registration_privacy_policy_text', 20);
add_action('woocommerce_register_form', function () {
    woocommerce_form_field('privacy_policy_and_terms_checkbox', [
        'type' => 'checkbox',
        'class' => ['form-row privacy'],
        'label_class' => ['woocommerce-form__label woocommerce-form__label-for-checkbox checkbox'],
        'input_class' => ['woocommerce-form__input woocommerce-form__input-checkbox input-checkbox'],
        'required' => true,
        'label' => sprintf(
            '<span style="margin-left: 6px; position: relative; top: -3px;">%s <a href="%s" target="_blank">%s</a> %s <a href="%s" target="_blank">%s</a></span>',
            esc_html__('I have read and agree to the website', 'sage'),
            esc_url(get_permalink(wc_terms_and_conditions_page_id())),
            esc_html__('Terms and Conditions', 'sage'),
            esc_html__('and', 'sage'),
            esc_url(get_permalink(wc_privacy_policy_page_id())),
            esc_html__('Privacy Policy', 'sage')
        ),
    ]);
}, 20);

/*
|--------------------------------------------------------------------------
|  My Account Page
|--------------------------------------------------------------------------
|
|  Add validation to register form form privacy policy and terms
|
*/
add_action('woocommerce_register_post', function ($username, $email, $validation_errors) {
    if (! isset($_POST['privacy_policy_and_terms_checkbox']) || empty($_POST['privacy_policy_and_terms_checkbox'])) {
        $validation_errors->add('privacy_policy_error', __('You must agree to the privacy policy and the terms.', 'sage'));
    }

    return $validation_errors;
}, 10, 3);

/*
|--------------------------------------------------------------------------
|  My Account Page
|--------------------------------------------------------------------------
|
| Edit my account navigation links
|
*/
add_filter('woocommerce_account_menu_items', function ($items) {
    unset($items['downloads']);
    $items['wishlist'] = __('Favorites', 'sage');

    return $items;
});

/*
|--------------------------------------------------------------------------
| My Account
|--------------------------------------------------------------------------
|
| Redirect navigation links to pages
|
*/
add_filter('woocommerce_get_endpoint_url', function ($url, $endpoint, $value, $permalink) {
    $wishlist = ht_get_field('wishlist_page', 'option');
    if(empty($wishlist)){
        return $url;
    }
    if ($endpoint === $wishlist->post_name) {
        return esc_url( get_permalink( $wishlist->ID ) );
    }
    return $url;
}, 10, 4);

/*
|--------------------------------------------------------------------------
|  My Account Page
|--------------------------------------------------------------------------
|
| Default Addresses
|
*/
add_filter('woocommerce_default_address_fields', function ($fields) {
    $fields['email']['priority'] = 10;
    $fields['country']['priority'] = 15;
    $fields['first_name']['priority'] = 20;
    $fields['last_name']['priority'] = 25;
    $fields['address_1']['priority'] = 30;
    $fields['address_2']['priority'] = 35;
    $fields['postcode']['priority'] = 40;
    $fields['city']['priority'] = 45;
    $fields['phone']['priority'] = 50;

    $fields['state']['priority'] = 55;
    unset($fields['company']);

    return $fields;
});

/*
|--------------------------------------------------------------------------
|  Billing Fields
|--------------------------------------------------------------------------
|
*/
add_filter('woocommerce_billing_fields', function ($fields) {

    $fields['billing_email']['class'][] = 'md:col-span-4 2xl:col-span-2';
    $fields['billing_email']['priority'] = 10;

    $fields['billing_phone']['class'][] = 'md:col-span-4 2xl:col-span-2';
    $fields['billing_phone']['priority'] = 15;

    $fields['billing_first_name']['class'][] = 'md:col-span-2';
    $fields['billing_first_name']['priority'] = 20;

    $fields['billing_last_name']['class'][] = 'md:col-span-2';
    $fields['billing_last_name']['priority'] = 25;

    $fields['billing_address_1']['class'][] = 'md:col-span-4 2xl:col-span-2';
    $fields['billing_address_1']['priority'] = 30;

    $fields['billing_address_2']['class'][] = 'md:col-span-4 2xl:col-span-2';
    $fields['billing_address_2']['priority'] = 35;
    $fields['billing_address_2']['label_class'] = []; // to remove screen reader class

    $fields['billing_postcode']['class'][] = 'md:col-span-2';
    $fields['billing_postcode']['priority'] = 40;
    $fields['billing_postcode']['maxlength'] = 5;

    $fields['billing_city']['class'][] = 'md:col-span-2';
    $fields['billing_city']['priority'] = 45;

    $fields['billing_country']['class'][] = 'md:col-span-4 2xl:col-span-2';
    $fields['billing_country']['priority'] = 50;

    $fields['billing_state']['class'][] = 'md:col-span-4 2xl:col-span-2';
    $fields['billing_state']['priority'] = 55;

    unset($fields['billing_company']);

    return $fields;
});

/*
|--------------------------------------------------------------------------
|  Shipping Fields
|--------------------------------------------------------------------------
|
*/
add_filter('woocommerce_shipping_fields', function ($fields) {
    $fields['shipping_email']['class'][] = 'md:col-span-4 2xl:col-span-2';
    $fields['shipping_email']['label'] = __('Email address', 'woocommerce');
    $fields['shipping_email']['priority'] = 10;

    $fields['shipping_country']['class'][] = 'md:col-span-4 2xl:col-span-2';
    $fields['shipping_country']['priority'] = 15;

    $fields['shipping_first_name']['class'][] = 'md:col-span-2';
    $fields['shipping_first_name']['priority'] = 20;

    $fields['shipping_last_name']['class'][] = 'md:col-span-2';
    $fields['shipping_last_name']['priority'] = 25;

    $fields['shipping_address_1']['class'][] = 'md:col-span-4 2xl:col-span-2';
    $fields['shipping_address_1']['priority'] = 30;

    $fields['shipping_address_2']['class'][] = 'md:col-span-4 2xl:col-span-2';
    $fields['shipping_address_2']['priority'] = 35;
    $fields['shipping_address_2']['label_class'] = []; // to remove screen reader class

    $fields['shipping_postcode']['class'][] = 'md:col-span-2';
    $fields['shipping_postcode']['priority'] = 40;
    $fields['shipping_postcode']['maxlength'] = 5;

    $fields['shipping_city']['class'][] = 'md:col-span-2';
    $fields['shipping_city']['priority'] = 45;

    $fields['shipping_phone']['class'][] = 'md:col-span-4 2xl:col-span-2';
    $fields['shipping_phone']['label'] = __('Phone', 'woocommerce');
    $fields['shipping_phone']['priority'] = 50;

    $fields['shipping_state']['class'][] = 'md:col-span-4 2xl:col-span-2';
    $fields['shipping_state']['priority'] = 55;

    return $fields;
});

/*
|--------------------------------------------------------------------------
|  Checkout Fields
|--------------------------------------------------------------------------
|
*/
add_filter('woocommerce_checkout_fields', function ($fields) {

    // Placeholders
    $fields['billing']['billing_email']['placeholder'] = __('name@email.com', 'sage');
    $fields['billing']['billing_first_name']['placeholder'] = __('First name', 'sage');
    $fields['billing']['billing_last_name']['placeholder'] = __('Last name', 'sage');
    $fields['billing']['billing_address_1']['placeholder'] = __('Address', 'sage');
    $fields['billing']['billing_address_2']['placeholder'] = __('Apartment, suite, etc.', 'sage');
    $fields['billing']['billing_postcode']['placeholder'] = __('Postcode', 'sage');
    $fields['billing']['billing_city']['placeholder'] = __('City', 'sage');
    $fields['billing']['billing_phone']['placeholder'] = __('Phone', 'sage');

    $fields['shipping']['shipping_email']['placeholder'] = __('name@email.com', 'sage');
    $fields['shipping']['shipping_first_name']['placeholder'] = __('First name', 'sage');
    $fields['shipping']['shipping_last_name']['placeholder'] = __('Last name', 'sage');
    $fields['shipping']['shipping_address_1']['placeholder'] = __('Address', 'sage');
    $fields['shipping']['shipping_address_2']['placeholder'] = __('Apartment, suite, etc.', 'sage');
    $fields['shipping']['shipping_postcode']['placeholder'] = __('Postcode', 'sage');
    $fields['shipping']['shipping_city']['placeholder'] = __('City', 'sage');
    $fields['shipping']['shipping_phone']['placeholder'] = __('Phone', 'sage');

    return $fields;
});

/*
|--------------------------------------------------------------------------
|  Save custom register fields
|--------------------------------------------------------------------------
|
*/
// add_action('woocommerce_created_customer', function ($customer_id){
//     if (isset($_POST['first_name']))
//         update_user_meta($customer_id, 'first_name', sanitize_text_field($_POST['first_name']));
//         update_user_meta($customer_id, 'billing_first_name', sanitize_text_field($_POST['first_name']));
//     if (isset($_POST['last_name']))
//         update_user_meta($customer_id, 'last_name', sanitize_text_field($_POST['last_name']));
//         update_user_meta($customer_id, 'billing_last_name', sanitize_text_field($_POST['last_name']));
//     if (isset($_POST['address']))
//         update_user_meta($customer_id, 'billing_address_1', sanitize_text_field($_POST['address']));
//     if (isset($_POST['phone_number']))
//         update_user_meta($customer_id, 'billing_phone', sanitize_text_field($_POST['phone_number']));
// });
// // Validation for register form
// add_action( 'woocommerce_process_registration_errors', function( $validation_errors, $username, $password, $email ) {
//     if ( isset( $_POST['first_name'] ) && empty($_POST['first_name']) )
//         $validation_errors->add( 'first_name', __( 'Type your name.', 'sage' ) );
//     if ( isset( $_POST['last_name'] ) && empty($_POST['last_name']) )
//         $validation_errors->add( 'last_name', __( 'Typ your last name.', 'sage' ) );
//     if ( isset( $_POST['phone_number'] ) && empty($_POST['phone_number']) && !preg_match('/^(\(?\+?[0-9]*\)?)?[0-9_\- \(\)]*$/', $_POST['phone_number']) )
//         $validation_errors->add( 'phone_number', __( 'Type your phone number.', 'sage' ) );
//     return $validation_errors;
// }, 10, 4 );

/**
 * Disable cart fragments
 *
 * @see https://developer.woocommerce.com/2023/06/16/best-practices-for-the-use-of-the-cart-fragments-api/
 */
add_filter('woocommerce_get_script_data', function ($script_data, $handle) {
    if (is_woocommerce() || is_cart() || is_checkout()) {
        return $script_data;
    }

    return null;
}, 10, 2);

// On user registration maybe create consent
add_action('user_register', function ($user_id) {
    if ((WP_ENV == 'production' || WP_ENV == 'staging') && config('theme.consentApiEnabled', false)) {
        $user_object = get_userdata($user_id);
        $consentService = app(ConsentInterface::class);
        $consentService->create($user_object);
    }
});

/*
|--------------------------------------------------------------------------
| Set product catalog visibility to hidden when they go out of stock
|--------------------------------------------------------------------------
|
*/
// add_action('woocommerce_product_set_stock_status', [StockService::class, 'updateProductCatalogVisibility'], 10, 2);

/*
|--------------------------------------------------------------------------
| 404 Page for products which are out of stock or hidden from catalog.
|--------------------------------------------------------------------------
|
*/
// add_action('template_redirect', function() {
//     if (function_exists('is_product') && is_product()) {
//         global $product;

//         $product_object = wc_get_product(get_page_by_path( $product, OBJECT, 'product' ));
//          if ($product_object && ( !$product_object->is_in_stock() || 'hidden' == $product_object->get_catalog_visibility() ) ) {
//             wp_redirect(home_url('/404'));
//             exit;
//         }
//     }
// });

/*
|--------------------------------------------------------------------------
| Shipping methods
|--------------------------------------------------------------------------
|
*/
// add_filter('transient_shipping-transient-version', function($value, $name) { return false; }, 10, 2);
// add_filter('woocommerce_package_rates', [ShippingService::class, 'maybeUnsetShippingMethods'], 10, 2);

/*
|--------------------------------------------------------------------------
| Payment methods
|--------------------------------------------------------------------------
|
*/
// add_filter('woocommerce_available_payment_gateways', [PaymentService::class, 'maybeUnsetPaymentMethods'],10,1);
// add_filter( 'woocommerce_payment_gateways', [PaymentService::class, 'addCustomPaymentGateways'] );
// require( __DIR__. '/HT/PaymentGatewayClasses/class-wc-payment-gateway-cash-on-local-pickup.php' );
// require( __DIR__. '/HT/PaymentGatewayClasses/class-wc-payment-gateway-card-on-delivery.php' );
// add_action('woocommerce_checkout_update_order_meta', [PaymentService::class,'save_pos_field'], 20, 2);
// add_filter('woocommerce_order_get_payment_method_title', [PaymentService::class,'maybeShowPosName'], 99, 2);

/*
|--------------------------------------------------------------------------
| Checkout custom validation
|--------------------------------------------------------------------------
|
*/
// add_action('woocommerce_checkout_process', [CheckoutService::class, 'checkForFrozenProducts']);

/*
|--------------------------------------------------------------------------
| Fix for shipping labels not translating by wmpl correctly
|--------------------------------------------------------------------------
|
*/
// add_filter( 'woocommerce_cart_shipping_method_full_label', function( $label, $method ){
//     return apply_filters( 'wpml_translate_single_string', $label, 'admin_texts_woocommerce_shipping', str_replace(':', '', $method->get_id()) . '_shipping_method_title', apply_filters( 'wpml_current_language', null ) );
// },90,2);

/*
|--------------------------------------------------------------------------
| Sanitize text fields in Ninja Forms
|--------------------------------------------------------------------------
|
*/
add_filter('ninja_forms_submit_data', function($form_data) {
    if (isset($form_data['fields'])) {
        foreach ($form_data['fields'] as &$field) {
            if (isset($field['value'])) {
                $field['value'] = sanitize_text_field( wp_unslash( $field['value'] ) );
            }
        }
    }
    return $form_data;
});
