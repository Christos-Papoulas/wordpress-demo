<div class="page-banner">
  <div class="page-banner__bg-image" style="background-image: url('{{ Vite::asset('resources/images/library-hero.jpg') }}');"></div>
  <div class="page-banner__content container t-center c-white">
    <h1 class="headline headline--large">{!! $title !!}</h1>
  </div>
</div>

<div class="container container--narrow page-section">
  <div class="generic-content">
    {!! the_content() !!}
  </div>

  @if ($programs)
    <hr class="section-break">
    <h2 class="headline headline--medium">Subject Taught</h2>

    <ul class="link-list min-list">
    @foreach ($programs as $program)
      <li><a href="{{ get_the_permalink($program) }}">{{ get_the_title($program) }}</a></li>
    @endforeach
    </ul>
  @endif

</div>
