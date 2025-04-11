import {generateCartId} from "@scripts/utilities/helper.js"
import {generateCartDataHash} from "@scripts/utilities/helper.js"
import {format_wc_price} from "@scripts/utilities/helper.js"
import {validateHTCart} from "@scripts/components/shop/cart/helpers.js"
import {postData} from "@scripts/utilities/helper.js"

/**
 * @param {object} params.productCardData - The initial product data.
 * @param {int} params.productID - The ID of the product.
 * @param {string} params.variationID - The ID of the variation
 * @param {string} params.productType - The type of the product.
 * @param {int} params.minQty - The minimum quantity of the product.
 * @param {int} params.maxQty - The maximum quantity of the product.   
 */
export default ( { productCardData } ) => ({
    productCardData: productCardData,
    loading:false,
    productID: null,
    variationID: 0,
    selectedVariation: null,
    variation: {
        attributes: {},
        variation_attr: ''
    },
    productType: null,
    qty:0,
    stockStatus: null,
    minQty: 0,
    maxQty: 0,
    showButtons: false,
    addToCartText: wp.i18n.__('ADD TO CART', 'sage'),
    decreaseDisabled:true,
    increaseDisabled:true,
    savedData: {}, // save fetched data of variations, that dont change often, to avoid multiple requests ( eg. savedData.variation_id.sku )
    attributeInputs: {},
    init() {
        // console.log(productCardData)
        this.stockStatus = productCardData.stock_status
        this.minQty = productCardData.min_qty
        this.maxQty = productCardData.max_qty
        this.productID = productCardData.product_id
        this.productType = productCardData.type

        if(this.productType === 'variable'){
            this.createObjectOfAttributeInputs()
        }

        this.checkIfProductExistsInCart()
        this.enableDisableQtyButtons()
        this.$watch('qty', () => this.enableDisableQtyButtons())

        document.addEventListener('htCartUpdated', (event) => {
            this.checkIfProductExistsInCart()
            this.enableDisableQtyButtons()
        });

        jQuery(".single_variation_wrap").on("show_variation", () => {
            this.onShowVariation()
        })

        // This event is triggered 3 times by woocommerce.
        jQuery(".variations_form").on("reset_data", () => {
            this.onVariationReseted()
        });

        this.$dispatch('viewItem', { item: this.productCardData})
    },
    onVariationReseted(){
        this.createObjectOfAttributeInputs()
        this.variationID = 0
        this.stockStatus = null
        this.minQty = 0
        this.maxQty = 0
        this.enableDisableQtyButtons()
        this.getSelectedVariationPrice()
        this.updateProductSku()
        this.changeAddToCartButtonText()
        this.$dispatch('variationsReseted', { item: this.productCardData})
    },
    onShowVariation(){
        this.variationID = jQuery(this.$refs.form).find('input[name=variation_id]').val()
        if(this.variationID != 0){
            this.selectedVariation = this.productCardData.variations.find(variation => variation.id == this.variationID);
            if(this.selectedVariation === undefined){
                alert('Opps something went wrong.')
                return
            }

            this.stockStatus = this.selectedVariation.stock_status
            this.minQty = this.selectedVariation.min_qty
            this.maxQty = this.selectedVariation.max_qty

            this.variation.attributes = { ...this.selectedVariation.attributes }
            this.variation.variation_attr = ''
            
            // set empty attributes from the user selection
            for (let key in this.variation.attributes) {
                if (this.variation.attributes.hasOwnProperty(key)) {

                    let nativeSelectInput = document.querySelector('select[name="'+key+'"]')
                    this.variation.attributes[key] = nativeSelectInput.value
                    let selectedOption = nativeSelectInput.options[nativeSelectInput.selectedIndex]
                    if( this.variation.variation_attr !== ''){
                        this.variation.variation_attr += ', '
                    }
                    this.variation.variation_attr +=  selectedOption.text
                    
                }
            }
        }
        this.checkIfProductExistsInCart()
        this.enableDisableQtyButtons()
        this.getSelectedVariationPrice()
        this.updateProductSku()
        this.changeAddToCartButtonText()

        // create object for variation view item event
        let itemView = { ...this.selectedVariation }
        itemView.product_id = this.selectedVariation.id
        itemView.brand = this.productCardData.brand
        itemView.categories = this.productCardData.categories
        this.$dispatch('viewItem', { item: itemView})
    },
    async increaseQty(){
        if (this.increaseDisabled) {return}
        await this.addToCart()
    },
    async decreaseQty(){
        if (this.decreaseDisabled) {return} 
        await this.removeFromCart()
    },
    enableDisableQtyButtons(){

        if(Number(this.qty) > 0){
            this.showButtons = true
        }else{
            this.showButtons = false
        }

        if(this.stockStatus === 'outofstock'){
            this.qty = 0
            this.increaseDisabled = true
            this.decreaseDisabled = true
            return
        }
       
        if( this.maxQty == -1 || (Number(this.qty) < Number(this.maxQty)) ){ // max qty is -1 for unlimited by woocommerce
            this.increaseDisabled = false
        }else{
            this.increaseDisabled = true
            this.qty = this.maxQty
        }

        if( Number(this.qty) > 0 ){
            this.decreaseDisabled = false
        }else{
            this.decreaseDisabled = true
            if( Number(this.qty) < 0 ){
                this.qty = this.minQty
            }
        }
    },
    /**
     * 
     * change add cart button text
     */
    changeAddToCartButtonText(){
        if(this.stockStatus === 'outofstock'){
            this.addToCartText = wp.i18n.__('OUT OF STOCK', 'sage')
            return
        }
        this.addToCartText = this.variationID > 0 ? wp.i18n.__('ADD TO CART', 'sage') : wp.i18n.__('CHOOSE A VARIATION FIRST', 'sage')
    },
    /**
     * Check if product simple or variation exist in cart
     */
    async checkIfProductExistsInCart(){
        this.loading = true
        let ht_cart = localStorage.getItem('ht_cart')
        ht_cart = validateHTCart(ht_cart)

        let existingItem = Object.values(ht_cart.items).find(item => 
            item.product_id == this.productID && item.variation_id == this.variationID
        );

        if (existingItem) {
            this.qty = parseInt(existingItem.quantity)
        }else{
            this.qty = 0
        }
        this.loading = false
    },
    async addOneToCart(){
        if (this.increaseDisabled) {return}
        if(this.qty > 0){return}
        this.addToCart()
    },
    async addToCart(){
        if (this.increaseDisabled) {return}
        this.loading = true

        const button = this.$refs.button;
        const form = this.$refs.form;
        
        // Get product ID and variation ID
        this.productID = form.querySelector('input[name="product_id"]')?.value || button.value;
        this.variationID = form.querySelector('input[name="variation_id"]')?.value || 0;
        
        // Check if variation ID is required but not selected
        if (form.querySelectorAll('input[name="variation_id"]').length === 1 && this.variationID == 0) {
            document.getElementById('single-product-info').scrollIntoView({ behavior: "smooth" });
        
            mobiscroll.alert({
                title: 'Επιλέξτε μία παραλλαγή',
            });
        
            this.loading = false;
            return;
        }

        let ht_cart = localStorage.getItem('ht_cart')
        ht_cart = validateHTCart(ht_cart)

        let itemKey = generateCartId(this.productID, this.variationID, this.variation.attributes)
        let newItem = {
            "key" : itemKey,
            // TODO: data_hash array param should be = get_attribute_summary()
            "data_hash" : generateCartDataHash(this.productCardData.type, []),
            "product_id": parseInt(this.productID),
            "variation_id": parseInt(this.variationID),
            "quantity": parseInt(1),
            "max_qty": this.maxQty,
            "min_qty": this.minQty,
            "backorder_note": this.productCardData.backorder_note ?? '',
            "line_tax_data": {
                "subtotal": [],
                "total": []
            },
            "line_subtotal": ( parseFloat(this.productCardData.price) - parseInt(0) ) * parseInt(1), // price without tax
            "line_subtotal_tax": 0, // tax
            "line_total": ( parseFloat(this.productCardData.price) - parseInt(0) ) * parseInt(1), // price without tax
            "line_tax": 0, // tax
            "permalink": this.productCardData.permalink,
            "title": this.productCardData.title,
            "localization": this.productCardData.localization,
            "sku": this.productCardData.sku,
            "image_src": this.productCardData.image_src.woocommerce_thumbnail,
            "price": parseFloat(this.productCardData.price),
            "price_html": `<span class=\"woocommerce-Price-amount amount\"><bdi>${format_wc_price(parseFloat(this.productCardData.price).toFixed(2))}&nbsp;<span class=\"woocommerce-Price-currencySymbol\">&euro;</span></bdi></span>`,
            "regular_price": parseFloat(this.productCardData.regular_price),
            "messages": {
                "incrementDisabled": null,
                "decrementDisabled": null
            },

            // gtm4 required properties
            "brand": this.productCardData.brand,
            "categories": this.productCardData.categories
        }

        if(this.variationID != 0){

            newItem.variation = this.variation.attributes
            newItem.variation_attr = this.variation.variation_attr

            newItem.sku = this.selectedVariation.sku
            newItem.localization = this.selectedVariation.localization

            newItem.image_src = this.selectedVariation.image_src.woocommerce_thumbnail ?? this.productCardData.image_src.woocommerce_thumbnail
            newItem.permalink = this.selectedVariation.permalink

            newItem.min_qty = this.selectedVariation.min_qty
            newItem.max_qty = this.selectedVariation.max_qty
            newItem.backorder_note = this.selectedVariation.backorder_note
            newItem.line_subtotal = ( parseFloat(this.selectedVariation.price) - parseInt(0) ) * parseInt(1)  // price without tax,
            newItem.line_subtotal_tax = 0 // tax
            newItem.line_total = ( parseFloat(this.selectedVariation.price) - parseInt(0) ) * parseInt(1)  // price without tax,
            newItem.line_tax = 0 // tax
            newItem.price = parseFloat(this.selectedVariation.price)
            newItem.price_html =  `<span class=\"woocommerce-Price-amount amount\"><bdi>${format_wc_price(parseFloat(this.selectedVariation.price).toFixed(2))}&nbsp;<span class=\"woocommerce-Price-currencySymbol\">&euro;</span></bdi></span>`,
            newItem.regular_price = this.selectedVariation.regular_price
            
            newItem.data_hash = generateCartDataHash('variation',this.selectedVariation.variation_attr)
        }else{
            newItem.data_hash = generateCartDataHash(this.productCardData.type, [])
        }
        
        // Check if the item already exists in the cart
        if (ht_cart.items[itemKey]) {
            let new_product_qty = parseInt(ht_cart.items[itemKey].quantity) + parseInt(1)

            // calculate for existing items
            ht_cart.items[itemKey].quantity = new_product_qty
            ht_cart.items[itemKey].line_subtotal = ( parseFloat(newItem.price) - parseInt(0) ) * parseInt(new_product_qty)  // price without tax
            ht_cart.items[itemKey].line_subtotal_tax = 0
            ht_cart.items[itemKey].line_tax = 0
            ht_cart.items[itemKey].line_total = ( parseFloat(newItem.price) - parseInt(0) ) * parseInt(new_product_qty)  // price without tax
        } else {
            // push it with the specific key
            ht_cart.items[itemKey] = newItem;
        }

        // tag ht_cart as unsynced and save it
        ht_cart.isSynced = false
        localStorage.setItem('ht_cart', JSON.stringify(ht_cart));
        this.$dispatch('closeAllModals', {})
        this.$dispatch('htCartUpdated', { openCart: true })
        this.$dispatch('addToCart', { item: newItem, quantity: 1 })
        
        this.loading = false
    },
    async removeFromCart(){
        this.loading = true
        const button = this.$refs.button
        const form = this.$refs.form;

        // Get product ID and variation ID
        this.productID = form.querySelector('input[name="product_id"]')?.value || button.value;
        this.variationID = form.querySelector('input[name="variation_id"]')?.value || 0;
        
        // Check if variation ID is required but not selected
        if (form.querySelectorAll('input[name="variation_id"]').length === 1 && this.variationID == 0) {
            mobiscroll.alert({
                title: 'Επιλέξτε μία παραλλαγή',
            });
            return;
        }
    
        let ht_cart = localStorage.getItem('ht_cart')
        ht_cart = validateHTCart(ht_cart)

        let itemKey = generateCartId(this.productID, this.variationID, this.variation.attributes)
        // fetch again price for good measure ( we could use the ht_cart.items[itemKey].price)
        let newItem = {
            "price": parseFloat(this.productCardData.price),
        }
        if(this.variationID != 0){
            let variationData = this.productCardData.variations.find(variation => variation.id == this.variationID);
            if(variationData === undefined){
                alert('Opps something went wrong.')
                return
            }
            newItem.price = parseFloat(variationData.price)
        }

        // Check if the item already exists in the cart
        if (!ht_cart.items[itemKey]) {
            return;
        }

        //  create object for remove_from_cart event
        let itemToRemove = { ...ht_cart.items[itemKey] }

        let new_product_qty = parseInt(ht_cart.items[itemKey].quantity) - Number(1)
        if( parseInt(new_product_qty) <= 0){
            delete ht_cart.items[itemKey];
        }else{
            // calculate for existing items
            ht_cart.items[itemKey].quantity = new_product_qty
            ht_cart.items[itemKey].line_subtotal = ( parseFloat(newItem.price) - parseInt(0) ) * parseInt(new_product_qty) // price without tax,
            ht_cart.items[itemKey].line_subtotal_tax = 0
            ht_cart.items[itemKey].line_tax = 0
            ht_cart.items[itemKey].line_total = ( parseFloat(newItem.price) - parseInt(0) ) * parseInt(new_product_qty) // price without tax,
        }

        // tag ht_cart as unsynced and save it
        ht_cart.isSynced = false
        localStorage.setItem('ht_cart', JSON.stringify(ht_cart));
    
        this.$dispatch('closeAllModals', {})
        this.$dispatch('htCartUpdated', { openCart: true })
        this.$dispatch('removeFromCart', { item: itemToRemove, quantity: 1 })
        
        this.loading = false
    },
    /**
     * on variation change, get the new price and set it on the price html
     */
    getSelectedVariationPrice(){
        const variationPriceElement = document.querySelector('.woocommerce-variation-price ins bdi');
        const regularPriceElement = document.querySelector('.woocommerce-variation-price del bdi');
        const fallbackPriceElement = document.querySelector('.woocommerce-variation-price bdi');
        
        let newVariationPrice = variationPriceElement ? variationPriceElement.textContent.trim() : '';
        let newVariationPriceDel = regularPriceElement ? regularPriceElement.textContent.trim() : '';
        
        if (!newVariationPrice && fallbackPriceElement) {
            newVariationPrice = fallbackPriceElement.textContent.trim();
        }
        
        if (newVariationPrice) {
            const singleProductPrice = document.getElementById('js_single_product_price');
            if (singleProductPrice) {
                singleProductPrice.textContent = newVariationPrice;
            }
            
            const stickyAddToCart = document.getElementById('js_sticky_add_to_cart_product_price');
            if (stickyAddToCart) {
                stickyAddToCart.textContent = newVariationPrice;
            }
        }
        
        const singleProductSalePrice = document.getElementById('js_single_product_sale_price');
        if (singleProductSalePrice) {
            singleProductSalePrice.textContent = newVariationPriceDel || '';
        }
    },
    /**
     * update the product sku
     * save skus to component data to avoid multiple requests
     */
    updateProductSku(){
        let node = document.getElementById('js_variation_sku');
        if(!node){return}

        if(this.savedData.hasOwnProperty(this.variationID) && this.savedData[this.variationID].hasOwnProperty('sku')){
            node.innerHTML = this.savedData[this.variationID].sku
            return
        }

        let idForSku = this.variationID ? this.variationID : this.productID
        let requestData = {
            action: "get_variation_sku",
            nonce: ajax_callback_settings.ajax_nonce,
            variation_id : idForSku
        }
        postData(ajax_callback_settings.ajax_url, requestData).then(response => {
            if (response.success) {
                node.innerHTML = response.data.variation_sku
                // Save the data for future use
                if (!this.savedData[this.variationID]) {
                    this.savedData[this.variationID] = {};
                }
                this.savedData[this.variationID]['sku'] = response.data.variation_sku
            }
        })  
    },
    /**
     * Read the native attribute select inputs and create an object with the attribute name and the selected value ( if any )
     */
    createObjectOfAttributeInputs(){
        const variationsTable = document.querySelector(".variations");
        const selects = variationsTable.querySelectorAll("select");
        
        const selectedValues = {};
        
        for (let i = 0; i < selects.length; i++) {
            const select = selects[i];
            const name = select.getAttribute("name");
            const selectedOption = select.options[select.selectedIndex]?.value || "";
            selectedValues[name] = selectedOption;
        }

        this.attributeInputs = { ...selectedValues }       
    }
})
