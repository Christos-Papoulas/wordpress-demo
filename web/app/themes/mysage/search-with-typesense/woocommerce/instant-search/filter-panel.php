<?php
$passed_args = $args['passed_args'] ?? [];
?>
<div class="cmtsfwc-filter-panel max-w-72 block w-[calc(50%_-_6px)] xl:w-[calc(100%_-_18rem)] xl:pr-8">
	<?php do_action( 'cm_tsfwc_before_filter_panel_start' ); ?>
    <div class="cmtsfwc-FilterToggle !fixed !bottom-4 !left-1/2 transform -translate-x-[50%] !bg-black !text-white !flex gap-1 items-center z-40 !rounded-full !px-3 !py-2 xl:!hidden">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
        </svg>
        <span class="cmtsfwc-FilterToggle-label text-white text-xs">
            <?php _e( 'Filter', 'typesense-search-for-woocommerce' ); ?>
        </span>
    </div>
    <div class="cmtsfwc-Filter-items">
        <div class="cmtsfwc-Filter-itemsHeader">
            <h3><?php _e( 'Filter Search Results', 'typesense-search-for-woocommerce' ); ?></h3>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 cmtsfwc-Filter-itemsHeaderCloseIcon cmtsfwc-Filter-itemsClose" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </div>
        <div class="cmtsfwc-Filter-itemsContent">
			<?php
			/**
			 * Codemanas\Typesense\WooCommerce\Main\TemplateHooks - category_filter 5
			 * Codemanas\Typesense\WooCommerce\Main\TemplateHooks - price_filter 10
			 * Codemanas\Typesense\WooCommerce\Main\TemplateHooks - rating_filter 15
			 * Codemanas\Typesense\WooCommerce\Main\TemplateHooks - product_filter 20
			 * Codemanas\Typesense\WooCommerce\Main\TemplateHooks - custom_filters 25
			 */
			do_action( 'cm_tsfwc_filter_panel_output', $passed_args );
			?>
        </div>
        <div class="cmtsfwc-Filter-itemsFooter">
            <a href="#" class="cmtsfwc-Filter-itemsFooterLink cmtsfwc-Filter-itemsClose" onclick="void(0)"><?php _e( 'Close', 'typesense-search-for-woocommerce' ); ?></a>
        </div>
    </div>
	<?php
	//allows other widgets / code etc. that are not dependent on instant search to be added.
	do_action( 'cm_tsfwc_before_filter_panel_end' );
	?>
</div>
