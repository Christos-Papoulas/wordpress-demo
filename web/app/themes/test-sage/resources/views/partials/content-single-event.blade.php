<div class="container container--narrow page-section">
  <div class="generic-content">
    {!! the_content() !!}
  </div>

  @if ($programs)
    <hr class="section-break">
    <h2 class="headline headline--medium">Related Programs</h2>

    <ul class="link-list min-list">
    @foreach ($programs as $program)
      <li><a href="{{ get_the_permalink($program) }}">{{ get_the_title($program) }}</a></li>
    @endforeach
    </ul>
  @endif

</div>
