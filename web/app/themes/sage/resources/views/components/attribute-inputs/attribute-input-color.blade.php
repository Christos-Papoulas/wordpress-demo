<div 
x-data="customAttributeInput( {  inputName: '{{ $inputName }}', 
    options: JSON.parse(
        atob('{{ base64_encode(json_encode($transformedOptions)) }}'),
    ),
})"
class="flex flex-wrap justify-between">
    
    <div class="w-full label text-xs text-body font-normal my-4">
        {!! __('Selection') . ' ' . $attributeLabel !!}
        <span x-text="`: ${selectedLabel}`"></span>
    </div>

    <ul class="w-full flex flex-wrap list-none mb-0 ml-0 space-x-3">
        <template x-for="option in options"  :key="option.termId">
            <li 
            x-on:click.prevent.stop="select(option.termSlug)"
            :class="{ 'border-black' : selected === option.termSlug , 'not-available' : option.available === false }"
            class="relative cursor-pointer border border-[#e5e7eb] px-0.5 py-0.5 w-7.5 h-7.5 flex justify-center items-center text-body text-xs transition duration-200 ease-in-out hover:border-black">
                <div :style="`background:${option.background};`" style="background-size:100%; background-repeat:no-repeat; background-position:center;" class="h-full w-full block bg-no-repeat relative group">
                    <div role="tooltip" style="top:calc(-100% + 6px); left:0;" class="group-hover:opacity-100 opacity-0 group-hover:inline-block hidden absolute z-10 px-3 py-2 text-xs text-white transition-opacity duration-300 bg-black shadow-sm tooltip ">
                        <span x-text="option.termName"></span>
                    </div>
                </div>
            </li>
        </template>
    </ul>
</div>
