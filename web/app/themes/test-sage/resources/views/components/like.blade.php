@props(['isLiked' => false, 'likeCounts' => 0, 'likeId' => 0])

<span class="like-box"
  x-data="like(@js(get_the_ID()), @js($isLiked), @js($likeCounts), @js($likeId))"
  @click="toggleLike()"
>
  <i class="fa fa-heart-o" aria-hidden="true" x-show="! isLiked" x-transition></i>
  <i class="fa fa-heart" aria-hidden="true" x-show="isLiked"  x-transition></i>
  <span class="like-count" x-text="count"></span>
</span>
