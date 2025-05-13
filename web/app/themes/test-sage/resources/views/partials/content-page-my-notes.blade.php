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
    <ul class="link-list min-list" id="my-notes">
      @while($userNotes->have_posts()) @php($userNotes->the_post())
        <li>
          <input class="note-title-field" type="text" value="{{ esc_attr(get_the_title()) }}">
          <span class="edit-note">
            <i class="fa fa-pencil" aria-hidden="true"></i> Edit
          </span>
          <span class="delete-note">
            <i class="fa fa-trash-o" aria-hidden="true"></i> Delete
          </span>
          <textarea class="note-body-field" readonly="readonly" data-id="{{ get_the_ID() }}">{{ wp_strip_all_tags(get_the_content()) }}</textarea>
        </li>
      @endwhile
    </ul>
  </div>
</div>

@if (isset($pagination))
  <nav class="page-nav" aria-label="Page">
    {!! $pagination !!}
  </nav>
@endif
