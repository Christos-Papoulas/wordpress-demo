<div x-data="{open:false, action:'insert'}" 
x-init="
document.addEventListener('closeAllModals', (event) => {
    open = false;
});
document.addEventListener('openWishlistNotification', (event) => {
    action = event?.detail?.action;
    document.body.classList.add('body-no-scroll');
    document.getElementById('backdrop').classList.remove('hidden');
    open = true;
});
"
:class="open && '!translate-x-0 !translate-y-0'"
class="transform translate-y-full xl:translate-y-0 xl:translate-x-[calc(100%_+_24px)] transition w-full xl:max-w-[530px] fixed bottom-0 left-0 xl:bottom-auto xl:left-auto xl:top-28 xl:right-4 z-[1010] px-3 py-4 xl:px-5 xl:py-5 overflow-y-auto bg-white shadow-md max-h-[50dvh]">
    <div x-cloak x-show="open" class="w-full">
        <div class="text-center text-base items-center text-body pt-5 pb-7" x-text="action == 'remove' ? `{{ __('PRODUCT REMOVED FROM YOUR WISHLIST','sage') }}` : `{{ __('PRODUCT ADDED TO YOUR WISHLIST.','sage') }}`">
        </div>
        <a href="{{ $wishlist_url }}"
            class="btn-md btn-outlined-primary mb-1">
            {{ __('GO TO WISHLIST','sage') }}
        </a>
        <button x-on:click="
            document.dispatchEvent(new CustomEvent('closeAllModals', {detail: {}}));
        "
            class="btn-md btn-solid-primary w-full">
            {{ __('CONTINUE SHOPPING','sage') }}
        </button>
    </div>


</div>
