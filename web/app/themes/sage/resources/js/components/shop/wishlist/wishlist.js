import {postData} from "@scripts/utilities/helper.js";

/**
 * @param {object} params.myWishlist - The user's wishlist.
 * @param {int} params.activeListID - The ID of the active list.
 */
export default ( { myWishlist, mobiscrollMessagesConfirmTitle, mobiscrollMessagesConfirmMessage, mobiscrollMessagesConfirmOkText, mobiscrollMessagesConfirmCancelText } ) => ({
    loading: true,
    myWishlist: myWishlist,
    items: [], // Contains woocommerce products
    count : 0,
    shareButtons: {
        shareUrl: '',
        showTooltip: false,
        fallbackShare: true,
    },
    mobiscrollMessages: {
        confirm: {
            title: mobiscrollMessagesConfirmTitle,
            message: mobiscrollMessagesConfirmMessage,
            okText: mobiscrollMessagesConfirmOkText,
            cancelText: mobiscrollMessagesConfirmCancelText
        }
    },
    async init() {
        await this.getItems()
    },
    async getItems(){

        this.loading = true
        let requestData = {
            action: "get_list_items_from_ids",
            nonce: ajax_callback_settings.ajax_nonce,
            post_ids : JSON.stringify(this.myWishlist.list),
        }

        await postData(ajax_callback_settings.ajax_url, requestData).then(response => {
            //console.log(response)
            if (response.success) {
                this.items = response.data.items
                this.count = Object.keys(this.items).length > 0
            }
        })  
        this.loading = false
    },
    async remove(button, post_id){
        if(post_id === null){return}
        this.loading = true

        let requestData = {
            action: "edit_wishlist",
            nonce: ajax_callback_settings.ajax_nonce,
            post_id : parseInt(post_id),
            get_products : true,
            db_action : 'remove',
        }
        this.loading = true
        await postData(ajax_callback_settings.ajax_url, requestData).then(response => {
            //console.log(response)
            if (response.success) {
                this.myWishlist = response.data.wishlist
                this.items = response.data.products
                this.count = Object.keys(this.items).length > 0 
            }else{
                mobiscroll.alert({
                    title: response.data.message,
                });
            }
        })   
        this.loading = false
    },
    addAllToCart(){

        let nodes = this.$root.querySelectorAll('.product-card')
        this.addToCardButtons = Array.from(nodes).map((el) => el._x_dataStack[0].addToCart());

    },
    async createShareButtonsData(){
        // Get the current URL
        const currentUrl = window.location.href;
        // Join the items array into a comma-separated string
        const ids = this.wishlist.items.join(',');
        // Check if the current URL already has a query string (?)
        const separator = currentUrl.includes('?') ? '&' : '?';
        // Construct the new URL by appending the ids parameter
        this.shareButtons.shareUrl = `${currentUrl}${separator}ids=${ids}`;

        // check if we have navigator share on the device
        if (navigator.share){
            this.fallbackShare = false
        }else{
            this.fallbackShare = true
        }
    }
})
