<section id="app-topbar" class="z-[1010] relative w-full hidden xl:flex py-1.5 bg-{{ $topbar_bg }}">
    <div class="w-full flex @if(!empty($topBarMenu)) justify-between @else justify-end @endif items-center ht-container-no-max-width">

        @if(!empty($topBarMenu))
            <ul class="ht-list-none flex gap-5">
                @foreach ($topBarMenu as $item)
                    <li><a href="{{ $item->url }}" target="{!! $item->target !!}" title="{!! $item->label !!}" class="text-xs uppercase transition {{ 'text-'.$topbar_color }} {{ 'hover:!text-'.$topbar_hover_color }}">{!! $item->label !!}</a></li>
                @endforeach
            </ul>
        @endif

        <ul class="list-none ml-0 mb-0 flex text-xs">
            <li class="{{ 'text-'.$topbar_color }} flex gap-2 mb-0 whitespace-nowrap pr-4 md:border-r-2 md:border-r-{{ $topbar_color }}">
                <svg viewBox="0 0 24 24" role="img" aria-hidden="true" height="23" width="23"><path fill="currentColor" d="M15,12H17A5,5 0 0,0 12,7V9A3,3 0 0,1 15,12M19,12H21C21,7 16.97,3 12,3V5C15.86,5 19,8.13 19,12M20,15.5C18.75,15.5 17.55,15.3 16.43,14.93C16.08,14.82 15.69,14.9 15.41,15.18L13.21,17.38C10.38,15.94 8.06,13.62 6.62,10.79L8.82,8.59C9.1,8.31 9.18,7.92 9.07,7.57C8.7,6.45 8.5,5.25 8.5,4A1,1 0 0,0 7.5,3H4A1,1 0 0,0 3,4A17,17 0 0,0 20,21A1,1 0 0,0 21,20V16.5A1,1 0 0,0 20,15.5Z"></path></svg>
                <a class="{{ 'text-'.$topbar_color }} {{ 'hover:!text-'.$topbar_hover_color }} flex items-center transition" href="tel:{{ isset($contact->phones[0]) ? $contact->phones[0]['number'] ?? '' : '' }}" title="{{ __('call us','sage') }}">{{ isset($contact->phones[0]) ? $contact->phones[0]['number'] ?? '' : '' }}</a>
            </li>
            <li class="{{ 'text-'.$topbar_color }} flex gap-2 mb-0 whitespace-nowrap pl-4">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M3 8L10.89 13.26C11.2187 13.4793 11.6049 13.5963 12 13.5963C12.3951 13.5963 12.7813 13.4793 13.11 13.26L21 8M5 19H19C19.5304 19 20.0391 18.7893 20.4142 18.4142C20.7893 18.0391 21 17.5304 21 17V7C21 6.46957 20.7893 5.96086 20.4142 5.58579C20.0391 5.21071 19.5304 5 19 5H5C4.46957 5 3.96086 5.21071 3.58579 5.58579C3.21071 5.96086 3 6.46957 3 7V17C3 17.5304 3.21071 18.0391 3.58579 18.4142C3.96086 18.7893 4.46957 19 5 19Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
                <a class="{{ 'text-'.$topbar_color }} {{ 'hover:!text-'.$topbar_hover_color }} flex items-center transition" href="mailto:{{ $contact->emails[0]['email'] ?? ''}}​" title="{{ __('email us','sage') }}">{{ $contact->emails[0]['email'] ?? ''}}</a>
            </li>
        </ul>
    </div>
</section>

