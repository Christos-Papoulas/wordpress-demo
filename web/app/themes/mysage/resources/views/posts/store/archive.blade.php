@include('partials.breadcrumbs', ['breadcrumbsContainer' => 'ht-container-medium'])
<div class="ht-container-medium page-header">
    <h1 class="flex text-xs font-bold lg:text-3xl lg:font-normal pt-4 lg:pt-7 mb-8 lg:mb-12 uppercase"> {{ __('STORES', 'sage') }}</h1>
</div>

<div class="ht-container-medium">
    <div class="text-body w-full">
        <section
            x-data="storelocator({
                postType : '{{ $postType }}',
                getSubcats : 0,
                term : JSON.parse( atob('{{ base64_encode(json_encode($taxonomyTerm)) }}') ),
                lang : '{{ get_locale() }}'
            })"
            data-layout="1"
            class="relative pb-16"
            id="alpine-posts-archive"
        >
            <div>
                <div class="mb-9 grid grid-cols-1 gap-4 lg:grid-cols-2">
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg
                                class="h-5 w-5"
                                width="20"
                                height="20"
                                viewBox="0 0 20 20"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M9.8435 18.062C14.6241 18.062 18.4995 14.1866 18.4995 9.406C18.4995 4.62542 14.6241 0.75 9.8435 0.75C5.06292 0.75 1.1875 4.62542 1.1875 9.406C1.1875 14.1866 5.06292 18.062 9.8435 18.062Z"
                                    stroke="#212121"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                ></path>
                                <path
                                    d="M16.1094 15.501L19.1864 18.749"
                                    stroke="#212121"
                                    stroke-linecap="square"
                                    stroke-linejoin="round"
                                ></path>
                            </svg>
                        </div>
                        <input
                            id="google-autocomplete-input"
                            type="text"
                            class="border-primary w-full border py-3 pr-3 pl-10 text-center placeholder:text-xs focus:border-gray-400 focus:ring focus:outline-none"
                            placeholder="{{ __('ENTER CITY OR POST CODE', 'sage') }}"
                        />
                    </div>
                    <div class="relative">
                        <button
                            type="button"
                            x-on:click="findNearest()"
                            class="w-full border border-[#68b670] bg-[#68b670] py-3 text-center"
                        >
                            <div class="pointer-events-none absolute inset-y-0 left-3 flex items-center pl-3">
                                <svg
                                    width="19"
                                    height="19"
                                    viewBox="0 0 19 19"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        d="M9.67163 18.4048C14.4522 18.4048 18.3276 14.5294 18.3276 9.74877C18.3276 4.9682 14.4522 1.09277 9.67163 1.09277C4.89105 1.09277 1.01562 4.9682 1.01562 9.74877C1.01562 14.5294 4.89105 18.4048 9.67163 18.4048Z"
                                        stroke="white"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    ></path>
                                    <path
                                        d="M14.0391 5.61133L5.30465 13.8859"
                                        stroke="white"
                                        stroke-linecap="square"
                                        stroke-linejoin="round"
                                    ></path>
                                    <path
                                        d="M9.67188 12.249C11.0526 12.249 12.1719 11.1297 12.1719 9.74902C12.1719 8.36831 11.0526 7.24902 9.67188 7.24902C8.29116 7.24902 7.17188 8.36831 7.17188 9.74902C7.17188 11.1297 8.29116 12.249 9.67188 12.249Z"
                                        fill="#68B670"
                                        stroke="white"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    ></path>
                                </svg>
                            </div>
                            <div class="text-white">
                                {{ __('FIND THE NEAREST STORE', 'sage') }}
                            </div>
                        </button>
                    </div>
                </div>
            </div>
            <div class="ht-container-large my-12 h-px w-full bg-slate-200"></div>

            <div class="grid grid-cols-1 gap-3 pb-7 lg:grid-cols-3">
                <div>
                    @include('posts.store.partials.posts')
                </div>
                <div class="w-full lg:col-span-2">
                    <div id="locations-map" class="h-full"></div>
                </div>
            </div>
        </section>
        <div class="ht-container-large mb-20 h-px w-full bg-slate-200"></div>
    </div>
</div>
