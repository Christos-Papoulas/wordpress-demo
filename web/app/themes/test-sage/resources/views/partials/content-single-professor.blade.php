@php
$banner = get_field('page-banner-background-image')['sizes']['pageBanner'] ?? Vite::asset('resources/images/library-hero.jpg');
@endphp
<div class="page-banner">
  <div class="page-banner__bg-image" style="background-image: url('{{ $banner }}');"></div>
  <div class="page-banner__content container t-center c-white">
    <h1 class="headline headline--large">{!! $title !!}</h1>
    <div class="page-banner__intro">
      <p>{{ the_field('page-banner-subtitle') }}</p>
    </div>
  </div>
</div>

<div class="container container--narrow page-section">
  <div class="generic-content">
    <div class="row group">
      <div class="one-third">
        {{ the_post_thumbnail('professorPortrait') }}
      </div>

      <div class="two-thirds">
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
