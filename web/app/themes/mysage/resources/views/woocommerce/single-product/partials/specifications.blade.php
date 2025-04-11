<div class="mb-3 w-full bg-[#FAFAFA] px-4">
    <div class="inline-flex w-full items-center justify-center">
        <hr class="bg-grey-main my-8 h-0.5 w-14 border-0" />
        <div class="px-6 text-center text-2xl">
            {{ __('Specifications', 'sage') }}
        </div>
        <hr class="bg-grey-main my-8 h-0.5 w-14 border-0" />
    </div>
    <div>
        <table class="tg">
            <tbody></tbody>
        </table>
        <table class="w-full text-base leading-8 text-black">
            <tbody>
                @if ($product->has_weight())
                    <tr class="border-grey-light border-b">
                        <td>Weight</td>
                        <td class="text-right">
                            {{ $product->get_weight().' '.get_option('woocommerce_weight_unit') }}
                        </td>
                    </tr>
                @endif

                @if ($product->has_dimensions())
                    <tr class="border-grey-light border-b">
                        <td>Dimensions</td>
                        <td class="text-right">
                            {{ $product->get_length().' x '.$product->get_width().' x '.$product->get_height().' '.get_option('woocommerce_dimension_unit') }}
                        </td>
                    </tr>
                @endif

                @foreach ($productAttributes as $attr)
                    <tr class="border-grey-light border-b">
                        <td>{!! $attr['label_name'] !!}</td>
                        <td class="text-right">{!! $attr['options_labels'] !!}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
