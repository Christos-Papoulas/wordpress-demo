<?php
/*
 * tmpl-cm-tsfwc-shortcode-[post-type-slug]-search-results
 * for different templates for different post types add the post type slug instead of [post-type-slug] as the id
 * example tmpl-cm-tsfwc-shortcode-page-search-results or tmpl-cm-tsfwc-shortcode-book-search-results
 */
use App\HT\Services\Wishlist;
?>
<script type="text/html" id="tmpl-cmtsfwc-HitsTemplate">
    <#
        const productCardDataString = btoa(unescape(encodeURIComponent(JSON.stringify(data.productCardData))));
    #>
    <div
    x-data="productCard({productCardData: JSON.parse(decodeURIComponent(escape(atob('{{{ productCardDataString }}}'))))})"
    class="product-card group">
        <div class="flex aspect-[800/1000] overflow-hidden relative bg-[#f4f2ee]">
            <a href="{{{data._highlightResult.permalink.value}}}" title="" class="flex aspect-[800/1000] overflow-hidden relative bg-[#f4f2ee]">
                <# if(data.video){ #>
                    <div class="bg-[#f4f2ee] flex absolute inset-0 opacity-100 group-hover:opacity-0 transition-opacity duration-500 ease-in-out">
                        <img src="{{{ data.productCardData.image_src.woocommerce_single }}}" alt="" class="maybeAddMixBlend object-contain w-full">
                    </div>
                    <video class="absolute left-0 top-0 w-full h-full object-contain opacity-0 group-hover:opacity-100 transition-opacity duration-500 ease-in-out" preload="metadata" playsinline="playsinline" loop="loop" muted="muted" autoplay>
                        <source src="{{{ data.video }}}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                <# }else{ #>
                    <div class="bg-[#f4f2ee] flex absolute inset-0 opacity-100 <# if(data.productCardData.image_src_2.woocommerce_single != ''){ #> group-hover:opacity-0 <# } #> transition-opacity duration-500 ease-in-out">
                        <img src="{{{ data.productCardData.image_src.woocommerce_single }}}" alt="" class="maybeAddMixBlend object-contain w-full">
                    </div>
                    <# if(data.productCardData.image_src_2.woocommerce_single != ''){  #>
                        <div class="bg-[#f4f2ee] flex absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500 ease-in-out">
                            <img src="{{{ data.productCardData.image_src_2.woocommerce_single }}}" alt="" class="maybeAddMixBlend object-contain w-full">
                        </div>
                    <# } #>
                <# } #>

                <div class="absolute right-1 md:right-2 top-1 md:top-2 flex gap-1 md:gap-2">
                    <# if(data.productCardData.last_units){ #>
                        <div class="bg-white text-[8px] md:text-[10px] leading-3 font-medium text-body p-1 md:p-2 shadow-sm">
                            <?php echo __('LAST UNITS','sage'); ?>
                        </div>
                    <# } #>
                    <# if(data.productCardData.cross_sells.length > 0){ #>
                    <div class="bg-white text-[8px] md:text-[10px] leading-3 font-medium text-body p-1 md:p-2 shadow-sm">
                        <?php echo __('PAIR OPTION AVAILABLE','sage'); ?>
                    </div>
                    <# } #>
                </div>
            </a>
            
            <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-500 ease-in-out flex flex-col z-10 overflow-hidden absolute left-0 bottom-0  w-full bg-white">
                <template x-if="productCardData.hasOwnProperty('attributes_and_options') && productCardData.attributes_and_options.hasOwnProperty(0)">
                    <div class="flex flex-col gap-2 px-2 py-2 border-t border-[#e5e7eb]">
                        <template x-for="attr in productCardData.attributes_and_options" :key="attr.id">
                            <div class="flex flex-wrap items-center gap-2">
                                <template x-for="term in attr.options">
                                    
                                    <div>
                                        <template x-if="attr.display_type === 'color'">
                                            <button type="button" 
                                                x-on:click="changeSelectedOptionOfAttribute(attr.name, term.slug, attr.display_type)"
                                                class="w-5 h-5 rounded-full border border-[#e5e7eb]"
                                                :class="selectedOptions.hasOwnProperty(attr.name) && selectedOptions[attr.name] == term.slug ? 'border-black' : ''"
                                                :style="`background:${term.background};`" style="background-size:100%; background-repeat:no-repeat; background-position:center;"
                                            >    
                                            </button>
                                        </template>

                                        <template x-if="attr.display_type !== 'color'">
                                            <button type="button" 
                                                x-on:click="changeSelectedOptionOfAttribute(attr.name, term.slug, attr.display_type)"
                                                class="px-1 h-5 border border-[#e5e7eb]"
                                                :class="selectedOptions.hasOwnProperty(attr.name) && selectedOptions[attr.name] == term.slug ? 'border-black' : ''"
                                                x-text="term.name"
                                            >    
                                            </button>
                                        </template>
                                    </div>

                                </template>
                            </div>
                        </template>
                    </div>
                </template>

                <button x-on:click.prevent="addOneToCart()" type="button" name="add-to-cart" value="{{{ data.post_id }}}" 
                    :class="showButtons ? 'cursor-default':'cursor-pointer'"
                    class="w-full btn-md btn-solid-primary relative">
                    <div class="flex items-center justify-center">
                        <span x-cloak x-show="!loading && !showButtons" x-text="addToCartText" x-ref="addToCartBtnText" x-text="addToCartText" data-addtext="<?php echo __('ADD TO CART','htech' ); ?>" data-outofstock="<?php echo __('OUT OF STOCK','htech' ); ?>" data-choosetext="<?php echo __('CHOOSE A VARIATION','htech' ); ?>"></span>
                        <span x-cloak x-show="!loading && showButtons" x-text="qty"></span>
                        <svg x-show="loading" class="w-4 h-4 text-white pointer-events-none animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <div x-on:click.prevent.stop="decreaseQty()" x-cloak x-show="!loading && showButtons" x-ref="qty_decrease" :class="decreaseDisabled && '!cursor-not-allowed'" class="cursor-pointer z-10 flex items-center absolute top-1.5 left-1.5 h-[calc(100%_-_.75rem)] transition text-white w-10 bg-[rgba(255,255,255,0.25)] hover:bg-[rgba(255,255,255,0.35)]">
                        <svg viewBox="0 0 448 512" class="w-2 h-8 lg:w-3 lg:h-3 mx-auto">
                            <path
                                d="M432 256c0 17.7-14.3 32-32 32L48 288c-17.7 0-32-14.3-32-32s14.3-32 32-32l352 0c17.7 0 32 14.3 32 32z"
                                fill="currentColor">
                            </path>
                        </svg>
                    </div>
                    <div x-on:click.prevent.stop="increaseQty()" x-cloak x-show="!loading && showButtons" x-ref="qty_increase" :class="increaseDisabled && '!cursor-not-allowed !bg-red-600'" class="cursor-pointer z-10 flex items-center absolute top-1.5 right-1.5 h-[calc(100%_-_.75rem)] transition text-white w-10 bg-[rgba(255,255,255,0.25)] hover:bg-[rgba(255,255,255,0.35)]">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="w-2 h-8 lg:w-3 lg:h-3  mx-auto">
                            <path
                                d="M256 80c0-17.7-14.3-32-32-32s-32 14.3-32 32V224H48c-17.7 0-32 14.3-32 32s14.3 32 32 32H192V432c0 17.7 14.3 32 32 32s32-14.3 32-32V288H400c17.7 0 32-14.3 32-32s-14.3-32-32-32H256V80z"
                                fill="currentColor">
                            </path>
                        </svg>
                    </div>
                </button>       
            </div>       
        </div>
        <div class="pt-2 md:pt-4 px-2 md:px-[18px]">
            <div class="flex justify-between gap-x-5">
                <a href="{{{data._highlightResult.permalink.value}}}" title="{{{data._highlightResult.permalink.value}}}" class="block">
                    <h3 class="text-[10px]/3 md:text-xs/4 uppercase text-body font-bold mb-0">
                        {{{data.formatted.post_title}}}
                    </h3>
                </a>
                <div class=""> 
                        <?php
                            $wishlist = esc_js(base64_encode(json_encode( app(Wishlist::class) )));
                            // get wishlist and define inWishlist and inWhichLists like in the component
                        ?>
                        <# 
                            let inWishlist = false;
                            let myWishlist = JSON.parse( atob('<?php echo $wishlist; ?>') );
                            if (myWishlist.list.includes(Number(data.post_id))) {
                                inWishlist = true;
                            }
                        #>
                    <button 
                        x-data="buttonAddToWishlist({
                            product_id : {{{ data.post_id }}},
                            myWishlist: JSON.parse( atob('<?php echo $wishlist; ?>') ),
                            inWishlist : {{{ inWishlist }}},
                        })"
                        :class="inWishlist && 'in-my-wishlist'"
                        x-on:click.prevent="addOrRemove()"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14.19" viewBox="0 0 16 14.19">
                            <path d="M14.37 1.63a3.88 3.88 0 00-5.48 0L8 2.52l-.89-.89a3.87 3.87 0 00-5.48 5.48l.89.89L8 13.48 13.48 8l.89-.89a3.88 3.88 0 000-5.48z" fill="none" stroke="currentColor" stroke-miterlimit="10"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <div class="text-[10px]/3 md:text-xs/4 text-body hit-cats">
               {{{ data.cat_links_html }}}
            </div>
            <p class="mb-0 text-[10px]/3 md:text-xs/4 text-body">
                {{{data.productCardData.price_html}}}
                <# if(data.productCardData.is_on_sale){ #> <span class="ml-2 line-through text-red-600">{{{data.productCardData.regular_price_html}}}</span>  <# } #>
            </p>
        </div>
        
    </div>
</script>
