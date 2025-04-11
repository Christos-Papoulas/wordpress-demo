/**
 * Product List Component. Used to push the list of products to the data layer.
 * 
 */
export default ( { list_id, list_name } ) => ({
    list_id: list_id,
    list_name: list_name,
    init() {
        setTimeout(() => {
            let nodes = this.$root.querySelectorAll('.product-card')
            let items = Array.from(nodes).map((el) => el._x_dataStack[0].productCardData );
            this.$dispatch('viewItemList', { list_id: list_id, list_name: list_name, items: items });
        }, 1000)
    }
})
