import axios from 'axios';

class Search {
  constructor() {
    this.isOverlayOpen = false
    this.isSpinnerVisible = false
    this.typingTimer

    this.resultsDiv = document.querySelector(".search-overlay__results")
    this.openButton = document.querySelectorAll(".js-search-trigger")
    this.closeButton = document.querySelector(".search-overlay__close")
    this.searchOverlay = document.querySelector(".search-overlay")
    this.searchField = document.getElementById("search-term")
    this.previousValue
    this.events()
  }

  events() {
    this.openButton.forEach(element => {
      element.addEventListener("click", () => this.openOverlay(), false)
    });

    this.closeButton.addEventListener("click", () => this.closeOverlay(), false)

    document.body.addEventListener("keydown", e => this.keyPress(e), false)

    this.searchField.addEventListener("keyup", e => this.typingLogic(e), false)
  }

  typingLogic(e) {
    if (this.previousValue == this.searchField.value) {
      return
    }

    if (this.typingTimer) {
      clearTimeout(this.typingTimer)
    }

    if (this.searchField.value == "") {
      this.resultsDiv.innerHTML = ""
      this.isSpinnerVisible = false
      return
    }

    if (! this.isSpinnerVisible) {
      this.resultsDiv.innerHTML = '<div class="spinner-loader"></div>'
      this.isSpinnerVisible = true
    }

    this.typingTimer = setTimeout(() => {
      console.log("fetch results")
      this.isSpinnerVisible = false
      this.resultsDiv.innerHTML = "<p>Searching...</p>"
      axios.get('/wp-json/wp/v2/posts?search=' + this.searchField.value)
        .then(res => this.showResults(res))
    }, 1200);

    this.previousValue = this.searchField.value
  }

  showResults(res) {
    if (res.data.length == 0) {
      this.resultsDiv.innerHTML = "<p>No results found</p>"
      return
    }
    this.resultsDiv.innerHTML = `
      <h2 class="search-overlay__section-title">General Information</h2>
      <ul class="link-list min-list">
        ${res.data.map(item => `<li><a href="${item.link}">${item.title.rendered}</a></li>`).join('')}
      </ul>
    `
  }

  openOverlay() {
    this.isOverlayOpen = true
    this.searchOverlay.classList.add("search-overlay--active")
    document.body.classList.add("body-no-scroll")
  }

  closeOverlay() {
    this.isOverlayOpen = false
    this.searchOverlay.classList.remove("search-overlay--active")
    document.body.classList.remove("body-no-scroll")
  }

  keyPress(e) {
    if (e.keyCode == 27 && this.isOverlayOpen) { // escape key
      this.closeOverlay()
    } else if (e.keyCode == 83 && !this.isOverlayOpen) { // s key
      this.openOverlay()
    }
  }
}

export default Search
