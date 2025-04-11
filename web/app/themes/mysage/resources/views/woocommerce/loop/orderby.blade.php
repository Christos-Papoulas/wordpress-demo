{{--
/**
 * Show options for ordering
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/orderby.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.6.0
--}}
@php
if (!defined('ABSPATH')) {
    exit();
}

@endphp
    <div class="relative xl:ms-0 z-10 w-full xl:w-auto min-w-[220px] my-1">
        <form class="woocommerce-ordering" method="get" onsubmit="event.preventDefault();">
			
            <select name="orderby" class="orderby xl:border xl:border-gray-300 text-black text-[13px] lg:text-sm font-semibold relative w-full py-2 ps-3 pe-10 text-center bg-white shadow-md focus:outline-none md:text-sm cursor-pointer" aria-label="@php esc_attr_e('Shop order', 'woocommerce'); @endphp">
                @php foreach ( $catalog_orderby_options as $id => $name ) : @endphp
                <option value="@php echo esc_attr($id); @endphp" @php selected($orderby, $id); @endphp>@php echo esc_html($name); @endphp</option>
                @php endforeach; @endphp
            </select>
			<span
                class="absolute inset-y-0 end-0 flex items-center pr-1 mr-1 my-1 bg-white pointer-events-none"><svg stroke="currentColor"
                    fill="none" stroke-width="0" viewBox="0 0 24 24" class="w-5 h-5 text-gray-400" aria-hidden="true"
                    height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4">
                    </path>
                </svg></span>
            <input type="hidden" name="paged" value="1" />
            @php wc_query_string_form_fields(null, ['orderby', 'submit', 'paged', 'product-page']); @endphp
        </form>
    </div>

