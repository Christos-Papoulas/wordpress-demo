<div class="page-banner">
  <div class="page-banner__bg-image" style="background-image: url('{{ $banner }}');"></div>
  <div class="page-banner__content container t-center c-white">
    <h1 class="headline headline--large">{!! the_title() !!}</h1>
    <div class="page-banner__intro">
      <p>{{ the_field('page-banner-subtitle') }}</p>
    </div>
  </div>
</div>
