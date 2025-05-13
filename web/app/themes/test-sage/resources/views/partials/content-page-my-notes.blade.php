@php
if (! is_user_logged_in()) {
  wp_redirect(home_url('/'));
  exit;
}

$userNotes = new WP_Query([
  'post_type' => 'note',
  'posts_per_page' => -1,
  'author' => get_current_user_id()
])
@endphp

<div class="container container--narrow page-section">
  <div class="generic-content">
    <div class="create-note" x-data="createNote()">
      <h2 class="headline headline--medium">Create a New Note</h2>
      <input class="new-note-title" type="text" placeholder="Title" x-model="title">
      <textarea class="new-note-body" placeholder="Your note here..." x-model="body"></textarea>
      <span class="submit-note" @click="createNote()">Create Note</span>
      <span class="note-limit-message">Note limit reached!</span>
    </div>
    <ul class="link-list min-list" id="my-notes">
      @while($userNotes->have_posts()) @php($userNotes->the_post())
        @include('partials.item-note', [
          'id' => get_the_ID(),
          'title' => get_the_title(),
          'body' => wp_strip_all_tags(get_the_content()),
        ])
      @endwhile
    </ul>
  </div>
</div>

@if (isset($pagination))
  <nav class="page-nav" aria-label="Page">
    {!! $pagination !!}
  </nav>
@endif
