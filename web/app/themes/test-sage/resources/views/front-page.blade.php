@extends('layouts.app')

@section('content')
  @include('partials.page-header')
    <div class="full-width-split group">
      <div class="full-width-split__one">
        <div class="full-width-split__inner">
          <h2 class="headline headline--small-plus t-center">Upcoming Events</h2>
          @php
          $today = date('Ymd');

          $events = new WP_Query([
            'posts_per_page' => 3,
            'post_type' => 'event',
            'meta_key' => 'event-date',
            'meta_query' => [
              [
                'key' => 'event-date',
                'compare' => '>=',
                'value' => $today,
                'type' => 'numeric'
              ],
            ],
            'orderby' => 'meta_value_num',
            'order' => 'ASC',
          ]);
          @endphp

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

          <p class="t-center no-margin"><a href="{{ get_post_type_archive_link('event') }}" class="btn btn--blue">View All Events</a></p>
        </div>
      </div>
      <div class="full-width-split__two">
        <div class="full-width-split__inner">
          <h2 class="headline headline--small-plus t-center">From Our Blogs</h2>
          @php
          $homepagePosts = new WP_Query([
            'posts_per_page' => 2,
          ]);
          @endphp

          @while ($homepagePosts->have_posts()) @php($homepagePosts->the_post())
            <div class="event-summary">
              <a class="event-summary__date event-summary__date--beige t-center" href="{{ get_the_permalink() }}">
                <span class="event-summary__month">{{ the_time('M') }}</span>
                <span class="event-summary__day">{{ the_time('d') }}</span>
              </a>
              <div class="event-summary__content">
                <h5 class="event-summary__title headline headline--tiny"><a href="{{ get_the_permalink() }}">{!! the_title() !!}</a></h5>
                <p>
                  @if (has_excerpt())
                    {!! get_the_excerpt() !!}
                  @else
                    {!! wp_trim_words(get_the_content(), 18) !!}
                  @endif
                  <a href="{{ get_the_permalink() }}" class="nu gray">Read more</a></p>
              </div>
            </div>
          @endwhile
          @php(wp_reset_postdata())

          <p class="t-center no-margin"><a href="{{ site_url('/blog') }}" class="btn btn--yellow">View All Blog Posts</a></p>
        </div>
      </div>
    </div>

    <div class="hero-slider">
      <div data-glide-el="track" class="glide__track">
        <div class="glide__slides">
          <div class="hero-slider__slide" style="background-image: url('{{ Vite::asset('resources/images/bus.jpg') }}');">
            <div class="hero-slider__interior container">
              <div class="hero-slider__overlay">
                <h2 class="headline headline--medium t-center">Free Transportation</h2>
                <p class="t-center">All students have free unlimited bus fare.</p>
                <p class="t-center no-margin"><a href="#" class="btn btn--blue">Learn more</a></p>
              </div>
            </div>
          </div>
          <div class="hero-slider__slide" style="background-image: url('{{ Vite::asset('resources/images/apples.jpg') }}');">
            <div class="hero-slider__interior container">
              <div class="hero-slider__overlay">
                <h2 class="headline headline--medium t-center">An Apple a Day</h2>
                <p class="t-center">Our dentistry program recommends eating apples.</p>
                <p class="t-center no-margin"><a href="#" class="btn btn--blue">Learn more</a></p>
              </div>
            </div>
          </div>
          <div class="hero-slider__slide" style="background-image: url('{{ Vite::asset('resources/images/bread.jpg') }}');">
            <div class="hero-slider__interior container">
              <div class="hero-slider__overlay">
                <h2 class="headline headline--medium t-center">Free Food</h2>
                <p class="t-center">Fictional University offers lunch plans for those in need.</p>
                <p class="t-center no-margin"><a href="#" class="btn btn--blue">Learn more</a></p>
              </div>
            </div>
          </div>
        </div>
        <div class="slider__bullets glide__bullets" data-glide-el="controls[nav]"></div>
      </div>
    </div>
@endsection

@section('sidebar')
  @include('sections.sidebar')
@endsection
