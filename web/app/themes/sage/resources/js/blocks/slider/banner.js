// import function to register Swiper custom elements
import { register } from 'swiper/element/bundle';

  // register Swiper custom elements
  register();

  const blocks = document.getElementsByClassName("slider-banner");
  for(let index=0;index < blocks.length;index++){
      let block = blocks[index]

      // initialize all sliders
      let swiperEl = block.querySelector('swiper-container')

      // now we need to assign all parameters to Swiper element
      Object.assign(swiperEl, 
          {
            slidesPerView: 1,
            allowTouchMove: true,
            loop: true,
            disableOnInteraction: true,
            pagination: {
                el:  block.querySelector('.swiper-pagination'),
                clickable: true,
            },
            navigation: {
              prevEl: block.querySelector('.swiper-btn-prev'),
              nextEl: block.querySelector('.swiper-btn-next'),
            },
              on: {
                  init() {    
                    this.slides.forEach((element) => element.classList.remove('hidden')) 
                    block.querySelector('.swiper-btn-prev')?.classList.remove('lg:hidden')       
                    block.querySelector('.swiper-btn-next')?.classList.remove('lg:hidden')       
                    block.querySelector('.slider-placeholder')?.remove()     
                  },
              },
          }
      );

      // and now initialize it
      swiperEl.initialize();
  }
