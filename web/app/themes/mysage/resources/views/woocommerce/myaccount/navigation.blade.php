<?php
/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 *
 * @version 2.6.0
 */
if (! defined('ABSPATH')) {
    exit;
}

do_action('woocommerce_before_account_navigation');
?>

<nav
    class="woocommerce-MyAccount-navigation bg-primary w-full p-6 text-left text-base lg:w-[335px] xl:col-span-1"
>
    <ul class="ht-list-none flex flex-col">
        <?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>

        <li
            class="<?php echo wc_get_account_menu_item_classes($endpoint); ?> group flex flex-row border-b border-black"
        >
            @php
                switch ($endpoint) {
                    case 'dashboard':
                        $icon = 'myAccount-svg-admin-sign';
                        break;
                    case 'orders':
                        $icon = 'myAccount-svg-cart-sign';
                        break;
                    case 'wishlist':
                        $icon = 'myAccount-svg-wishlist-sign';
                        break;
                    case 'edit-address':
                        $icon = 'myAccount-svg-envelope-sign';
                        break;
                    case 'edit-account':
                        $icon = 'myAccount-svg-acount-sign';
                        break;
                    case 'customer-logout':
                        $icon = 'myAccount-svg-logout-sign';
                        break;
                    case 'agapimenes-syntages':
                        $icon = 'myAccount-svg-agapimenes-syntages';
                        break;
                    default:
                        $icon = 'myAccount-svg-admin-sign';
                        break;
                }
            @endphp

            <a
                class="flex w-full items-center justify-between py-2.5 text-xs text-white font-bold uppercase"
                href="<?php echo esc_url(wc_get_account_endpoint_url($endpoint)); ?>"
            >
                {{ $label }}

                {{--
                    <span class="flex items-center aspect-square overflow-hidden">
                    @include("svg.icons.$icon", [ 'classes' => 'w-6 h-auto' ])
                    </span>
                --}}
            </a>
        </li>

        <?php endforeach; ?>
    </ul>
</nav>

<?php do_action('woocommerce_after_account_navigation'); ?>
