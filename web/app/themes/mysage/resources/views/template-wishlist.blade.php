{{-- 
  Template Name: Wishlist Template 
--}}

@extends('layouts.app')

@section('content')
@while(have_posts()) @php(the_post())
   
    @include('partials.page-header')

    <section 
        x-data="wishlist({
            myWishlist: JSON.parse( atob('{{ base64_encode(json_encode($wishlist)) }}') ),
            mobiscrollMessagesConfirmTitle : '{{ __('Delete Wishlist?','sage') }}',
            mobiscrollMessagesConfirmMessage : '{{ __('All products will be removed from this wishlist.','sage') }}',
            mobiscrollMessagesConfirmOkText : '{{ __('Agree','sage') }}',
            mobiscrollMessagesConfirmCancelText : '{{ __('Disagree','sage') }}'
        })" 
        class="pb-8">
    
        <div class="ht-container-large mt-5 relative" style="min-height: 480px;">

            <div x-cloak x-show="!loading && count <= 0" class="ht-container-large flex flex-col text-center items-center justify-center" style="min-height: 480px;">

                <template x-if="items.length === 0">
                    <div class="col-span-full text-center text-xl p-4 rounded-lg">
                        {{ __('Please add an item to your wishlist.','sage') }}
                    </div>
                </template>
                
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 2xl:grid-cols-6 gap-4">
                
                <template x-for="product in items" :key="product.product_id">
                    <x-product-card-alpinejs />
                </template>
            </div>

            <div x-show="loading" class="left-0 top-0 w-full h-full absolute opacity-60 bg-white z-10 flex items-center justify-center">
                <svg class="z-20 animate-spin h-10 w-10 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="#000" stroke-width="4"></circle>
                    <path class="opacity-75" fill="#000" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
  
        </div>

        {{-- buttons --}}
        <div class="mt-8 pb-8">
            <div class="ht-container-large grid grid-cols-1 gap-y-4 lg:gap-y-0 lg:gap-4 text-xs lg:text-base ">

                <a href="{{ wc_get_page_permalink('shop') }}" class="order-2 lg:order-1 btn-md btn-solid-primary uppercase">
                    {{ __('BACK TO STORE','sage') }}
                </a>
                
                {{-- TODO: check if everything is simple product --}}
                {{-- <button x-cloak x-show="count > 0" x-on:click="addAllToCart()" :disabled="loading" class="order-1 lg:order-2 btn-md btn-solid-primary">{{ __('Add everything to cart','sage') }}</button> --}}

            </div>
        </div>

    </section>

    @include('partials.content-page')

@endwhile
@endsection
