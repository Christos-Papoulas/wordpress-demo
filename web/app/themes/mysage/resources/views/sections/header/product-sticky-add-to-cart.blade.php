@php
    use App\HT\Services\Product\MediaService;
    $product = wc_get_product(get_queried_object_id());
    $type = $product->get_type();

    $featured_img = MediaService::getProductMainImage($product, 'woocommerce_thumbnail');
@endphp
<div x-data="{scrolled:false}" 

    x-on:scroll.window="
        if(window.pageYOffset >= 700){
            scrolled = true;
        }else{
            scrolled = false;
        }
    "
    x-cloak x-show="scrolled" class="ht-container-no-max-width text-body bg-white hidden lg:flex items-center justify-between py-2.5 border-t border-b-[3px] border-b-[#212121] border-t-[#E6E6E6] overflow-hidden">
    <div class="font-semibold text-[20px] leading-[20px] overflow-hidden flex gap-11 items-center" style="max-width:630px; max-height:62px;">
        <img id="js_sticky_add_to_cart_product_img" src="{{ $featured_img }}" alt="featured image" class="h-[70px]" />
        <span class="font-bold md:font-normal uppercase text-xs lg:text-2xl xl:text-3xl">{!! $product->get_title() !!}</span>
    </div>
    
    <div class="flex items-center gap-11">
        <div id="js_sticky_add_to_cart_product_price" class="text-body font-bold md:font-normal uppercase text-xs lg:text-2xl xl:text-3xl">
            {!! wc_price($product->get_price()) !!}
        </div>
        <button 
            x-on:click="
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    })
            " 
            type="button" class="btn-md btn-solid-primary">
            {{ __('ADD TO CART','sage') }}
        </button>
    </div>
</div>
