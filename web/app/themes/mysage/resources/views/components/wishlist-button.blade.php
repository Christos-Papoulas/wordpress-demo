<button 
    type="button"
    x-data="buttonAddToWishlist({
        product_id : {{ $product->get_id() }},
        myWishlist: JSON.parse( atob('{{ base64_encode(json_encode($wishlist)) }}') ),
        inWishlist : {{ $inWishlist ? 'true' : 'false' }},
    })"
    :class="inWishlist && 'in-my-wishlist'"
    class="{{ $class }}"
    x-on:click.prevent="addOrRemove()"
>
    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-auto" width="16" height="14.19" viewBox="0 0 16 14.19">
        <path d="M14.37 1.63a3.88 3.88 0 00-5.48 0L8 2.52l-.89-.89a3.87 3.87 0 00-5.48 5.48l.89.89L8 13.48 13.48 8l.89-.89a3.88 3.88 0 000-5.48z" fill="none" stroke="currentColor" stroke-miterlimit="10"></path>
    </svg>
    @if($context === 'single-product-page')
        <span class="text-body text-[10px]/3 md:text-xs/4 uppercase" x-text="inWishlist ? ' {{ __('REMOVE FROM WISHLIST','sage') }}' : '{{ __('ADD TO WISHLIST','sage') }}'">
        </span>
    @endif
</button>
