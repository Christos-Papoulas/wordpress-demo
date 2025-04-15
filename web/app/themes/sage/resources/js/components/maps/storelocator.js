import {postData} from "@scripts/utilities/helper.js";
import { Loader } from "@googlemaps/js-api-loader"

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
    googleMap: null,
    GoogleMapsLoader: null,
    destination:{
        lat:null,
        lng:null
    },
    async init() {
        // check if we are in an archive page
        if(Boolean(term)){
            term = JSON.parse(term);
            this.mainTaxonomy = {
                taxonomy : term.taxonomy,
                term : term,
            }
        }

        this.GoogleMapsLoader = await this.initGoogleMapsLoader()

        await this.getFacetes()
        await this.parseUrl()
        await this.getPosts(this.currentPage)
        this.addCollapseEvents()
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
    async getPosts(page = this.currentPage, paginate = false, orderBy = this.orderBy){
        //console.log('getting posts!')
        let requestData = {
            action : "get_posts_alpine",
            lang: this.lang,
            nonce : ajax_callback_settings.ajax_nonce,
            archive_for: JSON.stringify(this.mainTaxonomy),
            post_type: this.postType,
            active_filters : JSON.stringify(this.activeFilters),
            page : page,
            orderBy : orderBy
        }
        await postData(ajax_callback_settings.ajax_url, requestData).then(response => {
            //console.log('got posts!')
            console.log(response.data)
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
        this.renderMap()
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
    async initGoogleMapsLoader(){
        const loader = new Loader({
            apiKey: import.meta.env.VITE_GOOGLE_MAPS_API_KEY,
            version: "weekly",
        })
        return loader
    },
    createAutocompleteInput(Places){
        // the center, defaultbounds are not necessary but are best practices to limit/focus search results
        const center = { lat: 34.082298, lng: -82.284777 }; 
        // Create a bounding box with sides ~10km away from the center point
        const defaultBounds = {
            north: center.lat + 0.1,
            south: center.lat - 0.1,
            east: center.lng + 0.1,
            west: center.lng - 0.1,
        };

        //this const will be the first arg for the new instance of the Places API

        const input = document.getElementById("google-autocomplete-input"); //binds to our input element

        //this object will be our second arg for the new instance of the Places API
        const options = {
        bounds: defaultBounds, //optional
        //types: ["establishment"], //optioanl
        componentRestrictions: { country: "gr" }, //limiter for the places api search
        fields: ["address_components", "geometry", "icon", "name"], //allows the api to accept these inputs and return similar ones
        strictBounds: false, //optional
        };

        // per the Google docs create the new instance of the import above. I named it Places.
        const autocomplete = new Places.Autocomplete(input, options);

        //console.log('autocomplete', autocomplete); //optional log but will show you the available methods and properties of the new instance of Places.

        //add the place_changed listener to display results when inputs change
        autocomplete.addListener('place_changed', () => {
            const place = autocomplete.getPlace(); //this callback is inherent you will see it if you logged autocomplete
            console.log('place', place);
            this.destination.lat = place.geometry.location.lat()
            this.destination.lng = place.geometry.location.lng()
        });
    },
    findNearest(){
        if(this.destination.lat === undefined || this.destination.lat === null || this.destination.lat == ''
            || this.destination.lng === undefined || this.destination.lng === null || this.destination.lng == ''
        ){
            mobiscroll.alert({
                title: 'Type an address',
            });
            return
        }
        let loader = this.GoogleMapsLoader
            loader.load().then(async () => {
            this.callDistanceMatrix(google)
        })
    },
    callDistanceMatrix(google){
        const service = new google.maps.DistanceMatrixService(); // instantiate Distance Matrix service
        
        let origins = []
        this.posts.forEach( location => {
               
            let lat = Number(location.store_custom_fields_google_map_field.lat)
            let lng = Number(location.store_custom_fields_google_map_field.lng)

            if(typeof lat !== 'number'){
                return
            }
            if(typeof lng !== 'number'){
                return
            }
            origins.push(new google.maps.LatLng(lat, lng));
            // origins.push(location.store_custom_fields_address);
            
        })
        //console.log(origins)

        const matrixOptions = {
            origins: origins,
            destinations: [new google.maps.LatLng(this.destination.lat, this.destination.lng)],
            travelMode: 'DRIVING',
            // transitOptions: TransitOptions,
            // drivingOptions: DrivingOptions,
            unitSystem: google.maps.UnitSystem.IMPERIAL,
            // avoidHighways: Boolean,
            // avoidTolls: Boolean,
        }

        // Call Distance Matrix service
        service.getDistanceMatrix(matrixOptions, (response, status) => {
            //console.log(response)
            if (status !== "OK") {
                alert("Error with distance matrix");
                return;
            }
            this.calculateDistance(response);
        });
    },
    // Callback function used to process Distance Matrix response
    calculateDistance(response){      
        let routes = response.rows;
        // let leastseconds = 86400; // 24 hours
        // let drivetime = "";
        // let closest = "";

        // Find closest store
        // for (let i=0; i<routes.length; i++) {

        //   let routeseconds = routes[i].elements[0].duration.value;
         
        //   if (routeseconds > 0 && routeseconds < leastseconds) {
        //     leastseconds = routeseconds; // this route is the shortest (so far)
        //     drivetime = routes[i].elements[0].duration.text; // hours and minutes
        //     closest = response.originAddresses[i]; // city name from destinations
        //   }
        // }

        // alert("The closest location is " + closest + " (" + drivetime + ")");
        
        // store the distance to the stores and the reorder the array
        for (let i=0; i<routes.length; i++) {
            let routeseconds = routes[i].elements[0].duration.value;
            this.posts[i].distance = routeseconds
        }

        // Sort in ascending order based on distance
        this.posts.sort((a, b) => a.distance - b.distance);

        // Sort in descending order based on distance
        // this.posts.sort((a, b) => b.distance - a.distance);

        // recenter map to the nearest store
        this.googleMap.setCenter({lat: Number(this.posts[0].store_custom_fields_google_map_field.lat), lng: Number(this.posts[0].store_custom_fields_google_map_field.lng)}); 
    },
    renderMap(){
        let loader = this.GoogleMapsLoader
        loader.load().then(async () => {

            const Places = await loader.importLibrary('places')
            this.createAutocompleteInput(Places)

            // this.callDistanceMatrix(google)

            const { Map } = await google.maps.importLibrary("maps")
            const { AdvancedMarkerElement } = await google.maps.importLibrary("marker")

            const YOUR_DEFAULT_LATITUDE = 39.0742
            const YOUR_DEFAULT_LONGITUDE = 21.8243
        
            const map = new Map(document.getElementById("locations-map"), {
            center: { lat: YOUR_DEFAULT_LATITUDE, lng: YOUR_DEFAULT_LONGITUDE },
            zoom: 6,
            mapId: "4504f8b37365c3d0",
            })

            this.googleMap = map

            // Level of detail you can expect to see at each zoom level
            // 1: World
            // 5: Landmass/continent
            // 10: City
            // 15: Streets
            // 20: Buildings
           
            this.posts.forEach( location => {
               
                let lat = Number(location.store_custom_fields_google_map_field.lat)
                let lng = Number(location.store_custom_fields_google_map_field.lng)
                if(typeof lat !== 'number'){
                    return
                }
                if(typeof lng !== 'number'){
                    return
                }

                const marker = new AdvancedMarkerElement({
                    map,
                    position: { lat : lat, lng : lng },
                    content: this.buildContent(location),
                    title: location.name,
                })
        
                marker.addListener("click", () => {
                    this.clickMarker(marker, location)
                })
               
            })
        })
        
    },
    buildContent(location) {
        const content = document.createElement("div")
        content.dataset.id = location.id
      
        content.classList.add('location-marker','relative')
        let icon

        icon = `
            <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
            <path d="M13.2428 12.4925L10.0605 15.6748C9.92133 15.8141 9.75608 15.9246 9.57417 16C9.39227 16.0754 9.19729 16.1142 9.00038 16.1142C8.80347 16.1142 8.60849 16.0754 8.42658 16C8.24468 15.9246 8.07942 15.8141 7.94025 15.6748L4.75725 12.4925C3.91817 11.6534 3.34675 10.5843 3.11527 9.42043C2.88378 8.25655 3.00262 7.05017 3.45676 5.95383C3.91089 4.85749 4.67993 3.92044 5.66661 3.26116C6.6533 2.60189 7.81333 2.25 9 2.25C10.1867 2.25 11.3467 2.60189 12.3334 3.26116C13.3201 3.92044 14.0891 4.85749 14.5433 5.95383C14.9974 7.05017 15.1162 8.25655 14.8847 9.42043C14.6533 10.5843 14.0818 11.6534 13.2428 12.4925V12.4925Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M10.591 9.84099C11.0129 9.41903 11.25 8.84674 11.25 8.25C11.25 7.65326 11.0129 7.08097 10.591 6.65901C10.169 6.23705 9.59674 6 9 6C8.40326 6 7.83097 6.23705 7.40901 6.65901C6.98705 7.08097 6.75 7.65326 6.75 8.25C6.75 8.84674 6.98705 9.41903 7.40901 9.84099C7.83097 10.2629 8.40326 10.5 9 10.5C9.59674 10.5 10.169 10.2629 10.591 9.84099Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>`
        
        content.innerHTML = `
        <div>
            ${icon}
        </div>
        `

        return content
    },
    clickMarker(markerView, location) {
        if (markerView.content.classList.contains("highlight")) {
            markerView.content.classList.remove("highlight")
            markerView.zIndex = null
        } else {
            markerView.content.classList.add("highlight")
            markerView.zIndex = 1
        }

        window.open(`https://www.google.com/maps/place/?q=place_id:${location.store_custom_fields_google_map_field.place_id}`)
        //window.open(`https://www.google.com/maps/place/${encodeURIComponent(Number(location.store_custom_fields_google_map_field.lat))},${encodeURIComponent(Number(location.store_custom_fields_google_map_field.lng))}`)
    }
})
