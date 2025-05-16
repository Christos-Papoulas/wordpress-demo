<div class="container container--narrow page-section">
  <div class="generic-content">
    <div class="row group">
      <div class="one-third">
        {{ the_post_thumbnail('professorPortrait') }}
      </div>

      <div class="two-thirds">
        @include('components.like')
        {!! the_content() !!}
      </div>
    </div>
  </div>

  @if ($programs)
    <hr class="section-break">
    <h2 class="headline headline--medium">Subject Taught</h2>

    <ul class="link-list min-list">
    @foreach ($programs as $program)
      <li>
        <a href="{{ get_the_permalink($program) }}">{{ get_the_title($program) }}</a>
      </li>
    @endforeach
    </ul>
  @endif

</div>
