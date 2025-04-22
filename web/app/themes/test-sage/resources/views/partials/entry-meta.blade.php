<time class="dt-published metabox" datetime="{{ get_post_time('c', true) }}">
  Posted By <a href="{{ get_author_posts_url(get_the_author_meta('ID')) }}" rel="author">{{ get_the_author() }}</a> on {{ get_the_date() }}
</time>

@if (has_category())
  <p class="entry-categories">{!! get_the_category_list(', ') !!}</p>
@endif
