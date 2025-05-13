import.meta.glob([
  '../images/**',
  '../fonts/**',
]);

import Alpine from 'alpinejs'

// Our modules / classes
import MobileMenu from "./modules/MobileMenu"
import HeroSlider from "./modules/HeroSlider"
import Search from "./modules/Search"
import Notes from "./modules/Notes"

// Instantiate a new object using our modules/classes
const mobileMenu = new MobileMenu()
const heroSlider = new HeroSlider()
const search = new Search()

window.Alpine = Alpine

Alpine.data('notes', Notes)
Alpine.start()
