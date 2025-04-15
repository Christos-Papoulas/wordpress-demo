<?php
/**
 * Shop breadcrumb
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/breadcrumb.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     2.3.0
 * @see         woocommerce_breadcrumb()
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
@if(!empty( $breadcrumb ))
<nav class="flex py-3 md:py-5" aria-label="Breadcrumb">
	<ol class="list-none mb-0 ml-0 inline-flex flex-wrap items-center text-[10px]/3 md:text-xs/4 font-medium md:font-normal text-body uppercase">
		@foreach ($breadcrumb as $key => $crumb)

			@if(! empty( $crumb[1] ) && sizeof( $breadcrumb ) !== $key + 1)
				<li class="mb-1 inline-flex items-center">
					<a href="{{ esc_url( $crumb[1] ) }}" class="text-slate-500">
						{!! esc_html( $crumb[0] ) !!}
					</a>
					@if ( sizeof( $breadcrumb ) !== $key + 1 )
						<span class="mx-1">/</span> 
					@endif
				</li>
			@else
				<li aria-current="page" class="mb-1 flex items-center">
					<div class="flex items-center">
						<span class="">{!! esc_html( $crumb[0] ) !!}</span>
					</div>
					@if ( sizeof( $breadcrumb ) !== $key + 1 )
						<span class="mx-1">/</span> 
					@endif
				</li>
			@endif

		@endforeach
	</ol>
</nav>
@endif
