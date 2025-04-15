<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;
?>
{{-- Don't remove this classes from the wrapper div. Woocommerce populates/refreshes the div with theses classes (shop_table woocommerce-checkout-review-order-table) --}}
{{-- If the menu is sticky, adjust top and max-height of dropdown based on the height of the sticky menu --}}
<div 
:class="showReviewOrderDropdown ? 'h-[calc(85vh_-_324px)]' : 'h-auto'"
class="fixed lg:h-auto top-[95px] md:top-[95px] lg:top-0 left-0 w-full lg:relative shop_table woocommerce-checkout-review-order-table"> 
	<button 
		type="button"
		x-on:click="
			toggleReviewOrderDropdown()
		"
		class="h-12 bg-white text-sm font-semibold flex items-center justify-between lg:hidden w-full shadow-md lg:w-auto lg:shadow-none p-3 lg:p-0">
		<div class="flex items-center gap-1"> 
			<span x-text="showReviewOrderDropdown ? '{{ __("Hide Order Summary","htech") }}' : '{{ __("Show Order Summary","htech") }}'">{{ __("Hide Order Summary","htech") }}</span>
			<svg class="text-body rotate-180" x-bind:class="{'rotate-180': showReviewOrderDropdown}" xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512" fill="currentColor">
				<path d="M201.4 137.4c12.5-12.5 32.8-12.5 45.3 0l160 160c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L224 205.3 86.6 342.6c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3l160-160z"></path>
			</svg>
		</div>
		<div x-cloak x-show="orderTotalAmountHtml.loading" class="animate-pulse w-16 h-6 w bg-primary"></div>
		<div x-cloak x-show="!orderTotalAmountHtml.loading" class="bg-primary px-2 py-0.5 text-white flex justify-center items-center text-base font-semibold" x-html="orderTotalAmountHtml.value"></div>
	</button>
	<table 
		x-cloak
		x-show="showReviewOrderDropdown"
		class="flex lg:!flex flex-col border-t border-[#B6B6B6] lg:border-0 lg:bottom-0 ht-container lg:!px-0 bg-white h-[calc(85vh_-_324px)] lg:h-auto">
		{{-- <thead>
			<tr>
				<th class="product-name"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
				<th class="product-total"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
			</tr>
		</thead> --}}
		<tbody class="bg-white py-4 lg:py-0 overflow-auto h-[444px] lg:h-auto">
			<?php
			do_action( 'woocommerce_review_order_before_cart_contents' );

			$loop = 0;
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$loop++;
					?>
					<tr @if($loop > 3) :class="showAllProductsInReview ? '' : 'lg:hidden'" @endif class="flex @if($loop === 1) pt-4 pb-5 @else py-5 @endif border-b border-black items-start gap-3 justify-between <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
						<td class="gap-3 flex items-center">
							<div class="bg-[#f4f2ee] max-w-[130px] relative hidden lg:flex w-full aspect-[800/1000]">
								@php
									$img_src = null;
									$thumb_id = get_post_thumbnail_id($_product->get_ID());
									if($thumb_id){
										$img_src = wp_get_attachment_image_src($thumb_id, 'woocommerce_thumbnail')[0];
									}
									if($img_src === null && $_product->get_type() == 'variation'){              
										$img_src = wp_get_attachment_image_src(get_post_thumbnail_id($_product->get_parent_id()), 'woocommerce_thumbnail')[0];
									}
									$image = $img_src ?? wc_placeholder_img_src( 'woocommerce_thumbnail' );
								@endphp
								<img class="maybeAddMixBlend object-contain w-full h-full" src="{{ $image }}" alt="{{ $_product->get_name() }}">
								<span class="top-0 right-0 transform translate-x-[50%] -translate-y-[50%] text-xs absolute bg-secondary text-white w-5 h-5 rounded-full flex items-center justify-center">
									<strong>{{ $cart_item['quantity'] }}</strong>
								</span>
							</div>

							<div class="h-[124px] flex flex-col justify-between">
								<div class="flex flex-wrap">
									<div class="product-name w-full text-xs text-body font-bold uppercase"><?php echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ) . '&nbsp;'; ?></div>
									@if($_product->get_sku())
										<div class="w-full text-xs text-body">
											{{ __('SKU:', 'sage') . ' ' . $_product->get_sku() }}
										</div>
									@endif
									<?php
									if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
											echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $_product->get_ID() ) );
										}
									?>
								</div>
								{{--
								<div class="flex gap-4">
									<div class="flex items-center">
										
										<button class="bg-[#F5F5F5] w-6 h-6 flex items-center justify-center text-[#707072] hover:text-red-600transition"
										x-on:click.prevent.debounce="decrement('{{$cart_item_key}}')">
											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="w-2 h-8 lg:w-3 lg:h-3">
												<path
													d="M432 256c0 17.7-14.3 32-32 32L48 288c-17.7 0-32-14.3-32-32s14.3-32 32-32l352 0c17.7 0 32 14.3 32 32z"
													fill="currentColor">
												</path>
											</svg>
										</button>

										<div class="text-lg font-semibold px-2.5">{{ $cart_item['quantity'] }}</div>
									
										<button class="bg-[#F5F5F5] w-6 h-6 flex items-center justify-center text-[#707072] hover:text-body transition"
										x-on:click.prevent.debounce="increment('{{$cart_item_key}}')">
											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="w-2 h-8 lg:w-3 lg:h-3">
												<path
													d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z"
													fill="currentColor">
												</path>
											</svg>
										</button>
									</div>
									<div class="h-6 w-[2px] bg-primary relative top-0.5"></div>
									<button class="text-sm font-semibold text-[#707072] hover:text-red-600" x-on:click.prevent.debounce="removeItemFromCart(item.key)">
										{{ __('Remove','sage') }}
									</button>
								</div> --}}
							</div>
						</td>
						<td class="product-total text-xs text-right">
							<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</td>
					</tr>
					<?php
				}
			}

			do_action( 'woocommerce_review_order_after_cart_contents' );
			?>

			{{-- show more toggler --}}
			@if(is_array(WC()->cart->get_cart()) && count(WC()->cart->get_cart()) > 3)
				<tr class="hidden lg:flex">
					<td class="flex justify-between gap-3 w-full pt-4">
						<button type="button" x-on:click.prevent.debounce="showAllProductsInReview = !showAllProductsInReview" class="bg-[#ebedf4] px-1 text-[#7e839e]" x-text="showAllProductsInReview ? `{{ __('Show less products','sage') }}` : `{{ __('Show more products','sage') }} (+{{ count(WC()->cart->get_cart()) - 3 }})`"></button>
					</td>
				</tr>
			@endif

			{{-- custom coupon discount input for desktop --}}
			<tr class="hidden lg:flex w-full">
				<td class="flex flex-col gap-5 w-full pt-6 relative">
					<p class="ht-custom form-row form-row-first mb-0">
						<label class="text-xs text-body font-bold uppercase" for="apply_coupon">{{ __('Coupon','woocommerce') }}</label>
						<input @keyup.enter.debounce="applyCoupon($refs.couponCodeDesktop.value)" x-ref="couponCodeDesktop" type="text" class="placeholder:text-xs" placeholder="{{ __('DISCOUNT CODE','sage') }}" required="">
					</p>
					<button 
						type="button" 
						x-on:click.prevent.debounce="applyCoupon($refs.couponCodeDesktop.value)"
						class="btn-sm btn-solid-primary absolute right-0 bottom-2">
						{{ __('Redemption','sage') }}
					</button>
				</td>
			</tr>
		</tbody>
		<tfoot class="bg-white">

			<tr class="cart-subtotal">
				<th>{{ __( 'Subtotal', 'woocommerce' ) }}</th>
				<td><?php wc_cart_totals_subtotal_html(); ?></td>
			</tr>

			<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
				<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
					<th><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
					<td><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
				</tr>
			<?php endforeach; ?>

			<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
				<tr class="fee">
					<th><?php echo esc_html( $fee->name ); ?></th>
					<td><?php wc_cart_totals_fee_html( $fee ); ?></td>
				</tr>
			<?php endforeach; ?>

			<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
				<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
					<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
						<tr class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
							<th><?php echo esc_html( $tax->label ); ?></th>
							<td><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
						</tr>
					<?php endforeach; ?>
				<?php else : ?>
					<tr class="tax-total">
						<th><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></th>
						<td><?php wc_cart_totals_taxes_total_html(); ?></td>
					</tr>
				<?php endif; ?>
			<?php endif; ?>

			<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

			<tr class="order-total flex border-t-[1.5px] border-[#B6B6B6] justify-between items-center lg:!pb-0">
				<th>{{ __( 'Total', 'woocommerce' ) }}</th>
				<td><?php wc_cart_totals_order_total_html(); ?></td>
			</tr>

			<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

		</tfoot>
	</table>
</div>

