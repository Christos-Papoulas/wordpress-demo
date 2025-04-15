import {postData} from "@scripts/utilities/helper.js";

export default ( { getSubcats, term, lang } ) => ({
    lang: lang,
    postType: 'product',
    mainTaxonomy: null,
    getSubcats: Boolean(parseInt(getSubcats)),
    filtersLoading: true,
    productsLoading: true,
    showShopSidebar: false,
    subcatsExpanded: false,
    wishlist: [],
    facetes: null,
    priceRangeMaxPrice: 0,
    activeFilters: {
    },
    pills: [],
    productsLayout: 1,
    products: [],
    productsPerPage: null,
    totalProducts: null,
    maxPage: 1,
    currentPage: 1,
    orderBy: 'price',
    schema:'',
    async init() {
        // force reload on back button. Ensures we have the latest data and removes animation of buttons
        // window.onpageshow = function(event) {
        //     if (event.persisted) {
        //         window.location.reload();
        //     }
        // };

        document.addEventListener('closeAllModals', (event) => {
            this.closeFaceteDrawer()
        });

        this.setProductsLayout()
        await this.maybeSetMainTaxonomy(term)
        await this.getFacetes()
        await this.parseUrl()
        // await this.getWishlist()
        await this.getProducts(this.currentPage)

    },
    async maybeSetMainTaxonomy(term){
        // check if we are in an archive page
        if (Boolean(term)) {
            term = JSON.parse(term)
            this.mainTaxonomy = {
                taxonomy: term.taxonomy,
                term: term,
            }
        }
    },
    async getFacetes(){
        //console.log('gettin facetes!')
        let requestData = {
            action: "get_facetes",
            lang: this.lang,
            nonce: ajax_callback_settings.ajax_nonce,
            archive_for: JSON.stringify(this.mainTaxonomy),
            get_subcats: this.getSubcats
        }
        await postData(ajax_callback_settings.ajax_url, requestData).then(response => {
            //console.log('got facetes!')
            //console.log(response.data)
            this.facetes = Object.values(response.data.facetes)
            this.priceRangeMaxPrice = Math.ceil(response.data.maxPrice)
            this.filtersLoading = false
        }).catch(error => {
            throw error
        })
    },
    async getWishlist(){
        //console.log('getting wishlist!')
        let requestData = {
            action: "get_wishlist",
            nonce: ajax_callback_settings.ajax_nonce,
        }
        await postData(ajax_callback_settings.ajax_url, requestData).then(response => {
            //console.log('got wishlist!')
            this.wishlist = Object.values(response.data.wishlist)
        }).catch(error => {
            throw error
        })
    },
    async getProducts(page = this.currentPage, paginate = false, orderBy = this.orderBy){
        //console.log('getting products!')

        let requestData = {
            action : "get_products_for_shop",
            lang: this.lang,
            nonce : ajax_callback_settings.ajax_nonce,
            archive_for: JSON.stringify(this.mainTaxonomy),
            active_filters : JSON.stringify(this.activeFilters),
            page : page,
            orderBy : orderBy
        }
        await postData(ajax_callback_settings.ajax_url, requestData).then(response => {
            //console.log('got products!')
            //console.log(response.data)
            this.currentPage = page
            this.maxPage = response.data.max_page
            this.totalProducts = response.data.total_products
            this.productsPerPage = response.data.postsPerPage
            if(paginate){
                // when we use load more we merge the products 
                this.products = [...this.products, ...Object.values(response.data.products)];
            }else{
                this.products = Object.values(response.data.products)
            }
            this.createSchema()
            this.productsLoading = false
        }).catch(error => {
            throw error
        });

    },
    async filterProducts(){
        //console.log(this.activeFilters)
        //when filter always set the page to 1
        this.currentPage = 1

        await this.createUrl()
        return this.getProducts()
    },
    async paginate(button){
        //console.log('time to paginate')
        button.querySelector('svg').classList.remove('hidden')
        this.currentPage++

        await this.createUrl()
        await this.getProducts(this.currentPage, true)
        button.querySelector('svg').classList.add('hidden')
    },
    async goToPage(page){
        this.currentPage = page
        await this.createUrl()
        window.location.href = window.location.href
    },
    async parseUrl(){
        //console.log('parsing url!')
       
        // Extracting specific property using map and checking for existence
        const validTaxonomies = this.facetes.map(facete => facete.taxonomy).filter(Boolean);
        //console.log(validTaxonomies); // Output: ['product_cat', 'pa_brand', 'pa_color' ... ]

        const urlParams = new URLSearchParams(window.location.search);
        const params = {
            price_range:{
                min: 0,
                max: 0
            }
        };
        
        // loop through all URL parameters
        for (const [key, value] of urlParams.entries()) {
            let strippedKey
            if(key == 'ht_page'){
                this.currentPage = value
            }else if(key == 'ht_orderby'){
                this.orderBy = value
            }else if(key == 'ht_min_price'){
                params.price_range.min = value
            }else if(key == 'ht_max_price'){
                params.price_range.max = value
            }else if(key == 'ht_on_sale'){
                strippedKey = key.replace(/^ht_/, '')
                params[strippedKey] = Boolean(value)
            }else{

                // remove the prefix from the key ( ht_ )
                // remove the suffix for the arrays ( [] )
                strippedKey = key.replace(/^ht_/, '').replace(/\[\]$/, '')
            
                //check if taxonomy param exists in our facetes. (Ignore all other params that are not ours.)
                if( validTaxonomies.includes(strippedKey) ){

                    // if the property does not exist create it
                    if(!params.hasOwnProperty('taxonomies')){
                        params.taxonomies = {}
                    }

                    // if the taxonomy does not exists as prop create it
                    if (!params.taxonomies.hasOwnProperty(strippedKey)) {
                        params.taxonomies[strippedKey] = []
                    }

                    const index = this.facetes.findIndex(obj => obj.taxonomy === strippedKey);

                    // if the taxonomy is in our facetes
                    if(index != -1){
                        const termIndex = this.facetes[index].terms.findIndex(term => term.slug === value);
                        //console.log(termIndex)
                        // if the term is in the array of taxonomy terms
                        if(termIndex != -1){
                            // add the value to the array for the stripped key
                            params.taxonomies[strippedKey].push(this.facetes[index].terms[termIndex])
                        }
                    }
                    
                }

            }

        }

        // remove price range if conditions
        if(params.price_range.min < 0 || params.price_range.max <= 0 || (params.price_range.max < params.price_range.min) ){
            delete params.price_range
        }

        //console.log('parsed!');
        this.activeFilters = params
        //console.log(this.activeFilters);
    },
    async createUrl(){
        
        let url = window.location.href
        url = new URL(url)
        
        const params = this.activeFilters
        
        // loop through all active Filters
        const filters = Object.keys(params)
        .map(key => {
            if(key == 'price_range'){
                if( Number(params[key].max) > 0 && Number(params[key].max) >= Number(params[key].min)){
                    return [
                        `${encodeURIComponent('ht_min_price')}=${encodeURIComponent(params[key].min)}`,
                        `${encodeURIComponent('ht_max_price')}=${encodeURIComponent(params[key].max)}`
                    ].join('&'); 
                }
            }else if(key == 'on_sale'){
                if(params[key]){ return `${encodeURIComponent('ht_' + key)}=${encodeURIComponent(params[key])}` }
            }else if(key == 'taxonomies'){

                let taxs = []
                let taxonomies = params[key]

                for (const taxonomyKey in taxonomies) {
                    for (const termKey in taxonomies[taxonomyKey]) {         
                        taxs.push(`${encodeURIComponent('ht_' + taxonomyKey)}[]=${encodeURIComponent(taxonomies[taxonomyKey][termKey].slug)}`)
                    }                     
                }
                return taxs.join('&')

            }
        }).join('&')
        
        // always have page and orderby in params. We dont actually need it if page = 1 and orderBy = 'default'.
        let array = [
            `${encodeURIComponent('ht_page')}=${encodeURIComponent(this.currentPage)}`,
            `${encodeURIComponent('ht_orderby')}=${encodeURIComponent(this.orderBy)}`,
        ]
        // console.log(array)
        // if we have active filters
        if(filters !== ''){
            array.push(filters)
        }

        const searchParams = '?' + array.join('&');

        // console.log(searchParams)
        url.search = searchParams 
        window.history.replaceState({'search_params': searchParams}, "", url);
    },
    async clearFilters(){
        this.activeFilters = {}

        // for (let property in this.activeFilters) {
        //     if (this.activeFilters.hasOwnProperty(property)) {
        //       delete this.activeFilters[property]
        //     }
        // }

        // uncheck all checkboxes,radios and range inputs.
        // maybe we can do this with x-model on html
        const myDiv = this.$refs.shopSidebar
        var inputs = myDiv.getElementsByTagName("input")

        for (var i = 0; i < inputs.length; i++) {
            var input = inputs[i]
            if (input.type === "checkbox" || input.type === "radio") {
                input.checked = false
            }
        }

        return this.filterProducts()
    },
    calculateTaxonomy(taxonomy,term){
        if(!this.activeFilters.hasOwnProperty('taxonomies') ){
            this.activeFilters.taxonomies = {}
        }

        if(this.activeFilters.taxonomies.hasOwnProperty(taxonomy) ){
 
            // toggle term
            let index = this.activeFilters.taxonomies[taxonomy].indexOf(term)
            if(index == -1){
                this.activeFilters.taxonomies[taxonomy].push(term)
            }else{
                this.activeFilters.taxonomies[taxonomy].splice(index, 1);
            }

            // remove property if terms array is empty
            if(this.activeFilters.taxonomies[taxonomy].length == 0){
                delete this.activeFilters.taxonomies[taxonomy]
                // remove property if taxomonies are empty
                if(Object.keys(this.activeFilters.taxonomies).length === 0){
                    delete this.activeFilters.taxonomies
                }
            }
        }else{
            this.activeFilters.taxonomies[taxonomy] = [term]
        }

        //console.log(this.activeFilters)
        this.filterProducts()
    },
    calculatePriceRange(){
        const minPriceInput = document.getElementById('price-range-min-price')
        const minPriceLabel = document.getElementById('price-range-min-label')
        const maxPriceInput = document.getElementById('price-range-max-price')
        const maxPriceLabel = document.getElementById('price-range-max-label')

        let minPrice = minPriceInput.value
        let maxPrice = maxPriceInput.value

        if(Number(maxPrice) < Number(minPrice)){ 
            maxPrice = minPrice 
            maxPriceInput.value = maxPrice
        }

        maxPriceLabel.innerText = maxPrice
        minPriceLabel.innerText = minPrice
        this.activeFilters['price_range'] = {
            'min' : minPrice,
            'max' : maxPrice
        }
        // remove price range filter if both values are 0
        if(this.activeFilters.price_range.min == 0 && this.activeFilters.price_range.max == 0){
            delete this.activeFilters.price_range
        }
        
        //console.log(this.activeFilters)
        this.filterProducts()
    },
    calculateSwitch(key){
        if(this.activeFilters.hasOwnProperty(key) && this.activeFilters[key]){
            delete this.activeFilters[key]
        }else{
            this.activeFilters[key] = true
        }
        //console.log(this.activeFilters)
        this.filterProducts()
    },
    calculateOrderBy(selectInput){
        this.orderBy = selectInput.value
        //console.log(this.activeFilters)
        this.filterProducts()
    },
    removeActiveFilterByKey(key){
        //console.log(key)
        if(key == 'price_range'){
            document.getElementById('price-range-min-price').value = 0
            document.getElementById('price-range-max-price').value = 0
            this.calculatePriceRange()
        }else if(key == 'on_sale'){
            document.querySelector('#on_sale').checked = false;
            this.calculateSwitch('on_sale')
        }else{
            delete this.activeFilters[key]
        }
        //console.log(this.activeFilters)
    },
    removeActiveFilterTaxonomyTerm(taxonomy, term){
        // console.log(taxonomy)
        // console.log(term)

        document.querySelector('#'+taxonomy+'-'+term.term_id).checked = false;

        const index = this.activeFilters.taxonomies[taxonomy].findIndex(obj => obj === term)
        if(index != -1){
            this.activeFilters.taxonomies[taxonomy].splice(index, 1);
            // remove property if terms array is empty
            if(this.activeFilters.taxonomies[taxonomy].length == 0){
                delete this.activeFilters.taxonomies[taxonomy]
                // remove property if taxomonies are empty
                if(Object.keys(this.activeFilters.taxonomies).length === 0){
                    delete this.activeFilters.taxonomies
                }
            }
        }
        this.filterProducts()
        // console.log(this.activeFilters)
    },
    toggleFacetesDrawer(){
        if (!this.showShopSidebar) {
            this.$dispatch('closeAllModals', {})
            this.showShopSidebar = true
            document.body.classList.add('body-no-scroll')
            document.getElementById('backdrop').classList.remove('hidden')
        } else {
            this.showShopSidebar = false
            document.body.classList.remove('body-no-scroll')
            document.getElementById('backdrop').classList.add('hidden')
        }
    },
    closeFaceteDrawer(){
        this.showShopSidebar = false
        document.body.classList.remove('body-no-scroll')
        document.getElementById('backdrop').classList.add('hidden')
    },
    setProductsLayout(){
        let layout = localStorage.getItem('shop-layout');
        if(layout != undefined && layout != null){
            this.changeProductsLayout(layout);
        }
    },
    changeProductsLayout(layout){
        // change layout
        this.$refs.shopContainer.dataset.layout = layout

        // save to local storage
        localStorage.setItem('shop-layout',layout);

        // button's classes
        const togglers = document.querySelectorAll(".shop-grid-toggler")
        togglers.forEach((element) => {
            element.classList.remove('active');
        });
        document.querySelector(".shop-grid-toggler.layout-"+layout)?.classList.add('active')
    },
    createSchema(){
        let str = '';
        this.products.forEach(product => {
            let brand = null
            if(product.pa_brand.length >0){ brand = product.pa_brand[0] }

            str += `
            <script type="application/ld+json">
            {
                "@context": "https://schema.org/",
                "@type": "Product",
                "name": "${product.name.replace(/"/g, '\\"')}",
                "image": [
                  "${product.image}",
                  "${product.image_2}"
                ],
                "description": "${product.short_description.replace(/"/g, '\\"')}",
                "sku": "${product.sku.replace(/"/g, '\\"')}",
            `;
            if(product.mpn !== null){
                str += `
                "mpn": "${product.mpn.replace(/"/g, '\\"')}",`
            }
            if(brand !== null){
                str += `
                "brand": {
                    "@type": "Brand",
                    "name": "${brand.replace(/"/g, '\\"')}"
                },`;
            }

                str += `
                "offers": {
                    "@type": "Offer",
                    "url": "${product.url}",
                    "priceCurrency": "EUR",
                    "price": ${product.price},
                    "priceValidUntil": "2100-11-20",
                    "itemCondition": "https://schema.org/NewCondition",
                    "availability": "https://schema.org/InStock"
                }
            }
            </script>`;
        });
        this.schema = str
    }
})
