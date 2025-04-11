<div x-data="productGallery" id="single-product-gallery" data-pid="{{ $product->get_ID() }}" class="overflow-hidden">

    <div x-ref="desktopImagesContainer" id="desktop-images-container" class="hidden md:grid grid-cols-2 gap-2.5 relative invisible">

        <div class="w-full h-full flex overflow-hidden bg-[#f4f2ee]">

            <!-- Main zoom image -->
            <a class="MagicZoom w-full flex aspect-[800/1000] bg-[#f4f2ee] object-contain"  title="{!! $product->get_title() !!}" href="{{ $productFeaturedImagesUrl['full'] }}"
            data-gallery="product-gallery" 
            data-options="variableZoom: true; transitionEffect: false; zoomOn: click; zoomPosition: inner; expand: off" 
            >
                <img class="maybeAddMixBlend" src="{{ $productFeaturedImagesUrl['full'] }}" alt="{!! $product->get_title() !!}"/>
            </a>

        </div>

        @if(!empty($video))
        <div class="w-full aspect-[800/1000] bg-[#f4f2ee] relative overflow-hidden">
            <video class="absolute left-0 top-0 w-full h-full object-contain" preload="metadata" playsinline="playsinline" loop="loop" muted="muted" autoplay>
                <source src="{{ $video }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
        @endif
       
        @if(!empty($productGalleryUrls))
        @foreach ($productGalleryUrls as $imgUrl)
            <div class="">
                <a class="MagicZoom w-full flex aspect-[800/1000] bg-[#f4f2ee] object-contain"  title="{!! $product->get_title() !!}" href="{{ $imgUrl['full'] }}"
                    data-gallery="product-gallery" 
                    data-options="variableZoom: true; transitionEffect: false; zoomOn: click; zoomPosition: inner; expand: off" 
                    >
                        <img class="maybeAddMixBlend" src="{{ $imgUrl['full'] }}" alt="{!! $product->get_title() !!}"/>
                        {{-- <img class="maybeAddMixBlend" src="{{ $imgUrl['woocommerce_gallery_thumbnail'] }}" alt="{!! $product->get_title() !!}"/> --}}
                </a>
            </div>
        @endforeach
        @endif

    </div>

    <div id="gallery-mobile-placeholder" class="md:hidden">
        <div class="w-full flex aspect-[800/1000] bg-[#f4f2ee] justify-center items-center"  title="{!! $product->get_title() !!}">
            <img class="maybeAddMixBlend object-contain w-full h-full" src="{{ $productFeaturedImagesUrl['full'] }}" alt="{!! $product->get_title() !!}"/>
        </div>
    </div>

    <div x-ref="gallerymobile" class="md:hidden">
        <swiper-container init="false">
            <swiper-slide class="h-auto hidden">
                <!-- Main zoom image -->
                <a class="MagicZoom w-full flex aspect-[800/1000] bg-[#f4f2ee] object-contain"  title="{!! $product->get_title() !!}" href="{{ $productFeaturedImagesUrl['full'] }}"
                    data-gallery="product-gallery-mobile" 
                    data-options="variableZoom: true; transitionEffect: false; zoomOn: click; zoomPosition: inner; expand: off" 
                    >
                        <img class="maybeAddMixBlend" src="{{ $productFeaturedImagesUrl['full'] }}" alt="{!! $product->get_title() !!}"/>
                </a>
            </swiper-slide>

            @if(!empty($video))
            <swiper-slide class="h-auto hidden">
                <div class="w-full aspect-[800/1000] bg-[#f4f2ee] relative">
                    <video class="absolute left-0 top-0 w-full h-full object-contain" preload="metadata" playsinline="playsinline" loop="loop" muted="muted" autoplay>
                        <source src="{{ $video }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </swiper-slide>
            @endif
            
            @if(!empty($productGalleryUrls))
            @foreach ($productGalleryUrls as $imgUrl)
                <swiper-slide class="h-auto hidden">
                    <a class="MagicZoom w-full flex aspect-[800/1000] bg-[#f4f2ee] object-contain"  title="{!! $product->get_title() !!}" href="{{ $imgUrl['full'] }}"
                        data-gallery="product-gallery-mobile" 
                        data-options="variableZoom: true; transitionEffect: false; zoomOn: click; zoomPosition: inner; expand: off" 
                        >
                            <img class="maybeAddMixBlend" src="{{ $imgUrl['full'] }}" alt="{!! $product->get_title() !!}"/>
                            {{-- <img class="maybeAddMixBlend" src="{{ $imgUrl['woocommerce_gallery_thumbnail'] }}" alt="{!! $product->get_title() !!}"/> --}}
                    </a>
                </swiper-slide>
            @endforeach
            @endif
        </swiper-container>

        <div class="swiper-pagination"></div>

    </div>

</div>
<script>
    var mzOptions = {};
    mzOptions = {
        onZoomReady: function() {
            //console.log('onReady', arguments[0]);
            document.getElementById('desktop-images-container').classList.remove('invisible');
        },
    }
</script>
