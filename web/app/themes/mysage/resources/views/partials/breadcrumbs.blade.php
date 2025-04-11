<div class="htech-breadcrumb {{ $breadcrumbsContainer ?? 'ht-container-no-max-width' }} flex w-full items-center">
    @php
        do_action('htech_breadcrumbs');
    @endphp
</div>
