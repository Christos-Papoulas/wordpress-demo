<div class="container container--narrow page-section">
  <div class="generic-content">
    {!! the_content() !!}
  </div>

  @if ($programs->have_posts())
    <hr class="section-break">
    <h2 class="headline headline--medium">Programs available at this campus</h2>

    <ul class="min-list link-list">
    @while ($programs->have_posts()) @php($programs->the_post())
      <li>
        <a href="{{ get_the_permalink() }}">{{ get_the_title() }}</a>
      </li>
    @endwhile
    </ul>
  @endif

</div>
