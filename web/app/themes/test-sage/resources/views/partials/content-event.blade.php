@php

$eventDate = new DateTime(get_field('event-date'));

@endphp

<div class="event-summary">
  <a class="event-summary__date t-center" href=""{{ get_the_permalink() }}">
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
      <a href="{{ get_the_permalink() }}" class="nu gray">Learn more</a>
    </p>
  </div>
</div>
