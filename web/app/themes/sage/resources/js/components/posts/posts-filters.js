import {postData} from "@scripts/utilities/helper.js";

export default ( { postType, getSubcats, term, lang } ) => ({
    lang: lang,
    postType: postType,
    mainTaxonomy: null,
    getSubcats: Boolean(parseInt(getSubcats)),
    filtersLoading: true,
    postsLoading: true,
    facetes: null,
    activeFilters: {
    },
    posts: [],
    postsPerPage: null,
    totalPosts: null,
    maxPage: 1,
    currentPage: 1,
    orderBy: 'default',
    schema:'',
    async init() {
        await this.setup()
        await this.getFacetes()
        await this.parseUrl()
        await this.getPosts(this.currentPage)
        this.addCollapseEvents()
    },
    async setup(){
        // check if we are in an archive page
        if(Boolean(term)){
            term = JSON.parse(term);
            this.mainTaxonomy = {
                taxonomy : term.taxonomy,
                term : term,
            }
        }
    },
    async getFacetes(){
        //console.log('gettin facetes!')
        let requestData = {
            action: "get_facetes_for_posts",
            lang: this.lang,
            nonce: ajax_callback_settings.ajax_nonce,
            post_type: this.postType,
            archive_for: JSON.stringify(this.mainTaxonomy),
            get_subcats: this.getSubcats
        }
        await postData(ajax_callback_settings.ajax_url, requestData).then(response => {
            // console.log('got facetes!')
            // console.log(response.data)
            this.facetes = Object.values(response.data.facetes)
            this.priceRangeMaxPrice = Math.ceil(response.data.maxPrice)
            this.filtersLoading = false
        }).catch(error => {
            reject('facetes error' + error);
        })
    },
    async getPosts(page = this.currentPage, paginate = false){
        //console.log('getting posts!')
        let requestData = {
            action : "get_posts_alpine",
            lang: this.lang,
            nonce : ajax_callback_settings.ajax_nonce,
            archive_for: JSON.stringify(this.mainTaxonomy),
            post_type: this.postType,
            active_filters : JSON.stringify(this.activeFilters),
            page : page,
            orderBy : this.orderBy
        }
        await postData(ajax_callback_settings.ajax_url, requestData).then(response => {
            //console.log('got posts!')
            //console.log(response.data)
            this.currentPage = page
            this.maxPage = response.data.max_page
            this.totalPosts = response.data.total_posts
            this.postsPerPage = response.data.postsPerPage
            if(paginate){
                // when we use load more we merge the posts 
                this.posts = [...this.posts, ...Object.values(response.data.posts)];
            }else{
                this.posts = Object.values(response.data.posts)
            }
            this.postsLoading = false
            this.createSchema()
        }).catch(error => {
            reject('posts' + error);
        });
    },
    async filterPosts(){
        //console.log(this.activeFilters)
        //when filter always set the page to 1
        this.currentPage = 1
        await this.createUrl()
        return this.getPosts()
    },
    async paginate(button){
        //console.log('time to paginate')
        button.querySelector('svg').classList.remove('hidden')
        this.currentPage++
        await this.createUrl()
        await this.getPosts(this.currentPage, true)
        button.querySelector('svg').classList.add('hidden')   
    },
    async parseUrl(){
        //console.log('parsing url!')
        
        // Extracting specific property using map and checking for existence
        const validTaxonomies = this.facetes.map(facete => facete.taxonomy).filter(Boolean);
        //console.log(validTaxonomies); // Output: ['product_cat', 'pa_brand', 'pa_color' ... ]

        const urlParams = new URLSearchParams(window.location.search);
        const params = {};
        
        // loop through all URL parameters
        for (const [key, value] of urlParams.entries()) {
            let strippedKey
            if(key == 'ht_page'){
                this.currentPage = value
            }else if(key == 'ht_orderby'){
                this.orderBy = value
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
            if(key == 'taxonomies'){

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
        //       delete this.activeFilters[property];
        //     }
        // }

        // uncheck all checkboxes,radios and range inputs.
        // maybe we can do this with x-model on html
        const myDiv = this.$refs.postsSidebar
        var inputs = myDiv.getElementsByTagName("input")
        
        for (var i = 0; i < inputs.length; i++) {
            var input = inputs[i];
            if (input.type === "checkbox" || input.type === "radio") {
            input.checked = false;
            }
        }

        return this.filterPosts()
    },
    addCollapseEvents(){
        const elements = document.querySelectorAll('.facete-heading')
        elements.forEach(element => {
        element.addEventListener('click', event => {
            let wrapper = event.target.closest('.facete-wrapper')
            let heading = wrapper.querySelector('.facete-heading')

            var x = heading.getAttribute("aria-expanded")
            if (x == "true") 
            {
                x = "false"
            } else {
                x = "true"
            }
            heading.setAttribute("aria-expanded", x)

            wrapper.querySelector('.facete-heading').setAttribute('aria-expanded', 'true')
            wrapper.querySelector('.facete-heading > button > svg').classList.toggle('rotate-180')
            wrapper.querySelector('.facete-body').classList.toggle('hidden')
            })
        });
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
        this.filterPosts()
    },
    calculateOrderBy(selectInput){
        this.orderBy = selectInput.value
        //console.log(this.activeFilters)
        this.filterPosts()
    },
    calculateSearchInput(searchInput){
        let value = searchInput.value;
        // console.log(value)
        if(value == '' || value === undefined || value === null){
            delete this.activeFilters.searchfor
        }else{
            this.activeFilters.searchfor = value
        }
        // console.log(this.activeFilters)
        this.filterPosts()
    },
    removeActiveFilterByKey(key){
        //console.log(key)
        delete this.activeFilters[key]
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
        this.filterPosts()
        // console.log(this.activeFilters)
    },
    toggleFacetesDrawer(){
        this.$refs.postsSidebar.classList.toggle('hidden')
    },
    closeFaceteDrawer(){
        this.$refs.postsSidebar.classList.add('hidden')
    },
    createSchema(){
        let str = '';
        if(this.postType == 'post'){
            this.posts.forEach(post => {
                str += `
                <script type="application/ld+json">
                {
                    "@context": "https://schema.org",
                    "@type": "Article",
                    "mainEntityOfPage": {
                        "@type": "WebPage",
                        "@id": "${post.url}"
                    },
                    "headline": "${post.name.replace(/"/g, '\\"')}",
                    "image": "${post.image}",
                    "datePublished": "${post.datePublished}",
                    "dateModified": "${post.dateModified}",
                    "author": {
                        "@type": "Organization",
                        "name": "${global_app_data.app_name}",
                        "url": "${global_app_data.home_url}"
                    },
                    "publisher": {
                        "@type": "Organization",
                        "name": "${global_app_data.app_name}",
                        "url": "${global_app_data.home_url}",
                        "logo": {
                            "@type": "ImageObject",
                            "url": "${global_app_data.logo.url}",
                            "width": ${global_app_data.logo.width},
                            "height": ${global_app_data.logo.height}
                        }
                    },
                    "description": "${post.excerpt.replace(/"/g, '\\"')}"
                }
                </script>`
            });
        }
        this.schema = str
    },
    chunkedPosts(posts, size) {
        const chunked = [];
        for (let i = 0; i < posts.length; i += size) {
            chunked.push(posts.slice(i, i + size));
        }
        return chunked;
    }
})
