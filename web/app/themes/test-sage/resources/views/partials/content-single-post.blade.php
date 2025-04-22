<div class="page-banner">
  <div class="page-banner__bg-image" style="background-image: url('{{ Vite::asset('resources/images/library-hero.jpg') }}');"></div>
  <div class="page-banner__content container t-center c-white">
    <h1 class="headline headline--large">{!! $title !!}</h1>
    {{-- <h2 class="headline headline--medium">We think you&rsquo;ll like it here.</h2>
    <h3 class="headline headline--small">Why don&rsquo;t you check out the <strong>major</strong> you&rsquo;re interested in?</h3>
    <a href="#" class="btn btn--large btn--blue">Find Your Major</a> --}}
  </div>
</div>

<div class="container container--narrow page-section">
  <div class="generic-content">
    {!! the_content(); !!}
  </div>
</div>
