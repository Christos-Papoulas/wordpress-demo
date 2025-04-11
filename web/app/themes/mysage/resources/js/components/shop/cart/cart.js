import { postData } from "@scripts/utilities/helper.js"
import { format_wc_price } from "@scripts/utilities/helper.js"
import { validateHTCart } from "@scripts/components/shop/cart/helpers.js"

export default ( { shippingPostCode } ) => ({
    loading: true,
    disableCheckoutButton: true,
    errors: [],
    showMinicart: false,
    items: {},
    count: 0,
    couponCode: '',
    coupon: {
        applyCouponSuccess: null,
        applyCouponMessage: '',
    },
    // cart totals
    subtotal: 0,
    subtotalFormatted: '',
    subtotalVatSuffix: '',
    coupons: [],
    shipping: [],
    shippingHtml: '',
    feesHtml: '',
    taxHtml: '',
    total: 0,
    totalFormatted: '',
    totalVatSuffix: '',
    // extra
    discountHtml: '',
    freeshipping: {
        enabled: false,
        free_shipping_amount: null,
        percentage: null,
        amount_to_free_shipping: null,
        amount_to_free_shipping_html: ''
    },
    localStorageCart: {},
    postCodeData: {
        activeShippingPostcode: shippingPostCode,
        shippingPostCodeInputVal: shippingPostCode,
        loading: false,
        error: false,
    },
    async init() {
        await this.addEventListeners()
        await this.createWCcart()
        await this.getCartAndTotals()
        await this.syncLocalStorageCartFromWcCart()
        this.loading = false

        if(this.postCodeData.activeShippingPostcode === null || this.postCodeData.activeShippingPostcode === ''){
            this.openPostcodeModal()
        }

        this.$dispatch('viewCart', { items: this.items, total: this.total, count: this.count })
    },
    async addEventListeners() {
        
        jQuery(document.body).on('updated_shipping_method', () => {
            this.getCartAndTotals()
        })
    },
    async createWCcart(){

        const loader = document.getElementById('cart_loader_modal')
        const instance = mobiscroll.popup(loader);
        instance.setOptions({
            display: 'center',
            closeOnEsc: false,
            closeOnOverlayClick: false
        });
        instance.open();
        loader.classList.remove('hidden')

        const response = await this.createWCcartFromLocalStorageCart()
        //console.log('Created WC Cart', response)
        if(response.success){
            // if all products added
            // console.log( this.localStorageCart )
            this.setCartFromWCresponse(response)
            this.localStorageCart = {
                isSynced : true,
                version : import.meta.env.VITE_LOCAL_STORAGE_CART_VERSION,
                items : response.data.items
            }
            // console.log( this.localStorageCart )
            localStorage.setItem('ht_cart', JSON.stringify(this.localStorageCart));
            instance.close();
        }else{
           
            // if not all products added
            this.setCartFromWCresponse(response)
            //console.log( this.localStorageCart )
            this.localStorageCart = {
                isSynced : true,
                version : import.meta.env.VITE_LOCAL_STORAGE_CART_VERSION,
                items : response.data.items
            }
            localStorage.setItem('ht_cart', JSON.stringify(this.localStorageCart));

            instance.close();
            mobiscroll.alert({
                title: response.data.error_title,
                message: response.data.message,
            });
        }
    },
    async createWCcartFromLocalStorageCart(){

        let ht_cart = localStorage.getItem('ht_cart')
        ht_cart = validateHTCart(ht_cart)
        this.localStorageCart = ht_cart

        let data = {
            items : []
        }
        for (let key in this.localStorageCart.items) {
            if (this.localStorageCart.items.hasOwnProperty(key)) { // Check if the key is a direct property
                let item = this.localStorageCart.items[key];
                data.items.push({
                    "product_id": item.product_id,
                    "variation_id": item.variation_id,
                    "variation": item.variation,
                    "quantity": item.quantity,
                })
            }
        }

        let requestData = {
            action: "create_wc_cart_from_local_storage",
            nonce: ajax_callback_settings.ajax_nonce,
            data: JSON.stringify(data)
        };
        return await postData(ajax_callback_settings.ajax_url, requestData).then(response => {
            //console.log(response)
            return response
        })
        
    },        
    async getCartAndTotals() {

        this.disableCheckoutButton = true 
        
        let requestData = {
            action: "get_cart_and_totals",
            nonce: ajax_callback_settings.ajax_nonce,
            forMinicart : false
        }

        try {
            const response = await postData(ajax_callback_settings.ajax_url, requestData);
            //console.log('Cart Totals', response);
            await this.setCartFromWCresponse(response);
            this.getFreeShippingAmount()
        } catch (error) {
            console.error('Error fetching cart and totals:', error);
        }
        // console.log('finished')
        this.disableCheckoutButton = false
    },
    async setCartFromWCresponse(response){
        // console.log(response)
        // Check if response.data.items is an empty array
        this.items = Array.isArray(response.data.items) && response.data.items.length === 0
        ? {}
        : response.data.items;

        this.count = response.data.totals.count

        this.subtotal = response.data.totals.subtotal.amount
        this.subtotalFormatted = response.data.totals.subtotal.formatted
        this.subtotalVatSuffix = response.data.totals.subtotal.vat_suffix ?? ''
        this.coupons = response.data.totals.coupons
        this.shipping = response.data.totals.shipping
        this.shippingHtml = response.data.totals.shippingHtml
        this.feesHtml = response.data.totals.feesHtml
        this.taxHtml = response.data.totals.taxHtml
        this.discountHtml = response.data.totals.discountHtml
        
        this.total = response.data.totals.total.amount
        this.totalFormatted = response.data.totals.total.formatted
        this.totalVatSuffix = response.data.totals.total.vat_suffix ?? ''
        
        this.show = this.count > 0 ? true : false

        // console.log('setCartFromWCresponse')
    },
    async syncLocalStorageCartFromWcCart(){

        //console.log('Syncing to local storage');
        this.localStorageCart = {
            isSynced : true,
            version : import.meta.env.VITE_LOCAL_STORAGE_CART_VERSION,
            items : this.items
        }
        localStorage.setItem('ht_cart', JSON.stringify(this.localStorageCart));

        this.calculateFreeShipping()
        this.$dispatch('htCartUpdated', { openCart: false })
    },
    async updateCartItemQuantity(key, quantity, datalayerEventName) {

        const item = this.items[key]
        // quantity -1 for woocommerce = unlimited
        if (item && ( item.max_qty == -1 || (quantity) <= item.max_qty) ){

            let requestData = {
                action: "update_cart_item_quantity",
                nonce: ajax_callback_settings.ajax_nonce,
                cart_item_key: key,
                quantity: quantity
            }

            try {
                const response = await postData(ajax_callback_settings.ajax_url, requestData);
                //console.log('Item quantity updated');
                await this.getCartAndTotals();
                await this.syncLocalStorageCartFromWcCart()
            } catch (error) {
                console.error('Error updating cart item quantity:', error);
            }

        }else{
            item.quantity = item.max_qty
            item.messages.incrementDisabled = 'Sorry! you reached maximum limit for this product!'
        }
    },
    async removeItemFromCart(key) {
        let requestData = {
            action: "remove_item_from_cart",
            cart_item_key: key,
            nonce: ajax_callback_settings.ajax_nonce
        };

        try {
            const response = await postData(ajax_callback_settings.ajax_url, requestData);
            if(response.data.quantity > 0){
                this.$dispatch('removeFromCart', { item: item, quantity: response.data.quantity })
            }
            await this.getCartAndTotals();
            await this.syncLocalStorageCartFromWcCart()
        } catch (error) {
            console.error('Error updating cart item quantity:', error);
        }
    },
    async increment(key) {
        const item = this.items[key]

        // quantity -1 for woocommerce = unlimited
        if (item && (item.max_qty == -1 || (Number(item.quantity) + 1) <= item.max_qty)) {
            item.quantity++
            await this.updateCartItemQuantity(key, item.quantity, 'add_to_cart')
            this.$dispatch('addToCart', { item: item, quantity: 1 })
            item.messages.incrementDisabled = null
        } else {
            item.messages.incrementDisabled = 'Sorry! you reached maximum limit for this product!'
        }
    },
    async decrement(key) {
        const item = this.items[key]
        // condition is at blade too
        if (item && (Number(item.quantity) - 1) > 0) {
            //console.log(item.quantity)
            item.quantity--
            await this.updateCartItemQuantity(key, item.quantity, 'remove_from_cart')
            this.$dispatch('removeFromCart', { item: item, quantity: 1 })
            item.messages.decrementDisabled = null
        } else {
            //console.log('Sorry! you reached minimum limit!')
            item.messages.decrementDisabled = 'Sorry! you reached minimum limit!'
        }
    },

    applyCoupon() {
        return new Promise((resolve, reject) => {

            if (!this.couponCode) {
                resolve()
                return;
            }

            let requestData = {
                action: "apply_coupon",
                nonce: ajax_callback_settings.ajax_nonce,
                coupon_code: this.couponCode
            }

            postData(ajax_callback_settings.ajax_url, requestData).then(data => {
                if (data.success) {
                    this.coupon.applyCouponSuccess = true;
                    this.coupon.applyCouponMessage = 'Το κουπόνι προστέθηκε επιτυχώς!';
                } else {
                    this.coupon.applyCouponSuccess = false;
                    this.coupon.applyCouponMessage = data.data.message;
                }

                // clear message
                setTimeout(() => {
                    this.coupon.applyCouponMessage = null;
                }, 3000)

                this.getCartAndTotals()
            })

        })
    },
    removeCoupon(couponSlug){
        return new Promise((resolve, reject) => {

            let requestData = {
                action: "remove_coupon",
                nonce: ajax_callback_settings.ajax_nonce,
                couponSlug: couponSlug
            }

            postData(ajax_callback_settings.ajax_url, requestData).then(data => {
                this.getCartAndTotals()
            })

        })
    },
    async getFreeShippingAmount(){
        let requestData = {
            action: "get_free_shipping_amount",
            nonce: ajax_callback_settings.ajax_nonce
        }
        await postData(ajax_callback_settings.ajax_url, requestData).then(response => {
            //console.log('Getting free shipping', response)

            if(response.user_zone_id === null || response.amount_to_free_shipping === null){
                this.freeshipping.enabled = false
                this.freeshipping.free_shipping_amount = null
                this.freeshipping.percentage = null
                this.freeshipping.amount_to_free_shipping = null
            }else{
                this.freeshipping.free_shipping_amount = response.amount_to_free_shipping
            }

            this.calculateFreeShipping()
        })
    },
    async calculateFreeShipping(){

        //console.log('Calculating free shipping')

        // console.log(this.total)
        // console.log(this.freeshipping.free_shipping_amount)

        this.freeshipping.percentage = 
        Math.round((Number(this.total) / Number(this.freeshipping.free_shipping_amount)) * 100 * 100) / 100; // Round to 2 decimal places
    
        this.freeshipping.amount_to_free_shipping = 
        Math.round((Number(this.freeshipping.free_shipping_amount) - Number(this.total)) * 100) / 100; // Round to 2 decimal places

        if( Number(this.freeshipping.amount_to_free_shipping) <= Number(0) ){ this.freeshipping.enabled = true }else{ this.freeshipping.enabled = false }
        
        this.freeshipping.amount_to_free_shipping_html = 
        format_wc_price(this.freeshipping.amount_to_free_shipping);

        // percentage bar manipulate
        if (this.freeshipping.percentage != null && this.freeshipping.percentage !== undefined && this.freeshipping.percentage >= 0 && this.freeshipping.percentage < 100) {
            let styleElement = document.createElement('style');
            document.head.appendChild(styleElement);
            styleElement.sheet.insertRule('#freeShippingPercentageBar::before { width:' + this.freeshipping.percentage + '%; }', 0);
        }
    },
    openPostcodeModal(){
        this.modalEl = this.$refs.postcodeModal
        if(this.modalEl === undefined || this.modalEl === null){
            return
        }
        this.modalInstance = mobiscroll.popup(this.modalEl)

        this.modalInstance.setOptions({
            display: 'center',
            fullScreen: true,
            maxHeight: '600px',
            maxWidth: '800px',
            closeOnEsc: true,
            closeOnOverlayClick: true
        });
        this.modalInstance.open();
        this.modalEl.classList.remove('hidden')
    },
    closePostcodeModal(){
        this.modalInstance.close();
        this.modalEl.classList.add('hidden')
    },
    async updateShippingPostcode(){
        
        let requestData = {
            action: "update_session_billing_and_shipping_postcode",
            nonce: ajax_callback_settings.ajax_nonce,
            postcode: this.postCodeData.shippingPostCodeInputVal,
        }
        this.postCodeData.loading = true
        this.modalInstance.setOptions({
            closeOnEsc: false,
            closeOnOverlayClick: false
        });
        await postData(ajax_callback_settings.ajax_url, requestData).then(response => {
            //console.log(response)
      
            this.postCodeData.shippingPostCodeInputVal = response.data.session_shipping_postcode
            this.postCodeData.activeShippingPostcode = this.postCodeData.shippingPostCodeInputVal

            this.postCodeData.loading = false
            this.postCodeData.error = false
            this.modalInstance.close();
            this.modalInstance.setOptions({
                closeOnEsc: true,
                closeOnOverlayClick: true
            });
        })  

        this.getCartAndTotals()
    },
    async validateAndGoToCheckout(checkoutUrl = global_app_data.checkout_url){

        this.errors = []

        let requestData = {
            action: "validate_cart_before_checko",
            nonce: ajax_callback_settings.ajax_nonce,
            postcode: this.postCodeData.shippingPostCode,
        }

        // send also the chosen store to be saved to the session.
        let chosen_store_input = document.querySelector('[name="ht_store_pickup"]:checked')
        if(chosen_store_input !== undefined && chosen_store_input !== null && chosen_store_input !== ''){
            requestData.chosen_store = chosen_store_input.value
        }

        await postData(ajax_callback_settings.ajax_url, requestData).then(response => {
            console.log(response)
            if (response.success) {
                location.href = checkoutUrl
            } else {

                // update items. Items now have the 'must_be_removed' property
                this.items = Array.isArray(response.data.items) && response.data.items.length === 0
                ? {}
                : response.data.items;

                this.errors.push(response.data.message)
            }
        })
    },
})
