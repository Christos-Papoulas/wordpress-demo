@if(!empty($benefits))
    <ul class="ht-list-none flex flex-col gap-3">
        @foreach($benefits as $benefit)
            <li class="flex items-center gap-3">
                <img width="16" height="16" src="{{ $benefit['img']['url'] }}" alt="{{ $benefit['img']['title'] }}"/>
                @if(!empty($benefit['link']['url']))
                    <a href="{{ $benefit['link']['url'] }}" title="{{ $benefit['link']['title'] }}" target="{{ $benefit['link']['target'] }}" class="font-bold underline !text-body">
                    {{ $benefit['title'] }}
                    </a>
                @else
                    <span class="font-bold underline text-body">{{ $benefit['title'] }}</span>
                @endif
            </li>
        @endforeach
    </ul>
@endif
