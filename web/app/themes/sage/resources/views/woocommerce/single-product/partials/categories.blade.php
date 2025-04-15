@if (! empty($productCategories))
    <div class="flex items-center gap-4">
        @foreach ($productCategories as $key => $cat)
            <a
                href="{{ get_term_link($cat->term_id, 'product_cat') }}"
                class=" bg-primary rounded-full px-4 py-2 text-sm font-semibold !text-white"
            >
                {!! $cat->name !!}
            </a>
        @endforeach
    </div>
@endif
