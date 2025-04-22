{{-- <div class="page-header">
  <div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url('{{ Vite::asset('resources/images/ocean.jpg') }}');"></div>
    <div class="page-banner__content container container--narrow">
      <h1 class="page-banner__title">{!! $title !!}</h1>
      <div class="page-banner__intro">
        <p>DON'T FORGET TO REPLACE ME LATER</p>
      </div>
    </div>
  </div>
</div> --}}


<div class="page-banner">
  <div class="page-banner__bg-image" style="background-image: url('{{ Vite::asset('resources/images/library-hero.jpg') }}');"></div>
  <div class="page-banner__content container t-center c-white">
    <h1 class="headline headline--large">{!! $title !!}</h1>
    @if (is_category())
      <h2 class="headline headline--medium">The best category ever {{ single_cat_title() }}!</h2>
    @elseif (is_author())
      <h2 class="headline headline--medium">All posts by {{ get_the_author() }}</h2>
    @else
      <h2 class="headline headline--medium">We think you&rsquo;ll like it here.</h2>
    @endif
    <h3 class="headline headline--small">{!! the_archive_description() !!}</h3>
    <a href="#" class="btn btn--large btn--blue">Find Your Major</a>
  </div>
</div>
