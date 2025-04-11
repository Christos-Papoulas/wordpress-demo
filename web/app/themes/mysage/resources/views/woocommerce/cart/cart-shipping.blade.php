<?php
/**
 * Shipping Methods Display
 *
 * In 2.1 we show methods per package. This allows for multiple methods per order if so desired.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-shipping.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

use App\HT\Models\Store;
use App\HT\Services\StoreService;
$stores = StoreService::getStores();

$formatted_destination    = isset( $formatted_destination ) ? $formatted_destination : WC()->countries->get_formatted_address( $package['destination'], ', ' );
$has_calculated_shipping  = ! empty( $has_calculated_shipping );
$show_shipping_calculator = ! empty( $show_shipping_calculator );
$calculator_text          = '';
?>
<tr class="woocommerce-shipping-totals shipping flex flex-wrap">
	<th class="hidden pb-9 border-b border-gray-300"><?php //echo wp_kses_post( $package_name ); ?></th>
	<td data-title="<?php echo esc_attr( $package_name ); ?>" class="w-full">
		<?php if ( $available_methods ) : ?>
			<ul id="shipping_method" class="woocommerce-shipping-methods list-none mb-0 ml-0 space-y-2">
				<?php foreach ( $available_methods as $method ) : ?>
					<li class="flex flex-col relative @if($chosen_method == $method->id) border-[#B6B6B6] border-[1.5px] @endif"
					@if($method->get_method_id() == 'local_pickup' && $chosen_method == $method->id)

						{{-- Set available store ids  --}}
						@php
							$availableStoreIds = [];
						@endphp
						@foreach($stores as $key => $store)
							@php
								$availableStoreIds[] =  $store->ID;
							@endphp
						@endforeach

						{{-- Set fallback selection as the first available store id --}}
						@foreach($stores as $key => $store)
							@php
								if(ht_get_field('store_custom_fields_local_pickup_enabled', $store->ID) == 'yes'){ 
									$selectedStoreID =  $store->ID;
									break;
								}
							@endphp
						@endforeach

						x-data="{ openStores : true, selectedStore: localStorage.getItem('ht_selected_store') || {{ $selectedStoreID }} }"
					@endif
					>
						
						<div class="flex items-center text-xs text-body uppercase px-5 py-3">
							<?php
							if ( 1 < count( $available_methods ) ) {
								printf( '<input type="radio" name="shipping_method[%1$d]" data-index="%1$d" id="shipping_method_%1$d_%2$s" value="%3$s" class="shrink-0 checkbox shipping_method mr-4" %4$s />', $index, esc_attr( sanitize_title( $method->id ) ), esc_attr( $method->id ), checked( $method->id, $chosen_method, false ) ); // WPCS: XSS ok.
							} else {
								printf( '<input type="hidden" name="shipping_method[%1$d]" data-index="%1$d" id="shipping_method_%1$d_%2$s" value="%3$s" class="shrink-0 shipping_method" />', $index, esc_attr( sanitize_title( $method->id ) ), esc_attr( $method->id ) ); // WPCS: XSS ok.
							}
							printf( '<label class="w-full cursor-pointer mr-4" for="shipping_method_%1$s_%2$s">%3$s</label>', $index, esc_attr( sanitize_title( $method->id ) ),  wc_cart_totals_shipping_method_label( $method ) ); // WPCS: XSS ok.
							do_action( 'woocommerce_after_shipping_rate', $method, $index );
							?>
						</div>

						@if($method->get_method_id() == 'local_pickup' && $chosen_method == $method->id)
							<button type="button" x-on:click.prevent="openStores = !openStores" :class="openStores && 'rotate-180'" class="text-body top-5 right-4 h-5 transform -translate-y-[50%] absolute flex items-center">
								<svg width="16" height="8" viewBox="0 0 16 8" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M15.4111 0.666014L11.7411 3.66601L8.07118 6.66601L0.867188 0.666016" stroke="currentColor" stroke-miterlimit="10"/>
								</svg>
							</button>
							<div x-cloak x-show="openStores" id="store-selection" class="w-full">
								<?php $loopno = 0; ?>
								
								@foreach($stores as $key => $store)
									@php
										if(ht_get_field('store_custom_fields_local_pickup_enabled', $store->ID) != 'yes'){ continue;}
										$loopno++;
									@endphp
									<div 
										x-on:click="
											selectedStore = {{ $store->ID }}
											localStorage.setItem('ht_selected_store', selectedStore);
										" 
										class="@if($loopno == 1) border-b border-t @else border-b @endif border-white bg-slate-100 flex flex-col px-5 text-xs">									
										<div class="flex items-center justify-between h-10 flex-row-reverse">
											<label class="flex w-full h-full items-center cursor-pointer" for="{{ $store->post_name }}">{{ $store->post_title }}</label>
											<input :checked="selectedStore == {{$store->ID}}" class="mr-4" type="radio" id="{{ $store->post_name }}" name="{{ Store::INPUT_NAME }}" value="{{ $store->ID }}"/>
										</div>
										
										{{-- <div x-cloak x-show="selectedStore == {{ $store->ID }}" class="font-normal text-xs pb-5 pl-5" style="line-height:2; max-width:330px;">
											{!! strip_tags($store->post_content, ['<br>','<div>','<strong>','<em>']) !!}
											@php $fields = ht_get_field('store_custom_fields', $store->ID); @endphp
											{{ $fields['address'] . ' ' . $fields['address_number'] . ', ' . $fields['zip_code'] }}
										</div> --}}
									</div>
								@endforeach
						
							</div>
						@endif

					</li>
				<?php endforeach; ?>
			</ul>
			<?php if ( is_cart() ) : ?>
				<p class="woocommerce-shipping-destination">
					<?php
					if ( $formatted_destination ) {
						// Translators: $s shipping destination.
						printf( esc_html__( 'Shippint to %s.', 'sage' ) . ' ', '<strong>' . esc_html( $formatted_destination ) . '</strong>' );
						$calculator_text = esc_html__( 'Edit Address', 'sage' );
					} else {
						echo wp_kses_post( apply_filters( 'woocommerce_shipping_estimate_html', __( 'Shipping options will be updated during checkout.', 'woocommerce' ) ) );
					}
					?>
				</p>
			<?php endif; ?>
			<?php
		elseif ( ! $has_calculated_shipping || ! $formatted_destination ) :
			if ( is_cart() && 'no' === get_option( 'woocommerce_enable_shipping_calc' ) ) {
				echo wp_kses_post( apply_filters( 'woocommerce_shipping_not_enabled_on_cart_html', __( 'Shipping costs are calculated during checkout.', 'woocommerce' ) ) );
			} else {
				echo wp_kses_post( apply_filters( 'woocommerce_shipping_may_be_available_html', __( 'Enter your address to view shipping options.', 'woocommerce' ) ) );
			}
		elseif ( ! is_cart() ) :
			echo wp_kses_post( apply_filters( 'woocommerce_no_shipping_available_html', __( 'There are no shipping options available. Please ensure that your address has been entered correctly, or contact us if you need any help.', 'woocommerce' ) ) );
		else :
			// Translators: $s shipping destination.
			echo wp_kses_post( apply_filters( 'woocommerce_cart_no_shipping_available_html', sprintf( esc_html__( 'No shipping options were found for %s.', 'woocommerce' ) . ' ', '<strong>' . esc_html( $formatted_destination ) . '</strong>' ) ) );
			$calculator_text = esc_html__( 'Enter a different address', 'woocommerce' );
		endif;
		?>

		<?php if ( $show_package_details ) : ?>
			<?php echo '<p class="woocommerce-shipping-contents"><small>' . esc_html( $package_details ) . '</small></p>'; ?>
		<?php endif; ?>

		<?php if ( $show_shipping_calculator ) : ?>
			<?php woocommerce_shipping_calculator( $calculator_text ); ?>
		<?php endif; ?>
	</td>
</tr>
