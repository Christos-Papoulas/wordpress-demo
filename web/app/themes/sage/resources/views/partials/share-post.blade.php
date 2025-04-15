@php
    $shareUrl = get_permalink();
    $titleEncoded = str_replace('+', '%20', urlencode_deep($title));
    $excerptEncoded = str_replace('+', '%20', urlencode_deep(get_the_excerpt()));
@endphp

<div x-data="{
    title: '{{ $title }}',
    shareUrl: '{{ $shareUrl }}',
    showTooltip: false,
}">
    <ul class="fallback-share ht-list-none mt-6 flex space-x-2">
        <li data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
            <a
                class="text-body transition hover:text-gray-300"
                aria-label="Social Article"
                aria-hidden="true"
                target="_blank"
                href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}"
                title=""
                rel=""
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path
                        d="M12 2.04001C6.5 2.04001 2 6.53001 2 12.06C2 17.06 5.66 21.21 10.44 21.96V14.96H7.9V12.06H10.44V9.85001C10.44 7.34001 11.93 5.96001 14.22 5.96001C15.31 5.96001 16.45 6.15001 16.45 6.15001V8.62001H15.19C13.95 8.62001 13.56 9.39001 13.56 10.18V12.06H16.34L15.89 14.96H13.56V21.96C15.9164 21.5879 18.0622 20.3855 19.6099 18.5701C21.1576 16.7546 22.0054 14.4457 22 12.06C22 6.53001 17.5 2.04001 12 2.04001Z"
                        fill="currentColor"
                    />
                </svg>
            </a>
        </li>
        <li data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300">
            <a
                class="text-body transition hover:text-gray-300"
                aria-label="Social Article"
                aria-hidden="true"
                target="_blank"
                href="https://twitter.com/intent/tweet?text={{ $titleEncoded }}&url={{ $shareUrl }}"
                title=""
                rel=""
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path
                        d="M22.46 6C21.69 6.35 20.86 6.58 20 6.69C20.88 6.16 21.56 5.32 21.88 4.31C21.05 4.81 20.13 5.16 19.16 5.36C18.37 4.5 17.26 4 16 4C13.65 4 11.73 5.92 11.73 8.29C11.73 8.63 11.77 8.96 11.84 9.27C8.28001 9.09 5.11001 7.38 3.00001 4.79C2.63001 5.42 2.42001 6.16 2.42001 6.94C2.42001 8.43 3.17001 9.75 4.33001 10.5C3.62001 10.5 2.96001 10.3 2.38001 10C2.38001 10 2.38001 10 2.38001 10.03C2.38001 12.11 3.86001 13.85 5.82001 14.24C5.46001 14.34 5.08001 14.39 4.69001 14.39C4.42001 14.39 4.15001 14.36 3.89001 14.31C4.43001 16 6.00001 17.26 7.89001 17.29C6.43001 18.45 4.58001 19.13 2.56001 19.13C2.22001 19.13 1.88001 19.11 1.54001 19.07C3.44001 20.29 5.70001 21 8.12001 21C16 21 20.33 14.46 20.33 8.79C20.33 8.6 20.33 8.42 20.32 8.23C21.16 7.63 21.88 6.87 22.46 6Z"
                        fill="currentColor"
                    />
                </svg>
            </a>
        </li>
        <li data-aos="fade-up" data-aos-duration="1000" data-aos-delay="400">
            <a
                class="text-body transition hover:text-gray-300"
                aria-label="Social Article"
                aria-hidden="true"
                target="_blank"
                href="https://www.reddit.com/submit?title={{ $titleEncoded }}&url={{ $shareUrl }}"
                title=""
                rel=""
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path
                        d="M14.5 15.41C14.58 15.5 14.58 15.69 14.5 15.8C13.77 16.5 12.41 16.56 12 16.56C11.61 16.56 10.25 16.5 9.54 15.8C9.44 15.69 9.44 15.5 9.54 15.41C9.65 15.31 9.82 15.31 9.92 15.41C10.38 15.87 11.33 16 12 16C12.69 16 13.66 15.87 14.1 15.41C14.21 15.31 14.38 15.31 14.5 15.41ZM10.75 13.04C10.75 12.47 10.28 12 9.71 12C9.14 12 8.67 12.47 8.67 13.04C8.67 13.61 9.14 14.09 9.71 14.08C10.28 14.08 10.75 13.61 10.75 13.04ZM14.29 12C13.72 12 13.25 12.5 13.25 13.05C13.25 13.6 13.72 14.09 14.29 14.09C14.86 14.09 15.33 13.61 15.33 13.05C15.33 12.5 14.86 12 14.29 12ZM22 12C22 17.5 17.5 22 12 22C6.5 22 2 17.5 2 12C2 6.5 6.5 2 12 2C17.5 2 22 6.5 22 12ZM18.67 12C18.67 11.19 18 10.54 17.22 10.54C16.82 10.54 16.46 10.7 16.2 10.95C15.2 10.23 13.83 9.77 12.3 9.71L12.97 6.58L15.14 7.05C15.16 7.6 15.62 8.04 16.18 8.04C16.75 8.04 17.22 7.57 17.22 7C17.22 6.43 16.75 5.96 16.18 5.96C15.77 5.96 15.41 6.2 15.25 6.55L12.82 6.03C12.75 6 12.68 6.03 12.63 6.07C12.57 6.11 12.54 6.17 12.53 6.24L11.79 9.72C10.24 9.77 8.84 10.23 7.82 10.96C7.56 10.71 7.2 10.56 6.81 10.56C6 10.56 5.35 11.21 5.35 12C5.35 12.61 5.71 13.11 6.21 13.34C6.19 13.5 6.18 13.62 6.18 13.78C6.18 16 8.79 17.85 12 17.85C15.23 17.85 17.85 16.03 17.85 13.78C17.85 13.64 17.84 13.5 17.81 13.34C18.31 13.11 18.67 12.6 18.67 12Z"
                        fill="currentColor"
                    />
                </svg>
            </a>
        </li>
        <li data-aos="fade-up" data-aos-duration="1000" data-aos-delay="500">
            <a
                class="text-body transition hover:text-gray-300"
                aria-label="Social Article"
                aria-hidden="true"
                href="https://www.linkedin.com/sharing/share-offsite?mini=true&url={{ $shareUrl }}&title={{ $titleEncoded }}&summary={{ $excerptEncoded }}"
                title=""
                rel=""
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path
                        d="M19 3C19.5304 3 20.0391 3.21071 20.4142 3.58579C20.7893 3.96086 21 4.46957 21 5V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H19ZM18.5 18.5V13.2C18.5 12.3354 18.1565 11.5062 17.5452 10.8948C16.9338 10.2835 16.1046 9.94 15.24 9.94C14.39 9.94 13.4 10.46 12.92 11.24V10.13H10.13V18.5H12.92V13.57C12.92 12.8 13.54 12.17 14.31 12.17C14.6813 12.17 15.0374 12.3175 15.2999 12.5801C15.5625 12.8426 15.71 13.1987 15.71 13.57V18.5H18.5ZM6.88 8.56C7.32556 8.56 7.75288 8.383 8.06794 8.06794C8.383 7.75288 8.56 7.32556 8.56 6.88C8.56 5.95 7.81 5.19 6.88 5.19C6.43178 5.19 6.00193 5.36805 5.68499 5.68499C5.36805 6.00193 5.19 6.43178 5.19 6.88C5.19 7.81 5.95 8.56 6.88 8.56ZM8.27 18.5V10.13H5.5V18.5H8.27Z"
                        fill="currentColor"
                    />
                </svg>
            </a>
        </li>
        <li data-aos="fade-up" data-aos-duration="1000" data-aos-delay="600">
            <a
                class="text-body transition hover:text-gray-300"
                aria-label="Social Article"
                aria-hidden="true"
                target="_blank"
                href="https://wa.me/?text={{ $shareUrl }}"
                title=""
                rel=""
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path
                        d="M12.04 2C6.58005 2 2.13005 6.45 2.13005 11.91C2.13005 13.66 2.59005 15.36 3.45005 16.86L2.05005 22L7.30005 20.62C8.75005 21.41 10.38 21.83 12.04 21.83C17.5 21.83 21.9501 17.38 21.9501 11.92C21.9501 9.27 20.92 6.78 19.05 4.91C17.18 3.03 14.69 2 12.04 2ZM12.05 3.67C14.25 3.67 16.31 4.53 17.87 6.09C19.42 7.65 20.2801 9.72 20.2801 11.92C20.2801 16.46 16.58 20.15 12.04 20.15C10.56 20.15 9.11005 19.76 7.85005 19L7.55005 18.83L4.43005 19.65L5.26005 16.61L5.06005 16.29C4.24005 15 3.80005 13.47 3.80005 11.91C3.81005 7.37 7.50005 3.67 12.05 3.67ZM8.53005 7.33C8.37005 7.33 8.10005 7.39 7.87005 7.64C7.65005 7.89 7.00005 8.5 7.00005 9.71C7.00005 10.93 7.89005 12.1 8.00005 12.27C8.14005 12.44 9.76005 14.94 12.25 16C12.84 16.27 13.3 16.42 13.66 16.53C14.25 16.72 14.79 16.69 15.22 16.63C15.7 16.56 16.68 16.03 16.89 15.45C17.1 14.87 17.1 14.38 17.04 14.27C16.97 14.17 16.81 14.11 16.56 14C16.31 13.86 15.09 13.26 14.87 13.18C14.64 13.1 14.5 13.06 14.31 13.3C14.15 13.55 13.67 14.11 13.53 14.27C13.38 14.44 13.24 14.46 13 14.34C12.74 14.21 11.94 13.95 11 13.11C10.26 12.45 9.77005 11.64 9.62005 11.39C9.50005 11.15 9.61005 11 9.73005 10.89C9.84005 10.78 10 10.6 10.1 10.45C10.23 10.31 10.27 10.2 10.35 10.04C10.43 9.87 10.39 9.73 10.33 9.61C10.27 9.5 9.77005 8.26 9.56005 7.77C9.36005 7.29 9.16005 7.35 9.00005 7.34C8.86005 7.34 8.70005 7.33 8.53005 7.33Z"
                        fill="currentColor"
                    />
                </svg>
            </a>
        </li>
        <li data-aos="fade-up" data-aos-duration="1000" data-aos-delay="700">
            <a
                class="text-body transition hover:text-gray-300"
                aria-label="Social Article"
                aria-hidden="true"
                target="_blank"
                href="mailto:?subject={{ $titleEncoded }}&body={{ str_replace('+', '%20', urlencode_deep($shareUrl)) }}"
                title=""
                rel=""
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path
                        d="M22 6C22 4.9 21.1 4 20 4H4C2.9 4 2 4.9 2 6V18C2 19.1 2.9 20 4 20H20C21.1 20 22 19.1 22 18V6ZM20 6L12 11L4 6H20ZM20 18H4V8L12 13L20 8V18Z"
                        fill="currentColor"
                    />
                </svg>
            </a>
        </li>
        <li class="" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="800">
            <button
                type="button"
                class="text-body transition hover:text-gray-300"
                aria-label="Social Article"
                aria-hidden="true"
                x-on:click="
                    navigator.clipboard.writeText(shareUrl)
                    showTooltip = true
                    setTimeout(function () {
                        showTooltip = false
                    }, 2000)
                "
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path
                        d="M19 21H8V7H19V21ZM19 5H8C7.46957 5 6.96086 5.21071 6.58579 5.58579C6.21071 5.96086 6 6.46957 6 7V21C6 21.5304 6.21071 22.0391 6.58579 22.4142C6.96086 22.7893 7.46957 23 8 23H19C19.5304 23 20.0391 22.7893 20.4142 22.4142C20.7893 22.0391 21 21.5304 21 21V7C21 6.46957 20.7893 5.96086 20.4142 5.58579C20.0391 5.21071 19.5304 5 19 5ZM16 1H4C3.46957 1 2.96086 1.21071 2.58579 1.58579C2.21071 1.96086 2 2.46957 2 3V17H4V3H16V1Z"
                        fill="currentColor"
                    />
                </svg>
            </button>
            <div
                x-cloak
                x-show="showTooltip"
                x-transition
                role="tooltip"
                class="bg-light-gray tooltip absolute z-10 inline-block rounded-lg px-3 py-2 text-sm font-medium text-body shadow-sm"
            >
                {{ __('Copied!', 'sage') }}
            </div>
        </li>
    </ul>
    {{--
        <div
        class="navigator-share hidden">
        <button type="button" class="bg-light-blue text-body4 py-2 text-sm font-bold transition rounded-md min-w bg-opacity-100 hover:bg-opacity-60"
        x-on:click="
        if (navigator.share){
        navigator.share({
        title: title,
        url: shareUrl
        })
        .then(function() {
        console.log('Successful share');
        })
        .catch(function(error) {
        console.log('Error sharing:', error);
        });
        }
        ">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M17 8h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-9a2 2 0 0 1 2-2h2v2H5v9h14v-9h-2V8zM6.5 5.5l1.414 1.414L11 3.828V14h2V3.828l3.086 3.086L17.5 5.5L12 0L6.5 5.5z"/></svg>              <span class="ml-2">Share</span>
        </button>
        </div>
    --}}
</div>
