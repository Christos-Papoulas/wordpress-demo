<div class="text-{{ $stockStatus['color'] }} flex items-center gap-2">
    <div class="border-{{ $stockStatus['color'] }} flex h-5 w-5 items-center justify-center rounded-full border">
        <svg class="w-3" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
            <path
                d="M470.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L192 338.7 425.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"
            ></path>
        </svg>
    </div>
    {{ $stockStatus['label'] }}
</div>
