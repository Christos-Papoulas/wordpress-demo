// Import GSAP
import { gsap } from "gsap";

// Duplicate slides to create seamless effect
const scrollerInner = document.getElementById("slider-header-messages");
if(scrollerInner){
  const slides = [...scrollerInner.children];
  slides.forEach((slide) => {
    const clone = slide.cloneNode(true);
    scrollerInner.appendChild(clone);
  });

  // GSAP Infinite Scroll Animation
  gsap.to(".scroller__inner", {
    xPercent: -50, // Moves left infinitely
    repeat: -1, // Loops forever
    duration: parseInt(scrollerInner.dataset.duration), // Adjust speed
    ease: "linear",
  });
}

