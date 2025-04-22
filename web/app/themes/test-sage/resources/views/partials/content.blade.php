
<article @php(post_class('post-item'))>
  <header>
    <h2 class="entry-title headline headline--medium headline--post-title">
      <a href="{{ get_permalink() }}">
        {!! $title !!}
      </a>
    </h2>

    @include('partials.entry-meta')
  </header>

  <div class="entry-summary">
    @php(the_excerpt())
    <a class="btn btn--blue" href="{{ get_permalink() }}">
      Continue reading &raquo;
    </a>
  </div>
</article>
