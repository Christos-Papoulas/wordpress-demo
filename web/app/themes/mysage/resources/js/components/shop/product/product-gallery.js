import {postData} from "@scripts/utilities/helper.js";
import {register} from 'swiper/element/bundle';

export default () => ({
    productID: 0,
    savedData: [],
    init() {
        // TODO: refactor without dataset. Pass the post_id as a parameter
        this.productID = document.getElementById('single-product-gallery').dataset.pid
        this.initializeMobileSlider()
        
        // jQuery(".single_variation_wrap").on("show_variation", (event, variation) => {
        //     // Fired when the user selects all the required dropdowns / attributes
        //     // and a final variation is selected / shown
        //     this.getVariationGallery()
        // });

         // TODO: js_attr_button deleted from the project
        // jQuery(document).on('click','.bvariation.display_color', (e) => { 
        
        //     let attribute_name = $(e.currentTarget).data('name');
        //     let attribute_value = $(e.currentTarget).data('value');

        //     this.searchVariationAndgetGallery(attribute_name, attribute_value)
        // });
        
        // do not reset the gallery, we reset variation when a not available color is clicked.
        // document.addEventListener('variations_reseted', (event) => {
        //     this.resetGallery()
        // });
        
    },
    searchVariationAndgetGallery(attribute_name, attribute_value){

        if(this.savedData.hasOwnProperty(attribute_name) && this.savedData[attribute_name].hasOwnProperty(attribute_value)){
            let data = this.savedData[attribute_name][attribute_value];
            let smallImageUrl = data.featured_img.full
            let largeImageUrl = data.featured_img.full
            let gallery = data.gallery
            this.updateGallery(smallImageUrl, largeImageUrl, gallery)
        }else{
            let requestData = {
                action: "search_variation_and_get_gallery",
                product_id: this.productID,
                attribute_name: attribute_name,
                attribute_value: attribute_value,
                nonce: ajax_callback_settings.ajax_nonce
            }
            postData(ajax_callback_settings.ajax_url, requestData).then(response => {
                //console.log(response)
                let smallImageUrl = response.data.featured_img.full
                let largeImageUrl = response.data.featured_img.full
                let gallery = response.data.gallery
    
                // Ensure the first level exists
                if (!this.savedData[attribute_name]) {
                    this.savedData[attribute_name] = {};
                }
                // Assign the value to the nested property
                this.savedData[attribute_name][attribute_value] = response.data;

                this.updateGallery(smallImageUrl, largeImageUrl, gallery)
            })
        }
    },
    resetGallery(){
        if(this.savedData.hasOwnProperty('default')){
            let data = this.savedData['default'];
            let smallImageUrl = data.featured_img
            let largeImageUrl = data.featured_img
            let gallery = data.gallery
            this.updateGallery(smallImageUrl, largeImageUrl, gallery)
        }else{     
            let requestData = {
                action: "get_product_gallery",
                product_id: this.productID,
                nonce: ajax_callback_settings.ajax_nonce
            }
            postData(ajax_callback_settings.ajax_url, requestData).then(response => {
                // console.log(response)
                let smallImageUrl = response.data.featured_img
                let largeImageUrl = response.data.featured_img
                let gallery = response.data.gallery

                // Assign the value to the nested property
                this.savedData['default'] = response.data;
                this.updateGallery(smallImageUrl, largeImageUrl, gallery)
            })
        }

    },
    getVariationGallery(){
        let variation_id = document.querySelector('input[name="variation_id"]').value;

        let requestData = {
            action: "get_variation_gallery",
            variation_id: variation_id,
            nonce: ajax_callback_settings.ajax_nonce
        }
        postData(ajax_callback_settings.ajax_url, requestData).then(response => {

            let smallImageUrl = response.data.featured_img.full
            let largeImageUrl = response.data.featured_img.full
            let gallery = response.data.gallery

            this.updateGallery(smallImageUrl, largeImageUrl, gallery)
        })
    },
    /**
     * Updates the content-single-product page gallery
     * 
     * @param {string} smallImageUrl 
     * @param {string} largeImageUrl 
     * @param {array} gallery 
     */
    updateGallery(smallImageUrl, largeImageUrl, gallery){

        // for sticky add to cart
        document.getElementById('js_sticky_add_to_cart_product_img').src = smallImageUrl

        let desktopHTML = `
            <div class="w-full h-full flex bg-white">
                <a class="MagicZoom flex aspect-[800/1000] bg-[#f4f2ee] object-cover"  title="" href="${largeImageUrl}"
                data-gallery="product-gallery" 
                data-options="variableZoom: true; transitionEffect: false; zoomOn: click; zoomPosition: inner; expand: off" 
                >
                    <img class="maybeAddMixBlend" src="${largeImageUrl}" alt=""/>
                </a>
            </div>`

        let mobilehtml = `<swiper-container init="false"><swiper-slide class="h-auto">
                <a class="MagicZoom flex aspect-[800/1000] bg-[#f4f2ee] object-cover"  title="" href="${largeImageUrl}"
                    data-gallery="product-gallery-mobile" 
                    data-options="variableZoom: true; transitionEffect: false; zoomOn: click; zoomPosition: inner; expand: off" 
                    >
                        <img class="maybeAddMixBlend" src="${largeImageUrl}" alt=""/>
                </a>
            </swiper-slide>`

        
        for (const src of gallery) {
            desktopHTML += `
                <div class="w-full h-full flex bg-white">
                    <a class="MagicZoom flex aspect-[800/1000] bg-[#f4f2ee] object-cover"  title="" href="${src}"
                    data-gallery="product-gallery" 
                    data-options="variableZoom: true; transitionEffect: false; zoomOn: click; zoomPosition: inner; expand: off" 
                    >
                        <img class="maybeAddMixBlend" src="${src}" alt=""/>
                    </a>
                </div>
            `
            mobilehtml += `
                <swiper-slide class="h-auto">
                    <a class="MagicZoom flex aspect-[800/1000] bg-[#f4f2ee] object-cover"  title="" href="${src}"
                        data-gallery="product-gallery-mobile" 
                        data-options="variableZoom: true; transitionEffect: false; zoomOn: click; zoomPosition: inner; expand: off" 
                        >
                            <img class="maybeAddMixBlend" src="${src}" alt=""/>
                    </a>
                </swiper-slide>`
        }

        mobilehtml += `</swiper-container><div class="swiper-pagination"></div>`

        // desktop
        this.$refs.desktopImagesContainer.innerHTML = desktopHTML
        // mobile slider
        this.$refs.gallerymobile.innerHTML = mobilehtml

        window.MagicZoom.refresh()

        this.initializeMobileSlider()
    },
    initializeMobileSlider(){
        register();
        let block =  document.getElementById("single-product-gallery");
        let swiperEl = block.querySelector('swiper-container')

        Object.assign(swiperEl, 
            {
            loop: false,
            slidesPerView: 1,
            allowTouchMove: true,
            // navigation: {
            //     prevEl: block.querySelector('.swiper-btn-prev'),
            //     nextEl: block.querySelector('.swiper-btn-next'),
            // },
            pagination: {
                el:  block.querySelector('.swiper-pagination'),
                clickable: true,
            },
                on: {
                    init() {
                        document.getElementById('gallery-mobile-placeholder')?.remove()
                        this.slides.forEach((element) => element.classList.remove('hidden'))      
                    },
                },
            }
        );
        swiperEl.initialize();
    }
})
