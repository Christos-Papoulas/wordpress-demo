<?php
/**
 * Lost password form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-lost-password.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.0.1
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="ht-container-no-max-width bg-primary py-16">
    <div class="max-w-[900px] mx-auto text-xs font-bold xl:text-3xl xl:font-normal mb-8 xl:mb-12 uppercase text-white">{!! __('Lost your password?', 'sage') !!}</div>

    <div class="">
        <div class="text-white">
            <?php do_action( 'woocommerce_before_lost_password_form' ); ?>
        </div>
        <form method="post" class="woocommerce-ResetPassword lost_reset_password p-6  bg-white max-w-[900px] m-10 mx-auto rounded-lg ">

            <p class="text-xs text-body font-bold uppercase"><?php echo apply_filters( 'woocommerce_lost_password_message', esc_html__( 'Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.', 'woocommerce' ) ); ?></p><?php // @codingStandardsIgnoreLine ?>

            <p class="ht-custom woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
                <label for="user_login"><?php esc_html_e( 'Username or email', 'woocommerce' ); ?></label>
                <input class="woocommerce-Input woocommerce-Input--text input-text" type="text" name="user_login" id="user_login" autocomplete="username" placeholder="user@mail.com or user" />
            </p>

            <div class="clear"></div>

            <?php do_action( 'woocommerce_lostpassword_form' ); ?>

            <p class="woocommerce-form-row form-row">
                <input type="hidden" name="wc_reset_password" value="true" />
                <button type="submit" class="w-full btn-md btn-solid-primary woocommerce-Button button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" value="<?php esc_attr_e( 'Reset password', 'woocommerce' ); ?>"><?php esc_html_e( 'Reset password', 'woocommerce' ); ?> 
                <svg width="20" height="18" viewBox="0 0 20 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M17.6804 0.5H2.32039C1.26151 0.5 0.400391 1.397 0.400391 2.5V15.5C0.400391 16.603 1.26151 17.5 2.32039 17.5H17.6804C18.7393 17.5 19.6004 16.603 19.6004 15.5V2.5C19.6004 1.397 18.7393 0.5 17.6804 0.5ZM17.6804 2.5V4.011L10.0004 9.234L2.32039 4.012V2.5H17.6804ZM2.32039 15.5V6.544L9.41095 11.289C9.57906 11.4265 9.78663 11.5013 10.0004 11.5013C10.2142 11.5013 10.4217 11.4265 10.5898 11.289L17.6804 6.544L17.6823 15.5H2.32039Z" fill="white"/>
                </svg>

                </button>
            </p>

            <?php wp_nonce_field( 'lost_password', 'woocommerce-lost-password-nonce' ); ?>
            <a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>" class="text-xs uppercase flex gap-2"> 
                
                <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M15.2383 8L1.79828 8M1.79828 8L8.51828 15M1.79828 8L8.51828 1" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                {!! __('Return to login page', 'sage') !!}
            </a>
        </form>
        
        <?php
        do_action( 'woocommerce_after_lost_password_form' );
        ?>
    </div>
</div>
