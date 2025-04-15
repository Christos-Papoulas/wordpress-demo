export function validateHTCart(ht_cart) {
    if (ht_cart === undefined || ht_cart === null) {
        ht_cart = {
            isSynced: false,
            version: import.meta.env.VITE_LOCAL_STORAGE_CART_VERSION,
            items: {}
        }
        return ht_cart
    } 

    ht_cart = JSON.parse(ht_cart)

    if(ht_cart.version === undefined || ht_cart.version !== import.meta.env.VITE_LOCAL_STORAGE_CART_VERSION){
        ht_cart = {
            isSynced: false,
            version: import.meta.env.VITE_LOCAL_STORAGE_CART_VERSION,
            items: {}
        }
    }
    return ht_cart
}
