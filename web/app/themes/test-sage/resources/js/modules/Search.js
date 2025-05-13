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
      this.isSpinnerVisible = false
      this.resultsDiv.innerHTML = "<p>Searching...</p>"
      axios.get('/api/search?term=' + this.searchField.value)
        .then(res => this.showResults(res))
    }, 950);

    this.previousValue = this.searchField.value
  }

  showResults(res) {
    console.log(res)
    if (res.data.length == 0) {
      this.resultsDiv.innerHTML = "<p>No results found</p>"
      return
    }

    this.resultsDiv.innerHTML = `
    <div class="row">
      <div class="one-third">
        <h2 class="search-overlay__section-title">Pages</h2>
        <ul class="link-list min-list">
          ${res.data.page.map(item => `<li><a href="${item.permalink}">${item.title}</a></li>`).join('')}
        </ul>
      </div>
      <div class="one-third">
        <h2 class="search-overlay__section-title">Programs</h2>
        <ul class="link-list min-list">
          ${res.data.program.map(item => `<li><a href="${item.permalink}">${item.title}</a></li>`).join('')}
        </ul>
      </div>
      <div class="one-third">
        <h2 class="search-overlay__section-title">Professors</h2>
        <ul class="professor-cards">
          ${res.data.professor.map(item => `
            <li class="professor-card__list-item">
              <a href="${item.permalink}" class="professor-card">
                <img src="${item.image}" alt="" class="professor-card__image">
                <span class="professor-card__name">${item.title}</span>
              </a>
            </li>
          `).join('')}
        </ul>
      </div>
    </div>
    <div class="row">
      <div class="one-third">
        <h2 class="search-overlay__section-title">Posts</h2>
        <ul class="link-list min-list">
          ${res.data.post.map(item => `<li><a href="${item.permalink}">${item.title}</a> by ${item.authorName}</li>`).join('')}
        </ul>
      </div>
      <div class="one-third">
        <h2 class="search-overlay__section-title">Campuses</h2>
        <ul class="link-list min-list">
          ${res.data.campus.map(item => `<li><a href="${item.permalink}">${item.title}</a></li>`).join('')}
        </ul>
      </div>
      <div class="one-third">
        <h2 class="search-overlay__section-title">Events</h2>
          ${res.data.event.map(item => `
            <div class="event-summary">
              <a class="event-summary__date event-summary__date--beige t-center" href="${item.permalink}">
                <span class="event-summary__month">${item.month}</span>
                <span class="event-summary__day">${item.day}</span>
              </a>
              <div class="event-summary__content">
                <h5 class="event-summary__title headline headline--tiny"><a href="${item.permalink}">${item.title}</a></h5>
                <p>
                  ${item.excerpt}
                  <a href="${item.permalink}" class="nu gray">Read more</a></p>
              </div>
            </div>`).join('')}
      </div>
    </div>
    `
  }

  openOverlay() {
    this.isOverlayOpen = true
    this.searchOverlay.classList.add("search-overlay--active")
    this.searchField.value = ""
    setTimeout(() => this.searchField.focus(), 301)
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
    } /*else if (e.keyCode == 83 && !this.isOverlayOpen) { // s key
      this.openOverlay()
    }*/
  }
}

export default Search
