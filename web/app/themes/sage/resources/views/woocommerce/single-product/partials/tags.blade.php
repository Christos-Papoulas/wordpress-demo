@if (! empty($productTags))
    /
    <b>{{ __('TAGS:', 'sage') }}</b>
    @foreach ($productTags as $key => $tag)
        <a
            href="{{ $tag->permalink }}"
            class="@if($key != array_key_last($productTags)) after:content-[','] @endif hover:font-bold"
        >
            {!! $tag->name !!}
        </a>
    @endforeach
@endif
