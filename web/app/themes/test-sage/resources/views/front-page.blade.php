@extends('layouts.app')

@section('content')
  @include('partials.page-header')
    <div class="full-width-split group">
      <div class="full-width-split__one">
        <div class="full-width-split__inner">
          <h2 class="headline headline--small-plus t-center">Upcoming Events</h2>

          <div class="event-summary">
            <a class="event-summary__date t-center" href="#">
              <span class="event-summary__month">Mar</span>
              <span class="event-summary__day">25</span>
            </a>
            <div class="event-summary__content">
              <h5 class="event-summary__title headline headline--tiny"><a href="#">Poetry in the 100</a></h5>
              <p>Bring poems you&rsquo;ve wrote to the 100 building this Tuesday for an open mic and snacks. <a href="#" class="nu gray">Learn more</a></p>
            </div>
          </div>
          <div class="event-summary">
            <a class="event-summary__date t-center" href="#">
              <span class="event-summary__month">Apr</span>
              <span class="event-summary__day">02</span>
            </a>
            <div class="event-summary__content">
              <h5 class="event-summary__title headline headline--tiny"><a href="#">Quad Picnic Party</a></h5>
              <p>Live music, a taco truck and more can found in our third annual quad picnic day. <a href="#" class="nu gray">Learn more</a></p>
            </div>
          </div>

          <p class="t-center no-margin"><a href="#" class="btn btn--blue">View All Events</a></p>
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
                <p>{!! wp_trim_words(get_the_content(), 18) !!} <a href="{{ get_the_permalink() }}" class="nu gray">Read more</a></p>
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
