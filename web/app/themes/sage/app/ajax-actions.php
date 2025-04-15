<?php

namespace App;

use App\HT\Services\Cart\CartService;
use App\HT\Services\Cart\CouponService;
use App\HT\Services\CheckoutService;
use App\HT\Services\Filter\Post\FilterService as PostFilterService;
use App\HT\Services\InvoiceService;
use App\HT\Services\Product\MediaService;
use App\HT\Services\Product\ProductService;
use App\HT\Services\Wishlist;

// Alpine Filters
/* get facetes for posts ( and cpt ) */
add_action('wp_ajax_get_facetes_for_posts', [PostFilterService::class, 'getFacetesForPosts']);
add_action('wp_ajax_nopriv_get_facetes_for_posts', [PostFilterService::class, 'getFacetesForPosts']);
/* get posts ( and cpt ) */
add_action('wp_ajax_get_posts_alpine', [PostFilterService::class, 'getPostsForAlpine']);
add_action('wp_ajax_nopriv_get_posts_alpine', [PostFilterService::class, 'getPostsForAlpine']);

/* get cart items and totals */
add_action('wp_ajax_nopriv_get_cart_and_totals', [CartService::class, 'getCartAndTotals']);
add_action('wp_ajax_get_cart_and_totals', [CartService::class, 'getCartAndTotals']);

/* get free shipping */
add_action('wp_ajax_nopriv_get_free_shipping_amount', [CartService::class, 'getFreeShippingAmount']);
add_action('wp_ajax_get_free_shipping_amount', [CartService::class, 'getFreeShippingAmount']);

/* update cart item quantity */
add_action('wp_ajax_nopriv_update_cart_item_quantity', [CartService::class, 'updateCartItemQuantity']);
add_action('wp_ajax_update_cart_item_quantity', [CartService::class, 'updateCartItemQuantity']);

/* remove cart item */
add_action('wp_ajax_nopriv_remove_item_from_cart', [CartService::class, 'removeItemFromCart']);
add_action('wp_ajax_remove_item_from_cart', [CartService::class, 'removeItemFromCart']);

/* clear cart */
add_action('wp_ajax_nopriv_clear_cart', [CartService::class, 'clearCart']);
add_action('wp_ajax_clear_cart', [CartService::class, 'clearCart']);

/* apply coupon */
add_action('wp_ajax_nopriv_apply_coupon', [CouponService::class, 'applyCoupon']);
add_action('wp_ajax_apply_coupon', [CouponService::class, 'applyCoupon']);

/* remove coupon */
add_action('wp_ajax_nopriv_remove_coupon', [CouponService::class, 'removeCoupon']);
add_action('wp_ajax_remove_coupon', [CouponService::class, 'removeCoupon']);

/* update session shipping postcode */
add_action('wp_ajax_nopriv_update_session_billing_and_shipping_postcode', [CartService::class, 'updateSessionBillingAndShippingPostcode']);
add_action('wp_ajax_update_session_billing_and_shipping_postcode', [CartService::class, 'updateSessionBillingAndShippingPostcode']);

/* validate cart before checkout */
add_action('wp_ajax_nopriv_validate_cart_before_checko', [CartService::class, 'validateCartBeforeCheckout']);
add_action('wp_ajax_validate_cart_before_checko', [CartService::class, 'validateCartBeforeCheckout']);

// Checkout
/* get_shipping_methods_for_checkout */
add_action('wp_ajax_nopriv_get_shipping_methods_for_checkout', [CheckoutService::class, 'getShippingMethodsForCheckout']);
add_action('wp_ajax_get_shipping_methods_for_checkout', [CheckoutService::class, 'getShippingMethodsForCheckout']);

/* get company info from vat */
add_action('wp_ajax_nopriv_get_company_info_from_vat', [InvoiceService::class, 'validateVatNumber']);
add_action('wp_ajax_get_company_info_from_vat', [InvoiceService::class, 'validateVatNumber']);

/* Edit wishlist */
add_action('wp_ajax_nopriv_edit_wishlist', [app(Wishlist::class), 'edit']);
add_action('wp_ajax_edit_wishlist', [app(Wishlist::class), 'edit']);

/* get wishlist list items from ids */
add_action('wp_ajax_nopriv_get_list_items_from_ids', [app(Wishlist::class), 'getListItemsFromIdsJSON']);
add_action('wp_ajax_get_list_items_from_ids', [app(Wishlist::class), 'getListItemsFromIdsJSON']);

/* get product gallery */
add_action('wp_ajax_nopriv_get_product_gallery', [MediaService::class,'getProductGalleryJson']);
add_action('wp_ajax_get_product_gallery', [MediaService::class,'getProductGalleryJson']);

/* search variation and get variation gallery */
add_action('wp_ajax_nopriv_search_variation_and_get_gallery', [MediaService::class,'searchVariationAndGetGallery']);
add_action('wp_ajax_search_variation_and_get_gallery', [MediaService::class,'searchVariationAndGetGallery']);

/* get variation gallery */
// add_action('wp_ajax_nopriv_get_variation_gallery', [MediaService::class,'getVariationGallery']);
// add_action('wp_ajax_get_variation_gallery', [MediaService::class,'getVariationGallery']);

/* get variation sku */
add_action('wp_ajax_nopriv_get_variation_sku', [ProductService::class,'getVariationSku']);
add_action('wp_ajax_get_variation_sku', [ProductService::class,'getVariationSku']);

/* create wc cart from local storage data */
add_action('wp_ajax_nopriv_create_wc_cart_from_local_storage', [CartService::class, 'createWCcartFromLocalStorage']);
add_action('wp_ajax_create_wc_cart_from_local_storage', [CartService::class, 'createWCcartFromLocalStorage']);
