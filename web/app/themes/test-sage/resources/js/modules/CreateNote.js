import axios from 'axios'

export default () => ({
  title: '',
  body: '',
  noteLimit: false,
  isLoading: false,

  createNote() {
    // basic client-side validation
    if (! this.title.trim() || ! this.body.trim()) {
      return alert('Please enter a title and some content.')
    }

    this.isLoading = true

    axios.post(
      '/wp-json/wp/v2/note',
      {
        title: this.title,
        content: this.body,
        status: 'private'
      },
      {
        headers: {'X-WP-Nonce': testSage.nonce}
      }
    )
    .then(res => {
      this.addNewItemToList(res)

      // clear the form
      this.title = ''
      this.body  = ''
      this.noteLimit = false

      // let parent/listeners know a new note arrived
      this.$dispatch('note-created', res.data)
    })
    .catch(err => {
      // WP returns 403 if your user has hit the note limit
      if (err.response && err.response.status === 403) {
        this.noteLimit = true
        document.querySelector('.note-limit-message').style.visibility = 'visible'
        document.querySelector('.note-limit-message').style.opacity = 1

      } else {
        alert(err.message || 'Create failed')
      }
    })
    .finally(() => (this.isLoading = false))
  },

  addNewItemToList(res) {
    console.log(res)
    const ul = document.getElementById('my-notes')
    const newItemHtml = `
      <li data-id="${res.data.id}">
        <input class="note-title-field" type="text" value="${res.data.title.raw}" readonly>
        <textarea class="note-body-field" readonly>${res.data.content.raw}</textarea>
        </span>
      </li>
    `
    ul.insertAdjacentHTML('afterbegin', newItemHtml)
  }
})
