@include('partials.page-header', ['breadcrumbsContainer' => 'ht-container-no-max-width', 'pageTitleContainer' => 'ht-container-no-max-width'])

<div class="ht-container-no-max-width">
    <div class="text-body w-full pb-10">
        <section
            x-data="posts({
                postType : '{{ $postType }}',
                getSubcats : {{ $termDisplayType === 'subcategories' ? 1 : 0 }},
                term : JSON.parse( atob('{{ base64_encode(json_encode($taxonomyTerm)) }}') ),
                lang : '{{ get_locale() }}'
            })"
            data-layout="1"
            class="border-primary relative border-b pb-20"
            id="alpine-posts-archive"
        >
            @if (! empty($categories))
                <div class="ht-container-box grid grid-cols-3 gap-1.5 pb-12 lg:grid-cols-6 xl:pb-20">
                    @foreach ($categories as $category)
                        <a
                            href="{{ get_category_link($category->term_id) }}"
                            class="@if($category->name == get_queried_object()->name) btn-solid-primary @else btn-outlined-primary @endif btn-md uppercase"
                        >
                            {!! $category->name !!}
                        </a>
                    @endforeach
                </div>
            @endif

            @include('posts.post.partials.posts')

            @include('posts.post.partials.load-more-btn')
        </section>
    </div>
</div>
