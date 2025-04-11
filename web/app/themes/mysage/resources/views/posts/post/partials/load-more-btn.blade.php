<template x-if="$data.currentPage < $data.maxPage">
    <div class="my-16 flex justify-center lg:my-20">
        <button type="button" x-on:click.debounce="paginate($event.target)" class="btn-md btn-solid-primary w-full uppercase">
            <svg
                class="loader hidden h-5 w-5 animate-spin text-black"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
            >
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path
                    class="opacity-75"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                ></path>
            </svg>
            {{ __('Show more', 'sage') }}
        </button>
    </div>
</template>
