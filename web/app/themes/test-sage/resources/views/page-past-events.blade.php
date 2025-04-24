@extends('layouts.app')

@section('content')
  @include('partials.page-header')
  @php
    $pastEvents = new WP_Query([
      'paged' => get_query_var('paged', 1),
      'post_type' => 'event',
      'meta_key' => 'event-date',
      'orderby' => 'meta_value_num',
      'order' => 'ASC',
      'meta_query' => [
        [
          'key' => 'event-date',
          'compare' => '<',
          'value' => date('Ymd'),
          'type' => 'numeric'
        ],
      ]
    ]);
  @endphp

  <div  class="container container--narrow page-section">
    @while($pastEvents->have_posts()) @php($pastEvents->the_post())
      @include('partials.content-event')
    @endwhile

    {!! paginate_links([
      'total' => $pastEvents->max_num_pages
    ]) !!}
  </div>
@endsection

{{-- @section('sidebar')
  @include('sections.sidebar')
@endsection --}}
