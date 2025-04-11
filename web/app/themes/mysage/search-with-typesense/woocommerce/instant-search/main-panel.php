<?php
$config      = $args['config'] ?? [];
$passed_args = $args['passed_args'] ?? [];
?>

<div class="w-full xl:w-[calc(100%_-_18rem)]">
	<div <?php if($passed_args['unique_id'] == 'ts_woo_main_search'){ echo 'id="ts_woo_main_search_results"';} ?> class="cmtsfwc-Results">
		<?php
		/**
		 * Codemanas\Typesense\WooCommerce\Main\TemplateHooks - search_results_heading - 5
		 * Codemanas\Typesense\WooCommerce\Main\TemplateHooks - search_results_output - 10
		 * Codemanas\Typesense\WooCommerce\Main\TemplateHooks - pagination - 15
		 */
		do_action( 'cm_tsfwc_instant_search_results_main_panel', [ 'passed_args' => $passed_args, 'config' => $config ] );
		?>
	</div>
</div>
