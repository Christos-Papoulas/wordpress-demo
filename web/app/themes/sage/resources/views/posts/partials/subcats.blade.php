<section class="py-10">
    <div class="ht-container">
        <template x-for="facete in facetes">
            <div class="">
                <template x-if="facete.terms && facete.terms.length && facete.type == 'taxonomy'">
                    <div class="grid gap-y-12 lg:grid-cols-2 lg:gap-6 xl:gap-28">
                        <template x-for="term in facete.terms">
                            <div class="flex flex-wrap text-center">
                                <div class="w-full">
                                    <div class="py-2 text-3xl font-black" x-text="term.name"></div>
                                    {{-- <div class="text-6xl font-extralight py-2">ΜΟΝΤΕΡΝΕΣ</div> --}}
                                    <div class="mx-auto max-w-lg py-7 text-sm font-light">
                                        <p x-text="term.description"></p>
                                    </div>
                                    <div>
                                        <a :href="term.url" class="outlined-btn-dark inline-block text-xs">
                                            {{ __('MORE', 'sage') }}
                                        </a>
                                    </div>
                                </div>

                                <div class="mt-16 flex w-full items-end">
                                    <div class="flex flex-wrap justify-center gap-6 xl:gap-y-28">
                                        <a
                                            :href="term.url"
                                            class="group relative block w-full transition-all duration-200 ease-linear"
                                        >
                                            <img
                                                :src="term.thumbnail"
                                                :alt="term.name"
                                                class="mx-auto w-full object-cover lg:w-auto"
                                            />
                                            <div
                                                class="absolute top-1/2 left-1/2 h-5/6 w-10/12 -translate-x-1/2 -translate-y-1/2 bg-black opacity-0 bg-blend-multiply transition-opacity duration-400 ease-linear group-hover:opacity-50"
                                            >
                                                <div class="flex h-full items-center justify-center">
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        width="85.039"
                                                        height="85.039"
                                                        viewBox="0 0 85.039 85.039"
                                                    >
                                                        <g
                                                            id="Group_15952"
                                                            data-name="Group 15952"
                                                            transform="translate(0 -0.001)"
                                                        >
                                                            <rect
                                                                id="Rectangle_8006"
                                                                data-name="Rectangle 8006"
                                                                width="2"
                                                                height="85.039"
                                                                transform="translate(41.52 0.001)"
                                                                fill="#fff"
                                                            ></rect>
                                                            <rect
                                                                id="Rectangle_8007"
                                                                data-name="Rectangle 8007"
                                                                width="85.039"
                                                                height="2.001"
                                                                transform="translate(0 41.519)"
                                                                fill="#fff"
                                                            ></rect>
                                                        </g>
                                                    </svg>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </template>
    </div>
</section>
