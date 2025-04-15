@php
    if ($product->is_type('variable')) {
        $variation_ids = $product->get_children();
    }
@endphp

@if (! empty($variation_ids))
    <div
        class="border-primary relative mb-5 max-h-[500px] overflow-x-hidden overflow-y-scroll rounded-lg border px-4 pb-5"
    >
        <div
            class="variatios-overview sticky top-0 mb-1 w-full bg-gradient-to-b from-white from-0% via-white via-87% to-transparent py-2 font-medium"
        >
            {{ __('Models', 'sage') }}
            <span class="font-normal" style="font-size: 0.8em">
                {{ __('(Click to show availability)', 'sage') }}
            </span>
        </div>
        <div class="grid gap-4 text-xs md:gap-1">
            @foreach ($variation_ids as $variation_id)
                @php
                    $variation_obj = new WC_Product_Variation($variation_id);
                    if (! $variation_obj->is_purchasable()) {
                        continue;
                    }
                    switch ($variation_obj->is_in_stock()) {
                        case true:

                            if ($variation_obj->get_stock_quantity() <= 0) {
                                $t = __('on backorder', 'sage');
                                $b = 'bg-[#F79E1B]';
                                $only_store = false;
                            } else {
                                $t = __('in stock', 'sage');
                                $b = 'bg-green-500';
                                $only_store = false;
                            }

                            break;
                        case false:
                            $b = 'bg-red-500';
                            if (get_post_meta($variation_obj->get_id(), '_ht_stock_label', true) == 'Διαθέσιμος σε επιλεγμένα καταστήματα') {
                                $t = '';
                                $only_store = true;
                            } else {
                                $t = __('out of stock', 'sage');
                                $only_store = false;
                            }
                            break;
                        case 'onbackorder':
                            $t = __('on backorder', 'sage');
                            $b = 'bg-[#F79E1B]';
                            $only_store = false;
                            break;
                        default:
                            $t = __('out of stock', 'sage');
                            $b = 'bg-orange-400';
                            $only_store = false;
                    }
                @endphp

                <div class="flex flex-wrap items-center justify-between">
                    <div>
                        <button
                            x-data="{}"
                            type="button"
                            class="hover:text-secondary transition"
                            x-on:click="
                                document.dispatchEvent(
                                    new CustomEvent('refreshStoreStockStatus', {
                                        detail: { productID: <?php echo $variation_id; ?> },
                                    }),
                                )
                            "
                        >
                            <?php
                            $terms_html = [];
                            foreach ($variation_obj->get_attributes() as $tax => $term_slug) {
                                $terms_html[] = get_term_by('slug', $term_slug, $tax)->name;
                            }
                            ?>

                            {!! implode(', ', $terms_html).', '.$t !!}
                        </button>
                    </div>
                    <div class="flex grow items-center justify-between gap-4 md:justify-end">
                        @if (! $variation_obj->is_in_stock())
                            @if (! $only_store)
                                <div x-data="buttonBackInStockNotify">
                                    <button x-on:click="openModal()" type="button" class="hover:text-secondary">
                                        {{ __('Notify me', 'sage') }}
                                    </button>

                                    <div
                                        :class="open && '!translate-x-0 !translate-y-0'"
                                        class="fixed bottom-0 left-0 z-[1010] max-h-[50dvh] w-full translate-y-full transform overflow-y-auto bg-white px-3 py-4 shadow-md transition xl:top-40 xl:right-4 xl:bottom-auto xl:left-auto xl:max-w-[530px] xl:translate-x-[calc(100%_+_24px)] xl:translate-y-0 xl:px-5 xl:py-5"
                                    >
                                        <div x-cloak x-show="open" class="w-full">
                                            <template x-if="!jobdone">
                                                <div class="text-body items-center pt-5 pb-7 text-center text-base">
                                                    {{ __('Enter your email and we will notify you when the product is in stock.', 'sage') . ' ' }}
                                                    <span class="font-semibold">
                                                        {{ strip_tags($product->name) }}
                                                        {!! strip_tags(implode(', ', $terms_html)) !!}
                                                    </span>
                                                    {{  ' ' . __('is available.', 'sage') }}
                                                </div>
                                            </template>
                                            <template x-if="jobdone">
                                                <div class="text-body items-center pt-5 pb-7 text-center text-base">
                                                    {{ __('Thank you, we will notify you.', 'sage') }}
                                                </div>
                                            </template>
                                            <template x-if="!jobdone">
                                                <p class="form-row ht-custom">
                                                    <input
                                                        x-model="userEmail"
                                                        type="email"
                                                        value=""
                                                        placeholder="name@email.com"
                                                        :class="error && 'border-red-600'"
                                                    />
                                                </p>
                                            </template>
                                            <template x-if="error">
                                                <div
                                                    class="mb-5 text-xs text-red-600 lg:text-sm"
                                                    x-text="error"
                                                ></div>
                                            </template>
                                            <button
                                                x-on:click="closeModal()"
                                                class="btn-md btn-solid-secondary mb-1 w-full"
                                            >
                                                {{ __('CONTINUE SHOPPING', 'sage') }}
                                            </button>
                                            <template x-if="!jobdone">
                                                <button
                                                    x-on:click.debounce="addRecord({{ $variation_id }})"
                                                    class="btn-md btn-solid-primary !bg-primary w-full !text-white"
                                                >
                                                    <span x-show="!loading">
                                                        {{ __('NOTIFY ME', 'sage') }}
                                                    </span>
                                                    <svg
                                                        x-cloak
                                                        x-show="loading"
                                                        class="pointer-events-none h-4 w-4 animate-spin text-white"
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
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <span>{{ __('Available only in store', 'sage') }}</span>
                            @endif
                        @endif

                        <div class="flex items-center gap-4">
                            <div>{!! wc_price($variation_obj->get_price()) !!}</div>
                            <div class="{{ $b }} h-2.5 w-2.5"></div>
                        </div>
                    </div>
                </div>
            @endforeach

            @php
                unset($t);
                unset($b);
                unset($only_store);
            @endphp
        </div>
    </div>
@endif
