<div class="container container--narrow page-section">
  <div class="generic-content">
    {!! the_content(); !!}
  </div>

  @if ($professors->have_posts())
    <hr class="section-break">

    <h2 class="headline headline--medium">{{ get_the_title() }} professors</h2>
    <ul class="professor-cards">
    @while ($professors->have_posts())
      @php
      $professors->the_post();
      @endphp
      <li class="professor-card__list-item">
        <a href="{{ get_the_permalink() }}" class="professor-card">
          <img src="{{ the_post_thumbnail_url('professorLandscape') }}" alt="" class="professor-card__image">
          <span class="professor-card__name">{!! the_title() !!}</span>
        </a>
      </li>
    @endwhile
    </ul>
  @endif

  @if ($events->have_posts())
    <hr class="section-break">

    <h2 class="headline headline--medium">Upcoming {{ get_the_title() }} Events</h2>

    @while ($events->have_posts())
      @php
      $events->the_post();
      @endphp
      @include('partials.content-event')
    @endwhile
  @endif
</div>
