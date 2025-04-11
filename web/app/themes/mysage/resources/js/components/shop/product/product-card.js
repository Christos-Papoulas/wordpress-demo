import {generateCartId} from "@scripts/utilities/helper.js";
import {generateCartDataHash} from "@scripts/utilities/helper.js";
import { format_wc_price } from "@scripts/utilities/helper.js"
import { validateHTCart } from "@scripts/components/shop/cart/helpers.js"

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
    addToCartText: '',
    decreaseDisabled:true,
    increaseDisabled:true,
    selectedOptions: {},
    featuredImageToDisplay: productCardData.image_src.woocommerce_single,
    init() {
            
        this.stockStatus = productCardData.stock_status
        this.minQty = productCardData.min_qty
        this.maxQty = productCardData.max_qty
        this.productID = productCardData.product_id
        this.productType = productCardData.type

        if(this.productType === 'variable'){
            this.createSelectedOptionsObject()
        }

        this.checkIfProductExistsInCart()
        this.enableDisableQtyButtons()
        this.changeAddToCartButtonText()
        this.$watch('qty', () => this.enableDisableQtyButtons())

        document.addEventListener('htCartUpdated', (event) => {
            this.checkIfProductExistsInCart()
            this.enableDisableQtyButtons()
        });

        this.$watch('selectedOptions', () => {
            this.maybeFindVariation()
        })

        this.$watch('variationID', () => {
            this.featuredImageToDisplay = this.selectedVariation.image_src.woocommerce_single
        })
    },
    createSelectedOptionsObject(){
        if(!this.productCardData.hasOwnProperty('attributes_and_options')){ return }
        for (let i = 0; i < this.productCardData.attributes_and_options.length; i++) {
            const attr = this.productCardData.attributes_and_options[i];
            this.selectedOptions[attr.name] = null;
        }
    },
    changeSelectedOptionOfAttribute(attr_name, slug, attr_display_type){
        this.selectedOptions[attr_name] = slug
        if(attr_display_type === 'color'){
           this.findFeaturedImageFromColorAttr(attr_name, slug)
        }
    },
    async findFeaturedImageFromColorAttr(attr_name, slug){
        const variation = this.productCardData.variations.find(variation => 
            variation.attributes['attribute_' + attr_name] == slug
        );
        this.featuredImageToDisplay = variation.image_src.woocommerce_single
    },
    async maybeFindVariation(){

        // Check if any selected option is null
        for (const key in this.selectedOptions) {
            if (this.selectedOptions[key] === null) {
                return
            }
        }

        // Format selected options into WooCommerce attribute keys
        const formattedSelected = {};
        for (const key in this.selectedOptions) {
            formattedSelected[`attribute_${key}`] = this.selectedOptions[key];
        }
        // console.log(formattedSelected)
        // console.log(this.productCardData.variations)
        // Find matching variation
        for (let i = 0; i < productCardData.variations.length; i++) {
            const variation = productCardData.variations[i];
            const attrs = variation.attributes;
        
            let isMatch = true;
            for (const key in formattedSelected) {
                const selectedValue = formattedSelected[key];
                const variationValue = attrs[key];
        
                // If variation's value is not empty and does not match the selected, it's not a match.
                // in woocommerce empty string represents "any" term, for example color=blue, size=any
                if (variationValue !== '' && variationValue !== selectedValue) {
                    isMatch = false;
                    break;
                }
            }

            if (isMatch) {
                this.variationID = variation.id
                this.selectedVariation = variation

                this.stockStatus = this.selectedVariation.stock_status
                this.minQty = this.selectedVariation.min_qty
                this.maxQty = this.selectedVariation.max_qty
    
                this.variation.attributes = { ...this.selectedVariation.attributes }
                this.variation.variation_attr = ''

                for (let key in this.variation.attributes) {
                    if (this.variation.attributes.hasOwnProperty(key)) {
                        const taxonomy = key.replace('attribute_', '')
                        const term = this.findTerm(taxonomy, this.selectedOptions[taxonomy])

                        // very important line.
                        // this.variation.attributes will be used for cart item key.
                        // this line is to fill empty attribute options ( when variation has an attribute as any )
                        this.variation.attributes[key] = term.slug

                        if( this.variation.variation_attr !== ''){
                            this.variation.variation_attr += ', '
                        }
                        this.variation.variation_attr +=  term.name
                    }
                }

                this.checkIfProductExistsInCart()
                this.enableDisableQtyButtons()
                this.getSelectedVariationPrice()
                this.changeAddToCartButtonText()
            }
        }

    },
    getSelectedVariationPrice(){
        this.productCardData.price_html = this.selectedVariation.price_html
    },
    findTerm(taxonomy, term_slug) {
    
        for (let i = 0; i < this.productCardData.attributes_and_options.length; i++) {
            const attribute = this.productCardData.attributes_and_options[i];
            if (attribute.name === taxonomy) {
                for (let j = 0; j < attribute.options.length; j++) {
                    const option = attribute.options[j];
                    if (option.slug === term_slug) {
                        return option;
                    }
                }
            }
        }
    
        return false
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
        let button = this.$refs.addToCartBtnText;
        if(!button){ return }
        if(this.stockStatus === 'outofstock'){
            this.addToCartText = button.dataset.outofstock
            return
        }
   
        if(this.productType === 'variable'){
            this.addToCartText = this.variationID > 0 ? button.dataset.addtext : button.dataset.choosetext
        }else{
            this.addToCartText = button.dataset.addtext
        }

        // if(!this.showButtons && (this.stockStatus === 'outofstock' || this.increaseDisabled)){
        //     this.addToCartText = wp.i18n.__('OUT OF STOCK', 'sage')
        //     return
        // }
   
        // if(this.productType === 'variable'){
        //     this.addToCartText = this.variationID > 0 ? wp.i18n.__('ADD TO CART', 'sage') : wp.i18n.__('CHOOSE A VARIATION', 'sage')
        // }else{
        //     this.addToCartText = wp.i18n.__('ADD TO CART', 'sage')
        // }
    },
    async checkIfProductExistsInCart(){
        this.loading = true
        let ht_cart = localStorage.getItem('ht_cart')
        ht_cart = validateHTCart(ht_cart)

        let itemKey = generateCartId(this.productID, this.variationID, this.variation.attributes)
        if (ht_cart.items[itemKey]) {
            this.qty = parseInt(ht_cart.items[itemKey].quantity)
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
        this.loading = true
   
        let ht_cart = localStorage.getItem('ht_cart')
        ht_cart = validateHTCart(ht_cart)

        let itemKey = generateCartId(this.productID, this.variationID, this.variation.attributes)
        let newItem = {
            "key" : itemKey,
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

        // create object for add_to_cart event
        let itemToAdd = { ...newItem }
        if(this.productCardData.hasOwnProperty('list_id') && this.productCardData.list_id !== null){
            itemToAdd.list_id = this.productCardData.list_id
        }
        if(this.productCardData.hasOwnProperty('list_name') && this.productCardData.list_name !== null){
            itemToAdd.list_name = this.productCardData.list_name
        }
        this.$dispatch('addToCart', { item: itemToAdd, quantity: 1 })

        this.loading = false
    },
    async removeFromCart(){
        this.loading = true

        let ht_cart = localStorage.getItem('ht_cart')
        ht_cart = validateHTCart(ht_cart)

        let itemKey = generateCartId(this.productID, this.variationID, this.variation.attributes)
        // fetch again price for good measure ( we could use the ht_cart.items[itemKey].price)
        let newItem = {
            "price": parseFloat(this.productCardData.price),
        }

        // Check if the item already exists in the cart
        if (!ht_cart.items[itemKey]) {
            return;
        }

        //  create object for remove_from_cart event
        let itemToRemove = { ...ht_cart.items[itemKey] }
        if(this.productCardData.hasOwnProperty('list_id') && this.productCardData.list_id !== null){
            itemToRemove.list_id = this.productCardData.list_id
        }
        if(this.productCardData.hasOwnProperty('list_name') && this.productCardData.list_name !== null){
            itemToRemove.list_name = this.productCardData.list_name
        }

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
})
