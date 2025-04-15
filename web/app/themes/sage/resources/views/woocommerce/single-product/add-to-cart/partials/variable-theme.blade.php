{{--
/**
 * Variable product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/variable.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 6.1.0
 */
--}}
@php
use App\HT\Services\Product\MediaService;

defined( 'ABSPATH' ) || exit;

$attribute_keys  = array_keys( $attributes );
$variations_json = wp_json_encode( $available_variations );
$variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );

do_action( 'woocommerce_before_add_to_cart_form' ); 
@endphp

<form 
	x-data="buttonAddToCartSinglePage({ productCardData: JSON.parse( atob('{{ base64_encode(json_encode($productCardData)) }}') ) })" 
	x-ref="form" class="relative z-30 variations_form cart" action="@php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); @endphp" method="post" enctype='multipart/form-data' data-product_id="@php echo absint( $product->get_id() ); @endphp" data-product_variations="@php echo $variations_attr;@endphp">
	<div class="flex flex-col items-start space-s-4">
	
		@php do_action( 'woocommerce_before_variations_form' ); @endphp
		@php if ( false ) : @endphp
			<p class="stock out-of-stock">@php echo esc_html( apply_filters( 'woocommerce_out_of_stock_message', __( 'This product is currently out of stock and unavailable.', 'woocommerce' ) ) ); @endphp</p>
		@php else : @endphp
			
			{{-- Variations Attributes --}}
			<div class="w-full mb-4">		
				@foreach ( $attributes as $attribute_name => $options )
				@php
					$attribute_id = 0;
					$display_type = [];
					foreach (wc_get_attribute_taxonomies() as $att) {
						if ('pa_' . $att->attribute_name === $attribute_name) {
							$attribute_id = $att->attribute_id;
							break;
						}
					}
					$d_type = $attribute_id ? get_option( "wc_attribute_display_type-$attribute_id" ) : 'default';
					$display_type[$attribute_name] = $d_type ? $d_type : 'default';
				@endphp
					<x-attribute-input
						:displayType="$display_type[$attribute_name]"
						:attributeName="$attribute_name"
						:options="$options"
						:allVariations="$productCardData['variations']"
					/>

					{{-- 
						/**
						* Filters the reset variation button.
						*
						* @since 2.5.0
						*
						* @param string  $button The reset variation button HTML.
						*/ 
					--}}
					{!! end( $attribute_keys ) === $attribute_name ? wp_kses_post( apply_filters( 'woocommerce_reset_variations_link', '<a class="reset_variations text-red-600 underline" href="#" aria-label="' . esc_attr__( 'Clear options', 'woocommerce' ) . '">' . esc_html__( 'Clear selections', 'sage' ) . '</a>' ) ) : ''; !!}
				@endforeach
			</div>

			<table class="variations hidden" cellspacing="0" role="presentation">
				<tbody>
					<?php foreach ( $attributes as $attribute_name => $options ) : ?>
						<tr>
							<th class="label mb-2"><label for="<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>"><?php echo wc_attribute_label( $attribute_name ); // WPCS: XSS ok. ?></label></th>
							<td class="value">
								<?php
									wc_dropdown_variation_attribute_options(
										array(
											'options'   => $options,
											'attribute' => $attribute_name,
											'product'   => $product,
										)
									);
									//echo end( $attribute_keys ) === $attribute_name ? wp_kses_post( apply_filters( 'woocommerce_reset_variations_link', '<a class="border px-2 py-0.5 text-black text-xs lg:text-sm uppercase font-semibold transition duration-200 ease-in-out hover:border-red-600 hover:text-red-600 !no-underline reset_variations" href="#">' . esc_html__( 'Clear', 'woocommerce' ) . '</a>' ) ) : '';
								?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>

			@php do_action( 'woocommerce_after_variations_table' ); @endphp

			<div class="single_variation_wrap w-full fixed bottom-0 left-0 lg:relative !p-3 lg:!p-0 bg-bodyBg">
				@php
					/**
					 * Hook: woocommerce_before_single_variation.
					 */
					do_action( 'woocommerce_before_single_variation' );

					if ( empty( $available_variations ) && false !== $available_variations ){
					}else{
						/**
						* Hook: woocommerce_single_variation. Used to output the cart button and placeholder for variation data.
						*
						* @since 2.4.0
						* @hooked woocommerce_single_variation - 10 Empty div for variation data.
						* @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
						*/
						do_action( 'woocommerce_single_variation' );
					}

					/**
					 * Hook: woocommerce_after_single_variation.
					 */
					do_action( 'woocommerce_after_single_variation' );
				@endphp
			</div>
		@php endif; @endphp

		@php do_action( 'woocommerce_after_variations_form' ); @endphp
	</div>
</form>

@php
do_action( 'woocommerce_after_add_to_cart_form' );
@endphp
