import {postData} from "@scripts/utilities/helper.js";
import {register} from 'swiper/element/bundle';

export default () => ({
    init() {

        register();

        const blocks = document.getElementsByClassName("compare-product-slider-info");
        var slidersArr = []

        for(let index=0;index < blocks.length;index++){
            let block = blocks[index]
      
            // initialize all sliders
            let swiperEl = block.querySelector('swiper-container')

            // now we need to assign all parameters to Swiper element
            Object.assign(swiperEl, 
                {
                    loop: false,
                    slidesPerView: 2,
                    spaceBetween: 12,
                    allowTouchMove: false,
                    breakpoints: {
                        768: {
                            slidesPerView: 2,
                            spaceBetween: 12,
                        },
                        1024: {
                            slidesPerView: 3,
                            spaceBetween: 12,
                        },
                        1280: {
                            slidesPerView: 3,
                            spaceBetween: 12,
                        },
                    },
                    on: {
                        init() {
                            slidersArr.push(this)
                        },
                    },
                }
            );

            // and now initialize it
            swiperEl.initialize();
        }

        const mainSliderBlock = document.getElementById("compare-product-slider-main");
        const mainSlider = mainSliderBlock.querySelector('swiper-container')

        Object.assign(mainSlider, 
            {
                loop: false,
                slidesPerView: 2,
                spaceBetween: 12,
                allowTouchMove: true,
                navigation: {
                    prevEl: mainSliderBlock.querySelector('.swiper-btn-prev'),
                    nextEl: mainSliderBlock.querySelector('.swiper-btn-next'),
                },
                breakpoints: {
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 12,
                    },
                    1024: {
                        slidesPerView: 3,
                        spaceBetween: 12,
                    },
                    1280: {
                        slidesPerView: 3,
                        spaceBetween: 12,
                    },
                },
                on: {
                    init() {
                        this.controller.control = slidersArr;
                    },
                },
            }
        );
        mainSlider.initialize();

    },
    changeList(){

        let url = window.location.href
        url = new URL(url)
        
        let array = [
            `${encodeURIComponent('ht_list')}=${encodeURIComponent(this.$refs.compareListSelectInput.value)}`,
        ]
        const searchParams = '?' + array.join('&');
        url.search = searchParams
        window.location.href = url
    },
    clearList(cat_id){
        let requestData = {
            action: "remove_from_compare_list_by_category",
            nonce: ajax_callback_settings.ajax_nonce,
            cat_id,
        }
  
        postData(ajax_callback_settings.ajax_url, requestData).then(response => {
            if (response.success) {
                window.location.reload()
            }
        })
    },
    clearAllLists(){
        let requestData = {
            action: "remove_all_products_from_compare_list",
            nonce: ajax_callback_settings.ajax_nonce,
        }
  
        postData(ajax_callback_settings.ajax_url, requestData).then(response => {
            if (response.success) {
                window.location.reload()
            }
        })
    },
    removeProduct(post_id){
        let requestData = {
            action: "remove_product_from_compare_list",
            nonce: ajax_callback_settings.ajax_nonce,
            post_id,
        }
  
        postData(ajax_callback_settings.ajax_url, requestData).then(response => {
            if (response.success) {
                window.location.reload()
            }
        })
    }
})
