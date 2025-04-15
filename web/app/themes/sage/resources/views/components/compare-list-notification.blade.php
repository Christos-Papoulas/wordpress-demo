<div
    x-data="{ open: false, action: 'insert' }"
    x-init="
        document.addEventListener('closeAllModals', (event) => {
            open = false
        })
        document.addEventListener('openCompareListNotification', (event) => {
            action = event?.detail?.action
            document.body.classList.add('body-no-scroll')
            document.getElementById('backdrop').classList.remove('hidden')
            open = true
        })
    "
    :class="open && '!translate-x-0 !translate-y-0'"
    class="fixed bottom-0 left-0 z-[1010] max-h-[50dvh] w-full translate-y-full transform overflow-y-auto bg-white px-3 py-4 shadow-md transition xl:top-40 xl:right-4 xl:bottom-auto xl:left-auto xl:max-w-[530px] xl:translate-x-[calc(100%_+_24px)] xl:translate-y-0 xl:px-5 xl:py-5"
>
    <div x-cloak x-show="open" class="w-full">
        <div
            class="text-body items-center pt-5 pb-7 text-center text-base uppercase"
            x-text="
                action == 'remove'
                    ? `{{ __('Product removed from your compare list.', 'sage') }}`
                    : `{{ __('Product added to your compare list.', 'sage') }}`
            "
        ></div>
        <a href="/compare" class="btn-md btn-solid-secondary mb-1">
            {{ __('GO TO COMPARE PAGE', 'sage') }}
        </a>
        <button
            x-on:click="
                document.body.classList.remove('body-no-scroll')
                document.getElementById('backdrop').classList.add('hidden')
                open = false
            "
            class="btn-md btn-solid-primary w-full"
        >
            {{ __('CONTINUE SHOPPING', 'sage') }}
        </button>
    </div>
</div>
