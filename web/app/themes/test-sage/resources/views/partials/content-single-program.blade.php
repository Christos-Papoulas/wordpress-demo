<div class="page-banner">
  <div class="page-banner__bg-image" style="background-image: url('{{ Vite::asset('resources/images/library-hero.jpg') }}');"></div>
  <div class="page-banner__content container t-center c-white">
    <h1 class="headline headline--large">{!! $title !!}</h1>
  </div>
</div>

<div class="container container--narrow page-section">
  <div class="generic-content">
    {!! the_content(); !!}
  </div>

  @while ($events->have_posts())
    @php
    $events->the_post();
    $eventDate = new DateTime(get_field('event-date'));
    @endphp
    <div class="event-summary">
      <a class="event-summary__date t-center" href="#">
        <span class="event-summary__year">{{ $eventDate->format('Y') }}</span>
        <span class="event-summary__month">{{ $eventDate->format('M') }}</span>
        <span class="event-summary__day">{{ $eventDate->format('d') }}</span>
      </a>
      <div class="event-summary__content">
        <h5 class="event-summary__title headline headline--tiny"><a href="{{ get_the_permalink() }}">{!! the_title() !!}</a></h5>
        <p>
          @if (has_excerpt())
            {!! get_the_excerpt() !!}
          @else
            {!! wp_trim_words(get_the_content(), 18) !!}
          @endif
          <a href="{{ get_the_permalink() }}" class="nu gray">Learn more</a></p>
      </div>
    </div>
  @endwhile
</div>
