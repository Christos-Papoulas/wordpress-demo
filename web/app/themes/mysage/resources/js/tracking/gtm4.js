import Analytics from 'analytics'
import googleTagManager from '@analytics/google-tag-manager'
import googleAnalytics from '@analytics/google-analytics'
import { debounce } from "@scripts/utilities/helper.js"

/**
 * @see https://github.com/DavidWells/analytics
 * 
 * track view_item, view_cart, purchase, begin_checkout, view_item_list, add_shipping_info, add_payment_info
 */
export default ( ) => ({
    analytics: null,
    init(){

        if(import.meta.env.DEV){
            console.log('Analytics disabled in development mode')
            return
        }

        this.analytics =  Analytics({
            app: 'fashion.wpdev247.com',
            version: 100,
            plugins: [
                googleAnalytics({
                    measurementIds: ['G-CNND4HNZTH'],
                }),
                googleTagManager({
                    containerId: 'GTM-5KJP5NXV',
                    // dataLayerName: 'customDataLayer', The dataLayer is by default set to window.dataLayer. 
                })
            ]
        })

        this.addEventListeners()

        /* Track a page view */
        this.analytics.page()

        const user = global_app_data.user

        if(user.email){
            this.generateUserId(user.email).then(hashedUserID => {
                /* Identify a visitor */
                // @see https://support.google.com/analytics/answer/9213390?hl=en
                this.analytics.identify(hashedUserID, {
                    firstName: user.first_name ? user.first_name : '',
                    lastName: user.last_name ? user.last_name : '',
                    email: user.email ? user.email : '',
                })
            })
        }
    },
    /**
     * Generate a hashed user id from the email
     * @see https://developer.mozilla.org/en-US/docs/Web/API/Crypto
     * @param {string} email 
     * @returns string
     */
    async generateUserId(email) {
        const encoder = new TextEncoder();
        const data = encoder.encode(email.trim().toLowerCase()); // Normalize email
        const hashBuffer = await crypto.subtle.digest('SHA-256', data);
        const hashArray = Array.from(new Uint8Array(hashBuffer));
        return hashArray.map(byte => byte.toString(16).padStart(2, '0')).join('');
    },
    /**
     * Add event listeners for custom events
     * 
     * @see https://developers.google.com/analytics/devguides/collection/ga4/reference/events?client_type=gtag
     */
    async addEventListeners() {
        document.addEventListener('addToCart', (event) => {
            this.addToCart(event.detail.item, event.detail.quantity)
        });
        document.addEventListener('removeFromCart', (event) => {
            this.removeFromCart(event.detail.item, event.detail.quantity)
        });
        document.addEventListener('viewCart', (event) => {
            this.viewCart(event.detail)
        });
        document.addEventListener('viewItemList', (event) => {
            this.viewItemList(event.detail)
        });
        document.addEventListener('viewItem', (event) => {
            this.viewItem(event.detail.item)
        });
        document.addEventListener('purchaseItems', (event) => {
            this.purchaseItems(event.detail)
        });
        document.addEventListener('beginCheckout', (event) => {
            this.beginCheckout(event.detail)
        });
        document.addEventListener('addShippingInfo', (event) => {
            this.addShippingInfo(event.detail)
        });
        document.addEventListener('addPaymentInfo', (event) => {
            this.addPaymentInfo(event.detail)
        });

        // typesense user search
        document.addEventListener("DOMContentLoaded", () => {
            const searchInput = document.querySelector(".ais-SearchBox-input");
        
            if (searchInput) {
                const debouncedSearch = debounce((event) => {
                    const sanitizedQuery = this.sanitizeInput(event.target.value);
                    if (sanitizedQuery) {
                        this.search(sanitizedQuery);
                    }
                }, 500); 
        
                searchInput.addEventListener("input", debouncedSearch);
            }
        });
    },
    sanitizeInput(input) {
        return input.replace(/<\/?[^>]+(>|$)/g, "").trim(); // Removes HTML tags & trims whitespace
    },
    /**
     * Search event
     * 
     * @see https://developers.google.com/analytics/devguides/collection/ga4/reference/events?client_type=gtag#search
     */
    search(search){
        this.trackEvent('search', {
            search_term: search
        })
    },
    /**
     * Add to cart event
     * 
     * @see https://developers.google.com/analytics/devguides/collection/ga4/reference/events?client_type=gtag#add_to_cart
     */
    addToCart(item, quantity){
        //console.log('add to cart event for', item)
        this.trackEvent('add_to_cart', {
            currency: "EUR",
            value: item.price,
            items: [
                this.createProductObject(item, 1)
            ]
        })
    },
    /**
     * Remove from cart event
     * 
     */
    removeFromCart(item, quantity){
        //console.log('remove from cart event for', item)
        this.trackEvent('remove_form_cart', {
            currency: "EUR",
            value: item.price,
            items: [
                this.createProductObject(item, quantity)
            ]
        })
    },
    /**
     * Begin checkout event
     * 
     * @see https://developers.google.com/analytics/devguides/collection/ga4/reference/events?client_type=gtag#begin_checkout
     */
    beginCheckout(data){
        let obj = {
            currency: "EUR",
            value: data.total,
            items: []
        }

        if(false){
            obj.coupon = "SUMMER_FUN"
        }

        Object.entries(data.items).forEach(([key, item], index) => {
            obj.items.push(this.createProductObject(item, item.quantity, index));
        });
        // console.log('event for begin_checkout', obj)
        this.trackEvent('begin_checkout', obj)
    },
    /**
     * Add payment info event
     * 
     * @see https://developers.google.com/analytics/devguides/collection/ga4/reference/events?client_type=gtag#add_payment_info
     */
    addPaymentInfo(data){

        let payment_type = '(payment type not found)';
        let payment_el = document.querySelector( '.payment_methods input:checked' );
        if ( !payment_el ) {
            payment_el = document.querySelector( 'input[name^=payment_method]' ); // select the first input element
        }
        if ( payment_el ) {
            payment_type = payment_el.value;
        }

        let obj = {
            currency: "EUR",
            payment_type: payment_type,
            value: data.total,
        }
        this.trackEvent('add_payment_info', obj)
    },
    /**
     * Add shipping info event
     * 
     * @see https://developers.google.com/analytics/devguides/collection/ga4/reference/events?client_type=gtag#add_shipping_info
     */
    addShippingInfo(data){

        let shipping_tier = '(shipping tier not found)';
        let shipping_el = document.querySelector( 'input[name^=shipping_method]:checked' );
        if ( !shipping_el ) {
            shipping_el = document.querySelector( 'input[name^=shipping_method]' ); // select the first input element
        }
        if ( shipping_el ) {
            shipping_tier = shipping_el.value;
        }

        let obj = {
            currency: "EUR",
            shipping_tier: shipping_tier,
            value: data.total,
        }
        console.log('event for add_shipping_info', obj)
        this.trackEvent('add_shipping_info', obj)
    },
    /**
     * purchase event
     * 
     * @see https://developers.google.com/analytics/devguides/collection/ga4/reference/events?client_type=gtag#purchase
     */
    purchaseItems(data){
        let obj = {
            currency: "EUR",
            value: data.total,
            transaction_id: data.transaction_id,
            shipping: data.shipping,
            tax: data.tax,
            items: []
        }
        
        if(false){
            obj.coupon = "SUMMER_FUN"
        }

        Object.entries(data.items).forEach(([key, item], index) => {
            obj.items.push(this.createProductObject(item, item.quantity, index));
        });
        // console.log('event for purchase', obj)
        this.trackEvent('purchase', obj)
    },
    /**
     * View item list event
     * 
     * @see https://developers.google.com/analytics/devguides/collection/ga4/reference/events?client_type=gtag#view_item_list
     */
    viewItemList(data){
        let obj = {
            item_list_id: data.list_id,
            item_list_name: data.list_name,
            items: []
        }
        Object.entries(data.items).forEach(([key, item], index) => {
            obj.items.push(this.createProductObject(item, null, index));
        });
        // console.log('event for view_item_list', obj)
        this.trackEvent('view_item_list', obj)
    },
    /**
     * View cart event
     * 
     * @see https://developers.google.com/analytics/devguides/collection/ga4/reference/events?client_type=gtag#view_cart
     */
    viewCart(data){
        let obj = {
            currency: "EUR",
            value: data.total,
            items: []
        }
        Object.entries(data.items).forEach(([key, item], index) => {
            obj.items.push(this.createProductObject(item, item.quantity, index));
        });
        // console.log('event for view cart', obj)
        this.trackEvent('view_cart', obj)
    },
    /**
     * View item event
     * 
     * @see https://developers.google.com/analytics/devguides/collection/ga4/reference/events?client_type=gtag#view_item
     */
    viewItem(item){
        // console.log('event for view item', item)  
        this.trackEvent('view_item', {
            currency: "EUR",
            value: item.price,
            items: [
                this.createProductObject(item)
            ]
        })
    },
    /**
     * Create a product object for the events
     */
    createProductObject(item, quantity = null, index = 0){
        
        let obj = { 
            item_id: item.product_id,
            item_name: item.title,
            index: index,
            price: item.price,
        }

        // TODO : 
        // affiliation: "Google Merchandise Store",
        // coupon: "SUMMER_FUN",
        if(item.price < item.regular_price){
            obj.discount = item.regular_price - item.price
        }

        if(quantity !== null){
            obj.quantity = quantity
        }

        // Loop through the categories array and dynamically assign properties
        // max 5 categories
        item.categories.forEach((category, i) => {
            if( i > 4 ) return
            obj[`item_category${i === 0 ? "" : i + 1}`] = category
        });

        if(item.hasOwnProperty('brand') && item.brand !== null && item.brand !== ''){
            obj.item_brand = item.brand
        }
        if(item.hasOwnProperty('variation_attr')){
            obj.item_variant = item.variation_attr
        }
        if(item.hasOwnProperty('list_id')){
            obj.item_list_id = item.list_id
        }
        if(item.hasOwnProperty('list_name')){
            obj.item_list_name = item.list_name
        }
        if(item.hasOwnProperty('location_id')){
            obj.location_id = item.location_id
        }
        // console.log('product object', obj)
        return obj
    },
    /**
     * Track a custom event
     */
    trackEvent(event, object){
        this.analytics.track(event, object)
    }
})
