<?php

use Codemanas\Typesense\Backend\Admin;
use Codemanas\Typesense\Main\TypesenseAPI;
use Codemanas\Typesense\WooCommerce\Frontend\Frontend;
use Codemanas\Typesense\WooCommerce\Main\Fields\Fields;

$args = $args ?? [];

$config = Admin::get_search_config_settings();
$tsfwc_wc_settings = Fields::get_option('global_setting');

// slug will always for product
$args['collection'] = TypesenseAPI::getInstance()->getCollectionNameFromSchema('product');

// Sort by options
$default_sorting_options = [
    'recent' => '_text_match:desc,sort_by_date:desc',
    'oldest' => '_text_match:desc,sort_by_date:asc',
    'sort_by_rating_low_to_high' => '_text_match:desc,rating:asc',
    'sort_by_rating_high_to_low' => '_text_match:desc,rating:desc',
    'sort_by_price_low_to_high' => '_text_match:desc,price:desc',
    'sort_by_price_high_to_low' => '_text_match:desc,price:desc',
    'sort_by_popularity' => '_text_match:desc,total_sales:desc',
];
$maybe_sort_by_featured = $maybe_sort_by_featured = ($args['show_featured_first'] == 'yes') ? 'is_featured:desc,' : '';
$sort_by_key = $tsfwc_wc_settings['default_sort_by'];
$sorting_initial = ! empty($default_sorting_options[$sort_by_key]) ? $default_sorting_options[$sort_by_key] : '_text_match:desc,sort_by_date:desc';
$defaultSortBy = $maybe_sort_by_featured.$sorting_initial;
$args['default_sort_by'] = apply_filters('cmtsfwc_sortby_default', $defaultSortBy);
$args['query_by'] = 'post_title,sku,barcodes';

// css class
$product_open_css_class = Frontend::getInstance()->get_woocommerce_product_loop_start_class();

// language hook for plugins like WPML
$current_lang = apply_filters('tsfwc_current_lang', null);

$additional_classes = [];
if (! empty($args['custom_class'])) {
    $custom_classes = explode(',', $args['custom_class']);
    foreach ($custom_classes as $custom_class) {
        $additional_classes[] = $custom_class;
    }
}

// current page
$current_page = (get_query_var('paged')) ? get_query_var('paged') : 1;
?>
<!-- Additonal config  -->

<?php if ($args['unique_id'] == 'ts_woo_main_search') { ?>
    <div id="ts_woo_main_search_loader" class="cmtsfwc-Results-loader w-full mb-32 pt-8 !gap-0" style="min-height:90vh; min-height:90dvh; ">

        <div class="flex flex-wrap xl:flex-nowrap w-full">
            <div class="hidden xl:block max-w-[350px] w-[calc(50%_-_6px)] xl:w-[calc(100%_-_350px)] xl:pr-8" style="padding-top:8.3rem;">
                <?php for ($i = 0; $i < 30; $i++) {
                    echo '<div class="animate-pulse bg-slate-200 w-full" style="height:25px; margin-bottom:32px;"></div>';
                }
    ?>
            </div>
            <div class="w-full xl:w-[calc(100%_-_350px)]">
                <div class="animate-pulse bg-slate-200 w-full mb-4" style="height:49px;"></div>
                <div class="animate-pulse bg-slate-200 w-full mb-4" style="height:52px;"></div>
                <ul class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-2 2xl:grid-cols-3 3xl:grid-cols-4 gap-x-1.5 gap-y-8 mb-0 ml-0 list-none">
                    <?php for ($i = 0; $i < 12; $i++) {
                        echo '<li class="animate-pulse bg-slate-200 w-full" style="aspect-ratio:331/505;"></li>';
                    }
    ?>
                </ul>
            </div>
        </div>

    </div>
<?php } ?>

<div 
    <?php
        if ($args['unique_id'] != 'ts_woo_secondary_search') {
            ?>
        x-data="{canClick:true}"
        x-on:scroll.window="
            let button = document.querySelector('.cmtsfwc-NextButton.button:not(.cmtsfwc-NextButton--disabled)');
            if(button !== undefined && button !== null){
                let rect = button.getBoundingClientRect();
                if(
                    rect.top >= 0 &&
                    rect.left >= 0 &&
                    rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                    rect.right <= (window.innerWidth || document.documentElement.clientWidth) && canClick) 
                {
                    canClick = false;
                    button.click();
                    setTimeout(() => {
                        canClick = true;
                    }, 1000);
                }
            }
        " 
    <?php
        }
?>
    <?php if ($args['unique_id'] != 'ts_woo_secondary_search') {
        echo 'id="ts_woo_secondary_search_container"';
    }?>
    class="cmtsfwc-InstantSearch ais-InstantSearch mb-32 pt-8 !gap-0 <?php echo esc_html(implode(' ', $additional_classes)); ?> <?php if ($args['unique_id'] != 'ts_woo_secondary_search') {
        echo 'hidden';
    }?>"
    data-id="<?php echo esc_html($args['unique_id']); ?>"
    data-config="<?php echo _wp_specialchars(json_encode($args), ENT_QUOTES, 'UTF-8', true); ?>"
    data-placeholder="<?php echo esc_html($args['placeholder'] ?? 'Search for...'); ?>"
    data-product_css_class="<?php echo esc_html($product_open_css_class); ?>"
    data-query_by="<?php echo esc_html($args['query_by']); ?>"
    data-query_length="<?php echo apply_filters('cm_tsfwc_search_query_length', 0); ?>"
    data-lang="<?php echo $current_lang ?>"
    data-additional_search_params="<?php echo _wp_specialchars(json_encode(apply_filters('cm_tsfwc_additional_search_params', [])), ENT_QUOTES, 'UTF-8', true); ?>"
    data-additional_config="<?php echo _wp_specialchars(json_encode(apply_filters('cm_tsfwc_additional_config', [])), ENT_QUOTES, 'UTF-8', true); ?>"
    data-current_page="<?php echo esc_attr($current_page); ?>"
    >
	<?php do_action('cm_tsfwc_instant_search_before_output', $args, $config); ?>

    <div class="cmtsfwc-InstantSearch-overlay cmtsfwc-FilterPanel-itemsClose"></div>
    
    <div class="flex flex-wrap xl:flex-nowrap w-full xl:justify-end">
        <a href="<?php echo e(home_url('/')); ?>" class="facete-logo hidden max-w-72 w-[calc(50%_-_6px)] xl:w-[calc(100%_-_18rem)] xl:pr-8 mb-4 xl:mb-0">
            <img class="relative xl:top-2" src="<?php echo ht_get_field('header_logo','options')['url'] ?? ''; ?>" alt="<?php echo get_bloginfo('name', 'display'); ?>" class="h-[26px]">
        </a>
        <div class="cmtsfwc-SearchHeader w-full xl:!w-[calc(100%_-_18rem)]">

            <?php
            /**
             * Codemanas\Typesense\WooCommerce\Main\TemplateHooks search_box - 5
             * Codemanas\Typesense\WooCommerce\Main\TemplateHooks search_stats - 10
             * Codemanas\Typesense\WooCommerce\Main\TemplateHooks search_refinements - 15
             */
            do_action( 'cm_tsfwc_instant_search_results_header', $args, $config );
            ?>

        </div>
    </div>
    <div class="flex flex-wrap xl:flex-nowrap w-full">
        <?php
        /**
         * Codemanas\Typesense\WooCommerce\Main\TemplateHooks - filter_panel - 5
         * Codemanas\Typesense\WooCommerce\Main\TemplateHooks - main_panel - 10
         */
        do_action( 'cm_tsfwc_instant_search_results_output', $args, $config );
        do_action( 'cm_tsfwc_instant_search_after_output', $args, $config ); ?>
    </div>
</div>
