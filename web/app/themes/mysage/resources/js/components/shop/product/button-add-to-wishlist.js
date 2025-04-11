import {postData} from "@scripts/utilities/helper.js";

/**
 * @param {object} params
 * @param {number} params.product_id - The ID of the product.
 * @param {object} params.myWishlist - The user's wishlist.
 * @param {boolean} params.inWishlist - Whether the product is in any wishlist.
 */
export default ( { product_id, myWishlist, inWishlist }  ) => ({
    loading : false,
    product_id : product_id,
    myWishlist: myWishlist,
    inWishlist : inWishlist,
    init() {
        document.addEventListener('wishlistUpdated', (event) => {
           this.myWishlist = event.detail.wishlist
        }); 
    },
    addOrRemove(){
        if(this.inWishlist){
            this.remove()
        }else{
            this.add()
        }
    },
    remove(){
        const db_action = 'remove'
        this.edit(db_action)
    },
    add(){
        const db_action = 'insert'
        this.edit(db_action)
    },
    async edit(db_action){
        if(this.product_id === null){return}
        const post_id = parseInt(this.product_id)    

        let requestData = {
            action: "edit_wishlist",
            nonce: ajax_callback_settings.ajax_nonce,
            post_id,
            db_action,
        }
        this.loading = true
        await postData(ajax_callback_settings.ajax_url, requestData).then(response => {
            //console.log(response)
            if (response.success) {
                this.myWishlist = response.data.wishlist
                this.inWishlist = response.data.inWishlist

                this.$dispatch('closeAllModals', {})
                this.$dispatch('openWishlistNotification', { action: db_action })
            }else{
                mobiscroll.alert({
                    title: response.data.message,
                });
            }
            this.loading = false
        })  
    },
})
