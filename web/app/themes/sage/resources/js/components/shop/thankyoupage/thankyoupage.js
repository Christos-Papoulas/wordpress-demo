import { validateHTCart } from "@scripts/components/shop/cart/helpers.js"
// import {postData} from "@scripts/utilities/helper.js";

export default ( { transaction_id, totalCost, shippingCost, taxCost, coupon } ) => ({
    loading: false,
    items: {},
    transaction_id: transaction_id,
    totalCost: totalCost,
    shippingCost: shippingCost,
    taxCost: taxCost,
    coupon: coupon,
    async init() {
        // TODO: maybe get the items from php order object
        let ht_cart = localStorage.getItem('ht_cart')
        ht_cart = validateHTCart(ht_cart)
        // items
        this.items = ht_cart.items

        if(Object.keys(this.items).length > 0){
            this.$dispatch('purchaseItems', { 
                items: this.items, 
                total: this.totalCost, 
                transaction_id: this.transaction_id, 
                shipping: this.shippingCost, 
                tax: this.taxCost,
                coupon: this.coupon === '' ? false : this.coupon
            })
        }
        // clear the local storage cart after the purchase event
        this.clearLocalStorageCart()
    },
    clearLocalStorageCart(){
        localStorage.removeItem('ht_cart')
    },
    // async sendMail(order_id){
    //     let requestData = {
    //         action: "trigger_customer_email_for_order",
    //         nonce: ajax_callback_settings.ajax_nonce,
    //         order_id: order_id
    //     }
    //     await postData(ajax_callback_settings.ajax_url, requestData).then(response => {
    //         if(response.success){
    //             mobiscroll.alert({
    //                 title: 'Email Sent',
    //                 message: "An email has been sent to the order's billing email address. If you didn't receive it, please contact us."
    //             })
    //         }
    //     }).catch(error => {
    //        throw error
    //     })
    // }
})
