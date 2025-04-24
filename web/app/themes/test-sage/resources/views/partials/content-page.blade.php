<div class="container container--narrow page-section">

  <div class="metabox metabox--position-up metabox--with-home-link">
    <p>
      {{-- <a class="metabox__blog-home-link" href="#"><i class="fa fa-home" aria-hidden="true"></i> Back to About Us</a> --}}
      <span class="metabox__main">{{ $title }}</span>
    </p>
  </div>
  <!--
  <div class="page-links">
    <h2 class="page-links__title"><a href="#">About Us</a></h2>
    <ul class="min-list">
      <li class="current_page_item"><a href="#">Our History</a></li>
      <li><a href="#">Our Goals</a></li>
    </ul>
  </div>
  -->

  <div class="generic-content">
    {!! the_content(); !!}
  </div>

</div>

@if (isset($pagination))
  <nav class="page-nav" aria-label="Page">
    {!! $pagination !!}
  </nav>
@endif
