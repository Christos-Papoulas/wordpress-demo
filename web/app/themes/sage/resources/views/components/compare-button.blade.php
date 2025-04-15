<button
    x-data="buttonAddToCompareList"
    @php
        $inCompareList = in_array($product->get_id(), $myCompareList);
    @endphp
    x-on:click.prevent="add(event.currentTarget)"
    type="button"
    class="{{ $class }} group @if ($inCompareList)  in-my-wishlist @endif flex items-center gap-5 text-black"
    data-pid="{{ $product->get_id() }}"
>
    <div class="transition group-hover:text-red-600">{{ __('Compare', 'sage') }}</div>
    <svg
        class="transition group-hover:text-red-600"
        width="45"
        height="34"
        viewBox="0 0 45 34"
        fill="none"
        xmlns="http://www.w3.org/2000/svg"
    >
        <path
            d="M33.1914 12L43.0002 21.956L33.1914 32.0636"
            stroke="currentColor"
            stroke-width="1"
            stroke-miterlimit="10"
            stroke-linecap="square"
        ></path>
        <path
            d="M42.5648 22.0791H23"
            stroke="currentColor"
            stroke-width="1"
            stroke-miterlimit="10"
            stroke-linecap="square"
        ></path>
        <path
            d="M11.8086 22.0635L1.9998 12.1075L11.8086 1.99989"
            stroke="currentColor"
            stroke-width="1"
            stroke-miterlimit="10"
            stroke-linecap="square"
        ></path>
        <path
            d="M2.43521 11.9844L22 11.9844"
            stroke="currentColor"
            stroke-width="1"
            stroke-miterlimit="10"
            stroke-linecap="square"
        ></path>
    </svg>
</button>
