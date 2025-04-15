import.meta.glob([
  '../images/**',
  '../fonts/**',
]);
import Alpine from 'alpinejs';

import '@scripts/utilities/share-buttons.js';
import '@scripts/utilities/translations.js';

// Components
import {default as posts} from '@scripts/components/posts/posts-filters.js';
import {
  productList, productGallery, productCard, buttonAddToCartSinglePage, customAttributeInput, buttonAddToWishlist,
  checkout, thankYouPage, shop, wishlist, loginForm, editAddress, storelocator, cart, miniCart, cartButton
} from "@scripts/components/shop/index.js";

// Typesense loader animation
import '@scripts/components/shop/archive/typesense-loader.js';

// Gutenberg block scripts
import '@scripts/blocks/index.js';

// Tracking
import {default as gtm4} from '@scripts/tracking/gtm4.js';
// Manually initialize GTM4 outside of x-data
document.addEventListener('alpine:init', () => {
  Alpine.store('gtm4', gtm4());
});

Alpine.data('loginForm', loginForm)
Alpine.data('shop', shop)
Alpine.data('wishlist', wishlist)
Alpine.data('cart', cart)
Alpine.data('miniCart', miniCart)
Alpine.data('cartButton', cartButton)
Alpine.data('checkout', checkout)
Alpine.data('thankYouPage', thankYouPage)
Alpine.data('posts', posts)
Alpine.data('productGallery', productGallery)
// Alpine.data('productStoreStockStatus', productStoreStockStatus)
Alpine.data('productCard', productCard)
Alpine.data('productList', productList)
Alpine.data('buttonAddToCartSinglePage', buttonAddToCartSinglePage)
Alpine.data('customAttributeInput', customAttributeInput)
Alpine.data('buttonAddToWishlist', buttonAddToWishlist)
// Alpine.data('buttonAddToCompareList', buttonAddToCompareList)
// Alpine.data('buttonBackInStockNotify', buttonBackInStockNotify)
Alpine.data('storelocator', storelocator)
// Alpine.data('compareProduct', compareProduct)
// Alpine.data('brandsTemplate', brandsTemplate)
Alpine.data('editAddress', editAddress)
Alpine.data('gtm4', gtm4); 
window.Alpine = Alpine
Alpine.start()
