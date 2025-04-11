{{--
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.8.0
 */
--}}
@php
defined( 'ABSPATH' ) || exit;
@endphp
<div 
x-data="cart( { shippingPostCode: '{{ $shipping_postcode = WC()->customer->get_shipping_postcode() }}' } )" 
id="cart-single-page" 
class="">
   

	<div class="ht-container page-header">
		<h1 class="flex text-xs font-bold lg:text-3xl lg:font-normal pt-4 lg:pt-7 mb-8 lg:mb-12 uppercase ">{!! '<span class="mr-1 lg:mr-2">' . __('SHOPPING BAG','sage') . '</span><span x-text="`• ${count} ΠΡΟΪΟΝΤΑ`" x-cloak x-show="!loading"></span>' !!}</h1>
	</div>

	<template x-if="errors.length > 0">
		<div class="ht-container woocommerce-NoticeGroup woocommerce-NoticeGroup-checkout">
			<div role="alert">
				<ul class="woocommerce-error" tabindex="-1">
					<template x-for="error in errors">
						<li x-html="error"></li>
					</template>
				</ul>
			</div>
		</div>
	</template>

	@include('woocommerce.cart.partials.cart-loader')


	<div x-cloak x-show="!loading && count == 0" x-cloak style="display: none !important;" class="ht-container flex flex-col text-lg font-semibold text-center items-center justify-center" style="min-height: 50dvh;">
		@php
		/*
		* @hooked wc_empty_cart_message - 10
		* Do not use the do_action because notices will be print below at the hook 'do_action( 'woocommerce_before_cart' )'
		* If you open this hook, notices will be print here and then cleared. 
		* @see wp-content/plugins/woocommerce/includes/wc-notice-functions.php wc_print_notices() function.
		*/
		do_action( 'woocommerce_cart_is_empty' );
		@endphp
		<div class="flex mt-5">
			<a href="{{ wc_get_page_permalink('shop') }}"
				class="btn-md btn-solid-secondary !w-auto">
				{{ __('Continue Shopping','sage') }}
			</a>
		</div>
	</div>
	
	<div x-cloak x-show="!loading && count > 0" x-cloak style="display: none !important;">
		<section class="">
			@php
				do_action( 'woocommerce_before_cart' );
			@endphp
		</section>
		<section>
			<div class="ht-container-large">
				<div class="">
				<div class="text-xs font-normal border-b border-black pb-1">
					<div class="hidden lg:flex items-center">
					<div class="w-3/6">{{ __('PRODUCTS','sage') }}</div>
					<div class="w-1/6 text-center">{{ __('PRICE','sage') }}</div>
					<div class="w-1/6 text-center">{{ __('QUANTITY','sage') }}</div>
					<div class="w-1/6 text-center">{{ __('REMOVE','sage') }}</div>
					</div>
				</div>
				<div class="flex flex-col">
					{{-- product row --}}
					<template x-for="item in items" :key="item.key">
						<div class="w-full flex flex-col items-center py-5 border-b border-black">

							<div class="w-full gap-3 flex">
								<a :href="item.permalink" class="bg-[#f4f2ee] max-w-[100px] lg:hidden relative flex w-full aspect-[800/1000]">
									<img class="maybeAddMixBlend object-contain w-full h-full" :src="item.image_src" :alt="item.title">
									<span class="top-0 right-0 transform translate-x-[50%] -translate-y-[50%] text-xs absolute bg-secondary text-white w-5 h-5 rounded-full flex items-center justify-center">
										<strong class="product-quantity" x-html="item.quantity"></strong>
									</span>
								</a>
								<div class="flex flex-col lg:flex-row lg:w-full gap-3 lg:gap-0 pr-3 lg:pr-0 w-full">
									<div class="flex items-center gap-3 lg:w-3/6">
										<a :href="item.permalink" class="bg-[#f4f2ee] max-w-[130px] relative hidden lg:flex w-full aspect-[800/1000]">
											<img class="maybeAddMixBlend object-contain w-full h-full" :src="item.image_src" :alt="item.title">
											<span class="top-0 right-0 transform translate-x-[50%] -translate-y-[50%] text-xs absolute bg-secondary text-white w-5 h-5 rounded-full flex items-center justify-center">
												<strong class="product-quantity" x-html="item.quantity"></strong>
											</span>
										</a>
										<div class="w-full lg:w-auto lg:gap-4 flex items-start justify-between">
											<div class="w-full flex flex-col">
												<a :href="item.permalink" class="w-full flex flex-col lg:justify-center lg:w-full lg:max-w-[802px]">

													@if(defined( 'ICL_SITEPRESS_VERSION' ))
														@php
															$lang = apply_filters( 'wpml_current_language', null )
															// show the translated post title. if the cart is synced from WC cart, localization property doesnt exist.
														@endphp
														<div class="w-full text-xs text-body max-w-xs font-bold uppercase" x-html="item.hasOwnProperty('localization') && item.localization.hasOwnProperty('{{$lang}}') ? item.localization['{{$lang}}'].post_title : item.title"></div>
													@else
														<div class="w-full text-xs text-body max-w-xs font-bold uppercase" x-html="item.title"></div>
													@endif
													<div class="w-full text-xs text-body max-w-xs font-bold uppercase" x-html="item.variation_attr"></div>

													<div class="w-full text-xs text-body hidden lg:block" x-html="`{{ __('SKU:','sage') }} ${item.sku}`">
													</div>
												</a>
												<template x-if="item.backorder_note != ''"><div x-html="item.backorder_note"></div></template>
											</div>
										</div>
									</div>
									<div class="w-full flex flex-wrap justify-between lg:justify-start lg:w-3/6">
										<div class="flex items-center text-xs lg:justify-center lg:w-1/3" x-html="item.price_html"></div>
										<div class="flex items-center lg:w-2/3">
											<div class="flex flex-col lg:w-1/2">
												<div class="flex items-center lg:justify-center">
													{{-- minus btn --}}
													<button type="button" class="hover:text-red-500"
													x-on:click.prevent.debounce="if(item.quantity>1)decrement(item.key)">
														<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="w-2 h-8 lg:w-3 lg:h-3">
															<path
																d="M432 256c0 17.7-14.3 32-32 32L48 288c-17.7 0-32-14.3-32-32s14.3-32 32-32l352 0c17.7 0 32 14.3 32 32z"
																fill="currentColor">
															</path>
														</svg>
													</button>
													<div class="text-center mb-0 w-12 text-xs bg-transparent px-2.5" x-text="item.quantity"></div>
													{{-- plus btn --}}
													<button type="button" class="hover:text-black"
													x-on:click.prevent.debounce="increment(item.key)">
														<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="w-2 h-8 lg:w-3 lg:h-3">
															<path
																d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z"
																fill="currentColor">
															</path>
														</svg>
													</button>
												</div>
											</div>
											<button type="button" class="flex items-center lg:justify-center ml-7 lg:w-1/2" x-on:click.prevent.debounce="removeItemFromCart(item.key)">
												<svg
												class="text-red-600 transition-all ease-in-out duration-150 w-2 h-8 lg:w-3 lg:h-3"
												xmlns="http://www.w3.org/2000/svg" width="14" height="14"
												viewBox="0 0 14.121 14.121">
												<g id="Group_16004" data-name="Group 16004"
													transform="translate(1.061 1.061)">
													<line id="Line_385" data-name="Line 385" x2="12" y2="12"
														transform="translate(0)" fill="none" stroke="currentColor"
														stroke-width="3"/>
													<line id="Line_386" data-name="Line 386" x1="12" y2="12" fill="none"
														stroke="currentColor" stroke-width="3"/>
												</g>
												</svg>
											</button>
										</div>
									</div>
								</div>
							</div>

							<div class="w-full mt-1 text-xs text-red-600 text-center" x-show="item.messages.incrementDisabled" x-html="item.messages.incrementDisabled" x-cloak style="display: none !important;"></div>
							<div class="w-full mt-1 text-xs text-red-600 text-center" x-show="item.messages.decrementDisabled" x-html="item.messages.decrementDisabled" x-cloak style="display: none !important;"></div>
						</div>
					</template>
				</div>
				</div>
			</div>
		</section>


		{{-- totals --}}
		<section class="ht-container-large">
			<div class="grid grid-cols-1 md:grid-cols-2 gap-x-5">
				<div class="">

					<div class="pt-4 pb-1 border-b border-black flex items-end">
						<div class="text-xs text-body font-normal">{{ __('SHIPPING','sage') }}</div>
					</div>

					<div>
						@if(config('theme.checkoutProvider','wordpress') == 'stripe')
							{{-- custom shipping form for stripe --}}
							<form id="ht_shipping_form" 
								data-validatetext="{{ __('Please fill the required shipping fields.','sage') }}"
								class="py-4" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
								<div class="woocommerce-shipping-fields">
								
									<div class="shipping_address">
							
										<?php 
											$checkout = WC()->checkout();
											do_action( 'woocommerce_before_checkout_shipping_form', $checkout ); 
										?>
							
										<div class="woocommerce-shipping-fields__field-wrapper">
											<?php
											$fields = $checkout->get_checkout_fields( 'shipping' );
							
											foreach ( $fields as $key => $field ) {
												woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
											}
											?>
										</div>
							
										<?php do_action( 'woocommerce_after_checkout_shipping_form', $checkout ); ?>
							
									</div>
								
								</div>
							</form>
						@else
							<table id="cart-shiping-table" class="my-4 text-xs w-full">
								<tbody x-html="shippingHtml"></tbody>
							</table> 
						@endif
					</div>

				</div>
				<div class="">

					<div class="pt-4 pb-1 border-b border-black flex items-center justify-between">
						<div class="text-xs text-body font-normal">{!! esc_html_e( 'TOTALS', 'sage' ) !!}</div>
					</div>
					
					<div class="my-4"> 
						<div class="py-3 border-b border-[#e5e7eb] flex items-center justify-between">
							<div class="text-xs">{{ __('SUBTOTAL','sage') }}:</div>
							<div class="font-medium text-xs" x-html="`<small class='mr-3'>${subtotalVatSuffix}</small>${subtotalFormatted}`"></div>
						</div>

						<template x-for="coupon in coupons">
							<div class="py-3 border-b border-[#e5e7eb] flex items-center justify-between">
								<div class="text-xs" x-html="`{{ __('COUPON','sage') }} (${coupon.label}):`"></div>
								<div class="font-medium text-xs" x-html="`- ${coupon.formatted}`"></div>
							</div>
						</template>
		
						<div x-show="Number(shipping.shipping_total) > 0" class="py-3 border-b border-[#e5e7eb] flex items-center justify-between">
							<div class="text-xs">{{ __('SHIPPING','sage') }}:</div>
							<div class="font-medium text-xs" x-html="shipping.shipping_total_formatted"></div>
						</div>

						<div>
							<table class="w-full">
								<tbody class="flex w-full" x-html="feesHtml">

								</tbody>
							</table>
						</div>

						<div>
							<table class="w-full">
								<tbody class="flex w-full" x-html="taxHtml">

								</tbody>
							</table>
						</div>

						<div class="py-3 flex items-center justify-between">
							<div class="text-xs">{{ __('TOTAL','sage') }}:</div>
							<div class="font-medium text-xs" x-html="`${totalFormatted} ${totalVatSuffix}`"></div>
						</div>
					</div>

				</div>

			</div>
		</section>

		{{-- Apply Coupon --}}
		<section class="ht-container-large pt-4 pb-8 border-t border-black">
			<div class="grid grid-cols-1 md:grid-cols-2 gap-x-5 gap-y-4">
				{{-- free shipping --}}
				<div class="">
					@include('woocommerce/cart/partials/freeshipping')
				</div>
				<div class="text-xs flex flex-wrap items-end h-full w-full">

					<div class="md:col-span-2 mb-2">
						<div class="flex" x-show="coupons.length>0">
							<div class="flex items-center">{{ __('COUPONS','sage') }}</div>  
							<div class="mx-1 flex items-center">|</div>
							<div class="flex space-x-2 items-center">
								<template x-for="coupon in coupons">
									<button type="button" x-on:click="removeCoupon(coupon.label)" class="font-medium text-xs flex flex-shrink-0 mx-1.5 items-center border-[#e5e7eb] bg-white border px-3.5 py-2.5 transition duration-200 ease-in-out hover:border-red-500 hover:text-red-600">
										<span x-html="coupon.label"></span>
										<svg 
											stroke="currentColor" fill="currentColor"
											stroke-width="0" viewBox="0 0 512 512"
											class="text-xs flex-shrink-0 ml-2"
											height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
											<path
												d="M289.94 256l95-95A24 24 0 00351 127l-95 95-95-95a24 24 0 00-34 34l95 95-95 95a24 24 0 1034 34l95-95 95 95a24 24 0 0034-34z">
											</path>
										</svg>
									</button>
								</template>
							</div>
						</div>
					</div>

					<template x-if="coupon.applyCouponSuccess !== null">
						<div :class="coupon.applyCouponSuccess ? 'text-[#229E44]' : 'text-red-600'" class="w-full text-center text-xs mb-2" x-html="coupon.applyCouponMessage"></div>
					</template>

					<div class="grid grid-cols-2 gap-3 w-full">
						<input @keyup.enter.debounce="applyCoupon()" type="text" class="border border-primary btn-md outline-none" name="apply_coupon" value="" style="height:40px!important;" x-model="couponCode" placeholder="{{ __('COUPON','sage') }}">
						<button 
							type="button" 
							x-on:click.prevent.debounce="applyCoupon()"
							class="btn-md btn-solid-primary">
							{{ __('APPLY','sage') }}
						</button>
					</div>
				</div>
			</div>
		</section>


		{{-- buttons --}}
		<section class="pb-8">
			<div class="ht-container-large grid grid-cols-1 gap-y-4 lg:gap-y-0 lg:grid-cols-2 lg:gap-4 text-xs lg:text-base ">
				<a href="{{ wc_get_page_permalink('shop') }}"
				class="order-2 lg:order-1 btn-md btn-outlined-primary">
				{{ __('BACK TO STORE','sage') }}
				</a>
				<button 
					type="button"
					x-on:click="validateAndGoToCheckout( '{{ wc_get_checkout_url() }}' )"
					:disabled="disableCheckoutButton"
					class="order-1 lg:order-2 btn-md btn-solid-primary"
				>
				{{ __('CHECKOUT','sage') }}
				</button>

			</div>
		</section>

		<section class="">
			@php
			/**
			* Cart collaterals hook.
			*
			* @hooked woocommerce_cross_sell_display
			* @hooked woocommerce_cart_totals - 10
			*/
			do_action( 'woocommerce_cart_collaterals' );
			@endphp
		
			@php do_action( 'woocommerce_after_cart' ); @endphp
		</section>
		
	</div>

</div>

<div id="cart_loader_modal" class="hidden">
	<div class="flex flex-col items-center gap-5">
		<div class="">{{ __('One moment, verifying stock in our ERP','sage') }}</div>
		<svg class="animate-spin h-10 w-10 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
			<circle class="opacity-25" cx="12" cy="12" r="10" stroke="#000" stroke-width="4"></circle>
			<path class="opacity-75" fill="#000" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
		</svg>
	</div>
</div>
