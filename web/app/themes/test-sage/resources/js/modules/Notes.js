import axios from 'axios'

export default ( { id, title, body } ) => ({
  editing: false,
  noteId: id,
  title,
  body,

  toggleEdit() {
    this.editing = !this.editing
    if (this.editing) this.$refs.title.focus()
  },

  deleteNote() {
    axios.delete(`/wp-json/wp/v2/note/${this.noteId}`, {
      headers: { 'X-WP-Nonce': testSage.nonce }
    })
    .then(() => {
      console.log('then')
      this.$el.closest('li').remove()
    })
    .catch(e => {
      console.log('catch')
      alert(e.message || 'Delete failed')
    })
  },

  updateNote() {
    axios.post(`/wp-json/wp/v2/note/${this.noteId}`, {
      title: this.title,
      content: this.body
    }, {
      headers: { 'X-WP-Nonce': testSage.nonce }
    })
    .then(() => this.toggleEdit())
    .catch(e => alert(e.message || 'Update failed'))
  }
})
