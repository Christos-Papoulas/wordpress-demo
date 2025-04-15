<section
    class="@if(isset($block->classes)){{ $block->classes }}@endif pb-12 xl:pb-20"
    @if($accordion['is_faq']) itemscope itemtype="https://schema.org/FAQPage" @endif
>
    <div class="ht-container-large w-full">
        @if (! empty($accordion['title']))
            <h2
                class="mb-16 text-5xl font-bold lg:text-6xl xl:text-7xl"
                data-aos="zoom-in-right"
                data-aos-duration="1000"
            >
                {!! strip_tags($accordion['title'], ['<br>', '<div>', '<span>', '<sub>', '<sup>']) !!}
            </h2>
        @endif

        <div>
            <h3 class="lg:text-xl-bold text-dark-gray ht-stront-as-white mb-0 text-lg">
                {!! strip_tags($accordion['subtitle'], ['<br>', '<div>', '<strong>', '<em>']) !!}
            </h3>

            @if (! empty($accordion['accordion']))
                <div
                    class="accordion-wrapper"
                    x-data="{
                        openRow: 0,
                        lastVisible: 6,
                        count: {{ count($accordion['accordion']) }},
                    }"
                >
                    <div class="accordion-inner-wrapper">
                        @foreach ($accordion['accordion'] as $key => $row)
                            <div
                                class="@if($key > 6) hidden @endif border-b border-gray-400"
                                data-aos="fade-up"
                                data-aos-duration="500"
                                data-aos-delay="{{ 0 + (($key + 1) * 50) }}"
                                @if($accordion['is_faq']) itemscope itemtype="https://schema.org/Question" itemprop="mainEntity" @endif
                            >
                                <button
                                    x-on:click="
                                        if (openRow == {{ $key }}) {
                                            openRow = null
                                        } else {
                                            openRow = {{ $key }}
                                        }
                                    "
                                    class="text-body hover:text-body flex w-full items-center gap-x-4 py-5 text-left text-lg font-extrabold lg:text-xl xl:text-2xl"
                                >
                                    <div>
                                        <span :class="openRow == {{ $key }} ? '' : 'hidden'">
                                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                                                <path
                                                    d="M16.5 11H11H5.5"
                                                    stroke="currentColor"
                                                    stroke-width="4"
                                                    stroke-linecap="square"
                                                    stroke-linejoin="round"
                                                />
                                            </svg>
                                        </span>
                                        <span :class="openRow == {{ $key }} ? 'hidden' : ''">
                                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none">
                                                <path
                                                    d="M11 5.57129V10.8213M11 10.8213V16.0713M11 10.8213H16.5M11 10.8213H5.5"
                                                    stroke="currentColor"
                                                    stroke-width="4"
                                                    stroke-linecap="square"
                                                    stroke-linejoin="round"
                                                />
                                            </svg>
                                        </span>
                                    </div>
                                    <span @if($accordion['is_faq']) itemprop="name" @endif>
                                        {{ $row['title'] }}
                                    </span>
                                </button>
                                <div
                                    class="pr-8 pb-8 text-sm font-semibold lg:text-base"
                                    x-show="openRow == {{ $key }}"
                                    @if($accordion['is_faq']) itemscope itemtype="https://schema.org/Answer" itemprop="acceptedAnswer" @endif
                                >
                                    <div class="mb-0" @if($accordion['is_faq']) itemprop="text" @endif>
                                        {!! strip_tags($row['content'], ['<br>', '<div>', '<span>', '<strong>', '<p>', '<ul>', '<ol>', '<li>', '<a>', '<sub>', '<sup>', '<blockquote>']) !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if (count($accordion['accordion']) > 7)
                        <div class="mt-12 flex w-full items-center justify-center">
                            <button
                                x-on:click="
                                    let button = $event.currentTarget
                                    let loader = button.querySelector('.loader')
                                    button.disabled = true
                                    loader.classList.remove('hidden')
                                    let container = button.parentNode.parentNode
                                    let accordion = container.querySelector('.accordion-inner-wrapper')

                                    let startIndex = Number(lastVisible) + Number(1)
                                    let endIndex = Number(lastVisible) + Number(10)
                                    let selectedChildren = []

                                    if (accordion && accordion.children) {
                                        for (let i = startIndex; i <= endIndex; i++) {
                                            selectedChildren.push(accordion.children[i])
                                        }
                                    }
                                    selectedChildren.forEach(function (child) {
                                        child?.classList.remove('hidden')
                                    })

                                    lastVisible = endIndex
                                    loader.classList.add('hidden')
                                    button.disabled = false
                                "
                                x-cloak
                                x-show="Number(lastVisible) + Number(1) < count"
                                class="btn-solid-primary min-w bg-opacity-60 hover:bg-opacity-100 mx-auto flex w-48 items-center justify-center gap-x-4 rounded-md px-6 py-4 text-sm font-bold text-black transition disabled:cursor-not-allowed"
                            >
                                <svg
                                    class="loader hidden h-5 w-5 animate-spin text-black"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <circle
                                        class="opacity-25"
                                        cx="12"
                                        cy="12"
                                        r="10"
                                        stroke="currentColor"
                                        stroke-width="4"
                                    ></circle>
                                    <path
                                        class="opacity-75"
                                        fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                    ></path>
                                </svg>
                                <span>{{ __('Show more', 'sage') }}</span>
                            </button>
                        </div>
                    @endif

                    <div class="mt-20 mb-24 flex flex-wrap items-center gap-5 md:justify-center lg:mb-28 xl:mb-36">
                        <h3 class="mb-0 text-3xl font-extrabold lg:text-4xl xl:text-5xl">
                            {!! strip_tags($accordion['after_accordion_content'], ['<br>', '<div>', '<span>', '<sub>', '<sup>']) !!}
                        </h3>
                        @if (! empty($accordion['after_accordion_link']))
                            <a
                                href="{{ $accordion['after_accordion_link']['url'] }}"
                                target="{{ $accordion['after_accordion_link']['target'] }}"
                                class="btn-md btn-solid-primary"
                            >
                                {{ $accordion['after_accordion_link']['title'] }}
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
