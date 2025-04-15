<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
?>
<section class="px-3 md:px-0">
    <div id="product-{{ the_ID() }}" {!! wc_product_class( '', $product ) !!}>
        <div class="flex flex-wrap">
            <div class="w-full md:w-2/3 mb-3 md:mb-0 flex justify-end">
                @php
                /**
                 * Hook: woocommerce_before_single_product_summary.
                 *
                 * @hooked woocommerce_show_product_sale_flash - 10
                 * @hooked woocommerce_show_product_images - 20
                 */
                do_action( 'woocommerce_before_single_product_summary' );
                @endphp
                <div class="w-full">
                    @include('woocommerce.single-product.partials.gallery')
                </div>
            </div>

            <div id="single-product-info" class="w-full md:w-1/3 md:px-4 lg:px-7 summary entry-summary relative">
                <div id="single-product-sticky-container" class="lg:sticky lg:top-[4.5rem]">
                    <div class="flex flex-col md:relative md:-top-1 lg:-top-[7px]">
                        <div class="font-bold md:font-normal uppercase text-xs lg:text-2xl xl:text-3xl">{!! $product->get_title() !!}</div>
                        @include('woocommerce.single-product.partials.price')                
                    </div>

                    <div class="pt-5 w-full">
                        @include('woocommerce.single-product.partials.short-description')
                    </div>

                    @php
                        /**
                         * Hook: woocommerce_single_product_summary.
                         *
                         * @hooked woocommerce_template_single_title - 5
                         * @hooked woocommerce_template_single_rating - 10
                         * @hooked woocommerce_template_single_price - 10
                         * @hooked woocommerce_template_single_excerpt - 20
                         * @hooked woocommerce_template_single_add_to_cart - 30
                         * @hooked woocommerce_template_single_meta - 40
                         * @hooked woocommerce_template_single_sharing - 50
                         * @hooked WC_Structured_Data::generate_product_data() - 60
                         */
                        do_action( 'woocommerce_single_product_summary' )
                    @endphp

                    <div class="pt-5 w-full"> 
                        <x-wishlist-button
                        :product="$product"
                        context="single-product-page"
                        class="btn-md btn-outlined-primary w-full"
                         />
                    </div>

                    <div class="my-8">
                        @include('woocommerce.single-product.partials.benefits')
                    </div>

                    @if($product->get_sku())
                    <div class="my-8 flex gap-3">
                        <span>{{ __('SKU:', 'sage') }}</span>
                        <span id="js_variation_sku">{{ $product->get_sku() }}</span>
                    </div>
                    @endif 

                    <div x-data="{isOpen:false}" class="facete-wrapper border-0">
                        <h2 class="facete-heading mb-0">
                            <button x-on:click="isOpen = !isOpen" type="button" class="flex items-center justify-between w-full py-[10px] border-b border-black text-body" aria-expanded="false">
                                <span class="text-[10px]/3 md:text-xs/4 uppercase font-bold">{{ __('MORE INFORMATION','sage') }}</span>
                                <svg x-show="!isOpen" viewBox="0 0 24 24" role="img" class="facete_svg_open" width="24" height="24"><path fill="currentColor" d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z"></path></svg>
                                <svg x-show="isOpen" x-cloak viewBox="0 0 24 24" role="img" aria-hidden="true" class="facete_svg_close" width="24" height="24"><path fill="currentColor" d="M19,13H5V11H19V13Z"></path></svg>
                            </button>
                        </h2>
                        <div class="facete-body h-0 overflow-hidden el-collapsible" :class="isOpen && 'is-open'" :style="isOpen && {height: $el.scrollHeight+`px`}">
                            @include('woocommerce.single-product.partials.description')
                        </div>
                    </div>
                    <div x-data="{isOpen:false}" class="facete-wrapper border-0">
                        <h2 class="facete-heading mb-0">
                            <button x-on:click="isOpen = !isOpen" type="button" class="flex items-center justify-between w-full py-[10px] border-b border-black text-body" aria-expanded="false">
                                <span class="text-[10px]/3 md:text-xs/4 uppercase font-bold">{{ __('SPECS','sage') }}</span>
                                <svg x-show="!isOpen" viewBox="0 0 24 24" role="img" class="facete_svg_open" width="24" height="24"><path fill="currentColor" d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z"></path></svg>
                                <svg x-show="isOpen" x-cloak viewBox="0 0 24 24" role="img" aria-hidden="true" class="facete_svg_close" width="24" height="24"><path fill="currentColor" d="M19,13H5V11H19V13Z"></path></svg>
                            </button>
                        </h2>
                        <div class="facete-body h-0 overflow-hidden el-collapsible" :class="isOpen && 'is-open'" :style="isOpen && {height: $el.scrollHeight+`px`}">
                            <div class="py-5">
                                <?php do_action( 'woocommerce_product_additional_information', $product ); ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
        
        {{-- <div class="ht-container">
            @include('woocommerce.single-product.partials.reviews', ['reviews' => $product_reviews])
        </div> --}}

        @php
        /**
         * Hook: woocommerce_after_single_product_summary.
         *
         * @hooked woocommerce_output_product_data_tabs - 10
         * @hooked woocommerce_upsell_display - 15
         * @hooked woocommerce_output_related_products - 20
         */
        do_action( 'woocommerce_after_single_product_summary' )
        @endphp
    </div>

    <div class="mt-32">
        @php do_action( 'woocommerce_after_single_product' ) @endphp
    </div>

</section>

<div>
    <p class="ht-container-no-max-width text-xs xl:text-3xl">{{ __('BOUGHT TOGETHER','sage') }}</p>
    @include('blocks.product.product-slider',[
        'slider'=>[
            'products' => $cross_sells_and_upsells[0],
            'list_id' => 'bought_together_products',
            'list_name' => __('Bought together products','sage')
        ]
    ])
</div>

<div>
    <p class="ht-container-no-max-width text-xs xl:text-3xl">{{ __('MOST POPULAR RIGHT NOW','sage') }}</p>
    @include('blocks.product.product-slider',[
        'slider'=>[
            'products' => $cross_sells_and_upsells[1],
            'list_id' => 'related_products',
            'list_name' => __('Related Products','sage')
        ]
    ])
</div>
