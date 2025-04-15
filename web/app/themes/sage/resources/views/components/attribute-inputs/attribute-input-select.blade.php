<div 
x-data="customAttributeInput( {  inputName: '{{ $inputName }}', 
    options: JSON.parse(
        atob('{{ base64_encode(json_encode($transformedOptions)) }}'),
    ),
})"
class="flex flex-wrap justify-between">
    
    <div class="w-full label text-xs text-body font-normal my-4">
        {!!  __('Selection') . ' ' . $attributeLabel !!}
        <span x-text="`: ${selectedLabel}`"></span>
    </div>

    <select 
        x-on:change.prevent.stop="select($event.target.value)"
        class="btn-md btn-outlined-primary w-full uppercase text-center text-sm font-semibold">
        <option :selected="selected === ''" value="">{!! __( 'Choose an option', 'woocommerce' ) !!}</option>
        <template x-for="option in options"  :key="option.termId">
            <option 
            :selected="selected === option.termSlug"
            :value="option.termSlug"
            :disabled="option.available === false"
            x-text="option.termName">
            </option>
        </template>
    </select>
</div>
