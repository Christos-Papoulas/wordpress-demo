export default () => ({
    itemsCount:0,
    totalFormatted: 0,
    localStorageCart: {},
    init() {
        this.updateCount()
        // this.updateTotal()
        this.addEventListeners()
    },
    addEventListeners() {
        document.addEventListener('htCartUpdated', (event) => {
            this.updateCount()
            // this.updateTotal()
        });
    },
    updateCount(){
       
        let ht_cart = localStorage.getItem('ht_cart')
        if(ht_cart === undefined || ht_cart === null){
            ht_cart = {
                isSynced : false,
                items : {}
            }
        }else{
            ht_cart = JSON.parse(ht_cart)
        }

        // save the object
        this.localStorageCart = ht_cart

        // total price
        let itemsCount = Object.values(this.localStorageCart.items).reduce((total, item) => {
            return total + parseInt(item.quantity)
        }, 0)

        this.itemsCount = itemsCount
    },
    updateTotal(){
       
        let ht_cart = localStorage.getItem('ht_cart')
        if(ht_cart === undefined || ht_cart === null){
            ht_cart = {
                isSynced : false,
                items : {}
            }
        }else{
            ht_cart = JSON.parse(ht_cart)
        }

        // save the object
        this.localStorageCart = ht_cart

        // total price
        let totalLineTotal = Object.values(this.localStorageCart.items).reduce((total, item) => {
            return total + ( parseFloat(item.line_total) + parseFloat(item.line_tax) )
        }, 0)

        this.totalFormatted = `<span class=\"woocommerce-Price-amount amount\"><bdi>${totalLineTotal.toFixed(2)}&nbsp;<span class=\"woocommerce-Price-currencySymbol\">&euro;</span></bdi></span>`
    }
})
