<div
x-data="productCard({ productCardData: product })"
class="product-card group"
>
    <div class="flex aspect-[800/1000] overflow-hidden relative bg-[#f4f2ee]">
        <a :href="product.permalink" :title="product.title" class="flex aspect-[800/1000] overflow-hidden relative bg-[#f4f2ee]">
            <template x-if="product.video">
                <div class="w-full h-full">
                    <div class="bg-[#f4f2ee] flex absolute inset-0 opacity-100 group-hover:opacity-0 transition-opacity duration-500 ease-in-out">
                        <img :src="product.image_src.woocommerce_single" :alt="product.title" class="maybeAddMixBlend object-contain w-full">
                    </div>
                    <video class="absolute left-0 top-0 w-full h-full object-contain opacity-0 group-hover:opacity-100 transition-opacity duration-500 ease-in-out" preload="metadata" playsinline="playsinline" loop="loop" muted="muted" autoplay>
                        <source :src="product.video" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            </template>
            <template x-if="!product.video">
                <div class="w-full h-full">
                    <div :class="product.image_src_2.woocommerce_single != '' && 'group-hover:opacity-0'"  class="bg-[#f4f2ee] flex absolute inset-0 opacity-100 group-hover:opacity-100 transition-opacity duration-500 ease-in-out">
                        <img :src="product.image_src.woocommerce_single" :alt="product.title" class="maybeAddMixBlend object-contain w-full">
                    </div>
                    <template x-if="product.image_src_2.woocommerce_single != ''">
                        <div class="bg-[#f4f2ee] flex absolute inset-0 opacity-0 group-hover:opacity-100 transition-opacity duration-500 ease-in-out">
                            <img :src="product.image_src_2.woocommerce_single" :alt="product.title" class="maybeAddMixBlend object-contain w-full">
                        </div>
                    </template>
                </div>
            </template>

            <div class="absolute right-1 md:right-2 top-1 md:top-2 flex gap-1 md:gap-2">
                <template x-if="product.last_units">
                    <div class="bg-white text-[8px] md:text-[10px] leading-3 font-medium text-body p-1 md:p-2 shadow-sm">
                        {{ __('LAST UNITS','sage') }}
                    </div>
                </template>
                <template x-if="product.cross_sells.length > 0">
                    <div class="bg-white text-[8px] md:text-[10px] leading-3 font-medium text-body p-1 md:p-2 shadow-sm">
                        {{ __('PAIR OPTION AVAILABLE','sage') }}
                    </div>
                </template>
                <template x-if="product.new_in_badge">
                    <div class="bg-white text-[8px] md:text-[10px] leading-3 font-medium text-body p-1 md:p-2 shadow-sm">
                        {{ __('NEW','sage') }}
                    </div>
                </template>
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

            <button x-on:click.prevent="addOneToCart()" type="button" name="add-to-cart" :value="product.product_id" 
                :class="showButtons ? 'cursor-default':'cursor-pointer'"
                class="w-full btn-md btn-solid-primary relative">
                <div class="flex items-center justify-center">
                    <span x-cloak x-show="!loading && !showButtons" x-ref="addToCartBtnText" x-text="addToCartText" data-addtext="{{ __('ADD TO CART','htech' ) }}" data-outofstock="{{ __('OUT OF STOCK','htech' ) }}" data-choosetext="{{ __('CHOOSE A VARIATION','htech' ) }}"></span>
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
            <a :href="product.permalink" :title="product.title" class="block">
                <h3 class="text-[10px]/3 md:text-xs/4 uppercase text-body font-bold mb-0" x-html="product.title">
                
                </h3>
            </a>
            <div class=""> 
                <button x-on:click="remove(event.currentTarget, product.product_id)" type="button"
                    class="text-red-600 hover:text-red-800">
                    {{ __('Remove','sage') }}
                </button>
            </div>
        </div>

        <div class="text-[10px]/3 md:text-xs/4 text-body hit-cats" x-html="product.category_list">
  
        </div>
        <p class="mb-0 text-[10px]/3 md:text-xs/4 text-body">
            <span x-html="product.price_html"></span>
            <template x-if="product.is_on_sale"><span class="ml-2 line-through text-red-600" x-html="product.regular_price_html"></span></template>            
        </p>
    </div>
</div>
