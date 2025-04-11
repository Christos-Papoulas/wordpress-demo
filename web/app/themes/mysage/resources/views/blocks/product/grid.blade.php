<section class="@if(isset($block->classes)){{ $block->classes }}@endif pb-12 xl:pb-20">
    <div class="grid w-full grid-cols-1 gap-2.5 md:grid-cols-2 xl:grid-cols-4">
        @foreach ($products as $product)
            <x-product-card :product="$product" />
        @endforeach
    </div>
</section>
