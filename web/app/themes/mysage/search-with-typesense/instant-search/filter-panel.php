
<?php
$facets = $args['facet'] ?? [];
$config = $args['config'] ?? [];
$schema = $args['schema'] ?? [];

$passed_args = $args['passed_args'] ?? [];

// Removed a logic to check multiple posttypes here so multi faceting can work
if ($passed_args['filter'] === 'show' && ! empty($facets)) { ?>
    <div 
    x-data="{open:false}"
    x-init="
        $watch('open', () => {
            if(!open){
                document.body.classList.remove('body-no-scroll');
                document.getElementById('backdrop').classList.add('hidden');
            }else{
                document.body.classList.add('body-no-scroll');
                document.getElementById('backdrop').classList.remove('hidden');
            }
        });
        document.addEventListener('closeAllModals', (event) => {
            open=false;
        });
    "
    class="cmswt-FilterPanel max-w-[350px] block w-0 xl:w-[calc(100%_-_350px)] xl:pr-8 !pt-0">

        <div class="hidden xl:flex justify-between items-center gap-2 w-full border-b-[1.5px] border-primary pb-5">
            <h3 class="text-lg mb-0"><?php _e('Find what you are looking for', 'sage'); ?></h3>
            <span>
                <svg width="20" height="19" viewBox="0 0 20 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                <mask id="path-1-inside-1_4728_24052" fill="white">
                <path d="M11 2.50391H20V4.50391H11V2.50391ZM0 4.50391H7V6.50391H9V0.503906H7V2.50391H0V4.50391ZM7 14.5039H20V16.5039H7V14.5039ZM17 8.50391H20V10.5039H17V8.50391ZM15 12.5039V6.51591H13V8.50391H0V10.5039H13V12.5039H15ZM5 18.5039V12.5039H3V14.5039H0V16.5039H3V18.5039H5Z"/>
                </mask>
                <path d="M11 2.50391H20V4.50391H11V2.50391ZM0 4.50391H7V6.50391H9V0.503906H7V2.50391H0V4.50391ZM7 14.5039H20V16.5039H7V14.5039ZM17 8.50391H20V10.5039H17V8.50391ZM15 12.5039V6.51591H13V8.50391H0V10.5039H13V12.5039H15ZM5 18.5039V12.5039H3V14.5039H0V16.5039H3V18.5039H5Z" fill="#1D1D1F"/>
                <path d="M11 2.50391V0.503906H9V2.50391H11ZM20 2.50391H22V0.503906H20V2.50391ZM20 4.50391V6.50391H22V4.50391H20ZM11 4.50391H9V6.50391H11V4.50391ZM0 4.50391H-2V6.50391H0V4.50391ZM7 4.50391H9V2.50391H7V4.50391ZM7 6.50391H5V8.50391H7V6.50391ZM9 6.50391V8.50391H11V6.50391H9ZM9 0.503906H11V-1.49609H9V0.503906ZM7 0.503906V-1.49609H5V0.503906H7ZM7 2.50391V4.50391H9V2.50391H7ZM0 2.50391V0.503906H-2V2.50391H0ZM7 14.5039V12.5039H5V14.5039H7ZM20 14.5039H22V12.5039H20V14.5039ZM20 16.5039V18.5039H22V16.5039H20ZM7 16.5039H5V18.5039H7V16.5039ZM17 8.50391V6.50391H15V8.50391H17ZM20 8.50391H22V6.50391H20V8.50391ZM20 10.5039V12.5039H22V10.5039H20ZM17 10.5039H15V12.5039H17V10.5039ZM15 12.5039V14.5039H17V12.5039H15ZM15 6.51591H17V4.51591H15V6.51591ZM13 6.51591V4.51591H11V6.51591H13ZM13 8.50391V10.5039H15V8.50391H13ZM0 8.50391V6.50391H-2V8.50391H0ZM0 10.5039H-2V12.5039H0V10.5039ZM13 10.5039H15V8.50391H13V10.5039ZM13 12.5039H11V14.5039H13V12.5039ZM5 18.5039V20.5039H7V18.5039H5ZM5 12.5039H7V10.5039H5V12.5039ZM3 12.5039V10.5039H1V12.5039H3ZM3 14.5039V16.5039H5V14.5039H3ZM0 14.5039V12.5039H-2V14.5039H0ZM0 16.5039H-2V18.5039H0V16.5039ZM3 16.5039H5V14.5039H3V16.5039ZM3 18.5039H1V20.5039H3V18.5039ZM11 4.50391H20V0.503906H11V4.50391ZM18 2.50391V4.50391H22V2.50391H18ZM20 2.50391H11V6.50391H20V2.50391ZM13 4.50391V2.50391H9V4.50391H13ZM0 6.50391H7V2.50391H0V6.50391ZM5 4.50391V6.50391H9V4.50391H5ZM7 8.50391H9V4.50391H7V8.50391ZM11 6.50391V0.503906H7V6.50391H11ZM9 -1.49609H7V2.50391H9V-1.49609ZM5 0.503906V2.50391H9V0.503906H5ZM7 0.503906H0V4.50391H7V0.503906ZM-2 2.50391V4.50391H2V2.50391H-2ZM7 16.5039H20V12.5039H7V16.5039ZM18 14.5039V16.5039H22V14.5039H18ZM20 14.5039H7V18.5039H20V14.5039ZM9 16.5039V14.5039H5V16.5039H9ZM17 10.5039H20V6.50391H17V10.5039ZM18 8.50391V10.5039H22V8.50391H18ZM20 8.50391H17V12.5039H20V8.50391ZM19 10.5039V8.50391H15V10.5039H19ZM17 12.5039V6.51591H13V12.5039H17ZM15 4.51591H13V8.51591H15V4.51591ZM11 6.51591V8.50391H15V6.51591H11ZM13 6.50391H0V10.5039H13V6.50391ZM-2 8.50391V10.5039H2V8.50391H-2ZM0 12.5039H13V8.50391H0V12.5039ZM11 10.5039V12.5039H15V10.5039H11ZM13 14.5039H15V10.5039H13V14.5039ZM7 18.5039V12.5039H3V18.5039H7ZM5 10.5039H3V14.5039H5V10.5039ZM1 12.5039V14.5039H5V12.5039H1ZM3 12.5039H0V16.5039H3V12.5039ZM-2 14.5039V16.5039H2V14.5039H-2ZM0 18.5039H3V14.5039H0V18.5039ZM1 16.5039V18.5039H5V16.5039H1ZM3 20.5039H5V16.5039H3V20.5039Z" fill="#1D1D1F" mask="url(#path-1-inside-1_4728_24052)"/>
                </svg>
            </span>
        </div>
        <div
            x-on:click="open = !open"
            class="cmswt-FilterToggle fixed top-[20%] -right-3 transform -translate-x-[50%] bg-black text-white flex gap-1 items-center justify-center z-40 rounded-full w-12 h-12 xl:hidden">
            <svg width="21" height="18" viewBox="0 0 21 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M11.4673 2.00391H20.1673V4.00391H11.4673V2.00391ZM0.833984 4.00391H7.60065V6.00391H9.53399V0.00390625H7.60065V2.00391H0.833984V4.00391ZM7.60065 14.0039H20.1673V16.0039H7.60065V14.0039ZM17.2673 8.00391H20.1673V10.0039H17.2673V8.00391ZM15.334 12.0039V6.01591H13.4007V8.00391H0.833984V10.0039H13.4007V12.0039H15.334ZM5.66732 18.0039V12.0039H3.73398V14.0039H0.833984V16.0039H3.73398V18.0039H5.66732Z" fill="white"/>
            </svg>
        </div>
        <div :class="open && 'cmswt-FilterPanel-items--show'" class="cmswt-FilterPanel-items">
            <div class="cmswt-Filter-itemsHeader">
                <h3><?php _e('Filter Search Results', 'typesense-search-for-woocommerce'); ?></h3>
                <svg x-on:click="open = false" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 cmswt-Filter-itemsHeaderCloseIcon cmswt-Filter-itemsClose" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
            <div class="cmswt-FilterPanel-itemsPopupHeader">
                <div class="cmswt-FilterPanel-itemsPopupLabel">
                    <h5 class="cmswt-FilterPanel-itemsPopupLabelHeader"><?php _e('Apply Filters', 'search-with-typesense'); ?></h5>
                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6 cmswt-FilterPanel-itemsPopupHeaderCloseLogo cmswt-FilterPanel-itemsClose"
                         width="16" height="17" viewBox="0 0 16 17" fill="none">
                        <path d="M11.3334 5.16666L4.66675 11.8333M4.66675 5.16666L11.3334 11.8333" stroke="#2E2E2E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </div>
            <div class="cmswt-FilterPanel-itemsContent">
				<?php
                do_action('cm_typesense_instant_search_results_before_filter_panel', $facets);
    foreach ($facets as $post_type => $filters) {
        foreach ($filters as $filter) {
            $filter_label = $filter;
            if ($filter_label == 'post_author') {
                $filter_label = __('Author', 'search-with-typesense');
            }
            if ($filter_label == 'meal_type') {
                $filter_label = __('Meal Type', 'search-with-typesense');
            }
            if ($filter_label == 'occasion') {
                $filter_label = __('Occasion', 'search-with-typesense');
            }
            if ($filter_label == 'diet_type') {
                $filter_label = __('Diet Type', 'search-with-typesense');
            }
            ?>
                        <div class="cmswt-Filter cmswt-Filter-<?php echo esc_html($filter); ?> cmswt-Filter-collection_<?php echo $post_type; ?>"
                             data-facet_id="<?php echo esc_attr($post_type.'_'.$filter); ?>"
                             data-label="<?php echo esc_attr(apply_filters('cm_typesense_search_facet_label', $filter_label, $filter, $post_type)); ?>"
                             data-title="<?php esc_html_e(
                                 apply_filters(
                                     'cm_typesense_search_facet_title',
                                     sprintf('%s', esc_html(ucwords($filter_label))),
                                     $filter, $post_type),
                                 'search-with-typesense'); ?>"
                             data-settings="<?php echo _wp_specialchars(json_encode(apply_filters('cm_typesense_search_facet_settings', [
                                 'searchable' => false,
                             ], $filter, $post_type)), ENT_QUOTES, 'UTF-8', true); ?>"
                             data-filter_type="<?php echo apply_filters('cm_typesense_filter_type', 'refinement', $filter, $post_type) ?>"
                        ></div>
					<?php }
        }
    do_action('cm_typesense_instant_search_results_after_filter_panel', $facets);
    ?>
            </div>
            <div x-on:click="open=false" class="cmswt-FilterPanel-itemsFooter">
                <a class="cmswt-FilterPanel-itemsFooterCloseLink cmswt-FilterPanel-itemsClose"><?php _e('Close', 'typesense-search-for-woocommerce'); ?></a>
            </div>
        </div>
    </div>
<?php }
