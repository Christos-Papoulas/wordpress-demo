import axios from 'axios';

class MyNotes {
  constructor() {
    if (document.querySelector("#my-notes")) {
      this.events()
    }
  }

  events() {
    document
    .querySelectorAll("#my-notes .delete-note")
    .forEach(el => el.addEventListener("click", e => this.deleteNote(e)))
  }

  deleteNote(e) {
    const li = e.target.closest("li")
    const noteId = li.getAttribute('data-id')
    console.debug("deleting note", noteId)
    console.log('nonce', testSage.nonce)
    axios.delete('/wp-json/wp/v2/note/' + noteId, {
      headers: {
        'X-WP-Nonce': testSage.nonce
      }
    })
      .then(res => {
        console.log(res)
        li.remove()
      })
      .catch(error => alert(error.message || "Something went wrong"));
  }
}

export default MyNotes;
