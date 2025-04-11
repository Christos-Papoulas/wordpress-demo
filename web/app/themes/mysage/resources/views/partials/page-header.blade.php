@if ($showBreadcrumbs)
    @include('partials.breadcrumbs', ['breadcrumbsContainer' => $breadcrumbsContainer])
@endif

@if ($showPageTitle)
    <div class="{{ $pageTitleContainer }} page-header">
        <h1 class="flex text-xs font-bold lg:text-3xl lg:font-normal pt-4 lg:pt-7 mb-8 lg:mb-12 uppercase">{!! $title !!}</h1>
    </div>
@endif
