<div class="bg-gray-400 relative flex h-full items-center justify-center p-10">
    <img
        fetchpriority="high"
        class="noselect mx-auto aspect-square max-h-[512px] p-10"
        src="{{ $productFeaturedImagesUrl['full'] }}"
        alt="{!! $product->get_title() !!}"
    />

    <div class="absolute top-8 right-8 z-10">
        <x-wishlist-button :product="$product" context="single-product-page" class="" />
    </div>
</div>
