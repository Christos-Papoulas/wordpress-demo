import {postData} from "@scripts/utilities/helper.js";

export default () => ({
    parentProductID: 0,
    productID: 0,
    isOpen: true,
    loading: true,
    stores: [],
    savedData: [],
    buttons: [],
    init() {
        //TODO: refactor without dataset. Pass the post_id as a parameter
        this.buttons = document.getElementsByClassName('js-show-store-stock-status-cta')
        this.productID = this.$refs.container.dataset.pid
        this.parentProductID = this.productID
        this.getStoreStockStatus(this.productID)

        jQuery(".single_variation_wrap").on("show_variation", (event, variation) => {
            // Fired when the user selects all the required dropdowns / attributes
            // and a final variation is selected / shown
            for(let i = 0; i < this.buttons.length; i++) {
                this.buttons[i].disabled = false;
            }
            this.productID = variation.variation_id
            this.getStoreStockStatus(variation.variation_id)
        });

        document.addEventListener('refreshStoreStockStatus', (event) => {
            this.productID = event.detail.productID
            this.getStoreStockStatus(event.detail.productID)
        });

        document.addEventListener('showPopUpOfStoreStockStatus', (event) => {
            document.body.classList.add('body-no-scroll')
            document.getElementById('backdrop').classList.remove('hidden')
            this.showPopUp()
        });
        document.addEventListener('closeAllModals', (event) => {
            document.body.classList.remove('body-no-scroll')
            document.getElementById('backdrop').classList.add('hidden')
            this.hidePopUp()
        });
        
        document.addEventListener('variationsReseted', (event) => {
            for(let i = 0; i < this.buttons.length; i++) {
                this.buttons[i].disabled = true;
            }
            this.productID = this.parentProductID
            this.getStoreStockStatus(this.productID)
        });
        
    },
    getStoreStockStatus(product_id){
        this.loading = true
        if(this.savedData.hasOwnProperty(product_id)){
            this.stores = this.savedData[product_id]
            this.loading = false
        }else{
            let requestData = {
                action: "get_product_store_stock_status",
                product_id: product_id,
                nonce: ajax_callback_settings.ajax_nonce
            }
            postData(ajax_callback_settings.ajax_url, requestData).then(response => {
                //console.log(response)
                this.stores = response.data.stores
                this.savedData[product_id] = response.data.stores
                this.loading = false
            })
        }   
    },
    showPopUp(){
        if(this.savedData.hasOwnProperty(this.productID)){
            this.stores = this.savedData[this.productID]
        }else{
            let requestData = {
                action: "get_product_store_stock_status",
                product_id: this.productID,
                nonce: ajax_callback_settings.ajax_nonce
            }
            postData(ajax_callback_settings.ajax_url, requestData).then(response => {
                // console.log(response)
                this.stores = response.data.stores
                this.savedData[this.productID] = response.data.stores
            })
        }

        const availableStores = []
        for(let i = 0; i < this.stores.length; i++) {
            if( this.stores[i].stock > 0){
                availableStores.push(this.stores[i])
            }
        }

        const storesContainer = document.getElementById('store-stock-modal-content')
        storesContainer.innerHTML = ''
        let storeDiv

        storeDiv = document.createElement('div')
        storeDiv.className = 'text-body text-2xl xl:text-3xl text-center w-full mb-8';
        storeDiv.innerHTML = availableStores.length > 0 ? `Το προϊόν είναι διαθέσιμο στα παρακάτω καταστήματα` : `Το προϊόν δεν είναι διαθέσιμο σε κανένα κατάστημα`;
        storesContainer.appendChild(storeDiv);

        if(availableStores.length > 0){
            storeDiv = document.createElement('div')
            storeDiv.className = 'hidden xl:grid grid-cols-4 gap-5 text-body mb-3';
            storeDiv.innerHTML = `
                <div class="text-left"><strong>Κατάστημα</strong></div>
                <div class="text-center"><strong>Διεύθυνση</strong></div>
                <div class="text-center"><strong>Τηλέφωνο</strong></div>
                <div class="text-center"><strong>Χάρτης</strong></div>
            `;
            storesContainer.appendChild(storeDiv);
    
            availableStores.forEach(store => {
                let storeDiv = document.createElement('div')
                storeDiv.className = 'w-full';
                storeDiv.innerHTML = `
                    <div x-data="{isOpen:false}" class="grid grid-cols-4 gap-x-5 py-2 text-xs xl:text-sm border-b border-b-body xl:border-b-0">
                        <div x-on:click="isOpen = !isOpen" class="col-span-4 xl:col-span-1 text-left flex justify-between items-center xl:block">
                            <span>${store.post_title}</span>
                            <svg :class="isOpen && '!rotate-90'" class="xl:hidden transform transition -rotate-90" width="14" height="8" viewBox="0 0 14 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13 0.999998L9.97196 4L6.94393 7L1 1" stroke="#212121" stroke-miterlimit="10"></path>
                            </svg>
                        </div>
                        <div x-cloak x-show="isOpen" class="col-span-4 xl:col-span-1 xl:!block xl:text-center mb-2 xl:mb-0 mt-2 xl:mt-0 flex xl:justify-center items-center">
                            <span class="font-bold xl:hidden mr-2">Διεύθυνση:</span>
                            <span>${store.address} ${store.address_number}</span>
                        </div>
                        <div x-cloak x-show="isOpen" class="col-span-4 xl:col-span-1 xl:!block xl:text-center mb-2 xl:mb-0 flex xl:justify-center items-center">
                            <span class="font-bold xl:hidden mr-2">Τηλέφωνο:</span>
                            <a class="!text-body" title="Call us" href="tel:${store?.phones[0]?.number}">${store?.phones[0]?.number}</a>
                        </div>
                        <div x-cloak x-show="isOpen" class="col-span-4 xl:col-span-1 xl:!block xl:text-center mb-2 xl:mb-0 flex xl:justify-center items-center">
                            <a href="https://www.google.com/maps/place/?q=place_id:${store.store_custom_fields_google_map_field.place_id}" target="_blank" title="${store.post_title}" class="!text-body flex xl:justify-center items-center">
                                <span class="font-bold xl:hidden mr-2">Δείτε το στον χάρτη</span>
                                <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
                                    <path d="M13.2428 12.4925L10.0605 15.6748C9.92133 15.8141 9.75608 15.9246 9.57417 16C9.39227 16.0754 9.19729 16.1142 9.00038 16.1142C8.80347 16.1142 8.60849 16.0754 8.42658 16C8.24468 15.9246 8.07942 15.8141 7.94025 15.6748L4.75725 12.4925C3.91817 11.6534 3.34675 10.5843 3.11527 9.42043C2.88378 8.25655 3.00262 7.05017 3.45676 5.95383C3.91089 4.85749 4.67993 3.92044 5.66661 3.26116C6.6533 2.60189 7.81333 2.25 9 2.25C10.1867 2.25 11.3467 2.60189 12.3334 3.26116C13.3201 3.92044 14.0891 4.85749 14.5433 5.95383C14.9974 7.05017 15.1162 8.25655 14.8847 9.42043C14.6533 10.5843 14.0818 11.6534 13.2428 12.4925V12.4925Z" stroke="#000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M10.591 9.84099C11.0129 9.41903 11.25 8.84674 11.25 8.25C11.25 7.65326 11.0129 7.08097 10.591 6.65901C10.169 6.23705 9.59674 6 9 6C8.40326 6 7.83097 6.23705 7.40901 6.65901C6.98705 7.08097 6.75 7.65326 6.75 8.25C6.75 8.84674 6.98705 9.41903 7.40901 9.84099C7.83097 10.2629 8.40326 10.5 9 10.5C9.59674 10.5 10.169 10.2629 10.591 9.84099Z" stroke="#000" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                            </a>
                        </div>
                    <div>
                `;
                storesContainer.appendChild(storeDiv);
            });
        }

        document.getElementById('store-stock-modal').classList.remove('hidden')
    },
    hidePopUp(){
        document.getElementById('store-stock-modal').classList.add('hidden')
    }
})
