@extends('layouts.app')

@section('content')
  @include('partials.page-header')

  <div  class="container container--narrow page-section">
    <ul class="link-list">
      @while(have_posts()) @php(the_post())
        @include('partials.content-program')
      @endwhile
    </ul>
    {!! get_the_posts_navigation() !!}
  </div>
@endsection

{{-- @section('sidebar')
  @include('sections.sidebar')
@endsection --}}
