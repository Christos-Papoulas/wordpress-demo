{{--
    /**
    * Empty cart page
    *
    * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-empty.php.
    *
    * HOWEVER, on occasion WooCommerce will need to update template files and you
    * (the theme developer) will need to copy the new files to your theme to
    * maintain compatibility. We try to do this as little as possible, but it does
    * happen. When this occurs the version of the template file will be bumped and
    * the readme will list any important changes.
    *
    * @see     https://docs.woocommerce.com/document/template-structure/
    * @package WooCommerce\Templates
    * @version 3.5.0
    */
--}}
@php
    defined('ABSPATH') || exit;
@endphp

{{-- include cart blade even if wc cart is empty. We use local storage cart --}}

@include('woocommerce.cart.cart')

{{--
    <div class="ht-container-large flex flex-col text-center items-center justify-center" style="min-height: 50dvh;">
    @php
    /*
    * @hooked wc_empty_cart_message - 10
    */
    do_action( 'woocommerce_cart_is_empty' );
    @endphp
    <div class="flex mt-5">
    <a href="{{ wc_get_page_permalink('shop') }}"
    class="btn-md btn-solid-secondary !w-auto">
    <svg class="absolute left-6 " xmlns="http://www.w3.org/2000/svg" width="13.538" height="10.121"
    viewBox="0 0 13.538 10.121">
    <g id="arrow-right" transform="translate(12.788 9.061) rotate(180)">
    <g id="Group_2503" data-name="Group 2503" transform="translate(0 -1.702)">
    <path id="Path_4820" data-name="Path 4820" d="M6.962.7l3.911,3.97L6.962,8.7"
    transform="translate(0.866 1)" fill="none" stroke="#fff" stroke-linecap="round"
    stroke-width="1.5"/>
    <line id="Line_158" data-name="Line 158" x1="11.74" transform="translate(0 5.702)"
    fill="none" stroke="#fff" stroke-linecap="round" stroke-width="1.5"/>
    </g>
    </g>
    </svg>
    {{ __('BACK TO STORE','sage') }}
    </a>
    </div>
    <div>
--}}
