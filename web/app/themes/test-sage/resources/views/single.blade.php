@extends('layouts.app')

@section('content')
  @while(have_posts()) @php(the_post())
    @include('components.banner')
    @includeFirst(['partials.content-single-' . get_post_type(), 'partials.content-single'])
  @endwhile
@endsection
