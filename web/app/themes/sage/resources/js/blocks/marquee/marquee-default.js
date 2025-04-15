// import function to register Swiper custom elements
import { register } from 'swiper/element/bundle';


  // register Swiper custom elements
  register();

  const blocks = document.getElementsByClassName("marquee-default");
  for(let index=0;index < blocks.length;index++){
      let block = blocks[index]

      // initialize all sliders
      let swiperEl = block.querySelector('swiper-container')

      // now we need to assign all parameters to Swiper element
      Object.assign(swiperEl, 
          {
            spaceBetween: 8,
            speed: 6000,
            autoplay: {
              delay: 0,
            },
            loop: true,
            slidesPerView:'auto',
            allowTouchMove: false,
            disableOnInteraction: false,
            on: {
                init() {          
                    this.wrapperEl.style.transition = 'all 0.3s linear';
                },
            },
          }
      );

      // and now initialize it
      swiperEl.initialize();
  }

