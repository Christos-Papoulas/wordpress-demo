import { postData } from "@scripts/utilities/helper.js"
import { format_wc_price } from "@scripts/utilities/helper.js"
import { validateHTCart } from "@scripts/components/shop/cart/helpers.js"

export default () => ({
    loading: true,
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
    async init() {
        await this.addEventListeners()
        await this.getFreeShippingAmount()
        await this.getCartAndTotals()
    },
    async addEventListeners() {
        document.addEventListener('showMiniCart', (event) => {
            this.toggleMinicart()
        });
        document.addEventListener('toggleMiniCart', (event) => {
            this.toggleMinicart()
        });
        document.addEventListener('closeAllModals', (event) => {
            this.closeMinicart()
        });
        document.addEventListener('htCartUpdated', (event) => {
            this.getCartAndTotals()
            if(event.detail.openCart){
                this.toggleMinicart()
            }
            // console.log('mini cart updated')
        });
    },
    async getCartAndTotals() {

        let ht_cart = localStorage.getItem('ht_cart')
        ht_cart = validateHTCart(ht_cart)

        // reset messages
        for (let key in ht_cart.items) {
            ht_cart.items[key].messages = {
                "incrementDisabled": null,
                "decrementDisabled": null
            }
        }

        // save the object
        this.localStorageCart = ht_cart
        
        // items
        this.items = ht_cart.items
        
        await this.calcCartTotalsFromLocalStorage()
        this.calculateFreeShipping()
    },
    async calcCartTotalsFromLocalStorage(){
        // total count
        let totalQuantity = Object.values(this.localStorageCart.items).reduce((total, item) => {
            return total + parseInt(item.quantity);
        }, 0);
        this.count = totalQuantity
       
        this.subtotal = 0
        this.subtotalFormatted = ''
        this.subtotalVatSuffix = ''
        this.coupons = []
        this.shipping = []
        this.shippingHtml = ''
        this.feesHtml = ''
        this.taxHtml = ''
        this.discountHtml = ''
        
        // total vat
        let totalVat = 0
        totalVat = Object.values(this.localStorageCart.items).reduce((total, item) => {
            return total + parseFloat(item.line_tax);
        }, 0);
        
        // total price
        let totalLineTotal = Object.values(this.localStorageCart.items).reduce((total, item) => {
            return total + ( parseFloat(item.line_total) + parseFloat(item.line_tax) );
        }, 0);
        this.total = totalLineTotal

        this.totalFormatted = `<span class=\"woocommerce-Price-amount amount\"><bdi>${format_wc_price(totalLineTotal.toFixed(2))}&nbsp;<span class=\"woocommerce-Price-currencySymbol\">&euro;</span></bdi></span>`
        this.totalVatSuffix = totalVat == 0 ? '' : `<small class=\"includes_tax\">(includes <span class=\"woocommerce-Price-amount amount\">${format_wc_price(totalVat.toFixed(2))}&nbsp;<span class=\"woocommerce-Price-currencySymbol\">&euro;</span></span> VAT)</small>`

        this.show = this.count > 0 ? true : false
        this.loading = false
    },
    async updateCartItemQuantity(key, quantity, datalayerEventName) {

        const item = this.items[key]
        // quantity -1 for woocommerce = unlimited
        if (item && ( item.max_qty == -1 || (quantity) <= item.max_qty) ){

            if(!this.localStorageCart.items[key]){
                console.log('cart item not found')
                return
            }

            if(quantity <= 0){
                this.removeItemFromCart(key)
            }else{
                this.localStorageCart.items[key].quantity = quantity

                let line_subtotal = ( parseFloat(this.localStorageCart.items[key].price) - parseInt(0) ) * parseInt(quantity) // remove tax

                // TODO: calc tax
                this.localStorageCart.items[key].line_subtotal = line_subtotal
                this.localStorageCart.items[key].line_subtotal_tax = 0
                this.localStorageCart.items[key].line_total = line_subtotal
                this.localStorageCart.items[key].line_tax = 0
                
                // tag ht_cart as unsynced and save it
                this.localStorageCart.isSynced = false
                localStorage.setItem('ht_cart', JSON.stringify(this.localStorageCart));
                
                this.updateMiniCart()

            }

        }else{
            item.quantity = item.max_qty
            item.messages.incrementDisabled = 'Sorry! you reached maximum limit for this product!'
        }
    },
    updateMiniCart() {
        this.$dispatch('htCartUpdated', { open: false })
    },
    async getCartTotals() {
        await this.calcCartTotalsFromLocalStorage()
    },
    removeItemFromCart(key) {
        if (this.localStorageCart.items.hasOwnProperty(key)) {
            const item = this.localStorageCart.items[key]
            delete this.localStorageCart.items[key];
            this.$dispatch('removeFromCart', { item: item, quantity: item.quantity })

            // tag ht_cart as unsynced and save it
            this.localStorageCart.isSynced = false
            localStorage.setItem('ht_cart', JSON.stringify(this.localStorageCart));
            this.updateMiniCart()
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
    async clearCart() {
        localStorage.removeItem('ht_cart')
        this.updateMiniCart()
    },
    toggleMinicart(){
        if(!this.showMinicart){
            this.$dispatch('closeAllModals', {})
            this.showMinicart = true
            document.body.classList.add('body-no-scroll')
            document.getElementById('backdrop').classList.remove('hidden')
        }else{
            this.closeMinicart()
        }
    },
    closeMinicart(){
        this.showMinicart = false
        document.body.classList.remove('body-no-scroll');
        document.getElementById('backdrop').classList.add('hidden');
    },
    async getFreeShippingAmount(){
        let requestData = {
            action: "get_free_shipping_amount",
            nonce: ajax_callback_settings.ajax_nonce
        }
        await postData(ajax_callback_settings.ajax_url, requestData).then(response => {
            // console.log('Getting free shipping', response)

            if(response.user_zone_id === null || response.amount_to_free_shipping === null){
                this.freeshipping.enabled = false
                this.freeshipping.free_shipping_amount = null
                this.freeshipping.percentage = null
                this.freeshipping.amount_to_free_shipping = null
            }else{
                this.freeshipping.free_shipping_amount = response.amount_to_free_shipping
            }
        })
    },
    async calculateFreeShipping(){
        // console.log('Calculating free shipping')
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
})
