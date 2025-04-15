// import function to register Swiper custom elements
import { register } from 'swiper/element/bundle';

// register Swiper custom elements
register();

const blocks = document.getElementsByClassName("slider-products-attributes");

for (let index = 0; index < blocks.length; index++) {
    let block = blocks[index]

    // initialize all sliders
    let swiperEl = block.querySelector('swiper-container')

    // now we need to assign all parameters to Swiper element
    Object.assign(swiperEl,
        {
            loop: false,
            slidesPerView: 3,
            spaceBetween: 16,
            allowTouchMove: true,
            autoplay: {
                delay: 3000,
            },
            navigation: {
                prevEl: block.querySelector('.swiper-btn-prev'),
                nextEl: block.querySelector('.swiper-btn-next'),
            },
            grid: {
                rows: 2,
                fill: 'row',
            },
            breakpoints: {
                700: {
                    slidesPerView: 3,
                    spaceBetween: 16,
                },
                1280: {
                    slidesPerView: 6,
                    spaceBetween: 16,
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
