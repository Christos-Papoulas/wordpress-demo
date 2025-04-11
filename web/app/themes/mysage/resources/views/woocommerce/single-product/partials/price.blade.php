@if(!empty($product_price) && $product_price > 0)
    <div class="flex font-bold md:font-normal uppercase text-xs lg:text-2xl xl:text-3xl price {{ esc_attr( apply_filters( 'woocommerce_product_price_class', 'price' ) ) }}">
        <div id="js_single_product_price">{!! wc_price($product_price) !!}</div>
        <div id="js_single_product_sale_price" class="ml-2 line-through text-red-600">
            @if($product_on_sale && $product_price != $product_regular_price)
                {!!  wc_price($product_regular_price) !!}
            @endif
        </div>
    </div>
@endif
