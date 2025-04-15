// import function to register Swiper custom elements
import { register } from 'swiper/element/bundle';

// register Swiper custom elements
register();

const blocks = document.getElementsByClassName("slider-products");

for(let index=0;index < blocks.length;index++){
    let block = blocks[index]

    // initialize all sliders
    let swiperEl = block.querySelector('swiper-container')

    // now we need to assign all parameters to Swiper element
    Object.assign(swiperEl, 
        {
        loop: false,
        slidesPerView: 2,
        spaceBetween: 6,
        allowTouchMove: true,
        navigation: {
            prevEl: block.querySelector('.swiper-btn-prev'),
            nextEl: block.querySelector('.swiper-btn-next'),
        },
        breakpoints: {
            768: {
            slidesPerView: 2,
            spaceBetween: 6,
            },
            1024: {
            slidesPerView: 3,
            spaceBetween: 6,
            },
            1280: {
            slidesPerView: 4,
            spaceBetween: 6,
            },
        },
            on: {
                init() {
                    this.slides.forEach((element) => element.classList.remove('hidden'))      
                },
            },
        }
    );

    // and now initialize it
    swiperEl.initialize();
}
