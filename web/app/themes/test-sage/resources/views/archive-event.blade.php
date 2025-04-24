@extends('layouts.app')

@section('content')
  @include('partials.page-header')

  <div  class="container container--narrow page-section">
    @while(have_posts()) @php(the_post())
      @include('partials.content-event')
    @endwhile

    {!! get_the_posts_navigation() !!}

    <hr class="section-break">

    <p>Looking for a recap of past events? <a href="{{ site_url('/past-events') }}">Check out our past events archive</p>
  </div>
@endsection

{{-- @section('sidebar')
  @include('sections.sidebar')
@endsection --}}
