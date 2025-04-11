// import function to register Swiper custom elements
import { register } from 'swiper/element/bundle';

  // register Swiper custom elements
  register();

  const blocks = document.getElementsByClassName("slider-style-1");
  for(let index=0;index < blocks.length;index++){
      let block = blocks[index]

      // initialize all sliders
      let swiperEl = block.querySelector('swiper-container')

      // now we need to assign all parameters to Swiper element
      Object.assign(swiperEl, 
          {
            slidesPerView: 1.2,
            allowTouchMove: true,
            disableOnInteraction: true,
            spaceBetween: 24,
            navigation: {
              prevEl: block.querySelector('.swiper-btn-prev'),
              nextEl: block.querySelector('.swiper-btn-next'),
            },
            breakpoints: {
              768: {
                slidesPerView: 2.5,
                spaceBetween: 24,
              },
              1024: {
                slidesPerView: 3.5,
                spaceBetween: 24,
              },
              1280: {
                slidesPerView: 3.5,
                spaceBetween: 24,
              },
              1536: {
                slidesPerView: 3.5,
                spaceBetween: 24,
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
