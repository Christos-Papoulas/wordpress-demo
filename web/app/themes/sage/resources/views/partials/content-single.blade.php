
<section class="@if(isset($block->classes)){{ $block->classes }}@endif">
  <div class="w-full py-10 text-body pt-4 lg:pt-7">

      <article @php(post_class('h-entry'))>
        <header class="ht-container-small">
          @include('partials.entry-meta')
          <h1 class="text-left text-xs font-bold lg:text-3xl lg:font-normal uppercase">
            {!! $title !!}
          </h1>
          @php($subtitle = ht_get_field('subtitle'))
          @if(!empty($subtitle))
            <div class="text-2xl font-semibold leading-9 text-body" data-aos="fade-up" data-aos-duration="1000">
              {!! strip_tags($subtitle, ['<br>','<div>','<strong>','<p>','<span>','<em>']) !!}          
            </div>
          @endif
        </header>

        <div class="ht-container-small mb-8 lg:mb-12">
          @include('partials.share-post')
        </div>

        <div class="ht-container-small !max-w-[1160px] mb-8 lg:mb-12">
          @php($img = get_the_post_thumbnail_url(get_the_ID(), 'full'))
          
          @if(!empty($img))
            <div class="w-full overflow-hidden">
              <img src="{{ $img }}" alt="thumbnail" width="100%" height="auto">
            </div>
          @endif
        </div>

        <div class="ht-container-small">
          <div class="text-base e-content text-dark-gray ht-strong-as-white">
            @php(the_content())
          </div>
  
          <footer>
            {!! wp_link_pages(['echo' => 0, 'before' => '<nav class="page-nav"><p>' . __('Pages:', 'sage'), 'after' => '</p></nav>']) !!}
          </footer>

          {{-- @if ($pagination())
            <footer>
              <nav class="page-nav" aria-label="Page">
                {!! $pagination !!}
              </nav>
            </footer>
          @endif --}}
  
          {{-- @php(comments_template()) --}}
        </div>

        <div class="mt-16 ht-container-small">
          <div class="text-xs font-bold lg:text-3xl lg:font-normal uppercase">
            <div class="mb-5">{{ __('Share article','sage') }}</div>
          </div>
          @include('partials.share-post')
        </div>

        {{-- Schema --}}
        <script type="application/ld+json">
          {
              "@context": "https://schema.org",
              "@type": "Article",
              "mainEntityOfPage": {
                  "@type": "WebPage",
                  "@id": "{{ get_permalink() }}"
              },
              "headline": "{{ $title }}",
              "image": "{{ $img }}",
              "datePublished": "{{ get_the_date('c') }}",
              "dateModified": "{{ get_the_modified_date('c') }}",
              "author": {
                  "@type": "Organization",
                  "name": "{{ get_bloginfo('name') }}",
                  "url": "{{ home_url() }}"
              },
              "publisher": {
                  "@type": "Organization",
                  "name": "{{ get_bloginfo('name') }}",
                  "url": "{{ home_url() }}",
                  "logo": {
                      "@type": "ImageObject",
                      "url": "{{ ht_get_field('header_logo','options')['url'] ?? '' }}",
                      "width": {{ ht_get_field('header_logo','options')['width'] }},
                      "height": {{ ht_get_field('header_logo','options')['height'] }}
                  }
              },
              "description": "{{ get_the_excerpt() }}"
          }
        </script>
      </article>

  </div>
</section>

@if(!empty($latestPosts))
<section class="ht-container-2k">
  <div class="pb-10 text-body">
    <div class="ht-container-small">
      <h3 class="pt-20 text-xs font-bold lg:text-3xl lg:font-normal uppercase" data-aos="fade-up" data-aos-duration="1000">{{ __('Additional Updates','sage') }}</h3>
      <div class="grid grid-cols-1">
        @foreach ($latestPosts as $latestPost)
          <div>
            <div class="flex flex-col border-b  sm:items-center sm:flex-row gap-7 border-dark-gray py-7" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="100">
              <?php
                $img = get_the_post_thumbnail_url($latestPost->ID, 'full');
              ?>
              @if(!empty($img))
                <div class="w-full flex aspect-square overflow-hidden md:max-w-[225px]">
                  <img src="{{ $img }}" alt="thumbnail" class="object-cover w-full">
                </div>
              @endif
            <div>
                <div class="mb-4 text-body text-sm  md:text-xs">{{ get_the_date('',$latestPost->ID) }}</div>
                <h4 class="mb-2 pb-0.5 text-lg font-bold">{!! $latestPost->post_title !!}</h4>
                <p class="text-body text-sm  md:text-xs">{!! $latestPost->post_excerpt !!}</p>
                <a href="{{ get_permalink($latestPost->ID) }}" class="text-body text-sm  md:text-xs">{{ __('Read more','sage') }}</a>
            </div>
          </div>
        @endforeach  
      </div>
    </div>
  </div>
</section>
@endif
