@extends('layouts.app')

@section('content')
  @include('partials.page-header')

  <div  class="container container--narrow page-section">
    @while(have_posts()) @php(the_post())
      @includeFirst(['partials.content', 'partials.content-page'])
    @endwhile

    {!! get_the_posts_navigation() !!}
  </div>
@endsection

{{-- @section('sidebar')
  @include('sections.sidebar')
@endsection --}}
