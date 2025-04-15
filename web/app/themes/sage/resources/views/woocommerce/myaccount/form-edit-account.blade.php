@php
    /**
     * Edit account form
     *
     * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
     *
     * HOWEVER, on occasion WooCommerce will need to update template files and you
     * (the theme developer) will need to copy the new files to your theme to
     * maintain compatibility. We try to do this as little as possible, but it does
     * happen. When this occurs the version of the template file will be bumped and
     * the readme will list any important changes.
     *
     * @see https://docs.woocommerce.com/document/template-structure/
     *
     * @version 3.5.0
     */
    defined('ABSPATH') || exit;

    do_action('woocommerce_before_edit_account_form');
@endphp
@if(!is_user_logged_in())
<style>
    .htech-breadcrumb a{
        color:#fff!important;
    }
</style>
@endif

<form
    class="woocommerce-EditAccountForm edit-account"
    action=""
    method="post"
    BfvgZBJhtlGpxKv1jlKyCFyzvOb2h7n9HKfXL6TkQm65hIhTMNDGnr6wOl5maNfDB
>
    <div class="flex w-full flex-col">
        <div class="dashboard-heading grid w-full grid-cols-5 grid-rows-1 pt-2">
            <h3 class="dashboard-title col-span-4 flex items-center text-xs text-body font-bold uppercase">
                {{ __('Account Details', 'woocommerce') }}
            </h3>
        </div>

        <div class="grid grid-cols-1 gap-x-5 lg:grid-cols-2">
            @php
                do_action('woocommerce_edit_account_form_start');
            @endphp

            <p class="ht-custom woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
                <label for="account_first_name">
                    {{ __('First name', 'woocommerce') }}&nbsp;
                    <span class="required">*</span>
                </label>
                <input
                    type="text"
                    class="woocommerce-Input woocommerce-Input--text input-text"
                    name="account_first_name"
                    id="account_first_name"
                    autocomplete="given-name"
                    value="@php
                    echo esc_attr($user->first_name);@endphp




"
                />
            </p>

            <p class="ht-custom woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
                <label for="account_last_name">
                    {{ __('Last name', 'woocommerce') }}&nbsp;
                    <span class="required">*</span>
                </label>
                <input
                    type="text"
                    class="woocommerce-Input woocommerce-Input--text input-text"
                    name="account_last_name"
                    id="account_last_name"
                    autocomplete="family-name"
                    value="@php
                    echo esc_attr($user->last_name);@endphp




"
                />
            </p>

            <p class="ht-custom woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide mb-12 md:mb-6">
                <label for="account_display_name">
                    {{ __('Display name', 'woocommerce') }}&nbsp;
                    <span class="required">*</span>
                </label>
                <input
                    type="text"
                    class="woocommerce-Input woocommerce-Input--text input-text"
                    name="account_display_name"
                    id="account_display_name"
                    value="@php
                    echo esc_attr($user->display_name);@endphp




"
                />
                <span class="block pt-4 md:pt-2">
                    <em class="text-sm text-slate-500 md:text-xs">
                        @php
                            esc_html_e('This will be how your name will be displayed in the account section and in reviews', 'woocommerce');
                        @endphp
                    </em>
                </span>
            </p>
            <div class="clear hidden"></div>

            <p class="ht-custom woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="account_email">
                    {{ __('Email address', 'woocommerce') }}&nbsp;
                    <span class="required">*</span>
                </label>
                <input
                    type="email"
                    class="woocommerce-Input woocommerce-Input--email input-text"
                    name="account_email"
                    id="account_email"
                    autocomplete="email"
                    value="@php
                    echo esc_attr($user->user_email);@endphp




"
                />
            </p>
        </div>
    </div>

    <div class="mt-4 flex w-full flex-col">
        <div class="">
            <div class="dashboard-heading grid w-full grid-cols-5 grid-rows-1 pt-2">
                <h3 class="dashboard-title col-span-4 flex items-center text-sm font-normal uppercase md:text-xs">
                    {{ __('Password change', 'woocommerce') }}
                </h3>
            </div>

            <p class="ht-custom woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="password_current">
                    {{ __('Current password (leave blank to leave unchanged)', 'woocommerce') }}
                </label>
                <input
                    type="password"
                    class="woocommerce-Input woocommerce-Input--password input-text w-full"
                    name="password_current"
                    id="password_current"
                    autocomplete="off"
                />
            </p>
            <p class="ht-custom woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                <label for="password_1">
                    {{ __('New password (leave blank to leave unchanged)', 'woocommerce') }}
                </label>
                <input
                    type="password"
                    class="woocommerce-Input woocommerce-Input--password input-text w-full"
                    name="password_1"
                    id="password_1"
                    autocomplete="off"
                />
            </p>
            <p class="ht-custom woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide xl:col-span-3">
                <label for="password_2">{{ __('Confirm new password', 'woocommerce') }}</label>
                <input
                    type="password"
                    class="woocommerce-Input woocommerce-Input--password input-text w-full"
                    name="password_2"
                    id="password_2"
                    autocomplete="off"
                />
            </p>
            <p class="flex items-end">
                @php
                    wp_nonce_field('save_account_details', 'save-account-details-nonce');
                @endphp

                <button
                    type="submit"
                    class="btn-md btn-solid-primary woocommerce-Button button w-full uppercase"
                    name="save_account_details"
                    value="@php
                    esc_attr_e('Save changes', 'woocommerce');@endphp




"
                >
                    {{ __('Save changes', 'woocommerce') }}
                </button>
                <input type="hidden" name="action" value="save_account_details" />
            </p>

            <div class="clear"></div>

            @php
                do_action('woocommerce_edit_account_form');
            @endphp

            @php
                do_action('woocommerce_edit_account_form_end');
            @endphp
        </div>
    </div>
</form>

@php
    do_action('woocommerce_after_edit_account_form');
@endphp
