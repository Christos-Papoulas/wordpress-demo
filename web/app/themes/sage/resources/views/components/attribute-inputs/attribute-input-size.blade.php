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

    <ul class="w-full @if(count($transformedOptions) === 2) grid grid-cols-2 gap-4 @else flex flex-wrap @endif list-none mb-0 ml-0 space-x-3">
        <template x-for="option in options" :key="option.termId">
            <li 
            x-on:click.prevent.stop="select(option.termSlug)"
            :class="{ 'border-black' : selected === option.termSlug , 'not-available' : option.available === false }"
            class="relative cursor-pointer border border-[#e5e7eb] px-2 py-0.5 @if(count($transformedOptions) === 2) w-full @else w-7.5 @endif h-7.5 flex justify-center items-center text-body text-xs uppercase transition duration-200 ease-in-out hover:border-black">
                <span x-text="option.termName"></span>
            </li>
        </template>
    </ul>
</div>
