import axios from 'axios'

export default (professorId, isLiked, count, likeId) => ({
  professorId,
  likeId,
  isLiked,
  count,

  toggleLike() {
    if (this.isLiked) {
      console.log(`/api/like/${this.likeId}`)
      axios.delete(`/api/like/${this.likeId}`, {
        headers: { 'X-WP-Nonce': testSage.nonce }
      })
      .then(() => this.isLiked = false)
      .then(() => this.count -= 1)
      .then(() => this.likeId = 0)
      .catch(e => alert(e.response.data.message))
    } else {
      console.log(`/api/like ${this.professorId}`)
      axios.post('/api/like', {professor: this.professorId}, {
        headers: {
          'X-WP-Nonce': testSage.nonce
        }
      })
      .then(function (response) {
        this.likeId = response.data.id
        this.isLiked = true
        this.count += 1
      }.bind(this))
      .catch(e => alert(e.response.data.message))

    }
  }
})
