{{--

/**
 * Pagination - Show numbered pagination for catalog pages.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/pagination.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 *
 * @version 3.3.1
 */
 --}}
@php
if (!defined('ABSPATH')) {
    exit;
}

$total = isset($total) ? $total : wc_get_loop_prop('total_pages');
$current = isset($current) ? $current : wc_get_loop_prop('current_page');
$base = isset($base) ? $base : esc_url_raw(str_replace(999999999, '%#%', remove_query_arg('add-to-cart', get_pagenum_link(999999999, false))));
$format = isset($format) ? $format : '';

if ($total <= 1) {
    return;
}

$pagination = paginate_links(apply_filters('woocommerce_pagination_args', [ // WPCS: XSS ok.
    'base' => $base,
    'format' => $format,
    'add_args' => false,
    'current' => max(1, $current),
    'total' => $total,
    'prev_text' => '&larr;',
    'next_text' => '&rarr;',
    'type' => 'array',
    'end_size' => 3,
]));
@endphp
<div id="shop_pagination" class="content__row text-center col-span-full mt-6">
    <div class="md:flex-1 md:flex md:items-center md:justify-between w-full">
        <nav class="woocommerce-pagination flex justify-center content-center w-full" s>
            @php
            //removed CSS class woocommerce-pagination
            $paginate_links = paginate_links(
                apply_filters(
                    'woocommerce_pagination_args',
                    [ // WPCS: XSS ok.
                        'base' => $base,
                        'format' => $format,
                        'add_args' => false,
                        'current' => max(1, $current),
                        'total' => $total,
                        'prev_text' => is_rtl() ? '&rarr;' : '&larr;',
                        'next_text' => is_rtl() ? '&larr;' : '&rarr;',
                        'type' => 'array',
                        'end_size' => 3,
                        'mid_size' => 3,
                    ]
                )
            );
            if (is_array($paginate_links)) { 
				@endphp
                <div class="flex justify-between xl:hidden">
                    <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Previous</a>
                    <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Next</a>
                </div>
                <ul class="hidden pagination relative z-0 inline-flex rounded-md shadow-sm -space-x-px lg:inline-flex list-none mb-0 ml-0">
					@php foreach ($paginate_links as $paginate_link) { @endphp
                        <li class="page-item bg-white border-black text-black hover:bg-gray-300 relative inline-flex items-center border text-sm font-medium">
                            @php
                            echo wp_kses_post($paginate_link);
                            @endphp
                        </li>
                    @php } @endphp
                </ul>
				@php } @endphp
        </nav>
    </div>

</div>