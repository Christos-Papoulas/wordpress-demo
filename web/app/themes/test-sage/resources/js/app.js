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
import CreateNote from "./modules/CreateNote"
import Like from "./modules/Like"

// Instantiate a new object using our modules/classes
const mobileMenu = new MobileMenu()
const heroSlider = new HeroSlider()
const search = new Search()

window.Alpine = Alpine

Alpine.data('notes', Notes)
Alpine.data('createNote', CreateNote)
Alpine.data('like', Like)
Alpine.start()
